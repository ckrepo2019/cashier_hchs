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
use App\CashierModel;

class DisplayModel extends Model
{
    public static function ne_oth($levelid)
    {
        $thead = db::table('tuitionheader')
            ->where('levelid', $levelid)
            ->where('syid', CashierModel::getSYID())
            ->where('deleted', 0)
            ->first();

        if($thead)
        {
            $tdetail = db::table('tuitiondetail')
                ->select('tuitiondetail.id', 'itemclassification.description', 'amount')
                ->join('itemclassification', 'tuitiondetail.classificationid', '=', 'itemclassification.id')
                ->join('chrngsetup', 'tuitiondetail.classificationid', '=', 'chrngsetup.classid')
                ->where('headerid', $thead->id)
                ->where('tuitiondetail.deleted', 0)
                ->where('chrngsetup.deleted', 0)
                ->where('groupname', 'OTH')
                ->get();

            return $tdetail;
        }
    }

}