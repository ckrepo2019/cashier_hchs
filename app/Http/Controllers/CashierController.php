<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\CashierModel;
use DB;
use NumConvert;
use PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use TCPDF;


class CashierController extends Controller
{
  public function __construct()
  {
      // $this->middleware('auth');
  }

	public function index()
	{
		// $account = db::table('account_account')
		// 		->get();

		// return $account;		
    // return config('app.type');
		return view('cash');
	}

  public function studlist(Request $request)
  {
  	if($request->ajax())
  	{
  		$query = $request->get('query');

  		$student = db::table('studinfo')
  				->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelid', 'contactno', 'sid', 'lrn', 'gradelevel.levelname', 'grantee.description as grantee', 'sectionname', 'studentstatus.description as studentstatus', 'studinfo.studstatus')
  				->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
          ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
          ->join('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
  				->where('lastname', 'like', '%'. $query . '%')
  				->where('studinfo.deleted', 0)
  				->orWhere('firstname', 'like', '%'. $query . '%')
  				->where('studinfo.deleted', 0)
  				->orWhere('sid', 'like', '%'. $query . '%')
  				->where('studinfo.deleted', 0)
  				->orWhere('rfid', 'like', '%'. $query . '%')
  				->where('studinfo.deleted', 0)
          // ->orderby('gradelevel.sortid', 'asc')
  				->orderby('lastname', 'asc')
  				->orderby('firstname', 'asc')
  				->get();
  		
  		$output = '';

      $esc = '';
      $colorstat = '';
      
  		foreach($student as $stud)
  		{
        if($stud->studstatus == 1)
        {
          $colorstat = 'text-success';
        }
        elseif($stud->studstatus == 2)
        {
          $colorstat = 'text-primary';
        }
        elseif($stud->studstatus == 3)
        {
          $colorstat = 'text-danger';
        }
        elseif($stud->studstatus == 4)
        {
          $colorstat = 'text-warning';
        }
        elseif($stud->studstatus == 5)
        {
          $colorstat = 'text-secondary';
        }
        elseif($stud->studstatus == 6)
        {
          $colorstat = 'text-orange';
        }

  			$output .= '
  				<tr class="client-line '.$colorstat.'" data-id="'.$stud->id.'" data-name="'.strtoupper($stud->lastname.', ' .$stud->firstname . ' ' .$stud->middlename . ' ' . $stud->suffix).'" data-level="'.$stud->levelname.'">
            <td>'.$stud->sid.'</td>
            <td>'.strtoupper($stud->lastname.' ' .$stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix).'</td>
            <td>'.$stud->levelname.'</td>
            <td>'.$stud->sectionname.'</td>
            <td class="">'.$stud->grantee.'</td>
            <td class="">'.$stud->studentstatus.'</td>
          </tr>
  			';
  		}

  		$data = array(
  			'output' => $output
  		);

  		echo json_encode($data);
  	}
  }

  public function fixSched($studid)
  {
    $syid = CashierModel::getSYID();
    $semid = CashierModel::getSemID();
    
    $paysched = db::table('studpayscheddetail')
        ->where('syid', $syid)
        ->where('semid', $semid)
        ->where('studid', $studid)
        ->get();
    
    foreach($paysched as $pay)
    {
      $bal = $pay->amount - $pay->amountpay;

      if($bal != $pay->balance)
      {
        $upd = db::table('studpayscheddetail')
            ->where('id', $pay->id)
            ->update([
              'balance' => $bal,
              'updateddatetime' => CashierModel::getServerDateTime(),
              'updatedby' => auth()->user()->id
            ]);
      }
    }

  }

  public function loadpaysched(Request $request)
  {
    if($request->ajax())
    {
      $tnum = CashierModel::getTransNo();      
      $studid = $request->get('studid');

      $syid = CashierModel::getSYID();
      $semid = CashierModel::getSemID();

      $this->fixSched($studid);

      $studinfo = db::table('studinfo')
          ->where('id', $studid)
          ->first();

      $payplan = 0;
      $feesid = $studinfo->feesid;

      if($studinfo->studstatus != 0)
      {
        $payplan = 0;
      }
      else
      {
        $payplan = 1;
      }

      // if($studinfo->levelid == 14 || $studinfo->levelid == 15)
      // {
      //   $studpayscheddetail = db::table('studpayscheddetail')
      //       ->where('studid', $studid)
      //       ->where('syid', $syid)
      //       ->where('semid', $semid)
      //       ->where('deleted', 0)
      //       ->orderBy('duedate', 'asc')
      //       ->get();
      // }
      // else
      // {
      //   $studpayscheddetail = db::table('studpayscheddetail')
      //       ->where('studid', $studid)
      //       ->where('syid', $syid)
      //       ->where('deleted', 0)
      //       ->orderBy('duedate', 'asc')
      //       ->get(); 
      // }

      $classitem = db::table('studpayscheddetail')
        ->select('itemclassification.id', 'itemclassification.description')
        ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
        ->where('studid', $studid)
        ->where('studpayscheddetail.deleted', 0)
        ->where('syid', CashierModel::getSYID())
        ->where('semid', CashierModel::getSemID())
        ->groupBy('classid')
        ->get();

        $citem = '<option value="0">All</option>';
        foreach($classitem as $class)
        {
          $citem .='
            <option value="'.$class->id.'">'.$class->description.'</option>
          ';
        }

      $studpayscheddetail = db::table('studpayscheddetail')
          ->where('studid', $studid)
          ->where('syid', $syid)
          ->where('semid', $semid)
          ->where('deleted', 0)
          ->orderBy('duedate', 'asc')
          ->get();

      


      $monthdue = '';
      $monthno = '';
      $output = '';
      $studstatus = 99;

      $countSched = 0;
      $schedstat = '';

      
      
      foreach($studpayscheddetail as $paysched)
      {

        if($paysched->duedate != '')
        {
          // return $paysched->duedate;
          $dDate = date_create($paysched->duedate);
          $monthdue = strtoupper(date_format($dDate, 'F'));
          $monthno = date_format($dDate, 'n');
          
        }

        if($paysched->classid == 1)
        {
          $class_color = 'cat-tuition';
        }
        elseif($paysched->classid == 8)
        {
          $class_color = 'cat-compfee';
        }
        elseif($paysched->classid == 4)
        {
          $class_color = 'cat-books';
        }
        else
        {
          $class_color = 'cat-oth';
        }

        $m_color = CashierModel::colorcode($monthno);

        $countSched += $paysched->balance;
        if($paysched->amountpay == 0 && $paysched->amount > 0)
        {
          $output .= '
            <article class="product unpaid" class-id="'.$paysched->classid.'" tabindex="0" aria-labelledby="'.$paysched->id.'" month-due="'.$monthdue.'" data-due="'.$paysched->duedate.'" data-value="'.$paysched->amount.'">

              <div class="">
                <div class="paysched-list" style="display:block !important;">
                  '.$paysched->particulars.'     
                </div>
                <div class="monthdue"><b>'. $monthdue .'</b></div>
              </div>
              
              <div class="product-name '.$m_color.'" id="'.$paysched->id.'">
                &#8369; '.number_format($paysched->amount, 2).'
                
              </div>

            </article>
          ';
        }
        elseif($paysched->balance > 0)
        { 
          $output .= '
            <article class="product incomplete" class-id="'.$paysched->classid.'" tabindex="0" aria-labelledby="'.$paysched->id.'" month-due="'.$monthdue.'" data-due="'.$paysched->duedate.'" data-value="'.$paysched->balance.'">
              <div class="">
                
                <div class="paysched-list">
                  '.$paysched->particulars.'
                </div>
                <div class="monthdue"><b>'. $monthdue .'</b></div>
              </div>
              <div class="product-name '.$m_color.'" id="'.$paysched->id.'">
                &#8369; '.number_format($paysched->balance, 2).'
              </div>
            </article>
          '; 
        }
      }

      if(count($studpayscheddetail) == 0)
      {
        
        if($studinfo->studstatus == 0)
        {
          $output = '<div class="text-xl text-center mt-5"><h1>Student not enrolled.</h1></div>';
          $studstatus = 0;
        }
        else
        {
          $output = '<div class="text-xl text-center mt-5"><h1>Student has no payment schedule.</h1></div>'; 
          $studstatus = 1;
        }
      } 
      else
      {
        if($countSched == 0)
        {
          $output = '<div class="text-xl text-center mt-5"><h1>Student has already paid in Full.</h1></div>';
          $studstatus = 1;
        }  
      }

      


      $orderlines = '
        <div class="order-empty">
          <i class="fa fa-shopping-cart" role="img" aria-label="Shopping cart" title="Shopping cart"></i>
          <h1>No data to display</h1>
        </div>
      ';

      // return $studstatus;
      

      $data = array(
        'output' => $output,
        'transno' => $tnum,
        'orderlines' => $orderlines,
        'studstatus' => $studstatus,
        'payplan' => $payplan,
        'feesid' => $feesid,
        'classitem' => $citem
      );

      echo json_encode($data);
    }
  }


  public function cashtrans(Request $request)
  {
    if($request->ajax())
    {
      $dayid = $request->get('dayid');
      $studid = $request->get('studid');
      $payscheddetailid = $request->get('detailid');
      $terminalno = $request->get('terminalno');
      $transno = $request->get('transno');
      $or = $request->get('ornum');
      $monthdue = $request->get('monthdue');
      $classid = $request->get('classid');
      $due = $request->get('duedate');

      // return $due;

      $paydetail = DB::table('studpayscheddetail')
          ->where('id', $payscheddetailid)
          ->first();

      if($due != '')
      {
        $dDate = date_create($due);
        $particulars = $paydetail->particulars . ' - ' . strtoupper(date_format($dDate, 'F'));
      }
      else
      {
        $particulars = $paydetail->particulars; 
      }

      if($paydetail->balance > 0)
      {
        $amount = $paydetail->balance;
      }
      else
      {
        $amount = $paydetail->amount;
      }

      $cashTrans = db::table('chrngcashtrans')
          ->where('payscheddetailid', $payscheddetailid)
          ->where('transno', $transno)
          ->where('deleted', 0)
          ->first();

      if($cashTrans)
      {
        // return $amount  . '-' . $cashTrans->amount;
        $amount -= $cashTrans->amount;

      }

      $syid = CashierModel::getSYID();
      
      $cashtransid = db::table('chrngcashtrans')
          ->insertGetId([
            'payscheddetailid' => $payscheddetailid,
            'classid' => $classid,
            'particulars' => $particulars,
            'itemprice' => $amount,
            'qty' => 1,
            'amount' => $amount,
            'duedate' => $due,
            'studid' => $studid,
            'syid' => $syid,
            'orno' => $or,
            'transno' => $transno,
            'terminalno' => $terminalno,
            'deleted' => 0,
            'transdone' => 0,
            'dayid' => $dayid,
            'transdatetime' => CashierModel::getServerDateTime(),
            'createdby' => auth()->user()->id
          ]);

      

      // return $output;

      $data = array(
        'output' => CashierModel::getOrderLines($transno, $cashtransid)
      );



      echo json_encode($data);

    }
  }

  public function cashtransdel(Request $request)
  {
    if($request->ajax())
    {
      $cashid = $request->get('cashid');
      $transno = $request->get('transno');
      $or = $request->get('ornum');

      $del = db::table('chrngcashtrans')
          ->where('id', $cashid)
          ->update([
            'deleted' => 1
          ]);

      $selItem = db::table('chrngcashtrans')
          ->where('transno', $transno)
          ->where('deleted', 0)
          ->orderBy('id', 'DESC')
          ->first();

      if($selItem)
      {
        $getOrderLines = CashierModel::getOrderLines($transno, $selItem->id);  
      }
      else
      {
        $getOrderLines = CashierModel::getOrderLines($transno, 0);
      }

      $data = array(
        'output' => $getOrderLines
      );

      echo json_encode($data);
    }
  }


  public function viewPayment(Request $request)
  {
    if($request->ajax())
    {
      $transno = $request->get('transno');
      $paytype = $request->get('paytype');

      $cashtrans = DB::table('chrngcashtrans')
          ->where('transno', $transno)
          ->where('deleted', 0)
          ->sum('amount');
      $output = '
        <table class="paymentlines">
          <colgroup>
              <col class="due">
              <col class="tendered">
              <col class="change">
              <col class="method">
              <col class="controls">
          </colgroup>
          <thead>
            <tr class="">
                <th>Due</th>
                <th>Tendered</th>
                <th>Change</th>
                <th>Method</th>
                <th></th>
            </tr>
          </thead>
          <tbody> 
            <tr class="paymentline selected">
              <td class="col-due" data-value="'.$cashtrans.'" data-input="0">'.number_format($cashtrans, 2).' </td>
              <td id="tendered" class="col-tendered edit" data-value="" data-input="1">
                  0.00
              </td>
              <td class="col-change"></td>
              <td id="col-paytype" class="col-name"> CASH </td>
              <td class="delete-button" data-cid="c26" aria-label="Delete" title="Delete">
                  <i class="fa fa-times-circle"></i>
              </td>
            </tr>
          </tbody>
        </table>        

      ';


      // if($paytype == 2)
      // {
      //   $output
      // }

      $data = array(
        'output' => $output
      );

      echo json_encode($data);

    }
  }


  public function performPayment(Request $request)
  {
    if($request->ajax())
    {
      $studid = $request->get('studid');
      $ornum = $request->get('ornum');
      $transDesc = $request->get('transDesc');
      $totalamount = $request->get('totalamount');
      $amountpaid = $request->get('amountpaid');
      $tPaid = $request->get('amountpaid');
      $terminalno = $request->get('terminalno');
      $transby = $request->get('transby');
      $transno = $request->get('transno');
      $dayid = $request->get('dayid');

      $accountno = $request->get('accountno');
      $accountname = $request->get('accountname');
      $bankname = $request->get('bname');
      $chequeno = $request->get('chequeno');
      $chequedate = $request->get('chequedate');
      $creditcardno = $request->get('creditcardno');
      $cardtype = $request->get('cardtype');
      $refno = $request->get('refno');
      $paytype = $request->get('paytype');
      $paytransdate = $request->get('paytransdate');
      $olid = $request->get('olid');
      $adjdate = $request->get('adjdate');

      $_amountpaid = $amountpaid;


      if($adjdate == '')
      {
        $curDT = CashierModel::getServerDateTime();  
      }
      else
      {
        $adjdate = $adjdate . ' 00:00:00';
        $curDT = date_create($adjdate);
        $curDT = date_format($curDT, 'Y-m-d 00:00');
      }

      
      $syID = CashierModel::getSYID();
      $enrollid = 0;

      $custName = '';

      $divItem ='';

      $oltrans = 0;

      if($paytype != 'CASH' || $paytype != 'CHEQUE')
      {
        $oltrans = 1;
      }
      else
      {
        $oltrans = 0;
      }

      // return $ornum;
      //update ORNUM

      $_or = db::table('orcounter')
        ->where('ornum', $ornum)
        ->first();

      if($_or)
      {
        $updOR = db::table('orcounter')
            ->where('ornum', $ornum)
            ->update([
              'used' => 1
            ]);  
      }
      else
      {
        $insOR = db::table('orcounter')
          ->insert([
            'ornum' => $ornum,
            'terminalno' => $terminalno,
            'used' => 1,
            'createddatetime' => CashierModel::getServerDateTime(),
            'oltrans' => $oltrans
          ]);
      }

      
      //update ORNUM

      if($studid != '')
      {
        $stud = db::table('studinfo')
            ->select('studinfo.*', 'gradelevel.levelname')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('studinfo.id', $studid)
            ->first();

        $studname = $stud->lastname. ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix;
        $gradesection = $stud->levelname. ' - ' . $stud->sectionname;
        $sid = $stud->sid;
            
        if($stud->levelid == 14 || $stud->levelid == 15)
        {
          $enrollstud = db::table('sh_enrolledstud')
              ->where('studid', $studid)
              ->where('syid', $syID)
              ->first();
        }
        else
        {
          $enrollstud = db::table('enrolledstud')
              ->where('studid', $studid)
              ->where('syid', $syID)
              ->first();
        }  
        // $levelsection = $stud->levelname . ' ' . $stud->sectionname;
        $levelsection = $stud->levelid;

        if(!$enrollstud)
        {
          $enrollid = 0;
        }
        else
        {
          $enrollid = $enrollstud->id;
        }
      }
      else
      {

        $custName = $request->get('custName');

        $studid = 0;
        $studname = $custName;
        $levelsection = '';
        $sid = '';
        $gradesection = '';

      }

      $schoolinfo = CashierModel::schoolinfo();



      $VARornum = '';

      if($schoolinfo->olreceipt == 1)
      {
        if($paytype != 'CASH' && $paytype != 'CHEQUE')
        {
          $VARornum = 'OL-' . $ornum;
        }
        else
        {
          $VARornum = $ornum;
        }
      }
      else
      {
        $VARornum = $ornum;
      }

      $pay = db::table('chrngtrans')
          ->insertGetId([
            'chrngdayid' => $dayid,
            'shiftid' => 4,
            'ornum' => $VARornum,
            'studname' => $studname,
            'glevel' => $gradesection,
            'transdate' => $curDT,
            'transdesc' => $transDesc,
            'totalamount' => $totalamount,
            'amountpaid' => $amountpaid,
            'studid' => $studid,
            'syid' => $syID,
            'semid' => CashierModel::getSemID(),
            'posted' => 0,
            // 'glevel' => $levelsection,
            'sid' => $sid,
            'terminalno' => $terminalno,
            'transby' => Auth::user()->id,
            'accountno' => $accountno,
            'accountname' => $accountname,
            'bankname' => $bankname,
            'chequeno' => $chequeno,
            'chequedate' => $chequedate,
            'creditcardno' => $creditcardno,
            'cardtype' => $cardtype,
            'refno' => $refno,
            'paytransdate' => $paytransdate,
            'paytype' => $paytype,
          ]);

      $cashtrans = db::table('chrngcashtrans')
          ->where('transno', $transno)
          ->where('deleted', 0)
          ->get();

      $items = '';
      $totalAmountToLedger = 0;
      

      foreach($cashtrans as $cash)
      {
              
        $dAmount = $cash->amount;

        if($cash->duedate <> '')
        {
          $dDate = date_create($cash->duedate);
          $dMonth = strtoupper(date_format($dDate, 'm'));
        }
        else
        {
          $dDate = '';
        }

        $totalamountpay = 0;
        $totalbalance = 0;
        
        if($amountpaid <> 0)
        {
          // return $dAmount . ' ' . $amountpaid;
          if($cash->transdone == 0)
          {
            $totalAmountToLedger += $dAmount;
            if($amountpaid >= $dAmount)
            {
              $amountpaid -= $dAmount;

              $schedinfo = db::table('studpayscheddetail')
                  ->where('id', $cash->payscheddetailid)
                  ->first();

              if($schedinfo->balance > $dAmount)
              {

                $tamountpay = $schedinfo->amountpay + $dAmount;
                $payscheddetail = db::table('studpayscheddetail')
                  ->where('id', $cash->payscheddetailid)
                  ->update([
                    'amountpay' => $tamountpay,
                    'balance' => $schedinfo->amount - $tamountpay,
                    'updateddatetime' => CashierModel::getServerDateTime(),
                    'updatedby' => auth()->user()->id
                  ]);  
              }
              else
              {
                $tamountpay = $schedinfo->amountpay + $dAmount;
                $payscheddetail = db::table('studpayscheddetail')
                  ->where('id', $cash->payscheddetailid)
                  ->update([
                    'amountpay' => $tamountpay,
                    'balance' => $schedinfo->amount - $tamountpay,
                    'updateddatetime' => CashierModel::getServerDateTime(),
                    'updatedby' => auth()->user()->id
                  ]);  
              }

              

              if($cash->qty > 1)
              {
                $items.= '
                  <div class="row">
                    <div class="col-5 border-bottom text-bold">'.$cash->particulars.' ('.$cash->itemprice.' * '.$cash->qty.')</div>
                    <div class="col-3 border-bottom text-right">'.number_format($dAmount, 2).'</div>
                  </div>
                ';
              }
              else
              {
                $items.= '
                  <div class="row">
                    <div class="col-5 border-bottom text-bold">'.$cash->particulars.'</div>
                    <div class="col-3 border-bottom text-right">'.number_format($dAmount, 2).'</div>
                  </div>
                '; 
              }


              if($dDate == '')
              {
                $paysched = db::table('studpaysched')
                    ->where('studid', $studid)
                    ->where('syid', $syID)
                    ->whereNull('duedate')
                    ->get();
              }
              else
              {
                $paysched = db::select('select * from studpaysched where studid = ? and syid = ? and MONTH(duedate) = ? and deleted = 0', [$studid, $syID, $dMonth]);
              }

              if(count($paysched) > 0)
              {
                $totalamountpay = $paysched[0]->amountpay + $dAmount;
                $payschedid = $paysched[0]->id;

                if($paysched[0]->amountdue > $totalamountpay)
                {
                  $totalbalance = $paysched[0]->amountdue - $totalamountpay;
                }
                else
                {
                  $totalbalance = 0;
                }

                $paysched = DB::table('studpaysched')
                  ->where('id', $payschedid)
                  ->update([
                    'amountpay' => $totalamountpay,
                    'balance' => $totalbalance
                  ]);
              }
              
              
              $paydetail = db::table('chrngtransdetail')
                ->insert([
                  'chrngtransid' => $pay,
                  'payschedid' => $cash->payscheddetailid,
                  'items' => $cash->particulars,
                  'amount' => $dAmount,
                  'qty' => 1,
                  'itemprice' =>$dAmount,
                  'classid' => $cash->classid,
                  'itemkind' => $cash->transdone
                  ]);
            }
            else //if($amountpaid >= $dAmount)
            {
              $dAmount = $dAmount - $amountpaid;

              $schedinfo = db::table('studpayscheddetail')
                  ->where('id', $cash->payscheddetailid)
                  ->first();

              $tamountpay = $schedinfo->amountpay + $amountpaid;

              $payscheddetail = db::table('studpayscheddetail')
                ->where('id', $cash->payscheddetailid)
                ->update([
                  'amountpay' => $tamountpay,
                  'balance' => $schedinfo->amount - $tamountpay, //$dAmount
                  'updateddatetime' => CashierModel::getServerDateTime(),
                  'updatedby' => auth()->user()->id
                ]);


                if($cash->qty > 1)
                {
                  $items.= '
                    <div class="row">
                      <div class="col-5 border-bottom text-bold">'.$cash->particulars.' ('.$cash->itemprice.' * '.$cash->qty.')</div>
                      <div class="col-3 border-bottom text-right">'.number_format($amountpaid, 2).'</div>
                    </div>
                  ';
                }
                else
                {
                  $items.= '
                    <div class="row">
                      <div class="col-5 border-bottom text-bold">'.$cash->particulars.'</div>
                      <div class="col-3 border-bottom text-right">'.number_format($amountpaid, 2).'</div>
                    </div>
                  ';
                }

                if($dDate == '')
                {
                  $paysched = db::table('studpaysched')
                      ->where('studid', $studid)
                      ->where('syid', $syID)
                      ->whereNull('duedate')
                      ->get();
                }
                else
                {
                  $paysched = db::select('select * from studpaysched where studid = ? and syid = ? and MONTH(duedate) = ? and deleted = 0', [$studid, $syID, $dMonth]);
                }
    
                if(count($paysched) > 0)
                {
                  $totalamountpay = $paysched[0]->amountpay + $amountpaid;
                  $payschedid = $paysched[0]->id;
    
                  if($paysched[0]->amountdue > $totalamountpay)
                  {
                    $totalbalance = $paysched[0]->amountdue - $totalamountpay;
                  }
                  else
                  {
                    $totalbalance = 0;
                  }

                  $paysched = DB::table('studpaysched')
                    ->where('id', $payschedid)
                    ->update([
                      'amountpay' => $totalamountpay,
                      'balance' => $totalbalance
                    ]);
                }
                
              $paydetail = db::table('chrngtransdetail')
                ->insert([
                  'chrngtransid' => $pay,
                  'payschedid' => $cash->payscheddetailid,
                  'items' => $cash->particulars,
                  'itemprice' =>$amountpaid,
                  'amount' => $amountpaid,
                  'qty' => 1,
                  'classid' => $cash->classid,
                  'itemkind' => $cash->transdone
                ]);

              $amountpaid = 0;
            }
          }
          else //transDone == 1 (ITEMS)
          {
            if($cash->qty > 1)
            {
              $items.= '
                <div class="row">
                  <div class="col-5 border-bottom text-bold">'.$cash->particulars.' ('.$cash->itemprice.' * '.$cash->qty.')</div>
                  <div class="col-3 border-bottom text-right">'.number_format($dAmount, 2).'</div>
                </div>
              ';
            }
            else
            {
              $items.= '
                <div class="row">
                  <div class="col-5 border-bottom text-bold">'.$cash->particulars.' ('.$cash->qty.' * '.$cash->itemprice.')</div>
                  <div class="col-3 border-bottom text-right">'.number_format($dAmount, 2).'</div>
                </div>
              ';
            }

            $paydetail = db::table('chrngtransdetail')
                ->insertGetId([
                  'chrngtransid' => $pay,
                  'payschedid' => $cash->payscheddetailid,
                  'items' => $cash->particulars,
                  'itemprice' => $cash->itemprice,
                  'amount' => $dAmount,
                  'qty' => $cash->qty,
                  'classid' => $cash->classid,
                  'itemkind' => $cash->transdone
                  ]);

            $getAmount = db::table('chrngtransdetail')
                ->where('id', $paydetail)
                ->first();

            $dpAmount = $getAmount->amount;

            $chkisDP = db::table('items')
                ->where('id', $cash->payscheddetailid)
                ->where('isdp', 1)
                ->get();

            if(count($chkisDP) > 0)
            {
              $ledger = db::table('studledger')
                ->insert([
                  'studid' => $studid,
                  'enrollid' => $enrollid,
                  'syid' => $syID,
                  'semid' => CashierModel::getsemID(),
                  'particulars' => $chkisDP[0]->description . ' - OR: ' . $ornum . ' - ' . $paytype,
                  'payment' => $dpAmount, //$chkisDP[0]->amount, //$tPaid,
                  'ornum' => $VARornum,
                  'paytype' => $paytype,
                  'transid' => $pay,
                  'createddatetime' => $curDT, 
                  'deleted' => 0
                ]);

              // CashierModel::smsPayment($studid, $tPaid, $ornum);       
            }

          }
        }
      }

      $ctrans = db::table('chrngcashtrans')
        ->where('transno', $transno)
        ->where('deleted', 0)
        ->where('transdone', 1)
        ->count();

      if($ctrans == 0)
      {
        $totalAmountToLedger = $_amountpaid;
      }


      // if($enrollid != 0 and $totalAmountToLedger > 0 and $cash->transdone == 0)
      if($totalAmountToLedger > 0) //and $cash->transdone == 0)
      {
        $ledger = db::table('studledger')
            ->insert([
              'studid' => $studid,
              'enrollid' => $enrollid,
              'syid' => $syID,
              'semid' => CashierModel::getsemID(),
              'particulars' => 'PAYMENT TUITION/BOOKS/OTH - OR: ' . $ornum . ' - ' . $paytype, 
              'payment' => $totalAmountToLedger, //$tPaid,
              'ornum' => $VARornum,
              'paytype' => $paytype,
              'transid' => $pay,
              'createddatetime' => $curDT, 
              'deleted' => 0
            ]);

        // CashierModel::smsPayment($studid, $tPaid, $ornum);
      }

      CashierModel::insertOR($ornum + 1, $terminalno, $paytype);
      
      $dtnow = date_create($curDT);
      $dtnow = date_format($dtnow, 'm/d/Y h:i A');

      $wnum = strtok($tPaid, '.');
      $dec = substr(number_format($tPaid, 2), strpos($tPaid, '.') + 2);

      $wnum = strtoupper(NumConvert::word($wnum));

      if($dec > 0)
      {
        $dec = 'AND ' . $dec . '/100';
      }
      else
      {
        $dec = '';
      }

      if($olid != '')
      {
        $olupdate = db::table('onlinepayments')
            ->where('id', $olid)
            ->update([
              'isapproved' => 5,
              'chrngtransid' => $pay,
              'updatedby' => auth()->user()->id,
              'updateddatetime' => CashierModel::getServerDateTime()
            ]);

      }


      $data = array(
        'transid' => $pay,
        'sid' => $sid,
        'studname' => strtoupper($studname),
        'gradesection' => $gradesection,
        'ornum' => $ornum,
        'amountpaid' => $tPaid,
        'items' => $items,
        'curdate' => $dtnow,
        'formatpaid' => number_format($tPaid, 2),
        'numtowords' => $wnum . ' PESOS ' . $dec . ' ('. number_format($tPaid, 2) .')' //strtoupper(NumConvert::word($tPaid)) //strtoupper(CashierModel::numberTowords($tPaid))
      );

      echo json_encode($data);

    }
  }

  public function printor($transid)
  {

    

    $trans = explode('--', $transid); // ----------------------------------------------- [shane]
    $transid = $trans[0];// ------------------------------------------------------------ [shane]
    $previousbalance = $trans[1];// ---------------------------------------------------- [shane]
    $schoolinfo = db::table('schoolinfo')
      ->first();

    $schoolname = $schoolinfo->schoolname;
    $schooladdress = $schoolinfo->address;

    $trans = db::table('chrngtrans')
        ->where('id', $transid)
        ->first();
    
    // return collect($trans);

    if($trans->glevel != '')
    {
      $explodeglevel = explode(' - ', $trans->glevel);// --------------------------------- [shane]
      $gradelevel = $explodeglevel[0];// ------------------------------------------------- [shane]
      $section = $explodeglevel[1];// ---------------------------------------------------- [shane]
    }else{
      $gradelevel = '';
      $section = '';
    }
    
    

    $transdetail = db::table('chrngtransdetail')
        ->where('chrngtransid', $transid)
        ->get();

    $datenow = date_create(CashierModel::getServerDateTime());
    $datenow = date_format($datenow, 'M d, Y');
    // return collect($trans[1]);
    $amount = strtok($trans->amountpaid, '.');

    // return $trans->amountpaid;
        
    $dec = substr(number_format($previousbalance, 2), strpos($previousbalance, '.') + 2);
    $amountstring = strtoupper(NumConvert::word($amount));
    if($dec > 0)
    {
      $dec = 'AND ' . $dec . '/100';
    }
    else
    {
      $dec = '';
    }
    $data = array(
      'gradelevel'        => $gradelevel,// ------------------------------------------- [shane]
      'section'        => $section,// ------------------------------------------------- [shane]
      'previousbalance'   => $previousbalance,// -------------------------------------- [shane]
      'schoolname' => $schoolname,
      'schoolinfo' => $schoolinfo,
      'schooladdress' => $schooladdress,
      'trans' => $trans,
      'amountstring'      => $amountstring . ' PESOS ' . $dec,
      'transdetail' => $transdetail,
      'amount' => $amount,
      'cashier' => auth()->user()->name,
      'datenow' => $datenow
    );
    // return $data;
    if(strtolower($schoolinfo->snr) == 'hccsi')
    {
      $pdf = PDF::loadView('pdf.or_hccsi', $data)->setPaper('letter', 'portrait');
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'sic')
    {
      $pdf = PDF::loadView('pdf.or_sic', $data)->setPaper('letter', 'portrait');
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'sait')
    {
      $pdf = PDF::loadView('pdf.or_sait', $data)->setPaper('letter', 'portrait');
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'bct')
    {
      $pdf = PDF::loadView('pdf.or_bct', $data)->setPaper('letter', 'portrait');
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'shjms')
    {
      $pdf = PDF::loadView('pdf.or_shjms', $data)->setPaper('letter', 'portrait');
      return $pdf->stream('receipt.pdf');
    }
    else{
      $pdf = PDF::loadView('pdf.or_default', $data)->setPaper('letter', 'portrait');
      return $pdf->stream('receipt.pdf');
    }
  }

  public function getstudassessment(Request $request)
  {
    if($request->ajax())
    {
      $studid = $request->get('studid');
      $syID = CashierModel::getSYID();

      $months = db::table('monthsetup') 
          ->get();


      $curMonth = date_create(CashierModel::getServerDateTime());
      $curMonth = strtoupper(date_format($curMonth, 'F'));
      // return $curMonthORG;

      $msetup = '';

      foreach($months as $month)
      {
        // return $month->description;

        if($month->description == $curMonth)
        {
          $msetup .= '
            <option id="" selected>'.$month->description.'</option>
          '; 
        }
        else
        {
          $msetup .= '
            <option id="">'.$month->description.'</option>
          ';
        }
        
      }

      // return  $curMonth.toString();

      $studinfo = db::table('studinfo')
        ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'sectionname')
        ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
        ->where('studinfo.id', $studid)
        ->first();

      $studName = strtoupper($studinfo->lastname. ', '. $studinfo->firstname . ' ' . $studinfo->middlename . ' '. $studinfo->suffix. ' | '. $studinfo->levelname . ' - ' . $studinfo->sectionname);

      $data = array(
        'studname' => $studName,
        'month' =>$msetup
      );

      echo json_encode($data);
    }
  }

