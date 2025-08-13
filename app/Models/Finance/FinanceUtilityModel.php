<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use App\RegistrarModel;
use App\CashierModel;

class FinanceUtilityModel extends Model
{
    public static function resetv3_generatefees($studid, $glevel, $enrollid, $syid, $semid, $feesid)
    {
        $enroll_table = '';

        if($glevel == 14 || $glevel == 15)
        {
            $enroll_table = 'sh_enrolledstud';
        }
        elseif($glevel >= 17 && $glevel <= 20)
        {
            $enroll_table = 'college_enrolledstud';
        }
        else
        {
            $enroll_table = 'enrolledstud';
        }

        $enrollinfo = db::table($enroll_table)
            ->where('id', $enrollid)
            ->first();

        if($enrollinfo)
        {
            $dateenrolled = $enrollinfo->createddatetime;
        }
        else
        {
            $dateenrolled = CashierModel::getServerDateTime();
        }

        if($dateenrolled == null || $dateenrolled == '')
        {
            $dateenrolled = CashierModel::getServerDateTime();   
        }

        $tuition = db::table('tuitionheader')
            ->select('tuitionheader.id', 'tuitiondetail.id as tuitiondetailid', 'syid', 'levelid', 'grantee.description', 'itemclassification.description as particulars', 'amount', 'itemclassification.id as classid', 'pschemeid', 'semid', 'istuition', 'persubj', 'permop', 'permopid')
            ->leftjoin('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')  
            ->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
            ->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
            ->where('tuitionheader.id', $feesid)
            ->where('tuitiondetail.deleted', 0)
            ->get();


        if($glevel >= 17 && $glevel <=20)
        {
            $totalunits = db::table('college_studsched')
                ->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
                ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                ->where('college_studsched.studid', $studid)
                ->where('college_studsched.deleted', 0)
                ->where('college_classsched.deleted', 0)
                ->where('college_classsched.syID', $syid)
                ->where('college_classsched.semesterID', $semid)
                ->first();

            if($totalunits)
            {
                $units = $totalunits->totalunits;
            }
            else
            {
                $units = 0;
            }

        }

        if(count($tuition) > 0)
        {
            $feesid = $tuition[0]->id;

            db::table('studinfo')
                ->where('id', $studid)
                ->update([
                    'feesid' => $feesid
                ]);
        }


        $college_particulars = '';
        $tuitionamount = 0;
        $itemizedamount = 0;

        // return $tuition;

        foreach($tuition as $tui)
        {
            if($glevel >= 17 && $glevel <=20)
            {
                if($tui->istuition == 1)
                {
                    // echo $tui->amount . ' * ' . $units;
                    $tuitionamount = $tui->amount * $units;
                    $college_particulars = ' | ' . $units . ' Units';
                }
                else
                {
                    $tuitionamount = $tui->amount;
                    $college_particulars = '';
                }


                if($tui->persubj == 1)
                {
                    $totalsubj = db::table('college_studsched')
                        ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                        ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                        ->where('college_studsched.studid', $studid)
                        ->where('college_studsched.deleted', 0)
                        ->where('college_classsched.deleted', 0)
                        ->where('college_classsched.syID', $syid)
                        ->where('college_classsched.semesterID', $semid)
                        ->count();

                    $tuitionamount *= $totalsubj;
                }

                if($tui->permop == 1)
                {
                    $paymentsetup = db::table('paymentsetup')
                        ->where('id', $tui->permopid)
                        ->first();

                    if($paymentsetup)
                    {
                        $tuitionamount *= $paymentsetup->noofpayment;
                    }
                }
            }
            else
            {
                $tuitionamount = $tui->amount;
                $college_particulars = '';
            }


            $sLedger = db::table('studledger')
                ->insert([
                    'studid' => $studid,
                    'enrollid' => $enrollid,
                    'syid' => $syid,
                    'semid' => $semid,
                    'classid' => $tui->classid,
                    'particulars' => $tui->particulars . $college_particulars,
                    'amount' => $tuitionamount,
                    'pschemeid' => $tui->pschemeid,
                    'deleted' => 0,
                    'createddatetime' => $dateenrolled
                ]);


                    //studledger Itemized

            $tuitionitems = db::table('tuitionitems')
                ->where('tuitiondetailid', $tui->tuitiondetailid)
                ->where('deleted', 0)
                ->get();

            foreach($tuitionitems as $tItems)
            {
                $checkitemized = db::table('studledgeritemized')
                    ->where('studid', $studid)
                    ->where('tuitionitemid', $tItems->id)
                    ->where('deleted', 0)
                    ->count();

                if($checkitemized == 0)
                {
                    if($tui->istuition == 1)
                    {
                        $itemizedamount = $tuitionamount;
                    }
                    else
                    {
                        $itemizedamount = $tItems->amount;
                    }

                    if($tui->persubj == 1)
                    {
                        $itemizedamount = $tuitionamount;
                    }


                    db::table('studledgeritemized')
                        ->insert([
                            'studid' => $studid,
                            'semid' => $semid,
                            'syid' => $syid,
                            'tuitiondetailid' => $tui->tuitiondetailid,
                            'classificationid' => $tui->classid,
                            'tuitionitemid' => $tItems->id,
                            'itemid' => $tItems->itemid,
                            'itemamount' => $itemizedamount,
                            // 'createdby' => auth()->user()->id,
                            'createddatetime' => RegistrarModel::getServerDateTime(),
                            'deleted' => 0
                        ]);
                }
            }

            $paymentsetup = db::table('paymentsetup')
                ->select('paymentsetup.id', 'paymentdesc', 'paymentsetup.noofpayment', 'paymentno', 'duedate', 'payopt', 'percentamount')
                ->leftjoin('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
                ->where('paymentsetup.id', $tui->pschemeid)
                ->where('paymentsetupdetail.deleted', 0)
                ->get();
                
                
            if($paymentsetup[0]->payopt == 'divided')
            {
                $divPay = 0;

                if(count($paymentsetup) > 1)
                {
                    $paymentno = $paymentsetup[0]->noofpayment;
                    $divPay = $tuitionamount / $paymentno;
                    $divPay = number_format($divPay, 2, '.', '');
                }
                else
                {
                    $paymentno = 1;
                    $divPay = $tuitionamount;
                    $divPay = number_format($divPay, 2, '.', '');
                }

                // echo ' divPay: ' . $divPay;
                $paycount = 0;
                $paytAmount = 0;
                $paydisbalance = 0;

                foreach($paymentsetup as $pay)
                {
                    $paycount += 1;
                    $paytAmount += $divPay;

                    if($paycount != $paymentno)
                    {
                        $scheditem = db::table('studpayscheddetail')
                            ->insert([
                                'studid' => $studid,
                                'enrollid' => $enrollid,
                                'syid' => $syid,
                                'semid' => $semid,
                                'tuitiondetailid' => $tui->tuitiondetailid,
                                'particulars' => $tui->particulars,
                                'duedate' => $pay->duedate,
                                'paymentno' => $pay->paymentno,
                                'amount' => $divPay,
                                'balance' => $divPay,
                                'classid' => $tui->classid
                            ]);
                    }
                    else
                    {
                        // echo ' payAmount: '. $paytAmount . ' <= ' . $tuitionamount . '; ';
                        if($paytAmount <= $tuitionamount)
                        {
                            $paydisbalance = $tuitionamount - $paytAmount;
                            $paydisbalance = number_format($paydisbalance, 2, '.', '');

                            $divPay += $paydisbalance;
                            
                            // echo ' paydisbalance: ' . $paydisbalance;
                            // echo ' +divPay: '. $divPay;
                            $scheditem = db::table('studpayscheddetail')
                                ->insert([
                                    'studid' => $studid,
                                    'enrollid' => $enrollid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'tuitiondetailid' => $tui->tuitiondetailid,
                                    'particulars' => $tui->particulars,
                                    'duedate' => $pay->duedate,
                                    'paymentno' => $pay->paymentno,
                                    'amount' => $divPay,
                                    'balance' => $divPay,
                                    'classid' => $tui->classid
                                ]);

                        }
                        else
                        {
                            $paydisbalance = $paytAmount - $tuitionamount;
                            $paydisbalance = number_format($paydisbalance, 2, '.', '');


                            // $divPay = number_format($divPay - $paydisbalance);
                            $divPay -= $paydisbalance;
                            // echo ' paydisbalance: ' . $paydisbalance;
                            // echo ' -divPay: '. $divPay;

                            $scheditem = db::table('studpayscheddetail')
                            ->insert([
                                'studid' => $studid,
                                'enrollid' => $enrollid,
                                'syid' => $syid,
                                'semid' => $semid,
                                'tuitiondetailid' => $tui->tuitiondetailid,
                                'particulars' => $tui->particulars,
                                'duedate' => $pay->duedate,
                                'paymentno' => $pay->paymentno,
                                'amount' => $divPay,
                                'balance' => $divPay,
                                'classid' => $tui->classid
                            ]);
                        }
                    }
                }
            }
            else
            {
                $paycount = 0;
                $pAmount = 0;
                $curAmount = $tuitionamount;

                foreach($paymentsetup as $pay)
                {
                    $paycount +=1;
                    if($paycount < count($paymentsetup))
                    {
                        if($curAmount > 0)
                        {
                            $pAmount = round($pay->percentamount * ($tuitionamount/100), 2);
                            $curAmount = (round($curAmount - $pAmount, 2));

                            $scheditem = db::table('studpayscheddetail')
                            ->insert([
                                    'studid' => $studid,
                                    'enrollid' => $enrollid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'tuitiondetailid' => $tui->tuitiondetailid,
                                    'particulars' => $tui->particulars,
                                    'duedate' => $pay->duedate,
                                    'paymentno' => $pay->paymentno,
                                    'amount' => $pAmount,
                                    'balance' => $pAmount,
                                    'classid' => $tui->classid
                                ]);
                        }
                    }
                    else
                    {
                        if($curAmount > 0)
                        {
                            $scheditem = db::table('studpayscheddetail')
                            ->insert([
                                    'studid' => $studid,
                                    'enrollid' => $enrollid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'tuitiondetailid' => $tui->tuitiondetailid,
                                    'particulars' => $tui->particulars,
                                    'duedate' => $pay->duedate,
                                    'paymentno' => $pay->paymentno,
                                    'amount' => $curAmount,
                                    'balance' => $curAmount,
                                    'classid' => $tui->classid
                                ]); 
                            $curAmount = 0;
                        }
                    }
                }
            }
        }
    }

    public static function resetv3_generatebookentries($studid, $levelid, $syid, $semid)
    {
        $be_setup = db::table('bookentrysetup')
            ->join('itemclassification', 'bookentrysetup.classid', '=', 'itemclassification.id')
            ->first();

        $bookentries = db::table('bookentries')
            ->where('studid', $studid)
            ->where('bestatus', 'APPROVED')
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(CashierModel::shssetup() == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->get();

        foreach($bookentries as $be)
        {
            db::table('studledger')
                ->insert([
                    'studid' => $studid,
                    'semid' => $semid,
                    'syid' => $syid,
                    'classid' => $be_setup->classid,
                    'particulars' => $be_setup->description,
                    'amount' => $be->amount,
                    'ornum' => $be->id,
                    'pschemeid' => $be_setup->mopid,
                    'deleted' => 0,
                    'createdby' => auth()->user()->id,    
                    'createddatetime' => $be->createddatetime
                ]);

            db::table('studledgeritemized')
                ->insert([
                    'studid' => $studid,
                    'semid' => $semid,
                    'syid' => $syid,
                    'classificationid' => $be_setup->classid,
                    'itemid' => $be_setup->itemid,
                    'itemamount' => $be->amount,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $be->createddatetime
                ]);

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
                            'semid' => $semid,
                            'syid' => $syid,
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
                            'semid' => $semid,
                            'syid' => $syid,
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
        }


    }

    public static function resetv3_generatepayments($studid, $levelid, $enrollid, $syid, $semid)
    {
        $kind = '';
        $kinddesc = '';
        $ledgeramount = 0;
        $ledgerparticulars = '';

        $chrngtrans = db::table('chrngtrans')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(CashierModel::shssetup() == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            // ->where('ornum', 612362)
            ->where('cancelled', 0)
            ->get();

        foreach($chrngtrans as $trans)
        {
            $transno = $trans->transno;
            $ornum = $trans->ornum;
            $chrngtransid = $trans->id;

            $chrngcashtrans = db::table('chrngcashtrans')
                ->where('transno', $transno)
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->get();    

            foreach($chrngcashtrans as $cashtrans)
            {
                $lineamount = $cashtrans->amount;

                if($cashtrans->kind != 'item')
                {
                    $kind = 0;
                    $ledgeramount += $cashtrans->amount;

                }
                else
                {
                    $kind = 1;
                }

                if($cashtrans->kind != 'item')   
                {
                    $excludeclassid = db::table('chrngtransexclude')
                        ->where('deleted', 0)
                        ->get();

                    $exclassid = array();

                    foreach($excludeclassid as $exclude)
                    {
                        array_push($exclassid, $exclude->classid);
                    }

                    array_push($exclassid, 9);

                    $payscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('classid', $cashtrans->classid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
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
                        ->where('balance', '>', 0)
                        ->get();

                    foreach($payscheddetail as $scheddetail)
                    {
                        if($lineamount > 0)
                        {
                            if($lineamount > $scheddetail->balance)
                            {
                                db::table('studpayscheddetail')
                                    ->where('id', $scheddetail->id)
                                    ->update([
                                        'amountpay' => $scheddetail->amountpay + $scheddetail->balance,
                                        'balance' => 0,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => CashierModel::getServerDateTime(),
                                    ]);

                                FinanceUtilityModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $scheddetail->balance, $scheddetail->classid, $levelid, $trans->id, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);

                                $lineamount -= $scheddetail->balance;
                            }
                            else
                            {
                                db::table('studpayscheddetail')
                                    ->where('id', $scheddetail->id)
                                    ->update([
                                        'amountpay' => $scheddetail->amountpay + $lineamount,
                                        'balance' => $scheddetail->balance - $lineamount,
                                        'updatedby' => auth()->user()->id,
                                        'updateddatetime' => CashierModel::getServerDateTime(),
                                    ]);
                             
                                
                                FinanceUtilityModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $scheddetail->classid, $levelid, $chrngtransid, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);


                                $lineamount = 0;      
                            }
                        }
                    }

                    

                    if($lineamount > 0)
                    {
                        $groupname = '';
                        
                        nextgroup:
                        

                        if($groupname == '')
                        {
                            $groupname = 'OTH';
                        }
                        else
                        {
                            $groupname = 'TUI';
                        }

                        $setupclassid = array();

                        $chrngsetup = db::table('chrngsetup')
                            ->where('deleted', 0)
                            ->where('groupname', $groupname)
                            ->get();


                        foreach($chrngsetup as $setup)
                        {
                            array_push($setupclassid, $setup->classid);
                        }

                        $payscheddetail = db::table('studpayscheddetail')
                            ->where('studid', $studid)
                            ->where('deleted', 0)
                            ->where('syid', $syid)
                            ->whereNotIn('classid', $exclassid)
                            ->where(function($q) use($levelid, $semid){
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
                            ->where(function($q) use($setupclassid){
                                if(count($setupclassid) > 0)
                                {
                                    $q->whereIn('classid', $setupclassid);
                                }
                            })
                            ->where('balance', '>', 0)
                            ->get();

                        foreach($payscheddetail as $scheddetail)
                        {
                            if($lineamount > 0)
                            {
                                if($lineamount > $scheddetail->balance)
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $scheddetail->id)
                                        ->update([
                                            'amountpay' => $scheddetail->amountpay + $scheddetail->balance,
                                            'balance' => 0,
                                            'updatedby' => auth()->user()->id,
                                            'updateddatetime' => CashierModel::getServerDateTime(),
                                        ]);

                                    FinanceUtilityModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $scheddetail->balance, $scheddetail->classid, $levelid, $trans->id, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);

                                    $lineamount -= $scheddetail->balance;


                                }
                                else
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $scheddetail->id)
                                        ->update([
                                            'amountpay' => $scheddetail->amountpay + $lineamount,
                                            'balance' => $scheddetail->balance - $lineamount,
                                            'updatedby' => auth()->user()->id,
                                            'updateddatetime' => CashierModel::getServerDateTime(),
                                        ]);
                                 
                                    
                                    FinanceUtilityModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $scheddetail->classid, $levelid, $chrngtransid, $ornum, $studid, $kind, $syid, $semid, $cashtrans->itemid);


                                    $lineamount = 0;      
                                }
                            }
                        }
                    }

                    if($lineamount > 0)
                    {
                        goto nextgroup;
                    }

                    // return $lineamount;
                }
            }

