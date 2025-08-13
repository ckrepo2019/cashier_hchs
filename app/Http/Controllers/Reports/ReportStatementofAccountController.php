<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\Reports\StatementofAccountModel;
class ReportStatementofAccountController extends Controller
{
    public function selections(Request $request)
    {
        $schoolyears = DB::table('sy')
            ->get();

        $semesters = DB::table('semester')
            ->where('deleted','0')
            ->get();

        $monthsetups = DB::table('monthsetup')
            ->get();

        return array(
            'schoolyears'   => $schoolyears,
            'semesters'     => $semesters,
            'monthsetups'   => $monthsetups
        );
    }
    public function allstudents(Request $request)
    {
            
        $students = StatementofAccountModel::allstudents();
        
        $notes = DB::table('schoolreportsnote')
            ->where('deleted','0')
            ->where('type','1')
            ->get();
        
        $status = 0;

        if(count($notes)>0)
        {
            foreach($notes as $note)
            {
                if($note->status)
                {
                    $status+=1;
                }
            }
        }
        if($request->ajax())
        {
        return view('include.blade.statementofaccount_viewstudents')
            ->with('students', $students);
        }
    }
    public function studinfo(Request $request)
    {
        $studinfo = DB::table('studinfo')
            ->where('id', $request->get('studid'))
            ->first();

        if($studinfo->middlename == null)
        {
            $studinfo->middlename = '';
        }else{
            $studinfo->middlename = $studinfo->middlename[0].'.';
        }
        if($studinfo->suffix == null)
        {
            $studinfo->suffix = '';
        }
        return collect($studinfo);
    }
    public function getaccount(Request $request)
    {
        // return $request->all();
        $studid = $request->get('studid');
        $semid = $request->get('selectedsemester');
        $syid = $request->get('selectedschoolyear');
        if($request->get('selectedmonth') == null)
        {
            $month = null;
        }else{
            $month      = date('m', strtotime($request->get('selectedmonth')));
        }
        $studinfo = db::table('studinfo')
            ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'sectionname', 'levelid')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('studinfo.id', $studid)
            ->first();

        
        if($studinfo->levelid == 14 || $studinfo->levelid == 15)
        {
        $ledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('deleted', 0)
            ->orderBy('id', 'asc')
            ->get();
        }
        elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
        {
        $ledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('deleted', 0)
            ->orderBy('id', 'asc')
            ->get(); 
        }
        else
        {
        $ledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->orderBy('id', 'asc')
            ->get();
        }