  public function genassessment(Request $request)
  {
    if($request->ajax())
    {
      $strmonth = $request->get('strmonth');

      $studid = $request->get('studid');
      $showall = $request->get('showall');
      $month = 0;
      $month = date("m", strtotime($strmonth));

      
      $syID = CashierModel::getSYID();
      $semid = CashierModel::getSemID();

      $studinfo = db::table('studinfo')
          ->where('id', $studid)
          ->first();

      if($studinfo->levelid == 14 || $studinfo->levelid == 15)
      {
        $getPaySched = db::select('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
            from studpayscheddetail
            where studid = ? and syid = ? and semid = ? and deleted = 0 and amount >=0
            group by MONTH(duedate)
            order by duedate', [$studid, $syID, $semid]);

      }
      else
      {
        $getPaySched = db::select('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
            from studpayscheddetail
            where studid = ? and syid = ? and deleted = 0 and amount >=0
            group by MONTH(duedate)
            order by duedate', [$studid, $syID]);
      }
      

      $output = '';
      $footer = '';
      $totalBal = 0;

      if(count($getPaySched) > 0)
      {
        foreach($getPaySched as $psched)
        {

          // return $getPaySched;
          $totalBal += $psched->balance;
          $m = date_create($psched->duedate);
          $f = date_format($m, 'F');
          $m = date_format($m, 'm');
          
          if($psched->duedate != '')
          {
            $particulars = 'TUITION/BOOKS/OTH FEE - ' . $f;  
          }
          else
          {
            $particulars = 'TUITION/BOOKS/OTH FEE';
            $m = 0;
          }

          
          // return $showall;
          if($showall == 'false')
          {
            // return $m . ' != ' . $month;
            if($m != $month)
            {
              if($psched->balance > 0)
              {
                $output .='
                  <tr>
                    <td>'.$particulars.'</td>
                    <td class="text-right">'.number_format($psched->amountdue, 2).'</td>
                    <td class="text-right">'.number_format($psched->amountpay, 2).'</td>
                    <td class="text-right">'.number_format($psched->balance, 2).'</td>
                  </tr>
                ';
              }
            }
            else
            {
              if($psched->balance > 0)
              {
                $output .='
                  <tr>
                    <td>'.$particulars.'</td>
                    <td class="text-right">'.number_format($psched->amountdue, 2).'</td>
                    <td class="text-right">'.number_format($psched->amountpay, 2).'</td>
                    <td class="text-right">'.number_format($psched->balance, 2).'</td>
                  </tr>
                ';
              }
              else
              {
                $output .='
                  <tr>
                    <td>'.$particulars.'</td>
                    <td class="text-right">'.number_format($psched->amountdue, 2).'</td>
                    <td class="text-right">'.number_format($psched->amountpay, 2).'</td>
                    <td class="text-right">'.number_format($psched->balance, 2).'</td>
                  </tr>
                ';
              }
              break; 
            }
          }
          else
          {
            // return $m . ' != ' . $month; 
            if($m != $month)
            {
              
              $output .='
                <tr>
                  <td>'.$particulars.'</td>
                  <td class="text-right">'.number_format($psched->amountdue, 2).'</td>
                  <td class="text-right">'.number_format($psched->amountpay, 2).'</td>
                  <td class="text-right">'.number_format($psched->balance, 2).'</td>
                </tr>
              ';
              
            }
            else
            {
              
              $output .='
                <tr>
                  <td>'.$particulars.'</td>
                  <td class="text-right">'.number_format($psched->amountdue, 2).'</td>
                  <td class="text-right">'.number_format($psched->amountpay, 2).'</td>
                  <td class="text-right">'.number_format($psched->balance, 2).'</td>
                </tr>
              ';
              
              break; 
            }
          }
        }

        $footer .='
          <tr class="bg-primary">
            <td class="text-right text-bold" colspan="4">TOTAL DUE: '.number_format($totalBal, 2).'</td>
          </tr>
        ';

      }

      $data = array(
        'output' => $output,
        'footer' =>$footer
      );

      echo json_encode($data);
    }
  }

  public function getstudledger(Request $request)
  {
    if($request->ajax())
    {
      $studid = $request->get('studid');
      $syID = CashierModel::getSYID();
      $semid = CashierModel::getSemID();

      $studinfo = db::table('studinfo')
        ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'sectionname', 'levelid')
        ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
        ->where('studinfo.id', $studid)
        ->first();

      $studName = strtoupper($studinfo->lastname. ', '. $studinfo->firstname . ' ' . $studinfo->middlename . ' '. $studinfo->suffix. ' | '. $studinfo->levelname . ' - ' . $studinfo->sectionname);
      
      if($studinfo->levelid == 14 || $studinfo->levelid == 15)
      {
        $ledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('syid', $syID)
            ->where('semid', $semid)
            ->where('deleted', 0)
            ->orderBy('createddatetime', 'asc')
            ->get();
      }
      elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
      {
        $ledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('syid', $syID)
            ->where('semid', $semid)
            ->where('deleted', 0)
            ->orderBy('createddatetime', 'asc')
            ->get(); 
      }
      else
      {
        $ledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('syid', $syID)
            ->where('deleted', 0)
            ->orderBy('createddatetime', 'asc')
            ->get();
      }

      $output = '';
      $bal = 0;
      $debit = 0;
      $credit = 0;

      foreach($ledger as $led)
      {
        $debit += $led->amount;

        if($led->void == 0)
        {
          $credit += $led->payment;
        }
        
        $lDate = date_create($led->createddatetime);
        $lDate = date_format($lDate, 'm-d-Y');

        if($led->amount > 0)
        {
          $amount = number_format($led->amount,2);
        }
        else
        {
          $amount = '';
        }

        if($led->payment > 0)
        {
          $payment = number_format($led->payment,2);
        }
        else
        {
          $payment = '';
        }

        if($led->void == 0)
        {
          $bal += $led->amount - $led->payment;
        }

        if($led->void == 0)
        {
          $output .='
            <tr class="">
              <td class="">' .$lDate.' </td>
              <td>'.$led->particulars.'</td>
              <td class="text-right">'.$amount.'</td>
              <td class="text-right">'.$payment.'</td>
              <td class="text-right">'.number_format($bal, 2).'</td>
            </tr>
            ';
        }
        else
        {
          $output .='
            <tr class="">
              <td class="text-danger"><del>' .$lDate.' </del></td>
              <td class="text-danger"><del>'.$led->particulars.'</del></td>
              <td class="text-right text-danger"><del>'.$amount.'</del></td>
              <td class="text-right text-danger"><del>'.$payment.'</del></td>
              <td class="text-right text-danger"><del>'.number_format($bal, 2).'</del></td>
            </tr>
            ';
        }

      }

      $output .='
        <tr class="pay-2">
          <th></th>
          <th style="text-align:right">
            <h5>
              <strong>TOTAL:<strong>
            </h5>
          </th>
          <th>
            <h5 class="text-right">
              <strong><u>'.number_format($debit, 2).'</u></strong>
            </h5>
          </th>
          <th>
            <h5 class="text-right">
              <strong><u>'.number_format($credit, 2).'</u></strong>
            </h5>
          </th>
          <th>
            <h5 class="text-right">
              <strong><u>'.number_format($bal, 2).'</u></strong>
            </h5>
          </th>
        </tr>
      ';

      $data = array(
        'output' => $output,
        'studname' => $studName
      );

      echo json_encode($data);
    }
  }

  public function getcashsetup(Request $request)
  {
    if($request->ajax())
    {
      $curTerminal = $request->get('terminalid');
      $terminals = db::table('chrngterminals')
        ->where('id', $curTerminal)
        ->get();

      // return $terminals;
      $cashier = '';
      foreach($terminals as $terminal)
      {

        $tcount=+1;

        if($curTerminal == $terminal->id)
        {
          $cashier .='
            <option value="'.$terminal->id.'" selected>'.$terminal->description.'</option>
          ';
        }
        else
        {
          $cashier .='
            <option value="'.$terminal->id.'">'.$terminal->description.'</option>
          ';
        }
      }


      $curDate = date_create(CashierModel::getServerDateTime());

      $from = date_format($curDate, 'Y-m-d');
      $to = date_format($curDate, 'Y-m-d');

      $data = array(
        'terminal' => $cashier,
        'from' => $from,
        'to' => $to
      );

      echo json_encode($data);
    }
  }  

  public function getcashtrans(Request $request)
  {
    if($request->ajax())
    {
      $terminalid = $request->get('terminalid');
      $dtFrom = $request->get('dtFrom');
      $dtTo = $request->get('dtTo');
      $search = $request->get('search');

      $from = date_create($dtFrom);
      // $from = date_create('01/01/2020');
      $from = date_format($from, 'Y-m-d');
      $to = date_create($dtTo);
      // $to = date_create('01/31/2020');
      $to = date_format($to, 'Y-m-d');

      $from .= ' 00:00';
      $to .= ' 23:59';
      
      $transactions = db::table('chrngtrans')
          ->select('chrngtrans.id', 'ornum', 'transdate', 'totalamount', 'amountpaid', 'studid', 'particulars', 'terminalno', 'transby', 'studname', 'posted', 'sid', 'glevel', 'name', 'cancelled', 'paymenttype.description')
          ->join('users', 'chrngtrans.transby', '=', 'users.id')
          ->join('paymenttype', 'chrngtrans.paytype', '=', 'paymenttype.description')
          ->where('terminalno', $terminalid)
          ->where('ornum', 'like', '%'. $search . '%')
          ->whereBetween('transdate', [$from, $to])
          ->orWhere('terminalno', $terminalid)
          ->where('studname', 'like', '%'. $search . '%')
          ->whereBetween('transdate', [$from, $to])
          ->orderBy('id', 'DESC')
          ->get();

      // return $transactions;

      $output = '';
      $gTotal = 0;
      $count = 0;
      foreach($transactions as $trans)
      {
        $count += 1;
        $tdate = date_create($trans->transdate);
        $tdate = date_format($tdate, 'm-d-Y');

        if($trans->cancelled == 0)
        {
          $gTotal += $trans->amountpaid;
        }
        
        if($trans->posted == 0 && $trans->cancelled == 0)
        {
          $output .='

            <tr style="font-size:larger">
              <td>'.$count.'</td>
              <td>'.$tdate.'</td>
              <td>'.$trans->ornum.'</td>
              <td>'.strtoupper($trans->studname).'</td>
              <td class="text-right">'.number_format($trans->amountpaid, 2).'</td>
              <td></td>
              <td>'.strtoupper($trans->name).'</td>
              <td>'.$trans->description.'</td>
              <td>
                <span class="btn-view btn btn-primary btn-sm" data-id="'.$trans->id.'">View</span>
              </td>
              <td>
                <span class="btn-void btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-voidpermission" data-id="'.$trans->id.'" data-or="'.$trans->ornum.'">
                  Void
                </span>
              </td>
            </tr>
          ';
        }
        else if($trans->cancelled == 1)
        {
          $output .='

            <tr style="font-size:larger">
              <td class="text-danger"><del>'.$count.'</del></td>
              <td class="text-danger"><del>'.$tdate.'</del></td>
              <td class="text-danger"><del>'.$trans->ornum.'</del></td>
              <td class="text-danger"><del>'.strtoupper($trans->studname).'</del></td>
              <td class="text-right text-danger""><del>'.number_format($trans->amountpaid, 2).'</del></td>
              <td class="text-center class="text-danger""></td>
              <td class="text-danger"><del>'.strtoupper($trans->name).'</del></td>
              <td>'.$trans->description.'</td>
              <td colspan="2"><span class="btn-view btn btn-block btn-danger btn-sm" data-id="'.$trans->id.'">View</span></td>
            </tr>
          ';
        }
        else
        {
          $output .='

            <tr style="font-size:larger">
              <td>'.$count.'</td>
              <td>'.$tdate.'</td>
              <td>'.$trans->ornum.'</td>
              <td>'.strtoupper($trans->studname).'</td>
              <td class="text-right">'.number_format($trans->amountpaid, 2).'</td>
              <td class="text-center"><i class="fa fa-check"></i></td>
              <td>'.strtoupper($trans->name).'</td>
              <td>'.$trans->description.'</td>
              <td colspan="2"><span class="btn-view btn btn-block btn-primary btn-sm" data-id="'.$trans->id.'">View</span></td>
            </tr>
          ';
        }

      }

      $output .='
        <tr style="font-size:larger" class="pay-2">
          <td colspan = "4" class="text-right text-bold">TOTAL</td>
          <td class="text-right text-bold"><u>'.number_format($gTotal, 2).'</u></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      ';

      $data = array(
        'output' => $output
      );

      echo json_encode($data);
    }
  }

  public function getcashtransPrint(Request $request)
  {
    if($request->ajax())
    {
      $terminalid = $request->get('terminalid');
      $dtFrom = $request->get('dtFrom');
      $dtTo = $request->get('dtTo');
      $search = $request->get('search');


      $from = date_create($dtFrom);
      // $from = date_create('01/01/2020');
      $from = date_format($from, 'Y-m-d');
      $to = date_create($dtTo);
      // $to = date_create('01/31/2020');
      $to = date_format($to, 'Y-m-d');

      $from .= ' 00:00';
      $to .= ' 23:59';
      
      $transactions = db::table('chrngtrans')
          ->select('chrngtrans.id', 'ornum', 'transdate', 'totalamount', 'amountpaid', 'studid', 'particulars', 'terminalno', 'transby', 'studname', 'posted', 'sid', 'glevel', 'name', 'cancelled', 'paymenttype.description')
          ->join('users', 'chrngtrans.transby', '=', 'users.id')
          ->join('paymenttype', 'chrngtrans.paytype', '=', 'paymenttype.description')
          ->where('terminalno', $terminalid)
          ->where('ornum', 'like', '%'. $search . '%')
          ->whereBetween('transdate', [$from, $to])
          ->orWhere('terminalno', $terminalid)
          ->where('studname', 'like', '%'. $search . '%')
          ->whereBetween('transdate', [$from, $to])
          ->get();

      // return $transactions;

      $output = '';
      $gTotal = 0;
      $count = 0;
      foreach($transactions as $trans)
      {
        $count += 1;
        $tdate = date_create($trans->transdate);
        $tdate = date_format($tdate, 'm-d-Y');
        
        if($trans->cancelled == 0)
        {
          $gTotal += $trans->amountpaid;
        }
        
        if($trans->posted == 0 && $trans->cancelled == 0)
        {
          $output .='

            <tr style="font-size:larger">
              <td>'.$count.'</td>
              <td>'.$tdate.'</td>
              <td>'.$trans->ornum.'</td>
              <td>'.strtoupper($trans->studname).'</td>
              <td class="text-right">'.number_format($trans->amountpaid, 2).'</td>
              <td></td>
              <td>'.strtoupper($trans->name).'</td>
              <td>'.$trans->description.'</td>
              
            </tr>
          ';
        }
        else if($trans->cancelled == 1)
        {
          $output .='

            <tr style="font-size:larger">
              <td class="text-danger"><del>'.$count.'</del></td>
              <td class="text-danger"><del>'.$tdate.'</del></td>
              <td class="text-danger"><del>'.$trans->ornum.'</del></td>
              <td class="text-danger"><del>'.strtoupper($trans->studname).'</del></td>
              <td class="text-right text-danger""><del>'.number_format($trans->amountpaid, 2).'</del></td>
              <td class="text-center class="text-danger""></td>
              <td class="text-danger"><del>'.strtoupper($trans->name).'</del></td>
              <td>'.$trans->description.'</td>
            </tr>
          ';
        }
        else
        {
          $output .='

            <tr style="font-size:larger">
              <td>'.$count.'</td>
              <td>'.$tdate.'</td>
              <td>'.$trans->ornum.'</td>
              <td>'.strtoupper($trans->studname).'</td>
              <td class="text-right">'.number_format($trans->amountpaid, 2).'</td>
              <td class="text-center"><i class="fa fa-check"></i></td>
              <td>'.strtoupper($trans->name).'</td>
              <td>'.$trans->description.'</td>
            </tr>
          ';
        }

      }

      $output .='
        <tr style="font-size:larger" class="pay-2">
          <td colspan = "4" class="text-right text-bold">TOTAL</td>
          <td class="text-right text-bold"><u>'.number_format($gTotal, 2).'</u></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      ';

      // return $from;
      $from = date_create($from);
      $from = date_format($from, 'm-d-Y');
      $to = date_create($to);
      $to = date_format($to, 'm-d-Y');
      if($from != $to)
      {
        $dr = $from . ' - ' . $to;
      }
      else
      {
        $dr = $from; 
      }
      // return $dr;
      $dtnow = date_create(CashierModel::getServerDateTime());
      $data = array(
        'output' => $output,
        'dt' => $dr,
        'datenow' => date_format($dtnow, 'm-d-Y h:i A')
      );

      echo json_encode($data);
    }
  }

  public function insertOR(Request $request)
  {
    $insertOR = db::table('orcounter')
          ->insert([
            'ornum' => $or,
            'terminalno' =>  1,
            'createddatetime' => CashierModel::getServerDateTime()
          ]);
  }

  public function getornum_setup(Request $request)
  {
    $terminalno = $request->get('terminalno');
    $paytypeid = $request->get('paytypeid');

    if($paytypeid == '')
    {
      $paytypeid = 0;
    }

    $curOR = CashierModel::getornum($terminalno, $paytypeid);

    $data = array(
      'curOR' => $curOR
    );

    echo json_encode($data);
  }

  public function insertor_setup(Request $request)
  {
    if($request->ajax())
    {
      $ornum = $request->get('ornum');
      $terminalno = $request->get('terminalno');
      

      $curOR = CashierModel::insertOR($ornum, $terminalno, 'CASH');
      

      $data = array(
        'curOR' => $curOR
      );
  
      echo json_encode($data);
    }
  }

  public function getornum(Request $request)
  {
    if($request->ajax())
    {
      $terminalno = $request->get('terminalno');
      $paytypeid = $request->get('paytypeid');
      
      $curOR = CashierModel::getornum($terminalno, $paytypeid);
      


      // if($curOR > 0)
      // {
      //   $curOR += 1;
      // }

      // $curOR = CashierModel::insertOR($curOR, $terminalno);
      // $curOR = CashierModel::getornum($terminalno);
      // return $curOR;
      if($curOR == 0)
      {
        $ornum = db::table('orcounter')
          ->where('terminalno', $terminalno)
          ->where('used', 1)
          ->orderBy('id','desc')
          ->take(1)
          ->get();

        if(count($ornum) > 0)
        {
          $curOR += 1;
        }
        else
        {
          $curOR = 0;
        }
      }

      $data = array(
        'curOR' => $curOR
      );

      echo json_encode($data);
    }
  }

  public function getterminal(Request $request)
  {
    if($request->ajax())
    {

      $owner = CashierModel::getIp();
      // return $owner;
      $terminals = db::table('chrngterminals')
          ->get();

      $tValue = '';
      $output = '';

      foreach($terminals as $terminal)
      {
        if($terminal->owner == $owner)
        {
          $output .='
            <option value="'.$terminal->id.'" selected>'.$terminal->description.'</option>
          ';

          $tValue = $terminal->description;
        }
        else
        {

          $output .='
            <option value="'.$terminal->id.'">'.$terminal->description.'</option>
          ';
        }
      }

      $data = array(
        'output' => $output,
        'tvalue' => $tValue
      );

      echo json_encode($data);
    }
  }

  public function saveterminal(Request $request)
  {
    if($request->ajax())
    {
      $terminalid = $request->get('terminalid');
      $owner = CashierModel::getIp();
      // Cache::forever('key', $terminalid);
      // $owner = Cache::get('key');
      // return $owner;

      $getData = db::table('chrngterminals')
          ->where('id', $terminalid)
          ->first();
      // return $getData[0]->id;
      // return $getData->owner;
        
      if($getData->owner == null)
      {
        $getterminal = db::table('chrngterminals')
            ->where('owner', $owner)
            ->get();
        // return $owner;

        if(count($getterminal) > 0)
        {
          $updData = db::table('chrngterminals')
            ->where('id', $getterminal[0]->id)
            ->update([
              'owner' => ''
            ]);

          $setTerminal = db::table('chrngterminals')
            ->where('id', $terminalid)
            ->update([
              'owner' => $owner
            ]);
        }
        else
        {
          $setTerminal = db::table('chrngterminals')
            ->where('id', $terminalid)
            ->update([
              'owner' => $owner
            ]);
        }

        return 1;
      }
      else
      {
        return 0;
      }
    }
  }

  public function loadTerminal(Request $request)
  {
    if($request->ajax())
    {
      $owner = CashierModel::getIp();
      // $owner = $request->header('User-Agent');
      // return Cookie::get('name');
      // return $owner;
      

      $getData = db::table('chrngterminals')
            ->where('owner', $owner)
            ->get();

      if(count($getData) > 0)
      {
        $tID = $getData[0]->id;
        $tDesc = $getData[0]->description;

        $data = array(
          'terminalid' => $tID,
          'terminalDesc' => $tDesc
        );

      }
      else
      {
        $data = array(
          'terminalid' => 0,
          'terminalDesc' => ''
        );      
      }

      echo json_encode($data);

    }
  }

  public function openday(Request $request)
  {
    if($request->ajax())
    {
      $terminalid = $request->get('terminalid');
      $openDay = '';
      $nopost = 0;

      // return $terminalid;
      if($terminalid > 0)
      {
        $dayid = db::table('chrngday')
          ->where('terminalid', $terminalid)
          ->whereNotNull('opendatetime')
          ->whereNull('closedatetime')
          ->get();

        $opendatetime = '';

        


        if(count($dayid) > 0)
        {
          $openDay = $dayid[0]->id;

          $opendatetime = date_create($dayid[0]->opendatetime);
          $opendatetime = date_format($opendatetime, 'm/d/Y h:i A');

          $dtOpen = date_create($dayid[0]->opendatetime);
          $dtOpen = date_format($dtOpen, 'm/d/Y');

          $dtNow = date_create(CashierModel::getServerDateTime());
          $dtNow = date_format($dtNow, 'm/d/Y');

          if($dtNow != $dtOpen)
          {
            $nopost = 1;
          }
          else
          {
            $nopost = 0;
          }

        }
        else
        {
          $openDay = 0;
        }

        $data = array(
          'result' => 1,
          'nopost' => $nopost,
          'openday' => $openDay,
          'opendatetime' => $opendatetime
        );
      }
      else
      {
        $data = array(
          'result' => 0
        );
      }

      echo json_encode($data);
    }
  }

  public function getdatetime(Request $request)
  {
    $curDayID = $request->get('curDayID');
    $datetime = '';

    if($curDayID == 0)
    {
      $datetime = date_create(CashierModel::getServerDateTime());
      $datetime = date_format($datetime, 'm/d/Y h:i A');

      return $datetime;  
    }
    else
    {
      $datetime = db::table('chrngday')
          ->where('id', $curDayID)
          ->first();

      $datetime = date_create($datetime->opendatetime);
      $datetime = date_format($datetime, 'm/d/Y h:i A');

      return $datetime;
    }

    
  }

  public function opendaycash(Request $request)
  {
    if($request->ajax())
    {
      $terminalno = $request->get('terminalid');

      $openday = db::table('chrngday')
          ->insert([
            'terminalid' => $terminalno,
            'opendatetime' => CashierModel::getServerDateTime()
          ]);

    }
    
  }


  public function postTrans(Request $request)
  {
    if($request->ajax())
    {
      $dayid = $request->get('dayid');

      $chrngtrans = db::table('chrngtrans')
          ->select('chrngtransid', 'chrngtransdetail.id as chrngtransdetailid', 'ornum', 'classid', 'chrngtransdetail.amount', 'studid', 'syid', 'semid')
          ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
          ->where('chrngdayid', $dayid)
          ->where('posted', 0)
          ->where('cancelled', 0)
          ->get();

      foreach($chrngtrans as $trans)
      {
        $transamount = $trans->amount;


        top:

        $ledgeritemized = db::select(
          'SELECT *
          FROM `studledgeritemized` 
          WHERE `studid` = ? 
            AND `syid` = ? 
            AND `semid` = ? 
            AND `classificationid` =? 
            AND `deleted` = 0
            AND `totalamount` < itemamount', [$trans->studid, $trans->syid, $trans->semid, $trans->classid]
        );

        // if(count($ledgeritemized) == 0)
        // {
        //  $ledgeritemized = db::select(
        //    'SELECT *
        //    FROM `studledgeritemized` 
        //    WHERE `studid` = ? 
        //      AND `syid` = ? 
        //      AND `semid` = ? 
        //      AND `totalamount` < itemamount', [$trans->studid, $trans->syid, $trans->semid]
        //  );
        // }

        if(count($ledgeritemized) == 0)
        {
          $transamount = 0;
        }

        // return $ledgeritemized;

        foreach($ledgeritemized as $item)
        {
          // echo ' transamount: ' . $transamount . '; itemid: ' . $item->itemid . ' classid: ' . $trans->classid;

          if($transamount > 0)
          {
            // echo ' transamount: ' . $transamount . '; itemid: ' . $item->itemid . ' classid: ' . $trans->classid;
            $checkitem = db::table('studledgeritemized')
              ->where('id', $item->id)
              ->first();

            // echo $checkitem->totalamount . ' < ' . $item->itemamount . '; ';
            if($checkitem)
            {
              if($checkitem->totalamount < $item->itemamount)
              {
                $_getamount = $item->itemamount - $item->totalamount;

                if($transamount >= $_getamount)
                {
                  db::table('studledgeritemized')
                    ->where('id', $item->id)
                    ->update([
                      'totalamount' => $item->totalamount + $_getamount,
                      'updatedby' => auth()->user()->id,
                      'updateddatetime' => CashierModel::getServerDateTime()
                    ]);


                  db::table('chrngtransitems')
                    ->insert([
                      'chrngtransid' => $trans->chrngtransid,
                      'chrngtransdetailid' => $trans->chrngtransdetailid,
                      'ornum' => $trans->ornum,
                      'itemid' => $item->itemid,
                      'classid' => $item->classificationid,
                      'amount' => $_getamount,
                      'studid' => $item->studid,
                      'syid' => $trans->syid,
                      'semid' => $trans->semid,
                      'createdby' => auth()->user()->id,
                      'createddatetime' => CashierModel::getServerDateTime()
                    ]);


                  $transamount -= $_getamount;

                }
                else
                {
                  db::table('studledgeritemized')
                    ->where('id', $item->id)
                    ->update([
                      'totalamount' => $item->totalamount + $transamount,
                      'updatedby' => auth()->user()->id,
                      'updateddatetime' => CashierModel::getServerDateTime()
                    ]);

                  db::table('chrngtransitems')
                    ->insert([
                      'chrngtransid' => $trans->chrngtransid,
                      'chrngtransdetailid' => $trans->chrngtransdetailid,
                      'ornum' => $trans->ornum,
                      'itemid' => $item->itemid,
                      'classid' => $item->classificationid,
                      'amount' => $transamount,
                      'studid' => $item->studid,
                      'syid' => $trans->syid,
                      'semid' => $trans->semid,
                      'createdby' => auth()->user()->id,
                      'createddatetime' => CashierModel::getServerDateTime()
                    ]);

                  $transamount = 0;
                }
    
              }
            }
          }
        }

        // return $transamount;

        if($transamount > 0)
        {
          goto top;
        }
      }

      $action = db::table('chrngtrans')
          ->where('chrngdayid', $dayid)
          ->where('posted', 0)
          ->where('cancelled', 0)
          ->update([
            'posted' => 1,
            'posteddatetime' => CashierModel::getServerDateTime()
          ]);
    }
  }


  public function endday(Request $request)
  {
    if($request->ajax())
    {
      $dayid = $request->get('dayid');
      $terminalno = $request->get('terminalid');
      $datetrans = CashierModel::getServerDateTime();

      $trans = db::table('chrngtrans')
          ->where('cancelled', 0)
          ->where('posted', 0)
          ->where('chrngdayid', $dayid)
          ->get();

      $msg = '';
      $validation = 0;

      if(count($trans) > 0)
      {
        $validation = 0;
      }
      else
      {
        
        $tAmount = db::select('SELECT chrngtrans.`id` AS chrngtransid, transdate, terminalno, chrngdayid, chrngtransdetail.`id`, payschedid, items, amount, classid, itemclassification.`description`, SUM(amount) AS TAmount
          FROM chrngtransdetail
          INNER JOIN chrngtrans ON chrngtransdetail.`chrngtransid` = chrngtrans.`id`
          INNER JOIN itemclassification ON chrngtransdetail.`classid` = itemclassification.`id`
          WHERE chrngdayid = ? and terminalno = ? and chrngtrans.`cancelled` = 0', [$dayid, $terminalno]);

        if(count($tAmount) > 0)
        {
          // return $tAmount[0]->TAmount;
          $insert = db::table('chrngcrs')
              ->insert([
                'terminalno' => $terminalno,
                'chrngdayid' => $dayid,
                'glid' => 0,
                'debit' => $tAmount[0]->TAmount,
                'transdatetime' => $datetrans
              ]);

          $groupAmount = db::select('select chrngtrans.id as chrngtransid, transdate, terminalno, chrngdayid, chrngtransdetail.id, glid, classid, itemclassification.`description`, sum(amount) as TAmount
            from chrngtransdetail
            inner join chrngtrans on chrngtransdetail.`chrngtransid` = chrngtrans.`id`
            inner join itemclassification on chrngtransdetail.`classid` = itemclassification.`id`
            where chrngdayid = ? and terminalno = ? and chrngtrans.cancelled = 0
            GROUP BY classid;', [$dayid, $terminalno]);

          // return $groupAmount;


          foreach($groupAmount as $gAmount)
          {
             $insert = db::table('chrngcrs')
              ->insert([
                'terminalno' => $terminalno,
                'chrngdayid' => $dayid,
                'glid' => $gAmount->glid,
                'credit' => $gAmount->TAmount,
                'transdatetime' => $datetrans
              ]);
          }

          $updDay = db::table('chrngday')
              ->where('id', $dayid)
              ->update([
                'closedatetime' => CashierModel::getServerDateTime()
              ]);

          $validation = 1;

        }
        
      }

      $data = array(
        'msg' => $msg,
        'validation' => $validation
      );

      echo json_encode($data);
    }
  }

  public function genCRS(Request $request)
  {
    if($request->ajax())
    {
      $dtFrom = $request->get('dtFrom');
      $dtTo = $request->get('dtTo');
      $terminalno = $request->get('terminalno');

      $dtFrom = date_create($dtFrom);
      $dtFrom = date_format($dtFrom, 'Y-m-d 00:00');

      $dtTo = date_create($dtTo);
      $dtTo = date_format($dtTo, 'Y-m-d 23:59');

      $output = '';
      $gtotal = '';

      // $getCRS = db::table('chrngcrs')
      //     ->select('code', 'account', 'debit', 'credit')
      //     ->join('acc_coa', 'chrngcrs.glid', '=', 'acc_coa.id')
      //     ->whereBetween('transdatetime', [$dtFrom. ' 00:00', $dtTo. ' 23:59'])
      //     ->get();

      $datearray = array();

      array_push($datearray, $dtFrom);
      array_push($datearray, $dtTo);

      $cash = db::table('chrngtrans')
        ->where('cancelled', 0)
        ->where('terminalno', $terminalno)
        ->where('posted', 1)
        ->whereBetween('transdate', $datearray)
        ->sum('amountpaid');

      $coa = db::table('acc_coa')
        ->where('id', 3)
        ->first();

      $output .= '
          <tr class="">
            <td class="crs-print">'.$coa->code.' - ' .$coa->account. '</td>
            <td class="crs-print"></td>
            <td class="text-right crs-print">'.number_format($cash, 2).'</td>
            <td class="text-right crs-print"></td>
          </tr>
        ';
        

      // $getCRS = db::select('
      //     SELECT code, account, SUM(debit) AS sumDEBIT, SUM(credit) AS sumCREDIT
      //     FROM chrngcrs
      //     INNER JOIN acc_coa ON chrngcrs.`glid` = acc_coa.`id`
      //     where transdatetime between ? and ?
      //     group by acc_coa.id
      //   ', [$dtFrom. ' 00:00', $dtTo. ' 23:59']);


      $getCRS = db::table('chrngtrans')
        ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
        ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
        ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
        ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
        ->where('terminalno', $terminalno)
        ->where('cancelled', 0)
        ->where('posted', 1)
        ->whereBetween('transdate', $datearray)
        ->groupBy('glid')
        ->get();


      
      
      $debit = 0;
      $credit = 0;
      foreach($getCRS as $CRS)
      {

        // $debit += $CRS->sumDEBIT;
        $credit += $CRS->credit;

        $output .= '
          <tr class="">
            <td class="crs-print">'.$CRS->code.' - ' .$CRS->account. '</td>
            <td class="crs-print"></td>
            <td class="text-right crs-print"></td>
            <td class="text-right crs-print">'.number_format($CRS->credit, 2).'</td>
          </tr>
        ';
      }

      $gtotal .='
        <th class="text-right crs-print" colspan="3">'.number_format($cash, 2).'</th>
        <th class="text-right crs-print" colspan="">'.number_format($credit, 2).'</th>
      ';

      $data = array(
        'output' => $output,
        'gtotal' =>$gtotal
      );

      echo json_encode($data);



    }
  }

  public function getCRSdatetime(Request $request)
  {
    
    $datetime = date_create(CashierModel::getServerDateTime());
    $datetimeFrom = date_format($datetime, 'm/01/Y h:i A');
    $datetimeTo = date_format($datetime, 'm/d/Y h:i A');
    $syDesc = CashierModel::getSYDesc();


    $data = array(
      'datefrom' => $datetimeFrom,
      'dateto' => $datetimeTo,
      'syDesc' => $syDesc
    );

    echo json_encode($data);
 
  }

  public function genledger(Request $request)
  {
    if($request->ajax())
    {
      $studid = $request->get('studid');


    }
  }

  public function itemSearch(Request $request)
  {
    if($request->ajax())
    {
      $itemname = $request->get('itemname');

      $studid = $request->get('studid');

      $studstatus = 0;

      if($studid != '')
      {
        $studinfo = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        $studstatus = $studinfo->studstatus;
      }
      else
      {
        $studstatus = 0;
      }


      if($studid != '')
      {
        if($studstatus == 0)
        {
          $items = db::table('items')
              ->where('description', 'like', '%'. $itemname . '%')
              ->where('deleted', 0)
              ->where('isreceivable', 0)
              ->where('isexpense', 0)
              ->orWhere('itemcode', 'like', '%'. $itemname . '%')
              ->where('deleted', 0)
              ->where('isreceivable', 0)
              ->where('isexpense', 0)
              ->orderBy('isdp', 'DESC')
              ->orderBy('description', 'ASC')
              ->get();
        }
        else
        {
          $items = db::table('items')
              ->where('description', 'like', '%'. $itemname . '%')
              ->where('deleted', 0)
              ->where('isreceivable', 0)
              ->where('isdp', 0)
              ->where('isexpense', 0)
              ->orWhere('itemcode', 'like', '%'. $itemname . '%')
              ->where('deleted', 0)
              ->where('isreceivable', 0)
              ->where('isdp', 0)
              ->where('isexpense', 0)
              ->orderBy('isdp', 'DESC')
              ->orderBy('description', 'ASC')
              ->get();
        }
      }
      else
      {
        $items = db::table('items')
              ->where('description', 'like', '%'. $itemname . '%')
              ->where('deleted', 0)
              ->where('isreceivable', 0)
              ->where('isdp', 0)
              ->where('isexpense', 0)
              ->orWhere('itemcode', 'like', '%'. $itemname . '%')
              ->where('deleted', 0)
              ->where('isreceivable', 0)
              ->where('isdp', 0)
              ->where('isexpense', 0)
              ->orderBy('isdp', 'DESC')
              ->orderBy('description', 'ASC')
              ->get();
      }
      $list = '';
      $dp = '';
      foreach($items as $item)
      {
        if($item->isdp == 1)
        {
          $dp = '<i class="fa fa-check text-primary text-center"></i>';
        }
        else
        {
          $dp = '';
        }

        $list .='
          <tr class="" class-id="'.$item->classid.'" data-id="'.$item->id.'">
            <td class="text-center">'.$dp.'</td>
            <td>'.$item->itemcode.'</td>
            <td class="desc">'.$item->description.'</td>
            <td class="amount">'.number_format($item->amount, 2).'</td>
          </tr>
        ';
      }

      $data = array(
        'list' => $list
      );

      echo json_encode($data);
    }
  }

  public function getTno(Request $request)
  {
    if($request->ajax())
    {
      $transno = CashierModel::getTransNo();
      return $transno;
    }
  }



  public function cashtransitem(Request $request)
  {
    if($request->ajax())
    {
      $terminalno = $request->get('terminalno');
      $transno = $request->get('transno');
      $studid = $request->get('studid');
      $itemid = $request->get('detailid');
      $classid = $request->get('classid');
      $dayid = $request->get('dayid');
      $particulars = $request->get('particulars');
      $amount = $request->get('amount');

      $insCashTrans = db::table('chrngcashtrans')
          ->insertGetId([
            'dayid' => $dayid,
            'transno' => $transno,
            'payscheddetailid' => $itemid,
            'classid' => $classid,
            'particulars' => $particulars,
            'itemprice' => $amount,
            'qty' => 1,
            'amount' => $amount,
            'deleted' => 0,
            'studid' => $studid,
            'syid' => CashierModel::getSYID(),
            'terminalno' => $terminalno,
            'transdone' => 1,
            'transdatetime' => CashierModel::getServerDateTime(),
            'createdby' => auth()->user()->id
          ]);

      $getOrderLines = CashierModel::getOrderLines($transno, $insCashTrans);

      // return $output;

      $data = array(
        'output' => $getOrderLines
      );

      echo json_encode($data);
    }
  }

  public function viewCashTrans(Request $request)
  {
    $schoolinfo = Db::table('schoolinfo')
      ->first();
      $transid = $request->get('formtrans-id');
      $tDetail = db::table('chrngtrans')
          ->select('chrngtrans.id', 'sid', 'studname', 'glevel', 'amountpaid', 'transdate', 'ornum',  'cancelled')
          ->where('chrngtrans.id', $transid)
          ->get();
      $items = Db::table('chrngtransdetail')
        ->select('items','itemprice','amount','qty')
        ->where('chrngtransid', $transid)
        ->get();

      $studname = '';
      $sid = '';
      $gradesection = '';
      $ornum = '';
      $totalamountpay = '';
      $tdate='';
      $void = $tDetail[0]->cancelled;
      
      if($tDetail[0]->glevel == null || $tDetail[0]->glevel == "")
      {
          $explodelevelsection = ["",""];
      }else{
          $explodelevelsection = explode(' - ', $tDetail[0]->glevel);
      }
      foreach($tDetail as $detail)
      {
        $studname = $detail->studname;
        $sid = $detail->sid;
        $gradesection = $detail->glevel;
        $ornum = $detail->ornum;
        $totalamountpay = $detail->amountpaid;
        $tdate = date_create($detail->transdate);
        $tdate = date_format($tdate, 'M d, Y');
      }
      $wnum = strtok($totalamountpay, '.');
      $dec = substr(number_format($totalamountpay, 2), strpos($totalamountpay, '.') + 2);
      $wnum = strtoupper(NumConvert::word($wnum));
      if($dec > 0)
      {
        $dec = 'AND ' . $dec . '/100';
      }
      else
      {
        $dec = '';
      }
      $data = array(
        'items' => $items,
        'studname' => $studname,
        'gradelevel' => $explodelevelsection[0],
        'sid' => $sid,
        'section' =>$explodelevelsection[1],
        'ornum' => $ornum,
        'totalamountpay' => $totalamountpay,
        'tdate' => $tdate,
        'datenow' => date('m-d-Y'),
        'void' => $void,
        'schoolinfo' => $schoolinfo,
        'numtowords' => $wnum . ' PESOS ' . $dec . ' ('. number_format($totalamountpay, 2) .')' //strtoupper(NumConvert::word($tPaid)) 
      );
    if(strtolower($schoolinfo->snr) == 'hccsi')// ------------------------------------------[shane]
    {
     $pdf = PDF::loadView('pdf.cashtrans_hccsi', $data)->setPaper('8.5x11','portrait');
     $pdf->getDomPDF()->set_option("enable_php", true);
     return $pdf->stream('Cash Transaction - '.$data['studname'].'.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'sic')// ----------------------------------------[shane]
    {
      $pdf = PDF::loadView('pdf.cashtrans_sic', $data)->setPaper('8.5x11','portrait');
      $pdf->getDomPDF()->set_option("enable_php", true);
      return $pdf->stream('Cash Transaction - '.$data['studname'].'.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'sait')// ---------------------------------------[shane]
    {
      $pdf = PDF::loadView('pdf.cashtrans_sait', $data)->setPaper('8.5x11','portrait');
      $pdf->getDomPDF()->set_option("enable_php", true);
      return $pdf->stream('Cash Transaction - '.$data['studname'].'.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'bct')// ----------------------------------------[shane]
    {
      $pdf = PDF::loadView('pdf.cashtrans_bct', $data)->setPaper('8.5x11','portrait');
      $pdf->getDomPDF()->set_option("enable_php", true);
      return $pdf->stream('Cash Transaction - '.$data['studname'].'.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'shjms')// ----------------------------------------[shane]
    {
      $pdf = PDF::loadView('pdf.cashtrans_shjms', $data)->setPaper('8.5x11','portrait');
      $pdf->getDomPDF()->set_option("enable_php", true);
      return $pdf->stream('Cash Transaction - '.$data['studname'].'.pdf');
    }else{
      // return $data;
      $pdf = PDF::loadView('pdf.cashtrans_default', $data)->setPaper('8.5x11','portrait');
      $pdf->getDomPDF()->set_option("enable_php", true);
      return $pdf->stream('Cash Transaction - '.$data['studname'].'.pdf');
    }
  }

  public function printcashtrans($terminalid, $dtfrom, $dtto, $filter)
  {

    $studinfo = db::table('schoolinfo')
        ->first();

    $schoolname = $studinfo->schoolname;
    $schooladdress = $studinfo->address;

    $dtfrom = date_create($dtfrom);
    $dtfrom = date_format($dtfrom, 'm/d/Y');

    $dtto = date_create($dtto);
    $dtto = date_format($dtto, 'm/d/Y');

    $daterange = 'PERIOD: ' . $dtfrom . ' - ' . $dtto;

    if($filter = '""')
    {
      $filter = '';
    }

    

    // return $terminalid . ' ' . $dtfrom . ' ' . $dtto . ' ' . $filter;

    $from = date_create($dtfrom);
    // $from = date_create('01/01/2020');
    $from = date_format($from, 'Y-m-d');
    $to = date_create($dtto);
    // $to = date_create('01/31/2020');
    $to = date_format($to, 'Y-m-d');

    $from .= ' 00:00';
    $to .= ' 23:59';
    
    $transactions = db::table('chrngtrans')
        ->select('chrngtrans.id', 'ornum', 'transdate', 'totalamount', 'amountpaid', 'studid', 'particulars', 'terminalno', 'transby', 'studname', 'posted', 'sid', 'glevel', 'name', 'cancelled', 'paymenttype.description')
        ->join('users', 'chrngtrans.transby', '=', 'users.id')
        ->join('paymenttype', 'chrngtrans.paytype', '=', 'paymenttype.description')
        ->where('terminalno', $terminalid)
        ->where('ornum', 'like', '%'. $filter . '%')
        ->whereBetween('transdate', [$from, $to])
        ->orWhere('terminalno', $terminalid)
        ->where('studname', 'like', '%'. $filter . '%')
        ->whereBetween('transdate', [$from, $to])
        ->get();


    // return $transactions;

    // $transsummary = db::select('
    //     select sum(amountpaid) as totalamount, paytype
    //     from chrngtrans
    //     where transdate between ? and ? and cancelled = 0
    //     group by paytype
    //   ', [$from, $to]);


    $transsummary = db::table('chrngtrans')
      ->select(db::raw('sum(amountpaid) as totalamount, paytype'))
      ->join('users', 'chrngtrans.transby', '=', 'users.id')
      ->join('paymenttype', 'chrngtrans.paytype', '=', 'paymenttype.description')
      ->where('terminalno', $terminalid)
      ->where('ornum', 'like', '%'. $filter . '%')
      ->whereBetween('transdate', [$from, $to])
      ->where('cancelled', 0)
      ->orWhere('terminalno', $terminalid)
      ->where('studname', 'like', '%'. $filter . '%')
      ->whereBetween('transdate', [$from, $to])
      ->where('cancelled', 0)
      ->groupBy('paytype')
      ->get();

      // return $transsummary;
      

    $data = array(
      'terminaid' => $terminalid,
      'schoolname' => $schoolname,
      'schooladdress' => $schooladdress,
      'daterange' => $daterange,
      'transactions' => $transactions,
      'datenow' => CashierModel::getServerDateTime(),
      'transsummary' => $transsummary
    );

    // $customPaper = array(0,0,)

    $pdf = PDF::loadView('/printcashtrans', $data)->setPaper('legal','landscape');
    $pdf->getDomPDF()->set_option("enable_php", true);
    return $pdf->stream('test.pdf');

    // return view('/printcashtrans')
    //   ->with('terminalid', $terminalid);


  }

  public function getQTY(Request $request)
  {
    if($request->ajax())
    {
      $transno = $request->get('transno');
      $cashtransid = $request->get('cashtransid');
      $qty = $request->get('qty');

      $cashitem = db::table('chrngcashtrans')
          ->where('id', $cashtransid)
          ->first();

      if($cashitem->transdone == 1)
      {
        $amount = $cashitem->itemprice * $qty;

        $updItem = db::table('chrngcashtrans')
            ->where('id', $cashtransid)
            ->update([
              'qty' => $qty,
              'amount' => $amount
            ]);

        $data = array(
          'return' => 1,
          'output' => CashierModel::getOrderLines($transno, $cashtransid)
        );
      }
      else
      {
        $data = array(
          'return' => 0
        );
      }

      echo json_encode($data);

    }
  }

  public function editAmount(Request $request)
  {
    if($request->ajax())
    {
      $transno = $request->get('transno');
      $cashtransid = $request->get('cashtransid');
      $price = $request->get('price');
      $amount = 0;

      $cashitem = db::table('chrngcashtrans')
          ->where('id', $cashtransid)
          ->first();

      // if($cashitem->transdone == 1)
      if(1>0)
      {
        $amount = $price * $cashitem->qty;

        $update = db::table('chrngcashtrans')
            ->where('id', $cashtransid)
            ->update([
              'itemprice' => $price,
              'amount' => $amount
            ]);

        $data = array(
          'return' => 1,
          'output' => CashierModel::getOrderLines($transno, $cashtransid)
        );

      }
      else
      {
        $data = array(
          'return' => 0
        );
      }

      echo json_encode($data);

    }
  }

  public function getoline(Request $request)
  {
    if($request->ajax())
    {
      $transno = $request->get('transno');

      $orderlists = db::table('chrngcashtrans')
          ->where('transno', $transno)
          ->where('transdone', 1)
          ->get();

      if(count($orderlists) > 0)
      {
        return 1;
      }
      else
      {
        return 0;
      }
    }
  }

  public function voidtrans(Request $request)
  {
    if($request->ajax())
    {
      $transid = $request->get('transid');
      $uname = $request->get('uname');
      $pword = $request->get('pword');

      // return $uname;

      $checkuser = db::table('users')
          ->where('email', $uname)
          ->get();
          // return $checkuser;
      if(count($checkuser) > 0)
      {
        $checkpermission = db::table('chrngpermission')
            ->where('userid', $checkuser[0]->id)
            ->get();

        // return $checkpermission;
        if(count($checkpermission) > 0)
        {

          if(hash::check($pword, $checkuser[0]->password))
          {

            $trans = db::table('chrngtrans')
                ->where('id', $transid)
                ->first();

            $updTrans = db::table('chrngtrans')
                ->where('id', $transid)
                ->update([
                  'cancelled' => 1,
                  'cancelledby' => auth()->user()->id,
                  'cancelleddatetime' => CashierModel::getServerDateTime()
                ]);

            $updLedger = db::table('studledger')
                ->where('transid', $trans->id)
                ->update([
                  'void' => 1,
                  'voidby' => auth()->user()->id,
                  'voiddatetime' => CashierModel::getServerDateTime()
                ]);

            if($trans->studid > 0)
            {
             
              $transdetail = db::table('chrngtransdetail')
                  ->where('chrngtransid', $transid)
                  ->get();

              foreach($transdetail as $detail)
              {
                if($detail->itemkind == 0)
                {
                  $schedAmount = 0;
                  $bal = 0;

                  $paysched = db::table('studpayscheddetail')
                      ->where('id', $detail->payschedid)
                      ->first();

                  $schedAmount = $paysched->amountpay - $detail->amount;
                  $bal = $paysched->amount - $schedAmount;

                  $updstudpayscheddetail = db::table('studpayscheddetail')
                      ->where('id', $detail->payschedid)
                      ->update([
                        'amountpay' => $schedAmount,
                        'balance' => $bal,
                        'updateddatetime' => CashierModel::getServerDateTime(),
                        'updatedby' => auth()->user()->id
                      ]);
                }
              }
              return 1;
            
            }

          }
          else
          {
            return 0;
          }
        }
        else
        {
          return 0;
        }

      }
      else
      {
        return 5;
      }

    }
  }

  public function onlinepay(Request $request)
  {
    if($request->ajax())
    {
      $payments = '';
      $transdate= "";
      $lists = db::table('onlinepayments')
          ->select('onlinepayments.*', 'studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 
            'paymenttype.description', 'amount')
          ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.qcode')
          ->join('paymenttype', 'onlinepayments.paymenttype', '=', 'paymenttype.id')
          ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
          ->where('isapproved', 1)
          ->get();


      foreach($lists as $list)
      {
        $tdate = date_create($list->TransDate);
        $transdate = date_format($tdate, 'm-d-Y');
        $transdate2 = date_format($tdate, 'Y-m-d');
        $studname = $list->lastname . ', ' . $list->firstname . ' ' . $list->middlename . ' ' . $list->suffix;
        $payments .='
          <tr style="cursor: pointer;" ol-id="'.$list->id.'" data-id="'.$list->studid.'" stud-name="'.strtoupper($studname) . ' - ' . $list->levelname .'" data-level="'.$list->levelname.'">
            <td class="ol-studname">'.strtoupper($studname).'</td>
            <td>'.$list->queingcode.'</td>
            <td class="ol-paytype" data-id="'.$list->paymentType.'" data-value="'.$list->bankName.'">'.$list->description.'</td>
            <td class="ol-amount" data-value="'.$list->amount.'">'.number_format($list->amount, 2).'</td>
            <td class="ol-refnum">'.$list->refNum.'</td>
            <td class="ol-transdate" data-value="'.$transdate2.'">'.$transdate.'</td>
          </tr>
        ';
      }

      $lists = db::table('onlinepayments')
          ->select('onlinepayments.*', 'studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 
            'paymenttype.description', 'amount')
          ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.sid')
          ->join('paymenttype', 'onlinepayments.paymenttype', '=', 'paymenttype.id')
          ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
          ->where('isapproved', 1)
          ->get();


      foreach($lists as $list)
      {
        $tdate = date_create($list->TransDate);
        $transdate = date_format($tdate, 'm-d-Y');
        $transdate2 = date_format($tdate, 'Y-m-d');
        $studname = $list->lastname . ', ' . $list->firstname . ' ' . $list->middlename . ' ' . $list->suffix;
        $payments .='
          <tr style="cursor: pointer;" ol-id="'.$list->id.'" data-id="'.$list->studid.'" stud-name="'.strtoupper($studname) . ' - ' . $list->levelname .'" data-level="'.$list->levelname.'">
            <td class="ol-studname">'.strtoupper($studname).'</td>
            <td>'.$list->queingcode.'</td>
            <td class="ol-paytype" data-id="'.$list->paymentType.'" data-value="'.$list->bankName.'">'.$list->description.'</td>
            <td class="ol-amount" data-value="'.$list->amount.'">'.number_format($list->amount, 2).'</td>
            <td class="ol-refnum">'.$list->refNum.'</td>
            <td class="ol-transdate" data-value="'.$transdate2.'">'.$transdate.'</td>
          </tr>
        ';
      }

      $lists = db::table('onlinepayments')
          ->select('onlinepayments.*', 'studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 
            'paymenttype.description', 'amount')
          ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.lrn')
          ->join('paymenttype', 'onlinepayments.paymenttype', '=', 'paymenttype.id')
          ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
          ->where('isapproved', 1)
          ->get();


      foreach($lists as $list)
      {
        $tdate = date_create($list->TransDate);
        $transdate = date_format($tdate, 'm-d-Y');
        $transdate2 = date_format($tdate, 'Y-m-d');
        $studname = $list->lastname . ', ' . $list->firstname . ' ' . $list->middlename . ' ' . $list->suffix;
        $payments .='
          <tr style="cursor: pointer;" ol-id="'.$list->id.'" data-id="'.$list->studid.'" stud-name="'.strtoupper($studname) . ' - ' . $list->levelname .'" data-level="'.$list->levelname.'">
            <td class="ol-studname">'.strtoupper($studname).'</td>
            <td>'.$list->queingcode.'</td>
            <td class="ol-paytype" data-id="'.$list->paymentType.'" data-value="'.$list->bankName.'">'.$list->description.'</td>
            <td class="ol-amount" data-value="'.$list->amount.'">'.number_format($list->amount, 2).'</td>
            <td class="ol-refnum">'.$list->refNum.'</td>
            <td class="ol-transdate" data-value="'.$transdate2.'">'.$transdate.'</td>
          </tr>
        ';
      }

      $data = array(
          'list' => $payments
        );

      echo json_encode($data);
    }
  }

  public function onlinedetail(Request $request)
  {
    if($request->ajax())
    {

      $dataid = $request->get('dataid');
      $divAmount = 0;
     

      $studinfo = db::table('onlinepayments')
          ->select('studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelid')
          ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.qcode')
          ->where('onlinepayments.id', $dataid)
          ->get();

      if(count($studinfo) == 0)
      {
        $studinfo = db::table('onlinepayments')
          ->select('studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelid')
          ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.sid')
          ->where('onlinepayments.id', $dataid)
          ->get();        

        if(count($studinfo) == 0)
        {
          $studinfo = db::table('onlinepayments')
            ->select('studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelid')
            ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.lrn')
            ->where('onlinepayments.id', $dataid)
            ->get();                  
        }


        // return $studinfo;
      }


      $studid = $studinfo[0]->studid;

      $transnum = db::select('select max(transno) as transno from transcounter');
      $transno = $transnum[0]->transno;
      // return $transno;

      $terminalno = $request->get('terminalno');
      // $transno = $request->get('transno');
      $dayid = $request->get('dayid');

      // $this->paytui($studid, $detail->tuitionMonth, $amount, $transno, $transnum, $terminalno, $dayid);

      $oldetail = db::table('onlinepaymentdetails')
          ->where('headerid', $dataid)
          ->where('deleted', 0)
          ->orderBy('paykind', 'ASC')
          ->get();

      $olpaysum = db::table('onlinepayments')
          ->where('id', $dataid)
          ->sum('amount');

      $oldetailsum = db::table('onlinepaymentdetails')
          ->where('headerid', $dataid)
          ->where('deleted', 0)
          ->sum('amount');

      $oldetailcount = db::table('onlinepaymentdetails')
          ->where('headerid', $dataid)
          ->where('deleted', 0)
          ->count('id');
      // return $olpaysum . ' ' . $oldetailsum;

      
      if($olpaysum > $oldetailsum)
      {
        $divAmount = $olpaysum;

        // $divAmount = $divAmount / $oldetailcount;

        foreach($oldetail as $detail)
        {
          if($detail->paykind == 1)
          { 
            $itemid = $detail->payscheddetailid;
            $classid = $detail->classid;
            $particulars = $detail->description;
            $amount = $detail->amount;  

            $insCashTrans = db::table('chrngcashtrans')
              ->insertGetId([
                'dayid' => $dayid,
                'transno' => $transno,
                'payscheddetailid' => $itemid,
                'classid' => $classid,
                'particulars' => $particulars,
                'itemprice' => $amount + $divAmount,
                'qty' => $detail->quantity,
                'amount' => $divAmount,
                'deleted' => 0,
                'studid' => $studid,
                'syid' => CashierModel::getSYID(),
                'terminalno' => $terminalno,
                'transdone' => 1,
                'transdatetime' => CashierModel::getServerDateTime(),
                'createdby' => auth()->user()->id
              ]);
            $divAmount = 0;
            // return '> ' . $divAmount;
          }
          else
          {
            $this->paytui($studid, $detail->payscheddetailid, $detail->tuitionMonth, $detail->amount, $transno, $transnum, $terminalno, $dayid);
            $divAmount -= $detail->amount;
            // return '>';
          }
        }
      }
      else if($olpaysum == $oldetailsum)
      {
        
        foreach($oldetail as $detail)
        {
          if($detail->paykind == 1)
          { 
            $itemid = $detail->payscheddetailid;
            $classid = $detail->classid;
            $particulars = $detail->description;
            $amount = $detail->amount;  

            $insCashTrans = db::table('chrngcashtrans')
              ->insertGetId([
                'dayid' => $dayid,
                'transno' => $transno,
                'payscheddetailid' => $itemid,
                'classid' => $classid,
                'particulars' => $particulars,
                'itemprice' => $amount,
                'qty' => $detail->quantity,
                'amount' => $amount,
                'deleted' => 0,
                'studid' => $studid,
                'syid' => CashierModel::getSYID(),
                'terminalno' => $terminalno,
                'transdone' => 1,
                'transdatetime' => CashierModel::getServerDateTime(),
                'createdby' => auth()->user()->id
              ]);
            $divAmount = 0;
            // return '== ' . $divAmount;
          }
          else
          {

            // $insCashTrans = 

            $this->paytui($studid, $detail->payscheddetailid, $detail->tuitionMonth, $detail->amount, $transno, $transnum, $terminalno, $dayid);

          }
        }
      }
      else
      {
        // echo $oldetail;
        $divAmount = $olpaysum;

        foreach($oldetail as $detail)
        {

          if($detail->paykind == 1)
          {
            $itemid = $detail->payscheddetailid;
            $classid = $detail->classid;
            $particulars = $detail->description;
            $amount = $detail->amount;  
            
            if($divAmount >= $detail->amount)
            {
              $itemid = $detail->payscheddetailid;
              $classid = $detail->classid;
              $particulars = $detail->description;
              $amount = $detail->amount;  

              $insCashTrans = db::table('chrngcashtrans')
                ->insertGetId([
                  'dayid' => $dayid,
                  'transno' => $transno,
                  'payscheddetailid' => $itemid,
                  'classid' => $classid,
                  'particulars' => $particulars,
                  'itemprice' => $amount,
                  'qty' => $detail->quantity,
                  'amount' => $amount,
                  'deleted' => 0,
                  'studid' => $studid,
                  'syid' => CashierModel::getSYID(),
                  'terminalno' => $terminalno,
                  'transdone' => 1,
                  'transdatetime' => CashierModel::getServerDateTime(),
                  'createdby' => auth()->user()->id
                ]);

              $divAmount -= $detail->amount;
            }
            else
            {
              $insCashTrans = db::table('chrngcashtrans')
                ->insertGetId([
                  'dayid' => $dayid,
                  'transno' => $transno,
                  'payscheddetailid' => $itemid,
                  'classid' => $classid,
                  'particulars' => $particulars,
                  'itemprice' => $divAmount,
                  'qty' => $detail->quantity,
                  'amount' => $divAmount,
                  'deleted' => 0,
                  'studid' => $studid,
                  'syid' => CashierModel::getSYID(),
                  'terminalno' => $terminalno,
                  'transdone' => 1,
                  'transdatetime' => CashierModel::getServerDateTime(),
                  'createdby' => auth()->user()->id
                ]);
            }

            // return '< ' . $divAmount;
          }
          else
          {

            // echo $detail->amount . '; ';
            // return $divAmount;
            if($divAmount > 0)
            {
              if($divAmount >= $detail->amount)
              {
                // echo ' - ' . $divAmount . ' >= ' . $detail->amount . ';';
                // $insCashTrans = 
                $this->paytui($studid, $detail->payscheddetailid, $detail->tuitionMonth, $detail->amount, $transno, $transnum, $terminalno, $dayid);
                // $insCashTrans = $this->paytui($studid, $detail->payscheddetailid, $detail->tuitionMonth, $detail->amount, $transno, $transnum, $terminalno, $dayid);

                $divAmount -= $detail->amount;
              }
              else
              {
                // $insCashTrans = 
                // echo ' - ' . $divAmount . ' < ' . $detail->amount . ';';
                $this->paytui($studid, $detail->payscheddetailid, $detail->tuitionMonth, $divAmount, $transno, $transnum, $terminalno, $dayid);

                $divAmount = 0;
              }
              // echo $divAmount . '; ';
            }
          }
        }
      }

      // return $divAmount;
      if($divAmount > 0)
      {
        // echo 'last-divamount: ' . $divAmount;
        $duedates = '';

        $od = db::table('onlinepaymentdetails')
            ->where('headerid', $dataid)
            ->where('deleted', 0)
            ->where('paykind', 2)
            ->orderBy('paykind', 'ASC')
            ->get();

        if(count($od) > 0)
        {
          foreach($od as $o)
          {
            if($duedates == '')
            {
              $duedates .= $o->tuitionMonth;  
            }
            else
            {
              $duedates .= ', ' . $o->tuitionMonth;
            }
            
          }

          // $duedates = str_replace('[', '(', $duedates);
          // $duedates = str_replace(']', ')', $duedates);
          // return '>divamount';
          $duedates = '(' . $duedates . ')';


          $paysched = db::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', CashierModel::getSYID())
            ->where('semid', CashierModel::getSemID())
            ->where('balance', '>', 0)
            ->where(function($q) use($duedates){
              if($duedates != '()')
              {
                $q->whereRaw('MONTH(duedate) NOT IN ' . $duedates);    
              }
            })
            ->orderBy('duedate', "ASC")
            ->orderBy('id', "ASC")
            ->get();

            // return $paysched;

          foreach($paysched as $sched)
          {
            $duedate = date_create($sched->duedate);
            $duedateint = date_format($duedate, 'n');
            $duedateword = strtoupper(date_format($duedate, 'F'));

            if($divAmount >= $sched->balance)
            {
              $insCashTrans = db::table('chrngcashtrans')
                  ->insertGetId([
                    'dayid' => $dayid,
                    'transno' => $transno,
                    'payscheddetailid' => $sched->id,
                    'classid' => $sched->classid,
                    'particulars' => $sched->particulars . ' - ' . $duedateword,
                    'itemprice' => $sched->balance,
                    'qty' => $detail->quantity,
                    'amount' => $sched->balance,
                    'deleted' => 0,
                    'studid' => $studid,
                    'syid' => CashierModel::getSYID(),
                    'terminalno' => $terminalno,
                    'transdone' => 0,
                    'transdatetime' => CashierModel::getServerDateTime(),
                    'createdby' => auth()->user()->id
                  ]);

              $divAmount -= $sched->balance;
            }
            else
            { 

              if($divAmount > 0)
              {
                $insCashTrans = db::table('chrngcashtrans')
                    ->insertGetId([
                      'dayid' => $dayid,
                      'transno' => $transno,
                      'payscheddetailid' => $sched->id,
                      'classid' => $sched->classid,
                      'particulars' => $sched->particulars . ' - ' . $duedateword,
                      'itemprice' => $divAmount,
                      'qty' => $detail->quantity,
                      'amount' => $divAmount,
                      'deleted' => 0,
                      'studid' => $studid,
                      'syid' => CashierModel::getSYID(),
                      'terminalno' => $terminalno,
                      'transdone' => 0,
                      'transdatetime' => CashierModel::getServerDateTime(),
                      'createdby' => auth()->user()->id
                    ]); 
              }

              $divAmount = 0;   
              break;        
            }
          }
        }
      }


      $selItem = db::table('chrngcashtrans')
          ->where('transno', $transno)
          ->orderBy('id', 'DESC')
          ->first();



      $getOrderLines = CashierModel::getOrderLines($transno, $selItem->id);

      // return $getOrderLines;

      $data = array(
        'output' => $getOrderLines
      );

      echo json_encode($data);

    }
  }

  public function paytui($studid, $detailid, $month, $amount, $transno, $transnum, $terminalno, $dayid)
  {

    $schedinfo = db::table('studpayscheddetail')
        ->where('id', $detailid)
        ->first();
    // echo $schedinfo;

    if($schedinfo->duedate == NULL)
    {
      $paysched = db::table('studpayscheddetail')
        ->where('id', $detailid)
        ->get();
    }
    else
    {

      $stud = db::table('studinfo')
          ->where('id', $studid)
          ->first();

      if($stud->levelid == 14 || $stud->levelid == 15)
      {
        $paysched = db::table('studpayscheddetail')
        ->where('studid', $studid)
        ->where('syid', CashierModel::getSYID())
        ->where('semid', CashierModel::getSemID())
        ->where('balance', '>', 0)
        ->whereMonth('duedate', $month)
        ->orderBy('duedate', "ASC")
        ->orderBy('id', "ASC")
        ->get();  
      }
      else
      {
        $paysched = db::table('studpayscheddetail')
        ->where('studid', $studid)
        ->where('syid', CashierModel::getSYID())
        // ->where('semid', CashierModel::getSemID())
        ->where('balance', '>', 0)
        ->whereMonth('duedate', $month)
        ->orderBy('duedate', "ASC")
        ->orderBy('id', "ASC")
        ->get();
      }

      
    }

    // echo 'detail: '. $paysched;
    
    // return $paysched;
    // echo 'pay: ' . $paysched . '; ';

    $curamount = $amount;
    // echo $month;
    foreach($paysched as $sched)
    {
      $duedate = date_create($sched->duedate);
      $duedateint = date_format($duedate, 'n');
      $duedateword = strtoupper(date_format($duedate, 'F'));
      
      // if($month != $duedateint)
      if(1 > 0)
      {
        // echo $curamount . ' >= ' . $sched->balance . '  ';
        if($curamount >= $sched->balance)
        {
          // echo 'payschedid: ' . $sched->id . ' ';
          $insCashTrans = db::table('chrngcashtrans')
            ->insertGetId([
              'dayid' => $dayid,
              'transno' => $transno,
              'payscheddetailid' => $sched->id,
              'classid' => $sched->classid,
              'particulars' => $sched->particulars . ' - ' . $duedateword,
              'itemprice' => $sched->balance,
              'qty' => 1,
              'amount' => $sched->balance,
              'duedate' => $sched->duedate,
              'deleted' => 0,
              'studid' => $studid,
              'syid' => CashierModel::getSYID(),
              'terminalno' => $terminalno,
              'transdone' => 0,
              'transdatetime' => CashierModel::getServerDateTime(),
              'createdby' => auth()->user()->id
            ]);  

          // echo $insCrashTrans . '; ';


          $curamount -= $sched->balance;

        }
        else
        {
          if($curamount > 0)
          {
            $insCashTrans = db::table('chrngcashtrans')
              ->insertGetId([
                'dayid' => $dayid,
                'transno' => $transno,
                'payscheddetailid' => $sched->id,
                'classid' => $sched->classid,
                'particulars' => $sched->particulars . ' - ' . $duedateword,
                'itemprice' => $curamount,
                'qty' => 1,
                'amount' => $curamount,
                'duedate' => $sched->duedate,
                'deleted' => 0,
                'studid' => $studid,
                'syid' => CashierModel::getSYID(),
                'terminalno' => $terminalno,
                'transdone' => 0,
                'transdatetime' => CashierModel::getServerDateTime(),
                'createdby' => auth()->user()->id
              ]); 

            // echo $insCashTrans . '; ';
            $curamount = 0;
          }
        }  
      }
    }

    if($curamount > 0)
    {
      $chrngcashtrans = db::table('chrngcashtrans')
          ->where('transno', $transno)
          ->groupBy('duedate')
          ->get();

      $duedates = '';
      $due = '';
      if(count($chrngcashtrans) > 0)
      {
        foreach($chrngcashtrans as $trans)
        {
          $due = date_create($trans->duedate);
          $due = date_format($due, 'm');

          if($duedates == '')
          {
            $duedates .= $due;
          }
          else
          {
            $duedates .= ',' . $due;
          }
        }
      }
      $duedates = '('. $duedates . ')';
      // echo $duedates;


      $paysched = db::table('studpayscheddetail')
        ->where('studid', $studid)
        ->where('syid', CashierModel::getSYID())
        ->where('semid', CashierModel::getSemID())
        ->where('balance', '>', 0)
        ->whereRaw('MONTH(duedate) NOT IN ' . $duedates)
        ->orderBy('duedate', "ASC")
        ->orderBy('id', "ASC")
        ->get();

      foreach($paysched as $sched)
      {
        $duedate = date_create($sched->duedate);
        $duedateint = date_format($duedate, 'n');
        $duedateword = strtoupper(date_format($duedate, 'F'));

        if($curamount >= $sched->balance)
        {
          $insCashTrans = db::table('chrngcashtrans')
            ->insertGetId([
              'dayid' => $dayid,
              'transno' => $transno,
              'payscheddetailid' => $sched->id,
              'classid' => $sched->classid,
              'particulars' => $sched->particulars . ' - ' . $duedateword,
              'itemprice' => $sched->balance,
              'qty' => 1,
              'amount' => $sched->balance,
              'deleted' => 0,
              'studid' => $studid,
              'syid' => CashierModel::getSYID(),
              'terminalno' => $terminalno,
              'transdone' => 0,
              'transdatetime' => CashierModel::getServerDateTime(),
              'createdby' => auth()->user()->id
            ]);  
          // echo $insCashTrans . '; ';
          $curamount -= $sched->balance;
        }
        else
        {
          if($curamount > 0)
          {
            $insCashTrans = db::table('chrngcashtrans')
              ->insertGetId([
                'dayid' => $dayid,
                'transno' => $transno,
                'payscheddetailid' => $sched->id,
                'classid' => $sched->classid,
                'particulars' => $sched->particulars . ' - ' . $duedateword,
                'itemprice' => $curamount,
                'qty' => 1,
                'amount' => $curamount,
                'duedate' => $sched->duedate,
                'deleted' => 0,
                'studid' => $studid,
                'syid' => CashierModel::getSYID(),
                'terminalno' => $terminalno,
                'transdone' => 0,
                'transdatetime' => CashierModel::getServerDateTime(),
                'createdby' => auth()->user()->id
              ]); 
            // echo $insCashTrans . '; ';
            $curamount = 0;
          }
        }
      }

    }
    
    return $curamount;
  }

  public function checkOLPay(Request $request)
  {
    if($request->ajax())
    {
      return CashierModel::checkOLPay();
    }
  }

  public function getActiveInfo(Request $request)
  {
    $sydesc = CashierModel::getSYDesc();
    $semdesc = CashierModel::getSemDesc();

    $data = array(
      'sydesc' => $sydesc,
      'semdesc' => $semdesc
    );

    echo json_encode($data);
  }

  public function changepass(Request $request)
  {
    if($request->ajax())
    {
      $oldpass = $request->get('oldpass');
      $newpass = $request->get('newpass');
      $confirm = $request->get('confirmpass');

      if(hash::check($oldpass, auth()->user()->password))
      {

        db::table('users')
          ->where('id', auth()->user()->id)
          ->update([
            'password' => hash::make($newpass)
          ]);

        return 1;
      }
      else
      {
        return 2;
      }


    }
  }

  public function checklogo(Request $request)
  {
    if($request->ajax())
    {
      $schoolinfo = db::table('schoolinfo')
        ->first();
      
      $logopath = $schoolinfo->essentiellink .'/'. $schoolinfo->picurl;
      $esURL = $schoolinfo->essentiellink;

      // return asset('/' . $logopath);
      if(File::exists($logopath))
      {
        return 1;
      }
      else
      {
        $url = $logopath; //'http://essentielv2.ck/schoollogo/schoollogo.png';
        $name = substr($url, strrpos($url, '/') + 1);
        $url = file_get_contents($url);
        File::put('schoollogo/' .$name, $url);
        // return $url;
        return 0;
      }
      // return Storage::get('schoollogo.png');
    }
  }

  public function checkusedor(Request $request)
  {
    if($request->ajax())
    {
      $curOR = $request->get('curor');
      $reuse = $request->get('reuse');

      $orbunker = db::table('orcounter')
        ->where('ornum', $curOR)
        ->first();

      if($orbunker)
      {
        if($orbunker->used == 0)
        {
          return 0;
        }
        else
        {
          if($reuse == 1)
            return 0;
          else
            return 1;
        }
      }
      else
      {
        return 0;
      }
    }
  }

  public function amountenter(Request $request)
  {
    if($request->ajax())
    {
      $studid = $request->get('studid');
      $amount = str_replace(',','', $request->get('amount'));
      $transno = $request->get('transno');
      $terminalno = $request->get('terminalno');
      $dayid = $request->get('dayid');
      $classid = $request->get('classid');

      $semid = '';

      $paysched = db::table('studpayscheddetail')
        ->where('studid', $studid)
        ->where('syid', CashierModel::getSYID())
        ->where('semid', CashierModel::getSemID())
        ->where('balance', '>', 0)
        ->where('deleted', 0)
        ->where(function($q) use ($classid){
          if($classid > 0)
          {
            $q->where('classid', $classid);
          }
        })
        ->orderBy('duedate')
        ->get();

      $studinfo = db::table('studinfo')
        ->where('id', $studid)
        ->first();

      if($studinfo->levelid == 14 || $studinfo->levelid == 15)
      {
        $semid = CashierModel::getSemID();
      }
      elseif($studinfo->levelid >= 17 || $studinfo->levelid <= 21)
      {
        $semid = CashierModel::getSemID();
      }
      else
      {
        $semid = null;
      }


      foreach($paysched as $pay)
      {
        $cashTrans = db::table('chrngcashtrans')
          ->where('payscheddetailid', $pay->id)
          ->where('transno', $transno)
          ->where('deleted', 0)
          ->count();

        if($cashTrans == 0)
        {
          if($amount > 0)
          {
            // echo ' amount: ' . $amount;
            $monthdue = date_create($pay->duedate);
            $monthdue = date_format($monthdue, 'F');

            $balance = $pay->balance;

            if($balance < $amount)
            {
              $amount -= $balance; 
            }
            else
            {
              $balance = $amount;
              $amount = 0;
            }

            // echo $balance . '; ' . $amount. '; ';

            $cashtrans = db::table('chrngcashtrans')
              ->insertGetId([
                'transno' => $transno,
                'payscheddetailid' => $pay->id,
                'particulars' => $pay->particulars . ' ' . $monthdue,
                'itemprice' => $balance,
                'qty' => 1,
                'amount' => $balance,
                'duedate' => $pay->duedate,
                'deleted' => 0,
                'studid' => $pay->studid,
                'semid' => $semid,
                'syid' => CashierModel::getSYID(),
                'terminalno' => $terminalno,
                'transdone' => 0,
                'transdatetime' => CashierModel::getServerDateTime(),
                'classid' => $pay->classid,
                'dayid' => $dayid
              ]);  
          }
        }
      }

      $selItem = db::table('chrngcashtrans')
          ->where('transno', $transno)
          ->orderBy('id', 'DESC')
          ->first();
      if($selItem)
      {
        $getOrderLines = CashierModel::getOrderLines($transno, $selItem->id); 
      }
      else
      {
        $getOrderLines = CashierModel::getOrderLines($transno, 0);  
      }

      


      $data = array(
        'output' => $getOrderLines
      );

      echo json_encode($data);
    }
  }

  public function bestudlist(Request $request)
  {
    if($request->ajax())
    {
      $studlist = CashierModel::select2_studlist();

      $list = '<option value="0">Select Student</option>';

      foreach($studlist as $stud)
      {
        $studname = $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' - ' . $stud->levelname;

        $list .='
          <option value='.$stud->id.'>'.$studname.'</option>          
        ';
      }

      $data = array(
        'list' => $list
      );


      echo json_encode($data);
    }
  }

  public function beappend(Request $request)
  {
    if($request->ajax())
    {
      $studid = $request->get('studid');
      $amount = str_replace(',', '', $request->get('amount'));
      $action = $request->get('action');

      if($action == 'create')
      {
        $be_setup = db::table('bookentrysetup')
          ->join('itemclassification', 'bookentrysetup.classid', '=', 'itemclassification.id')
          ->first();

        db::table('bookentries')
          ->insert([
            'studid' => $studid,
            'classid' => $be_setup->classid,
            'mopid' => $be_setup->mopid,
            'amount' => $amount,
            'createdby' => auth()->user()->id,
            'createddatetime' => CashierModel::getServerDateTime()
          ]);
      }
      else
      {
        $dataid = $request->get('dataid');
        $be_setup = db::table('bookentrysetup')
          ->join('itemclassification', 'bookentrysetup.classid', '=', 'itemclassification.id')
          ->first();

        db::table('bookentries')
          ->where('id', $dataid)
          ->update([
            'studid' => $studid,
            'amount' => $amount,
            'updatedby' => auth()->user()->id,
            'updateddatetime' => CashierModel::getServerDateTime()
          ]); 
      }

      // db::table('studledger')
      //   ->insert([
      //     'studid' => $studid,
      //     'semid' => CashierModel::getSemID(),
      //     'syid' => CashierModel::getSYID(),
      //     'classid' => $be_setup->classid,
      //     'particulars' => $be_setup->description,
      //     'amount' => $amount,
      //     'pschemeid' => $be_setup->mopid,
      //     'createdby' => auth()->user()->id,
      //     'createddatetime' => CashierModel::getServerDateTime()
      //   ]);


      // db::table('studledgeritemized')
      //   ->insert([
      //     'studid' => $studid,
      //     'semid' => CashierModel::getSemID(),
      //     'syid' => CashierModel::getSYID(),
      //     'classificationid' => $be_setup->classid,
      //     'itemamount' => $amount,
      //     'createdby' => auth()->user()->id,
      //     'createddatetime' => CashierModel::getServerDateTime()
      //   ]);

      // //studpayscheddetail

      // $modeofpayment = db::table('paymentsetup')
      //   ->where('paymentid', $be_setup->mopid)
      //   ->where('deleted', 0)
      //   ->get();

      // $noofpayment = count($modeofpayment);

      // $divAmount = $amount / $noofpayment;
      // $divAmount = number_format($divAmount, 2, '.', '')
      // $paymentno = 0;
      // $total = 0;

      // $foreach($modeofpayment as $mop)
      // {
      //   if($divAmount > 0)
      //   {
      //     $paymentno += 1;

      //     $total += $divAmount;

      //     if($paymentno < $noofpayment)
      //     {
      //       db::table('studpayscheddetail')
      //         ->insert([
      //           'studid' => $studid,
      //           'semid' => CashierModel::getSemID(),
      //           'syid' => CashierModel::getSYID(),
      //           'classid' => $be_setup->classid,
      //           'paymentno' => $paymentno,
      //           'particulars' => $be_setup->description,
      //           'duedate' => $mop->duedate,
      //           'amount' => $divAmount,
      //           'balance' => $divAmount
      //         ]);  
      //     }
      //     else
      //     {

      //       $total = $amount - $total;
      //       db::table('studpayscheddetail')
      //         ->insert([
      //           'studid' => $studid,
      //           'semid' => CashierModel::getSemID(),
      //           'syid' => CashierModel::getSYID(),
      //           'classid' => $be_setup->classid,
      //           'paymentno' => $paymentno,
      //           'particulars' => $be_setup->description,
      //           'duedate' => $mop->duedate,
      //           'amount' => $total,
      //           'balance' => $total
      //         ]); 
      //     }

          
      //   }
      // }




    }
  }

  public function loadbookentries(Request $request)
  {
    if($request->ajax())
    {
      $filter = $request->get('filter');
      $daterange = $request->get('dtrange');

      $daterange = explode(" - ", $daterange);

      $dateArray = array();

      // return $daterange[0];

      if($daterange[0] != '')
      {
          $d1 = date_create($daterange[0]);
          $d1 = date_format($d1, 'Y-m-d 00:00');
          array_push($dateArray, $d1);
      }
      else
      {
          $d1 = date_create($FinanceModel::getServerDateTime());
          $d1 = date_format($d1, 'Y-m-d 00:00');
          array_push($dateArray, $d1);
      }

      $d2 = date_create($daterange[1]);
      $d2 = date_format($d2, 'Y-m-d 23:59');
      array_push($dateArray, $d2);

      $bookentries = db::table('bookentries')
        ->select('bookentries.id', 'studid', 'classid', 'mopid', 'amount', 'bestatus', 'sid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelid', 'bookentries.createddatetime')
        ->join('studinfo', 'bookentries.studid', '=', 'studinfo.id')
        ->where('bookentries.deleted', 0)
        ->where('lastname', 'like', '%'.$filter.'%')
        ->orWhere('bookentries.deleted', 0)
        ->where('firstname', 'like', '%'.$filter.'%')
        ->get();

      $list = '';

      foreach($bookentries as $book)
      {
        $studname = $book->lastname . ', ' . $book->firstname . ' ' . $book->middlename . ' ' . $book->suffix;

        $date = date_create($book->createddatetime);
        $date = date_format($date, 'm-d-Y');

        $list .='
          <tr data-id="'.$book->id.'">
            <td>'.$studname.'</td>
            <td>'.number_format($book->amount, 2).'</td>
            <td>'.$date.'</td>
            <td>'.$book->bestatus.'</td>
          </tr>
        ';
      }

      $data = array(
        'list' => $list
      );

      echo json_encode($data);
    }
  }

  public function beedit(Request $request)
  {
    if($request->ajax())
    {
      $dataid = $request->get('dataid');

      $be_entry = db::table('bookentries')
        ->where('id', $dataid)
        ->first();

      $data = array(
        'studid' => $be_entry->studid,
        'amount' => number_format($be_entry->amount, 2),
        'status' => $be_entry->bestatus
      );

      echo json_encode($data);

    }
  }

  public function bedelete(Request $request)
  {
    if($request->ajax())
    {
      $dataid = $request->get('dataid');

      db::table('bookentries')
        ->where('id', $dataid)
        ->update([
          'deleted' => 1,
          'deletedby' => auth()->user()->id,
          'deleteddatetime' => CashierModel::getServerDateTime()
        ]);
    }
  }

  public function beapprove(Request $request)
  {
    if($request->ajax())
    {

      $dataid = $request->get('dataid');

      $be_setup = db::table('bookentrysetup')
        ->join('itemclassification', 'bookentrysetup.classid', '=', 'itemclassification.id')
        ->first();

      $be = db::table('bookentries')
        ->where('id', $dataid)
        ->first();
      // return $be->amount;

      $studid = $be->studid;

      db::table('studledger')
        ->insert([
          'studid' => $studid,
          'semid' => CashierModel::getSemID(),
          'syid' => CashierModel::getSYID(),
          'classid' => $be_setup->classid,
          'particulars' => $be_setup->description,
          'amount' => $be->amount,
          'pschemeid' => $be_setup->mopid,
          'deleted' => 0,
          'createdby' => auth()->user()->id,
          'createddatetime' => CashierModel::getServerDateTime()
        ]);


      db::table('studledgeritemized')
        ->insert([
          'studid' => $studid,
          'semid' => CashierModel::getSemID(),
          'syid' => CashierModel::getSYID(),
          'classificationid' => $be_setup->classid,
          'itemamount' => $be->amount,
          'createdby' => auth()->user()->id,
          'createddatetime' => CashierModel::getServerDateTime()
        ]);

      //studpayscheddetail

      $modeofpayment = db::table('paymentsetupdetail')
        ->where('paymentid', $be_setup->mopid)
        ->where('deleted', 0)
        ->get();

      $noofpayment = count($modeofpayment);

      $divAmount = $be->amount / $noofpayment;
      $divAmount = number_format($divAmount, 2, '.', '');
      $paymentno = 0;
      $total = 0;

      foreach($modeofpayment as $mop)
      {
        if($divAmount > 0)
        {
          $paymentno += 1;

          

          if($paymentno < $noofpayment)
          {
            $total += $divAmount;
            
            db::table('studpayscheddetail')
              ->insert([
                'studid' => $studid,
                'semid' => CashierModel::getSemID(),
                'syid' => CashierModel::getSYID(),
                'classid' => $be_setup->classid,
                'paymentno' => $paymentno,
                'particulars' => $be_setup->description,
                'duedate' => $mop->duedate,
                'amount' => $divAmount,
                'balance' => $divAmount
              ]);  
          }
          else
          {

            $total = $be->amount - $total;
            db::table('studpayscheddetail')
              ->insert([
                'studid' => $studid,
                'semid' => CashierModel::getSemID(),
                'syid' => CashierModel::getSYID(),
                'classid' => $be_setup->classid,
                'paymentno' => $paymentno,
                'particulars' => $be_setup->description,
                'duedate' => $mop->duedate,
                'amount' => $total,
                'balance' => $total,
              ]); 
          }
        }
      }

      db::table('bookentries')
        ->where('id', $dataid)
        ->update([
          'bestatus' => 'APPROVED',
          'updatedby' => auth()->user()->id,
          'updateddatetime' => CashierModel::getServerDateTime()
        ]);

    }
  }

  public function genfees(Request $request)
  {
    if($request->ajax())
    {
      $studid = $request->get('studid');

      $studinfo = db::table('studinfo')
        ->where('id', $studid)
        ->first();

      $levelid = $studinfo->levelid;

      $dataid = 0;

      $glevel_list = '';

      $glevel = db::table('gradelevel')
        ->where('deleted', 0)
        ->orderBy('sortid')
        ->get();


      foreach($glevel as $level)
      {
        if($levelid == $level->id)
        {
          $glevel_list .='
            <option value="'.$level->id.'" selected>'.$level->levelname.'</option>
          ';
        }
        else
        {
          $glevel_list .='
            <option value="'.$level->id.'">'.$level->levelname.'</option>
          ';
        }
      }

      $tuitionheader = db::table('tuitionheader')
        ->select(db::raw('tuitionheader.id, tuitionheader.description, paymentplan, grantee.description AS grantee, gradelevel.levelname, SUM(amount) AS amount'))
        ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
        ->join('gradelevel', 'tuitionheader.levelid', '=', 'gradelevel.id')
        ->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
        ->where('levelid', $levelid)
        ->where('tuitionheader.deleted', 0)
        ->groupBy('headerid')
        ->orderBy('paymentplan', 'ASC')
        ->get();

      // return $tuitionheader;
      $list = '';

      foreach($tuitionheader as $tuition)
      {
        if($studinfo->feesid != $tuition->id)
        {
          $list .='
            <tr data-id="'.$tuition->id.'" class="feesplan">
              <td>'.$tuition->description.'</td>
              <td>'.$tuition->paymentplan.'</td>
              <td>'.$tuition->levelname.'</td>
              <td>'.$tuition->grantee.'</td>
              <td class="text-right">'.number_format($tuition->amount, 2).'</td>
            </tr>
          ';
        }
        else
        {
          $list .='
            <tr data-id="'.$tuition->id.'" class="feesplan bg-success">
              <td>'.$tuition->description.'</td>
              <td>'.$tuition->paymentplan.'</td>
              <td>'.$tuition->levelname.'</td>
              <td>'.$tuition->grantee.'</td>
              <td class="text-right">'.number_format($tuition->amount, 2).'</td>
            </tr>
          '; 

          $dataid = $studinfo->feesid;
        }
      }

      $data = array(
        'list' => $list,
        'dataid' => $dataid,
        'glevel' => $glevel_list
      );

      echo json_encode($data);
    }
  }

  public function plandesc(Request $request)
  {
    if($request->ajax()) 
    {
      $studid = $request->get('studid');

      $stud = db::table('studinfo')
        ->where('id', $studid)
        ->first();

      $fees = db::table('tuitionheader')
        ->where('id', $stud->feesid)
        ->first();

      if($fees)
      {
        $data = array(
          'plan' => $fees->description . ' - ' . $fees->paymentplan
        );
      }
      else
      {
        $data = array(
          'plan' => ''
        );
      }

      echo json_encode($data);
    }
  }

  public function saveplandesc(Request $request)
  {
    if($request->ajax())
    {
      $studid = $request->get('studid');
      $feesid = $request->get('feesid');

      db::table('studinfo')
        ->where('id', $studid)
        ->update([
          'feesid' => $feesid,
          'updateddatetime' => CashierModel::getServerDateTime(),
          'updatedby' => auth()->user()->id
        ]);
    }
  }

  public function genpayplanperlevel(Request $request)
  {
    if($request->ajax())
    {
      $levelid = $request->get('levelid');
      $studid = $request->get('studid');

      // return $studid;

      $studinfo = db::table('studinfo')
        ->where('id', $studid)
        ->first();

      // return $levelid;

      $tuitionheader = db::table('tuitionheader')
        ->select(db::raw('tuitionheader.id, tuitionheader.description, paymentplan, grantee.description AS grantee, gradelevel.levelname, SUM(amount) AS amount'))
        ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
        ->join('gradelevel', 'tuitionheader.levelid', '=', 'gradelevel.id')
        ->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
        ->where('levelid', $levelid)
        ->where('tuitionheader.deleted', 0)
        ->groupBy('headerid')
        ->orderBy('paymentplan', 'ASC')
        ->get();

      // return $tuitionheader;
      $list = '';

      foreach($tuitionheader as $tuition)
      {
        if($studinfo->feesid != $tuition->id)
        {
          $list .='
            <tr data-id="'.$tuition->id.'" class="feesplan">
              <td>'.$tuition->description.'</td>
              <td>'.$tuition->paymentplan.'</td>
              <td>'.$tuition->levelname.'</td>
              <td>'.$tuition->grantee.'</td>
              <td class="text-right">'.number_format($tuition->amount, 2).'</td>
            </tr>
          ';
        }
        else
        {
          $list .='
            <tr data-id="'.$tuition->id.'" class="feesplan bg-success">
              <td>'.$tuition->description.'</td>
              <td>'.$tuition->paymentplan.'</td>
              <td>'.$tuition->levelname.'</td>
              <td>'.$tuition->grantee.'</td>
              <td class="text-right">'.number_format($tuition->amount, 2).'</td>
            </tr>
          '; 

          // $dataid = $studinfo->feesid;
        }
      }

      $data = array(
          'list' => $list
        );

        echo json_encode($data);
    }
  }

  public function printExamP($id)
  {
    $schoolinfo = db::table('schoolinfo')
      ->first();

    $studname = db::table('studinfo')
      ->select('lastname', 'firstname', 'middlename')
      ->where('id', $id)
      ->first();

    $name = $studname->lastname . ', ' . $studname->firstname;

    $sigpath = asset('assets/images/sig.PNG');
    // return $sigpath;

    $print ='';

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // set document information
            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle($schoolinfo->schoolname.' - Exam Permit');
            $pdf->SetSubject('Examination Permit');
            
            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            
            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            
            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            
            // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
            // set auto page breaks
            // $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            
            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            
            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
            }
            
            // ---------------------------------------------------------
            
            // set font
            $pdf->SetFont('dejavusans', '', 10);
            
            
            // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            // Print a table

            $pdf->AddPage();

            $print .='
              &nbsp;<br>
              <table border="1" cellpadding="2" style="margin-top:50px">
                  <thead>
                      <tr>
                        <th style="font-size: 8px !important; font-weight: bold;" width="300">STUDENT'."'".'S NAME</th>
                    <th style="font-size: 8px !important; font-weight: bold;" width="155">DATE</th>
              <th style="font-size: 8px !important; font-weight: bold;" width="205">Approved by:</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="font-size: 15px !important; text-align:center; vertical-align:middle !important;" width="300" height="60px">
                &nbsp;<br>'.$name.'
              </td>
              <td width="155" style="font-size: 15px !important; text-align:center;">&nbsp;<br>'.date_format(CashierModel::getServerDateTime(), 'M d, Y').'</td>
              <td width="205">
                <img src="'.$sigpath.'" width="90px"> <br>
                SR. JOAN R. MATULLANO,TDM<br>
                <span style="text-align:center; font-size: 8px !important;">HCB Finance Incharge</span>
              </td>
            </tr>
          </tbody>
        </table>
            ';

            $pdf->writeHTML($print, true, false, true, false, '');
                
            $pdf->lastPage();
            
            // ---------------------------------------------------------
            //Close and output PDF document
            $pdf->Output('Student Assessment.pdf', 'I');


  }

  public function changeparticulars(Request $request)
  {
    if($request->ajax())
    {
      $particulars = $request->get('particulars');
      $dataid = $request->get('dataid');
      $transno = $request->get('transno');

      db::table('chrngcashtrans')
        ->where('id', $dataid)
        ->update([
          'particulars' => $particulars
        ]);

      $data = array(
        'output' => CashierModel::getOrderLines($transno, $dataid)
      );

      echo json_encode($data);
    }
  }

}

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl;
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),70,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='C', $autopadding=true);
        // $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);

        $this->writeHTMLCell(false, 50, 40, 15, 'Examination Permit', false, false, false, $reseth=true, $align='C', $autopadding=true);
        $this->writeHTMLCell(false, 50, 40, 20, 'For the month of <span style="text-transform:uppercase">' . date_format(CashierModel::getServerDateTime(), 'F') . '</span>', false, false, false, $reseth=true, $align='C', $autopadding=true);
        // Ln();
    }

    // Page footer
    // public function Footer() {
    //     // Position at 15 mm from bottom
    //     $this->SetY(-15);
    //     // Set font
    //     $this->SetFont('helvetica', 'I', 8);
    //     // Page number
    //     $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    //     $this->Cell(0, 10, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    // }
}







// postTrans - Foreach
// $transamount = $trans->amount;
        
//         $ledgeritemized = db::table('studledgeritemized')
//           ->where('studid', $trans->studid)
//           ->where('syid', $trans->syid)
//           ->where('semid', $trans->semid)
//           ->where('classificationid', $trans->classid)
//           ->get();

//         foreach($ledgeritemized as $item)
//         {
//           if($transamount > 0)
//           {
//             $checkitem = db::table('studledgeritemized')
//               ->where('id', $item->id)
//               ->first();


//             if($checkitem->totalamount < $item->itemamount)
//             {
//               $_getamount = $item->itemamount - $item->totalamount;

//               if($transamount >= $_getamount)
//               {
//                 db::table('studledgeritemized')
//                   ->where('id', $item->id)
//                   ->update([
//                     'totalamount' => $item->totalamount + $_getamount,
//                     'updatedby' => auth()->user()->id,
//                     'updateddatetime' => CashierModel::getServerDateTime()
//                   ]);

//                 db::table('chrngtransitems')
//                   ->insert([
//                     'chrngtransid' => $trans->chrngtransid,
//                     'chrngtransdetailid' => $trans->chrngtransdetailid,
//                     'ornum' => $trans->ornum,
//                     'itemid' => $item->itemid,
//                     'classid' => $item->classificationid,
//                     'amount' => $_getamount,
//                     'studid' => $item->studid,
//                     'syid' => $trans->syid,
//                     'semid' => $trans->semid,
//                     'createdby' => auth()->user()->id,
//                     'createddatetime' => CashierModel::getServerDateTime()
//                   ]);

//                 $transamount -= $_getamount;

//               }
//               else
//               {
//                 db::table('studledgeritemized')
//                   ->where('id', $item->id)
//                   ->update([
//                     'totalamount' => $item->totalamount + $transamount,
//                     'updatedby' => auth()->user()->id,
//                     'updateddatetime' => CashierModel::getServerDateTime()
//                   ]);


//                 db::table('chrngtransitems')
//                   ->insert([
//                     'chrngtransid' => $trans->chrngtransid,
//                     'chrngtransdetailid' => $trans->chrngtransdetailid,
//                     'ornum' => $trans->ornum,
//                     'itemid' => $item->itemid,
//                     'classid' => $item->classificationid,
//                     'amount' => $transamount,
//                     'studid' => $item->studid,
//                     'syid' => $trans->syid,
//                     'semid' => $trans->semid,
//                     'createdby' => auth()->user()->id,
//                     'createddatetime' => CashierModel::getServerDateTime()
//                   ]);

//                 $transamount = 0;
//               }
//             }
//           }
//         }

