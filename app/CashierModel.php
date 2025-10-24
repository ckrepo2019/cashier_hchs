<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class CashierModel extends Model
{
    public static function getSYID()
    {
    	if(Auth::check())
        {            
            $sy = DB::table('chrngterminals')
    		      ->where('owner', auth()->user()->id)
    		      ->first();

            if($sy)
            {
                return $sy->syid;
            }
            else
            {
				return db::table('sy')
				         ->where('isactive', 1)
				         ->first()->id;
			}
        }
    }

    public static function getSYDesc()
    {
        if(Auth::check())
        {
            $terminal_sy = DB::table('chrngterminals')
                ->where('owner', auth()->user()->id)
                ->first();

            if($terminal_sy)
            {
                $sy = db::table('sy')
                    ->where('id', $terminal_sy->syid)
                    ->first();

                if($sy)
                {
                    return $sy->sydesc;
                }
            }
            else
            {
                return db::table('sy')
                    ->where('isactive', 1)
                    ->first()->sydesc;
            }
        }
    }

    public static function getSemID()
    {
        // $semID = db::table('semester')
        //         ->where('isactive', 1)
        //         ->first();
        if(Auth::check())
        {
            $semID = db::table('chrngterminals')
                ->where('owner', auth()->user()->id)
                ->first();

            if($semID)
            {
                return $semID->semid;
            }else{
				return db::table('semester')
				         ->where('isactive', 1)
				         ->first()->id;
			}
        }
    }

    public static function getSemDesc()
    {
        if(Auth::check())
        {
            $chrnginfo = db::table('chrngterminals')
                ->where('owner', auth()->user()->id)
                ->first();

            if($chrnginfo)
            {
                $semID = db::table('semester')
                    ->where('id', $chrnginfo->semid)
                    ->first();

                return $semID->semester; 
            }
            else
            {
                return 1;
            }
        }
    }

    public static function getServerDateTime()
    {
        $serverDateTime = db::select('SELECT CURRENT_TIMESTAMP');

        return Carbon::now('Asia/Manila');

        // return $serverDateTime[0]->CURRENT_TIMESTAMP;
    }

    public static function ActiveSY()
    {
        $activesy = db::table('sy')
            ->where('isactive', 1)
            ->first();

        return $activesy->id;
    }

    public static function ActiveSem()
    {
        $sem = db::table('semester')
            ->where('isactive', 1)
            ->first();

        return $sem->id;
    }

    public static function ActiveSemDesc()
    {
        $sem = db::table('semester')
            ->where('isactive', 1)
            ->first();        

        return $sem->semester;
    }

    public static function ActiveSYDesc()
    {
        $activesy = db::table('sy')
            ->where('isactive', 1)
            ->first();

        return $activesy->sydesc;
    }

    public static function colorcode($payno)
    {
    	if($payno==1)
    	{
    		$colorclass = 'pay-1';
    	}

    	elseif($payno==2)
    	{
    		$colorclass = 'pay-2';
    	}

    	elseif($payno==3)
    	{
    		$colorclass = 'pay-3';
    	}

    	elseif($payno==4)
    	{
    		$colorclass = 'pay-4';
    	}

    	elseif($payno==5)
    	{
    		$colorclass = 'pay-5';
    	}

    	elseif($payno==6)
    	{
    		$colorclass = 'pay-6';
    	}

    	elseif($payno==7)
    	{
    		$colorclass = 'pay-7';
    	}

    	elseif($payno==8)
    	{
    		$colorclass = 'pay-8';
    	}

    	elseif($payno==9)
    	{
    		$colorclass = 'pay-9';
    	}

    	elseif($payno==10)
    	{
    		$colorclass = 'pay-10';
    	}

    	elseif($payno==11)
    	{
    		$colorclass = 'pay-11';
    	}

    	elseif($payno==12)
    	{
    		$colorclass = 'pay-12';
    	}

    	else
    	{
    		$colorclass = 'pay-oth';
    	}

    	return $colorclass;

	}
	
	public static function insertOR($ornum, $terminalno, $paytype)
	{
		$checkOR = db::table('orcounter')
			->where('ornum', $ornum)
			->get();
		if(count($checkOR) > 0)
		{
      
            $genORnum = db::table('orcounter')
                ->where(function($q) use($paytype){
                    if($paytype != 'CASH' || $paytype != 'CHEQUE')
                    {
                        $q->where('oltrans', 0);
                    }
                    else
                    {
                        $q->where('oltrans', 0);
                    }
                })
                ->get();


            if($paytype != 'CASH' || $paytype != 'CHEQUE')
            {
                $insertOR = db::table('orcounter')
                    ->insert([
                        'ornum' => $ornum,
                        'terminalno' =>  $terminalno,
                        'used' => 0,
                        'salesinvoice' => 1,
                        'oltrans' => 0,
                        'createddatetime' => CashierModel::getServerDateTime()
                    ]);        
            }
            else
            {
                $insertOR = db::table('orcounter')
                    ->insert([
                        'ornum' => $ornum,
                        'terminalno' =>  $terminalno,
                        'salesinvoice' => 1,
                        'used' => 0,
                        'createddatetime' => CashierModel::getServerDateTime()
                    ]);
            }
		}
		else
		{
            // return $paytype;
            if($paytype != 'CASH' && $paytype != 'CHEQUE')
            {
                $insertOR = db::table('orcounter')
                    ->insert([
                        'ornum' => $ornum,
                        'terminalno' =>  $terminalno,
                        'salesinvoice' => 1,
                        'used' => 0,
                        'oltrans' => 0,
                        'createddatetime' => CashierModel::getServerDateTime()
                    ]);        
            }
            else
            {
      			$insertOR = db::table('orcounter')
      				->insert([
      					'ornum' => $ornum,
      					'terminalno' =>  $terminalno,
                        'salesinvoice' => 1,
                        'used' => 0,
  					     'createddatetime' => CashierModel::getServerDateTime()
                    ]);
            } 
		}
	}

    public static function getornum($terminalno, $paytypeid)
    {
        $schoolinfo = db::table('schoolinfo')
          ->first();

        if($schoolinfo->olreceipt == 1 and $paytypeid >= 3)
        {
          $ornum = db::table('orcounter')
            ->where('terminalno', $terminalno)
            ->where('used', 0)
            ->where('oltrans', 1)
            ->orderBy('id','desc')
            ->take(1)
            ->get(); 
        }
        else
        {
        $ornum = db::table('orcounter')
            ->where('terminalno', $terminalno)
            ->where('used', 0)
            ->where('oltrans', 0)
            ->orderBy('id','desc')
            ->take(1)
            ->get();
        }
        if(count($ornum) > 0)
        {
            return $ornum[0]->ornum;
        }
        else
        {
            return 0;
        }   
	}

    public static function checkornum($ornumber)
    {
        $ornum = db::table('orcounter')
            ->where('ornum', $ornumber)
            ->where('salesinvoice', 1)
            ->first();

        if($ornum)
        {
            if($ornum->used == 0)
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }
        else
        {
            return 0;
        }

    }
	
	public static function ledgerRbal($studid, $syid)
	{
		$rbal = 0;

		$ledger = db::table('studledger')
			->get();
	}



    public static function smsPayment($studid, $amount, $or)
    {
        $stud = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        $dt = date_create(CashierModel::getServerDateTime());
        $dt = date_format($dt, 'm-d-Y h:i A');
        $num = '';

        $studname = strtoupper($stud->lastname  . ', ' . $stud->firstname . ' ' . $stud->middlename .' '. $stud->suffix);

        $msg = 'You have just paid '. number_format($amount, 2) . ' for '. $studname . ' with OR#: ' . $or. '. Thank you. ' . $dt;

        if($stud->ismothernum == 1)
        {
            $num = $stud->mcontactno;
        }
        elseif($stud->isfathernum == 1)
        {
            $num = $stud->fcontactno;
        }
        elseif($stud->isguardannum == 1)
        {
            $num = $stud->gcontactno;
        }

        if($num <> '')
        {
        if($num[0] == 0)
        {
            $num = ltrim($num, $num[0]);
            $num = '+63' . $num;
        }


        $sms = db::table('smsbunker')
            ->insert([
                'message' => $msg,
                'receiver' => $num,
                'smsstatus' => 0
            ]);
    }
  }



    public static function getIp()
    {

    // return substr(exec('getmac'), 0, 17); 
    // return exec('getmac');


    // foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
    //     if (array_key_exists($key, $_SERVER) === true){
    //         foreach (explode(',', $_SERVER[$key]) as $ip){
    //             $ip = trim($ip); // just to be safe
    //             if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
    //                 return $ip;
    //             }
    //             else
    //             {
    //               return $ip;
    //             }
    //         }
    //     }
    // }

        return auth()->user()->id;

    // $cookie = Response::make('cookie');
    // $cookie =Cookie::forever('name', 'testing');
    // return Cookie::get('name')

    // return request()->ip();

    // return $request->header('User-Agent');


    }

    public static function getTransNo()
    {
        $transno = db::select('select max(transno) as transno from transcounter');
        $tnum = $transno[0]->transno + 1; 

        $updtransno = db::table('transcounter') 
            ->where('terminalno', 1)
            ->update([
              'transno' => $tnum
            ]);

        return $tnum;
    }


    public static function getOrderLines($transno, $cashtransid)
    {
        $cashtrans = db::table('chrngcashtrans')
            ->where('transno', $transno)
            ->where('deleted', 0)
            ->get();

        $output = '';
        $tAmount = 0.00;

        if(count($cashtrans) > 0)
        {
            foreach($cashtrans as $cash)
            {
                $tAmount += $cash->amount;

                if($cash->id == $cashtransid)
                {
                    if($cash->qty > 1)
                    {
                        $output .='
                            <ul class="orderlines text-info">
                                <li class="orderline bg-success" detail-id="'.$cash->payscheddetailid.'" data-id="'.$cash->id.'" month-due="'.$cash->duedate.'" paykind="'.$cash->transdone.'" data-value="'.$cash->amount.'">
                                    <span class="product-name">'.$cash->particulars.' </span>
                                    <span class="price">'.number_format($cash->amount, 2).'</span>
                                    <ul class="info-list text-secondary">
                                      <li class="info"><em>QTY:</em> '.$cash->qty.' @ '.$cash->itemprice.'/qty</li>
                                    </ul>
                                </li>
                            </ul>
                        ';
                    }
                    else
                    {
                        $output .='
                            <ul class="orderlines text-info">
                                <li class="orderline bg-success" detail-id="'.$cash->payscheddetailid.'" data-id="'.$cash->id.'" month-due="'.$cash->duedate.'" paykind="'.$cash->transdone.'" data-value="'.$cash->amount.'">
                                    <span class="product-name">'.$cash->particulars.' </span>
                                    <span class="price">'.number_format($cash->amount, 2).'</span>
                                </li>
                            </ul>
                        '; 
                    }
                }
                else
                {
                    if($cash->qty > 1)
                    {
                        $output .='
                            <ul class="orderlines text-info">
                                <li class="orderline" detail-id="'.$cash->payscheddetailid.'" data-id="'.$cash->id.'" month-due="'.$cash->duedate.'" paykind="'.$cash->transdone.'" data-value="'.$cash->amount.'">
                                    <span class="product-name">'.$cash->particulars.' </span>
                                    <span class="price">'.number_format($cash->amount, 2).'</span>
                                    <ul class="info-list text-secondary">
                                      <li class="info"><em>QTY:</em> '.$cash->qty.' @ '.$cash->itemprice.'/qty</li>
                                    </ul>
                                </li>
                            </ul>
                      ';
                    }
                    else
                    {
                        $output .='
                            <ul class="orderlines text-info">
                                <li class="orderline" detail-id="'.$cash->payscheddetailid.'" data-id="'.$cash->id.'" month-due="'.$cash->duedate.'" paykind="'.$cash->transdone.'" data-value="'.$cash->amount.'">
                                    <span class="product-name">'.$cash->particulars.' </span>
                                    <span class="price">'.number_format($cash->amount, 2).'</span>
                                </li>
                            </ul>
                        ';
                    }
                }
            }

            $tAmount = number_format($tAmount, 2, '.', '');
        
            $output .='
                <div class="summary clearfix text-success">
                    <div class="line">
                        <div class="entry total">
                            <span class="">Total: </span> <span id="tAmount" class="value" data-value="'.$tAmount.'"> '.number_format($tAmount, 2).'</span>
                          
                        </div>
                    </div>
                </div>  
            ';
        }
        else
        {
            $output = '
              <div class="order-empty">
                  <i class="fa fa-shopping-cart" role="img" aria-label="Shopping cart" title="Shopping cart"></i>
                  <h1>No data to display</h1>
              </div>       
            ';
        }

        return $output;
    }

    public static function paytype()
    {
        $paytype = db::table('paymenttype')
            ->get();

        return $paytype;
    }

    public static function schoolinfo() 
    {
        $schoolinfo = db::table('schoolinfo')
            ->first();

        return $schoolinfo;
    }

    public static function checkOLPay()
    {
        $olpaid = db::table('onlinepayments')
            ->where('isapproved', 1)
            ->where('syid', CashierModel::getSYID())
            ->count();

        $count = '';

        if($olpaid >= 10)
        {
            $count = '9+';
        }
        else
        {
            $count = $olpaid;
        }

        return $count;

    }

    public static function select2_studlist()
    {
        $student = db::table('studinfo')
            ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelid', 'contactno', 'sid', 'lrn', 'gradelevel.levelname', 'sectionname')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            // ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
            ->where('studinfo.deleted', 0)
            ->orderby('lastname', 'asc')
            ->orderby('firstname', 'asc')
            ->get();

        return $student;
    }

    public static function select2_mop()
    {
        $modeofpayment = db::table('paymentsetup')
          ->where('deleted', 0)
          ->get();

        return $modeofpayment;
    }

    public static function v2_orderlines($transno)
    {
        $total = 0;
        $line = '';

        $cashtrans = db::table('chrngcashtrans')
            ->where('transno', $transno)
            ->where('deleted', 0)
            // ->orderBy('kind')
            // ->orderBy('duedate')
            ->get();

        // return $cashtrans;

        foreach($cashtrans as $trans)
        {
            $line .='
                <tr class="v2_selitems" data-id="'.$trans->id.'" paysched-id="'.$trans->payscheddetailid.'" data-kind="'.$trans->kind.'">
                    <td class="sel_particulars">'.$trans->particulars.'</td>
                    <td class="text-right">
                        <input type="text" class="line-val" placeholder="0.00" value="'.number_format($trans->amount, 2).'" name="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" data-id="'.$trans->id.'">
                    </td>
                    <td class="text-right">
                        <button class="btn btn-sm btn-remove btn-gray text-danger" data-id="'.$trans->id.'" data-particulars="'.$trans->particulars.'"><i class="fas fa-times"></i></i></button>
                    </td>
                </tr>
            ';

            $total += $trans->amount;
        }

        $data = array(
            'line' => $line,
            'total' => $total
        );

        return $data;
    }

    

    public static function ledgeritemizedreset($studid)
    {
        db::table('studledgeritemized')
          ->where('studid', $studid)
          ->where('syid', CashierModel::getSYID())
          ->where('semid', CashierModel::getSemID())
          ->delete();


        $studinfo = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        if($studinfo)
        {
            if($studinfo->studstatus != 0)
            {
                $tuitions = db::table('tuitionheader')
                    ->select('syid', 'semid', 'grantee', 'tuitiondetail.id as detailid', 'tuitionitems.id as tuitionitemid', 'classificationid', 'itemid', 'tuitionitems.amount')
                    ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                    ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                    ->where('tuitionheader.deleted', 0)
                    ->where('tuitiondetail.deleted', 0)
                    ->where('tuitionitems.deleted', 0)
                    ->where('levelid', $studinfo->levelid)
                    ->where('grantee', $studinfo->grantee)
                    ->where('syid', CashierModel::getSYID())
                    ->where('semid', CashierModel::getSemID())
                    ->get();

                if(count($tuitions) == 0)
                {
                    $tuitions = db::table('tuitionheader')
                    ->select('syid', 'semid', 'grantee', 'tuitiondetail.id as detailid', 'tuitionitems.id as tuitionitemid', 'classificationid', 'itemid', 'tuitionitems.amount')
                    ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                    ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                    ->where('tuitionheader.deleted', 0)
                    ->where('tuitiondetail.deleted', 0)
                    ->where('tuitionitems.deleted', 0)
                    ->where('levelid', $studinfo->levelid)
                    ->where('syid', CashierModel::getSYID())
                    ->where('semid', CashierModel::getSemID())
                    ->get();
                }

                foreach($tuitions as $tuition)
                {
                    db::table('studledgeritemized')
                        ->insert([
                            'studid' => $studinfo->id,
                            'syid' => CashierModel::getSYID(),
                            'semid' => CashierModel::getSemID(),
                            'tuitiondetailid' => $tuition->detailid,
                            'classificationid' => $tuition->classificationid,
                            'tuitionitemid' => $tuition->tuitionitemid,
                            'itemAmount' => $tuition->amount,
                            'itemid' => $tuition->itemid,
                            'deleted' => 0
                        ]); 
                }


                $balforwardsetup = db::table('balforwardsetup')->first();

                $studledger = db::table('studledger')
                    ->where('studid', $studid)
                    ->where('syid', CashierModel::getSYID())
                    ->where('semid', CashierModel::getSemID())
                    ->where('classid', $balforwardsetup->classid)
                    ->where('void', 0)
                    ->where('deleted', 0)
                    ->first();

                if($studledger)
                {
                    db::table('studledgeritemized')
                        ->insert([
                            'studid' => $studid,
                            'syid' => CashierModel::getSYID(),
                            'semid' => CashierModel::getSemID(), 
                            'classificationid' => $studledger->classid,
                            'itemamount' => $studledger->amount,
                            'deleted' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => CashierModel::getServerDateTime()
                        ]);
                }

            }
        }
    }

    public static function transitemsreset($studid)
    {
        db::table('chrngtransitems')
          ->where('studid', $studid)
          ->where('syid', CashierModel::getSYID())
          ->where('semid', CashierModel::getSemID())
          ->delete();

        $chrngtrans = db::table('chrngtrans')
            ->select('chrngtransid', 'chrngtransdetail.id as chrngtransdetailid', 'ornum', 'classid', 'chrngtransdetail.amount', 'studid', 'syid', 'semid')
            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
            ->where('cancelled', 0)
            ->where('studid', $studid)
            ->get();

        $transOR = 0;
        $transstudID = 0;

        foreach($chrngtrans as $trans)
        {

            $transOR = $trans->ornum;
            $transstudID = $trans->studid;
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
                    AND (`totalamount` < itemamount or isnull(totalamount))', [$trans->studid, $trans->syid, $trans->semid, $trans->classid]
            );


            if(count($ledgeritemized) == 0)
            {
                $ledgeritemized = db::select(
                    'SELECT *
                    FROM `studledgeritemized` 
                    WHERE `studid` = ? 
                        AND `syid` = ? 
                        AND `semid` = ? 
                        AND `classificationid` =? 
                        AND `deleted` = 0', [$trans->studid, $trans->syid, $trans->semid, $trans->classid]
                );
            }

            foreach($ledgeritemized as $item)
            {
                if($transamount > 0)
                {
                    $checkitem = db::table('studledgeritemized')
                        ->where('id', $item->id)
                        ->first();

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

            if($transamount > 0)
            {

                $chrngtrans = db::table('chrngtrans')
                    ->select('chrngtransid', 'chrngtransdetail.id as chrngtransdetailid', 'ornum', 'classid', 'chrngtransdetail.amount', 'studid', 'syid', 'semid')
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->where('cancelled', 0)
                    ->where('posted', 1)
                    ->where('studid', $transstudID)
                    ->where('ornum', $transOR)
                    ->get();

                foreach($chrngtrans as $trans)
                {
                    if($transamount > 0)
                    {
                        $ledgeritemized = db::select(
                            'SELECT *
                            FROM `studledgeritemized` 
                            WHERE `studid` = ? 
                                AND `syid` = ? 
                                AND `semid` = ? 
                                AND `classificationid` =? 
                                AND `deleted` = 0', [$trans->studid, $trans->syid, $trans->semid, $trans->classid]
                        );

                        foreach($ledgeritemized as $item)
                        {
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
    }

    public static function chrngdistlogs($studid, $transid, $transdetailid, $scheddetailid, $classid, $amount)
    {
        db::table('chrngdistlogs')
            ->insert([
                'studid' => $studid,
                'transid' => $transid,
                'transdetailid' => $transdetailid,
                'scheddetailid' => $scheddetailid,
                'classid' => $classid,
                'amount' => $amount,
                'createddatetime' => CashierModel::getServerDateTime(),
                'createdby' => auth()->user()->id
            ]);
    }

    public static function shssetup()
    {
        $setup = db::table('schoolinfo')
            ->first()
            ->shssetup;

        return $setup;
    }


    public static function procItemized($tuitiondetailid, $payschedid ,$amount, $classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind)
    {
        $setup = db::table('chrngsetup')
            ->where('classid', $classid)
            ->first();

        if($setup)
        {
            if($setup->itemized == 0)
            {
                if($amount > 0)
                {
                    $itemized = db::table('studledgeritemized')   
                        ->where('tuitiondetailid', $tuitiondetailid)
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->whereColumn('totalamount', '<', 'itemamount')
                        ->where('classificationid', $classid)
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(CashierModel::getSemID() == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    if(CashierModel::shssetup() == 0)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                }
                            }
                            elseif($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                            else
                            {
                                if(CashierModel::getSemID() == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    $q->where('semid', '!=', 3);
                                }
                            }
                        })
                        ->get();

                    if(count($itemized) == 0)
                    {
                        $itemized = db::table('studledgeritemized')   
                            ->where('studid', $studid)
                            ->where('deleted', 0)
                            ->whereColumn('totalamount', '<', 'itemamount')
                            ->where('classificationid', $classid)
                            ->where(function($q) use($levelid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(CashierModel::getSemID() == 3)
                                    {
                                        $q->where('semid', 3);
                                    }
                                    else
                                    {
                                        if(CashierModel::shssetup() == 0)
                                        {
                                            $q->where('semid', CashierModel::getSemID());
                                        }
                                    }
                                }
                                elseif($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                                else
                                {
                                    if(CashierModel::getSemID() == 3)
                                    {
                                        $q->where('semid', 3);
                                    }
                                    else
                                    {
                                        $q->where('semid', '!=', 3);
                                    }
                                }
                            })
                            ->get();

                        if(count($itemized) == 0)
                        {
                            $itemized = db::table('studledgeritemized')   
                                ->where('studid', $studid)
                                ->where('deleted', 0)
                                ->whereColumn('totalamount', '<', 'itemamount')
                                ->where(function($q) use($levelid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('semid', 3);
                                        }
                                        else
                                        {
                                            if(CashierModel::shssetup() == 0)
                                            {
                                                $q->where('semid', CashierModel::getSemID());
                                            }
                                        }
                                    }
                                    elseif($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                    else
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('semid', 3);
                                        }
                                        else
                                        {
                                            $q->where('semid', '!=', 3);
                                        }
                                    }
                                })
                                ->get();   
                        }
                    }

                    foreach($itemized as $item)
                    {
                        $balance = $item->itemamount - $item->totalamount;
                        if($amount > $balance)
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $balance,
                                    'updateddatetime' => CashierModel::getServerDateTime(),
                                    'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $balance,
                                    'studid' => $studid,
                                    'syid' => CashierModel::getSYID(),
                                    'semid' => CashierModel::getSemID(),
                                    'kind' => $kind,
                                    'createddatetime' => CashierModel::getServerDateTime(),
                                    'createdby' => auth()->user()->id,
                                ]);

                            $amount -= $balance;
                        }
                        else
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $amount,
                                    'updateddatetime' => CashierModel::getServerDateTime(),
                                    'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $amount,
                                    'studid' => $studid,
                                    'syid' => CashierModel::getSYID(),
                                    'semid' => CashierModel::getSemID(),
                                    'kind' => $kind,
                                    'createddatetime' => CashierModel::getServerDateTime(),
                                    'createdby' => auth()->user()->id,
                                ]);

                            $amount = 0;
                        }
                    }
                }
            }
            else
            {
                if($amount > 0)
                {
                    $itemized = db::table('studledgeritemized')
                        ->where('id', $payschedid)
                        ->where('studid', $studid)
                        ->whereColumn('totalamount', '<', 'itemamount')
                        ->where('deleted', 0)
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(CashierModel::getSemID() == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    if(CashierModel::shssetup() == 0)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                }
                            }
                            elseif($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                            else
                            {
                                if(CashierModel::getSemID() == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    $q->where('semid', '!=', 3);
                                }
                            }
                        })
                        ->get();

                    if(count($itemized) == 0)
                    {
                        $itemized = db::table('studledgeritemized')
                            ->where('classificationid', $classid)
                            ->where('studid', $studid)
                            ->whereColumn('totalamount', '<', 'itemamount')
                            ->where('deleted', 0)
                            ->where(function($q) use($levelid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(CashierModel::getSemID() == 3)
                                    {
                                        $q->where('semid', 3);
                                    }
                                    else
                                    {
                                        if(CashierModel::shssetup() == 0)
                                        {
                                            $q->where('semid', CashierModel::getSemID());
                                        }
                                    }
                                }
                                elseif($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                                else
                                {
                                    if(CashierModel::getSemID() == 3)
                                    {
                                        $q->where('semid', 3);
                                    }
                                    else
                                    {
                                        $q->where('semid', '!=', 3);
                                    }
                                }
                            })
                            ->get();

                        if(count($itemized) == 0)
                        {
                            $itemized = db::table('studledgeritemized')
                                // ->where('classificationid', $classid)
                                ->where('studid', $studid)
                                ->whereColumn('totalamount', '<', 'itemamount')
                                ->where('deleted', 0)
                                ->where(function($q) use($levelid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('semid', 3);
                                        }
                                        else
                                        {
                                            if(CashierModel::shssetup() == 0)
                                            {
                                                $q->where('semid', CashierModel::getSemID());
                                            }
                                        }
                                    }
                                    elseif($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                    else
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('semid', 3);
                                        }
                                        else
                                        {
                                            $q->where('semid', '!=', 3);
                                        }
                                    }
                                })
                                ->get();
                        }
                    }
                    
                    foreach($itemized as $item)
                    {
                        $balance = $item->itemamount - $item->totalamount;

                        if($amount > $balance)
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $balance,
                                    'updateddatetime' => CashierModel::getServerDateTime(),
                                    'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $balance,
                                    'studid' => $studid,
                                    'syid' => CashierModel::getSYID(),
                                    'semid' => CashierModel::getSemID(),
                                    'kind' => $kind,
                                    'createddatetime' => CashierModel::getServerDateTime(),
                                    'createdby' => auth()->user()->id,
                                ]);

                            $amount -= $balance;
                        }
                        else
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $amount,
                                    'updateddatetime' => CashierModel::getServerDateTime(),
                                    'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $amount,
                                    'studid' => $studid,
                                    'syid' => CashierModel::getSYID(),
                                    'semid' => CashierModel::getSemID(),
                                    'kind' => $kind,
                                    'createddatetime' => CashierModel::getServerDateTime(),
                                    'createdby' => auth()->user()->id,
                                ]);

                            $amount = 0;
                        }
                    }
                }
            }


            if($amount > 0)
            {
                $itemized = db::table('studledgeritemized')   
                    ->where('tuitiondetailid', $tuitiondetailid)
                    ->where('deleted', 0)
                    ->whereColumn('totalamount', '<', 'itemamount')
                    ->where(function($q) use($levelid){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if(CashierModel::getSemID() == 3)
                            {
                                $q->where('semid', 3);
                            }
                            else
                            {
                                if(CashierModel::shssetup() == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }
                        }
                        elseif($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', CashierModel::getSemID());
                        }
                        else
                        {
                            if(CashierModel::getSemID() == 3)
                            {
                                $q->where('semid', 3);
                            }
                            else
                            {
                                $q->where('semid', '!=', 3);
                            }
                        }
                    })
                    ->get();

                foreach($itemized as $item)
                {
                    $balance = $item->itemamount - $item->totalamount;
                    if($amount > $balance)
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $balance,
                                'updateddatetime' => CashierModel::getServerDateTime(),
                                'updatedby' => auth()->user()->id
                            ]);

                        db::table('chrngtransitems')
                            ->insert([
                                'chrngtransid' => $chrngtransid,
                                'chrngtransdetailid' => $chrngtransdetailid,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'classid' => $classid,
                                'amount' => $balance,
                                'studid' => $studid,
                                'syid' => CashierModel::getSYID(),
                                'semid' => CashierModel::getSemID(),
                                'kind' => $kind,
                                'createddatetime' => CashierModel::getServerDateTime(),
                                'createdby' => auth()->user()->id,
                            ]);

                        $amount -= $balance;
                    }
                    else
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $amount,
                                'updateddatetime' => CashierModel::getServerDateTime(),
                                'updatedby' => auth()->user()->id
                            ]);

                        db::table('chrngtransitems')
                            ->insert([
                                'chrngtransid' => $chrngtransid,
                                'chrngtransdetailid' => $chrngtransdetailid,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'classid' => $classid,
                                'amount' => $amount,
                                'studid' => $studid,
                                'syid' => CashierModel::getSYID(),
                                'semid' => CashierModel::getSemID(),
                                'kind' => $kind,
                                'createddatetime' => CashierModel::getServerDateTime(),
                                'createdby' => auth()->user()->id,
                            ]);

                        $amount = 0;
                    }   
                }

            }

        }
    }

    public static function voidledgeritemized($studid, $chrngtransid)
    {
        $levelid = db::table('studinfo')->where('id', $studid)->first()->levelid;

        $chrngtransitems = db::table('chrngtransitems')
            ->where('chrngtransid', $chrngtransid)
            ->where('deleted', 0)
            ->get();

        foreach($chrngtransitems as $items)
        {
            $setup = db::table('chrngsetup')
                ->where('classid', $items->classid)
                ->first();

            // return $items->classid;

            if($setup)
            {
                if($setup->itemized == 0)
                {
                    $ledgeritemized = db::table('studledgeritemized')
                        ->where('classificationid', $items->classid)
                        ->where('deleted', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->get();

                    foreach($ledgeritemized as $leditem)
                    {
                        $_amount = $leditem->totalamount - $items->amount;

                        if($_amount < 0)
                        {
                            $_amount = 0;
                        }

                        db::table('studledgeritemized')
                            ->where('id', $leditem->id)
                            ->update([
                                'totalamount' =>  $_amount,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => CashierModel::getServerDateTime()
                            ]);
                    }
                }
                else
                {
                    $ledgeritemized = db::table('studledgeritemized')
                        ->where('itemid', $items->itemid)
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->first();

                    if($ledgeritemized)
                    {
                        $_amount = $ledgeritemized->totalamount - $items->amount;

                        

                        if($_amount < 0)
                        {
                            $_amount = 0;
                        }

                        db::table('studledgeritemized')
                            ->where('id', $ledgeritemized->id)
                            ->update([
                                'totalamount' => $_amount,
                                'updatedby' => auth()->user()->id,
                                'updateddatetime' => CashierModel::getServerDateTime()
                            ]);
                    }


                }
            }
        }

        db::table('chrngtransitems')
            ->where('chrngtransid', $chrngtransid)
            ->where('deleted', 0)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => CashierModel::getServerDateTime()
            ]);
    }

    public static function voidledgeritemized_v2($studid, $ornum, $levelid)
    {
        $syid = CashierModel::getSYID();
        $semid = CashierModel::getSemID();

        $chrngtransitems = db::table('chrngtransitems')
            ->where('studid', $studid)
            ->where('ornum', $ornum)
            ->where('deleted', 0)
            ->get();

        foreach($chrngtransitems as $transitems)
        {
            $ledgeritemized = db::table('studledgeritemized')
                ->where('studid', $studid)
                ->where('itemid', $transitems->itemid)
                ->where('syid', $syid)
                ->where(function($q) use($levelid){
                    if($levelid == 14 || $levelid == 15)
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    }
                    if($levelid >= 17 && $levelid <= 20)
                    {
                        $q->where('semid', $semid);
                    }
                })
                ->first();

            if($ledgeritemized)
            {
                $amount = $ledgeritemized->totalamount - $transitems->amount;

                db::table('studledgeritemized')
                    ->where('id', $ledgeritemized->id)
                    ->update([
                        'totalamount' => $amount,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => CashierModel::getServerDateTime()
                    ]);
            }

        }
    }

    public static function syinfo($studid, $element)
    {
        $_estud = collect();
        $ledgerinfo_list = '';

        $_enrolled = db::table('enrolledstud')
            ->select('levelid', 'syid', 'ghssemid as semid', 'levelname')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->where('studid', $studid)
            ->where('enrolledstud.deleted', 0)
            ->where('studstatus', '>', 0)
            ->get();

        $sh_enrolled = db::table('sh_enrolledstud')
            ->select('levelid', 'syid', 'semid', 'levelname')
            ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
            ->where('studid', $studid)
            ->where('sh_enrolledstud.deleted', 0)
            ->where('studstatus', '>', 0)
            ->get();

        $college_enrolled = db::table('college_enrolledstud')
            ->select('yearLevel as levelid', 'syid', 'semid', 'levelname')
            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->where('studid', $studid)
            ->where('college_enrolledstud.deleted', 0)
            ->where('studstatus', '>', 0)
            ->get();

        $_estud = $_estud->merge($_enrolled);
        $_estud = $_estud->merge($sh_enrolled);
        $_estud = $_estud->merge($college_enrolled);

        $ledgerinfo = db::table('studledger')
            ->select(db::raw('`syid`, `semid`, `sydesc`, `semester`, SUM(amount) - SUM(payment) AS balance'))
            ->join('sy', 'studledger.syid', '=', 'sy.id')
            ->join('semester', 'studledger.semid', '=', 'semester.id')
            ->where('studid', $studid)
            ->where('studledger.deleted', 0)
            ->groupBy('syid', 'semid')
            ->get();

        $activesy = db::table('sy')
            ->where('isactive', 1)
            ->first();

        $activesem = db::table('semester')
            ->where('isactive', 1)
            ->first();

        if($element == 'option')
        {

            foreach($ledgerinfo as $l_info)
            {
                $filtered = $_estud->where('syid', $l_info->syid)->first();
                

                if($filtered)
                {
                    if($l_info->syid == $activesy->id && $l_info->semid == $activesem->id)
                    {
                        $ledgerinfo_list .='
                            <option value="'.$l_info->syid.','.$l_info->semid.'">
                                '.$l_info->sydesc.' - '.$l_info->semester.' | '.$filtered->levelname.' - ACTIVE
                            </option>
                        ';
                    }
                    else
                    {
                        $ledgerinfo_list .='
                            <option value="'.$l_info->syid.','.$l_info->semid.'">
                                '.$l_info->sydesc.' - '.$l_info->semester.' | '.$filtered->levelname.'
                            </option>
                        ';
                    }
                }
                else
                {
                    if($l_info->syid == $activesy->id && $l_info->semid == $activesem->id)
                    {
                        $ledgerinfo_list .='
                            <option value="'.$l_info->syid.','.$l_info->semid.'">
                                '.$l_info->sydesc.' - '.$l_info->semester.' | NOT ENROLLED - ACTIVE
                            </option>
                        ';   
                    }
                    else
                    {
                        $ledgerinfo_list .='
                            <option value="'.$l_info->syid.','.$l_info->semid.'">
                                '.$l_info->sydesc.' - '.$l_info->semester.' | NOT ENROLLED
                            </option>
                        ';      
                    }
                }

                // $studledger = db::table('studledger')
                //     ->select(db::raw('SUM(amount) - SUM(payment) AS balance'))
                //     ->where()
            }
        }
        else
        {
            foreach($ledgerinfo as $l_info)
            {
                $filtered = $_estud->where('syid', $l_info->syid)->first();
                
                if($filtered)
                {
                    $ledgerinfo_list .='
                        <tr data-sy="'.$l_info->syid.'" data-sem="'.$l_info->semid.'">
                            <td>'.$l_info->sydesc.'</td>
                            <td>'.strtoupper($l_info->semester).'</td>
                            <td>'.$filtered->levelname.'</td>
                            <td>'.number_format($l_info->balance, 2).'</td>
                        </tr>
                    ';   
                }
                else
                {
                    $ledgerinfo_list .='
                        <tr data-sy="'.$l_info->syid.'" data-sem="'.$l_info->semid.'">
                            <td>'.$l_info->sydesc.'</td>
                            <td>'.strtoupper($l_info->semester).'</td>
                            <td>NOT ENROLLED</td>
                            <td>'.number_format($l_info->balance, 2).'</td>
                        </tr>
                    ';      
                }
            }   
        }
        
        $ledgerinfo_list .='
            <tr>
                <td colspan="4" class="text-center text-bold" data-sy="0" data-sem="0">OTHER SCHOOL YEAR</td>
            </tr>
        ';        

        $ledgerinfo_list .='
            <tr data-sy="'.$activesy->id.'" data-sem="'.$activesem->id.'">
                <td class="">'.$activesy->sydesc.'</td>
                <td class="">'.strtoupper($activesem->semester).'</td>
                <td colspan="2" class="text-center text-bold">ACTIVE</td>
            </tr>
        ';

        $ledgerinfo_list .='
            <tr data-sy="'.$activesy->id.'" data-sem="3">
                <td class="">'.$activesy->sydesc.'</td>
                <td class="">SUMMER</td>
                <td colspan="2" class="text-center text-bold">SUMMER CLASS</td>
            </tr>
        ';

        $sy = db::table('sy')
            ->orderBy('sydesc', 'desc')
            ->first();

        if($sy->isactive != 1)
        {
            $ledgerinfo_list .='
                <tr data-sy="'.$sy->id.'" data-sem="1">
                    <td class="">'.$sy->sydesc.'</td>
                    <td class="">1ST SEMESTER</td>
                    <td colspan="2" class="text-center text-bold">EARLY ENROLLMENT</td>
                </tr>
            ';            
        }
        else
        {
            $sem = db::table('semester')
                ->where('isactive', 1)
                ->first();

            if($sem->id == 1)
            {
                $ledgerinfo_list .='
                    <tr data-sy="'.$sy->id.'" data-sem="2">
                        <td class="">'.$sy->sydesc.'</td>
                        <td class="">2ND SEMESTER</td>
                        <td colspan="2" class="text-center text-bold">EARLY ENROLLMENT</td>
                    </tr>
                ';       
            }
            // elseif($sem == 2)
            // {
            //     $ledgerinfo_list .='
            //         <tr data-sy="'.$sy->id.'" data-sem="2">
            //             <td class="">'.$sy->sydesc.'</td>
            //             <td class="">2ND SEMESTER</td>
            //             <td colspan="2" class="text-center text-bold">EARLY ENROLLMENT</td>
            //         </tr>
            //     ';   
            // }
        }

        $data = array(
            'list' => $ledgerinfo_list,
            'activesy' => $activesy->id,
            'activesem' => $activesem->id
        );

        echo json_encode($data);
        // return $data;
    }

	public static function enrollmentinfo($studid, $syid, $semid)
    {
        $_estud = collect();

        $_enrolled = db::table('enrolledstud')
            ->select('enrolledstud.id', 'enrolledstud.levelid', 'enrolledstud.syid', 'ghssemid as semid', 'levelname', 'sectionid', 'sectionname')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->leftJoin('sections', 'enrolledstud.sectionid', '=', 'sections.id')
            ->where('studid', $studid)
            ->where('enrolledstud.deleted', 0)
            ->where('studstatus', '>', 0)
            ->where('enrolledstud.syid', $syid)
            ->get();

        $sh_enrolled = db::table('sh_enrolledstud')
            ->select('sh_enrolledstud.id', 'sh_enrolledstud.levelid', 'sh_enrolledstud.syid', 'sh_enrolledstud.semid', 'levelname', 'strandid', 'sectionid', 'sectionname', 'strandcode')
            ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
            ->leftJoin('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
            ->leftJoin('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
            ->where('studid', $studid)
            ->where('sh_enrolledstud.deleted', 0)
            ->where('studstatus', '>', 0)
            ->where('sh_enrolledstud.syid', $syid)
            ->groupBy('studid')
            ->get();

        $college_enrolled = db::table('college_enrolledstud')
            ->select('college_enrolledstud.id', 'yearLevel as levelid', 'college_enrolledstud.syid', 'college_enrolledstud.semid', 'levelname', 'sectionid', 'sectiondesc', 'courseabrv')
            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->leftJoin('college_sections', 'college_enrolledstud.sectionid', '=', 'college_sections.id')
            ->leftJoin('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
            ->where('studid', $studid)
            ->where('college_enrolledstud.deleted', 0)
            ->where('studstatus', '>', 0)
            ->where('college_enrolledstud.syid', $syid)
            ->where('college_enrolledstud.semid', $semid)
            ->get();

        $_estud = $_estud->merge($_enrolled);
        $_estud = $_estud->merge($sh_enrolled);
        $_estud = $_estud->merge($college_enrolled);

        return $_estud;
    }



















    public static function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }

        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);

    }

  public static function numberTowords($num)
  {

    $ones = array(
    0 =>"ZERO",
    1 => "ONE",
    2 => "TWO",
    3 => "THREE",
    4 => "FOUR",
    5 => "FIVE",
    6 => "SIX",
    7 => "SEVEN",
    8 => "EIGHT",
    9 => "NINE",
    10 => "TEN",
    11 => "ELEVEN",
    12 => "TWELVE",
    13 => "THIRTEEN",
    14 => "FOURTEEN",
    15 => "FIFTEEN",
    16 => "SIXTEEN",
    17 => "SEVENTEEN",
    18 => "EIGHTEEN",
    19 => "NINETEEN",
    "014" => "FOURTEEN"
    );
    $tens = array( 
    0 => "ZERO",
    1 => "TEN",
    2 => "TWENTY",
    3 => "THIRTY", 
    4 => "FORTY", 
    5 => "FIFTY", 
    6 => "SIXTY", 
    7 => "SEVENTY", 
    8 => "EIGHTY", 
    9 => "NINETY" 
    ); 
    $hundreds = array( 
    "HUNDRED", 
    "THOUSAND", 
    "MILLION", 
    "BILLION", 
    "TRILLION", 
    "QUARDRILLION" 
    ); /*limit t quadrillion */
    $num = number_format($num,2,".",","); 
    $num_arr = explode(".",$num); 
    $wholenum = $num_arr[0]; 
    $decnum = $num_arr[1]; 
    $whole_arr = array_reverse(explode(",",$wholenum)); 
    krsort($whole_arr,1); 
    $rettxt = ""; 
    foreach($whole_arr as $key => $i){
      
    while(substr($i,0,1)=="0")
        $i=substr($i,1,5);
    if($i < 20){ 
    /* echo "getting:".$i; */
    $rettxt .= $ones[$i]; 
    }elseif($i < 100){ 
    if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
    if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
    }else{ 
    if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
    if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
    if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
    } 
    if($key > 0){ 
    $rettxt .= " ".$hundreds[$key]." "; 
    }
    } 
    if($decnum > 0){
    $rettxt .= " and ";
    if($decnum < 20){
    $rettxt .= $ones[$decnum];
    }elseif($decnum < 100){
    $rettxt .= $tens[substr($decnum,0,1)];
    $rettxt .= " ".$ones[substr($decnum,1,1)];
    }
    }
    return $rettxt;
  }


}