        $output = '<table class="table table-bordered" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th colspan="5">LEDGER</th>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Billing</th>
                            <th>Payment</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>';
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
            <tr>
                <td>' .$lDate.' </td>
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
            <tr>
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
        <tr style="background-color:#007bff91">
            <th></th>
            <th style="text-align:right">
                <strong>TOTAL:<strong>
            </th>
            <th class="text-right">
                <strong><u>'.number_format($debit, 2).'</u></strong>
            </th>
            <th class="text-right">
                <strong><u>'.number_format($credit, 2).'</u></strong>
            </th>
            <th class="text-right">
                <strong><u>'.number_format($bal, 2).'</u></strong>
            </th>
        </tr>
        </tbody>
        <thead>
            <tr>
                <th colspan="5">ASSESSMENT</th>
            </tr>
        </thead>
        <tbody>';
        if($studinfo->levelid == 14 || $studinfo->levelid == 15)
        {
          $getPaySched = db::select('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
              from studpayscheddetail
              where studid = ? and syid = ? and semid = ? and deleted = 0
              group by MONTH(duedate)
              order by duedate', [$studid, $syid, $semid]);
  
        }
        else
        {
          $getPaySched = db::select('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
              from studpayscheddetail
              where studid = ? and syid = ? and deleted = 0
              group by MONTH(duedate)
              order by duedate', [$studid, $syid]);
        }
        $assessbilling = 0;
        $assesspayment = 0;
        $assessbalance = 0;
        $totalBal = 0;
        if(count($getPaySched) > 0)
        {
          foreach($getPaySched as $psched)
          {
  
            // return $getPaySched;
            $totalBal += $psched->balance;
            $assessbilling += $psched->amountdue;
            $assesspayment += $psched->amountpay;
            $assessbalance += $psched->balance;
            
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
            if($month == null || $month == "")
            {
              // return $m . ' != ' . $month;
              if($m != $month)
              {
                if($psched->balance > 0)
                {
                  $output .='
                    <tr>
                      <td></td>
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
                    <td></td>
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
                    <td></td>
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
                  <td></td>
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
                  <td></td>
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
  
          $output .='
            <tr style="background-color:#007bff91">
                <th></th>
                <th style="text-align:right">
                    <strong>TOTAL:<strong>
                </th>
                <th class="text-right">
                    <strong><u>'.number_format($assessbilling, 2).'</u></strong>
                </th>
                <th class="text-right">
                    <strong><u>'.number_format($assesspayment, 2).'</u></strong>
                </th>
                <th class="text-right">
                    <strong><u>'.number_format($assessbalance, 2).'</u></strong>
                </th>
            </tr>
            <tr style="background-color:#ffc1078c">
                <th></th>
                <th style="text-align:right">
                    <strong>TOTAL BALANCE:<strong>
                </th>
                <th class="text-right">
                    <strong><u>'.number_format($assessbilling, 2).'</u></strong>
                </th>
                <th class="text-right">
                    <strong><u>'.number_format($assesspayment, 2).'</u></strong>
                </th>
                <th class="text-right">
                    <strong><u>'.number_format($totalBal, 2).'</u></strong>
                </th>
            </tr>
            <tr style="background-color:#ffc1078c">
                <th></th>
                <th style="text-align:right">
                    <strong>TOTAL AMOUNT DUE:<strong>
                </th>
                <th class="text-right">
                   
                
                </th>
                <th class="text-right">
                   
                </th>
                <th class="text-right" style="font-size:">
                    <h4><strong><u>'.number_format($totalBal, 2).'</u></strong></h4>
                </th>
            </tr>
        </tbody>
        </table>';
  
        }else{
  
            $output .='
            <tr style="background-color:#ffc1078c">
                <th></th>
                <th style="text-align:right">
                    <strong>TOTAL BALANCE:<strong>
                </th>
                <th class="text-right">
                    <strong><u>'.number_format($debit, 2).'</u></strong>
                </th>
                <th class="text-right">
                    <strong><u>'.number_format($credit, 2).'</u></strong>
                </th>
                <th class="text-right">
                    <strong><u>'.number_format($bal, 2).'</u></strong>
                </th>
            </tr>
            <tr style="background-color:#ffc1078c">
                <th></th>
                <th style="text-align:right">
                    <strong>TOTAL AMOUNT DUE:<strong>
                </th>
                <th class="text-right">
                   
                
                </th>
                <th class="text-right">
                   
                </th>
                <th class="text-right" style="font-size:">
                    <h4><strong><u>'.number_format($bal, 2).'</u></strong></h4>
                </th>
            </tr>
          </tbody>
          </table>';
        }
        return $output;
    }
    public function export(Request $request)
    {
        $studid = $request->get('studid');
        $semid = $request->get('selectedsemester');
        $syid = $request->get('selectedschoolyear');
        $selectedschoolyear = DB::table('sy')
            ->where('id', $request->get('selectedschoolyear'))
            ->first()
            ->sydesc;

        if($request->get('selectedschoolyear') == null)
        {
            $selectedsemester ="";
        }else{
            $semester = DB::table('semester')
                ->where('id', $request->get('selectedschoolyear'))
                ->first()
                ->semester;

            $selectedsemester = $semester;
        }
        if($request->get('selectedmonth') == null)
        {
            $selectedmonth = null;
        }else{
            $selectedmonth      = date('m', strtotime($request->get('selectedmonth')));
        }
        $schoolinfo = Db::table('schoolinfo')
            ->select(
                'schoolinfo.schoolid',
                'schoolinfo.schoolname',
                'schoolinfo.authorized',
                'schoolinfo.picurl',
                'refcitymun.citymunDesc',
                'schoolinfo.district',
                'schoolinfo.address',
                'refregion.regDesc'
            )
            ->join('refregion','schoolinfo.region','=','refregion.regCode')
            ->join('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
            ->first();
        
        $studinfo = db::table('studinfo')
            ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'sectionname', 'levelid')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('studinfo.id', $studid)
            ->first();
        
        $notes = DB::table('schoolreportsnote')
            ->where('deleted','0')
            ->where('type','1')
            ->get();
        
        $notestatus = 0;
        if(count($notes)>0)
        {
            foreach($notes as $note)
            {
                if($note->status)
                {
                    $notestatus+=1;
                }
            }
        }
        if($studinfo->middlename == null)
        {
            $studinfo->middlename = '';
        }else{
            $studinfo->middlename = $studinfo->middlename[0].'.';
        }

        $preparedby = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->first();
        if($preparedby->middlename == null)
        {
            $preparedby->middlename = '';
        }else{
            $preparedby->middlename = $preparedby->middlename[0].'.';
        }

        if($request->get('exporttype') == 'pdf')
        {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // set document information
            $pdf->SetCreator('CK');
            $pdf->SetAuthor('CK Children\'s Publishing');
            $pdf->SetTitle($schoolinfo->schoolname.' - Statement of Account');
            $pdf->SetSubject('Statement of Account');
            
            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            
            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            
            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            
            // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            
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
            
            // add a page
            $pdf->AddPage();
                
            $html = '
                <table style="font-size: 9px; font-weight: bold; padding-top: 5px;" >
                    <tr>
                        <td>S.Y '.$selectedschoolyear;
                        
                        if($selectedmonth != null){
                            $html.='<br/>AS OF : '.strtoupper($request->get('selectedmonth'));
                        }
                        $html.='</td>
                                <td>';
                            if($selectedsemester != null)
                            {
                                $html.='SEMESTER : '.strtoupper($selectedsemester);
                            }
                            $html.='
                            </td>
                    </tr>
                    <tr>
                        <td colspan="2">STUDENT: '.$studinfo->lastname.', '.$studinfo->firstname.' '.$studinfo->middlename.' '.$studinfo->suffix.'</td>
                    </tr>
                </table>';
                if($request->get('selectedmonth') == null)
                {
                    $month = null;
                }else{
                    $month      = date('m', strtotime($request->get('selectedmonth')));
                }
        
                
                if($studinfo->levelid == 14 || $studinfo->levelid == 15)
                {
                $ledger = db::table('studledger')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('deleted', 0)
                    ->orderBy('id', 'asc')
                    ->get();
                }
                elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
                {
                $ledger = db::table('studledger')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('deleted', 0)
                    ->orderBy('id', 'asc')
                    ->get(); 
                }
                else
                {
                $ledger = db::table('studledger')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('deleted', 0)
                    ->orderBy('id', 'asc')
                    ->get();
                }
        
                $html .= '<table  cellspacing="0" cellpadding="1" border="1" style="font-size: 9px;">
                            <thead >
                                <tr>
                                    <th colspan="5" style="font-weight: bold;">LEDGER</th>
                                </tr>
                                <tr style="text-align:center;">
                                    <th style="font-weight: bold;" width="70px">Date</th>
                                    <th style="font-weight: bold;" width="200px">Description</th>
                                    <th style="font-weight: bold;" width="120px">Billing</th>
                                    <th style="font-weight: bold;" width="120px">Payment</th>
                                    <th style="font-weight: bold;" width="20%">Balance</th>
                                </tr>
                            </thead>';
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
                        $html .='
                        <tr>
                            <td width="70px">' .$lDate.' </td>
                            <td width="200px">'.$led->particulars.'</td>
                            <td width="120px" style="text-align:right;">'.$amount.'</td>
                            <td width="120px" style="text-align:right;">'.$payment.'</td>
                            <td width="20%" style="text-align:right;">'.number_format($bal, 2).'</td>
                        </tr>
                        ';
                    }
                    else
                    {
                        $html .='
                        <tr>
                            <td width="70px"><del>' .$lDate.' </del></td>
                            <td width="200px"><del>'.$led->particulars.'</del></td>
                            <td width="120px" style="text-align:right;"><del>'.$amount.'</del></td>
                            <td width="120px" style="text-align:right;"><del>'.$payment.'</del></td>
                            <td width="20%" style="text-align:right;"><del>'.number_format($bal, 2).'</del></td>
                        </tr>
                        ';
                    }
            
                }
        
                $html .='
                <tr style="background-color:#59bdf0">
                    <th width="70px"></th>
                    <th width="200px" style="text-align:right">
                        TOTAL:
                    </th>
                    <th width="120px" style="text-align:right;">
                        '.number_format($debit, 2).'
                    </th>
                    <th width="120px" style="text-align:right;">
                        '.number_format($credit, 2).'
                    </th>
                    <th width="20%" style="text-align:right;">
                        '.number_format($bal, 2).'
                    </th>
                </tr>
                    <tr>
                        <th colspan="5"  style="font-weight: bold;">ASSESSMENT</th>
                    </tr>';
                if($studinfo->levelid == 14 || $studinfo->levelid == 15)
                {
                  $getPaySched = db::select('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                      from studpayscheddetail
                      where studid = ? and syid = ? and semid = ? and deleted = 0
                      group by MONTH(duedate)
                      order by duedate', [$studid, $syid, $semid]);
          
                }
                else
                {
                  $getPaySched = db::select('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                      from studpayscheddetail
                      where studid = ? and syid = ? and deleted = 0
                      group by MONTH(duedate)
                      order by duedate', [$studid, $syid]);
                }
                $assessbilling = 0;
                $assesspayment = 0;
                $assessbalance = 0;
                $totalBal = 0;
                if(count($getPaySched) > 0)
                {
                  foreach($getPaySched as $psched)
                  {
          
                    // return $getPaySched;
                    $totalBal += $psched->balance;
                    $assessbilling += $psched->amountdue;
                    $assesspayment += $psched->amountpay;
                    $assessbalance += $psched->balance;
                    
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
                    if($month == null || $month == "")
                    {
                      // return $m . ' != ' . $month;
                      if($m != $month)
                      {
                        if($psched->balance > 0)
                        {
                          $html .='
                            <tr>
                              <td width="70px"></td>
                              <td width="200px">'.$particulars.'</td>
                              <td width="120px" style="text-align:right;">'.number_format($psched->amountdue, 2).'</td>
                              <td width="120px" style="text-align:right;">'.number_format($psched->amountpay, 2).'</td>
                              <td width="20%" style="text-align:right;">'.number_format($psched->balance, 2).'</td>
                            </tr>
                          ';
                        }
                      }
                      else
                      {
                        if($psched->balance > 0)
                        {
                          $html .='
                            <tr>
                            <td width="70px"></td>
                              <td width="200px">'.$particulars.'</td>
                              <td width="120px" style="text-align:right;">'.number_format($psched->amountdue, 2).'</td>
                              <td width="120px" style="text-align:right;">'.number_format($psched->amountpay, 2).'</td>
                              <td width="20%" style="text-align:right;">'.number_format($psched->balance, 2).'</td>
                            </tr>
                          ';
                        }
                        else
                        {
                          $html .='
                            <tr>
                            <td width="70px"></td>
                              <td width="200px">'.$particulars.'</td>
                              <td width="120px" style="text-align:right;">'.number_format($psched->amountdue, 2).'</td>
                              <td width="120px" style="text-align:right;">'.number_format($psched->amountpay, 2).'</td>
                              <td width="20%" style="text-align:right;">'.number_format($psched->balance, 2).'</td>
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
                        
                        $html .='
                          <tr>
                          <td width="70px"></td>
                            <td width="200px">'.$particulars.'</td>
                            <td width="120px" style="text-align:right;">'.number_format($psched->amountdue, 2).'</td>
                            <td width="120px" style="text-align:right;">'.number_format($psched->amountpay, 2).'</td>
                            <td width="20%" style="text-align:right;">'.number_format($psched->balance, 2).'</td>
                          </tr>
                        ';
                        
                      }
                      else
                      {
                        
                        $html .='
                          <tr>
                          <td width="70px"></td>
                            <td width="200px">'.$particulars.'</td>
                            <td width="120px" style="text-align:right;">'.number_format($psched->amountdue, 2).'</td>
                            <td width="120px" style="text-align:right;">'.number_format($psched->amountpay, 2).'</td>
                            <td width="20%" style="text-align:right;">'.number_format($psched->balance, 2).'</td>
                          </tr>
                        ';
                        
                        break; 
                      }
                    }
                  }
          
                  $html .='
                    <tr style="background-color:#59bdf0">
                        <th width="70px"></th>
                        <th width="200px" style="text-align:right">
                            TOTAL:
                        </th>
                        <th width="120px" style="text-align:right;">
                            '.number_format($assessbilling, 2).'
                        </th>
                        <th width="120px" style="text-align:right;">
                            '.number_format($assesspayment, 2).'
                        </th>
                        <th width="20%" style="text-align:right;">
                            '.number_format($assessbalance, 2).'
                        </th>
                    </tr>
                    <tr style="background-color: #f5e069">
                        <th width="70px"></th>
                        <th width="200px" style="text-align:right">
                            TOTAL BALANCE:
                        </th>
                        <th width="120px"style="text-align:right">
                            '.number_format($assessbilling, 2).'
                        </th>
                        <th width="120px"style="text-align:right">
                            '.number_format($assesspayment, 2).'
                        </th>
                        <th width="20%"style="text-align:right">
                            '.number_format($totalBal, 2).'
                        </th>
                    </tr>
                    <tr style="background-color: #f5e069">
                        <th width="70px"></th>
                        <th width="200px" style="text-align:right">
                            TOTAL AMOUNT DUE:
                        </th>
                        <th width="120px"style="text-align:right">
                           
                        
                        </th>
                        <th width="120px"style="text-align:right">
                           
                        </th>
                        <th width="20%" style="font-size:13px;text-align:right">
                            '.number_format($totalBal, 2).'
                        </th>
                    </tr>
                </table>';
          
                }else{
          
                    $html .='
                    <tr style="background-color: yellow">
                        < width="70px"></th>
                        <th width="200px" style="text-align:right">
                            TOTAL BALANCE:
                        </th>
                        <th width="120px"style="text-align:right">
                            '.number_format($debit, 2).'
                        </th>
                        <th width="120px"style="text-align:right">
                            '.number_format($credit, 2).'
                        </th>
                        <th width="20%"style="text-align:right">
                            '.number_format($bal, 2).'
                        </th>
                    </tr>
                    <tr style="background-color: yellow">
                        <th width="70px"></th>
                        <th width="200px" style="text-align:right">
                            TOTAL AMOUNT DUE:
                        </th>
                        <th width="120px">
                           
                        
                        </th>
                        <th width="120px">
                           
                        </th>
                        <th width="20%" style="font-size:13px;text-align:right">
                            '.number_format($bal, 2).'
                        </th>
                    </tr>
                  </table>';
                }
                // output the HTML content
                
                set_time_limit(3000);
                $pdf->writeHTML($html, true, false, true, false, '');

                $signatories = '';

                if($notestatus>0)
                {
                    $signatories.='<span style="font-size: 9px;font-weight: bold">NOTES:</span><br/>';
                    foreach($notes as $note)
                    {
                        $signatories.='<p style="line-height: 8px; margin-bottom: 0px;font-size: 9px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$note->description.'</p>';
                    }
                }
                $signatories.='<table  cellspacing="0" cellpadding="1" style="font-size: 9px;">
                                <thead>
                                    <tr>
                                        <th style="font-weight: bold;">Prepared By:</th>
                                        <th style="font-weight: bold;">Received By:</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td>
                                        <table style="width: 80%"  cellpadding="5" >
                                            <tr>
                                                <td style="border-bottom: 1px solid black;height: 25px;">
                                                
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;text-transform: uppercase; font-weight: bold;">
                                                   '.$preparedby->firstname.' '.$preparedby->middlename.' '.$preparedby->lastname.' '.$preparedby->suffix.'
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table style="width: 80%"  cellpadding="5" >
                                            <tr>
                                                <td style="border-bottom: 1px solid black;height: 25px;">
                                                
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-transform: uppercase; font-weight: bold; border-bottom: 1px solid black;">
                                                    Date: 
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>';
                
                $signatories.='</table>';
                $pdf->writeHTML($signatories, true, false, true, false, '');
                
                $pdf->lastPage();
                
                // ---------------------------------------------------------
                //Close and output PDF document
                $pdf->Output('Statement of Account.pdf', 'I');
        }elseif($request->get('exporttype') == 'excel')
        {
            // return $request->all();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
            $sheet = $spreadsheet->getActiveSheet();
            $borderstyle = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => array('argb' => 'black'),
                    ),
                ),
            );
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(base_path().'/public/'.$schoolinfo->picurl);
            $drawing->setHeight(70);
            $drawing->setWorksheet($sheet);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(0);
            $drawing->setOffsetY(20);
            
            $drawing->getShadow()->setVisible(true);
            $drawing->getShadow()->setDirection(45);

            $sheet->mergeCells('C2:G2');
            $sheet->setCellValue('C2', $schoolinfo->schoolname);
            $sheet->mergeCells('C3:G3');
            $sheet->setCellValue('C3', $schoolinfo->address);
            $sheet->mergeCells('C4:G4');
            $sheet->setCellValue('C4', 'Statement of Account');
            $sheet->mergeCells('C5:E5');
            $sheet->setCellValue('C5', 'S.Y '.$selectedschoolyear);
            $sheet->mergeCells('C6:E6');
            $sheet->setCellValue('C6', $selectedsemester);
            
            if($request->get('selectedmonth') != null){
                $sheet->mergeCells('F6:G6');
                $sheet->setCellValue('F6', 'AS OF MONTH OF: '.strtoupper($request->get('selectedmonth')));
            }
            
            foreach(array('A','B','C','D','E','F','G','H') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            if($request->get('selectedmonth') == null)
            {
                $month = null;
            }else{
                $month      = date('m', strtotime($request->get('selectedmonth')));
            }
            $studinfo = db::table('studinfo')
                ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'sectionname', 'levelid')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->where('studinfo.id', $studid)
                ->first();
    
            
            if($studinfo->levelid == 14 || $studinfo->levelid == 15)
            {
            $ledger = db::table('studledger')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->orderBy('id', 'asc')
                ->get();
            }
            elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
            {
            $ledger = db::table('studledger')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->orderBy('id', 'asc')
                ->get(); 
            }
            else
            {
            $ledger = db::table('studledger')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where('deleted', 0)
                ->orderBy('id', 'asc')
                ->get();
            }
            $sheet->mergeCells('A8:E8');
            $sheet->setCellValue('A8','STUDENT: '.$studinfo->lastname.','.$studinfo->firstname.' '.$studinfo->middlename.' '.$studinfo->suffix);
            $sheet->getStyle('A8:E8')->getFont()->setBold(true);
            $sheet->mergeCells('A9:H9');
            $sheet->setCellValue('A9','LEDGER');
            $sheet->getStyle('A9:H9')->getFont()->setBold(true);
            $sheet->getStyle('A9:H9')->applyFromArray($borderstyle);

            $sheet->mergeCells('A10:B10');
            $sheet->setCellValue('A10','Date');
            $sheet->getStyle('A10:B10')->applyFromArray($borderstyle);
            $sheet->mergeCells('C10:E10');
            $sheet->setCellValue('C10','Description');
            $sheet->getStyle('C10:E10')->applyFromArray($borderstyle);
            $sheet->setCellValue('F10','Billing');
            $sheet->getStyle('F10')->applyFromArray($borderstyle);
            $sheet->setCellValue('G10','Payment');
            $sheet->getStyle('G10')->applyFromArray($borderstyle);
            $sheet->setCellValue('H10','Balance');
            $sheet->getStyle('H10')->applyFromArray($borderstyle);

            $startcellno = 11;
            
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
                    $amount = $led->amount;
                }
                else
                {
                    $amount = null;
                }
        
                if($led->payment > 0)
                {
                    $payment = $led->payment;
                }
                else
                {
                    $payment = null;
                }
        
                if($led->void == 0)
                {
                    $bal += $led->amount - $led->payment;
                }
        
                if($led->void == 0)
                {
                    $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                    $sheet->setCellValue('A'.$startcellno,$lDate);
                    $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
                    $sheet->setCellValue('C'.$startcellno,$led->particulars);
                    $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('F'.$startcellno,$amount);
                    $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('G'.$startcellno,$payment);
                    $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('H'.$startcellno,$bal);
                    $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
                }
                else
                {
                    $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                    $sheet->setCellValue('A'.$startcellno,$lDate);
                    $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
                    $sheet->setCellValue('C'.$startcellno,$led->particulars);
                    $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('F'.$startcellno,$amount);
                    $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('G'.$startcellno,$payment);
                    $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('H'.$startcellno,$bal);
                    $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
                    $strikethrough = $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->getFont()->getStrikethrough();
                    $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->setStrikethrough($strikethrough);

                }
                $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );

                $startcellno+=1;
        
            }
    
            $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
            $sheet->setCellValue('A'.$startcellno,'');
            $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
            $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
            $sheet->setCellValue('C'.$startcellno,'TOTAL');
            $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
            $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('right');
            $sheet->setCellValue('F'.$startcellno,$debit);
            $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
            $sheet->setCellValue('G'.$startcellno,$credit);
            $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
            $sheet->setCellValue('H'.$startcellno,$bal);
            $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
            $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
            $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->getFont()->setBold(true);

            $startcellno+=1;
            
            $sheet->mergeCells('A'.$startcellno.':H'.$startcellno);
            $sheet->setCellValue('A'.$startcellno,'ASSESSMENT');
            $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->applyFromArray($borderstyle);

            $startcellno+=1;
            
            if($studinfo->levelid == 14 || $studinfo->levelid == 15)
            {
              $getPaySched = db::select('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                  from studpayscheddetail
                  where studid = ? and syid = ? and semid = ? and deleted = 0
                  group by MONTH(duedate)
                  order by duedate', [$studid, $syid, $semid]);
      
            }
            else
            {
              $getPaySched = db::select('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                  from studpayscheddetail
                  where studid = ? and syid = ? and deleted = 0
                  group by MONTH(duedate)
                  order by duedate', [$studid, $syid]);
            }
            $assessbilling = 0;
            $assesspayment = 0;
            $assessbalance = 0;
            $totalBal = 0;
            if(count($getPaySched) > 0)
            {
              foreach($getPaySched as $psched)
              {
      
                // return $getPaySched;
                $totalBal += $psched->balance;
                $assessbilling += $psched->amountdue;
                $assesspayment += $psched->amountpay;
                $assessbalance += $psched->balance;
                
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
                if($month == null || $month == "")
                {
                  // return $m . ' != ' . $month;
                  if($m != $month)
                  {
                    if($psched->balance > 0)
                    {
                      $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                      $sheet->setCellValue('A'.$startcellno,'');
                      $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                      $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
                      $sheet->setCellValue('C'.$startcellno,$particulars);
                      $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
                      $sheet->setCellValue('F'.$startcellno,$psched->amountdue);
                      $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
                      $sheet->setCellValue('G'.$startcellno,$psched->amountpay);
                      $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
                      $sheet->setCellValue('H'.$startcellno,$psched->balance);
                      $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
                      $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
                      
                      $startcellno+=1;
                    }
                  }
                  else
                  {
                    if($psched->balance > 0)
                    {
                        $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                        $sheet->setCellValue('A'.$startcellno,'');
                        $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
                        $sheet->setCellValue('C'.$startcellno,$particulars);
                        $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('F'.$startcellno,$psched->amountdue);
                        $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('G'.$startcellno,$psched->amountpay);
                        $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('H'.$startcellno,$psched->balance);
                        $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
                    }
                    else
                    {
                        $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                        $sheet->setCellValue('A'.$startcellno,'');
                        $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
                        $sheet->setCellValue('C'.$startcellno,$particulars);
                        $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('F'.$startcellno,$psched->amountdue);
                        $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('G'.$startcellno,$psched->amountpay);
                        $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->setCellValue('H'.$startcellno,$psched->balance);
                        $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
                        $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
                    }
                    $startcellno+=1;
                    break; 
                  }
                }
                else
                {
                  // return $m . ' != ' . $month; 
                  if($m != $month)
                  {
                
                    $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                    $sheet->setCellValue('A'.$startcellno,'');
                    $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
                    $sheet->setCellValue('C'.$startcellno,$particulars);
                    $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('F'.$startcellno,$psched->amountdue);
                    $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('G'.$startcellno,$psched->amountpay);
                    $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('H'.$startcellno,$psched->balance);
                    $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
                    $startcellno+=1;
                    
                  }
                  else
                  {
                    
                    $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                    $sheet->setCellValue('A'.$startcellno,'');
                    $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
                    $sheet->setCellValue('C'.$startcellno,$particulars);
                    $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('F'.$startcellno,$psched->amountdue);
                    $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('G'.$startcellno,$psched->amountpay);
                    $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->setCellValue('H'.$startcellno,$psched->balance);
                    $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
                    $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
                    $startcellno+=1;
                    
                    break; 
                  }
                }
              }
      
              $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
              $sheet->setCellValue('A'.$startcellno,'');
              $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
              $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
              $sheet->setCellValue('C'.$startcellno,'TOTAL');
              $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('right');
              $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
              $sheet->setCellValue('F'.$startcellno,$assessbilling);
              $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
              $sheet->setCellValue('G'.$startcellno,$assesspayment);
              $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
              $sheet->setCellValue('H'.$startcellno,$assessbalance);
              $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
              $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
              $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->getFont()->setBold(true);
  
              $startcellno+=1;

              $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
              $sheet->setCellValue('A'.$startcellno,'');
              $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
              $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
              $sheet->setCellValue('C'.$startcellno,'TOTAL BALANCE');
              $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('right');
              $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
              $sheet->setCellValue('F'.$startcellno,$assessbilling);
              $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
              $sheet->setCellValue('G'.$startcellno,$assesspayment);
              $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
              $sheet->setCellValue('H'.$startcellno,$totalBal);
              $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
              $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
              $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->getFont()->setBold(true);
  
              $startcellno+=1;

              $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
              $sheet->setCellValue('A'.$startcellno,'');
              $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
              $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
              $sheet->setCellValue('C'.$startcellno,'TOTAL AMOUNT DUE');
              $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('right');
              $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
              $sheet->setCellValue('F'.$startcellno,'');
              $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
              $sheet->setCellValue('G'.$startcellno,'');
              $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
              $sheet->setCellValue('H'.$startcellno,$totalBal);
              $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
              $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
              $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->getFont()->setBold(true);
      
            }else{
                $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                $sheet->setCellValue('A'.$startcellno,'');
                $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
                $sheet->setCellValue('C'.$startcellno,'TOTAL BALANCE');
                $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('right');
                $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
                $sheet->setCellValue('F'.$startcellno,$debit);
                $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
                $sheet->setCellValue('G'.$startcellno,$credit);
                $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
                $sheet->setCellValue('H'.$startcellno,$bal);
                $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
                $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
                $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->getFont()->setBold(true);
    
                $startcellno+=1;
  
                $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
                $sheet->setCellValue('A'.$startcellno,'');
                $sheet->getStyle('A'.$startcellno.':B'.$startcellno)->applyFromArray($borderstyle);
                $sheet->mergeCells('C'.$startcellno.':E'.$startcellno);
                $sheet->setCellValue('C'.$startcellno,'TOTAL AMOUNT DUE');
                $sheet->getStyle('C'.$startcellno)->getAlignment()->setHorizontal('right');
                $sheet->getStyle('C'.$startcellno.':E'.$startcellno)->applyFromArray($borderstyle);
                $sheet->setCellValue('F'.$startcellno,'');
                $sheet->getStyle('F'.$startcellno)->applyFromArray($borderstyle);
                $sheet->setCellValue('G'.$startcellno,'');
                $sheet->getStyle('G'.$startcellno)->applyFromArray($borderstyle);
                $sheet->setCellValue('H'.$startcellno,$bal);
                $sheet->getStyle('H'.$startcellno)->applyFromArray($borderstyle);
                $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getNumberFormat()->setFormatCode( '#,##0.00' );
                $sheet->getStyle('A'.$startcellno.':H'.$startcellno)->getFont()->setBold(true);
      
            }
            $startcellno+=2;
            if($notestatus>0)
            {
                $sheet->setCellValue('A'.$startcellno,'NOTES:');
                $startcellno+=1;
                // $signatories.='<span style="font-size: 9px;font-weight: bold">NOTES:</span><br/>';
                foreach($notes as $note)
                {
                    $sheet->mergeCells('B'.$startcellno.':G'.$startcellno);
                    $sheet->setCellValue('B'.$startcellno,$note->description);
                    $startcellno+=1;
                }
                $startcellno+=1;
            }
            $sheet->mergeCells('A'.$startcellno.':B'.$startcellno);
            $sheet->setCellValue('A'.$startcellno,'Prepared By:');
            $sheet->mergeCells('F'.$startcellno.':H'.$startcellno);
            $sheet->setCellValue('F'.$startcellno,'Received By:');

            $startcellno+=2;

            $sheet->mergeCells('A'.$startcellno.':D'.$startcellno);
            $sheet->getStyle('A'.$startcellno.':D'.$startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->mergeCells('F'.$startcellno.':H'.$startcellno);
            $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $startcellno+=1;
            
            $sheet->mergeCells('A'.$startcellno.':D'.$startcellno);
            $sheet->setCellValue('A'.$startcellno,$preparedby->firstname.' '.$preparedby->middlename.' '.$preparedby->lastname.' '.$preparedby->suffix);
            $sheet->getStyle('A'.$startcellno)->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('F'.$startcellno, 'Date:');
            $sheet->getStyle('F'.$startcellno.':H'.$startcellno)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Statement of Account.xlsx"');
            $writer->save("php://output");
        }
    }
}

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl;
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),15,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
        $title = $this->writeHTMLCell(false, 50, 40, 20, 'Statement of Account', false, false, false, $reseth=true, $align='L', $autopadding=true);
        // Ln();
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}