            $transkind = db::table('chrngcashtrans')
                ->where('transno', $transno)
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->groupBy('kind')
                ->get();

            $ledgerparticulars = '';

            foreach($transkind as $particulars)
            {
                if($particulars->kind != null && $particulars->kind != 'item')
                {
                    if($particulars->kind == 'reg')
                    {
                        $kinddesc = 'REGISTRATION';
                    }
                    elseif($particulars->kind == 'dp')
                    {
                        $kinddesc = 'DOWNPAYMENT';
                    }
                    elseif($particulars->kind == 'misc')
                    {
                        $kinddesc = 'MISCELLANEOUS';   
                    }
                    elseif($particulars->kind == 'tui')
                    {
                        $kinddesc = 'TUITION';   
                    }
                    elseif($particulars->kind == 'oth')
                    {
                        $kinddesc = 'OTHER FEES/BOOKS';   
                    }
                    elseif($particulars->kind == 'old')
                    {
                        $kinddesc = 'OLD ACCOUNT';   
                    }



                    if($ledgerparticulars == '')
                    {
                        $ledgerparticulars = $kinddesc;
                    }
                    else
                    {
                        
                        $ledgerparticulars .= '/' . $kinddesc;
                        
                    }

                    echo 'kind: ' . $ledgerparticulars . '<br>';

                }
            }

