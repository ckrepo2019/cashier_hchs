<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;
use DB;
class StatementofAccountModel extends Model
{
    
    public static function allstudents()
    {
        
        $studinfo_1 = DB::table('studinfo')
            ->select(
                'studinfo.id',
                // 'studinfo.sid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                // 'studinfo.gender',
                // 'studinfo.mol',
                // 'studinfo.grantee as granteeid',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid',
                'gradelevel.levelname'
                // 'academicprogram.id as acadprogid',
                // 'academicprogram.acadprogcode',
                // 'modeoflearning.description as mol',
                // 'grantee.description as grantee'
                )
            ->join('enrolledstud', 'studinfo.id','=','enrolledstud.studid')
            ->join('sections', 'studinfo.sectionid','=','sections.id')
            ->join('gradelevel', 'sections.levelid','=','gradelevel.id')
            ->join('sy', 'enrolledstud.syid','=','sy.id')
            // ->join('academicprogram', 'gradelevel.acadprogid','=','academicprogram.id')
            // ->leftJoin('modeoflearning', 'studinfo.mol','=','modeoflearning.id')
            // ->leftJoin('grantee', 'studinfo.grantee','=','grantee.id')
            ->where('sections.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('enrolledstud.deleted','0')
            ->where('studinfo.studstatus','!=','0')
            ->where('sy.isactive','1')
            // ->take(5)
            ->get();
            
        $studinfo_2 = DB::table('studinfo')
            ->select(
                'studinfo.id',
                // 'studinfo.sid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                // 'studinfo.gender',
                // 'studinfo.mol',
                // 'studinfo.grantee as granteeid',
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid',
                'gradelevel.levelname'
                // 'academicprogram.acadprogcode',
                // 'academicprogram.id as acadprogid',
                // 'modeoflearning.description as mol',
                // 'grantee.description as grantee'
                )
            ->join('sh_enrolledstud', 'studinfo.id','=','sh_enrolledstud.studid')
            ->join('sections', 'studinfo.sectionid','=','sections.id')
            ->join('gradelevel', 'sections.levelid','=','gradelevel.id')
            ->join('sy', 'sh_enrolledstud.syid','=','sy.id')
            // ->join('academicprogram', 'gradelevel.acadprogid','=','academicprogram.id')
            // ->leftJoin('modeoflearning', 'studinfo.mol','=','modeoflearning.id')
            // ->leftJoin('grantee', 'studinfo.grantee','=','grantee.id')
            ->where('sections.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('sh_enrolledstud.deleted','0')
            ->where('studinfo.studstatus','!=','0')
            ->where('sy.isactive','1')
            // ->take(5)
            ->get();
            
        
        $studinfo_3 = DB::table('studinfo')
            ->select(
                'studinfo.id',
                // 'studinfo.sid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                // 'studinfo.gender',
                // 'studinfo.mol',
                // 'studinfo.grantee as granteeid',
                'college_enrolledstud.sectionID as sectionid',
                'college_enrolledstud.courseid',
                'college_sections.id as sectionid',
                'college_sections.sectionDesc as sectionname',
                'gradelevel.levelname',
                'gradelevel.id as levelid'
                // 'academicprogram.id as acadprogid',
                // 'academicprogram.acadprogcode',
                // 'modeoflearning.description as mol',
                // 'grantee.description as grantee'
                )
            ->join('college_enrolledstud', 'studinfo.id','=','college_enrolledstud.studid')
            ->join('college_sections', 'college_enrolledstud.sectionID','=','college_sections.id')
            ->join('gradelevel', 'college_sections.yearID','=','gradelevel.id')
            ->join('sy', 'college_enrolledstud.syid','=','sy.id')
            // ->join('academicprogram', 'gradelevel.acadprogid','=','academicprogram.id')
            // ->leftJoin('modeoflearning', 'studinfo.mol','=','modeoflearning.id')
            // ->leftJoin('grantee', 'studinfo.grantee','=','grantee.id')
            ->where('college_sections.deleted','0')
            ->where('studinfo.deleted','0')
            ->where('college_enrolledstud.deleted','0')
            ->where('studinfo.studstatus','!=','0')
            ->where('sy.isactive','1')
            // ->take(5)
            ->get();
            $allItems = collect();
            $allItems = $allItems->merge($studinfo_1);
            $allItems = $allItems->merge($studinfo_2);
            $allItems = $allItems->merge($studinfo_3);
            $allItems = $allItems->sortBy('lastname');


            if(count($allItems)>0)
            {
                foreach($allItems as $student)
                {
                    if($student->middlename === null)
                    {
                        $student->middlename = '';
                    }else{
                        $student->middlename = $student->middlename[0].'.';
                    }
                }
            }
            return $allItems;
    }
}
