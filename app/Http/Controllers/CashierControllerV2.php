<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\CashierModel;
use App\DisplayModel;
use App\Models\Finance\FinanceUtilityModel;
use DB;
use NumConvert;
use PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use TCPDF;


class CashierControllerV2 extends Controller
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
        return view('cashv2');
    }

    public function studlist(Request $request)
    {
        if($request->ajax())
        {
            $query = $request->get('query');

            $student = db::table('studinfo')
                ->select(db::raw('studinfo.id, sid, lrn, levelid, CONCAT(lastname, ", ", firstname) AS fullname, rfid'))
                ->where('studinfo.deleted', 0)
                // ->where('studinfo.id', 49)
                ->orderby('lastname', 'asc')
                ->orderby('firstname', 'asc')
                ->having('fullname', 'like', '%'.$query.'%')
                ->orHaving('rfid', $query)
                ->orHaving('sid', 'like', '%' .$query. '%')
                ->limit(300)
                ->get();

            // return $student;

            $output = '';

            $esc = '';
            $strand = '';
            $_status = 0;
            $levelname = '';
            $sectionname = '';
      
            foreach($student as $stud)
            {
                $enrolledstud = '';
                $colorstat = '';

                $es = db::table('enrolledstud')
                    ->select('id', 'studstatus', 'levelid', 'sectionid')
                    ->where('deleted', 0)
                    ->where('syid', CashierModel::getSYID())
                    ->where('studid', $stud->id)
                    ->first();

                if($es)
                {
                    if($es->studstatus == 1)
                    {
                      $colorstat = 'text-success';
                    }
                    elseif($es->studstatus == 2)
                    {
                      $colorstat = 'text-primary';
                    }
                    elseif($es->studstatus == 3)
                    {
                      $colorstat = 'text-danger';
                    }
                    elseif($es->studstatus == 4)
                    {
                      $colorstat = 'text-warning';
                    }
                    elseif($es->studstatus == 5)
                    {
                      $colorstat = 'text-secondary';
                    }
                    elseif($es->studstatus == 6)
                    {
                      $colorstat = 'text-orange';
                    }
                    else
                    {
                        $colorstat = 'text-orange';   
                    }

                    $_status = $es->studstatus;
                    $levelname = db::table('gradelevel')->where('id', $es->levelid)->first()->levelname;
                    $section = db::table('sections')
                        ->where('id', $es->sectionid)
                        ->first();
                    if($section)
                    {
                        $sectionname = $section->sectionname;
                    }
                }
                else
                {
                    $es = db::table('sh_enrolledstud')
                        ->select('id', 'studstatus', 'levelid', 'sectionid', 'strandid')
                        ->where('deleted', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where(function($q){
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->where('studid', $stud->id)
                        ->first();

                    if($es)
                    {
                        if($es->studstatus == 1)
                        {
                          $colorstat = 'text-success';
                        }
                        elseif($es->studstatus == 2)
                        {
                          $colorstat = 'text-primary';
                        }
                        elseif($es->studstatus == 3)
                        {
                          $colorstat = 'text-danger';
                        }
                        elseif($es->studstatus == 4)
                        {
                          $colorstat = 'text-warning';
                        }
                        elseif($es->studstatus == 5)
                        {
                          $colorstat = 'text-secondary';
                        }
                        elseif($es->studstatus == 6)
                        {
                          $colorstat = 'text-orange';
                        }
                        else
                        {
                            $colorstat = 'text-orange';   
                        }

                        $_status = $es->studstatus;
                        $levelname = db::table('gradelevel')->where('id', $es->levelid)->first()->levelname;
                        $section = db::table('sections')
                            ->where('id', $es->sectionid)
                            ->first();
                        if($section)
                        {
                            $sectionname = $section->sectionname;
                        }
                    }
                    else
                    {
                        $es = db::table('college_enrolledstud')
                            ->select('id', 'studstatus', 'yearlevel as levelid', 'sectionid', 'courseid')
                            ->where('deleted', 0)
                            ->where('syid', CashierModel::getSYID())
                            ->where('semid', CashierModel::getSemID())
                            ->where('studid', $stud->id)
                            ->first();

                        if($es)
                        {
                            if($es->studstatus == 1)
                            {
                              $colorstat = 'text-success';
                            }
                            elseif($es->studstatus == 2)
                            {
                              $colorstat = 'text-primary';
                            }
                            elseif($es->studstatus == 3)
                            {
                              $colorstat = 'text-danger';
                            }
                            elseif($es->studstatus == 4)
                            {
                              $colorstat = 'text-warning';
                            }
                            elseif($es->studstatus == 5)
                            {
                              $colorstat = 'text-secondary';
                            }
                            elseif($es->studstatus == 6)
                            {
                              $colorstat = 'text-orange';
                            }
                            else
                            {
                                $colorstat = 'text-orange';   
                            }

                            $_status = $es->studstatus;
                            $levelname = db::table('gradelevel')->where('id', $es->levelid)->first()->levelname;
                            $section = db::table('college_sections')
                                ->where('id', $es->sectionid)
                                ->first();
                            if($section)
                            {
                                $sectionname = $section->sectionDesc;
                            }
                        }
                        else
                        {
                            $_status = 0;
                            $colorstat = '';
                            $levelname = db::table('gradelevel')->where('id', $stud->levelid)->first()->levelname;
                            $sectionname = '';
                        }
                    }
                }


                
                $output .= '
                    <tr class="client-line '.$colorstat.'" data-id="'.$stud->id.'" data-name="'.strtoupper($stud->fullname).'" data-level="'.$levelname.'" data-status="'.$_status.'" data-toggle="tooltip" title="'.$levelname.' | '.$sectionname.'">
                        <td> '. $stud->sid . ' - ' .strtoupper($stud->fullname).'</td>
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

    public function v2_studhistory(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $list = '';

            $enrolledstud = db::table('enrolledstud')
                ->select(db::raw('sy.`sydesc`, levelname, sections.`sectionname`, teacher.`lastname`, teacher.`firstname`, teacher.`middlename`, grantee.description as grantee'))
                ->join('sy', 'enrolledstud.syid', '=', 'sy.id')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                ->join('teacher', 'enrolledstud.teacherid', '=', 'teacher.id')
                ->join('studinfo', 'enrolledstud.studid', 'studinfo.id')
                ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
                ->where('studid', $studid)
                ->where('enrolledstud.deleted', 0)
                ->get();


            foreach($enrolledstud as $enroll)
            {
                $teachername = $enroll->lastname . ', ' . $enroll->lastname . ' ' . $enroll->middlename; 

                $list .='
                    <tr>
                        <td>'.$enroll->sydesc.'</td>
                        <td>'.$enroll->levelname.' - '.$enroll->sectionname.' | '. $enroll->grantee .'</td>
                    </tr>
                ';
            }

            $enrolledstud = db::table('sh_enrolledstud')
                ->select(db::raw('sy.`sydesc`, levelname, sections.`sectionname`, teacher.`lastname`, teacher.`firstname`, teacher.`middlename`, grantee.description as grantee'))
                ->join('studinfo', 'sh_enrolledstud.studid', 'studinfo.id')
                ->join('sy', 'sh_enrolledstud.syid', '=', 'sy.id')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                ->join('teacher', 'sh_enrolledstud.teacherid', '=', 'teacher.id')
                ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
                ->where('studid', $studid)
                ->where('sh_enrolledstud.deleted', 0)
                ->get();

            foreach($enrolledstud as $enroll)
            {
                $teachername = $enroll->lastname . ', ' . $enroll->lastname . ' ' . $enroll->middlename; 

                $list .='
                    <tr>
                        <td>'.$enroll->sydesc.'</td>
                        <td>'.$enroll->levelname.' - '.$enroll->sectionname.' | '. $enroll->grantee .'</td>
                    </tr>
                ';
            }

            $data = array(
                'list' => $list
            );

            echo json_encode($data);
        }
    }

    // public function v2_payinfo(Request $request)
    // {
    //     if($request->ajax())
    //     {
    //         $studid = $request->get('studid');
    //         $tnum = CashierModel::getTransNo();

    //         $studinfo = db::table('studinfo')
    //             ->where('id', $studid)
    //             ->first();

    //         $levelid = $studinfo->levelid;
    //         $feesid = $studinfo->feesid;

    //         $gradelevel = db::table('gradelevel')
    //             ->where('id', $levelid)
    //             ->first();

    //         $acadprogid = 0;

    //         if($gradelevel)
    //         {
    //             $acadprogid = $gradelevel->acadprogid;
    //         }

    //         $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
    //         $course = '';
    //         $strand = '';

    //         if($studinfo->courseid > 0)
    //         {
    //             $college_courses = db::table('college_courses')
    //                 ->where('id', $studinfo->courseid)
    //                 ->first();

    //             if($college_courses)
    //             {
    //                 $course = ' | ' . $college_courses->courseabrv;
    //             }
    //             else
    //             {
    //                 $course = '';
    //             }
    //         }

    //         if($studinfo->strandid > 0 || $studinfo->strandid != null)
    //         {
    //             $sh_strand = db::table('sh_strand')
    //                 ->where('id', $studinfo->strandid)
    //                 ->first();

    //             if($sh_strand)
    //             {
    //                 $strand = ' | ' .$sh_strand->strandcode;
    //             }
    //             else
    //             {
    //                 $strand = '';
    //             }
    //         }
            
    //         $grantees = db::table('grantee')
    //             ->where('id', $studinfo->grantee)
    //             ->first();

    //         $grantee = '';

    //         if($grantee)
    //         {
    //             $grantee = $grantees->description;
    //         }

    //         $studstatus = db::table('studentstatus')->where('id', $studinfo->studstatus)->first()->description;

    //         $name = $studinfo->lastname . ', ' . $studinfo->firstname . ' ' . $studinfo->middlename . ' ' . $studinfo->suffix;
    //         $info = '
    //             <tr>
    //                 <td>'.$levelname.' - '.$studinfo->sectionname.' | '.$grantee.'</td>
    //                 <td>'.$studstatus.'</td>
    //             </td>
    //         ';


    //         $miscitemized = 0;

    //         $reg = '';
    //         $tui = '';
    //         $misc = '';
    //         $oth = '';
    //         $old = '';

    //         $regcount = 0;
    //         $tuicount = 0;
    //         $misccount = 0;
    //         $othtotal = 0;
    //         $oldtotal = 0;

    //         $regtotal = 0;
    //         $tuitotal = 0;
    //         $misctotal = 0;
    //         $othtotal = 0;
    //         $oldtotal = 0;
    //         $gtotal = 0;

    //         $grandtotal = 0;

    //         $dDate = '';
    //         $monthdue = '';
    //         $monthno = '';
    //         $duecount = 0;
    //         $duedesc = '';
    //         $regremaining = 0;
    //         $_regbal = 0;

    //         $citem = '';

    //         $_div = array();

    //         $_addonfromreg = 0;
    //         $tb_enroll ='';

    //         if($levelid == 14 || $levelid == 15)
    //         {
    //             $tb_enroll = 'sh_enrolledstud';
    //         }
    //         elseif($levelid >= 17 && $levelid <= 21)
    //         {
    //             $tb_enroll = 'college_enrolledstud';
    //         }
    //         else
    //         {
    //             $tb_enroll = 'enrolledstud';
    //         }

    //         $checkenroll = db::table($tb_enroll)
    //             ->where('studid', $studid)
    //             ->where('syid', CashierModel::getSYID())
    //             ->where('deleted', 0)
    //             ->where('studstatus', '>', 0)
    //             ->where(function($q) use($levelid){
    //                 if($levelid == 14 || $levelid == 15)
    //                 {
    //                     if(db::table('schoolinfo')->first()->shssetup == 0)
    //                     {
    //                         $q->where('semid', CashierModel::getSemID());
    //                     }
    //                 }
    //                 if($levelid >= 17 && $levelid <= 21)
    //                 {
    //                     $q->where('semid', CashierModel::getSemID());
    //                 }
    //             })
    //             ->first();

    //         if(!$checkenroll)
    //         {
    //             if(db::table('schoolinfo')->first()->snr == 'dcc')
    //             {
    //                 $dp = db::table('dpsetup')
    //                     ->select(db::raw('*, sum(amount) as totalamount'))
    //                     ->where('levelid', $levelid)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where(function($q) use($levelid){
    //                         if(CashierModel::shssetup() == 0)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->where('deleted', 0)
    //                     // ->groupBy('classid')
    //                     ->first();

    //                 if($dp)
    //                 {
    //                     $reg .= '
    //                         <tr data-id="0" class-id="'.$dp->classid.'"  data-kind="dp">
    //                             <td class="paydesc"><span class="">DOWNPAYMENT</span></td>
    //                             <td class="text-right payval" data-value="'.$dp->totalamount.'">'.number_format($dp->totalamount, 2).'</td>
    //                         </tr>
    //                     ';        
    //                 }

    //                 $regtotal = $dp->totalamount;
    //             }
    //             else
    //             {
    //                 $_dp = db::table('dpsetup')
    //                     ->select(db::raw('dpsetup.*, items.`description`'))
    //                     ->join('items', 'dpsetup.itemid', '=', 'items.id')
    //                     ->where('levelid', $levelid)
    //                     ->where('dpsetup.deleted', 0)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where(function($q) use($levelid){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             if(db::table('schoolinfo')->first()->shssetup == 0)
    //                             {
    //                                 $q->where('semid', CashierModel::getSemID());
    //                             }
    //                         }
    //                         if($levelid >= 17 && $levelid <= 21)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->get();

    //                 foreach($_dp as $dp)
    //                 {
    //                     $reg .= '
    //                         <tr data-id="'.$dp->id.'" class-id="'.$dp->classid.'"  data-kind="dp">
    //                             <td class="paydesc"><span class="">'.$dp->description.'</span></td>
    //                             <td class="text-right payval" data-value="'.$dp->amount.'">'.number_format($dp->amount, 2).'</td>
    //                         </tr>
    //                     ';        

    //                     $regtotal += $dp->amount;
    //                 }

    //                 $balforwardclassid = db::table('balforwardsetup')
    //                     ->first()->classid;

    //                 $balforwarditems = db::table('studpayscheddetail')
    //                     // ->select('aaa')
    //                     ->where('studid', $studid)
    //                     ->where('deleted', 0)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where('classid', $balforwardclassid)
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where(function($q) use($levelid){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }

    //                         if($levelid >= 17 && $levelid <= 21)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->get();

    //                 if(count($balforwarditems) > 0)
    //                 {
    //                     foreach($balforwarditems as $oldacc)
    //                     {
    //                         $oldbal = $oldacc->balance;

    //                         if($oldbal < 0)
    //                         {
    //                             $oldbal = 0;
    //                         }

    //                         if($oldacc->duedate == null || $oldacc->duedate == '')
    //                         {
    //                             $monthdue = '';
    //                         }
    //                         else
    //                         {
    //                             $dDate = date_create($oldacc->duedate);
    //                             $monthdue = strtoupper(date_format($dDate, 'F'));
    //                             $monthno = date_format($dDate, 'n');
    //                         }

    //                         $old .= '
    //                             <tr data-id="'.$oldacc->id.'" data-kind="old">
    //                                 <td class="paydesc">'.$oldacc->particulars.' <span class="text-bold">'.$monthdue.'</span></td>
    //                                 <td class="text-right payval" data-value="'.$oldbal.'">'.number_format($oldbal, 2).'</td>
    //                             </tr>
    //                         ';

    //                         $oldtotal += $oldbal;
    //                         $gtotal += $oldbal;
    //                     }
    //                 }

    //                 $studpayscheddetail = db::table('studpayscheddetail')
    //                     ->select(db::raw('studpayscheddetail.*, itemized, groupname, sum(balance) as tbalance'))
    //                     ->leftjoin('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
    //                     ->where('studid', $studid)
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where('groupname', 'OTH')
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where(function($q) use($levelid){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }

    //                         if($levelid >= 17 && $levelid <= 21)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->groupBy('classid')
    //                     ->get();    

    //                 $linecounter = 0;
                    
    //                 foreach($studpayscheddetail as $paysched)
    //                 {
    //                     $othbal = $paysched->tbalance;


    //                     if($othbal < 0)
    //                     {
    //                         $othbal = 0;
    //                     }

    //                     $oth .= '
    //                             <tr data-id="'.$paysched->id.'" data-kind="oth">
    //                                 <td class="paydesc">'.$paysched->particulars.'</span></td>
    //                                 <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
    //                             </tr>
    //                         ';

    //                     $othtotal += $othbal;
    //                     $gtotal += $othbal;
    //                 }

    //                 if(db::table('schoolinfo')->first()->snr == 'holycross')
    //                 {
    //                     foreach(DisplayModel::ne_oth($levelid) as $tdetail)
    //                     {
    //                         $othbal = $tdetail->amount;
    //                         $oth .= '
    //                             <tr data-id="'.$tdetail->id.'" data-kind="addon">
    //                                 <td class="paydesc">'.$tdetail->description.'</span></td>
    //                                 <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
    //                             </tr>
    //                         ';

    //                         $othtotal += $othbal;
    //                         $gtotal += $othbal;
    //                     }
    //                 }

                    
    //             }
    //         }
    //         else
    //         {
    //             if(db::table('schoolinfo')->first()->snr == 'dcc')
    //             {
    //                 $othclassid = array();
    //                 $oldclassid = array();
    //                 $othcount = 0;
    //                 $oldcount = 0;

    //                 $chrngsetup = db::table('chrngsetup')
    //                     ->where('deleted', 0)
    //                     ->where('groupname', 'OTH')
    //                     ->get();

    //                 // return $chrngsetup;

    //                 foreach($chrngsetup as $setup)
    //                 {   
    //                     array_push($othclassid, $setup->classid);
    //                     $othcount = count($chrngsetup);
    //                 }

    //                 $balforwardsetup = db::table('balforwardsetup')
    //                     ->first();

    //                 if($balforwardsetup)
    //                 {
    //                     $oldclassid = $balforwardsetup->classid;
    //                 } 

    //                 $studpaysched = db::table('studpayscheddetail')
    //                     ->select(db::raw('id, studid, semid, syid, classid, particulars, duedate, sum(amount) as totalamountdue, sum(amountpay) as totalpay, balance, SUM(balance) AS totalamount'))
    //                     ->where('studid', $studid)
    //                     ->where('deleted', 0)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where(function($q) use($levelid){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {   
    //                             if(db::table('schoolinfo')->first()->shssetup == 0)
    //                             {
    //                                 $q->where('semid', CashierModel::getSemID());
    //                             }
    //                         }

    //                         if($levelid >= 17 && $levelid <= 21)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->where(function($q) use($othcount, $othclassid){
    //                         if($othcount > 0)
    //                         {
    //                             $q->whereNotIn('classid', $othclassid);
    //                         }
    //                     })
    //                     ->where('classid', '!=', $oldclassid)
    //                     ->groupBy('duedate')
    //                     ->get();

    //                 $scheditemcount = count($studpaysched) - 1;
    //                 $registrationamount = 0;

    //                 foreach($studpaysched as $paysched)
    //                 {   
    //                     if($paysched->duedate != null)
    //                     {
    //                         $dDate = date_create($paysched->duedate);
    //                         $monthdue = strtoupper(date_format($dDate, 'F'));
    //                         $monthno = date_format($dDate, 'n');
    //                     }

    //                     if($levelid >= 17 && $levelid <= 21)
    //                     {
    //                         $dpsetup = db::table('dpsetup')
    //                             ->select(db::raw('*, sum(amount) as totalamount'))
    //                             ->where('levelid', $levelid)
    //                             ->where('deleted', 0)
    //                             ->where('syid', CashierModel::getSYID())
    //                             ->where('semid', CashierModel::getSemID())
    //                             // ->groupBy('classid')
    //                             ->first();

    //                         $_dpsetup = db::table('dpsetup')
    //                             ->select(db::raw('sum(amount) as totalamount, classid'))
    //                             ->where('levelid', $levelid)
    //                             ->where('deleted', 0)
    //                             ->where('syid', CashierModel::getSYID())
    //                             ->where('semid', CashierModel::getSemID())
    //                             ->groupBy('classid')
    //                             ->get();

    //                         $dparray = array();

    //                         foreach($_dpsetup as $dp)
    //                         {
    //                             array_push($dparray, $dp->classid);
    //                         }

    //                         $_psched = db::table('studpayscheddetail')
    //                             ->select(db::raw('sum(amountpay) as tamountpay'))
    //                             ->where('studid', $studid)
    //                             ->where('deleted', 0)
    //                             ->where('syid', CashierModel::getSYID())
    //                             ->where('semid', CashierModel::getSemID())
    //                             ->whereIn('classid', $dparray)
    //                             ->first();

    //                         $_tamountpay = $_psched->tamountpay;


    //                         if($paysched->duedate == null)
    //                         {

    //                             $registrationamount = $dpsetup->totalamount - $_tamountpay;
    //                             // return $registrationamount;
    //                             $_regbal = $registrationamount;

    //                             if($registrationamount < 0)
    //                             {
    //                                 $registrationamount = 0;
    //                             }

    //                             $reg .= '
    //                                 <tr data-id="'.$paysched->id.'" class-id="'.$paysched->classid.'"  data-kind="reg">
    //                                     <td class="paydesc"><span class="">'.strtoupper($paysched->particulars).'</span></td>
    //                                     <td class="text-right payval" data-value="'.$registrationamount.'">'.number_format($registrationamount, 2).'</td>
    //                                 </tr>
    //                             ';     

    //                             $regtotal += $registrationamount;
    //                             // $regremaining = $paysched->totalamountdue - $dpsetup->totalamount;
    //                             // return $regremaining;
    //                             // if($regremaining < 0)
    //                             // {
    //                             //     $regremaining = 0;
    //                             // }
    //                             // $gtotal += $regtotal;

    //                             if($paysched->totalamount == 0)
    //                             {
    //                                 $regremaining = 0;
    //                             }
    //                             else
    //                             {
    //                                 $regremaining = $paysched->totalamount; // - $_tamountpay;// - $dpsetup->totalamount;
    //                             }
    //                             // return $regremaining;
                                
    //                         }
    //                         else
    //                         {
    //                             $duecount += 1;

    //                             if($duecount == 1)
    //                             {
    //                                 $duedesc =  'Prelim Exam';
    //                             }
    //                             elseif($duecount == 2)
    //                             {
    //                                 $duedesc = 'Pre-Midterm Exam';
    //                             }
    //                             elseif($duecount == 3)
    //                             {
    //                                 $duedesc = 'Midterm Exam';
    //                             }
    //                             elseif($duecount == 4)
    //                             {
    //                                 $duedesc = 'Semi-Final Exam';
    //                             }
    //                             elseif($duecount == 5)
    //                             {
    //                                 $duedesc = 'Pre-Final Exam';
    //                             }
    //                             elseif($duecount == 6)
    //                             {
    //                                 $duedesc = 'Final Exam';
    //                             }

    //                             // return $regremaining;

    //                             if($regremaining != 0)
    //                             {
    //                                 if($_regbal > 0)
    //                                 {
    //                                     // $tuitotalamount = $regremaining + $paysched->totalamount;
    //                                     $tuitotalamount = $regremaining + $paysched->totalamount - $_regbal;
    //                                     // return $tuitotalamount;
    //                                     $tui .= '
    //                                         <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'" data-particulars="'.$duedesc.'">
    //                                             <td class="paydesc"><span class="">'.$duedesc.'</span></td>
    //                                             <td class="text-right payval" data-value="'.$tuitotalamount.'">'.number_format($tuitotalamount, 2).'</td>
    //                                         </tr>
    //                                     ';

    //                                     // echo $regremaining;

    //                                     $regremaining = 0;
    //                                     $tuitotal += $tuitotalamount;
    //                                 }
    //                                 else
    //                                 {
    //                                     $tuitotalamount = $paysched->totalamount;
    //                                     $tui .= '
    //                                         <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'" data-particulars="'.$duedesc.'">
    //                                             <td class="paydesc"><span class="">'.$duedesc.'</span></td>
    //                                             <td class="text-right payval" data-value="'.$tuitotalamount.'">'.number_format($tuitotalamount, 2).'</td>
    //                                         </tr>
    //                                     ';                                    

    //                                     $regremaining = 0;
    //                                     $tuitotal += $tuitotalamount;
    //                                 }
    //                             }
    //                             else
    //                             {
    //                                 // $tuitotalamount = $regremaining + $paysched

    //                                 $tui .= '
    //                                     <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'" data-particulars="'.$duedesc.'">
    //                                         <td class="paydesc"><span class="">'.$duedesc.'</span></td>
    //                                         <td class="text-right payval" data-value="'.$paysched->totalamount.'">'.number_format($paysched->totalamount, 2).'</td>
    //                                     </tr>
    //                                 ';    

    //                                 $tuitotal += $paysched->totalamount;
    //                             }

                                
    //                             // $gtotal += $tuitotal;
    //                         }
    //                     }
    //                     else
    //                     {
    //                         $dpsetup = db::table('dpsetup')
    //                             ->select(db::raw('*, sum(amount) as totalamount'))
    //                             ->where('levelid', $levelid)
    //                             ->where('deleted', 0)
    //                             ->where('syid', CashierModel::getSYID())
    //                             ->where(function($q) use($levelid){
    //                                 if($levelid == 14 || $levelid == 15)
    //                                     if(CashierModel::shssetup() == 0)
    //                                     {
    //                                         $q->where('semid', CashierModel::getSemID());
    //                                     }
    //                             })
    //                             // ->groupBy('classid')
    //                             ->first();

    //                         if($paysched->duedate == null)
    //                         {

    //                             $registrationamount = $dpsetup->totalamount - $paysched->totalpay;
    //                             $_regbal = $paysched->totalamount;

    //                             if($registrationamount < 0)
    //                             {
    //                                 $registrationamount = 0;
    //                             }

    //                             $reg .= '
    //                                 <tr data-id="'.$paysched->id.'" class-id="'.$paysched->classid.'"  data-kind="reg">
    //                                     <td class="paydesc"><span class="">'.$paysched->particulars.'</span></td>
    //                                     <td class="text-right payval" data-value="'.$registrationamount.'">'.number_format($registrationamount, 2).'</td>
    //                                 </tr>
    //                             ';     

    //                             $regtotal += $registrationamount;
    //                             $regremaining = $paysched->totalamountdue - $dpsetup->totalamount;

    //                             $_addonfromreg = $regremaining / $scheditemcount;
    //                             $_apushamount = $_regbal;

                                
    //                             for($i = $scheditemcount; $i != 0; $i-=1)
    //                             {
    //                                 if($_apushamount > $_addonfromreg)
    //                                 {
    //                                     array_push($_div, $_addonfromreg);
    //                                     $_apushamount -= $_addonfromreg;
    //                                 }
    //                                 else
    //                                 {
    //                                     array_push($_div, $_apushamount);
    //                                     $_apushamount = 0;        
    //                                 }
    //                             }
    //                         }
    //                         else
    //                         {
    //                             // return $_div;
    //                             if($levelid == 14 || $levelid == 15)                        
    //                             {
    //                                 $duecount += 1;

    //                                 if(CashierModel::getSemID() == 1)
    //                                 {
    //                                     if($duecount == 1)
    //                                     {
    //                                         $duedesc =  '1st - Preliminary';
    //                                         $_addonfromreg = $_div[3];
    //                                     }
    //                                     elseif($duecount == 2)
    //                                     {
    //                                         $duedesc = '1st - Mid-Terms';
    //                                         $_addonfromreg = $_div[2];
    //                                     }
    //                                     elseif($duecount == 3)
    //                                     {
    //                                         $duedesc = '1st - Semi-Final';
    //                                         $_addonfromreg = $_div[1];
    //                                     }
    //                                     elseif($duecount == 4)
    //                                     {
    //                                         $duedesc = '1st - Finals';
    //                                         $_addonfromreg = $_div[0];
    //                                     }
    //                                 }
    //                                 else
    //                                 {
    //                                     if($duecount == 1)
    //                                     {
    //                                         $duedesc = '2nd - Preliminary';
    //                                         $_addonfromreg = $_div[3];
    //                                     }
    //                                     elseif($duecount == 2)
    //                                     {
    //                                         $duedesc = '2nd - Mid-Terms';
    //                                         $_addonfromreg = $_div[2];
    //                                     }
    //                                     elseif($duecount == 3)
    //                                     {
    //                                         $duedesc = '2nd - Semi-Final';
    //                                         $_addonfromreg = $_div[1];
    //                                     }
    //                                     elseif($duecount == 4)
    //                                     {
    //                                         $duedesc = '2nd - Finals';
    //                                         $_addonfromreg = $_div[0];
    //                                     }
    //                                 }
    //                             }
    //                             else
    //                             {
    //                                 $duecount += 1;

    //                                 if($duecount == 1)
    //                                 {
    //                                     $duedesc =  'First Mid-Terms';
    //                                     $_addonfromreg = $_div[7];
    //                                 }
    //                                 elseif($duecount == 2)
    //                                 {
    //                                     $duedesc = 'First Finals';
    //                                     $_addonfromreg = $_div[6];
    //                                 }
    //                                 elseif($duecount == 3)
    //                                 {
    //                                     $duedesc = 'Second Mid-Terms';
    //                                     $_addonfromreg = $_div[5];
    //                                 }
    //                                 elseif($duecount == 4)
    //                                 {
    //                                     $duedesc = 'Second Finals';
    //                                     $_addonfromreg = $_div[4];
    //                                 }
    //                                 elseif($duecount == 5)
    //                                 {
    //                                     $duedesc = 'Third Mid-Terms';
    //                                     $_addonfromreg = $_div[3];
    //                                 }
    //                                 elseif($duecount == 6)
    //                                 {
    //                                     $duedesc = 'Third Finals';
    //                                     $_addonfromreg = $_div[2];
    //                                 }
    //                                 elseif($duecount == 7)
    //                                 {
    //                                     $duedesc = 'Fourth Mid-Terms';
    //                                     $_addonfromreg = $_div[1];
    //                                 }
    //                                 elseif($duecount == 8)
    //                                 {
    //                                     $duedesc = 'Fourth Finals';
    //                                     $_addonfromreg = $_div[0];
    //                                 }
    //                             }

    //                             $_regbal = $paysched->totalamount + $_addonfromreg;

    //                             $tui .= '
    //                                 <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'" data-particulars="'.$duedesc.'">
    //                                     <td class="paydesc"><span class="">'.$duedesc.'</span></td>
    //                                     <td class="text-right payval" data-value="'.$_regbal.'">'.number_format($_regbal, 2).'</td>
    //                                 </tr>
    //                             ';    

    //                             $tuitotal += $_regbal; 
    //                         }
    //                     }
    //                 }

                    

    //                 //OTH
    //                 $studpaysched = db::table('studpayscheddetail')
    //                     ->select(db::raw('id, studid, semid, syid, classid, particulars, duedate, amount, amountpay, balance, SUM(balance) AS totalamount'))
    //                     ->where('studid', $studid)
    //                     ->where('deleted', 0)
    //                     ->whereIn('classid', $othclassid)
    //                     ->where(function($q) use($levelid){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }

    //                         if($levelid >= 17 && $levelid <= 21)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->groupBy('classid')
    //                     ->get();

    //                 foreach($studpaysched as $paysched)
    //                 {
    //                     $oth .= '
    //                         <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'">
    //                             <td class="paydesc"><span class="text-bold">'.$paysched->particulars.'</span></td>
    //                             <td class="text-right payval" data-value="'.$paysched->totalamount.'">'.number_format($paysched->totalamount, 2).'</td>
    //                         </tr>
    //                     ';    

    //                     $othtotal += $paysched->totalamount;
    //                 }

    //                 //OLD
    //                 $studpaysched = db::table('studpayscheddetail')
    //                     ->select(db::raw('id, studid, semid, syid, classid, particulars, duedate, amount, amountpay, balance, SUM(balance) AS totalamount'))
    //                     ->where('studid', $studid)
    //                     ->where('deleted', 0)
    //                     ->where('classid', $oldclassid)
    //                     ->where(function($q) use($levelid){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }

    //                         if($levelid >= 17 && $levelid <= 21)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->groupBy('classid')
    //                     ->get();

    //                 foreach($studpaysched as $paysched)
    //                 {
    //                     $old .= '
    //                         <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'">
    //                             <td class="paydesc"><span class="text-bold">'.$paysched->particulars.'</span></td>
    //                             <td class="text-right payval" data-value="'.$paysched->totalamount.'">'.number_format($paysched->totalamount, 2).'</td>
    //                         </tr>
    //                     ';    

    //                     $oldtotal += $paysched->totalamount;
    //                 }

    //             }
    //             else
    //             {
    //             // MISC | REG
    //                 $miscinformation = db::table('studpayscheddetail')
    //                     ->select(db::raw('studpayscheddetail.*, itemized, groupname'))
    //                     ->leftjoin('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
    //                     ->where('studid', $studid)
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where('groupname', 'MISC')
    //                     ->where(function($q) use($levelid){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }

    //                         if($levelid >= 17 && $levelid <= 21)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->groupBy('classid')
    //                     ->get();


    //                 foreach($miscinformation as $miscinfo)
    //                 {
    //                     if($miscinfo->itemized == 1)
    //                     {
    //                         $miscitemized = 1;

    //                         $itemized = db::table('studledgeritemized')  
    //                             ->select('studledgeritemized.id', 'studid', 'classid', 'items.description', 'totalamount', 'itemamount', 'itemid')
    //                             ->join('items', 'studledgeritemized.itemid', '=', 'items.id')
    //                             ->where('classificationid', $miscinfo->classid)
    //                             ->where('studid', $studid)
    //                             ->where('syid', CashierModel::getSYID())
    //                             ->where('studledgeritemized.deleted', 0)
    //                             ->where(function($q) use($levelid){
    //                                 if($levelid == 14 || $levelid == 15)
    //                                 {
    //                                     $q->where('semid', CashierModel::getSemID());
    //                                 }

    //                                 if($levelid >= 17 && $levelid <= 21)
    //                                 {
    //                                     $q->where('semid', CashierModel::getSemID());
    //                                 }
    //                             })
    //                             ->get();

    //                         foreach($itemized as $item)
    //                         {
    //                             $bal = 0;

    //                             if($item->totalamount == null)
    //                             {
    //                                 $bal = $item->itemamount;
    //                             }
    //                             else
    //                             {
    //                                 $bal = $item->itemamount - $item->totalamount;   
    //                             }

    //                             $misc .='
    //                                 <tr data-id="'.$item->id.'" itemized-id="1" data-kind="misc" data-item="'.$item->itemid.'">
    //                                     <td class="paydesc">'.$item->description.'</span></td>
    //                                     <td class="text-right payval" data-value="'.$bal.'">'.number_format($bal, 2).'</td>
    //                                 </tr>
    //                             ';

    //                             // echo $bal . '; ';

    //                             $misctotal += $bal;

                                
    //                         }
    //                     }
    //                     else
    //                     {
    //                         $miscitemized = 0;
    //                     }
    //                 }
    //                 // MISC

    //                 // OTH
    //                 $studpayscheddetail = db::table('studpayscheddetail')
    //                     ->select(db::raw('studpayscheddetail.*, itemized, groupname, sum(balance) as tbalance'))
    //                     ->leftjoin('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
    //                     ->where('studid', $studid)
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where('groupname', 'OTH')
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where(function($q) use($levelid){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }

    //                         if($levelid >= 17 && $levelid <= 21)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->groupBy('classid')
    //                     ->get();    
                    
    //                 foreach($studpayscheddetail as $paysched)
    //                 {


    //                     if($paysched->itemized == 1)
    //                     {
    //                         $itemized = db::table('studledgeritemized')  
    //                             ->select('studledgeritemized.id', 'studid', 'classid', 'items.description', 'totalamount', 'itemamount', 'itemid')
    //                             ->join('items', 'studledgeritemized.itemid', '=', 'items.id')
    //                             ->where('classificationid', $paysched->classid)
    //                             ->where('studid', $studid)
    //                             ->where('syid', CashierModel::getSYID())
    //                             ->where('studledgeritemized.deleted', 0)
    //                             ->where(function($q) use($levelid){
    //                                 if($levelid == 14 || $levelid == 15)
    //                                 {
    //                                     $q->where('semid', CashierModel::getSemID());
    //                                 }

    //                                 if($levelid >= 17 && $levelid <= 21)
    //                                 {
    //                                     $q->where('semid', CashierModel::getSemID());
    //                                 }
    //                             })
    //                             ->get();

    //                         foreach($itemized as $item)
    //                         {
    //                             $bal = 0;

    //                             if($item->totalamount == null)
    //                             {
    //                                 $bal = $item->itemamount;
    //                             }
    //                             else
    //                             {
    //                                 $bal = $item->itemamount - $item->totalamount;
    //                             }

    //                             $oth .= '
    //                             <tr data-id="'.$paysched->id.'" data-kind="oth" data-classid="'.$paysched->classid.'" data-source="header" data-toggle="tooltip" title="Monthly Due: '.$paysched->amount.'">
    //                                 <td class="paydesc">'.$paysched->particulars.'</span></td>
    //                                 <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
    //                             </tr>
    //                         ';

    //                             $othbal += $bal;
    //                             $othtotal += $bal;
    //                         }
    //                     }
    //                     else
    //                     {
    //                         $othbal = $paysched->tbalance;

    //                         if($othbal < 0)
    //                         {
    //                             $othbal = 0;
    //                         }

    //                         $oth .= '
    //                             <tr data-id="'.$paysched->id.'" data-kind="oth" data-classid="'.$paysched->classid.'" data-source="header" data-toggle="tooltip" title="Monthly Due: '.$paysched->amount.'">
    //                                 <td class="paydesc">'.$paysched->particulars.'</span></td>
    //                                 <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
    //                             </tr>
    //                         ';
    //                         $othtotal += $othbal;
    //                     }
    //                 }
    //                 // OTH



    //                 $studpayscheddetail = db::table('studpayscheddetail')
    //                     ->select(db::raw('studpayscheddetail.*, itemized, groupname'))
    //                     ->leftjoin('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
    //                     ->where('studid', $studid)
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where(function($q) use($levelid){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }

    //                         if($levelid >= 17 && $levelid <= 21)
    //                         {
    //                             $q->where('semid', CashierModel::getSemID());
    //                         }
    //                     })
    //                     ->get();

    //                 foreach($studpayscheddetail as $paysched)
    //                 {
    //                     $dDate = date_create($paysched->duedate);
    //                     $monthdue = strtoupper(date_format($dDate, 'F'));
    //                     $monthno = date_format($dDate, 'n');

    //                     if($paysched->duedate == null || $paysched->duedate == '')
    //                     {
    //                         $monthdue = '';
    //                     }

    //                     if($paysched->groupname == 'reg')
    //                     {
    //                         $reg .= '
    //                             <tr>
    //                                 <td></td>
    //                             </tr>
    //                         ';
    //                     }
    //                     elseif($paysched->groupname == 'MISC')
    //                     {
    //                         if($miscitemized == 0)
    //                         {
    //                             $miscbal = $paysched->balance;

    //                             if($miscbal < 0)
    //                             {
    //                                 $miscbal = 0;
    //                             }

    //                             $misc .= '
    //                                 <tr data-id="'.$paysched->id.'" itemized-id="0" data-kind="misc">
    //                                     <td class="paydesc">'.$paysched->particulars.' <span class="text-bold">'.$monthdue.'</span></td>
    //                                     <td class="text-right payval" data-value="'.$miscbal.'">'.number_format($miscbal, 2).'</td>
    //                                 </tr>
    //                             ';  

    //                             $misctotal += $miscbal; 
    //                         }
    //                     }
    //                     elseif($paysched->groupname == 'TUI')
    //                     {
    //                         $tuibal = $paysched->balance;

    //                         if($tuibal < 0)
    //                         {
    //                             $tuibal = 0;
    //                         }

    //                         $tui .= '
    //                             <tr data-id="'.$paysched->id.'" data-kind="tui">
    //                                 <td class="paydesc">TUITION <span class="text-bold">'.$monthdue.'</span></td>
    //                                 <td class="text-right payval" data-value="'.$tuibal.'">'.number_format($tuibal, 2).'</td>
    //                             </tr>
    //                         ';   

    //                         $tuitotal += $tuibal;
    //                     }
                        
    //                     $balforwardclassid = db::table('balforwardsetup')
    //                         ->first()->classid;

    //                     $balforwarditems = db::table('studpayscheddetail')
    //                         // ->select('aaa')
    //                         ->where('studid', $studid)
    //                         ->where('deleted', 0)
    //                         ->where('syid', CashierModel::getSYID())
    //                         ->where('classid', $balforwardclassid)
    //                         ->where('id', $paysched->id)
    //                         ->where('studpayscheddetail.deleted', 0)
    //                         ->where(function($q) use($levelid){
    //                             if($levelid == 14 || $levelid == 15)
    //                             {
    //                                 $q->where('semid', CashierModel::getSemID());
    //                             }

    //                             if($levelid >= 17 && $levelid <= 21)
    //                             {
    //                                 $q->where('semid', CashierModel::getSemID());
    //                             }
    //                         })
    //                         ->get();

    //                     if(count($balforwarditems) > 0)
    //                     {
    //                         foreach($balforwarditems as $oldacc)
    //                         {
    //                             $oldbal = $oldacc->balance;

    //                             if($oldbal < 0)
    //                             {
    //                                 $oldbal = 0;
    //                             }

    //                             if($oldacc->duedate == null || $oldacc->duedate == '')
    //                             {
    //                                 $monthdue = '';
    //                             }
    //                             else
    //                             {
    //                                 $dDate = date_create($oldacc->duedate);
    //                                 $monthdue = strtoupper(date_format($dDate, 'F'));
    //                                 $monthno = date_format($dDate, 'n');
    //                             }

    //                             $old .= '
    //                                 <tr data-id="'.$oldacc->id.'" data-kind="old">
    //                                     <td class="paydesc">'.$oldacc->particulars.' <span class="text-bold">'.$monthdue.'</span></td>
    //                                     <td class="text-right payval" data-value="'.$oldbal.'">'.number_format($oldbal, 2).'</td>
    //                                 </tr>
    //                             ';

    //                             $oldtotal += $oldbal;
    //                         }
    //                     }



    //                 }
    //             }

    //             $gtotal = $regtotal + $tuitotal + $misctotal + $othtotal + $oldtotal;

    //             if(db::table('schoolinfo')->first()->snr == 'dcc')
    //             {
    //                 $classitem = db::table('studpayscheddetail')
    //                     ->select('itemclassification.id', 'particulars as description', 'duedate')
    //                     ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
    //                     ->where('studid', $studid)
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where('semid', CashierModel::getSemID())
    //                     ->where('balance', '>', 0)
    //                     ->groupBy('duedate')
    //                     ->get();

    //                 $citem = '<option value="0">All</option>';
    //                 $classarray = array();

    //                 foreach($classitem as $class)
    //                 {
    //                     if(!in_array($class->description, $classarray))
    //                     {
    //                         array_push($classarray, $class->description);

    //                         $citem .='
    //                             <option value="'.$class->id.'" data-due="'.$class->duedate.'">'.$class->description.'</option>
    //                         ';
    //                     }
    //                 }
    //             }
    //             else
    //             {
    //                 $classitem = db::table('studpayscheddetail')
    //                     ->select('itemclassification.id', 'particulars as description', 'duedate')
    //                     ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
    //                     ->where('studid', $studid)
    //                     ->where('studpayscheddetail.deleted', 0)
    //                     ->where('syid', CashierModel::getSYID())
    //                     ->where('semid', CashierModel::getSemID())
    //                     ->where('balance', '>', 0)
    //                     ->groupBy('duedate')
    //                     ->get();

    //                 $citem = '<option value="0">All</option>';
    //                 $classarray = array();

    //                 foreach($classitem as $class)
    //                 {
    //                     if(!in_array($class->description, $classarray))
    //                     {
    //                         array_push($classarray, $class->description);

    //                         $citem .='
    //                             <option value="'.$class->id.'" data-due="'.$class->duedate.'">'.$class->description.'</option>
    //                         ';
    //                     }
    //                 }
    //             }
    //         }

    //         // return $old;
    //         $data = array(
    //             'levelid' => $levelid,
    //             'feesid' => $feesid,
    //             'levelname' => $levelname,
    //             'studid' => $studid,
    //             'name' => $name,
    //             'course' => $course,
    //             'strand'  => $strand,
    //             'info' => $info,
    //             'tui' => $tui,
    //             'oth' => $oth,
    //             'old' => $old,
    //             'misc' => $misc,
    //             'reg' => $reg,
    //             'tnum' => $tnum,
    //             'tuitotal' => number_format($tuitotal, 2),
    //             'othtotal' => number_format($othtotal, 2),
    //             'oldtotal' => number_format($oldtotal, 2),
    //             'misctotal' => number_format($misctotal, 2),
    //             'regtotal' => number_format($regtotal, 2),
    //             'gtotal' => number_format($gtotal, 2),
    //             'citem' => $citem,
    //             'acadprogid' => $acadprogid
    //         );

    //         echo json_encode($data);
    //     }
    // }

    public function v3_payinfo(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $_feesid = $request->get('feesid');
            $tnum = CashierModel::getTransNo();

            // return $tnum;
            $studinfo = db::table('studinfo')
                ->where('id', $studid)
                ->first();

            $sid = $studinfo->sid;
            // $levelid = $studinfo->levelid;
            $feesid = $studinfo->feesid;
            $noloading = 0;

            if($studinfo->strandid > 0 || $studinfo->strandid != null)
            {
                $sh_strand = db::table('sh_strand')
                    ->where('id', $studinfo->strandid)
                    ->first();

                if($sh_strand)
                {
                    $strand = ' | ' .$sh_strand->strandcode;
                }
                else
                {
                    $strand = '';
                }
            }
            
            $grantees = db::table('grantee')
                ->where('id', $studinfo->grantee)
                ->first();

            $grantee = '';

            if($grantee)
            {
                $grantee = $grantees->description;
            }

            $miscitemized = 0;

            $reg = '';
            $tui = '';
            $misc = '';
            $oth = '';
            $old = '';
            $course = '';

            $regcount = 0;
            $tuicount = 0;
            $misccount = 0;
            $othtotal = 0;
            $_oldtotal = 0;
            $fees_bal = 0;

            $regtotal = 0;
            $tuitotal = 0;
            $misctotal = 0;
            $othtotal = 0;
            $oldtotal = 0;
            $gtotal = 0;

            $grandtotal = 0;

            $dDate = '';
            $monthdue = '';
            $monthno = '';
            $duecount = 0;
            $duedesc = '';
            $regremaining = 0;
            $_regbal = 0;

            $citem = '';
            $misclist = '';

            $_div = array();
            $_estud = collect();
            $ledger_estud = collect();

            $_enrolled = db::table('enrolledstud')
                ->select('id as enrollid', 'studid', 'levelid', 'syid', 'ghssemid as semid', 'studstatus')
                ->where('studid', $studid)
                ->where('syid', CashierModel::getSYID())
                ->where(function($q){
                    if(CashierModel::getSemID() == 3)
                    {
                        $q->where('ghssemid', 3);
                    }
                })
                ->where('deleted', 0)
                ->where('studstatus', '>', 0)
                ->first();

            $sh_enrolled = db::table('sh_enrolledstud')
                ->select('id as enrollid', 'studid', 'levelid', 'syid', 'syid', 'studstatus')
                ->where('studid', $studid)
                ->where('syid', CashierModel::getSYID())
                ->where(function($q){
                    if(CashierModel::getSemID() == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', CashierModel::getsemID());
                        }
                    }
                })
                ->where('deleted', 0)
                ->where('studstatus', '>', 0)
                ->first();

            $college_enrolled = db::table('college_enrolledstud')
                ->select('id as enrollid', 'studid', 'yearLevel as levelid', 'syid', 'syid', 'courseid', 'studstatus')
                ->where('studid', $studid)
                ->where('syid', CashierModel::getSYID())
                ->where('semid', CashierModel::getSemID())
                ->where('deleted', 0)
                ->where('studstatus', '>', 0)
                ->first();

            $_estud = $_estud->merge($_enrolled);
            $_estud = $_estud->merge($sh_enrolled);
            $_estud = $_estud->merge($college_enrolled);

            // return $_estud;

            $levelid = 0;

            if($_estud->count())
            {
                $levelid = $_estud['levelid'];

                if($levelid >= 17 && $levelid <= 21)
                {
                    if($_estud['courseid'] > 0)
                    {
                        $college_courses = db::table('college_courses')
                            ->where('id', $_estud['courseid'])
                            ->first();

                        if($college_courses)
                        {
                            $course = ' | ' . $college_courses->courseabrv;
                        }
                        else
                        {
                            $course = '';
                        }
                    }
                }
            }
            else
            {
                if($studinfo->courseid > 0)
                {
                    $college_courses = db::table('college_courses')
                        ->where('id', $studinfo->courseid)
                        ->first();

                    if($college_courses)
                    {
                        $course = ' | ' . $college_courses->courseabrv;
                    }
                    else
                    {
                        $course = '';
                    }
                }

                $levelid = $studinfo->levelid;
            }

            // return $course;
            
            $gradelevel = db::table('gradelevel')
                ->where('id', $levelid)
                ->first();

            $acadprogid = 0;

            if($gradelevel)
            {
                $acadprogid = $gradelevel->acadprogid;
            }

            $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
            // $course = '';
            $strand = '';

            $studstatus = db::table('studentstatus')->where('id', $studinfo->studstatus)->first()->description;

            $name = $studinfo->lastname . ', ' . $studinfo->firstname . ' ' . $studinfo->middlename . ' ' . $studinfo->suffix;
            $info = '
                <tr>
                    <td>'.$levelname.' - '.$studinfo->sectionname.' | '.$grantee.'</td>
                    <td>'.$studstatus.'</td>
                </td>
            ';
            
            $syinfo = '';
            // $_estud = collect();
            $collect_ledger = collect();
            $ledgerinfo_list = '';

            $ledger_enrolled = db::table('enrolledstud')
                ->select('levelid', 'syid', 'ghssemid as semid', 'levelname', 'studstatus')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->where('studid', $studid)
                ->where('enrolledstud.deleted', 0)
                ->where('studstatus', '>', 0)
                ->get();

            $ledgersh_enrolled = db::table('sh_enrolledstud')
                ->select('levelid', 'syid', 'semid', 'levelname', 'studstatus')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->where('studid', $studid)
                ->where('sh_enrolledstud.deleted', 0)
                ->where('studstatus', '>', 0)
                ->get();

            $ledgercollege_enrolled = db::table('college_enrolledstud')
                ->select('yearLevel as levelid', 'syid', 'semid', 'levelname', 'studstatus')
                ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                ->where('studid', $studid)
                ->where('college_enrolledstud.deleted', 0)
                ->where('studstatus', '>', 0)
                ->get();



            $ledger_estud = $ledger_estud->merge($ledger_enrolled);
            $ledger_estud = $ledger_estud->merge($ledgersh_enrolled);
            $ledger_estud = $ledger_estud->merge($ledgercollege_enrolled);

            $ledgerinfo = db::table('studledger')
                ->select('syid', 'semid', 'sydesc', 'semester')
                ->join('sy', 'studledger.syid', '=', 'sy.id')
                ->join('semester', 'studledger.semid', '=', 'semester.id')
                ->where('studid', $studid)
                ->where('studledger.deleted', 0)
                ->groupBy('syid', 'semid')
                ->get();

            foreach($ledgerinfo as $l_info)
            {
                $filtered = $ledger_estud->where('syid', $l_info->syid)->first();
                // $filtered->values();
                // return $filtered;
                if($filtered)
                {
                    $ledgerinfo_list .='
                        <option value="'.$l_info->syid.','.$l_info->semid.'">
                            '.$l_info->sydesc.' - '.$l_info->semester.' | '.$filtered->levelname.'
                        </option>
                    ';
                }
                else
                {
                    $ledgerinfo_list .='
                        <option value="'.$l_info->syid.','.$l_info->semid.'">
                            '.$l_info->sydesc.' - '.$l_info->semester.' | Not Enrolled
                        </option>
                    ';   
                }
            }

            $_addonfromreg = 0;
            $tb_enroll ='';

            // if($levelid == 14 || $levelid == 15)
            // {
            //     $tb_enroll = 'sh_enrolledstud';
            // }
            // elseif($levelid >= 17 && $levelid <= 21)
            // {
            //     $tb_enroll = 'college_enrolledstud';
            // }
            // else
            // {
            //     $tb_enroll = 'enrolledstud';
            // }

            $feelist = '';
            // $isEnroll = 0;

            // $checkenroll = db::table($tb_enroll)
            //     ->where('studid', $studid)
            //     ->where('syid', CashierModel::getSYID())
            //     ->where('deleted', 0)
            //     ->where('studstatus', '>', 0)
            //     ->where(function($q) use($levelid){
            //         if(CashierModel::getSemID() == 3)
            //         {
            //             $q->where('semid', CashierModel::getSemID());
            //         }
            //         else
            //         {
            //             if($levelid == 14 || $levelid == 15)
            //             {
            //                 if(db::table('schoolinfo')->first()->shssetup == 0)
            //                 {
            //                     $q->where('semid', CashierModel::getSemID());
            //                 }
            //             }
            //             if($levelid >= 17 && $levelid <= 21)
            //             {
            //                 $q->where('semid', CashierModel::getSemID());
            //             }
            //         }
            //     })
            //     ->first();



            // if(!$checkenroll)
            if(!$_estud->count())
            {
                $isEnroll = 0;

                $fees = db::table('tuitionheader')
                    ->select('id', 'description')
                    ->where('levelid', $levelid)
                    ->where('syid', CashierModel::getSYID())
                    ->where(function($q) use($levelid){
                        if($levelid == 14 || $levelid == 15)  
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', CashierModel::getSemID());
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
                        }
                    })
                    ->where('deleted', 0)
                    ->get();

                foreach($fees as $fee)
                {
                    $feelist .='
                        <tr data-id="'.$fee->id.'">
                            <td>'.$fee->description.'</td>
                        </tr>
                    ';
                }

                //MISC_start
                $chrngsetup = db::table('chrngsetup')
                    ->where('groupname', 'MISC')
                    ->where('deleted', 0)
                    ->get();

                foreach($chrngsetup as $_setup)
                {
                    if($chrngsetup)
                    {
                        $setup_classid = $_setup->classid;

                        if($_setup->itemized == 1)
                        {
                            $miscs = db::table('tuitionheader')
                                ->select(db::raw('tuitionheader.id AS headerid, tuitionitems.id as tuitionitemid, classificationid, items.id AS itemid, items.description, tuitionitems.amount, pschemeid'))
                                ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                                ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                                ->join('items', 'tuitionitems.itemid', '=', 'items.id')
                                ->where('tuitionheader.id', $_feesid)
                                ->where('tuitiondetail.classificationid', $setup_classid)
                                ->where('tuitionheader.deleted', 0)
                                ->where('tuitiondetail.deleted', 0)
                                ->where('tuitionitems.deleted', 0)
                                ->get();

                            $_count = 0;

                            foreach($miscs as $m)
                            {
                                $_count+=1;
                                $_trx = db::table('chrngtrans')
                                    ->select(db::raw('chrngtrans.id, ornum, chrngtrans.studid, sum(chrngcashtrans.`amount`) as amount, duedate, classid, kind, itemid'))
                                    ->join('chrngcashtrans', function($q){
                                        $q->on('chrngtrans.transno', '=', 'chrngcashtrans.transno')
                                            ->on('chrngtrans.studid', '=', 'chrngcashtrans.studid');
                                    })
                                    ->where('chrngtrans.syid', CashierModel::getSYID())
                                    ->where(function($q) use($levelid){
                                        if($levelid == 14 || $levelid == 15)  
                                        {
                                            if(db::table('schoolinfo')->first()->shssetup == 0)
                                            {
                                                $q->where('chrngtrans.semid', CashierModel::getSemID());
                                            }
                                        }
                                        elseif($levelid >= 17 && $levelid <= 21)
                                        {
                                            $q->where('chrngtrans.semid', CashierModel::getSemID());
                                        }
                                        else
                                        {
                                            if(CashierModel::getSemID() == 3)
                                            {
                                                $q->where('chrngtrans.semid', 3);
                                            }
                                        }
                                    })
                                    ->where('itemid', $m->itemid)
                                    ->where('chrngtrans.studid', $studid)
                                    ->where('chrngcashtrans.deleted', 0)
                                    ->where('cancelled', 0)
                                    ->groupBy('itemid')
                                    ->first();

                                $trx_amount = 0;

                                if($_trx)
                                {
                                    $trx_amount = $m->amount - $_trx->amount;
                                }
                                else
                                {
                                    $trx_amount = $m->amount;
                                }

                                // $misc .='
                                //     <tr data-id="'.$m->tuitionitemid.'-'.$_count.'" itemized-id="1" data-kind="misc" data-item="'.$m->itemid.'">
                                //         <td class="paydesc">'.$m->description.'</span></td>
                                //         <td class="text-right payval" data-value="'.$trx_amount.'">'.number_format($trx_amount, 2).'</td>
                                //     </tr>
                                // ';

                                $misc .='
                                    <tr data-id="'.$m->tuitionitemid.'" itemized-id="1" data-kind="misc" data-item="'.$m->itemid.'">
                                        <td class="paydesc">'.$m->description.'</span></td>
                                        <td class="text-right payval" data-value="'.$trx_amount.'">'.number_format($trx_amount, 2).'</td>
                                    </tr>
                                ';

                                $misctotal += $trx_amount;
                            }
                        }
                        else
                        {
                            $_misc = db::table('tuitionheader')
                                ->select(db::raw('tuitionheader.id AS headerid, tuitiondetail.id AS tuitiondetailid, classificationid, itemclassification.description as particulars, items.id AS itemid, items.description, tuitiondetail.amount, pschemeid'))
                                ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                                ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                                ->join('items', 'tuitionitems.itemid', '=', 'items.id')
                                ->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
                                ->where('tuitionheader.id', $_feesid)
                                ->where('tuitiondetail.classificationid', $setup_classid)
                                ->where('tuitionheader.deleted', 0)
                                ->where('tuitiondetail.deleted', 0)
                                ->where('tuitionitems.deleted', 0)
                                ->first();

                            if($_misc)
                            {
                                $modeofpayment = db::table('paymentsetup')
                                    ->select(db::raw('paymentsetup.id, paymentsetupdetail.id AS paydetailid, noofpayment, payopt, paymentno, duedate, percentamount'))
                                    ->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
                                    ->where('paymentsetup.id', $_misc->pschemeid)
                                    ->where('paymentsetupdetail.deleted', 0)
                                    ->get();

                                $noofpayment = $modeofpayment[0]->noofpayment;
                                $curamount = $_misc->amount;
                                $miscbal = number_format($curamount/$noofpayment, 2, '.', '');
                                $loopcount = 0;
                                $loopval = 0;

                                if($miscbal < 0)
                                {
                                    $miscbal = 0;
                                }

                                $_count = 0;
                                foreach($modeofpayment as $mop)
                                {
                                    $_count += 1;
                                    $_trx = db::table('chrngtrans')
                                        ->select(db::raw('chrngtrans.id, ornum, chrngtrans.studid, chrngcashtrans.`amount`, duedate, classid, kind, itemid'))
                                        ->join('chrngcashtrans', function($q){
                                            $q->on('chrngtrans.transno', '=', 'chrngcashtrans.transno')
                                                ->on('chrngtrans.studid', '=', 'chrngcashtrans.studid');
                                        })
                                        ->where('chrngtrans.syid', CashierModel::getSYID())
                                        ->where(function($q) use($levelid){
                                            if($levelid == 14 || $levelid == 15)  
                                            {
                                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                                {
                                                    $q->where('chrngtrans.semid', CashierModel::getSemID());
                                                }
                                            }
                                            elseif($levelid >= 17 && $levelid <= 21)
                                            {
                                                $q->where('chrngtrans.semid', CashierModel::getSemID());
                                            }
                                            else
                                            {
                                                if(CashierModel::getSemID() == 3)
                                                {
                                                    $q->where('chrngtrans.semid', 3);
                                                }
                                            }
                                        })
                                        ->where('classid', $setup_classid)
                                        ->where('duedate', $mop->duedate)
                                        ->where('chrngtrans.studid', $studid)
                                        ->where('chrngcashtrans.deleted', 0)
                                        ->where('cancelled', 0)
                                        ->first();

                                    $dDate = date_create($mop->duedate);
                                    $monthdue = strtoupper(date_format($dDate, 'F'));
                                    $monthno = date_format($dDate, 'n');

                                    if($mop->duedate == null || $mop->duedate == '')
                                    {
                                        $monthdue = '';
                                    }

                                    $loopcount +=1;

                                    $trx_amount = 0;

                                    if($loopcount == $noofpayment)
                                    {
                                        $miscbal = $curamount - $loopval;
                                    }

                                    $loopval += $miscbal;

                                    if($_trx)
                                    {
                                        $trx_amount = $miscbal - $_trx->amount;
                                    }
                                    else
                                    {
                                        $trx_amount = $miscbal;
                                    }

                                    $misc .='
                                        <tr data-id="'.$_misc->tuitiondetailid.'" data-kind="misc" data-due="'.$mop->duedate.'" data-classid="'.$_misc->classificationid.'">
                                            <td class="paydesc">'.$_misc->particulars.' <span class="text-bold">'.$monthdue.'</span></td>
                                            <td class="text-right payval" data-value="'.$trx_amount.'">'.number_format($trx_amount, 2).'</td>
                                        </tr>
                                    ';

                                    // $misc .='
                                    //     <tr data-id="'.$_misc->tuitiondetailid.'-'.$_count.'" data-kind="misc" data-due="'.$mop->duedate.'" data-classid="'.$_misc->classificationid.'">
                                    //         <td class="paydesc">'.$_misc->particulars.' <span class="text-bold">'.$monthdue.'</span></td>
                                    //         <td class="text-right payval" data-value="'.$trx_amount.'">'.number_format($trx_amount, 2).'</td>
                                    //     </tr>
                                    // ';

                                    // $misc .= '
                                    //     <tr data-id="'.$paysched->id.'" itemized-id="0" data-kind="misc">
                                    //         <td class="paydesc">'.$paysched->particulars.' <span class="text-bold">'.$monthdue.'</span></td>
                                    //         <td class="text-right payval" data-value="'.$miscbal.'">'.number_format($miscbal, 2).'</td>
                                    //     </tr>
                                    // ';  

                                    $misctotal += $trx_amount;
                                }
                            }
                        }
                    }
                }
                //MISC_end

                //TUI_start
                $chrngsetup = db::table('chrngsetup')
                    ->where('groupname', 'TUI')
                    ->where('deleted', 0)
                    ->get();

                foreach($chrngsetup as $_setup)
                {
                    $setup_classid = $_setup->classid;

                    $_tui = db::table('tuitionheader')
                        ->select(db::raw('tuitionheader.id AS headerid, tuitiondetail.id AS tuitiondetailid, classificationid, items.id AS itemid, items.description, tuitionitems.amount, pschemeid'))
                        ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                        ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                        ->join('items', 'tuitionitems.itemid', '=', 'items.id')
                        ->where('tuitionheader.id', $_feesid)
                        ->where('tuitiondetail.classificationid', $setup_classid)
                        ->where('tuitionheader.deleted', 0)
                        ->where('tuitiondetail.deleted', 0)
                        ->where('tuitionitems.deleted', 0)
                        ->first();

                    if($_tui)
                    {
                        $units = 0;
                        $curamount = 0;

                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $totalunits = db::table('college_studsched')
                                ->select(db::raw('SUM(lecunits) + SUM(labunits) AS totalunits'))
                                ->join('college_classsched', 'college_studsched.schedid', '=', 'college_classsched.id')
                                ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                                ->where('college_studsched.studid', $studid)
                                ->where('college_studsched.deleted', 0)
                                ->where('college_classsched.deleted', 0)
                                ->where('college_classsched.syID', CashierModel::getSYID())
                                ->where('college_classsched.semesterID', CashierModel::getSemID())
                                ->first();

                            if($totalunits)
                            {
                                $units = $totalunits->totalunits;
                            }
                            else
                            {
                                $units = 0;
                                
                            }

                            if($units > 0)
                            {
                                $noloading = 0;
                            }
                            else
                            {
                                $noloading = 1;
                            }

                            $curamount = $_tui->amount * $units;
                        }
                        else
                        {
                            $curamount = $_tui->amount;
                        }

                        $modeofpayment = db::table('paymentsetup')
                            ->select(db::raw('paymentsetup.id, paymentsetupdetail.id AS paydetailid, noofpayment, payopt, paymentno, duedate, percentamount'))
                            ->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
                            ->where('paymentsetup.id', $_tui->pschemeid)
                            ->where('paymentsetupdetail.deleted', 0)
                            ->get();

                        $noofpayment = $modeofpayment[0]->noofpayment;
                        // $curamount = $_tui->amount;
                        $tuibal = number_format($curamount/$noofpayment, 2, '.', '');
                        $loopcount = 0;
                        $loopval = 0;
                        
                        if($tuibal < 0)
                        {
                            $tuibal = 0;
                        }

                        $_count = 0;

                        foreach($modeofpayment as $mop)
                        {
                            $_count += 1;
                            $_trx = db::table('chrngtrans')
                                ->select(db::raw('chrngtrans.id, ornum, chrngtrans.studid, sum(chrngcashtrans.`amount`) as amount, duedate, classid, kind, itemid'))
                                ->join('chrngcashtrans', function($q){
                                    $q->on('chrngtrans.transno', '=', 'chrngcashtrans.transno')
                                        ->on('chrngtrans.studid', '=', 'chrngcashtrans.studid');
                                })
                                ->where('chrngtrans.syid', CashierModel::getSYID())
                                ->where(function($q) use($levelid){
                                    if($levelid == 14 || $levelid == 15)  
                                    {
                                        if(db::table('schoolinfo')->first()->shssetup == 0)
                                        {
                                            $q->where('chrngtrans.semid', CashierModel::getSemID());
                                        }
                                    }
                                    elseif($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('chrngtrans.semid', CashierModel::getSemID());
                                    }
                                    else
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('chrngtrans.semid', 3);
                                        }
                                    }
                                })
                                ->where('classid', $setup_classid)
                                ->where('duedate', $mop->duedate)
                                ->where('chrngtrans.studid', $studid)
                                ->where('chrngcashtrans.deleted', 0)
                                ->where('cancelled', 0)
                                ->first();

                            $dDate = date_create($mop->duedate);
                            $monthdue = strtoupper(date_format($dDate, 'F'));
                            $monthno = date_format($dDate, 'n');

                            if($mop->duedate == null || $mop->duedate == '')
                            {
                                $monthdue = '';
                            }

                            $loopcount +=1;

                            $trx_amount = 0;

                            if($loopcount == $noofpayment)
                            {
                                $tuibal = $curamount - $loopval;
                            }

                            $loopval += $tuibal;

                            if($_trx)
                            {
                                $trx_amount = $tuibal - $_trx->amount;
                            }
                            else
                            {
                                $trx_amount = $tuibal;
                            }

                            $tui .='
                                <tr data-id="'.$_tui->tuitiondetailid.'-'.$_count.'" data-kind="tui" data-due="'.$mop->duedate.'" data-classid="'.$_tui->classificationid.'" data-particulars="TUITION '.strtoupper($monthdue).'">
                                    <td class="paydesc">TUITION <span class="text-bold">'.$monthdue.'</span></td>
                                    <td class="text-right payval" data-value="'.$trx_amount.'">'.number_format($trx_amount, 2).'</td>
                                </tr>
                            ';

                            $tuitotal += $trx_amount;
                        }
                    }   
                }
                //TUI_end

                //OTH_start
                $chrngsetup = db::table('chrngsetup')
                    ->where('groupname', 'OTH')
                    ->where('deleted', 0)
                    ->get();

                foreach($chrngsetup as $setup)
                {
                    $_othdata = db::table('tuitionheader')
                        ->select(db::raw('tuitionheader.id AS headerid, tuitiondetail.id AS tuitiondetailid, classificationid, items.id AS itemid, items.description as particulars, tuitionitems.amount, pschemeid, tuitionitems.id as tuitionitemid'))
                        ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                        ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                        ->join('items', 'tuitionitems.itemid', '=', 'items.id')
                        ->where('tuitionheader.id', $_feesid)
                        ->where('tuitiondetail.classificationid', $setup->classid)
                        ->where('tuitionheader.deleted', 0)
                        ->where('tuitiondetail.deleted', 0)
                        ->where('tuitionitems.deleted', 0)
                        ->get();

                    foreach($_othdata as $_oth)
                    {
                        $othbal = $_oth->amount;
                        $trx_amount = 0;

                        if($setup->itemized != 1)
                        {
                            $_trx = db::table('chrngtrans')
                                ->select(db::raw('chrngtrans.id, ornum, chrngtrans.studid, chrngcashtrans.`amount`, duedate, classid, kind, itemid'))
                                ->join('chrngcashtrans', function($q){
                                    $q->on('chrngtrans.transno', '=', 'chrngcashtrans.transno')
                                        ->on('chrngtrans.studid', '=', 'chrngcashtrans.studid');
                                })
                                ->where('chrngtrans.syid', CashierModel::getSYID())
                                ->where(function($q) use($levelid){
                                    if($levelid == 14 || $levelid == 15)  
                                    {
                                        if(db::table('schoolinfo')->first()->shssetup == 0)
                                        {
                                            $q->where('chrngtrans.semid', CashierModel::getSemID());
                                        }
                                    }
                                    elseif($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('chrngtrans.semid', CashierModel::getSemID());
                                    }
                                    else
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('chrngtrans.semid', 3);
                                        }
                                    }
                                })
                                ->where('classid', $_oth->classificationid)
                                ->where('chrngtrans.studid', $studid)
                                ->where('chrngcashtrans.deleted', 0)
                                ->where('cancelled', 0)
                                ->get();
                            
                            if(count($_trx) > 0)  
                            {
                                foreach($_trx as $_t)
                                {
                                    $trx_amount += $_t->amount;
                                }

                                $trx_amount = $othbal - $trx_amount;
                            }
                            else
                            {
                                $trx_amount = $othbal;
                            }

                            $oth .='
                                <tr data-id="'.$_oth->tuitiondetailid.'" data-kind="oth" data-classid="'.$_oth->classificationid.'" data-source="header" data-toggle="tooltip" title="Monthly Due: '.$_oth->amount.'">
                                    <td class="paydesc">'.$_oth->particulars.'</span></td>
                                    <td class="text-right payval" data-value="'.$trx_amount.'">'.number_format($trx_amount, 2).'</td>
                                </tr>
                            ';

                            $othtotal += $trx_amount;
                        }
                        else
                        {
                            $_trx = db::table('chrngtrans')
                                ->select(db::raw('chrngtrans.id, ornum, chrngtrans.studid, chrngcashtrans.`amount`, duedate, classid, kind, itemid'))
                                ->join('chrngcashtrans', function($q){
                                    $q->on('chrngtrans.transno', '=', 'chrngcashtrans.transno')
                                        ->on('chrngtrans.studid', '=', 'chrngcashtrans.studid');
                                })
                                ->where('chrngtrans.syid', CashierModel::getSYID())
                                ->where(function($q) use($levelid){
                                    if($levelid == 14 || $levelid == 15)  
                                    {
                                        if(db::table('schoolinfo')->first()->shssetup == 0)
                                        {
                                            $q->where('chrngtrans.semid', CashierModel::getSemID());
                                        }
                                    }
                                    elseif($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('chrngtrans.semid', CashierModel::getSemID());
                                    }
                                    else
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('chrngtrans.semid', 3);
                                        }
                                    }
                                })
                                ->where('itemid', $_oth->itemid)
                                ->where('chrngtrans.studid', $studid)
                                ->where('cancelled', 0)
                                ->first();

                            if($_trx)
                            {
                                $trx_amount = $_oth->amount - $_trx->amount;
                            }
                            else
                            {
                                $trx_amount = $_oth->amount;
                            }

                            $oth .='
                                <tr data-id="'.$_oth->tuitionitemid.'" itemized-id="1" data-kind="oth" data-item="'.$_oth->itemid.'">
                                    <td class="paydesc">'.$_oth->particulars.'</span></td>
                                    <td class="text-right payval" data-value="'.$trx_amount.'">'.number_format($trx_amount, 2).'</td>
                                </tr>
                            ';

                            $othtotal += $trx_amount;   
                        }
                    }
                }


                //OTH_end

                $gtotal = $tuitotal + $misctotal + $othtotal + $oldtotal;

                $balforwardclassid = db::table('balforwardsetup')
                    ->first()->classid;

                $balforwarditems = db::table('studpayscheddetail')
                    // ->select('aaa')
                    ->where('studid', $studid)
                    ->where('deleted', 0)
                    ->where('syid', CashierModel::getSYID())
                    ->where('classid', $balforwardclassid)
                    ->where('studpayscheddetail.deleted', 0)
					->where('balance', '>', 0)
                    ->where(function($q) use($levelid){
                        if($levelid == 14 || $levelid == 15)  
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', CashierModel::getSemID());
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
                        }
                    })
                    ->get();

                if(count($balforwarditems) > 0)
                {
                    foreach($balforwarditems as $oldacc)
                    {
                        $oldbal = $oldacc->balance;

                        if($oldbal < 0)
                        {
                            $oldbal = 0;
                        }

                        if($oldacc->duedate == null || $oldacc->duedate == '')
                        {
                            $monthdue = '';
                        }
                        else
                        {
                            $dDate = date_create($oldacc->duedate);
                            $monthdue = strtoupper(date_format($dDate, 'F'));
                            $monthno = date_format($dDate, 'n');
                        }

                        $old .= '
                            <tr data-id="'.$oldacc->id.'" data-kind="old" data-classid="'.$balforwardclassid.'">
                                <td class="paydesc">'.$oldacc->particulars.' <span class="text-bold">'.$monthdue.'</span></td>
                                <td class="text-right payval" data-value="'.$oldbal.'">'.number_format($oldbal, 2).'</td>
                            </tr>
                        ';

                        $oldtotal += $oldbal;
                        $gtotal += $oldbal;
                    }
                }

                $fees_bal = $oldtotal;

                $bookclassid = db::table('bookentrysetup')->first()->classid;

                $bookpaysched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(balance) as balance, particulars, id, classid'))
                    ->where('studid', $studid)
                    ->where('classid', $bookclassid)
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
                        }
                    })
                    ->groupBy('particulars')
                    ->get();

                foreach($bookpaysched as $paysched)
                {
                    $_count += 1;
                    $othbal = $paysched->balance;

                    if($othbal < 0)
                    {
                        $othbal = 0;
                    }

                    $oth .= '
                            <tr data-id="'.$paysched->id.'" data-kind="oth" data-classid="'.$paysched->classid.'">
                                <td class="paydesc">'.$paysched->particulars.'</span></td>
                                <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
                            </tr>
                        ';

                    $othtotal += $othbal;
                    $gtotal += $othbal;
                }

                $studpayscheddetail = db::table('studpayscheddetail')
                    ->select(db::raw('studpayscheddetail.*, itemized, groupname, sum(balance) as tbalance'))
                    ->leftjoin('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
                    ->where('studid', $studid)
                    ->where('studpayscheddetail.deleted', 0)
                    ->where('syid', CashierModel::getSYID())
                    ->where('groupname', 'OTH')
                    ->where('studpayscheddetail.classid', '!=', $bookclassid)
                    ->where('studpayscheddetail.deleted', 0)
                    ->where(function($q) use($levelid){
                        if($levelid == 14 || $levelid == 15)  
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', CashierModel::getSemID());
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
                        }
                    })
                    ->groupBy('classid')
                    ->get(); 

                $_count = 0;   
                
                foreach($studpayscheddetail as $paysched)
                {
                    $_count += 1;
                    $othbal = $paysched->tbalance;

                    if($othbal < 0)
                    {
                        $othbal = 0;
                    }

                    // $oth .= '
                    //         <tr data-id="'.$paysched->id.'-'.$_count.'" data-kind="oth">
                    //             <td class="paydesc">'.$paysched->particulars.'</span></td>
                    //             <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
                    //         </tr>
                    //     ';

                    $oth .= '
                            <tr data-id="'.$paysched->id.' data-kind="oth">
                                <td class="paydesc">'.$paysched->particulars.'</span></td>
                                <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
                            </tr>
                        ';

                    $othtotal += $othbal;
                    $gtotal += $othbal;
                }

                if(db::table('schoolinfo')->first()->snr == 'holycross')
                {
                    foreach(DisplayModel::ne_oth($levelid) as $tdetail)
                    {
                        $othbal = $tdetail->amount;
                        $oth .= '
                            <tr data-id="'.$tdetail->id.'" data-kind="addon">
                                <td class="paydesc">'.$tdetail->description.'</span></td>
                                <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
                            </tr>
                        ';

                        $othtotal += $othbal;
                        $gtotal += $othbal;
                    }
                }
            }
            else
            {
                $isEnroll = 1;
                if(db::table('schoolinfo')->first()->snr == 'dcc')
                {
                    $othclassid = array();
                    $oldclassid = array();
                    $othcount = 0;
                    $oldcount = 0;

                    $chrngsetup = db::table('chrngsetup')
                        ->where('deleted', 0)
                        ->where('groupname', 'OTH')
                        ->get();

                    // return $chrngsetup;

                    foreach($chrngsetup as $setup)
                    {   
                        array_push($othclassid, $setup->classid);
                        $othcount = count($chrngsetup);
                    }

                    $balforwardsetup = db::table('balforwardsetup')
                        ->first();

                    if($balforwardsetup)
                    {
                        $oldclassid = $balforwardsetup->classid;
                    } 

                    $studpaysched = db::table('studpayscheddetail')
                        ->select(db::raw('id, studid, semid, syid, classid, particulars, duedate, sum(amount) as totalamountdue, sum(amountpay) as totalpay, balance, SUM(balance) AS totalamount'))
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where(function($q) use($levelid){
                            if(CashierModel::getSemID() == 3)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                            else
                            {
                                if($levelid == 14 || $levelid == 15)
                                {   
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                }

                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }
                        })
                        ->where(function($q) use($othcount, $othclassid){
                            if($othcount > 0)
                            {
                                $q->whereNotIn('classid', $othclassid);
                            }
                        })
                        ->where('classid', '!=', $oldclassid)
						->where('balance', '>', 0)
                        ->groupBy('duedate')
                        ->get();

                    $scheditemcount = count($studpaysched) - 1;
                    $registrationamount = 0;

                    foreach($studpaysched as $paysched)
                    {   
                        if($paysched->duedate != null)
                        {
                            $dDate = date_create($paysched->duedate);
                            $monthdue = strtoupper(date_format($dDate, 'F'));
                            $monthno = date_format($dDate, 'n');
                        }

                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $dpsetup = db::table('dpsetup')
                                ->select(db::raw('*, sum(amount) as totalamount'))
                                ->where('levelid', $levelid)
                                ->where('deleted', 0)
                                ->where('syid', CashierModel::getSYID())
                                ->where('semid', CashierModel::getSemID())
                                // ->groupBy('classid')
                                ->first();

                            $_dpsetup = db::table('dpsetup')
                                ->select(db::raw('sum(amount) as totalamount, classid'))
                                ->where('levelid', $levelid)
                                ->where('deleted', 0)
                                ->where('syid', CashierModel::getSYID())
                                ->where('semid', CashierModel::getSemID())
                                ->groupBy('classid')
                                ->get();

                            $dparray = array();

                            foreach($_dpsetup as $dp)
                            {
                                array_push($dparray, $dp->classid);
                            }

                            $_psched = db::table('studpayscheddetail')
                                ->select(db::raw('sum(amountpay) as tamountpay'))
                                ->where('studid', $studid)
                                ->where('deleted', 0)
                                ->where('syid', CashierModel::getSYID())
                                ->where('semid', CashierModel::getSemID())
                                ->whereIn('classid', $dparray)
                                ->first();

                            $_tamountpay = $_psched->tamountpay;


                            if($paysched->duedate == null)
                            {

                                $registrationamount = $dpsetup->totalamount - $_tamountpay;
                                // return $registrationamount;
                                $_regbal = $registrationamount;

                                if($registrationamount < 0)
                                {
                                    $registrationamount = 0;
                                }

                                $reg .= '
                                    <tr data-id="'.$paysched->id.'" class-id="'.$paysched->classid.'"  data-kind="reg">
                                        <td class="paydesc"><span class="">'.strtoupper($paysched->particulars).'</span></td>
                                        <td class="text-right payval" data-value="'.$registrationamount.'">'.number_format($registrationamount, 2).'</td>
                                    </tr>
                                ';     

                                $regtotal += $registrationamount;
                                // $regremaining = $paysched->totalamountdue - $dpsetup->totalamount;
                                // return $regremaining;
                                // if($regremaining < 0)
                                // {
                                //     $regremaining = 0;
                                // }
                                // $gtotal += $regtotal;

                                if($paysched->totalamount == 0)
                                {
                                    $regremaining = 0;
                                }
                                else
                                {
                                    $regremaining = $paysched->totalamount; // - $_tamountpay;// - $dpsetup->totalamount;
                                }
                                // return $regremaining;
                                
                            }
                            else
                            {
                                $duecount += 1;

                                if($duecount == 1)
                                {
                                    $duedesc =  'Prelim Exam';
                                }
                                elseif($duecount == 2)
                                {
                                    $duedesc = 'Pre-Midterm Exam';
                                }
                                elseif($duecount == 3)
                                {
                                    $duedesc = 'Midterm Exam';
                                }
                                elseif($duecount == 4)
                                {
                                    $duedesc = 'Semi-Final Exam';
                                }
                                elseif($duecount == 5)
                                {
                                    $duedesc = 'Pre-Final Exam';
                                }
                                elseif($duecount == 6)
                                {
                                    $duedesc = 'Final Exam';
                                }

                                // return $regremaining;

                                if($regremaining != 0)
                                {
                                    if($_regbal > 0)
                                    {
                                        // $tuitotalamount = $regremaining + $paysched->totalamount;
                                        $tuitotalamount = $regremaining + $paysched->totalamount - $_regbal;
                                        // return $tuitotalamount;
                                        $tui .= '
                                            <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'" data-particulars="'.$duedesc.'">
                                                <td class="paydesc"><span class="">'.$duedesc.'</span></td>
                                                <td class="text-right payval" data-value="'.$tuitotalamount.'">'.number_format($tuitotalamount, 2).'</td>
                                            </tr>
                                        ';

                                        // echo $regremaining;

                                        $regremaining = 0;
                                        $tuitotal += $tuitotalamount;
                                    }
                                    else
                                    {
                                        $tuitotalamount = $paysched->totalamount;
                                        $tui .= '
                                            <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'" data-particulars="'.$duedesc.'">
                                                <td class="paydesc"><span class="">'.$duedesc.'</span></td>
                                                <td class="text-right payval" data-value="'.$tuitotalamount.'">'.number_format($tuitotalamount, 2).'</td>
                                            </tr>
                                        ';                                    

                                        $regremaining = 0;
                                        $tuitotal += $tuitotalamount;
                                    }
                                }
                                else
                                {
                                    // $tuitotalamount = $regremaining + $paysched

                                    $tui .= '
                                        <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'" data-particulars="'.$duedesc.'">
                                            <td class="paydesc"><span class="">'.$duedesc.'</span></td>
                                            <td class="text-right payval" data-value="'.$paysched->totalamount.'">'.number_format($paysched->totalamount, 2).'</td>
                                        </tr>
                                    ';    

                                    $tuitotal += $paysched->totalamount;
                                }

                                
                                // $gtotal += $tuitotal;
                            }
                        }
                        else
                        {
                            $dpsetup = db::table('dpsetup')
                                ->select(db::raw('*, sum(amount) as totalamount'))
                                ->where('levelid', $levelid)
                                ->where('deleted', 0)
                                ->where('syid', CashierModel::getSYID())
                                ->where(function($q) use($levelid){
                                    if(CashierModel::getSemID() == 3)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                    elseif($levelid == 14 || $levelid == 15)
                                    {
                                        if(CashierModel::shssetup() == 0)
                                        {
                                            $q->where('semid', CashierModel::getSemID());
                                        }
                                    }
                                })
                                // ->groupBy('classid')
                                ->first();

                            if($paysched->duedate == null)
                            {

                                $registrationamount = $dpsetup->totalamount - $paysched->totalpay;
                                $_regbal = $paysched->totalamount;

                                if($registrationamount < 0)
                                {
                                    $registrationamount = 0;
                                }

                                $reg .= '
                                    <tr data-id="'.$paysched->id.'" class-id="'.$paysched->classid.'"  data-kind="reg">
                                        <td class="paydesc"><span class="">'.$paysched->particulars.'</span></td>
                                        <td class="text-right payval" data-value="'.$registrationamount.'">'.number_format($registrationamount, 2).'</td>
                                    </tr>
                                ';     

                                $regtotal += $registrationamount;
                                $regremaining = $paysched->totalamountdue - $dpsetup->totalamount;

                                $_addonfromreg = $regremaining / $scheditemcount;
                                $_apushamount = $_regbal;

                                
                                for($i = $scheditemcount; $i != 0; $i-=1)
                                {
                                    if($_apushamount > $_addonfromreg)
                                    {
                                        array_push($_div, $_addonfromreg);
                                        $_apushamount -= $_addonfromreg;
                                    }
                                    else
                                    {
                                        array_push($_div, $_apushamount);
                                        $_apushamount = 0;        
                                    }
                                }
                            }
                            else
                            {
                                // return $_div;
                                if($levelid == 14 || $levelid == 15)                        
                                {
                                    $duecount += 1;

                                    if(CashierModel::getSemID() == 1)
                                    {
                                        if($duecount == 1)
                                        {
                                            $duedesc =  '1st - Preliminary';
                                            $_addonfromreg = $_div[3];
                                        }
                                        elseif($duecount == 2)
                                        {
                                            $duedesc = '1st - Mid-Terms';
                                            $_addonfromreg = $_div[2];
                                        }
                                        elseif($duecount == 3)
                                        {
                                            $duedesc = '1st - Semi-Final';
                                            $_addonfromreg = $_div[1];
                                        }
                                        elseif($duecount == 4)
                                        {
                                            $duedesc = '1st - Finals';
                                            $_addonfromreg = $_div[0];
                                        }
                                    }
                                    else
                                    {
                                        if($duecount == 1)
                                        {
                                            $duedesc = '2nd - Preliminary';
                                            $_addonfromreg = $_div[3];
                                        }
                                        elseif($duecount == 2)
                                        {
                                            $duedesc = '2nd - Mid-Terms';
                                            $_addonfromreg = $_div[2];
                                        }
                                        elseif($duecount == 3)
                                        {
                                            $duedesc = '2nd - Semi-Final';
                                            $_addonfromreg = $_div[1];
                                        }
                                        elseif($duecount == 4)
                                        {
                                            $duedesc = '2nd - Finals';
                                            $_addonfromreg = $_div[0];
                                        }
                                    }
                                }
                                else
                                {
                                    $duecount += 1;

                                    if($duecount == 1)
                                    {
                                        $duedesc =  'First Mid-Terms';
                                        $_addonfromreg = $_div[7];
                                    }
                                    elseif($duecount == 2)
                                    {
                                        $duedesc = 'First Finals';
                                        $_addonfromreg = $_div[6];
                                    }
                                    elseif($duecount == 3)
                                    {
                                        $duedesc = 'Second Mid-Terms';
                                        $_addonfromreg = $_div[5];
                                    }
                                    elseif($duecount == 4)
                                    {
                                        $duedesc = 'Second Finals';
                                        $_addonfromreg = $_div[4];
                                    }
                                    elseif($duecount == 5)
                                    {
                                        $duedesc = 'Third Mid-Terms';
                                        $_addonfromreg = $_div[3];
                                    }
                                    elseif($duecount == 6)
                                    {
                                        $duedesc = 'Third Finals';
                                        $_addonfromreg = $_div[2];
                                    }
                                    elseif($duecount == 7)
                                    {
                                        $duedesc = 'Fourth Mid-Terms';
                                        $_addonfromreg = $_div[1];
                                    }
                                    elseif($duecount == 8)
                                    {
                                        $duedesc = 'Fourth Finals';
                                        $_addonfromreg = $_div[0];
                                    }
                                }

                                $_regbal = $paysched->totalamount + $_addonfromreg;

                                $tui .= '
                                    <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'" data-particulars="'.$duedesc.'">
                                        <td class="paydesc"><span class="">'.$duedesc.'</span></td>
                                        <td class="text-right payval" data-value="'.$_regbal.'">'.number_format($_regbal, 2).'</td>
                                    </tr>
                                ';    

                                $tuitotal += $_regbal; 
                            }
                        }
                    }

                    

                    //OTH
                    $studpaysched = db::table('studpayscheddetail')
                        ->select(db::raw('id, studid, semid, syid, classid, particulars, duedate, amount, amountpay, balance, SUM(balance) AS totalamount'))
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->whereIn('classid', $othclassid)
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->groupBy('classid')
                        ->get();

                    foreach($studpaysched as $paysched)
                    {
                        $oth .= '
                            <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'">
                                <td class="paydesc"><span class="text-bold">'.$paysched->particulars.'</span></td>
                                <td class="text-right payval" data-value="'.$paysched->totalamount.'">'.number_format($paysched->totalamount, 2).'</td>
                            </tr>
                        ';    

                        $othtotal += $paysched->totalamount;
                    }

                    //OLD
                    $studpaysched = db::table('studpayscheddetail')
                        ->select(db::raw('id, studid, semid, syid, classid, particulars, duedate, amount, amountpay, balance, SUM(balance) AS totalamount'))
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('classid', $oldclassid)
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(CashierModel::shssetup() == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->groupBy('classid')
                        ->get();

                    foreach($studpaysched as $paysched)
                    {
                        $old .= '
                            <tr data-id="'.$paysched->id.'" data-kind="tui" data-due="'.$paysched->duedate.'">
                                <td class="paydesc"><span class="text-bold">'.$paysched->particulars.'</span></td>
                                <td class="text-right payval" data-value="'.$paysched->totalamount.'">'.number_format($paysched->totalamount, 2).'</td>
                            </tr>
                        ';    

                        $oldtotal += $paysched->totalamount;
                    }

                }
                else
                {
                    // MISC | REG
                    $miscinformation = db::table('studpayscheddetail')
                        ->select(db::raw('studpayscheddetail.*, itemized, groupname'))
                        ->leftjoin('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
                        ->where('studid', $studid)
                        ->where('studpayscheddetail.deleted', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where('groupname', 'MISC')
                        ->where(function($q) use($levelid){
                            if(CashierModel::getSemID() == 3)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                            elseif($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->groupBy('classid')
                        ->get();


                    foreach($miscinformation as $miscinfo)
                    {
                        if($miscinfo->itemized == 1)
                        {
                            $miscitemized = 1;

                            $itemized = db::table('studledgeritemized')  
                                ->select('studledgeritemized.id', 'studid', 'classid', 'items.description', 'totalamount', 'itemamount', 'itemid')
                                ->join('items', 'studledgeritemized.itemid', '=', 'items.id')
                                ->where('classificationid', $miscinfo->classid)
                                ->where('studid', $studid)
                                ->where('syid', CashierModel::getSYID())
                                ->where('studledgeritemized.deleted', 0)
                                ->where(function($q) use($levelid){
                                    if(CashierModel::getSemID() == 3)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                    elseif($levelid == 14 || $levelid == 15)
                                    {
                                        if(db::table('schoolinfo')->first()->shssetup == 0)
                                        {
                                            $q->where('semid', CashierModel::getSemID());
                                        }
                                    }

                                    if($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                })
                                ->get();

                            foreach($itemized as $item)
                            {
                                $bal = 0;

                                if($item->totalamount == null)
                                {
                                    $bal = $item->itemamount;
                                }
                                else
                                {
                                    $bal = $item->itemamount - $item->totalamount;   
                                }

                                $misc .='
                                    <tr data-id="'.$item->id.'" itemized-id="1" data-kind="misc" data-item="'.$item->itemid.'">
                                        <td class="paydesc">'.$item->description.'</span></td>
                                        <td class="text-right payval" data-value="'.$bal.'">'.number_format($bal, 2).'</td>
                                    </tr>
                                ';

                                // echo $bal . '; ';

                                $misctotal += $bal;

                                
                            }
                        }
                        else
                        {
                            $miscitemized = 0;
                        }
                    }
                    // MISC

                    // OTH
                    $bookclassid = db::table('bookentrysetup')->first()->classid;
                    $studpayscheddetail = db::table('studpayscheddetail')
                        ->select(db::raw('studpayscheddetail.*, itemized, groupname, sum(balance) as tbalance'))
                        ->leftjoin('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
                        ->where('studid', $studid)
                        ->where('studpayscheddetail.deleted', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where('groupname', 'OTH')
                        ->where('studpayscheddetail.deleted', 0)
                        ->where('studpayscheddetail.classid', '!=', $bookclassid)
                        ->where(function($q) use($levelid){
                            if(CashierModel::getSemID() == 3)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                            elseif($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->groupBy('classid')
                        ->get();    
                    
                    foreach($studpayscheddetail as $paysched)
                    {
                        // return $paysched->itemized;
                        if($paysched->itemized == 1)
                        {
                            $itemized = db::table('studledgeritemized')  
                                ->select('studledgeritemized.id', 'studid', 'classid', 'items.description', 'totalamount', 'itemamount', 'itemid')
                                ->join('items', 'studledgeritemized.itemid', '=', 'items.id')
                                ->where('classificationid', $paysched->classid)
                                ->where('studid', $studid)
                                ->where('syid', CashierModel::getSYID())
                                ->where('studledgeritemized.deleted', 0)
                                ->where(function($q) use($levelid){
                                    if(CashierModel::getSemID() == 3)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                    elseif($levelid == 14 || $levelid == 15)
                                    {
                                        if(db::table('schoolinfo')->first()->shssetup == 0)
                                        {
                                            $q->where('semid', CashierModel::getSemID());
                                        }
                                    }

                                    if($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                })
                                ->get();

                            foreach($itemized as $item)
                            {
                                $bal = 0;

                                if($item->totalamount == null)
                                {
                                    $bal = $item->itemamount;
                                }
                                else
                                {
                                    $bal = $item->itemamount - $item->totalamount;
                                }

                                $oth .= '
                                    <tr data-id="'.$item->id.'" itemized-id="1" data-kind="oth" data-classid="'.$paysched->classid.'" data-source="" data-item="'.$item->itemid.'">
                                        <td class="paydesc">'.$item->description.'</span></td>
                                        <td class="text-right payval" data-value="'.$bal.'">'.number_format($bal, 2).'</td>
                                    </tr>
                                ';

                                // $othbal += $bal;
                                $othtotal += $bal;
                            }
                        }
                        else
                        {
                            $othbal = $paysched->tbalance;

                            if($othbal < 0)
                            {
                                $othbal = 0;
                            }

                            $oth .= '
                                <tr data-id="'.$paysched->id.'" data-kind="oth" data-classid="'.$paysched->classid.'" data-source="header" data-toggle="tooltip" title="Monthly Due: '.$paysched->amount.'">
                                    <td class="paydesc">'.$paysched->particulars.'</span></td>
                                    <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
                                </tr>
                            ';
                            $othtotal += $othbal;
                        }
                    }
                    // OTH



                    $studpayscheddetail = db::table('studpayscheddetail')
                        ->select(db::raw('studpayscheddetail.*, itemized, groupname'))
                        ->leftjoin('chrngsetup', 'studpayscheddetail.classid', '=', 'chrngsetup.classid')
                        ->where('studid', $studid)
                        ->where('studpayscheddetail.deleted', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where('studpayscheddetail.deleted', 0)
                        ->where(function($q) use($levelid){
                            if(CashierModel::getSemID() == 3)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                            elseif($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->get();

                    foreach($studpayscheddetail as $paysched)
                    {
                        $dDate = date_create($paysched->duedate);
                        $monthdue = strtoupper(date_format($dDate, 'F'));
                        $monthno = date_format($dDate, 'n');

                        if($paysched->duedate == null || $paysched->duedate == '')
                        {
                            $monthdue = '';
                        }

                        if($paysched->groupname == 'reg')
                        {
                            $reg .= '
                                <tr>
                                    <td></td>
                                </tr>
                            ';
                        }
                        elseif($paysched->groupname == 'MISC')
                        {
                            if($miscitemized == 0)
                            {
                                $miscbal = $paysched->balance;

                                if($miscbal < 0)
                                {
                                    $miscbal = 0;
                                }

                                $misc .= '
                                    <tr data-id="'.$paysched->id.'" itemized-id="0" data-kind="misc">
                                        <td class="paydesc">'.$paysched->particulars.' <span class="text-bold">'.$monthdue.'</span></td>
                                        <td class="text-right payval" data-value="'.$miscbal.'">'.number_format($miscbal, 2).'</td>
                                    </tr>
                                ';  

                                $misctotal += $miscbal; 
                            }
                        }
                        elseif($paysched->groupname == 'TUI')
                        {
                            $tuibal = $paysched->balance;

                            if($tuibal < 0)
                            {
                                $tuibal = 0;
                            }

                            $tui .= '
                                <tr data-id="'.$paysched->id.'" data-kind="tui">
                                    <td class="paydesc">TUITION <span class="text-bold">'.$monthdue.'</span></td>
                                    <td class="text-right payval" data-value="'.$tuibal.'">'.number_format($tuibal, 2).'</td>
                                </tr>
                            ';   

                            $tuitotal += $tuibal;
                        }
                        elseif($paysched->classid == $bookclassid)
                        {
                            $othbal = $paysched->balance;
                            if($othbal < 0)
                            {
                                $othbal = 0;
                            }

                            $oth .= '
                                <tr data-id="'.$paysched->id.'" data-kind="oth" data-classid="'.$paysched->classid.'" data-source="" data-toggle="tooltip" title="Monthly Due: '.$paysched->amount.'">
                                    <td class="paydesc">'.$paysched->particulars.'</span></td>
                                    <td class="text-right payval" data-value="'.$othbal.'">'.number_format($othbal, 2).'</td>
                                </tr>
                            ';

                            $othtotal += $othbal;
                        }
                        
                        $balforwardclassid = db::table('balforwardsetup')
                            ->first()->classid;

                        $balforwarditems = db::table('studpayscheddetail')
                            // ->select('aaa')
                            ->where('studid', $studid)
                            ->where('deleted', 0)
                            ->where('syid', CashierModel::getSYID())
                            ->where('classid', $balforwardclassid)
                            ->where('id', $paysched->id)
                            ->where('studpayscheddetail.deleted', 0)
							// ->where('balance', '>', 0)
                            ->where(function($q) use($levelid){
                                if(CashierModel::getSemID() == 3)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                                elseif($levelid == 14 || $levelid == 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                }

                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            })
                            ->get();

                        if(count($balforwarditems) > 0)
                        {
                            foreach($balforwarditems as $oldacc)
                            {
                                $oldbal = $oldacc->balance;

                                if($oldbal < 0)
                                {
                                    $oldbal = 0;
                                }

                                if($oldacc->duedate == null || $oldacc->duedate == '')
                                {
                                    $monthdue = '';
                                }
                                else
                                {
                                    $dDate = date_create($oldacc->duedate);
                                    $monthdue = strtoupper(date_format($dDate, 'F'));
                                    $monthno = date_format($dDate, 'n');
                                }

                                $old .= '
                                    <tr data-id="'.$oldacc->id.'" data-kind="old">
                                        <td class="paydesc">'.$oldacc->particulars.' <span class="text-bold">'.$monthdue.'</span></td>
                                        <td class="text-right payval" data-value="'.$oldbal.'">'.number_format($oldbal, 2).'</td>
                                    </tr>
                                ';

                                $oldtotal += $oldbal;
                            }
                        }



                    }
                }

                $gtotal = $regtotal + $tuitotal + $misctotal + $othtotal + $oldtotal;

                if(db::table('schoolinfo')->first()->snr == 'dcc')
                {
                    $classitem = db::table('studpayscheddetail')
                        ->select('itemclassification.id', 'particulars as description', 'duedate')
                        ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                        ->where('studid', $studid)
                        ->where('studpayscheddetail.deleted', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where('semid', CashierModel::getSemID())
                        ->where('balance', '>', 0)
                        ->groupBy('duedate')
                        ->get();

                    $citem = '<option value="0">All</option>';
                    $classarray = array();

                    foreach($classitem as $class)
                    {
                        if(!in_array($class->description, $classarray))
                        {
                            array_push($classarray, $class->description);

                            $citem .='
                                <option value="'.$class->id.'" data-due="'.$class->duedate.'">'.$class->description.'</option>
                            ';
                        }
                    }
                }
                else
                {
                    $classitem = db::table('studpayscheddetail')
                        ->select('itemclassification.id', 'particulars as description', 'duedate')
                        ->join('itemclassification', 'studpayscheddetail.classid', '=', 'itemclassification.id')
                        ->where('studid', $studid)
                        ->where('studpayscheddetail.deleted', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where('semid', CashierModel::getSemID())
                        ->where('balance', '>', 0)
                        ->groupBy('duedate')
                        ->get();

                    $citem = '<option value="0">All</option>';
                    $classarray = array();

                    foreach($classitem as $class)
                    {
                        if(!in_array($class->description, $classarray))
                        {
                            array_push($classarray, $class->description);

                            $citem .='
                                <option value="'.$class->id.'" data-due="'.$class->duedate.'">'.$class->description.'</option>
                            ';
                        }
                    }
                }
            }

            $activesy = db::table('sy')->where('isactive', 1)->first()->id;
            $activesem = db::table('semester')->where('isactive', 1)->first()->id;
            $_oldtotal = 0;

            $ototal = db::table('studledger')
                ->select(db::raw('SUM(amount) - SUM(payment) AS balance'))
                ->where('studid', $studid)
                ->where('syid', '!=', $activesy)
                ->where(function($q) use($levelid, $activesem){
                    if($levelid == 14 || $levelid == 15)
                    {
                        if($activesem == 3)
                        {
                            $q->where('semid', '!=', $activesem);
                        }
                        else
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', '!=', $activesem);
                            }
                        }
                    }
                    elseif($levelid >= 17 && $levelid <= 21)
                    {
                        $q->where('semid', '!=', $activesem);
                    }
                    else
                    {
                        if($activesem == 3)
                        {
                            $q->where('ghssemid', '!=', 3);
                        }
                    }
                })
                ->where('deleted', 0)
                ->where('void', 0)
                ->first();

            if($ototal)
            {
                $_oldtotal = $ototal->balance;
            }

            // return $feesid;
            $data = array(
                'levelid' => $levelid,
                'feesid' => $feesid,
                'levelname' => $levelname,
                'studid' => $studid,
                'sid' => $sid,
                'name' => $name,
                'course' => $course,
                'strand'  => $strand,
                'info' => $info,
                'tui' => $tui,
                'oth' => $oth,
                'old' => $old,
                'misc' => $misc,
                'reg' => $reg,
                'tnum' => $tnum,
                'tuitotal' => number_format($tuitotal, 2),
                'othtotal' => number_format($othtotal, 2),
                'oldtotal' => number_format($oldtotal, 2),
                'misctotal' => number_format($misctotal, 2),
                'regtotal' => number_format($regtotal, 2),
                'gtotal' => number_format($gtotal, 2),
                'citem' => $citem,
                'acadprogid' => $acadprogid,
                'isEnroll' => $isEnroll,
                'feelist' => $feelist,
                'noloading' => $noloading,
                'ledgerinfo_list' => $ledgerinfo_list,
                'syid' => CashierModel::getSYID(),
                'semid' => CashierModel::getSemID(),
                'fees_bal' => number_format($fees_bal, 2),
                // 'oldtotal' => number_format($_oldtotal, 2)
            );

            echo json_encode($data);
        }
    }
	

    public function v2_pushtotrans(Request $request)
    {
        if($request->ajax())
        {

            $payschedid = $request->get('payschedid');
            $levelid = $request->get('levelid');
            $studid = $request->get('studid');
            $transno = $request->get('transno');
            $particulars = $request->get('particulars');
            $terminalno = $request->get('terminalno');
            $kind = $request->get('kind');
            $itemized = $request->get('itemized');
            $classid = $request->get('classid');
            $source = $request->get('source');
            $itemid = $request->get('itemid');

            $datadue = $request->get('datadue');
            $datavalue = $request->get('datavalue');
            $studstatus = $request->get('studstatus');

            // return $datavalue;
            
            // $stud = db::table('studinfo')
            //     ->where('id', $studid)
            //     ->first();

            // if($stud)
            // {
            //     $studstatus = $stud->studstatus;    
            // }

            $amount = 0;
            $duedate = '';
            $othmlist = '';
            $othtotal = 0;

            if($studstatus != 0)
            {
                $checksched = db::table('studpayscheddetail')
                    ->where('studid', $studid)
                    ->where('syid', CashierModel::getSYID())
                    ->where(function($q) use($levelid){
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', CashierModel::getSemID());
                        }
                        elseif($levelid == 14 || $levelid == 15)
                        {
                            if(CashierModel::getSemID() == 3)
                            {
                                $q->where('semid', 3);
                            }
                            else
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }
                        }
                        else
                        {
                            if(CashierModel::getSemID() == 3)
                            {
                                $q->where('semid', 3);
                            }
                            else
                            {
                                $q->where('semid','!=', 3);
                            }
                        }
                    })
                    ->where('classid', $classid)
                    ->where('deleted', 0)
                    // ->where('balance', '>', 0)
                    ->get();
            }
            else
            {
                $checksched = db::table('tuitiondetail')
                    ->select(db::raw('paymentsetupdetail.id, classificationid as classid, duedate, itemclassification.`description` AS particulars, tuitiondetail.amount, noofpayment'))
                    ->join('paymentsetup', 'tuitiondetail.pschemeid', '=', 'paymentsetup.id')
                    ->join('paymentsetupdetail', 'paymentsetup.id', '=', 'paymentsetupdetail.paymentid')
                    ->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
                    ->where('tuitiondetail.id', $payschedid)
                    ->where('tuitiondetail.deleted', 0)
                    ->where('paymentsetupdetail.deleted', 0)
                    ->get();
            }



            if(count($checksched) > 1 && $source == 'header' && $kind == 'oth')
            {
                $divAmount = 0;
                $curAmount = 0;
                $oth_loopval = 0;
                $loop = 0;

                foreach($checksched as $_sched)
                {
                    $month = date_format(date_create($_sched->duedate), 'F');
                    $bg = '';
                    $disabled = '';
                    

                    $_tno = db::table('chrngcashtrans')
                        ->where('transno', $transno)
                        ->where('payscheddetailid', $_sched->id)
                        ->where('deleted', 0)
                        ->count();

                    if($_tno > 0)
                    {
                        $bg = 'bg-primary';
                        $disabled = 'disbaled="true"';
                    }
                    else
                    {
                        $bg = '';
                        $disabled = '';
                    }

                    // echo $_sched->id . '<br>';
                    if($studstatus != 0)
                    {
                        $othmlist .='
                            <tr data-id="'.$_sched->id.'" style="cursor:pointer" data-source="detail" data-kind="oth" data-amount="'.$_sched->balance.'" data-classid="'.$_sched->classid.'" class="'.$bg.'">
                                <td class="paydesc">'.$_sched->particulars.' - '.$month.'</td>
                                <td class="text-right payval" data-value="'.$_sched->balance.'">'.number_format($_sched->balance, 2).'</td>
                            </tr>
                        ';

                        $othtotal += $_sched->balance;
                    }
                    else
                    {
                        $curAmount = $_sched->amount;

                        $loop += 1;

                        if($loop < 10)
                        {
                            $divAmount = $curAmount / $_sched->noofpayment;
                        }
                        else
                        {
                            $divAmount = $curAmount - $oth_loopval;
                        }



                        $_trx = db::table('chrngtrans')
                            ->select(db::raw('chrngtrans.id, ornum, chrngtrans.studid, chrngcashtrans.`amount`, duedate, classid, kind, itemid'))
                            ->join('chrngcashtrans', function($q){
                                $q->on('chrngtrans.transno', '=', 'chrngcashtrans.transno')
                                    ->on('chrngtrans.studid', '=', 'chrngcashtrans.studid');
                            })
                            ->where('chrngtrans.syid', CashierModel::getSYID())
                            ->where(function($q) use($levelid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(CashierModel::getSemID() == 3)
                                    {
                                        $q->where('chrngtrans.semid', 3);
                                    }
                                    else
                                    {
                                        if(db::table('schoolinfo')->first()->shssetup == 0)
                                        {
                                            $q->where('chrngtrans.semid', CashierModel::getSemID());
                                        }
                                    }
                                }
                                elseif($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('chrngtrans.semid', CashierModel::getSemID());
                                }
                                else
                                {
                                    if(CashierModel::getSemID() == 3)   
                                    {
                                        $q->where('chrngtrans.semid', 3);
                                    }
                                    else
                                    {
                                        $q->where('chrngtrans.semid', '!=', 3);   
                                    }
                                }
                            })
                            ->where('duedate', $_sched->duedate)
                            ->where('chrngtrans.studid', $studid)
                            ->where('classid', $_sched->classid)
                            ->first();

                        if($loop <= 10)
                        {
                            if($_trx)
                            {
                                $trx_amount = $divAmount - $_trx->amount;
                            }
                            else
                            {
                                $trx_amount = $divAmount;
                            }

                            $othmlist .='
                                <tr data-id="'.$_sched->id.'" style="cursor:pointer" data-source="detail" data-kind="oth" data-amount="'.$trx_amount.'" data-classid="'.$_sched->classid.'" data-due="'.$_sched->duedate.'" class="'.$bg.'">
                                    <td class="paydesc">'.$_sched->particulars.' - '.$month.'</td>
                                    <td class="text-right payval" data-value="'.$trx_amount.'">'.number_format($trx_amount, 2).'</td>
                                </tr>
                            ';

                            $oth_loopval += $divAmount;
                            $othtotal += $trx_amount;

                        }

                        
                        // $trx_amount = 0;
                    }
                }

                $data = array(
                    'status' => 2,
                    'particulars' => $particulars,
                    'othmlist' => $othmlist,
                    'totalamount' => number_format($othtotal, 2)
                );
            }
            else
            {
                $checkitem = db::table('chrngcashtrans')
                    ->where('deleted', 0)
                    ->where('transno', $transno)
                    ->where('payscheddetailid', $payschedid)
					->where('kind', $kind)
                    ->count();

                if($checkitem > 0 && $studstatus != 0)
                {
                    $data = array(
                        'status' => 0
                    );
                }
                else
                {
                    if($terminalno > 0)
                    {
                        if($kind == 'dp')
                        {
                            $dpsetup = db::table('dpsetup')
                                // ->select(db::raw('*, sum(amount) as totalamount'))
                                ->where('id', $payschedid)
                                ->where('syid', CashierModel::getSYID())
                                ->where(function($q) use($levelid){
                                    if(CashierModel::shssetup() == 0)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                })
                                ->first();

                            $amount = $dpsetup->amount;
                            $classid = $dpsetup->classid;
                            $duedate = '';

                        }
                        elseif($kind != 'item')
                        {
                            if($studstatus != 0)
                            {
                                if($kind == 'reg' || $kind == 'misc' || $kind == 'oth')
                                {
                                    if($itemized == 1)
                                    {
                                        $ledgeritemized = db::table('studledgeritemized')
                                            ->where('id', $payschedid)
                                            ->first();

                                        if($ledgeritemized->itemamount == null)
                                        {
                                            $amount = $ledgeritemized->itemamount;
                                        }
                                        else
                                        {
                                            $amount = $ledgeritemized->itemamount - $ledgeritemized->totalamount;
                                        }

                                        $classid = $ledgeritemized->classificationid;

                                        // return $payschedid;
                                        // return $ledgeritemized->itemamount .' - '. $ledgeritemized->totalamount;
                                    }
                                    else
                                    {
                                        $paysched = db::table('studpayscheddetail')
                                            ->where('id', $payschedid)
                                            ->where('studid', $studid)
                                            ->where('deleted', 0)
                                            ->where('syid', CashierModel::getSYID())
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
                                            ->first();

                                            $amount = $paysched->balance;
                                            $classid = $paysched->classid;
                                            $duedate = $paysched->duedate;
                                    }
                                }
                                elseif($kind == 'oth1') //not used
                                {

                                    $payschedclassid = db::table('studpayscheddetail')
                                        ->where('id', $payschedid)
                                        ->where('studid', $studid)
                                        ->first()->classid;


                                    $paysched = db::table('studpayscheddetail')
                                        ->select(db::raw('id, classid, SUM(balance) AS amount, duedate'))
                                        ->where('studid', $studid)
                                        ->where('deleted', 0)
                                        ->where('classid', $payschedclassid)
                                        ->where('syid', CashierModel::getSYID())
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
                                        ->first();

                                    // return $paysched;

                                        $amount = $paysched->amount;
                                        $classid = $paysched->classid;
                                        $duedate = $paysched->duedate;
                                }
                                elseif($kind == 'addon')
                                {

                                    // $payschedclassid = db::table('studpayscheddetail')
                                    //     ->where('id1', $payschedid)
                                    //     ->where('studid', $studid)
                                    //     ->first()->classificationid;

                                    $paysched = db::table('tuitiondetail')
                                        ->where('id', $payschedid)
                                        ->first();

                                    // return $paysched;

                                        $amount = $paysched->amount;
                                        $classid = $paysched->classificationid;
                                        $duedate = null;
                                }
                                else
                                {
                                    $paysched = db::table('studpayscheddetail')
                                        ->where('id', $payschedid)
                                        ->where('studid', $studid)
                                        ->where('deleted', 0)
                                        ->where('syid', CashierModel::getSYID())
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
                                        ->first();

                                        $amount = $paysched->balance;
                                        $classid = $paysched->classid;
                                        $duedate = $paysched->duedate;
                                }
                            }
                            else
                            {
                                $_feesid = $request->get('feesid');
                                if($kind == 'reg' || $kind == 'misc' || $kind == 'oth')
                                {
                                    if($itemized == 1)
                                    {
                                        $getitems = db::table('tuitionheader')
                                            ->select(db::raw('tuitionheader.id AS headerid, tuitiondetail.id AS tuitiondetailid, classificationid, items.id AS itemid, items.description, tuitionitems.amount'))
                                            ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                                            ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                                            ->join('items', 'tuitionitems.itemid', '=', 'items.id')
                                            ->where('tuitionheader.deleted', 0)
                                            ->where('tuitiondetail.deleted', 0)
                                            ->where('tuitionitems.deleted', 0)
                                            ->where('tuitionheader.id', $_feesid)
                                            ->where('itemid', $itemid)
                                            ->first();

                                        if($getitems)
                                        {
                                            // $amount = $getitems->amount;
                                            $amount = $datavalue;
                                            $classid = $getitems->classificationid;

                                        }
                                    }
                                    else
                                    {
                                        if($kind == 'oth' || $kind == 'misc')
                                        {
                                            $amount = $datavalue;
                                            $classid = $classid;
                                            $duedate = $datadue;
                                        }
                                    }
                                }
                                elseif($kind == 'tui' || $kind == 'old')
                                {
                                    $amount = $datavalue;
                                    $classid = $classid;
                                    $duedate = $datadue;
                                }
                            }
                        }
                        else
                        {

                            $classid = $request->get('classid');
                            $amount = str_replace(',', '', $request->get('amount'));
                        }

                        $cashid = db::table('chrngcashtrans')
                            ->insertGetId([
                                'transno' => $transno,
                                'payscheddetailid' => $payschedid,
                                'particulars' => $particulars,
                                'itemprice' => $amount,
                                'qty' => 1,
                                'amount' => $amount,
                                'duedate' => $duedate,
                                'deleted' => 0,
                                'studid' => $studid,
                                'semid' => CashierModel::getSemID(),
                                'syid' => CashierModel::getSYID(),
                                'terminalno' => $terminalno,
                                'transdone' => 0,
                                'transdatetime' => CashierModel::getServerDateTime(),
                                'createdby' => auth()->user()->id,
                                'createddatetime' => CashierModel::getServerDateTime(),
                                'classid' => $classid,
                                'kind' => $kind,
                                'itemid' => $itemid
                            ]);

                        $cashtrans = CashierModel::v2_orderlines($transno);

                        $data = array(
                            'line' => $cashtrans['line'],
                            'total' => number_format($cashtrans['total'], 2),
                            'status' => 1
                        );
                    }
                    else
                    {
                        $data = array(
                            'status' => 0
                        );
                    }
                }
            }
            
            echo json_encode($data);
        }
    }

    public function v2_pushtotransDCC(Request $request)
    {
        if($request->ajax())
        {
            $payschedid = $request->get('payschedid');
            $levelid = $request->get('levelid');
            $studid = $request->get('studid');
            $transno = $request->get('transno');
            $particulars = $request->get('particulars');
            $terminalno = $request->get('terminalno');
            $kind = $request->get('kind');
            $itemized = $request->get('itemized');
            $datavalue = $request->get('datavalue');

            $amount = 0;
            $duedate = '';
            $classid = 0;

            // return $kind;
            if($kind == 'dp')
            {
                $dpsetup = db::table('dpsetup')
                    ->select(db::raw('*, sum(amount) as totalamount'))
                    ->where('levelid', $levelid)
                    ->where('syid', CashierModel::getSYID())
                    ->where(function($q) use($levelid){
                        if(CashierModel::shssetup() == 0)
                        {
                            $q->where('semid', CashierModel::getSemID());
                        }
                    })
                    ->where('deleted', 0)
                    // ->groupBy('classid')
                    ->first();

                $amount = $dpsetup->totalamount;
                $classid = $dpsetup->classid;
                $duedate = '';

            }
            elseif($kind != 'item')
            {
                if($kind == 'reg' || $kind == 'misc')
                {
                    if($itemized == 1)
                    {
                        $ledgeritemized = db::table('studledgeritemized')
                            ->where('id', $payschedid)
                            ->first();

                        if($ledgeritemized->itemamount == null)
                        {
                            $amount = $ledgeritemized->itemamount;
                        }
                        else
                        {
                            $amount = $ledgeritemized->itemamount - $ledgeritemized->totalamount;
                        }

                        $classid = $ledgeritemized->classificationid;

                        // return $payschedid;
                        // return $ledgeritemized->itemamount .' - '. $ledgeritemized->totalamount;
                    }
                    else
                    {
                        $paysched = db::table('studpayscheddetail')
                            // ->select('aaa')
                            ->where('id', $payschedid)
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

                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            })
                            ->first();

                        $paysched = db::table('studpayscheddetail')
                            ->select(db::raw('*, SUM(amount) - SUM(amountpay) AS totalbalance'))
                            ->where('studid', $studid)
                            ->where('deleted', 0)
                            ->where('syid', CashierModel::getSYID())
                            ->where('duedate', $paysched->duedate)
                            ->where(function($q) use($levelid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                }

                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            })
                            ->first();

                        if($datavalue != $paysched->totalbalance)
                        {
                            $amount = $datavalue;
                        }
                        else
                        {
                            $amount = $paysched->totalbalance;
                        }

                        $classid = $paysched->classid;
                        $duedate = $paysched->duedate;
                    }
                }
                elseif($kind == 'oth')
                {

                    $payschedclassid = db::table('studpayscheddetail')
                        ->where('id', $payschedid)
                        ->where('studid', $studid)
                        ->first()->classid;


                    $paysched = db::table('studpayscheddetail')
                        ->select(db::raw('id, classid, SUM(balance) AS amount, duedate'))
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('classid', $payschedclassid)
                        ->where('syid', CashierModel::getSYID())
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->first();

                    // return $paysched;

                        $amount = $paysched->amount;
                        $classid = $paysched->classid;
                        $duedate = $paysched->duedate;
                }
                else
                {
                    $paysched = db::table('studpayscheddetail')
                        ->where('id', $payschedid)
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

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->first();

                        $paysched = db::table('studpayscheddetail')
                            ->select(db::raw('*, SUM(amount) - SUM(amountpay) AS totalbalance'))
                            ->where('studid', $studid)
                            ->where('deleted', 0)
                            ->where('syid', CashierModel::getSYID())
                            ->where('duedate', $paysched->duedate)
                            ->where(function($q) use($levelid){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                }

                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            })
                            ->first();

                        if($datavalue != $paysched->totalbalance)
                        {
                            $amount = $datavalue;
                        }
                        else
                        {
                            $amount = $paysched->totalbalance;
                        }

                        
                        $classid = $paysched->classid;
                        $duedate = $paysched->duedate;
                }
            }
            else
            {
                $classid = $request->get('classid');
                $amount = str_replace(',', '', $request->get('amount'));
            }


            $cashid = db::table('chrngcashtrans')
                ->insertGetId([
                    'transno' => $transno,
                    'payscheddetailid' => $payschedid,
                    'particulars' => $particulars,
                    'itemprice' => $amount,
                    'qty' => 1,
                    'amount' => $amount,
                    'duedate' => $duedate,
                    'deleted' => 0,
                    'studid' => $studid,
                    'semid' => CashierModel::getSemID(),
                    'syid' => CashierModel::getSYID(),
                    'terminalno' => $terminalno,
                    'transdone' => 0,
                    'transdatetime' => CashierModel::getServerDateTime(),
                    'createdby' => auth()->user()->id,
                    'createddatetime' => CashierModel::getServerDateTime(),
                    'classid' => $classid,
                    'kind' => $kind
                ]);

            $cashtrans = CashierModel::v2_orderlines($transno);

            $data = array(
              'line' => $cashtrans['line'],
              'total' => number_format($cashtrans['total'], 2)
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
            $checkOR = CashierModel::checkornum($curOR);

          
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
                'curOR' => $curOR,
                'checkOR' => $checkOR
            );

            echo json_encode($data);
        }
    }

    public function checkornum(Request $request)
    {
        if($request->ajax())
        {
            $ornum = $request->get('ornum');

            $checkOR = CashierModel::checkornum($ornum);

            return $checkOR;

        }
    }

    public function v2_performpay(Request $request)
    {
        if($request->ajax())
        {
            $transno = $request->get('transno');
            $studid = $request->get('studid');
            $ornum = $request->get('ornum');
            $transdate = $request->get('transdate');
            $payor = $request->get('payor');
            $paytype = $request->get('paymenttype');
            $amountdue = str_replace(',', '', $request->get('amountdue'));
            $terminalno = $request->get('terminalno');
            $olid = $request->get('olid');
            $distFlag = $request->get('distflag');
            $acadprogid = $request->get('acadprogid');

            $kind = '';
            $kinddesc ='';
            $ledgeramount = 0;
            $ledgerparticulars = '';
            $enrollid = 0;
            $levelid = 0;
            $isenroll = 0;
            $syid = CashierModel::getSYID();
            $semid = CashierModel::getSemID();

            $accountno = $request->get('accountno');
            $accountname = $request->get('accountname');
            $bankname = $request->get('bankname');
            $checkno = $request->get('checkno');
            $checkdate = $request->get('checkdate');
            $refno = $request->get('remittance');
            $oltransdate = '';


            if($olid > 0)
            {
                $ol = db::table('onlinePayments')
                    ->where('id', $olid)
                    ->first();

                if($ol)
                {
                    $refno = $ol->refNum;
                    $oltransdate = $ol->paymentDate;
                }
            }



            $dpitems = array();
            // return $distFlag;



            // return $amountdue;
            $studinfo = db::table('studinfo')
                ->select('sid', 'levelname', 'sectionname', 'studstatus', 'levelid')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->where('studinfo.id', $studid)
                ->first();

            // return $studinfo->levelid;
            if($studinfo)
            {
                $enrolledstud = db::table('enrolledstud')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('deleted', 0)
                    ->first();

                if($enrolledstud)
                {
                    $levelid = $enrolledstud->levelid;
                    $isenroll = 1;
                }
                else
                {
                    $enrolledstud = db::table('sh_enrolledstud')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where(function($q) use($semid){
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', $semid);
                            }
                        })
                        ->where('deleted', 0)
                        ->groupBy('studid')
                        ->first();
                    if($enrolledstud)
                    {
                        $levelid = $enrolledstud->levelid;
                        $isenroll = 1;
                    }
                    else
                    {
                        $enrolledstud = db::table('college_enrolledstud')
                            ->where('studid', $studid)
                            ->where('syid', $syid)
                            ->where('semid', $semid)
                            ->where('deleted', 0)
                            ->first();

                        if($enrolledstud)
                        {
                            $levelid = $enrolledstud->yearLevel;
                            $isenroll = 1;
                        }
                        else
                        {
                            $levelid =  $studinfo->levelid;
                            $isenroll = 0;
                        }
                    }
                }
                $levelid = $studinfo->levelid;

                if($isenroll == 1)
                {
                    if($levelid == 14 || $levelid == 15)
                    {
                        $enrollstud = db::table('sh_enrolledstud')
                            ->where('studid', $studid)
                            ->where('syid', CashierModel::getSYID())
                            ->where('deleted', 0)
                            ->where(function($q){
                                if(CashierModel::getSemID() == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                }
                            })
                            ->first();
                    }
                    elseif($levelid >= 17 && $levelid <= 21)
                    {
                        $enrollstud = db::table('college_enrolledstud')
                            ->where('studid', $studid)
                            ->where('syid', CashierModel::getSYID())
                            ->where('semid', CashierModel::getSemID())
                            ->where('deleted', 0)
                            ->first();   
                    }
                    else
                    {
                        $enrollstud = db::table('enrolledstud')
                            ->where('studid', $studid)
                            ->where('syid', CashierModel::getSYID())
                            ->where(function($q){
                                if(CashierModel::getSemID() == 3)
                                {
                                    $q->where('ghssemid', 3);
                                }
                                else
                                {
                                    $q->where('ghssemid', '!=', 3);
                                }
                            })
                            ->where('deleted', 0)
                            ->first();
                    }

                    if($enrollstud)
                    {
                        $enrollid = $enrollstud->id;
                    }
                }
            }

            $timenow = date_create(CashierModel::getServerDateTime());
            $timenow = date_format($timenow, 'H:i');

            if($studinfo)
            {
                $chrngtransid = db::table('chrngtrans')
                    ->insertGetId([
                        'ornum' => $ornum,
                        'transdate' => $transdate . ' ' . $timenow,
                        'totalamount' => $amountdue,
                        'amountpaid' => $amountdue,
                        'studid' => $studid,
                        'semid' => CashierModel::getSemID(),
                        'syid' => CashierModel::getSYID(),
                        'terminalno' => $terminalno,
                        'paytype' => $paytype,
                        'transby' => auth()->user()->id,
                        'studname' => $payor,
                        'sid' => $studinfo->sid,
                        'glevel' => $studinfo->levelname . ' - ' .  $studinfo->sectionname,
                        'transno' => $transno,
                        'progid' => $acadprogid,
                        'refno' => $refno,
                        'paytransdate' => $oltransdate,
                        'chequeno' => $checkno,
                        'chequedate' => $checkdate,
                        'bankname' => $bankname,
                        'accountno' => $accountno,
                        'accountname' => $accountname,
                        'refno' => $refno
                    ]);
            }
            else
            {
                $chrngtransid = db::table('chrngtrans')
                    ->insertGetId([
                        'ornum' => $ornum,
                        'transdate' => $transdate . ' ' . $timenow,
                        'totalamount' => $amountdue,
                        'amountpaid' => $amountdue,
                        'studid' => $studid,
                        'semid' => CashierModel::getSemID(),
                        'syid' => CashierModel::getSYID(),
                        'terminalno' => $terminalno,
                        'paytype' => $paytype,
                        'transby' => auth()->user()->id,
                        'studname' => $payor,
                        'sid' => '',
                        'glevel' => '',
                        'transno' => $transno,
                        'progid' => $acadprogid,
                        'chequeno' => $checkno,
                        'chequedate' => $checkdate,
                        'bankname' => $bankname,
                        'accountno' => $accountno,
                        'accountname' => $accountname,
                        'refno' => $refno
                    ]);  
            }

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

                $chrngtransdetailid = db::table('chrngtransdetail')
                    ->insertGetId([
                        'chrngtransid' => $chrngtransid,
                        'payschedid' => $cashtrans->payscheddetailid,
                        'items' => $cashtrans->particulars,
                        'itemprice' => $cashtrans->itemprice,
                        'amount' => $cashtrans->amount,
                        'qty' => $cashtrans->qty,
                        'classid' => $cashtrans->classid,
                        'itemkind' => $kind
                    ]);


                $chrngee_setup = db::table('chrng_earlyenrollmentsetup')
                    ->first();

                if($chrngee_setup)
                {
                    if($cashtrans->payscheddetailid == $chrngee_setup->itemid)
                    {
                        $ee_setup = db::table('early_enrollment_setup')
                            ->select(db::raw('early_enrollment_setup.id, syid, semid'))
                            ->join('gradelevel', 'early_enrollment_setup.acadprogid', '=', 'gradelevel.acadprogid')
                            ->where('early_enrollment_setup.deleted', 0)
                            ->where('gradelevel.id', $levelid)
                            ->first();

                        if($ee_setup)
                        {
                            db::table('chrng_earlyenrollmentpayment')
                                ->insert([
                                    'studid' => $studid,
                                    'chrngtransid' => $chrngtransid,
                                    'amount' => $amountdue,
                                    'syid' => $ee_setup->syid,
                                    'semid' => $ee_setup->semid,
                                    'ee_setupid' => $ee_setup->id
                                ]);

                            $_stud = db::table('studinfo')
                                ->where('id', $studid)
                                ->first();

                            if($_stud)
                            {
                                if($_stud->ismothernum == 1)
                                {
                                    $substr = $_stud->mcontactno;
                                }
                                else if($_stud->isfathernum == 1)
                                {
                                    $substr = $_stud->fcontactno;
                                }
                                else if($_stud->isguardannum)
                                {
                                    $substr = $_stud->gcontactno;   
                                }
                                else
                                {
                                    $substr = $_stud->contactno;    
                                }

                                
                                if(substr($substr, 0,1)=='0')
                                {
                                    $substr = '+63' . substr($substr, 1);
                                }

                                $abbr = db::table('schoolinfo')->first()->abbreviation;

                                $smsParent = db::table('smsbunker')
                                ->insert([
                                    'message' => $abbr . ' message: Your payment for EARLY REGISTRATION has been successfully processed',
                                    'receiver' => $substr,
                                    'smsstatus' => 0
                                ]);    
                            }
                        }
                    }
                }

                if($studinfo)
                {
                    if($isenroll == 1)
                    {
                        if($cashtrans->kind != 'item')
                        {
                            $payscheddetail = db::table('studpayscheddetail')
                                // ->where('id', $cashtrans->payscheddetailid)
                                ->where('studid', $studid)
                                ->where('deleted', 0)
                                ->where('classid', $cashtrans->classid)
                                ->where('syid', CashierModel::getSYID())
                                ->where(function($q) use($levelid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('semid', 3);
                                        }
                                        else
                                        {
                                            if(db::table('schoolinfo')->first()->shssetup == 0)
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
                                ->where('balance', '>', 0)
                                ->get();

                            foreach($payscheddetail as $scheddetail)
                            {
                                if($lineamount > 0)
                                {
                                    $bookclassid = db::table('bookentrysetup')->first()->classid;

                                    if($scheddetail->classid == $bookclassid)
                                    {
                                        $_bookpaysched = db::table('studpayscheddetail')
                                            ->where('id', $cashtrans->payscheddetailid)
                                            ->where('studid', $studid)
                                            ->where('syid', CashierModel::getSYID())
                                            ->where(function($q) use($levelid){
                                                if($levelid == 14 || $levelid == 15)
                                                {
                                                    if(CashierModel::getSemID() == 3)
                                                    {
                                                        $q->where('semid', 3);
                                                    }
                                                    else
                                                    {
                                                        if(db::table('schoolinfo')->first()->shssetup == 0)
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
                                            ->where('balance', '>', 0)
                                            ->first();

                                        if($lineamount > $_bookpaysched->balance)
                                        {
                                            db::table('studpayscheddetail')
                                                ->where('id', $_bookpaysched->id)
                                                ->update([
                                                    'amountpay' => $_bookpaysched->amountpay + $_bookpaysched->balance,
                                                    'balance' => 0,
                                                    'updatedby' => auth()->user()->id,
                                                    'updateddatetime' => CashierModel::getServerDateTime(),
                                                ]);

                                            // if($scheddetail->classid == 2)
                                            // {
                                            //     echo 'aaa; <br>';
                                            // }

                                            CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $_bookpaysched->id, $_bookpaysched->classid, $_bookpaysched->balance);
                                            CashierModel::procItemized($_bookpaysched->tuitiondetailid, $cashtrans->payscheddetailid, $_bookpaysched->balance, $_bookpaysched->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);

                                            $lineamount -= $_bookpaysched->balance;


                                        }
                                        else
                                        {
                                            db::table('studpayscheddetail')
                                                ->where('id', $_bookpaysched->id)
                                                ->update([
                                                    'amountpay' => $_bookpaysched->amountpay + $lineamount,
                                                    'balance' => $_bookpaysched->balance - $lineamount,
                                                    'updatedby' => auth()->user()->id,
                                                    'updateddatetime' => CashierModel::getServerDateTime(),
                                                ]);
                                         
                                            CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $_bookpaysched->id, $_bookpaysched->classid, $lineamount);
                                            CashierModel::procItemized($_bookpaysched->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $_bookpaysched->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);


                                            $lineamount = 0;      
                                        }
                                    }
                                    else
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

                                            // if($scheddetail->classid == 2)
                                            // {
                                            //     echo 'aaa; <br>';
                                            // }

                                            CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $scheddetail->balance);
                                            CashierModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $scheddetail->balance, $scheddetail->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);

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
                                         
                                            CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $lineamount);
                                            CashierModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $scheddetail->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);


                                            $lineamount = 0;      
                                        }
                                    }
                                }
                            }

                            if($distFlag == 1)
                            {
                                if($lineamount > 0)
                                {
                                    $payscheddetail = db::table('studpayscheddetail')
                                        ->where('studid', $studid)
                                        ->where('deleted', 0)
                                        ->where('balance', '>', 0)
                                        ->where('syid', CashierModel::getSYID())
                                        ->where(function($q) use($levelid){
                                            if($levelid == 14 || $levelid == 15)
                                            {
                                                if(CashierModel::getSemID() == 3)
                                                {
                                                    $q->where('semid', 3);
                                                }
                                                else
                                                {
                                                    if(db::table('schoolinfo')->first()->shssetup == 0)
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
                                        ->orderBy('duedate', 'ASC')
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

                                                CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $scheddetail->balance);
                                                CashierModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $scheddetail->balance, $scheddetail->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);

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

                                                CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $lineamount);
                                                CashierModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $scheddetail->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);

                                                $lineamount = 0;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        $bookclassid = db::table('bookentrysetup')->first()->classid;
                        if($cashtrans->kind == 'old')
                        {

                            $payscheddetail = db::table('studpayscheddetail')
                                ->where('studid', $studid)
                                ->where('deleted', 0)
                                ->where('classid', $cashtrans->classid)
                                ->where('syid', CashierModel::getSYID())
                                ->where(function($q) use($levelid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('semid', 3);
                                        }
                                        else
                                        {
                                            if(db::table('schoolinfo')->first()->shssetup == 0)
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

                                        // if($scheddetail->classid == 2)
                                        // {
                                        //     echo 'aaa; <br>';
                                        // }

                                        CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $scheddetail->balance);
                                        CashierModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $scheddetail->balance, $scheddetail->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);

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
                                     
                                        CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $lineamount);
                                        CashierModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $scheddetail->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);


                                        $lineamount = 0;      
                                    }
                                }
                            }
                        }
                        elseif($cashtrans->kind == 'oth' && $cashtrans->classid == $bookclassid)
                        {
                            $payscheddetail = db::table('studpayscheddetail')
                                ->where('studid', $studid)
                                ->where('deleted', 0)
                                ->where('classid', $bookclassid)
                                ->where('particulars', $cashtrans->particulars)
                                ->where('syid', CashierModel::getSYID())
                                ->where(function($q) use($levelid){
                                    if($levelid == 14 || $levelid == 15)
                                    {
                                        if(CashierModel::getSemID() == 3)
                                        {
                                            $q->where('semid', 3);
                                        }
                                        else
                                        {
                                            if(db::table('schoolinfo')->first()->shssetup == 0)
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

                                        // if($scheddetail->classid == 2)
                                        // {
                                        //     echo 'aaa; <br>';
                                        // }

                                        CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $scheddetail->balance);
                                        CashierModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $scheddetail->balance, $scheddetail->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);

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
                                     
                                        CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $lineamount);
                                        CashierModel::procItemized($scheddetail->tuitiondetailid, $cashtrans->payscheddetailid, $lineamount, $scheddetail->classid, $levelid, $chrngtransid, $chrngtransdetailid, $ornum, $studid, $kind);


                                        $lineamount = 0;      
                                    }
                                }
                            }
                        }
                    }
                }

                $syid = CashierModel::getSYID();
                $semid = CashierModel::getSemID();

                if($cashtrans->kind == 'reg' || $cashtrans->kind == 'dp')
                {
                    $dpsetup = db::table('dpsetup')
                        ->where('syid', $syid)
                        ->where(function($q) use($levelid, $semid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if($semid == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    if(CashierModel::shssetup() == 0)
                                    {
                                        $q->where('semid', $semid);
                                    }
                                }
                            }
                            elseif($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', $semid);
                            }
                            else
                            {
                                if($semid == 3)
                                {
                                    $q->where('semid', 3);
                                }
                                else
                                {
                                    $q->where('semid', '!=', 3);
                                }
                            }
                        })
                        ->where('levelid', $levelid)
                        ->where('deleted', 0)
                        ->get();

                    foreach($dpsetup as $dp)
                    {
                        array_push($dpitems, $dp->itemid);
                    }

                    $trxamount = $cashtrans->amount;

                    foreach($dpitems as $_items)
                    {
                        $tuition = db::table('tuitionheader')
                            ->select(db::raw('syid, semid, levelid, itemid,classificationid AS classid, items.`description`, tuitionitems.`amount`'))
                            ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                            ->join('tuitionitems', 'tuitiondetail.id', '=', 'tuitionitems.tuitiondetailid')
                            ->join('items', 'tuitionitems.itemid', '=', 'items.id')
                            ->where('levelid', $levelid)
                            ->where('syid', $syid)
                            ->where(function($q) use($semid, $levelid){
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
                            ->where('grantee', 1)
                            ->where('tuitionheader.deleted', 0)
                            ->where('tuitiondetail.deleted', 0)
                            ->where('tuitionitems.deleted', 0)
                            ->where('itemid', $_items)
                            ->first();

                        if($tuition)
                        {
                            if($trxamount > 0)
                            {
                                if($trxamount > $tuition->amount)
                                {
                                    db::table('chrngtransitems')
                                        ->insert([
                                            'chrngtransid' => $chrngtransid,
                                            'ornum' => $ornum,
                                            'itemid' => $_items,
                                            'classid' => $tuition->classid,
                                            'amount' => $tuition->amount,
                                            'studid' => $studid,
                                            'syid' => $syid,
                                            'semid' => $semid,
                                            'createddatetime' => CashierModel::getServerDateTime()
                                        ]);

                                    $trxamount -= $tuition->amount;
                                }
                                else
                                {
                                    db::table('chrngtransitems')
                                        ->insert([
                                            'chrngtransid' => $chrngtransid,
                                            'ornum' => $ornum,
                                            'itemid' => $_items,
                                            'classid' => $tuition->classid,
                                            'amount' => $trxamount,
                                            'studid' => $studid,
                                            'syid' => $syid,
                                            'semid' => $semid,
                                            'createddatetime' => CashierModel::getServerDateTime()
                                        ]);                       

                                    $trxamount = 0;             
                                }
                            }
                        }
                    }
                }
            } 

            $transkind = db::table('chrngcashtrans')
                ->where('transno', $transno)
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->groupBy('kind')
                ->get();

            foreach($transkind as $particulars)
            {
                if($cashtrans->kind != null)
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
                }
            }

            if($ledgerparticulars != '')
            {
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'enrollid' => $enrollid,
                        'semid' => CashierModel::getSemID(),
                        'syid' => CashierModel::getSYID(),
                        'particulars' =>'PAYMENT FOR ' . $ledgerparticulars . ' - OR: ' . $ornum . ' - ' . $paytype,
                        'payment' => $ledgeramount,
                        'ornum' => $ornum,
                        'paytype' => $paytype,
                        'transid' => $chrngtransid,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => $transdate . ' ' . $timenow,
                        'deleted' => 0
                    ]);
            }

            // CashierModel::insertOR($ornum + 1, $terminalno, $paytype);

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
                    'oltrans' => 0
                  ]);
            }

            db::table('onlinepayments')
                ->where('id', $olid)
                ->update([
                    'isapproved' => 5,
                    'updateddatetime' => CashierModel::getServerDateTime(),
                    'updatedby' => auth()->user()->id
                ]);

            //update ORNUM

            CashierModel::insertOR($ornum + 1, $terminalno, $paytype);

            $data = array(
                'transid' => $chrngtransid
            );

            echo json_encode($data);
        }
    }
	
    public function v2_performpayDCC(Request $request)
    {
        if($request->ajax())
        {
            $transno = $request->get('transno');
            $studid = $request->get('studid');
            $ornum = $request->get('ornum');
            $transdate = $request->get('transdate');
            $payor = $request->get('payor');
            $paytype = $request->get('paymenttype');
            $amountdue = str_replace(',', '', $request->get('amountdue'));
            $terminalno = $request->get('terminalno');
            $olid = $request->get('olid');
            $kind = '';
            $kinddesc ='';
            $ledgeramount = 0;
            $ledgerparticulars = '';
            $enrollid = 0;
            $levelid = 0; 
            $othcount = 0;

            // return $amountdue;

            $studinfo = db::table('studinfo')
                ->select('sid', 'levelname', 'sectionname', 'studstatus', 'levelid')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->where('studinfo.id', $studid)
                ->first();

            // return $studinfo->levelid;

            if($studinfo)
            {
                $levelid = $studinfo->levelid;
            }

            if($studinfo)
            {
                if($studinfo->studstatus > 0)
                {
                    if($studinfo->levelid == 14 || $studinfo->levelid == 15)
                    {
                        $enrollstud = db::table('sh_enrolledstud')
                            ->where('studid', $studid)
                            ->where('syid', CashierModel::getSYID())
                            ->where(function($q){
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());        
                                }
                            })
                            ->where('deleted', 0)
                            ->first();
                    }
                    elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
                    {
                        $enrollstud = db::table('college_enrolledstud')
                            ->where('studid', $studid)
                            ->where('syid', CashierModel::getSYID())
                            ->where('semid', CashierModel::getSemID())
                            ->where('deleted', 0)
                            ->first();   
                    }
                    else
                    {
                        $enrollstud = db::table('enrolledstud')
                            ->where('studid', $studid)
                            ->where('syid', CashierModel::getSYID())
                            ->where('deleted', 0)
                            ->first();
                    }

                    if($enrollstud)
                    {
                        $enrollid = $enrollstud->id;
                    }
                }
            }

            $othclassid = array();
            $oldclassid = 0;

            $chrngsetup = db::table('chrngsetup')
                ->where('deleted', 0)
                ->where('groupname', 'OTH')
                ->get();

            foreach($chrngsetup as $setup)
            {
                array_push($othclassid, $setup->classid);
                $othcount = count($chrngsetup);
            }

            $balforwardsetup = db::table('balforwardsetup')
                ->first();

            if($balforwardsetup)
            {
                $oldclassid = $balforwardsetup->classid;
            }

            $dpclassid = db::table('dpsetup')
                ->select(db::raw('levelid, classid, sum(amount)'))
                ->where('levelid', $levelid)
                ->where('deleted', 0)
                ->first()->classid;

            $timenow = date_create(CashierModel::getServerDateTime());
            $timenow = date_format($timenow, 'h:i');

            if($studinfo)
            {
                $chrngtransid = db::table('chrngtrans')
                    ->insertGetId([
                        'ornum' => $ornum,
                        'transdate' => $transdate . ' ' . $timenow,
                        'totalamount' => $amountdue,
                        'amountpaid' => $amountdue,
                        'studid' => $studid,
                        'semid' => CashierModel::getSemID(),
                        'syid' => CashierModel::getSemID(),
                        'terminalno' => $terminalno,
                        'paytype' => $paytype,
                        'transby' => auth()->user()->id,
                        'studname' => $payor,
                        'sid' => $studinfo->sid,
                        'glevel' => $studinfo->levelname . ' - ' .  $studinfo->sectionname
                    ]);
            }
            else
            {
                $chrngtransid = db::table('chrngtrans')
                    ->insertGetId([
                        'ornum' => $ornum,
                        'transdate' => $transdate . ' ' . $timenow,
                        'totalamount' => $amountdue,
                        'amountpaid' => $amountdue,
                        'studid' => $studid,
                        'semid' => CashierModel::getSemID(),
                        'syid' => CashierModel::getSemID(),
                        'terminalno' => $terminalno,
                        'paytype' => $paytype,
                        'transby' => auth()->user()->id,
                        'studname' => $payor,
                        'sid' => '',
                        'glevel' => ''
                    ]);   
            }

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
                    $kinddesc = $cashtrans->kind;
                    $ledgeramount += $cashtrans->amount;

                }
                else
                {
                    $kind = 1;
                }

                $chrngtransdetailid = db::table('chrngtransdetail')
                    ->insertGetId([
                        'chrngtransid' => $chrngtransid,
                        'payschedid' => $cashtrans->payscheddetailid,
                        'items' => $cashtrans->particulars,
                        'itemprice' => $cashtrans->itemprice,
                        'amount' => $cashtrans->amount,
                        'qty' => $cashtrans->qty,
                        'classid' => $cashtrans->classid,
                        'itemkind' => $kind
                    ]);

                $chrngee_setup = db::table('chrng_earlyenrollmentsetup')
                    ->first();

                if($chrngee_setup)
                {
                    if($cashtrans->payscheddetailid == $chrngee_setup->itemid)
                    {
                        $ee_setup = db::table('early_enrollment_setup')
                            ->select(db::raw('early_enrollment_setup.id, syid, semid'))
                            ->join('gradelevel', 'early_enrollment_setup.acadprogid', '=', 'gradelevel.acadprogid')
                            ->where('early_enrollment_setup.deleted', 0)
                            ->where('gradelevel.id', $levelid)
                            ->first();

                        if($ee_setup)
                        {
                            db::table('chrng_earlyenrollmentpayment')
                                ->insert([
                                    'studid' => $studid,
                                    'chrngtransid' => $chrngtransid,
                                    'amount' => $amountdue,
                                    'syid' => $ee_setup->syid,
                                    'semid' => $ee_setup->semid,
                                    'ee_setupid' => $ee_setup->id
                                ]);

                            $_stud = db::table('studinfo')
                                ->where('id', $studid)
                                ->first();

                            if($_stud)
                            {
                                if($_stud->ismothernum == 1)
                                {
                                    $substr = $_stud->mcontactno;
                                }
                                else if($_stud->isfathernum == 1)
                                {
                                    $substr = $_stud->fcontactno;
                                }
                                else if($_stud->isguardannum)
                                {
                                    $substr = $_stud->gcontactno;   
                                }
                                else
                                {
                                    $substr = $_stud->contactno;    
                                }

                                
                                if(substr($substr, 0,1)=='0')
                                {
                                    $substr = '+63' . substr($substr, 1);
                                }

                                $abbr = db::table('schoolinfo')->first()->abbreviation;

                                $smsParent = db::table('smsbunker')
                                ->insert([
                                    'message' => $abbr . ' message: Your payment for EARLY REGISTRATION has been successfully processed',
                                    'receiver' => $substr,
                                    'smsstatus' => 0
                                ]);    
                            }
                        }
                    }
                }

                if($kinddesc != 'OTH' && $kinddesc != 'OLD')
                {
                    $payscheddetail = db::table('studpayscheddetail')
                        // ->select('aaa')
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('duedate', $cashtrans->duedate)
                        ->where('balance', '>', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->where(function($q) use($othcount, $othclassid){
                            if($othcount > 0)
                            {
                                $q->whereNotIn('classid', $othclassid);
                            }
                        })
                        ->where(function($q) use($othclassid){
                            if($othclassid != null)
                            {
                                $q->where('classid', '!=', $othclassid);
                            }
                        })
                        ->get();
                }
                elseif($kinddesc == 'OTH')
                {
                    $payscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('duedate', $cashtrans->duedate)
                        ->where('balance', '>', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->where(function($q) use($othcount, $othclassid){
                            if($othcount > 0)
                            {
                                $q->whereIn('classid', $othclassid);
                            }
                        })
                        ->get();   
                }
                elseif($kinddesc == 'OLD')
                {
                    $payscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        ->where('duedate', $cashtrans->duedate)
                        ->where('balance', '>', 0)
                        ->where('syid', CashierModel::getSYID())
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->where('classid', $oldclassid)
                        ->get();   
                }

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

                            CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $scheddetail->balance);

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

                            CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $lineamount);

                            $lineamount -= $scheddetail->balance;      
                        }
                    }
                }

                if($lineamount > 0)
                {
                    $payscheddetail = db::table('studpayscheddetail')
                        ->where('studid', $studid)
                        ->where('deleted', 0)
                        // ->where('classid', $dpclassid)
                        ->where('balance', '>', 0)
                        ->where(function($q) use($levelid){
                            if($levelid == 14 || $levelid == 15)
                            {
                                if(db::table('schoolinfo')->first()->shssetup == 0)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            }

                            if($levelid >= 17 && $levelid <= 21)
                            {
                                $q->where('semid', CashierModel::getSemID());
                            }
                        })
                        ->orderBy('duedate', 'ASC')
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

                                CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $scheddetail->balance);

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
                                
                                CashierModel::chrngdistlogs($studid, $chrngtransid, $chrngtransdetailid, $scheddetail->id, $scheddetail->classid, $lineamount);

                                $lineamount -= $scheddetail->balance;      
                            }
                        }
                    }
                }

                $isitemized = db::table('chrngsetup')
                    ->where('classid', $cashtrans->classid)
                    ->first();

                if($isitemized)
                {
                    if($isitemized->itemized == 1)
                    {
                        $ledgeritemized = db::table('studledgeritemized')
                            ->where('id', $cashtrans->payscheddetailid)  
                            ->first();
                    }
                    else
                    {
                        $ledgeritemized = db::table('studledgeritemized')
                            ->where('classificationid', $cashtrans->classid)  
                            ->first();

                    }

                    if($ledgeritemized)
                    {
                        if($ledgeritemized == null)
                        {
                            db::table('studledgeritemized')
                                ->where('id', $cashtrans->payscheddetailid)
                                ->update([
                                    'totalamount' => $cashtrans->amount,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => CashierModel::getServerDateTime(),
                                ]);
                        }
                        else
                        {
                            db::table('studledgeritemized')
                                ->where('id', $cashtrans->payscheddetailid)
                                ->update([
                                    'totalamount' => $ledgeritemized->totalamount + $cashtrans->amount,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => CashierModel::getServerDateTime(),
                                ]);
                        }


                        db::table('chrngtransitems')
                            ->insert([
                                'chrngtransid' => $chrngtransid,
                                'chrngtransdetailid' => $chrngtransdetailid,
                                'ornum' => $ornum,
                                'itemid' => $ledgeritemized->itemid,
                                'classid' => $cashtrans->classid,
                                'amount' => $cashtrans->amount,
                                'studid' => $studid,
                                'syid' => CashierModel::getSYID(),
                                'semid' => CashierModel::getSemID(),
                                'kind' => $cashtrans->kind,
                                'createddatetime' => CashierModel::getServerDateTime(),
                                'createdby' => auth()->user()->id,
                            ]);

                    }
                }
                
            } 

            $transkind = db::table('chrngcashtrans')
                ->where('transno', $transno)
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->groupBy('kind')
                ->get();

            foreach($transkind as $particulars)
            {
                
                // if($particulars->kind == 'reg')
                // {
                //     $kinddesc = 'REGISTRATION';
                // }
                // elseif($particulars->kind == 'misc')
                // {
                //     $kinddesc = 'MISCELLANEOUS';   
                // }
                // elseif($particulars->kind == 'tui')
                // {
                //     $kinddesc = 'TUITION';   
                // }
                // elseif($particulars->kind == 'oth')
                // {
                //     $kinddesc = 'OTHER FEES/BOOKS';   
                // }
                // elseif($particulars->kind == 'old')
                // {
                //     $kinddesc = 'OLD ACCOUNT';   
                // }

                if($ledgerparticulars == '')
                {
                    $ledgerparticulars = $particulars->particulars;
                }
                else
                {
                    $ledgerparticulars .= '/' . $particulars->particulars;
                }
                
            }

            if($ledgerparticulars != '')
            {
                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'enrollid' => $enrollid,
                        'semid' => CashierModel::getSemID(),
                        'syid' => CashierModel::getsyid(),
                        'particulars' =>'PAYMENT FOR ' . $ledgerparticulars . ' - OR: ' . $ornum . ' - ' . $paytype,
                        'payment' => $ledgeramount,
                        'ornum' => $ornum,
                        'paytype' => $paytype,
                        'transid' => $chrngtransid,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => $transdate . ' ' . $timenow,
                        'deleted' => 0
                    ]);
            }

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
                    'oltrans' => 0
                  ]);
            }

            db::table('onlinepayments')
                ->where('id', $olid)
                ->update([
                    'isapproved' => 5,
                    'updateddatetime' => CashierModel::getServerDateTime(),
                    'updatedby' => auth()->user()->id
                ]);

            //update ORNUM

            CashierModel::insertOR($ornum + 1, $terminalno, $paytype);

            $data = array(
                'transid' => $chrngtransid
            );

            echo json_encode($data);
        }
    }

    public function v2_viewtrans(Request $request)
    {
        if($request->ajax())
        {
            $transid = $request->get('transid');
            $studid = $request->get('studid');

            $idno = '';
            $studname = '';
            $gradelevel = '';
            $ornum = '';
            $terminalno = '';
            $cashier = '';
            $paytype = '';

            $totalamount = 0;
            $list = '';
            $cancelled = 0;

            $chrngtrans = db::table('chrngtrans')
                ->select('chrngtrans.id', 'sid', 'studname', 'glevel', 'amountpaid', 'amount', 'qty', 'items', 'transdate', 'ornum', 'terminalno', 'itemprice', 'cancelled', 'transby', 'paytype')
                ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                ->where('chrngtrans.id', $transid)
                ->get();

            if(count($chrngtrans) > 0)
            {
                $user = db::table('users')
                    ->where('id', $chrngtrans[0]->transby)
                    ->first();

                $cashier = $user->name;

                foreach($chrngtrans as $trans)
                {
                    $transdate = date_create($trans->transdate);
                    $transdate = date_format($transdate, 'm-d-Y');

                    $idno = $trans->sid;
                    $studname = $trans->studname;
                    $gradelevel = $trans->glevel;
                    $ornum = $trans->ornum;
                    $terminalno = $trans->terminalno;
                    $cancelled = $trans->cancelled;
                    $paytype = $trans->paytype;

                    $totalamount += $trans->amount;

                    $list .='
                        <tr>
                            <td>'.$trans->items.'</td>
                            <td class="text-right">'.number_format($trans->amount,2).'</td>
                        </tr>
                    ';
                }

                $list .='
                    <tr>
                        <td class="text-bold text-right">TOTAL</td>
                        <td class="text-bold text-right">'.number_format($totalamount,2).'</td>
                    </tr>
                ';
            }

            $data = array(
                'list' =>$list,
                'ornum' => $ornum,
                'terminalno' => $terminalno,
                'studname' => $studname,
                'gradelevel' => $gradelevel,
                'idno' => $idno,
                'transdate' => $transdate,
                'cancelled' => $cancelled,
                'cashier' => $cashier,
                'totalamount' => $totalamount,
                'paytype' => $paytype
            );

            echo json_encode($data);
        }
    }

    public function v2_removeorderline(Request $request)
    {
        if($request->ajax())
        {
            $dataid = $request->get('dataid');
            $transno = $request->get('transno');

            $chrngcashtrans = db::table('chrngcashtrans')
                ->where('id', $dataid)
                ->first();

            $particulars = $chrngcashtrans->particulars;

            db::table('chrngcashtrans')
                ->where('id', $dataid)
                ->update([
                    'deleted' => 1
                ]);

            // return $transno;
            $cashtrans = CashierModel::v2_orderlines($transno);

            $data = array(
              'line' => $cashtrans['line'],
              'total' => number_format($cashtrans['total'], 2),
              'schedid' => $chrngcashtrans->payscheddetailid,
              'kind' => $chrngcashtrans->kind,
              'particulars' => $particulars
            );

            echo json_encode($data);


        }
    }

    public function v2_updatelineamount(Request $request)
    {
        if($request->ajax())
        {
            $dataid = $request->get('dataid');
            $amount = str_replace(',', '', $request->get('amount'));
            $transno =$request->get('transno');

            db::table('chrngcashtrans')
                ->where('id', $dataid)
                ->update([
                    'amount' => $amount
                ]);

            $cashtrans = CashierModel::v2_orderlines($transno);

            $data = array(
              'line' => $cashtrans['line'],
              'total' => number_format($cashtrans['total'], 2)
            );

            echo json_encode($data);
        }
    }

    public function v2_loaditems(Request $request)
    {
        if($request->ajax())
        {
            $itemname = $request->get('filter');

            $items = db::table('items')
                ->where(function($q) use($itemname){
                        $q->where('description', 'like', '%'. $itemname . '%')
                            ->orWhere('itemcode', 'like', '%'. $itemname . '%');
                })
                ->where('deleted', 0)
                ->where(function($q){
                        $q->where('isreceivable', 0)
                            ->where('isdp', 0)
                            ->where('isexpense', 0)
                            ->orWhere('cash', 1);
                })
                ->orderBy('description', 'ASC')
                ->get();   
      
        
            $list = '';
            
            foreach($items as $item)
            {
                $list .='
                  <tr class="" class-id="'.$item->classid.'" data-id="'.$item->id.'">
                    <td>'.$item->itemcode.'</td>
                    <td class="paydesc">'.$item->description.'</td>
                    <td class="amount text-right payval">'.number_format($item->amount, 2).'</td>
                  </tr>
                ';
            }
        }

        $data = array(
            'list' => $list
        );

        echo json_encode($data);
    
    }

    public function v2_studledger(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $info = $request->get('info');

            if($info != '')
            {
                $info = explode(',', $info);

                $syID = $info[0];
                $semid = $info[1];
            }
            else
            {
                $syID = CashierModel::getSYID();
                $semid = CashierModel::getSemID();    
            }
            

            $studinfo = db::table('studinfo')
                ->select('studinfo.id', 'sid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 'sectionname', 'levelid')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->where('studinfo.id', $studid)
                ->first();

            $enrolled = db::table('enrolledstud')
                ->select(db::raw('levelname, sectionname, enrolledstud.levelid'))
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                ->where('studid', $studid)
                ->where('syid', $syID)
                ->where(function($q) use($semid){
                    if($semid == 3)
                    {
                        $q->where('ghssemid', 3);
                    }
                    else
                    {
                        $q->where('ghssemid', '!=', 3);   
                    }
                })
                ->first();

            if(!$enrolled)
            {
                $enrolled = db::table('sh_enrolledstud')
                    ->select(db::raw('levelname, sectionname, sh_enrolledstud.levelid'))
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                    ->where('studid', $studid)
                    ->where('syid', $syID)
                    ->where(function($q) use($semid){
                        if($semid == 3)
                        {
                            $q->where('semid', 3);
                        }
                        else
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', '!=', $semid);   
                            }
                        }
                    })
                    ->first();

                if(!$enrolled)
                {
                    $enrolled = db::table('college_enrolledstud')
                        ->select(db::raw('levelname, sectionname, college_enrolledstud.yearLevel as levelid'))
                        ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                        ->join('sections', 'college_enrolledstud.sectionid', '=', 'sections.id')
                        ->where('studid', $studid)
                        ->where('syid', $syID)
                        ->where('semid', $semid)
                        ->first();   
                }
            }


            $studName = strtoupper($studinfo->lastname. ', '. $studinfo->firstname . ' ' . $studinfo->middlename . ' '. $studinfo->suffix);
            // return $studName;
            $sid = $studinfo->sid;
            $gradesection = $studinfo->levelname . ' | ' . $studinfo->sectionname;
      
            if($studinfo->levelid == 14 || $studinfo->levelid == 15)
            {
                $ledger = db::table('studledger')
                    ->where('studid', $studid)
                    ->where('syid', $syID)
                    ->where('deleted', 0)
                    ->where(function($q) use($semid){
                        if($semid == 3)
                        {
                            $q->where('semid', $semid);
                        }
                        else
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', $semid);
                            }
                        }
                    })
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
                    ->where(function($q) use($semid){
                        if($semid == 3)
                        {
                            $q->where('semid', 3);
                        }
                        else
                        {
                            $q->where('semid', '!=', 3);
                        }
                    })
                    ->where('deleted', 0)
                    ->orderBy('createddatetime', 'asc')
                    ->get();

                // return $ledger;
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
                    // echo 'trans: ' . $led->transid . '<br>';
                    if($led->transid != null)
                    {
                        // echo $led->id . '<br>';
                        $output .='
                            <tr class="pointer" data-id="'.$led->id.'" trans-id="'.$led->transid.'">
                                <td class="" style="border-right: solid 1px #dee2e6; border-left: solid 1px #dee2e6;">' .$lDate.' </td>
                                <td style="border-right: solid 1px #dee2e6;">'.$led->particulars.'</td>
                                <td class="text-right" style="border-right: solid 1px #dee2e6;">'.$amount.'</td>
                                <td class="text-right" style="border-right: solid 1px #dee2e6;">'.$payment.'</td>
                                <td class="text-right" style="border-right: solid 1px #dee2e6; border-left: solid 1px">'.number_format($bal, 2).'</td>
                            </tr>
                        ';
                    }
                    else
                    {
                        $output .='
                            <tr class="" trans-id="0">
                                <td class="" style="border-right: solid 1px #dee2e6; border-left: solid 1px #dee2e6;">' .$lDate.' </td>
                                <td style="border-right: solid 1px #dee2e6;">'.$led->particulars.'</td>
                                <td class="text-right" style="border-right: solid 1px #dee2e6;">'.$amount.'</td>
                                <td class="text-right" style="border-right: solid 1px #dee2e6;">'.$payment.'</td>
                                <td class="text-right" style="border-right: solid 1px #dee2e6; border-left: solid 1px">'.number_format($bal, 2).'</td>
                            </tr>
                        ';
                    }
                }
                else
                {
                    $output .='
                        <tr class="" trans-id="0">
                            <td class="text-danger" style="border-right: solid 1px #dee2e6; border-left: solid 1px #dee2e6;"><del>' .$lDate.' </del></td>
                            <td class="text-danger" style="border-right: solid 1px #dee2e6;"><del>'.$led->particulars.'</del></td>
                            <td class="text-right text-danger" style="border-right: solid 1px #dee2e6;"><del>'.$amount.'</del></td>
                            <td class="text-right text-danger" style="border-right: solid 1px #dee2e6;"><del>'.$payment.'</del></td>
                            <td class="text-right text-danger" style="border-right: solid 1px #dee2e6; border-left: solid 1px"><del>'.number_format($bal, 2).'</del></td>
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
                'studname' => $studName,
                'sid' => $sid,
                'gradesection' => $gradesection
            );

            echo json_encode($data);
        }
    }


    public function v2_assessment(Request $request)
    {
        if($request->ajax())
        {
            $strmonth = $request->get('month');

            $studid = $request->get('studid');
            $showall = $request->get('showall');
            $month = 0;
            $month = date("m", strtotime($strmonth));
        
            // return $month;

            $syID = CashierModel::getSYID();
            $semid = CashierModel::getSemID();

            $studinfo = db::table('studinfo')
                ->where('id', $studid)
                ->first();

            $levelname = db::table('gradelevel')->where('id', $studinfo->levelid)->first()->levelname . ' | ' . $studinfo->sectionname;

            $studname = $studinfo->lastname . ', ' . $studinfo->firstname . ' ' . $studinfo->middlename . ' ' . $studinfo->suffix;
            $sid = $studinfo->sid;

            if($studinfo->levelid == 14 || $studinfo->levelid == 15)
            {
                // $getPaySched = db::select('select sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate
                //     from studpayscheddetail
                //     where studid = ? and syid = ? and semid = ? and deleted = 0 and amount >=0
                //     group by MONTH(duedate)
                //     order by duedate', [$studid, $syID, $semid]);

                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
                    ->where('studid', $studid)
                    ->where('syid', $syID)
                    ->where('deleted', 0)
                    ->where(function($q){
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->groupBy(db::raw('MONTH(duedate)'))
                    ->orderBy('duedate', 'ASC')
                    ->get();

            }
            elseif($studinfo->levelid >= 17 && $studinfo->levelid <= 21)
            {
                $getPaySched = db::table('studpayscheddetail')
                    ->select(db::raw('sum(amount) as amountdue, sum(amountpay) as amountpay, sum(balance) as balance, duedate'))
                    ->where('studid', $studid)
                    ->where('syid', $syID)
                    ->where('semid', $semid)
                    ->groupBy(db::raw('MONTH(duedate)'))
                    ->orderBy('duedate', 'ASC')
                    ->get();                
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

                  
                  
                    if($showall == 'false')
                    {
                        
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

                $output .='
                  <tr class="bg-primary">
                    <td class="text-right text-bold" colspan="4">TOTAL DUE: '.number_format($totalBal, 2).'</td>
                  </tr>
                ';

            }

            $data = array(
                'output' => $output,
                'studname' => $studname,
                'levelname' => $levelname,
                'sid' => $sid
            );

            echo json_encode($data);
        }
    }

    public function v2_transactions(Request $request)
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
                ->orderBy('ornum', 'DESC')
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
                        <tr>
                            <td class="side-border">'.$count.'</td>
                            <td class="center-border">'.$tdate.'</td>
                            <td class="center-border">'.$trans->ornum.'</td>
                            <td class="center-border">'.strtoupper($trans->studname).'</td>
                            <td class="text-right center-border">'.number_format($trans->amountpaid, 2).'</td>
                            <td class="center-border">'.strtoupper($trans->name).'</td>
                            <td class="center-border">'.$trans->description.'</td>
                            <td class="">
                                <span class="btn-view btn btn-primary btn-sm btn-block" data-id="'.$trans->id.'">View</span>
                            </td>
                            <td class="">
                                <span class="btn-void btn btn-warning btn-sm btn-block" data-toggle="modal" data-target="#modal-voidpermission" data-id="'.$trans->id.'" data-or="'.$trans->ornum.'">
                                    Void
                                </span>
                            </td>
                        </tr>
                    ';
                }
                else if($trans->cancelled == 1)
                {
                    $output .='
                        <tr>
                            <td class="text-danger side-border"><del>'.$count.'</del></td>
                            <td class="text-danger center-border"><del>'.$tdate.'</del></td>
                            <td class="text-danger center-border"><del>'.$trans->ornum.'</del></td>
                            <td class="text-danger center-border"><del>'.strtoupper($trans->studname).'</del></td>
                            <td class="text-right text-danger center-border"><del>'.number_format($trans->amountpaid, 2).'</del></td>
                            <td class="text-danger center-border"><del>'.strtoupper($trans->name).'</del></td>
                            <td class="text-danger center-border"><del>'.$trans->description.'<del></td>
                            <td colspan="2" class=""><span class="btn-view btn btn-block btn-danger btn-sm" data-id="'.$trans->id.'">View</span></td>
                        </tr>
                    ';
                }
                else
                {
                    $output .='
                        <tr>
                            <td class="center-border">'.$count.'</td>
                            <td class="center-border">'.$tdate.'</td>
                            <td class="center-border">'.$trans->ornum.'</td>
                            <td class="center-border">'.strtoupper($trans->studname).'</td>
                            <td class="text-right center-border">'.number_format($trans->amountpaid, 2).'</td>
                            <td class="center-border">'.strtoupper($trans->name).'</td>
                            <td class="center-border">'.$trans->description.'</td>
                            <td class="">
                                <span class="btn-view btn btn-primary btn-sm btn-block" data-id="'.$trans->id.'">View</span>
                            </td>
                            <td class="">
                                <span class="btn-void btn btn-warning btn-sm btn-block" data-toggle="modal" data-target="#modal-voidpermission" data-id="'.$trans->id.'" data-or="'.$trans->ornum.'">
                                    Void
                                </span>
                            </td>
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
                </tr>
            ';

            $data = array(
                'output' => $output
            );

            echo json_encode($data);
        }
    }

    public function v2_printcashtrans($terminalid, $dtfrom, $dtto, $filter)
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
        
        // return $filter;
        
        $paytype = '';
        

        if($filter == '""')
        {
          $filter = '';
        }
        else
        {
            $filter = str_replace('"', '', $filter);
        }

        

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
            ->where('ornum', 'like', '%'. $filter . '%')
            ->whereBetween('transdate', [$from, $to])
            ->where(function($q) use($terminalid){
                if($terminalid > 0)
                {
                    $q->where('terminalno', $terminalid);
                }
            })
            ->where(function($q) use ($paytype){
                if($paytype != '')
                {
                    $q->whereIn('paymenttype.id', $paytype);
                }
            })
            ->orWhere('studname', 'like', '%'. $filter . '%')
            ->whereBetween('transdate', [$from, $to])
            ->where(function($q) use($terminalid){
                if($terminalid > 0)
                {
                    $q->where('terminalno', $terminalid);
                }
            })
            ->where(function($q) use ($paytype){
                if($paytype != '')
                {   
                    $q->whereIn('paymenttype.id', $paytype);
                }
            })
            ->orderBy('ornum', 'DESC')
            ->get();
        // return $transactions;

        $transsummary = db::table('chrngtrans')
            ->select(db::raw('sum(amountpaid) as totalamount, paytype'))
            ->join('users', 'chrngtrans.transby', '=', 'users.id')
            ->join('paymenttype', 'chrngtrans.paytype', '=', 'paymenttype.description')
            ->where('ornum', 'like', '%'. $filter . '%')
            ->where(function($q) use ($terminalid){
                if($terminalid != 0)
                {
                    $q->where('terminalno', $terminalid);
                }
            })
            ->where(function($q) use ($paytype){
                    if($paytype != '')
                    {
                        $q->whereIn('paymenttype.id', $paytype);
                    }
                })
            ->where(function($q) use ($paytype){
                    if($paytype != '')
                    {
                        $q->whereIn('paymenttype.id', $paytype);
                    }
                })
            ->whereBetween('transdate', [$from, $to])
            ->where('cancelled', 0)
            ->orWhere('studname', 'like', '%'. $filter . '%')
            ->where(function($q) use ($terminalid){
                if($terminalid != 0)
                {
                    $q->where('terminalno', $terminalid);
                }
            })
            ->where(function($q) use ($paytype){
                    if($paytype != '')
                    {
                        $q->whereIn('paymenttype.id', $paytype);
                    }
                })
            ->where(function($q) use ($paytype){
                    if($paytype != '')
                    {
                        $q->whereIn('paymenttype.id', $paytype);
                    }
                })
            ->whereBetween('transdate', [$from, $to])
            ->where('cancelled', 0)
            ->groupBy('paytype')
            ->get();

        $ptypedesc = '';
        if($paytype != '')
        {
            $paytypedesc = db::table('paymenttype')
                ->whereIn('id', $paytype)
                ->get();

            if(count($paytypedesc) > 0)
            {
                foreach($paytypedesc as $desc)
                {
                    if($ptypedesc == '')
                    {
                        $ptypedesc .= $desc->description;
                    }
                    else
                    {
                        $ptypedesc .= ', ' . $desc->description;
                    }
                }
            }
        }

        $data = array(
            'terminalid' => $terminalid,
            'schoolname' => $schoolname,
            'schooladdress' => $schooladdress,
            'daterange' => $daterange,
            'transactions' => $transactions,
            'datenow' => CashierModel::getServerDateTime(),
            'transsummary' => $transsummary,
            'filter' => '"' . $filter . '"',
            'paytype' => $ptypedesc
        );

        $pdf = PDF::loadView('/pdf/printcashtrans', $data)->setPaper('legal','portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('cashiertransactions.pdf');
    }


    public function v2_voidtransactions(Request $request)
    {
        if($request->ajax())
        {
            $transid = $request->get('transid');
            $uname = $request->get('uname');
            $pword = $request->get('pword');
            $remarks = $request->get('remarks');

            $return = 0;
            $syid = CashierModel::getSYID();
            $semid = CashierModel::getSemID();
            $studid = 0;
            $feesid = 0;
            $esURL = '';

            $checkuser = db::table('users')
                ->where('email', $uname)
                ->get();

            if(count($checkuser) > 0)
            {
                $checkpermission = db::table('chrngpermission')
                    ->where('userid', $checkuser[0]->id)
                    ->get();


                if(count($checkpermission) > 0 || auth()->user()->email == 'ckgroup')
                {

                    if(hash::check($pword, $checkuser[0]->password))
                    {
                        if($remarks != '')
                        {
                            $trans = db::table('chrngtrans')
                                ->where('id', $transid)
                                ->first();

                            db::table('orcounter')
                                ->where('ornum', $trans->ornum)
                                ->update([
                                    'used' => 0
                                ]);

                            $updTrans = db::table('chrngtrans')
                                ->where('id', $transid)
                                ->update([
                                    'cancelled' => 1,
                                    'cancelledby' => auth()->user()->id,
                                    'cancelleddatetime' => CashierModel::getServerDateTime(),
                                    'cancelledremarks' => $remarks
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

                                        if($paysched)
                                        {
                                            $schedAmount = $paysched->amountpay - $detail->amount;
                                            $bal = $paysched->amount - $schedAmount;
                                        }

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

                                $studid = $trans->studid;

                                // CashierModel::voidledgeritemized($trans->studid, $transid);

                                // CashierModel::ledgeritemizedreset($trans->studid);
                                // CashierModel::transitemsreset($trans->studid);


                                
                            }

                            $return = 1;
                            
                            if($studid > 0)
                            {
                                $stud = db::table('studinfo')
                                    ->where('id', $studid)
                                    ->first();

                                $feesid = $stud->feesid;

                                $esURL = db::table('schoolinfo')
                                    ->first()->essentiellink;
                            }
                        }
                        else
                        {
                            $return = 2;
                        }
                        
                    }
                    else
                    {
                        $return = 0;
                    }
                }
                else
                {
                    $return = 0;
                }

            }
            else
            {
                $return = 5;
            }

            $data = array(
                'studid' =>$studid,
                'syid' => $syid,
                'semid' => $semid,
                'feesid' =>$feesid,
                'esURL' => $esURL,
                'return' => $return
            );

            echo json_encode($data);

        }
    }


    public function v2_genCRS(Request $request)
    {
        $dtFrom = $request->get('dfrom');
        $dtTo = $request->get('dto');
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
            // ->where('posted', 1)
            ->whereBetween('transdate', $datearray)
            ->sum('amountpaid');

        $coa = db::table('acc_coa')
            ->where('id', 371)
            ->first();

        $output .= '
            <tr class="">
                <td class="crs-print">'.$coa->code.' - ' .$coa->account. '</td>
                <td class="crs-print"></td>
                <td class="text-right crs-print">'.number_format($cash, 2).'</td>
                <td class="text-right crs-print"></td>
            </tr>
        ';
        


        $getCRS = db::table('chrngtrans')
            ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
            ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
            ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
            ->where('terminalno', $terminalno)
            ->where('cancelled', 0)
            // ->where('posted', 1)
            ->whereBetween('transdate', $datearray)
            ->groupBy('glid')
            ->get();

          $debit = 0;
          $credit = 0;
        foreach($getCRS as $CRS)
        {
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

        $output .='
            <tr>
                <th class="text-right crs-print" colspan="3">'.number_format($cash, 2).'</th>
                <th class="text-right crs-print" colspan="">'.number_format($credit, 2).'</th>
            </tr>
        ';

        $data = array(
            'output' => $output
        );

        echo json_encode($data);
    }

    public function genCRSexport(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
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

        // $dateexplode = explode(' - ', $request->get('selecteddaterange'));
        $dtFrom = $request->get('dfrom');
        $dtTo = $request->get('dto');
        $terminalno = $request->get('terminalno');

        $dtFrom = date_create($dtFrom);
        $dtFrom = date_format($dtFrom, 'Y-m-d 00:00');

        $dtTo = date_create($dtTo);
        $dtTo = date_format($dtTo, 'Y-m-d 23:59');

        $output = '';
        $gtotal = '';

        $datearray = array();

        array_push($datearray, $dtFrom);
        array_push($datearray, $dtTo);


        $coa = db::table('acc_coa')
            ->where('id', 2)
            ->first();

        $r_from = date_create($request->get('dfrom'));
        $r_from = date_format($r_from, 'm-d-Y');

        $r_to = date_create($request->get('dto'));
        $r_to = date_format($r_to, 'm-d-Y');
            
        $pdf = new EXPORTCRS(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('CK');
        $pdf->SetAuthor('CK Children\'s Publishing');
        $pdf->SetTitle($schoolinfo->schoolname.' - Cash Receipt Summary');
        $pdf->SetSubject('Cash Receipt Summary');
        
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        $pdf->SetFont('dejavusans', '', 10);
        
        $pdf->AddPage();
    
            $html='
            <br>
            <div>
                <span style="margin-left:30px;font-weight:bold; font-size:10px">Date: '.$r_from.' - '.$r_to.'</span>
            </div>
            <br>
            <table border="1" cellpadding="2" >
                <thead>
                    <tr>
                        <th style="font-size: 10px !important; font-weight: bold;" width="35" align="center">#</th>
                        <th style="font-size: 10px !important; font-weight: bold;" align="center" width="250" >Account</th>
                        <th style="font-size: 10px !important; font-weight: bold;" width="100" align="center">Department</th>
                        <th style="font-size: 10px !important; font-weight: bold;" align="center">Debit</th>
                        <th style="font-size: 10px !important; font-weight: bold;" align="center">Credit</th>
                    </tr>
                </thead>
                <tbody>';
            $count = 1;
    
            // return $terminalno;
            if($terminalno == null)
            {
                $cash = db::table('chrngtrans')
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                    ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                    ->where('cancelled', 0)
                    ->where('posted', 1)
                    ->whereBetween('transdate', $datearray)
                    ->sum('chrngtransdetail.amount');
                $html.='<tr class="">
                            <td style="font-size: 10px !important;" width="35" align="center">'.$count.'</td>
                            <td style="font-size: 10px !important;" align="left" width="250" >'.$coa->code.' - ' .$coa->account. '</td>
                            <td style="font-size: 10px !important;" width="100" align="center"></td>
                            <td style="font-size: 10px !important;" align="center">'.number_format($cash, 2).'</td>
                            <td style="font-size: 10px !important;" align="center"></td>
                        </tr>';
                $count+=1;

                $getCRS = array();
                $terminals = Db::table('chrngterminals')->get();
                foreach($terminals as $terminal)
                {
                    $getCRSquery = db::table('chrngtrans')
                        ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
                        ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                        ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                        ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                        ->where('terminalno', $terminal->id)
                        ->where('cancelled', 0)
                        ->where('posted', 1)
                        ->whereBetween('transdate', $datearray)
                        ->groupBy('glid')
                        ->get();
                    if(count($getCRSquery)>0)
                    {
                        foreach($getCRSquery as $crsquery)
                        {
                            array_push($getCRS,$crsquery);
                        }
                    }
                }
            }
            else{
                $cash = db::table('chrngtrans')
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                    ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                    ->where('cancelled', 0)
                    ->where('terminalno', $terminalno)
                    // ->where('posted', 1)
                    ->whereBetween('transdate', $datearray)
                    ->sum('chrngtransdetail.amount');
                $html.='<tr class="">
                            <td style="font-size: 10px !important;" width="35" align="center">'.$count.'</td>
                            <td style="font-size: 10px !important;" align="left" width="250" >'.$coa->code.' - ' .$coa->account. '</td>
                            <td style="font-size: 10px !important;" width="100" align="center"></td>
                            <td style="font-size: 10px !important;" align="center">'.number_format($cash, 2).'</td>
                            <td style="font-size: 10px !important;" align="center"></td>
                        </tr>';
                $count+=1;
                $getCRS = db::table('chrngtrans')
                    ->select(db::raw('acc_coa.code, acc_coa.`account`, sum(`chrngtransdetail`.`amount`) as credit'))
                    ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                    ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
                    ->join('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
                    ->where('terminalno', $terminalno)
                    ->where('cancelled', 0)
                    // ->where('posted', 1)
                    ->whereBetween('transdate', $datearray)
                    ->groupBy('glid')
                    ->get();
            }
            
            
            $debit = 0;
            $credit = 0;
            
            foreach($getCRS as $CRS)
            {

                $credit += $CRS->credit;
                $count+=1;
                $html.='
                <tr class="">
                    <td style="font-size: 10px !important;" width="35" align="center">'.$count.'</td>
                    <td style="font-size: 10px !important;" align="left" width="250" >'.$CRS->code.' - ' .$CRS->account. '</td>
                    <td style="font-size: 10px !important;" width="100" align="center"></td>
                    <td style="font-size: 10px !important;" align="center"></td>
                    <td style="font-size: 10px !important;" align="center">'.number_format($CRS->credit, 2).'</td>
                </tr>
                ';
            }
            
            $html .='<tr class="">
                    <th style="font-size: 10px !important; font-weight: bold;" align="right" colspan="4">'.number_format($cash, 2).'</th>
                    <th style="font-size: 10px !important; font-weight: bold;" >'.number_format($credit, 2).'</th>
                    </tr>';
            $html .='</tbody>
            </table>';
            // output the HTML content
            
            set_time_limit(3000);
            $pdf->writeHTML($html, true, false, true, false, '');
            
            
            // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            
            // test custom bullet points for list
            
            
            // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            
            // reset pointer to the last page
            $pdf->lastPage();
            
            // ---------------------------------------------------------
            //Close and output PDF document
            $pdf->Output('Cash Receipt Summary.pdf', 'I');
            
        
    }

    public function v2_amountenter(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $amount = str_replace(',','', $request->get('amount'));
            $transno = $request->get('transno');
            $terminalno = $request->get('terminalno');
            // $dayid = $request->get('dayid');
            $duedate = $request->get('duedate');

            $semid = '';

            $paysched = db::table('studpayscheddetail')
                // ->select('*', 'aaa')
                ->where('studid', $studid)
                ->where('syid', CashierModel::getSYID())
                ->where('semid', CashierModel::getSemID())
                ->where('balance', '>', 0)
                ->where('deleted', 0)
                ->where(function($q) use ($duedate){
                  if($duedate !='')
                  {
                    $q->where('duedate', $duedate);
                  }
                })
                ->orderBy('duedate')
                ->get();

            $studinfo = db::table('studinfo')
                ->where('id', $studid)
                ->first();

            if($studinfo->levelid == 14 || $studinfo->levelid == 15)
            {
                if(db::table('schoolinfo')->first()->shssetup == 0)
                {
                    $semid = CashierModel::getSemID();
                }
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
    
    public function v2_setterminalno(Request $request)
    {
        if($request->ajax())
        {
            $terminalno = $request->get('terminalno');

            $terminal = db::table('chrngterminals')
                ->where('id', $terminalno)
                ->first();
            if($terminal->owner == '' || $terminal->owner == null)
            {
                DB::table('chrngterminals')
                ->where('id', $terminalno)
                ->update([
                    'owner' => auth()->user()->id
                ]);

                return 1;
            }
            else
            {
                return 0;
            }
        }

    }

    public function v2_ledgerinfo(Request $request)
    {
        if($request->ajax())
        {
            $transid = $request->get('transid');

            $chrngtrans = db::table('chrngtrans')
                ->where('id', $transid)
                ->first();

            $transdate = date_create($chrngtrans->transdate);
            $transdate = date_format($transdate, 'm-d-Y');

            $ornum = $chrngtrans->ornum;
            $totalamount = number_format($chrngtrans->amountpaid, 2);
        }
    }

    public function reuseornum(Request $request)
    {
        if($request->ajax())
        {
            $ornum = $request->get('ornum');

            db::table('orcounter') 
              ->where('ornum', $ornum)
              ->update([
                'used' => 0
              ]);
        }
    }

    public function v2_onlinepay(Request $request)
    {
        if($request->ajax())
        {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $filter = $request->get('filter');

              $payments = '';
              $transdate= "";

              $lists = db::table('onlinepayments')
                  ->select('onlinepayments.*', 'studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 
                    'paymenttype.description', 'amount', 'sydesc', 'semester')
                  ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.sid')
                  ->join('paymenttype', 'onlinepayments.paymenttype', '=', 'paymenttype.id')
                  ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                  ->join('sy', 'onlinepayments.syid', '=', 'sy.id')
                  ->join('semester', 'onlinepayments.semid', '=', 'semester.id')
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
                        <td>'.$list->sydesc .' - '. $list->semester . '</td>
                        <td class="ol-paytype" data-id="'.$list->paymentType.'" data-value="'.$list->bankName.'">'.$list->description.'</td>
                        <td class="ol-amount" data-value="'.$list->amount.'">'.number_format($list->amount, 2).'</td>
                        <td class="ol-refnum">'.$list->refNum.'</td>
                        <td class="ol-transdate" data-value="'.$transdate2.'">'.$transdate.'</td>
                    </tr>
                ';
            }

            $lists = db::table('onlinepayments')
                ->select('onlinepayments.*', 'studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 
                'paymenttype.description', 'amount', 'sydesc', 'semester')
                ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.sid')
                ->join('paymenttype', 'onlinepayments.paymenttype', '=', 'paymenttype.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('sy', 'onlinepayments.syid', '=', 'sy.id')
                ->join('semester', 'onlinepayments.semid', '=', 'semester.id')
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
                        <td>'.$list->sydesc .' - '. $list->semester . '</td>
                        <td class="ol-paytype" data-id="'.$list->paymentType.'" data-value="'.$list->bankName.'">'.$list->description.'</td>
                        <td class="ol-amount" data-value="'.$list->amount.'">'.number_format($list->amount, 2).'</td>
                        <td class="ol-refnum">'.$list->refNum.'</td>
                        <td class="ol-transdate" data-value="'.$transdate2.'">'.$transdate.'</td>
                    </tr>
                ';
            }

            $lists = db::table('onlinepayments')
                ->select('onlinepayments.*', 'studinfo.id as studid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname', 
                'paymenttype.description', 'amount', 'sydesc', 'semester')
                ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.lrn')
                ->join('paymenttype', 'onlinepayments.paymenttype', '=', 'paymenttype.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('sy', 'onlinepayments.syid', '=', 'sy.id')
                ->join('semester', 'onlinepayments.semid', '=', 'semester.id')
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
                        <td>'.$list->sydesc .' - '. $list->semester . '</td>
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

    public function v3_onlinepay(Request $request)
    {
        if($request->ajax())
        {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $filter = $request->get('filter');
            $list = '';

            $onlinePayments = db::table('onlinepayments')
                ->select(db::raw('onlinepayments.id , queingcode, amount, paymentDate, onlinepayments.syid, onlinepayments.semid, refNum, transdate, paymenttype, levelid, studinfo.id AS studid, lastname, firstname, middlename, CONCAT(lastname, ", ", firstname) AS studname, sydesc, semester, paymenttype.description'))
                ->join('studinfo', 'onlinepayments.queingcode', '=', 'studinfo.sid')
                ->join('sy', 'onlinepayments.syid', '=', 'sy.id')
                ->join('semester', 'onlinepayments.semid', '=', 'semester.id')
                ->join('paymenttype', 'onlinepayments.paymenttype', '=', 'paymenttype.id')
                ->where('syid', $syid)
                ->where('isapproved', 1)
                ->having('studname','like', '%'.$filter.'%')
                ->get();

            foreach($onlinePayments as $olpayment)
            {
                $list .='
                    <tr ol-id="'.$olpayment->id.'" data-id="'.$olpayment->studid.'" data-amount="'.$olpayment->amount.'" data-paytype="'.$olpayment->paymenttype.'">
                        <td>'.$olpayment->studname.'</td>
                        <td>'.$olpayment->sydesc . ' - ' . $olpayment->semester .'</td>
                        <td>'.$olpayment->description.'</td>
                        <td>'.number_format($olpayment->amount, 2).'</td>
                        <td>'.$olpayment->refNum.'</td>
                        <td>'.date_format(date_create($olpayment->transdate), 'm-d-Y').'</td>
                    </tr>
                ';
            }

            $data = array(
                'list' => $list
            );

            echo json_encode($data);
        }
    }

    public function v2_loadpaysched(Request $request)
    { 
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $olid = $request->get('olid');
            $transno = $request->get('transno');
            $terminalno = $request->get('terminalno');

            $levelid = db::table('studinfo')->where('id', $studid)->first()->levelid;

            $olpayment = db::table('onlinepayments')
                ->where('id', $olid)
                ->first();

            $olamount = $olpayment->amount;
            $olcuramount = $olamount;

            // return $olcuramount;

            $oldetails = db::table('onlinepaymentdetails')
                ->where('headerid', $olid)
                ->where('deleted', 0)
                ->get();

            foreach($oldetails as $ol)
            {
                $ee_setup = db::table('chrng_earlyenrollmentsetup')
                    ->select('chrng_earlyenrollmentsetup.*', 'items.description')
                    ->join('items', 'chrng_earlyenrollmentsetup.itemid', '=', 'items.id')
                    ->where('itemid', $ol->payscheddetailid)
                    ->first();

                if($ee_setup)
                {

                    db::table('chrngcashtrans')
                        ->insert([
                            'transno' => $transno,
                            'payscheddetailid' => $ol->payscheddetailid,
                            'particulars' => $ee_setup->description,
                            'itemprice' => $ol->amount,
                            'qty' => 1,
                            'amount' => $ol->amount,
                            'deleted' => 0,
                            'studid' => $studid,
                            'syid' => CashierModel::getSYID(),
                            'semid' => CashierModel::getSemID(),
                            'terminalno' => $terminalno,
                            'transdatetime' => CashierModel::getServerDateTime(),
                            'classid' => $ol->classid,
                            'kind' => 'item'
                        ]);

                    $olcuramount = 0;

                }
                else
                {
                    if($olcuramount > 0)
                    {
                        $dpsetup = db::table('dpsetup')
                            ->select(db::raw('sum(amount) as totalamount, classid'))
                            ->where('levelid', $levelid)
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

                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', CashierModel::getSemID());
                                }
                            })
                            ->groupBy('classid')
                            ->first();

                        // return $ol->classid . ' = ' . $dpsetup->classid;

                        if($ol->classid == $dpsetup->classid)
                        {
                            $payscheddetail = db::table('studpayscheddetail')
                                ->where('studid', $studid)
                                ->where('classid', $dpsetup->classid)
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
                                    if($levelid >= 17 && $levelid <= 21)
                                    {
                                        $q->where('semid', CashierModel::getSemID());
                                    }
                                })
                                ->groupBy('classid')
                                ->first();

                            $detailid = 0;
                            $particulars = '';

                            if($payscheddetail)
                            {
                                $detailid = $payscheddetail->id;
                                $particulars = $payscheddetail->particulars;
                            }
                            else
                            {
                                $detailid = 0;
                                $particulars = 'DOWNPAYMENT';
                            }

                            db::table('chrngcashtrans')
                                ->insert([
                                    'transno' => $transno,
                                    'payscheddetailid' => $detailid,
                                    'particulars' => $particulars,
                                    'itemprice' => $olcuramount,
                                    'qty' => 1,
                                    'amount' => $olcuramount,
                                    'deleted' => 0,
                                    'studid' => $studid,
                                    'syid' => CashierModel::getSYID(),
                                    'semid' => CashierModel::getSemID(),
                                    'terminalno' => $terminalno,
                                    'transdatetime' => CashierModel::getServerDateTime(),
                                    'classid' => $dpsetup->classid,
                                    'kind' => 'reg'
                                ]);

                            // $olcuramount -= $dpsetup->totalamount;
                            $olcuramount = 0;
                            // return 'aaa';
                        }
                        else
                        {

                            if($olcuramount > 0)
                            {
                                $kinds = db::table('chrngsetup')
                                    ->where('classid', $ol->classid)->where('deleted', 0)
                                    ->first();

                                $kind = '';

                                if($kinds)
                                {
                                    $kind = $kinds->groupname;
                                }

                                $paysched = db::table('studpayscheddetail')
                                    ->where('id', $ol->payscheddetailid)
                                    ->first();

                                $duedate = '';

                                if($paysched)
                                {
                                    $duedate = $paysched->duedate;
                                }
                                else
                                {
                                    $duedate = null;
                                }

                                if($olcuramount > $ol->amount)
                                {
                                    $curamount = $ol->amount;
                                    $olcuramount -= $ol->amount;
                                }
                                else
                                {
                                    $curamount = $olcuramount;
                                    $olcuramount = 0;
                                }

                                db::table('chrngcashtrans')
                                    ->insert([
                                        'transno' => $transno,
                                        'payscheddetailid' => $ol->payscheddetailid,
                                        'particulars' => 'PAYABLES',
                                        'itemprice' => $curamount,
                                        'qty' => 1,
                                        'amount' => $curamount,
                                        'duedate' => $duedate,
                                        'deleted' => 0,
                                        'studid' => $studid,
                                        'syid' => CashierModel::getSYID(),
                                        'semid' => CashierModel::getSemID(),
                                        'terminalno' => $terminalno,
                                        'transdatetime' => CashierModel::getServerDateTime(),
                                        'classid' => $ol->classid,
                                        'kind' => strtolower($kind)
                                    ]);
                            }
                        }   
                    }
                }
            }

            if($olcuramount > 0)
            {
                $cashtrans = db::table('chrngcashtrans')
                    ->where('transno', $transno)
                    ->where('deleted', 0)
                    ->orderBy('id', 'DESC')
                    ->first();

                $olcuramount += $cashtrans->amount;

                db::table('chrngcashtrans')
                    ->where('id', $cashtrans->id)
                    ->update([
                        'itemprice' => $olcuramount,
                        'amount' => $olcuramount
                    ]);
            }

            $cashtrans = CashierModel::v2_orderlines($transno);

            $data = array(
              'line' => $cashtrans['line'],
              'total' => number_format($cashtrans['total'], 2),
              'paytype' => $olpayment->paymentType
            );

            echo json_encode($data);
        }
    }

    public function reloadselitems(Request $request)
    {
        if($request->ajax())
        {
            $transno = $request->get('transno');
            $schedid = $request->get('schedid');
            $particulars = $request->get('particulars');
            $kind = $request->get('kind');

            db::table('chrngcashtrans')
                ->where('transno', $transno)
                ->where('payscheddetailid', $schedid)
                ->where('deleted', 0)
                ->update([
                    'particulars' => $particulars
                ]);

            $cashtrans = CashierModel::v2_orderlines($transno);

            $data = array(
              'line' => $cashtrans['line'],
              'total' => number_format($cashtrans['total'], 2)
            );

            echo json_encode($data);
        }
    }

    public function viewPayplans(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');

            $feesid = db::table('studinfo')
                ->where('id', $studid)
                ->first()->feesid;

            // FinanceModel::col_dropsubj($studid, 6);

            $tuitionheader = db::table('tuitionheader')
                ->select(db::raw('tuitionheader.id, tuitionheader.`description`, tuitionheader.levelid, grantee.`description` AS grantee, SUM(amount) AS amount'))
                ->join('studinfo', 'tuitionheader.levelid', '=', 'studinfo.levelid')
                ->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
                ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
                ->where('studinfo.id', $studid)
                ->where('tuitionheader.deleted', 0)
                ->where('tuitiondetail.deleted', 0)
                ->where('tuitionheader.syid', CashierModel::getSYID())
                ->where('tuitionheader.semid', CashierModel::getSemID())
                ->groupBy('tuitiondetail.headerid')
                ->orderBy('tuitionheader.description', 'ASC')
                ->get();

            $list = '';


            foreach($tuitionheader as $thead)
            {   
                if($feesid != $thead->id)
                {
                    $list .='
                        <div class="col-md-4 col-fees" data-id="'.$thead->id.'">
                            <div class="card" style="cursor: pointer">
                                <div class="card-header bg-info text-bold">
                                    '.$thead->description.'
                                </div>  
                                <div class="card-body">
                                    <span class="text-bold">GRANTEE<span>: <span>'.$thead->grantee.'</span><br>
                                    <span class="text-bold">AMOUNT</span>: <span>'.number_format($thead->amount, 2).'</span>
                                </div>
                            </div>
                        </div>  
                    ';
                }
                else
                {
                    $list .='
                        <div class="col-md-4 col-fees" data-id="'.$thead->id.'">
                            <div class="card" style="cursor: pointer">
                                <div class="card-header bg-success text-bold">
                                    '.$thead->description.'
                                </div>  
                                <div class="card-body bg-light">
                                    <span class="text-bold">GRANTEE<span>: <span>'.$thead->grantee.'</span><br>
                                    <span class="text-bold">AMOUNT</span>: <span>'.number_format($thead->amount, 2).'</span>
                                </div>
                            </div>
                        </div>  
                    ';   
                }
            }

            $data = array(
                'feelist' => $list,
                'feesid' => $feesid
            );

            echo json_encode($data);
        }
    }

    public function changepayplans(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $feesid = $request->get('feesid');

            if($studid != '' || $studid != null)
            {
                db::table('studinfo')
                    ->where('id', $studid)
                    ->update([
                        'feesid' => $feesid,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => CashierModel::getServerDateTime()
                    ]);
            }
        }
    }

    public function info_changesysem(Request $request)
    {
        if($request->ajax())
        {
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            db::table('chrngterminals')
                ->where('owner', auth()->user()->id)
                ->update([
                    'syid' => $syid,
                    'semid' => $semid
                ]);
            
        }
    }

    public function v2_printledger(Request $request)
    {
        $studid = $request->get('studid');
        $info = $request->get('info');
        $syid = 0;
        $semid = 0;
		
		if($info != '')
        {
            $info = explode(',', $info);

            $syid = $info[0];
            $semid = $info[1];
        }
        else
        {
            $syid = CashierModel::getSYID();
            $semid = CashierModel::getSemID();    
        }

        $stud = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        $sinfo = db::table('schoolinfo')
            ->first();

        $levelid = $stud->levelid;

        $glevel = db::table('gradelevel')
            ->where('id', $levelid)
            ->first();

        $levelname = $glevel->levelname;

        $ledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->where('void', 0)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
            })
            ->orderBy('createddatetime', 'asc')
            ->get();

        $data = array(
            'ledger' => $ledger,
            'stud' => $stud,
            'curDate' => date_format(date_create(CashierModel::getServerDateTime()), 'm-d-Y'),
            'sinfo' => $sinfo,
            'levelname' => $levelname
        );


        $pdf = PDF::loadView('/pdf/pdfledger', $data)->setPaper('letter','portrate');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('test.pdf');
    }

    public function payhistory(Request $request)
    {
        
        $studid = $request->get('studid');
        $action = $request->get('action');

        $stud = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        $levelid = 0;

        if($stud)
        {
            $levelid = $stud->levelid;
        }

        $list = '';
        $transid = 0;
        $totalamount = 0;
        $grandtotal = 0;

        $chrngtrans = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.id, ornum, transdate, chrngtrans.amountpaid, chrngtransdetail.*'))
            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
            ->where('studid', $studid)
            ->where('syid', CashierModel::getSYID())
            ->where(function($q) use($levelid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', CashierModel::getSemID());
                    }
                }
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', CashierModel::getSemID());
                }
            })
            ->where('cancelled', 0)
            ->get();

        foreach($chrngtrans as $trans)
        {
            // echo ' transid: ' . $transid . ' - ' . $trans->chrngtransid . '<br>' ;

            // echo 'transid-H: ' . $transid . ' ' . $trans->chrngtransid . '<br>';
            if($transid == 0 || $transid != $trans->chrngtransid)
            {
                if($transid != $trans->chrngtransid && $transid != 0)
                {
                    // echo 'transid: ' . $transid . ' ' . $trans->chrngtransid . '<br>';

                    $list .='
                        <tr>
                            <td colspan="2" class="text-right text-bold">TOTAL: </td>
                            <td class="text-right text-bold">'.number_format($totalamount, 2).'</td>
                        </tr>
                    ';

                    $totalamount = 0;
                }

                $list .='
                    <tr>
                        <td class="text-bold">'.date_format(date_create($trans->transdate), 'm-d-Y h:n A').'</td>
                        <td colspan="2" class="text-bold">'. $trans->ornum . '</td>
                        
                    </tr>
                    <tr>
                        <td colspan="2" class="pl-5">'.$trans->items.'</td>
                        <td colspan="2" class="text-right">'.number_format($trans->amount, 2).'</td>
                    </tr>
                ';
            }
            else
            {
                $list .='
                    <tr>
                        <td colspan="2" class="pl-5">'.$trans->items.'</td>
                        <td colspan="2" class="text-right">'.number_format($trans->amount, 2).'</td>
                    </tr>
                ';                
            }

            $totalamount += $trans->amount;
            $grandtotal += $trans->amount;
            $transid = $trans->chrngtransid;
        }

        $list .='
            <tr>
                <td colspan="2" class="text-right text-bold">TOTAL: </td>
                <td class="text-right text-bold">'.number_format($totalamount, 2).'</td>
            </tr>
        ';

        $list .='
            <tr>
                <td colspan="2" class="text-right text-bold">GRAND TOTAL: </td>
                <td class="text-right text-bold"><u>'.number_format($grandtotal, 2).'</u></td>
            </tr>
        ';




        $data = array(
            'list' => $list
        );

        if($action == 'load')
        {
            echo json_encode($data);
        }
        else
        {
            $pdf = PDF::loadView('/pdf/pdfpayhistory', $data)->setPaper('letter','portrait');;
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('Payment History.pdf');
        }
    }

    public function soa_generate(Request $request)
    {
        if($request->ajax())
        {
            $levelid = $request->get('levelid');
            $filter = $request->get('filter');

            $list = '';

            $studinfo = db::table('studinfo')
                ->select(db::raw('studinfo.id, sid, concat(lastname, ", ", firstname) as studname, grantee, sectionname, feesid, levelid, levelname, grantee.description as grantee'))
                ->where(function($q) use($levelid){
                    if($levelid > 0)
                    {
                        $q->where('levelid', $levelid);
                    }
                })
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
                ->where('studinfo.deleted', 0)
                ->where('studstatus', '>', 0)
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->having('studname', 'like', '%'.$filter.'%')
                ->get();

            foreach($studinfo as $stud)
            {
                $less = 0;
                $subtotal = 0;
                $payment = 0;
                $balace = 0;

                if($stud->grantee == 'ESC')
                {
                    $less = 9000;
                }
                elseif($stud->grantee == 'VOUCHER')
                {
                    $less = 1400;
                }
                else
                {
                    $less = 0;
                }

                $feesid = $stud->feesid;
                // return $less;

                if($feesid == null || $feesid == 0)
                {
                    $fees = db::table('tuitionheader')
                        ->where('levelid', $stud->levelid)
                        ->where('grantee', $stud->grantee)
                        ->where('deleted', 0)
                        ->first();

                    if($fees)
                    {
                        $feesid = $fees->id;
                    }
                }

                $studledger = db::table('studledger')
                    ->select(db::raw('sum(amount) as amount'))
                    ->where('studid', $stud->id)
                    ->where('syid', CashierModel::getSYID())
                    ->where('deleted', 0)
                    ->first();


                $subtotal += $studledger->amount;
                $subtotal += $less;

                $chrngtrans = db::table('chrngtrans')
                    ->select(db::raw('sum(amountpaid) as amount'))
                    ->where('studid', $stud->id)
                    ->where('cancelled', 0)
                    ->first();

                $_discount = db::table('studledger')
                    ->select(db::raw('sum(payment) as amount'))
                    ->where('studid', $stud->id)
                    ->where('syid', CashierModel::getSYID())
                    ->where('deleted', 0)
                    ->where('particulars', 'like', '%DISCOUNT:%')
                    ->first();

                $discount = $_discount->amount;
                $payment += $chrngtrans->amount;

                $less = $less + $discount;

                $balance = $subtotal - $less - $payment;

                $list .='
                    <tr data-id="'.$stud->id.'">
                        <td> '.$stud->sid.' - '.$stud->studname.'</td>
                        <td> '.$stud->levelname.'</td>
                        <td> '.$stud->grantee.'</td>
                        <td class="text-right">'.number_format($subtotal, 2).'</td>
                        <td class="text-right">'.number_format($less, 2).'</td>
                        <td class="text-right">'.number_format($payment, 2).'</td>
                        <td class="text-right">'.number_format($balance, 2).'</td>
                    </tr>
                ';
            }

            $data = array(
                'list' => $list
            );


            echo json_encode($data);
        }
    }

    public function soa_print(Request $request)
    {
        $studid = $request->get('studid')   ;

        $studinfo = db::table('studinfo')
            ->select(db::raw('studinfo.id, sid, concat(lastname, ", ", firstname) as studname, grantee, sectionname, feesid, levelid, levelname'))
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('studinfo.id', $studid)
            ->first();

        $tuiarray = array();
        $otharray = array();
        $bookamount = '';
        $oldaccount = '';
        $discount = '';
        $subtotal = 0;
        $paylist = '';

        $esc = 0;

        if($studinfo->grantee == 2)
        {
            $esc = 9000;
        }
        elseif($studinfo->grantee == 3)
        {
            $esc = 14000;
        }
        else
        {
            $esc = 0;
        }

        $sy = db::table('sy')
            ->where('id', CashierModel::getSYID())
            ->first();

        $sy = $sy->sydesc;

        $booksetup = db::table('bookentrysetup')
            ->first();



        $chrngsetup = db::table('chrngsetup')
            ->where('deleted', 0)
            ->where('classid', '!=', $booksetup->classid)
            ->get();

        foreach($chrngsetup as $setup)
        {
            if($setup->groupname == 'TUI')
            {
                array_push($tuiarray, $setup->classid);
            }
            elseif($setup->groupname == 'MISC' || 'OTH')
            {
                array_push($otharray, $setup->classid);
            }
        }

        $tuition = 0;

        $studledger = db::table('studledger')
            ->select(db::raw('sum(amount) as amount'))
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->where('syid', CashierModel::getSYID())
            ->whereIn('classid', $tuiarray)
            ->first();

        $tuition = $studledger->amount;
        $tuition += $esc;

        $studledger = db::table('studledger')
            ->select(db::raw('sum(amount) as amount'))
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->where('syid', CashierModel::getSYID())
            ->whereIn('classid', $otharray)
            ->first();

        $oth = $studledger->amount;

        $booksetup = db::table('bookentrysetup')
            ->first();

        $studledger = db::table('studledger')
            ->select(db::raw('sum(amount) as amount'))
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->where('syid', CashierModel::getSYID())
            ->where('classid', $booksetup->classid)
            ->first();

        if($studledger->amount > 0)
        {
            $bookamount = number_format($studledger->amount, 2);
        }
        else
        {
            $bookamount = '';
        }

        $balforwardsetup = db::table('balforwardsetup')
            ->first();

        $studledger = db::table('studledger')
            ->select(db::raw('sum(amount) as amount'))
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->where('syid', CashierModel::getSYID())
            ->where('classid', $balforwardsetup->classid)
            ->first();

        if($studledger->amount > 0)
        {
            $oldaccount = number_format($studledger->amount, 2);    
        }
        else
        {
            $oldaccount = '';
        }

        $studledger = db::table('studledger')
            ->select(db::raw('sum(payment) as amount'))
            ->where('particulars', 'like', '%DISCOUNT:%')
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->where('syid', CashierModel::getSYID())
            ->first();

        if($studledger->amount > 0)
        {
            $discount = number_format($studledger->amount, 2);
        }
        else
        {
            $discount = '';
        }



        $subtotal = $tuition + (float)$oth + (float)str_replace(',', '', $bookamount) + (float)str_replace(',', '', $oldaccount);
        $less = $esc + (float)str_replace(',', '', $discount);

        $totalpayable = $subtotal - $less;
        $monthlypayable = number_format($totalpayable / 10, 2);

        $chrngtrans = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.id, ornum, transdate, chrngtransdetail.classid, itemkind, SUM(chrngtransdetail.amount) AS amount, transno'))
            ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
            ->where('chrngtrans.studid', $studid)
            ->where('chrngtrans.syid', CashierModel::getSYID())
            ->where('cancelled', 0)
            ->groupBy('ornum')
            ->get();
        
        $balance = $totalpayable;
        
        foreach($chrngtrans as $trans)
        {
            $cashtrans = db::table('chrngcashtrans')
                ->where('transno', $trans->transno)
                ->where('deleted', 0)
                ->groupBy('transno')
                ->first();

            $_kind = '';

            if($cashtrans)
            {
                if($cashtrans->kind == 'item')
                {
                    $_kind = 'item';
                }
                else
                {
                    $_kind = '';
                }
            }

            if($_kind != 'item')
            {
                $date = date_format(date_create($trans->transdate), 'm/d/Y');
                $balance -= $trans->amount;
                
                $paylist .='
                    <tr>
                        <td>'.$date.'</td>
                        <td>'.$trans->ornum.'</td>
                        <td class="text-right">'.number_format($trans->amount, 2).'</td>
                        <td class="text-right">'.number_format($balance, 2).'</td>
                        <td></td>
                    </tr>
                ';
            }
        }

        $data = array(
            'studname' => $studinfo->studname,
            'section' => $studinfo->sectionname,
            'levelname' => $studinfo->levelname,
            'sy' => $sy,
            'tuition' => number_format($tuition, 2),
            'oth' => number_format($oth, 2),
            'bookamount' => $bookamount, 
            'oldaccount' => $oldaccount,
            'discount' => $discount,
            'subtotal' => number_format($subtotal, 2),
            'esc' => number_format($esc, 2),
            'less' => number_format($less, 2),
            'totalpayable' => number_format($totalpayable, 2),
            'monthlypayable' => $monthlypayable,
            'paylist' => $paylist
        );



        $pdf = PDF::loadView('/pdf/soa_hchscp', $data)->setPaper('letter','portrait');;
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('SOA.pdf');
    }

    public function old_load(Request $request)
    {
        if($request->ajax())
        {
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $list = '';

            $old_classid = db::table('balforwardsetup')->first()->classid;

            $studledger = db::table('studledger')
                ->select('studinfo.id as studid', 'sid', 'lastname', 'firstname', 'levelname', 'studledger.syid', 'studledger.semid', 'studledger.amount', 'particulars')
                ->join('studinfo', 'studledger.studid', '=','studinfo.id')
                ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                ->where('classid', $old_classid)
                ->where('studledger.amount', '>', 0)
                ->where('studledger.deleted', 0)
                ->where('syid', $syid)
                ->where(function($q) use($levelid, $semid){
                    if($levelid > 0)
                    {
                        $q->where('levelid', $levelid);

                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('studledger.semid', $semid);
                        }
                    }
                })
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->get();

            foreach($studledger as $ledger)
            {
                $name = $ledger->sid . ' - ' . $ledger->lastname . ', ' . $ledger->firstname;
                
                $list .='
                    <tr>
                        <td>'.$name.'</td>
                        <td>'.$ledger->levelname.'</td>
                        <td>'.$ledger->particulars.'</td>
                        <td text-right>'.number_format($ledger->amount, 2).'</td>
                        <td>
                            <button class="btn btn-danger btn-sm old_remove"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                ';
            }

            $data = array(
                'list' => $list 
            );

            echo json_encode($data);
        }   
    }

    public function old_add_studlist(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $studlist = '<option value="0">NAME</option>';
            $sylist = '<option value="0">School Year</option>';
            $semlist = '<option value="0">Semester</option>';
            if($studid > 0)
            {
                $stud = db::table('studinfo')
                    ->select('studinfo.id', 'levelname', 'levelid', 'sectionid', 'courseid', 'grantee.description as grantee')
                    ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                    ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
                    ->where('studinfo.id', $studid)
                    ->first();

                if($stud)
                {
                    $section = '';

                    if($stud->levelid >= 17 && $stud->levelid <= 21)
                    {
                        $collegecourse = db::table('college_courses')
                            ->where('id', $stud->courseid)
                            ->first();

                        if($collegecourse)
                        {
                            $section = $collegecourse->courseabrv;
                        }

                        $sem = db::table('semester')
                            ->where('isactive', 1)
                            ->first();

                        if($sem->id == 1)
                        {
                            $schoolyear = db::table('sy')
                                ->where('sydesc', '<', CashierModel::getSYDesc())
                                ->get();

                            foreach($schoolyear as $sy)
                            {
                                $sylist .='
                                    <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                                ';    
                            }

                            $_semester = db::table('semester')
                                ->get();

                            foreach($_semester as $_sem)
                            {
                                $semlist .='
                                    <option value="'.$_sem->id.'">'.$_sem->semester.'</option>
                                ';       
                            }
                        }
                        elseif($sem->id == 2)
                        {
                            $schoolyear = db::table('sy')
                                ->where('sydesc', '<=', CashierModel::getSYDesc())
                                ->get();

                            foreach($schoolyear as $sy)
                            {
                                $sylist .='
                                    <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                                ';    
                            }
                        }

                        
                    }
                    else
                    {
                        $_section = db::table('sections')
                            ->where('id', $stud->sectionid)
                            ->first();

                        if($_section)
                        {
                            $section = $_section->sectionname;
                        }

                        $schoolyear = db::table('sy')
                            ->where('sydesc', '<', CashierModel::getSYDesc())
                            ->get();

                        foreach($schoolyear as $sy)
                        {
                            $sylist .='
                                <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                            ';    
                        }
                    }

                    // return $section;

                    $data = array(
                        'levelname' => $stud->levelname,
                        'grantee' => $stud->grantee,
                        'section' => $section,
                        'levelid' => $stud->levelid,
                        'semlist' => $semlist,
                        'sylist' => $sylist
                    );
                }
            }
            else
            {
                $studinfo = db::table('studinfo')
                    ->select('id', 'sid', 'lastname', 'firstname')
                    ->where('deleted', 0)
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->get();

                foreach($studinfo as $stud)
                {
                    $studlist .='
                        <option value="'.$stud->id.'">'.$stud->sid.' - '.$stud->lastname.', '.$stud->firstname.' </option>
                    ';
                }

                $data = array(
                    'studlist' => $studlist
                );
            }

            echo json_encode($data);
        }
    }

    public function old_post(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $syfrom = $request->get('syfrom');
            $semfrom = $request->get('semfrom');
            $amount = $request->get('amount');

            $sy = db::table('sy')
                ->where('id', $syfrom)
                ->first();

            $sem = db::table('semester')
                ->where('id', $semfrom)
                ->first();

            $syid = CashierModel::getSYID();
            $semid = CashierModel::getSemID();

            $studinfo = db::table('studinfo')
                ->select('id', 'levelid')
                ->where('id', $studid)
                ->first();

            $levelid = $studinfo->levelid;

            $balclassid = db::table('balforwardsetup')->first()->classid;

            $particulars = 'Balance forwarded from SY ' . $sy->sydesc . ' ' . $sem->semester;
            $reverse_particulars = 'Balance forwarded to SY ' . $sy->sydesc . ' ' . $sem->semester;

            $studledger = db::table('studledger')
                ->where('studid', $studid)
                ->where('syid', CashierModel::getSYID())
                ->where('semid', CashierModel::getSemID())
                ->where('particulars', $particulars)
                ->where('deleted', 0)
                ->first();

            if(!$studledger)
            {
                $oldledger = db::table('studledger')
                    ->select(db::raw('SUM(amount) - SUM(payment) AS balance'))
                    ->where('studid', $studid)
                    ->where('syid', $syfrom)
                    ->where(function($q)use($levelid, $semfrom){
                        if($levelid == 14 || $levelid == 15)
                        {
                            if(db::table('schoolinfo')->first()->shssetup == 0)
                            {
                                $q->where('semid', $semfrom);
                            }
                        }
                        if($levelid >= 17 && $levelid <= 21)
                        {
                            $q->where('semid', $semfrom);
                        }
                    })
                    ->where('deleted', 0)
                    ->where('void', 0)
                    ->first();

                if($oldledger)
                {
                    if($oldledger->balance > 0)
                    {
                        db::table('studledger')
                            ->insert([
                                'studid' => $studid,
                                'syid' => $syfrom,
                                'semid' => $semfrom,
                                'classid' => $balclassid,
                                'particulars' =>$reverse_particulars,
                                'payment' => $amount,
                                'createddatetime' => CashierModel::getServerDateTime(),
                                'deleted' => 0,
                                'void' => 0
                            ]);

                        $itemized = db::table('studledgeritemized')
                            ->where('studid', $studid)
                            ->where('syid', $syfrom)
                            ->where(function($q)use($levelid, $semfrom){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', $semfrom);
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', $semfrom);
                                }
                            })
                            ->where('deleted', 0)
                            ->whereColumn('totalamount', '!=', 'itemamount')
                            ->get();

                        foreach($itemized as $item)
                        {
                            db::table('studledgeritemized')   
                                ->where('id', $item->id)
                                ->update([
                                    'totalamount' => $item->itemamount
                                ]);
                        }

                        $payscheddetail = db::table('studpayscheddetail')
                            ->where('studid', $studid)
                            ->where('syid', $syfrom)
                            ->where(function($q)use($levelid, $semfrom){
                                if($levelid == 14 || $levelid == 15)
                                {
                                    if(db::table('schoolinfo')->first()->shssetup == 0)
                                    {
                                        $q->where('semid', $semfrom);
                                    }
                                }
                                if($levelid >= 17 && $levelid <= 21)
                                {
                                    $q->where('semid', $semfrom);
                                }
                            })
                            ->where('deleted', 0)
                            ->where('balance', '>', 0)
                            ->get();

                        foreach($payscheddetail as $detail)
                        {
                            db::table('studpayscheddetail')
                                ->where('id', $detail->id)
                                ->update([
                                    'amountpay' => $detail->amountpay + $detail->balance,
                                    'balance' => 0,
                                    'updateddatetime' => CashierModel::getServerDateTime()
                                ]);
                        }

                    }
                }

                db::table('studledger')
                    ->insert([
                        'studid' => $studid,
                        'syid' => CashierModel::getSYID(),
                        'semid' => CashierModel::getSemID(),
                        'classid' => $balclassid,
                        'particulars' =>$particulars,
                        'amount' => $amount,
                        'createddatetime' => CashierModel::getServerDateTime(),
                        'deleted' => 0,
                        'void' => 0
                    ]);

                FinanceUtilityModel::resetv3_generateoldaccounts($studid, $levelid, $syid, $semid);

                return 'done';
            }
            else
            {
                return 'exist';
            }
        }
    }

    public function v2_items(Request $request)
    {
        if($request->ajax())
        {
            $filter = $request->get('filter');

            $items = db::table('items')
                ->select(db::raw('items.id, itemcode, items.description, itemclassification.description AS classdesc, amount'))
                ->join('itemclassification', 'items.classid', '=', 'itemclassification.id')
                ->where(function($q) use($filter){
                    $q->where('items.description', 'like', '%'.$filter.'%')
                        ->orWhere('itemcode', 'like', '%'.$filter.'%');
                })
                ->where('items.deleted', 0)
                ->where('cash', 1)
                ->orderBy('items.description')
                ->get();

            $list = '';

            foreach($items as $item)
            {
                $list .='
                    <tr data-id="'.$item->id.'">
                        <td>'.$item->itemcode.'</td>
                        <td>'.$item->description.'</td>
                        <td>'.$item->classdesc.'</td>
                        <td>'.number_format($item->amount, 2).'</td>
                    </tr>
                ';
            }

            $data = array(
                'list' => $list
            );

            echo json_encode($data);
        }
    }

    public function load_classification(Request $request)
    {
        $classification = db::table('itemclassification')
            ->where('deleted', 0)
            ->orderBy('description')
            ->get();

        $class = '<option value="0"></option>';
        $coa = '<option value="0"></option>';

        foreach($classification as $c)
        {
            $class .='
                <option value="'.$c->id.'">'.$c->description.'</option>
            ';
        }

        $gl = db::table('acc_coa')
            ->where('deleted', 0)
            ->orderBy('code')
            ->get();

        foreach($gl as $g)
        {
            $coa .='
                <option value="'.$g->id.'">'.$g->code.' - '.$g->account.'</option>
            ';
        }

        $data = array(
            'classlist' => $class,
            'coalist' => $coa
        );

        echo json_encode($data);
    }

    public function v2_items_save(Request $request)
    {
        if($request->ajax())
        {
            $code = $request->get('code');
            $classcode = $request->get('classcode');
            $description = $request->get('description');
            $classid = $request->get('classid');
            $amount = $request->get('amount');
            $glid = $request->get('glid');
            $dataid = $request->get('dataid');

            $cash = $request->get('cash');

            if($dataid == 0)
            {
                $checkexist = db::table('items')
                    ->where('description', $description)
                    ->where('deleted', 0)
                    ->count();



                if($checkexist > 0)
                {
                    return 'exist';
                }
                else
                {
                    db::table('items')
                        ->insert([
                            'itemcode' => $code,
                            'classcode' => $classcode,
                            'description' => $description,
                            'classid' => $classid,
                            'amount' => $amount,
                            'isreceivable' => 0,
                            'isexpense' => 0,
                            'cash' => $cash,
                            'glid' => $glid,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => CashierModel::getServerDateTime()
                        ]);

                    return 'done';
                }
            }
            else
            {
                $checkexist = db::table('items')
                    ->where('description', $description)
                    ->where('id', '!=', $dataid)
                    ->where('deleted', 0)
                    ->count();

                if($checkexist > 0)
                {
                    return 'exist';
                }
                else
                {
                    db::table('items')
                        ->where('id', $dataid)
                        ->update([
                            'itemcode' => $code,
                            'classcode' => $classcode,
                            'description' => $description,
                            'classid' => $classid,
                            'amount' => $amount,
                            'isreceivable' => 0,
                            'isexpense' => 0,
                            'cash' => $cash,
                            'glid' => $glid,
                            'updatedby' => auth()->user()->id,
                            'updateddatetime' => CashierModel::getServerDateTime()
                        ]);

                    return 'done';   
                }
            }
        }
    }

    public function v2_items_edit(Request $request)
    {
        if($request->ajax())
        {
            $classification = db::table('itemclassification')
            ->where('deleted', 0)
            ->orderBy('description')
            ->get();

            $class = '<option value="0"></option>';
            $coa = '<option value="0"></option>';

            foreach($classification as $c)
            {
                $class .='
                    <option value="'.$c->id.'">'.$c->description.'</option>
                ';
            }

            $gl = db::table('acc_coa')
                ->where('deleted', 0)
                ->orderBy('code')
                ->get();

            foreach($gl as $g)
            {
                $coa .='
                    <option value="'.$g->id.'">'.$g->code.' - '.$g->account.'</option>
                ';
            }

            $dataid = $request->get('dataid');

            $item = db::table('items')
                ->where('id', $dataid)
                ->first();

            $data = array(
                'dataid' => $item->id,
                'code' => $item->itemcode,
                'classcode' => $item->classcode,
                'description' => $item->description,
                'classid' => $item->classid,
                'amount' => $item->amount,
                'cash' => $item->cash,
                'receivable' => $item->isreceivable,
                'expense' => $item->isexpense,
                'glid' => $item->glid,
                'classlist' => $class,
                'coalist' => $coa
            );

            echo json_encode($data);
        }
    }

    public function addfeesid(Request $request)
    {
        $studid = $request->get('studid');
        $feesid = $request->get('feesid');

        db::table('studinfo')
            ->where('id', $studid)
            ->update([
                'feesid' => $feesid
            ]);
    }


    public function collection(Request $request)
    {
        $terminalno = $request->get('terminalno');
        $action = $request->get('action');
        $datefrom = date_format(date_create($request->get('datefrom')), 'Y-m-d 00:00');
        $dateto = date_format(date_create($request->get('dateto')), 'Y-m-d 23:59');
        $list = '';
        $check = '';
        $checktotal = 0;
        $online = '';
        $onlinetotal = 0;
        $total = 0;
        

        $trans = db::table('chrngtrans')
            ->select(db::raw('chrngtrans.ornum, chrngcashtrans.`particulars`, SUM(chrngcashtrans.amount) AS amount, description, account, paytype'))
            ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
            ->join('itemclassification', 'chrngcashtrans.classid', '=', 'itemclassification.id')
            ->leftJoin('acc_coa', 'itemclassification.glid', '=', 'acc_coa.id')
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('chrngcashtrans.deleted', 0)
            ->where('cancelled', 0)
            ->where('chrngtrans.terminalno', $terminalno)
            ->groupBy('classid')
            ->get();

        $cash = collect($trans);

        foreach($trans as $trx)
        {
            $total += $trx->amount;
            $list .='
                <tr>
                    <td style="width: 70em">'.$trx->description.'</td>
                    <td class="text-right">'.number_format($trx->amount, 2).'</td>
                </tr>
            ';
        }

        $list .='
            <tr>
                <td class="text-right">TOTAL: </td>
                <td class="text-right text-bold"><u>'.number_format($total, 2).'</u></td>
            </tr>
        ';

        $trans = db::table('chrngtrans')
            ->select(db::raw('amountpaid, chequeno, paytype, refno'))
            ->whereBetween('transdate', [$datefrom, $dateto])
            ->where('cancelled', 0)
            ->where('chrngtrans.terminalno', $terminalno)
            ->where('paytype', '!=', 'CASH')
            ->get();

        foreach($trans as $trx)
        {

            if($trx->paytype == 'CHEQUE')
            {
                $check .='
                    <tr>
                        <td>'.$trx->chequeno.'</td>
                        <td class="text-right">'.number_format($trx->amountpaid, 2).'</td>
                    </tr>
                ';

                $checktotal += $trx->amountpaid;
            }
            else
            {
                $online .='
                    <tr>
                        <td>'.$trx->paytype.'</td>
                        <td>'.$trx->refno.'</td>
                        <td class="text-right">'.number_format($trx->amountpaid, 2).'</td>
                    </tr>
                ';

                $onlinetotal += $trx->amountpaid;
            }
        }

        $deno = db::table('chrng_denomination')
            ->where('transdate', $datefrom)
            ->where('terminalno', $terminalno)
            ->first();

        if($action == 'pdf')
        {
            $data = array(
                'check' => $check,
                'online' => $online,
                'deno' => $deno,
                'datefrom' => $datefrom,
                'dateto' => $dateto,
                'terminalno' => $terminalno,
                'cash' => $cash,
                'checktotal' => number_format($checktotal, 2),
                'onlinetotal' => number_format($onlinetotal, 2)
            );

            $pdf = PDF::loadview('pdf.pdf_collectionreport', compact('datefrom', 'dateto', 'data'));
            return $pdf->stream('studledger.pdf');
        }
        else
        {
            $data = array(
                'list' => $list,
                'check' => $check,
                'checktotal' => number_format($checktotal, 2),
                'online' => $online,
                'onlinetotal' => number_format($onlinetotal, 2),
                'deno' => $deno
            );

            echo json_encode($data);    
        }

        
    }

    public function savedeno(Request $request)
    {
        $id = $request->get('id');
        $count = $request->get('count');
        $transdate = $request->get('transdate');
        $terminalno = $request->get('terminalno');

        $deno = db::table('chrng_denomination')
            ->where('transdate', $transdate)
            ->where('terminalno', $terminalno)
            ->count();

        if($deno == 0)
        {
            db::table('chrng_denomination')
                ->insert([
                    'transdate' => $transdate,
                    'terminalno' => $terminalno,
                    $id => $count,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => CashierModel::getServerDateTime()
                ]);
        }
        else
        {
            db::table('chrng_denomination')
                ->where('transdate', $transdate)
                ->where('terminalno', $terminalno)
                ->update([
                    $id => $count,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => CashierModel::getServerDateTime()
                ]);   
        }
    }


    public function old_loadamount(Request $request)
    {
        $studid = $request->get('studid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $levelname = '';
        $section = '';
        $course = '';
        $amount = 0;

        $info = db::table('enrolledstud')
            ->select('levelname', 'sectionname', 'enrolledstud.levelid')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->first();

        if($info)
        {
            $levelname = $info->levelname . '|' . $info->sectionname;

            $acc = db::table('studledger')
                ->select(db::raw('SUM(amount) - SUM(payment) AS balance'))
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where('deleted', 0)
                ->where('void', 0)
                ->first();

            if($acc)
            {
                $amount = $acc->balance;
            }
            else
            {
                $amount = 0;
            }


        }
        else
        {
            $info = db::table('sh_enrolledstud')
                ->select('levelname', 'sectionname', 'sh_enrolledstud.levelid')
                ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where(function($q) use($semid){
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                })
                ->first();

            if($info)
            {
                $levelname = $info->levelname . '|' . $info->sectionname;

                $acc = db::table('studledger')
                    ->select(db::raw('SUM(amount) - SUM(payment) AS balance'))
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where(function($q) use($semid){
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->where('deleted', 0)
                    ->where('void', 0)
                    ->first();

                if($acc)
                {
                    $amount = $acc->balance;
                }
                else
                {
                    $amount = 0;
                }
            }
            else
            {
                $info = db::table('college_enrolledstud')
                    ->select('levelname', 'sectionDesc as sectionname', 'courseabrv', 'yearLevel as levelid')
                    ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                    ->join('college_sections', 'college_enrolledstud.sectionid', '=', 'college_sections.id')
                    ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                    ->where('studid', $studid)
                    ->where('college_enrolledstud.syid', $syid)
                    ->where(function($q) use($semid){
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    })
                    ->first();

                if($info)
                {
                    $levelname = $info->levelname . '|' . $info->sectionname . ' - ' . $info->courseabrv;

                    $acc = db::table('studledger')
                        ->select(db::raw('SUM(amount) - SUM(payment) AS balance'))
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('deleted', 0)
                        ->where('void', 0)
                        ->first();

                    if($acc)
                    {
                        $amount = $acc->balance;
                    }
                    else
                    {
                        $amount = 0;
                    }
                }
            }

        }

        $data = array(
            'info' => $levelname,
            'amount' => $amount
        );

        echo json_encode($data);

        
    }

    public function old_getsem(Request $request)
    {
        $syid = $request->get('syid');
        $semlist = '';

        if($syid == CashierModel::getSYID())
        {
            $semlist .='
                <option value="0">Semester</option>
                <option value="1">1st Semester</option>
            ';
        }
        else
        {
            $semlist .='
                <option value="0">Semester</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
            ';   
        }

        return $semlist;
    }


    public function loadfees(Request $request)
    {
        $levelid = $request->get('levelid');
        $feelist = '';

        // return CashierModel::getSemID();

        $fees = db::table('tuitionheader')
            ->select(db::raw('tuitionheader.id, tuitionheader.description, SUM(amount) AS amount'))
            ->join('tuitiondetail', 'tuitionheader.id', 'tuitiondetail.headerid')
            ->where('levelid', $levelid)
            ->where('syid', CashierModel::getSYID())
            ->where(function($q) use($levelid){
                if(CashierModel::getSemID() == 3)
                {
                    $q->where('semid', CashierModel::getSemID());
                }
                elseif($levelid == 14 || $levelid == 15)  
                {
                    if(CashierModel::getSemID() == 3)
                    {
                        $q->where('semid', CashierModel::getSemID());   
                    }
                    elseif(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', CashierModel::getSemID());
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
            ->where('tuitionheader.deleted', 0)
            ->where('tuitiondetail.deleted', 0)
            ->groupBy('tuitionheader.id')
            ->get();

        foreach($fees as $fee)
        {
            $feelist .='
                <tr data-id="'.$fee->id.'">
                    <td>'.$fee->description.'</td>
                    <td>'.number_format($fee->amount, 2).'</td>
                </tr>
            ';
        }

        $data = array(
            'feelist' => $feelist
        );

        echo json_encode($data);
    }

    public function v3_assessment(Request $request)
    {
        $studid = $request->get('studid');
        $ledgerinfo = explode(',', $request->get('ledgerinfo'));

        $syid = $ledgerinfo[0];
        $semid = $ledgerinfo[1];

        $stud = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        $sinfo = db::table('schoolinfo')
            ->first();

        $levelid = $stud->levelid;

        $glevel = db::table('gradelevel')
            ->where('id', $levelid)
            ->first();

        $levelname = $glevel->levelname;

        $ledger = db::table('studledger')
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->where('void', 0)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
            })
            ->orderBy('createddatetime', 'asc')
            ->get();

        $studpayscheddetail = db::table('studpayscheddetail')
            ->select(db::raw('duedate, SUM(amount) AS amount, SUM(amountpay) AS payment, SUM(balance) AS balance'))
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->where('syid', $syid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('semid', $semid);
                }
            })
            ->groupBy('duedate')
            ->get();
			
        $einfo = CashierModel::enrollmentinfo($studid, $syid, $semid);

        $data = array(
            'ledger' => $ledger,
            'stud' => $stud,
            'curDate' => date_format(date_create(CashierModel::getServerDateTime()), 'm-d-Y'),
            'sinfo' => $sinfo,
            // 'levelname' => $levelname,
            'paysched' => $studpayscheddetail,
            'einfo' => $einfo
        );


        $pdf = PDF::loadView('/pdf/pdf_assessment', $data)->setPaper('letter','portrate');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('Assessment.pdf');
    }
	
    public function syinfo(Request $request)
    {
        $studid = $request->get('studid');
        $element = 'table';

        return CashierModel::syinfo($studid, $element);
    }


    public function addpayment_tui(Request $request)
    {   
        $chrngsetup = db::table('chrngsetup')
            ->where('groupname', 'TUI')
            ->where('deleted', 0)
            ->first();

        $studid = $request->get('studid');
        $amount = $request->get('amount');
        $transno = $request->get('transno');
        $terminalno = $request->get('terminalno');
        $particulars = 'TUITION';
        $classid = $chrngsetup->classid;
        $kind = 'tui';

        db::table('chrngcashtrans')
            ->insert([
                'transno' => $transno,
                'particulars' => $particulars,
                'itemprice' => $amount,
                'qty' => 1,
                'amount' => $amount,
                'studid' => $studid,
                'syid' => CashierModel::getSYID(),
                'semid' => CashierModel::getSemID(),
                'classid' => $classid,
                'kind' => $kind,
                'deleted' => 0,
                'transdatetime' => CashierModel::getServerDateTime(),
                'createddatetime' => CashierModel::getServerDateTime(),
                'createdby' => auth()->user()->id,
                'transdone' => 0,
                'terminalno' => $terminalno
            ]);

        $cashtrans = CashierModel::v2_orderlines($transno);

        $data = array(
            'line' => $cashtrans['line'],
            'total' => number_format($cashtrans['total'], 2),
        );

        echo json_encode($data);

    }
	
	public function ul_loadfees(Request $request)
    {
        $studid = $request->get('studid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $levelid = 0;

        $stud = db::table('studinfo')
            ->where('id', $studid)
            ->first();

        $enrolledstud = db::table('enrolledstud')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->first();

        if($enrolledstud)
        {
            $levelid = $enrolledstud->levelid;
        }
        else
        {
            $enrolledstud = db::table('sh_enrolledstud')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where(function($q) use($semid){
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                })
                ->where('deleted', 0)
                ->groupBy('studid')
                ->first();
            if($enrolledstud)
            {
                $levelid = $enrolledstud->levelid;
            }
            else
            {
                $enrolledstud = db::table('college_enrolledstud')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('deleted', 0)
                    ->first();

                if($enrolledstud)
                {
                    $levelid = $enrolledstud->yearLevel;
                }
                else
                {
                    $levelid =  $stud->levelid;
                }
            }
        }

        

        // $levelid = $stud->levelid;

        $tuitionheader = db::table('tuitionheader')
            ->select(db::raw('tuitionheader.id, tuitionheader.`description`, tuitionheader.levelid, grantee.`description` AS grantee, SUM(amount) AS amount'))
            ->join('grantee', 'tuitionheader.grantee', '=', 'grantee.id')
            ->join('tuitiondetail', 'tuitionheader.id', '=', 'tuitiondetail.headerid')
            ->where('tuitionheader.deleted', 0)
            ->where('tuitiondetail.deleted', 0)
            ->where('tuitionheader.syid', $syid)
            ->where('tuitionheader.levelid', $levelid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('tuitionheader.semid', $semid);
                    }
                    else
                    {
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                    }
                        
                }
                elseif($levelid >= 17 && $levelid <= 21)
                {
                    $q->where('tuitionheader.semid', $semid);
                }
                else
                {
                    if($semid == 3)
                    {
                        $q->where('tuitionheader.semid', $semid);
                    }
                    else
                    {
                        $q->where('tuitionheader.semid', '!=', 3);
                    }
                }
            })
            ->groupBy('tuitiondetail.headerid')
            ->orderBy('tuitionheader.description', 'ASC')
            ->get();

        $list = '';

        // return $levelid;
        foreach($tuitionheader as $thead)
        {
            $list .='
                <div class="col-md-4 col-fees" data-id="'.$thead->id.'">
                    <div class="card" style="cursor: pointer">
                        <div class="card-header bg-info text-bold">
                            '.$thead->description.'
                        </div>  
                        <div class="card-body">
                            <span class="text-bold">GRANTEE<span>: <span>'.$thead->grantee.'</span><br>
                            <span class="text-bold">AMOUNT</span>: <span>'.number_format($thead->amount, 2).'</span>
                        </div>
                    </div>
                </div>  
            ';
        }

        $data = array(
            'feelist' => $list
        );

        echo json_encode($data);
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

    public function be_loadstud(Request $request)
    {
        if($request->ajax())
        {
            $be_studlist = '';

            foreach(CashierModel::select2_studlist() as $stud)
            {
                $be_studlist .='
                    <option value="'.$stud->id.'">'.$stud->lastname.', '.$stud->firstname.' '.$stud->middlename.' - ' .$stud->levelname. '</option>
                ';
            }

            return $be_studlist;
        }
    }

  public function printor($transid, Request $request)
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
    
    
    $amounttendered = 0;
    if($request->has('amounttendered'))
    {
        $amounttendered = str_replace( ',', '', $request->get('amounttendered'));
        if($amounttendered == null)
        {
            $amounttendered = 0;
        }
        DB::table('chrngtrans')
        ->where('id', $transid)
        ->update([
            'amounttendered'    => $amounttendered
        ]);
    }else{
        if($trans->amounttendered == null)
        {
            $trans->amounttendered = 0;
        }
        $amounttendered = str_replace( ',', '', $trans->amounttendered);
    }
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

    if(count($transdetail) > 10)
    {
      $transdetail = db::table('chrngtransdetail')
        ->select(db::raw('chrngtransid, itemclassification.`description` AS items, SUM(amount) AS amount, classid'))
        ->join('itemclassification', 'chrngtransdetail.classid', '=', 'itemclassification.id')
        ->where('chrngtransid', $transid)
        ->groupBy('classid')
        ->get();
    }

    $datenow = date_create(CashierModel::getServerDateTime());
    $datenow = date_format($datenow, 'M d, Y');
    // return collect($trans[1]);
    $amount = strtok($trans->amountpaid, '.');
    
    $dec = substr(number_format($previousbalance, 2), strpos($previousbalance, '.') + 1);

    // return $dec;

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
      'transdate' => $trans->transdate,
      'amount' => $trans->amountpaid,
      'cashier' => auth()->user()->name,
      'datenow' => $datenow,
      'amounttendered'  => number_format($amounttendered,2)
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
    elseif(strtolower($schoolinfo->snr) == 'spct')
    {
      $pdf = PDF::loadView('pdf.or_spct', $data);
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'gbbc')
    {
      $pdf = PDF::loadView('pdf.or_gbbc', $data)->setPaper('9.5x5.4', 'portrait');
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'svai')
    {
      $pdf = PDF::loadView('pdf.or_svai', $data);
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'faai')
    {
      $pdf = PDF::loadView('pdf.or_faai', $data);
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'sjaes')
    {
      $pdf = PDF::loadView('pdf.or_sjaes', $data);
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'ndsc' || strtolower($schoolinfo->snr) == 'ndk')
    {
      $pdf = PDF::loadView('pdf.or_ndsc', $data);
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'sjsfi')
    {
      $pdf = PDF::loadView('pdf.or_sjsfi', $data);
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'sbc')
    {
      $pdf = PDF::loadView('pdf.or_sbc', $data);
      return $pdf->stream('receipt.pdf');
    }
    elseif(strtolower($schoolinfo->snr) == 'pcc')
    {
      $pdf = PDF::loadView('pdf.or_pcc', $data);
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

      return 'list: ' . $list;
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
            $bookid = $request->get('bookid');
            $action = $request->get('action');

            // return $studid;

            if($action == 'create')
            {
                $be_setup = db::table('bookentrysetup')
                  ->join('itemclassification', 'bookentrysetup.classid', '=', 'itemclassification.id')
                  ->first();

                $be_id = db::table('bookentries')
                  ->insertGetId([
                    'studid' => $studid,
                    'classid' => $be_setup->classid,
                    'mopid' => $be_setup->mopid,
                    'amount' => $amount,
                    'bookid' => $bookid,
                    'syid' => CashierModel::getSYID(),
                    'semid' => CashierModel::getSemID(),
                    'createdby' => auth()->user()->id,
                    'createddatetime' => CashierModel::getServerDateTime()
                  ]);

                return $be_id;
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
                        'bookid' => $bookid,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => CashierModel::getServerDateTime()
                ]); 
            }

            
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
          $d1 = date_create(CashierControllerV2::getServerDateTime());
          $d1 = date_format($d1, 'Y-m-d 00:00');
          array_push($dateArray, $d1);
      }

      $d2 = date_create($daterange[1]);
      $d2 = date_format($d2, 'Y-m-d 23:59');
      array_push($dateArray, $d2);

      $bookentries = db::table('bookentries')
        ->select('bookentries.id', 'studid', 'bookentries.classid', 'mopid', 'bookentries.amount', 'bestatus', 'sid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelid', 'bookentries.createddatetime', 'items.description')
        ->join('studinfo', 'bookentries.studid', '=', 'studinfo.id')
        ->leftjoin('items', 'bookentries.bookid', '=', 'items.id')
        ->where('bookentries.deleted', 0)
        ->where(function($q) use($filter){
            $q->where('lastname', 'like', '%'.$filter.'%')
                ->orWhere('firstname', 'like', '%'.$filter.'%')
                ->orWhere('sid', 'like', '%'.$filter.'%');
        })
        ->where('syid', CashierModel::getSYID())
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
            <td>'.$book->description.'</td>
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
                ->select(db::raw('bookentries.id, bookid, bookentries.studid, bookentries.classid, mopid, bookentries.amount, bestatus, items.description'))
                ->leftJoin('items', 'bookentries.bookid', '=', 'items.id')
                ->where('bookentries.id', $dataid)
                ->first();
          // return $be->amount;

            $studid = $be->studid;

            db::table('studledger')
                ->insert([
                    'studid' => $studid,
                    'semid' => CashierModel::getSemID(),
                    'syid' => CashierModel::getSYID(),
                    'classid' => $be_setup->classid,
                    'particulars' => 'BOOKS: ' . $be->description,
                    'amount' => $be->amount,
                    'ornum' => $dataid,
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
                    'itemid' => $be->bookid,
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
                            'particulars' => 'BOOKS: ' . $be->description,
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
                            'particulars' => 'BOOKS: ' . $be->description,
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
        $this->Image('@'.file_get_contents($image_file),60,9,17,17);
        
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

class EXPORTCRS extends TCPDF {

    //Page header
    public function Header() {
        $schoollogo = DB::table('schoolinfo')->first();
        $image_file = public_path().'/'.$schoollogo->picurl;
        $extension = explode('.', $schoollogo->picurl);
        $this->Image('@'.file_get_contents($image_file),60,9,17,17);
        
        $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='C', $autopadding=true);
        $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='C', $autopadding=true);

        $this->writeHTMLCell(false, 50, 40, 20, 'Cash Receipt Summary', false, false, false, $reseth=true, $align='C', $autopadding=true);
        // $this->writeHTMLCell(false, 50, 40, 20, 'For the month of <span style="text-transform:uppercase">' . date_format(CashierModel::getServerDateTime(), 'F') . '</span>', false, false, false, $reseth=true, $align='C', $autopadding=true);
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