            // return 'particulars: ' . $ledgerparticulars;

            $paytype = $trans->paytype;      
            $timenow = date_create($trans->transdate);
            $timenow = date_format($timenow, 'H:i');

            if($ledgerparticulars != '')
            {
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'enrollid' => $enrollid,
                        'semid' => $semid,
                        'syid' => $syid,
                        'particulars' =>'PAYMENT FOR ' . $ledgerparticulars . ' - OR: ' . $ornum . ' - ' . $paytype,
                        'payment' => $ledgeramount,
                        'ornum' => $ornum,
                        'paytype' => $paytype,
                        'transid' => $trans->id,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => $trans->transdate . ' ' . $timenow,
                        'deleted' => 0
                    ]);

                $ledgeramount = 0;
            }

            // return $trans->ornum;

        }        
    }

    public static function procItemized($tuitiondetailid, $payschedid ,$amount, $classid, $levelid, $chrngtransid, $ornum, $studid, $kind, $syid, $semid)
    {
        $setup = db::table('chrngsetup')
            ->where('classid', $classid)
            ->where('deleted', 0)
            ->first();

        if($setup)
        {
            // echo $classid . '<br>';
            if($setup->itemized == 0)
            {

                if($amount > 0)
                {
                    $itemized = db::table('studledgeritemized')   
                        ->where('tuitiondetailid', $tuitiondetailid)
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('syid', $syid)
                        ->whereColumn('totalamount', '<', 'itemamount')
                        ->where('classificationid', $classid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(CashierModel::shssetup() == 0)
                                {
                                    $q->where('semid', $semid);
                                }
                            }
                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->get();

                    if(count($itemized) == 0)
                    {
                        $itemized = db::table('studledgeritemized')   
                            ->where('studid', $studid)
                            ->where('deleted', 0)
                            ->where('syid', $syid)
                            ->whereColumn('totalamount', '<', 'itemamount')
                            ->where('classificationid', $classid)
                            ->where(function($q) use($levelid, $semid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(CashierModel::shssetup() == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', $semid);
                                }
                            })
                            ->get();

                        if(count($itemized) == 0)
                        {
                            $itemized = db::table('studledgeritemized')   
                                ->where('studid', $studid)
                                ->where('deleted', 0)
                                ->whereColumn('totalamount', '<', 'itemamount')
                                ->where('syid', $syid)
                                ->where(function($q) use($levelid, $semid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if(CashierModel::shssetup() == 0)
                                        {
                                            $q->where('semid', $semid);
                                        }
                                    }
                                    if($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                })
                                ->get();   
                        }
                    }

                    // echo 'classid: ' . $classid . '<br>';

                    // return $itemized;

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
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    // 'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $balance,
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'kind' => $kind,
                                    'createddatetime' => CashierModel::getServerDateTime(),
                                    // 'createdby' => auth()->user()->id,
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
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    // 'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $amount,
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'kind' => $kind,
                                    'createddatetime' => CashierModel::getServerDateTime(),
                                    // 'createdby' => auth()->user()->id,
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
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(CashierModel::shssetup() == 0)
                                {
                                    $q->where('semid', $semid);
                                }
                            }
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->whereColumn('totalamount', '<', 'itemamount')
                        ->where('deleted', 0)
                        ->get();

                    if(count($itemized) == 0)
                    { 
                        $itemized = db::table('studledgeritemized')
                            ->where('classificationid', $classid)
                            ->where('studid', $studid)
                            ->where('syid', $syid)
                            ->where(function($q) use($levelid, $semid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(CashierModel::shssetup() == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 20)
                                {
                                    $q->where('semid', $semid);
                                }
                            })
                            ->whereColumn('totalamount', '<', 'itemamount')
                            ->where('deleted', 0)
                            ->get();

                        if(count($itemized) == 0)
                        {
                            $itemized = db::table('studledgeritemized')
                                // ->where('classificationid', $classid)
                                ->where('studid', $studid)
                                ->where('syid', $syid)
                                ->where(function($q) use($levelid, $semid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if(CashierModel::shssetup() == 0)
                                        {
                                            $q->where('semid', $semid);
                                        }
                                    }
                                    if($levelid >= 17 && $levelid <= 20)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                })
                                ->whereColumn('totalamount', '<', 'itemamount')
                                ->where('deleted', 0)
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
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    // 'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $balance,
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'kind' => $kind,
                                    'createddatetime' => CashierModel::getServerDateTime(),
                                    // 'createdby' => auth()->user()->id,
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
                                    // 'updatedby' => auth()->user()->id
                                ]);

                            db::table('chrngtransitems')
                                ->insert([
                                    'chrngtransid' => $chrngtransid,
                                    // 'chrngtransdetailid' => $chrngtransdetailid,
                                    'ornum' => $ornum,
                                    'itemid' => $item->itemid,
                                    'classid' => $classid,
                                    'amount' => $amount,
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $semid,
                                    'kind' => $kind,
                                    'createddatetime' => CashierModel::getServerDateTime(),
                                    // 'createdby' => auth()->user()->id,
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
                    ->where(function($q) use($levelid, $semid){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if(CashierModel::shssetup() == 0)
                            {
                                $q->where('semid', $semid);
                            }
                        }
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', $semid);
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
                                // 'chrngtransdetailid' => $chrngtransdetailid,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'classid' => $classid,
                                'amount' => $balance,
                                'studid' => $studid,
                                'syid' => $syid,
                                'semid' => $semid,
                                'kind' => $kind,
                                'createddatetime' => CashierModel::getServerDateTime(),
                                // 'createdby' => auth()->user()->id,
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
                                // 'updatedby' => auth()->user()->id
                            ]);

                        db::table('chrngtransitems')
                            ->insert([
                                'chrngtransid' => $chrngtransid,
                                // 'chrngtransdetailid' => $chrngtransdetailid,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'classid' => $classid,
                                'amount' => $amount,
                                'studid' => $studid,
                                'syid' => $syid,
                                'semid' => $semid,
                                'kind' => $kind,
                                'createddatetime' => CashierModel::getServerDateTime(),
                                // 'createdby' => auth()->user()->id,
                            ]);

                        $amount = 0;
                    }   
                }

            }

        }
        else
        {
            
        }
    }

    public static function resetv3_generateoldaccounts($studid, $levelid, $syid, $semid)
    {
        
        $balforwardsetup = db::table('balforwardsetup')       
            ->first();

        $balclassid = $balforwardsetup->classid;

        $studledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('classid', $balclassid)
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20 )
                {
                    $q->where('semid', $semid);
                }
            })
            ->get();

        foreach($studledger as $ledger)
        {
            $studpaysched =  db::table('studpayscheddetail')
                ->where('studid', $studid)
                ->where('particulars', $ledger->particulars)
                ->where('syid', $syid)
                ->where('deleted', 0)
                ->where(function($q) use($levelid, $semid){
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

            if(!$studpaysched)
            {
                db::table('studpayscheddetail')
                    ->insert([
                        'studid' => $studid,
                        'semid' => $semid,
                        'syid' => $syid,
                        'classid' => $balclassid,
                        'paymentno' => 1,
                        'particulars' => $ledger->particulars,
                        // 'duedate' => $mop->duedate,
                        'amount' => $ledger->amount,
                        'amountpay' => 0,
                        'balance' => $ledger->amount,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => CashierModel::getServerDateTime()
                    ]);

                db::table('studledgeritemized')
                    ->insert([
                        'studid' => $studid,
                        'semid' => $semid,
                        'syid' => $syid,
                        'classificationid' => $balclassid,
                        'itemamount' => $ledger->amount,
                        'createddatetime' => CashierModel::getServerDateTime()
                    ]);
            }
        }
    }

    public static function resetv3_generateadjustments($studid, $levelid, $syid, $semid)
    {
        $adjustments = db::table('adjustments')
            ->select(db::raw('adjustments.id, classid, amount, description, mop, iscredit, isdebit, syid, semid, adjstatusdatetime'))
            ->join('adjustmentdetails', 'adjustments.id', '=', 'adjustmentdetails.headerid')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('adjustmentdetails.deleted', 0)
            ->where(function($q) use($levelid, $semid){
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
            ->get();

        foreach($adjustments as $adj)
        {
            if($adj->isdebit == 1)
            {
                //--------studLedger------//
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'classid' => $adj->classid,
                        'particulars' => 'ADJ: ' . $adj->description,
                        'amount' => $adj->amount,
                        'ornum' => $adj->id,
                        'pschemeid' => $adj->mop,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => CashierModel::getServerDateTime(),
                        'deleted' => 0,
                    ]);
                //--------studLedger------//

                //-----------------studledgeritemized--------------//
                $checkitemized = db::table('studledgeritemized')
                    ->where('studid', $studid)
                    ->where('classificationid', $adj->classid)
                    ->where('deleted', 0)
                    ->get();

                if(count($checkitemized) > 0)
                {
                    db::table('studledgeritemized')
                        ->where('id', $checkitemized[0]->id)
                        ->update([
                            'itemamount' => floatval($checkitemized[0]->itemamount) + floatval($adj->amount),
                            'updatedby' => auth()->user(),
                            'updateddatetime' => CashierModel::getServerDateTime()
                        ]);
                }
                else
                {
                    db::table('studledgeritemized')
                        ->insert([
                            'studid' => $studid,
                            'semid' => $semid,
                            'syid' => $syid,
                            'classificationid' => $adj->classid,
                            'itemamount' => $adj->amount,
                            'deleted' => 0,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => CashierModel::getServerDateTime()
                        ]);
                }
                //-----------------studledgeritemized--------------//

                //--------------paymentsched--------------//

                $paymentsetup = db::table('paymentsetup')
                    ->select('paymentsetup.id', 'noofpayment', 'paymentno', 'duedate')
                    ->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
                    ->where('paymentsetup.id', $adj->mop)
                    ->where('paymentsetupdetail.deleted', 0)
                    ->get();

                $tAmount = $adj->amount;
                $schedAmount = 0;

                $schedAmount = $adj->amount / intval($paymentsetup[0]->noofpayment);
                $_id = array();

                foreach($paymentsetup as $setup)
                {
                    if($tAmount > 0)
                    {
                        $sched = db::table('studpayscheddetail')
                            ->where('studid', $studid)
                            ->where('classid', $adj->classid)
                            ->where('deleted', 0)
                            ->where('syid', CashierModel::getSYID())
                            ->where(function($q) use($levelid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(CashierModel::shssetup() == 0)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 20)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            })
                            ->whereNotIn('id', $_id)
                            ->take(1)
                            ->get();



                        if(count($sched) > 0)                                   
                        {
                            array_push($_id, $sched[0]->id);
                            // echo 'studpayscheddetail+: ' . $sched[0]->id . '; ';
                            db::table('studpayscheddetail')
                                ->where('id', $sched[0]->id)
                                ->update([
                                    'amount' => $sched[0]->amount + $schedAmount,
                                    'balance' => $sched[0]->balance + $schedAmount,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => CashierModel::getServerDateTime(),

                                ]);
                            $tAmount -= $schedAmount;
                        }
                        else
                        {
                            // echo 'studpayscheddetail-: ' . $sched[0] . '; ';
                            $schedid = db::table('studpayscheddetail')
                                ->insertGetId([
                                    'studid' => $studid,
                                    'semid' => $semid,
                                    'syid' => $syid,
                                    'classid' => $adj->classid,
                                    'particulars' => $adj->description,
                                    'duedate' => $setup->duedate,
                                    'amount' => $schedAmount,
                                    'balance'=> $schedAmount,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => CashierModel::getServerDateTime()
                                ]);

                            $tAmount -= $schedAmount;
                            array_push($_id, $schedid);

                        }

                    }
                }

                //--------------paymentsched--------------//
            }
            else // CREDIT
            {
                $zeroclass = 0;

                $adjdate = date_format(date_create($adj->adjstatusdatetime), 'Y-m-d H:i');
                // return $adjdate;

                //--------studLedger------//
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'classid' => $adj->classid,
                        'particulars' => 'ADJ: ' . $adj->description,
                        'payment' => $adj->amount,
                        'ornum' => $adj->id,
                        'pschemeid' => $adj->mop,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => $adjdate,
                        'deleted' => 0,
                    ]);
                //--------studLedger------//

                //-----------------studledgeritemized--------------//

                $adjamount = $adj->amount;

                $ledgeritemized = db::table('studledgeritemized')
                    ->where('studid', $studid)
                    ->where('classificationid', $adj->classid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid) {
                        if($levelid >= 17 && $levelid <= 20)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->whereColumn('itemamount', '>', 'totalamount')
                    ->where('deleted', 0)
                    ->get();

                echo 'first <br>';

                if(count($ledgeritemized) == 0)
                {
                    $ledgeritemized = db::table('studledgeritemized')
                        ->where('studid', $studid)
                        // ->where('classificationid', $adj->classid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid) {
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->whereColumn('itemamount', '>', 'totalamount')
                        ->where('deleted', 0)
                        ->get();

                    echo 'second <br>';
                }

                // return $ledgeritemized;

                foreach($ledgeritemized as $item)
                {
                    $bal = $item->itemamount - $item->totalamount;

                    if($adjamount > $bal)
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $bal,
                                'updateddatetime' => CashierModel::getServerDateTime()
                            ]);

                        $adjamount -= $bal;
                    }
                    else
                    {
                        db::table('studledgeritemized')
                            ->where('id', $item->id)
                            ->update([
                                'totalamount' => $item->totalamount + $adjamount,
                                'updateddatetime' => CashierModel::getServerDateTime()
                            ]);                        

                        $adjamount = 0;
                    }
                }

                // echo 'adjamount ' . $adjamount . '<br>';

                if($adjamount > 0)
                {
                    $ledgeritemized = db::table('studledgeritemized')
                        ->where('studid', $studid)
                        // ->where('classificationid', $adj->classid)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid) {
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->whereColumn('itemamount', '>', 'totalamount')
                        ->where('deleted', 0)
                        ->get();

                    foreach($ledgeritemized as $item)
                    {
                        $bal = $item->itemamount - $item->totalamount;

                        if($adjamount > $bal)
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $bal,
                                    'updateddatetime' => CashierModel::getServerDateTime()
                                ]);

                            $adjamount -= $bal;
                        }
                        else
                        {
                            db::table('studledgeritemized')
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->totalamount + $adjamount,
                                    'updateddatetime' => CashierModel::getServerDateTime()
                                ]);                        

                            $adjamount = 0;
                        }
                    }

                    echo 'third <br>';


                }

                // echo 'adjamount ' . $adjamount . '<br>';

                //-----------------studledgeritemized--------------//

                //-----------------studpayscheddetail--------------//

                if(db::table('schoolinfo')->first()->dpdist == 1)
                {
                    $payscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('classid', $adj->classid)
                        ->where('deleted', 0)
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid >= 17 && $levelid <= 20)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->get();

                    // return $payscheddetail;

                    $paycount = count($payscheddetail);

                    $divamount = number_format($adj->amount / $paycount, 2, '.', '');
                    $d_adjamount = $adj->amount;
                    $counter = 0;

                    // return $payscheddetail;

                    foreach($payscheddetail as $detail)
                    {
                        $counter += 1;

                        if($counter != $paycount)
                        {
                            db::table('studpayscheddetail')
                                ->where('id', $detail->id)
                                ->update([
                                    'amountpay' => $detail->amountpay + $divamount,
                                    'balance' => $detail->balance - $divamount,
                                    'updateddatetime' => CashierModel::getServerDateTime()
                                ]);
                        }
                        else
                        {
                            db::table('studpayscheddetail')
                                ->where('id', $detail->id)
                                ->update([
                                    'amountpay' => $detail->amountpay + $d_adjamount,
                                    'balance' => $detail->balance - $d_adjamount,
                                    'updateddatetime' => CashierModel::getServerDateTime()
                                ]);   
                        }

                        $d_adjamount -= $divamount;
                    }

                }
                else
                {
                    $adjamount = $adj->amount;

                    // return $adj->classid;

                    $paysched = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('classid', $adj->classid)
                        ->where('syid', $syid)
                        ->where(function($q) use($semid, $levelid){
                            if($levelid == 14 || $levelid = 15)
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
                        ->where('deleted', 0)
                        ->where('balance', '>', 0)
                        ->get();

                    if(count($paysched) == 0)
                    {
                        $paysched = db::table('studpayscheddetail')
                            ->where('studid', $studid)
                            // ->where('classid', $adj->classid)
                            ->where('syid', $syid)
                            ->where(function($q) use($semid, $levelid){
                                if($levelid == 14 || $levelid = 15)
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
                            ->where('deleted', 0)
                            ->where('balance', '>', 0)
                            ->get();
                    }

                    foreach($paysched as $sched)
                    {
                        if($adjamount > 0)
                        {
                            if($adjamount > $sched->balance)
                            {
                                db::table('studpayscheddetail')
                                    ->where('id', $sched->id)
                                    ->update([
                                        'amountpay' => $sched->balance + $sched->amountpay,
                                        'balance' => 0,
                                        'updateddatetime' => CashierModel::getServerDateTime()
                                    ]);

                                $adjamount -= $sched->balance;
                            }
                            else
                            {
                                db::table('studpayscheddetail')
                                    ->where('id', $sched->id)
                                    ->update([
                                        'amountpay' => $sched->amountpay + $adjamount,
                                        'balance' => $sched->balance - $adjamount,
                                        'updateddatetime' => CashierModel::getServerDateTime()
                                    ]);

                                $adjamount = 0;
                            }
                        }
                    }

                    echo 'adjamount ' . $adjamount . '<br>';

                    if($adjamount > 0)
                    {
                        $paysched = db::table('studpayscheddetail')
                            ->where('studid', $studid)
                            // ->where('classid', $adj->classid)
                            ->where('syid', $syid)
                            ->where(function($q) use($semid, $levelid){
                                if($levelid == 14 || $levelid = 15)
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
                            ->where('deleted', 0)
                            ->where('balance', '>', 0)
                            ->get();
                        foreach($paysched as $sched)
                        {
                            if($adjamount > 0)
                            {
                                if($adjamount > $sched->balance)
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $sched->id)
                                        ->update([
                                            'amountpay' => $sched->balance + $sched->amountpay,
                                            'balance' => 0,
                                            'updateddatetime' => CashierModel::getServerDateTime()
                                        ]);

                                    $adjamount -= $sched->balance;
                                }
                                else
                                {
                                    db::table('studpayscheddetail')
                                        ->where('id', $sched->id)
                                        ->update([
                                            'amountpay' => $sched->amountpay + $adjamount,
                                            'balance' => $sched->balance - $adjamount,
                                            'updateddatetime' => CashierModel::getServerDateTime()
                                        ]);

                                    $adjamount = 0;
                                }
                            }
                        }
                    }

                    echo 'adjamount ' . $adjamount . '<br>';
                    
                }
                //-----------------studpayscheddetail--------------//
            }
        }        
    }

    public static function resetv3_generatediscounts($studid, $levelid, $syid, $semid)
    {
        $discamount = 0;
        $particulars = 'DISCOUNT: ';

        $studdiscount = db::table('studdiscounts')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('posted', 1)
            ->get();

        foreach($studdiscount as $discount)
        {
            $discinfo = db::table('discounts')
                ->where('id', $discount->discountid)
                ->first();

            if($discinfo)
            {
                $particulars = 'DISCOUNT: ' . $discinfo->particulars;

                $studledger = db::table('studledger')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function($q) use($levelid, $semid){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if(CashierModel::shssetup() == 0)
                            {
                                $q->where('semid', $semid);
                            }
                        }
                        if($levelid >= 17 && $levelid <= 20)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('classid', $discount->classid)
                    ->where('deleted', 0)
                    ->first();

                if($studledger)
                {
                    $ledgeramount = $studledger->amount;

                    if($discinfo->percent == 0)
                    {
                        $discamount = $discinfo->amount;
                    }
                    else
                    {
                        $discamount = ($discinfo->amount/100) * $ledgeramount;
                    }
                }
            }

            //studledger
            db::table('studledger')
                ->insert([
                    'studid' => $studid,
                    'syid' => $syid,
                    'semid' => $semid,
                    'particulars' =>'DISCOUNT: ' . $discinfo->particulars,
                    'payment' => $discamount,
                    'ornum' => $discount->id,
                    'deleted' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => $discount->createddatetime
                ]);

            $studledgeritemized = db::table('studledgeritemized')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where(function($q) use($levelid, $semid){
                    if($levelid == 14 || $levelid == 15)
                    {
                        if(CashierModel::shssetup() == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    }
                    if($levelid >= 17 && $levelid <= 20)
                    {
                        $q->where('semid', $semid);
                    }
                })
                ->where('deleted', 0)
                ->where('classificationid', $discount->classid)
                ->get();

            foreach($studledgeritemized as $itemized)
            {
                $itemamount = $discamount;
                // return $discamount;

                if($itemized->totalamount == null || $itemized->totalamount == 0)
                {   
                    if($itemamount > $itemized->itemamount)
                    {
                        db::table('studledgeritemized')
                            ->where('id', $itemized->id)
                            ->update([
                                'totalamount' => $itemized->itemamount,
                                'updateddatetime' => CashierModel::getServerDateTime()
                            ]);

                        $itemamount -= $itemized->amount;
                    }
                    else
                    {
                        db::table('studledgeritemized')
                            ->where('id', $itemized->id)
                            ->update([
                                'totalamount' => $itemamount,
                                'updateddatetime' => CashierModel::getServerDateTime()
                            ]);

                        $itemamount = 0;
                    }

                }
                else
                {

                    $balance = $itemized->itemamount - $itemized->totalamount;

                    if($itemamount > $balance)
                    {
                        db::table('studledgeritemized')
                            ->where('id', $itemized->id)
                            ->update([
                                'totalamount' => $itemized->itemamount + $balance,
                                'updateddatetime' => CashierModel::getServerDateTime()
                            ]);

                        $itemamount -= $balance;
                    }
                    else
                    {
                        db::table('studledgeritemized')
                            ->where('id', $itemized->id)
                            ->update([
                                'totalamount' => $itemamount + $itemized->itemamount,
                                'updateddatetime' => CashierModel::getServerDateTime()
                            ]);

                        $itemamount = 0;
                    }                    
                }
            }

            if(db::table('schoolinfo')->first()->dpdist == 1)
            {
                FinanceUtilityModel::discount_detaildist1($studid, $syid, $semid, $levelid, $discamount, $discount->classid);
            }
            else
            {
                FinanceUtilityModel::discount_detaildist0($studid, $syid, $semid, $levelid, $discamount, $discount->classid);
            }

            // echo 'over: ' . $over . '<br>';

            $rep = 0;

            // if($over > 0)
            // {
            //     rep:
            //     $scheddetail = db::table('studpayscheddetail')
            //         ->where('studid', $studid)
            //         ->where('syid', $syid)
            //         ->where(function($q) use($levelid, $semid){
            //             if($levelid == 14 || $levelid == 15)
            //             {
            //                 if(CashierModel::shssetup() == 0)
            //                 {
            //                     $q->where('semid', $semid);
            //                 }
            //             }
            //             if($levelid >= 17 && $levelid <= 20)
            //             {
            //                 $q->where('semid', $semid);
            //             }
            //         })
            //         ->where('deleted', 0)
            //         ->where('classid', $discount->classid)
            //         ->where('balance', '>', 0)
            //         ->get();

            //     foreach($scheddetail as $detail)
            //     {
            //         if($over > 0)
            //         {
            //             if($over > $detail->balance)
            //             {
            //                 db::table('studpayscheddetail')
            //                     ->where('id', $detail->id)
            //                     ->update([
            //                         'amountpay' => $detail->amountpay + $detail->balance,
            //                         'balance' => 0,
            //                         'updateddatetime' => CashierModel::getServerDateTime()
            //                     ]); 

            //                 $over -= $detail->balance;
            //             }
            //             else
            //             {
            //                 db::table('studpayscheddetail')
            //                     ->where('id', $detail->id)
            //                     ->update([
            //                         'amountpay' => $detail->amountpay + $over,
            //                         'balance' => $detail->balance - $over,
            //                         'updateddatetime' => CashierModel::getServerDateTime()
            //                     ]); 

            //                 $over = 0;
            //             }
            //         }
            //     }



            //     if($over > 0)
            //     {
            //         $rep += 1;

            //         if($rep < 3)
            //         {
            //             goto rep;
            //         }
            //     }
            // }
        }   
    }

    public static function discount_detaildist1($studid, $syid, $semid, $levelid, $discamount, $discountclassid)
    {
        //studpayscheddetail
        $count = db::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(CashierModel::shssetup() == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->where('deleted', 0)
            ->where('classid', $discountclassid)
            ->count();

        $divamount = number_format($discamount/$count, 2, '.', '');
        $divtotal = 0;
        $counter = 1;
        $over = 0;

        $scheddetail = db::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(CashierModel::shssetup() == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->where('deleted', 0)
            ->where('classid', $discountclassid)
            // ->where('balance', '>', 0)
            ->get();

        foreach($scheddetail as $detail)
        {   
            if($counter < $count)
            {
                if($divamount > $detail->balance)
                {
                    db::table('studpayscheddetail')
                        ->where('id', $detail->id)
                        ->update([
                            'amountpay' => $detail->amountpay + $detail->balance,
                            'balance' => 0,
                            'updateddatetime' => CashierModel::getServerDateTime()
                        ]);
                    $divtotal += $detail->balance;
                    $over += $divamount - $detail->balance;
                }
                else
                {
                    db::table('studpayscheddetail')
                        ->where('id', $detail->id)
                        ->update([
                            'amountpay' => $detail->amountpay + $divamount,
                            'balance' => $detail->balance - $divamount,
                            'updateddatetime' => CashierModel::getServerDateTime()
                        ]);
                    $divtotal += $divamount;
                }

                
                $counter +=1;
            }
            else
            {
                $damount = 0;

                if($divtotal > $discamount)
                {
                    $damount = $divtotal-$discamount - $over;
                }
                else
                {
                    $damount = $discamount - $divtotal - $over;   
                }

                if($damount > $detail->balance)
                {
                    db::table('studpayscheddetail')
                        ->where('id', $detail->id)
                        ->update([
                            'amountpay' => $detail->amountpay + $detail->balance,
                            'balance' => 0,
                            'updateddatetime' => CashierModel::getServerDateTime()
                        ]); 

                    // echo 'over: ' . $over . ' damount: ' . $damount . ' - detail: ' . $detail->balance . '<br>';
                }
                else
                {
                    db::table('studpayscheddetail')
                        ->where('id', $detail->id)
                        ->update([
                            'amountpay' => $detail->amountpay + $damount,
                            'balance' => $detail->balance - $damount,
                            'updateddatetime' => CashierModel::getServerDateTime()
                        ]); 
                }
            }
        }
    }

    public static function discount_detaildist0($studid, $syid, $semid, $levelid, $discamount, $discountclassid)
    {  

        $payschedDetail = db::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(CashierModel::shssetup() == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 20)
                {
                    $q->where('semid', $semid);
                }
            })
            ->where('balance', '>', 0)
            ->where('deleted', 0)
            ->where('classid', $discountclassid)
            ->get();

        $_over = 0;

        $totalpay = 0;
        if(count($payschedDetail) > 0)
        {
            // $calcAmount = $tAmount / count($payschedDetail);
            $_over = $discamount;

            foreach($payschedDetail as $paysched)
            {
                $payInfo = db::table('studpayscheddetail')
                    ->where('id', $paysched->id)
                    ->first();

                $_bal = $paysched->balance;

                if($_bal <= $_over)
                {

                    $totalpay = $payInfo->amount;

                    $_over -= $payInfo->amount - $payInfo->amountpay;

                    $updpaySched = db::table('studpayscheddetail')
                            ->where('id', $paysched->id)
                            ->update([
                                'amountpay' => $totalpay,
                                'balance' => 0,
                                'updateddatetime' => CashierModel::getServerDateTime(),
                                'updatedby' => auth()->user()->id
                            ]);
                    

                }
                else
                {
                    if($_over > 0)
                    {
                        $totalpay = $payInfo->amountpay + $_over;

                        $updpaySched = db::table('studpayscheddetail')
                                ->where('id', $paysched->id)
                                ->update([
                                    'amountpay' => $totalpay,
                                    'balance' => $payInfo->amount - $totalpay,
                                    'updateddatetime' => CashierModel::getServerDateTime(),
                                    'updatedby' => auth()->user()->id
                                ]);

                        $_over = 0;
                    }
                }
                
            }
        }

    }

    public static function ucon_maketransitems($ornum, $tamount)
    {
        start:
        $transitems = db::table('chrngtransitems')
            ->where('ornum', $ornum)
            ->where('classid', 3)
            ->where('deleted', 0)
            ->get();

        if(count($transitems) > 0)
        {
            foreach($transitems as $item)
            {
                
                // $ucon = db::table('consolidated_oth')
                //     ->where('ornum', $ornum)
                //     ->where('itemid', $item->itemid)
                //     ->where('deleted', 0)
                //     ->count();

                // echo 'ornum: ' . $ornum . ' - ' . $tamount . '<br>';

                if($tamount > 0)
                {
                    if($item->amount > $tamount)    
                    {
                        db::table('consolidated_oth')
                            ->insert([
                                'transdate' => $item->createddatetime,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'amount' => $tamount,
                                'classid' => $item->classid,
                                'userid' => auth()->user()->id
                            ]);

                        $tamount = 0;
                    }
                    else
                    {
                        db::table('consolidated_oth')
                            ->insert([
                                'transdate' => $item->createddatetime,
                                'ornum' => $ornum,
                                'itemid' => $item->itemid,
                                'amount' => $item->amount,
                                'classid' => $item->classid,
                                'userid' => auth()->user()->id
                            ]);

                        $tamount -= $item->amount;
                    }
                }
            }



            if($tamount > 0)
            {
                goto start;
            }
        }
        else
        {
            $syid = 0;
            $semid = 0;
            $trx = db::table('chrngtrans')
                ->where('ornum', $ornum)
                ->where('cancelled', 0)
                ->first();

            if($trx)
            {
                $syid= $trx->syid;
                $semid = $trx->semid;

                $stud = db::table('studinfo')
                    ->select('levelid')
                    ->where('id', $trx->studid)
                    ->first();

                $tuitions = db::table('tuitionheader')
                    ->select('itemid', 'tuitionitems.amount', 'classificationid')
                    ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                    ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                    ->where('levelid', $stud->levelid)
                    ->where('syid', $syid)
                    ->where(function($q) use($stud, $semid){
                        if($stud->levelid == 14 || $stud->levelid == 15)
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', $semid);
                            }
                        }
                        if($stud->levelid >= 17 && $stud->levelid <= 20)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('tuitiondetail.deleted', 0)
                    ->where('tuitionheader.deleted', 0)
                    ->where('tuitionitems.deleted', 0)
                    ->where('classificationid', 3)
                    ->groupBy('itemid')
                    ->get();

                $countin = 0;

                foreach($tuitions as $tui)
                {
                    if($tui->amount == $tamount)
                    {
                        db::table('consolidated_oth')
                            ->insert([
                                'transdate' => $trx->transdate,
                                'ornum' => $ornum,
                                'itemid' => $tui->itemid,
                                'amount' => $tui->amount,
                                'classid' => $tui->classificationid,
                                'userid' => auth()->user()->id
                            ]);

                        $countin += 1;
                    }
                }

                // return $countin;

                if($countin == 0)
                {
                    foreach($tuitions as $tui)
                    {
                        if($tamount > 0)
                        {
                            if($tui->amount >= $tamount)
                            {
                                db::table('consolidated_oth')
                                ->insert([
                                    'transdate' => $trx->transdate,
                                    'ornum' => $ornum,
                                    'itemid' => $tui->itemid,
                                    'amount' => $tamount,
                                    'classid' => $tui->classificationid,
                                    'userid' => auth()->user()->id
                                ]);

                                $tamount = 0;
                            }
                            else
                            {
                                db::table('consolidated_oth')
                                ->insert([
                                    'transdate' => $trx->transdate,
                                    'ornum' => $ornum,
                                    'itemid' => $tui->itemid,
                                    'amount' => $tui->amount,
                                    'classid' => $tui->classificationid,
                                    'userid' => auth()->user()->id
                                ]);

                                $tamount -= $tui->amount;
                            }
                        }
                    }
                }
            }
        }
    }
}
