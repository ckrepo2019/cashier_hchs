    
@extends('layouts.app')

@section('content')
<style>

    body{
        padding-top: 0 !important;
    }

    .card-border{
        box-shadow: 1px 1px 4px #272727c9!important; 
        border: .3px solid gray !important;
    }

    .payproc{
        display: block; 
        height: 697px !important; 
        position: absolute !important; 
        top: 0; left: 14;
        background-color: rgba(0,0,0,.4);
    }

    @media print
    {
        body{
            scale: 1;
            font-size: 150px !important;
        }
        .pos .pos-receipt-container {
          border: hidden !important;
        }
        .pos-receipt{
          display: block !important;
        }

        .card-style{

        }
    } 
}
</style>


@section('content')

    <div class="row p-0 pl-3" style="background-color: #343a40; height: 55px">
        <div class="col-md-1 mt-2">
            <img class="pos-logo" src="{{asset('assets/essentiel.png')}}" alt="Logo" height="46px">
        </div>
        {{-- <div class="col-md-3 mt-3">
            <span id="setTerminal" class="text-secondary mt-3 btnterminal" style="cursor: pointer;">
                <h5 class="text-sm"> TERMINAL: <span id="tDesc" terminal-id=""></span></h5>
            </span>
        </div>
        <div class="col-md-3 mt-3">
            <span id="setDay" class="text-warning mt-3 ml-1" style="cursor: pointer;" day-id="" data-target="">
                <h5 id="textDay" class="text-secondary text-sm"> DAY: <span>CLOSE</span></h5>
            </span>
        </div> --}}

        <div class="col-md-7 mt-2 text-center">
            <span id="studname-header" class="text-lime mt-3 ml-1" stud-id="" level-id="" trans-id="" ol-id="0" style="cursor: pointer; font-size: 22px" data-status="" fees-id="0">
                -
            </span>
        </div>

        <div class="col-md-4 pr-4 pt-0" style="margin-top: 0px">
            <div class="row">
                <div id="syinfo" class="col-md-12 mt-3 pr-4" style="margin-top: 0px !important; cursor: pointer;">
                    <span class="text-dark">
                        <h5 class="text-secondary text-sm text-right">
							<span id="info_balance" class="text-danger" style="display: none;"><i class="fa fa-warning"></i></span>
                            <span class="text-warning text-bold pointer">SY: </span>
                            <span id="uname_sy" class="text-light">{{App\CashierModel::getSYDesc()}}</span> |
                            <span class="text-warning text-bold pointer">Sem: </span>
                            <span id="uname_sem" class="text-light">
                                {{App\CashierModel::getSemDesc()}}
                            </span>
                        </h5>
                    </span>            
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3 pr-4" style="margin-top: 0px !important">
                    <span class="username text-dark">
                        <h5 class="text-secondary text-sm text-right">
                            <span id="olpayments" class="text-info pointer">Online Payments </span>
                            <span id="olcount" class="badge badge-success">0</span> | <span class="text-warning pointer text-sm u-info">{{Auth::user()->name}} </span>
                            <span id="btninformation" class="text-lg text-warning">
                                {{-- <i class="fas fa-info-circle"></i> --}}
                            </span>
                            
                        </h5>
                    </span>            
                </div>
            </div>
        </div>

        {{-- <div class="col-md-3 mt-3 pr-4">
            <span class="username text-dark">
                <h5 class="text-secondary text-sm text-right">
                    <span id="olpayments" class="text-info pointer">Online Payments </span>
                    <span id="olcount" class="badge badge-success">0</span> | <span class="text-warning pointer u-info">{{Auth::user()->name}} </span>
                    <span id="btninformation" class="text-lg text-warning">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    
                </h5>
            </span>
        </div> --}}
    </div>

    
    <div class="row p-2">
        {{-- studlist --}}
        <div class="col-md-4 pl-3">
            <div class="row" style="display: none">
                <div class="col-md-12">
                    <div class="card" style="box-shadow: 1px 1px 4px #272727c9!important; border: .3px solid gray">
                        <div class="card-header bg-warning" style="height: 37px">
                            <label>STUDENT INFO</label>
                        </div>
                        <div class="card-body p-0" style="height: 76px">
                            <table class="table table-sm  text-sm">
                                <thead>
                                    <tr>
                                        <th>Level</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="studinformation">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="divstudlist">
                <div class="row">                
                    <div class="col-md-12">
                        <input id="searchStud" class="form-control" placeholder="Search Student" autocomplete="off">
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-md-12">
                        <div class="card" style="box-shadow: 1px 1px 4px #272727c9!important; border: .3px solid gray">
                            <div class="card-header bg-primary text-center text-bold">
                                STUDENT NAME
                            </div>
                            <div class="card-body table-responsive p-0 screen-adj" style="height: 435px">
                                <table class="table table-hover table-sm text-sm" style="cursor: pointer; overflow-y: scroll;">
                                    <tbody id="studlist" class="text-sm">
                                    </tbody>
                                </table>  
                            </div>
                            <div class="card-footer bg-dark">
                                <div class="row">
                                    <div class="col-md-12 text-sm p-0" style="margin-top: -6px">
                                        Legend: <span class="text-success">Enrolled</span> |
                                        <span class="text-primary">Late Enrollment</span> |
                                        <span class="text-danger">Dropped Out</span> |
                                        <span class="text-warning">Transferred In</span> |
                                        <span class="text-secondary">Transferred Out</span> |
                                        <span class="text-orange">Withdrawn</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="divcart" style="display: none;">
                <div class="row pt-2 payproc screen-bg" style="width: 99%; top: -8">
                    <div class="col-md-11" style="margin-left: 14px; margin-top: 5px">
                        <div class="card" style="box-shadow: 1px 1px 4px #272727c9!important; border: .3px solid gray">
                            <div class="card-header bg-primary text-bold">
                                <div class="row">
                                    <div class="col-md-5">
                                        Selected Items
                                    </div>
                                    <div class="col-md-5">
                                        
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button type="button" id="cancel-trans" class="close" data-dismiss="modal" aria-label="Close">
                                            {{-- <span aria-hidden="true">×</span> --}}
                                            <span aria-hidden="true"><i class="fas fa-undo-alt"></i></span>
                                            
                                        </button>
                                    </div>
                                </div> 
                        </div>
                            <div class="card-body table-responsive p-0 screen-adj" style="height: 459px">
                                <table class="table table-hover table-sm text-sm" style="cursor: pointer; overflow-y: scroll;">
                                    <thead>
                                        <tr>
                                            <th>Particulars</th>
                                            <th class="text-center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderlist" class="text-sm">
                                    </tbody>    
                                </table>  
                            </div>
                            <div class="card-footer bg-dark">
                                <div class="row">
                                    <div class="col-md-5" style="margin-top: -3px">
                                        <button id="payprocess" class="btn btn-success btn-block btn-sm text-md text-bold">P A Y [F5]</button>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        Amount Due: 
                                    </div>
                                    <div id="orderlisttotal" class="col-md-3 text-right text-bold">
                                        0.00 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- studlist --}}
        <div class="col-md-8">
            <div id="divpaysched" style="display: block">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            {{-- <div class="col-md-12">
                                <div class="card" style="box-shadow: 1px 1px 4px #272727c9!important; border: .3px solid gray">
                                    <div class="card-header bg-success" style="height: 37px">
                                        <label>Registration</label>
                                    </div>
                                    <div class="card-body p-0 screen-sidepanel" style="overflow-y: auto;">
                                        <table class="table table-striped table-sm" style="font-size: 11px">
                                            <thead>
                                                <tr>
                                                    <th>Particulars</th>
                                                    <th class="text-center">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="reglist" class="pointer payment-list">
                                            </tbody>
                                            <tfoot>
                                                <tr class="">
                                                    <td class="text-right text-bold">TOTAL:</td>
                                                    <td id="regtotal" class="text-right text-bold text-lg text-success" style="padding-right: 22px">0.00</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="card" style="box-shadow: 1px 1px 4px #272727c9!important; border: .3px solid gray">
                                    <div class="card-header bg-navy" style="height: 37px">
                                        <label>Miscellaneous</label>
                                    </div>
                                    <div id="screen-misc" class="card-body p-0" style="overflow-y: auto;">
                                        <table class="table table-striped table-sm" style="font-size: 10px">
                                            <thead>
                                                <tr>
                                                    <th>Particulars</th>
                                                    <th class="text-center">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="misclist" class="pointer payment-list">
                                            </tbody>
                                            <tfoot>
                                                <tr class="">
                                                    <td class="text-right text-bold">TOTAL:</td>
                                                    <td id="misctotal" class="text-right text-bold text-lg text-success" style="padding-right: 22px">0.00</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card" style="box-shadow: 1px 1px 4px #272727c9!important; border: .3px solid gray">
                                    <div class="card-header bg-info" style="height: 37px">
                                        <div class="row form-group">
                                            <div class="col-md-10">
                                                <label>Tuition <span id="tlevelname" class="text-bold"></span></label>        
                                            </div>
                                            <div id="addpayment_tuition" class="col-md-2" style="cursor: pointer;" data-toggle="tooltip" title="Add Payment">
                                                <i class="fa-solid fa-plus"></i>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div id="screen-tui" class="card-body p-0" style="">
                                        <table class="table table-striped table-sm" style="font-size: 11px">
                                            <thead>
                                                <tr class="">
                                                    <th>Particulars</th>
                                                    <th class="text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tuilist" class="pointer payment-list">
                                            </tbody>
                                            <tfoot>
                                                <tr class="">
                                                    <td class="text-right text-bold">TOTAL:</td>
                                                    <td id="tuitotal" class="text-right text-bold text-lg text-success" style="padding-right: 22px">0.00</td>
                                                </tr>
                                                <tr id="nosubjloading" style="display: none;">
                                                    <td class="text-center" colspan="2">
                                                        <h4 class="text-danger">NO SUBJECT LOADING</h4>
                                                    </td>
                                                    
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card" style="box-shadow: 1px 1px 4px #272727c9!important; border: .3px solid gray">
                                    <div class="card-header bg-danger" style="height: 37px">
                                        <label>Other Fees/Books</label>
                                    </div>
                                    <div class="card-body p-0 screen-sidepanel" style="overflow-y: auto;">
                                        <table class="table table-striped table-sm" style="font-size: 11px">
                                            <thead>
                                                <tr>
                                                    <th>Particulars</th>
                                                    <th class="text-center">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="othlist" class="pointer payment-list">
                                            </tbody>
                                            <tfoot>
                                                <tr class="">
                                                    <td class="text-right text-bold">TOTAL:</td>
                                                    <td id="othtotal" class="text-right text-bold text-lg text-success" style="padding-right: 22px">0.00</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card" style="box-shadow: 1px 1px 4px #272727c9!important; border: .3px solid gray">
                                    <div class="card-header bg-pink" style="height: 37px">
                                        <label>Old Account</label>
                                    </div>
                                    <div class="card-body p-0 screen-sidepanel">
                                        <table class="table table-striped table-sm" style="font-size: 11px">
                                            <thead>
                                                <tr>
                                                    <th>Particulars</th>
                                                    <th class="text-center">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="oldlist" class="pointer payment-list">
                                            </tbody>
                                            <tfoot>
                                                <tr class="">
                                                    <td class="text-right text-bold">TOTAL:</td>
                                                    <td id="oldtotal" class="text-right text-bold text-lg text-success" style="padding-right: 22px">0.00</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="divpayprocess" style="display: none">
                <div class="row payproc" style="padding-top: 73px !important; margin-left: -13px; margin-top: -8px">
                    <div class="col-md-10" style="margin-left: 70px">
                        <div class="card card-border">
                            <div class="card-header bg-gray-dark">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Payment</label>        
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button id="back-payment" class="btn btn-warning"><i class="fas fa-angle-double-left"></i> Close</button>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <label>OR NUMBER</label>
                                            </div>
                                            <div id="v2_reuse" class="col-md-5 text-right text-lg mt-1 pl-1 text-danger" style="display: none; cursor: pointer;" data-toggle="tooltip" title="Re-use OR number">
                                                <i class="fas fa-recycle"></i>
                                            </div>
                                        </div>
                                        <input id="v2_ornum" class="form-control text-xl" style="height: 60px" placeholder="OR NUMBER">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Transaction Date</label>
                                        @php
                                            $datenow = date_create(App\CashierModel::getServerDateTime());
                                            $datenow = date_format($datenow, 'Y-m-d');
                                        @endphp
                                        <input id="v2_transdate" type="date" name="" class="form-control text-lg" style="height: 60px" value="{{$datenow}}">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Payment Type</label>
                                        <select id="v2_type" class="form-control text-lg" style="height: 60px">
                                            @foreach(DB::table('paymenttype')->where('deleted', 0)->get() as $type)
                                                <option value="{{$type->id}}">{{$type->description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-9">
                                        <label>Payor</label>
                                        <input id="v2_payor" class="form-control" style="height: 60px" placeholder="Payor Name" onkeyup="forceKeyPressUppercase(event.keyCode)">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Acad Prog</label>
                                        <select id="v2_acadprog" class="form-control" style="height: 60px">
                                            <option value="0"></option>
                                            @foreach(DB::table('academicprogram')->get() as $aprog)
                                                <option value="{{$aprog->id}}">{{$aprog->acadprogcode}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label>Amount Due</label>
                                        <input id="v2_due" type="currency" name="" class="form-control text-xl disabled-input" style="height: 60px" placeholder="0.00" disabled="">
                                    </div>
                                
                                
                                    <div class="col-md-3">
                                        <label>Amount Tendered</label>
                                        <input id="v2_tendered" type="text" name="currency-field" class="form-control text-xl" style="height: 60px" placeholder="0.00" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" data-toggle="tooltip" title="Received Amount">
                                    </div>
                                
                                
                                    <div class="col-md-3">
                                        <label>Change</label>
                                        <input id="v2_change" type="text" name="currency-field" class="form-control text-xl disabled-input" style="height: 60px" placeholder="0.00" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" disabled="">
                                    </div>
                                    <div class="col-md-3">
                                        <button id="v2_performpay" class="btn btn-success btn-lg btn-block" style="height: 60px; margin-top: 32px">P A Y</button>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="divitems" style="display: none;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-border">
                            <div class="card-header bg-navy">
                                <div class="row">
                                    <div class="col-md-6"><h5>Items</h5></div>
                                    <div class="col-md-6 text-right">
                                        <button id="back-items" class="btn btn-warning"><i class="fas fa-angle-double-left"></i> Close</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-2" style="height: 30em">
                                <div class="row">
                                    <div class="col-md-8">
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group mb-1">
                                            <input type="search" id="filter" class="form-control" placeholder="Search Item">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 table-responsive tableFixHead" style="height: 25em">
                                        <table class="table table-hover table-sm text-sm" style="overflow-y: scroll !important;">
                                            <thead>
                                                <tr class="bg-secondary">
                                                    <th class="bg-secondary" style="z-index: 100">ITEM CODE</th>
                                                    <th class="bg-secondary" style="z-index: 100">DESCRIPTION</th>
                                                    <th class="bg-secondary" style="z-index: 100">AMOUNT</th>
                                                </tr>
                                            </thead>
                                            <tbody id="v2_itemlist" class="pointer">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="divmenu" style="display: none;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-border">
                            <div class="card-header bg-danger">
                                <div class="row">
                                    <div class="col-md-6"><h5>Menu</h5></div>
                                    <div class="col-md-6 text-right">
                                        <button id="back-menu" class="btn btn-dark"><i class="fas fa-angle-double-left"></i> Close</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div id="menu_studledger" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-orange">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Student <br> Ledger</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-columns"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="menu_assessment" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-dark">Student <br> Assessment</span>
                                            </div>
                                            <div class="icon">
                                                <i class="far fa-credit-card"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="menu_transaction" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Cashier <br> Transaction</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-cash-register"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    {{-- <div id="menu_cashsummary" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-olive">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Cash <br> Receipt Summary</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-receipt"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div> --}}

                                    <div id="menu_soa" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Statement of<br> Account</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-file-invoice"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="menu_items" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-indigo">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Items<br> &nbsp;</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-list-ol"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="menu_onlinepayments" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Online <br> Payments</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-globe"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="menu_bookentry" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-pink">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Book <br> Entry</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div id="menu_terminal" class="col-md-4 u-info" style="cursor: pointer;">
                                        <div class="small-box bg-secondary">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Cashier <br> Information</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-cogs"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div id="menu_balforward" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Old Accounts <br> Management</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-exchange-alt"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    {{-- <div id="menu_soa" class="col-md-4" style="cursor: pointer;">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <br>
                                                <span class="text-bold text-light">Change <br> Password</span>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-lock"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span id="colchange" style="display: none;"></span>
            <div class="row bg-dark w-100" style="height: 40px; bottom: 17; position: absolute;">
                <div class="col-md-8 mt-1">
                    <button id="btn_enteramount" class="btn btn-warning btn-sm text-sm">Enter Amount [F6]</button>
                    <button id="btn_items" class="btn btn-primary btn-sm">Items</button>
                    <button id="btn_menu" class="btn btn-danger btn-sm">Menu [F2]</button>
                    <button id="btn_ledger" class="btn btn-info btn-sm">Ledger</button>
                    {{--<button id="btn_assessment" class="btn bg-olive btn-sm">Assessment</button>--}}
                    @if(DB::table('schoolinfo')->first()->paymentplan == 1)
                        <button id="btn_plans" class="btn bg-pink btn-sm">Payment Plan</button>
                    @endif
                </div>
                <div class="col-md-4 text-right text-lg text-warning">
                    {{-- AMOUNT DUE: 0.00 |  --}}<span class="text-lime">GRAND TOTAL: <span id="gtotal" class="text-bold">0.00</span></span>
                </div>
                {{-- <div class="col-md-4 text-right text-lg text-lime border">
                    
                </div> --}}
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="modal-v2ledger" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="height: 37em">
                <div id="modalhead" class="modal-header bg-primary">
                    <h4 class="modal-title">Student Ledger</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 ">
                            ID No.: <span id="v2_sid" class="text-bold"></span>
                        </div>
                        <div class="col-md-6 ">
                            NAME: <span id="v2_studname" class="text-bold"></span>
                        </div>
                        <div class="col-md-4 ">
                            GRADE|SECTION: <span id="v2_grade" class="text-bold"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <select id="ledger_info" class="select2 ledgerinfo" style="width: 100%;">

                            </select>
                        </div>
                        {{-- <div class="col-md-2">
                            <select id="ledger_sy" class="form-control ledgerinfo">
                                <option>School Year</option>
                                @foreach(DB::table('sy')->get() as $sy)
                                    @if($sy->id == App\CashierModel::getSYID())
                                        <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                                    @else
                                        <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-md-2">
                            <button id="ledger_sa" class="btn btn-primary btn-block">Student Assessment</button>
                        </div>
                        <div class="col-md-2">
                            <button id="payhistory" class="btn btn-danger btn-block">Payment History</button>
                        </div>
                        <div class="col-md-2">
                            <button id="v2_updledger" class="btn btn-info btn-block">Update Ledger</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="table-responsive tableFixHead" style="height: 18em">
                                <table class="table table-hover table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th class="bg-dark" style="border-right: solid 1px #dee2e6; border-left: solid 1px #dee2e6;  ">Date</th>
                                            <th class="bg-dark" style="border-right: solid 1px #dee2e6;">Particulars</th>
                                            <th class="text-center bg-dark" style="border-right: solid 1px #dee2e6;">Charges</th>
                                            <th class="text-center bg-dark" style="border-right: solid 1px #dee2e6;">Payment</th>
                                            <th class="text-center bg-dark" style="border-right: solid 1px #dee2e6; border-left: solid 1px #dee2e6;">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="v2ledger-list" class="">
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="print_v2ledger" type="button" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-v2assessment" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="height: 38em">
                <div id="modalhead" class="modal-header bg-warning">
                    <h4 class="modal-title">Student Assessment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-3">
                                  ID No.:
                                </div>
                                <div class="col-md-2">
                                    <span id="v2_assessment_sid" class="text-bold"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                  NAME:
                                </div>
                                <div class="col-md-8">
                                    <span id="v2_assessment_studname" class="text-bold"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    GRADE|SECTION:
                                </div>
                                <div class="col-md-8">
                                    <span id="v2_assessment_grade" class="text-bold"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <select id="assessment_month" class="form-control assessement-info">
                                <option>Select Month</option>
                                @foreach(DB::table('monthsetup')->get() as $month)
                                    <option value="{{substr($month->description, 0,3)}}">{{$month->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="showall" checked="" class="mt-2 assessement-info">
                                    <label for="showall">
                                        Show all
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div id="table_v2assessment" class="table-responsive tableFixHead">
                                <table class="table table-striped table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th class="bg-dark" style="border-right: solid 1px #dee2e6;">Particulars</th>
                                            <th class="text-center bg-dark" style="border-right: solid 1px #dee2e6;">Amount Due</th>
                                            <th class="text-center bg-dark" style="border-right: solid 1px #dee2e6;">Amount Pay</th>
                                            <th class="text-center bg-dark" style="border-right: solid 1px #dee2e6; border-left: solid 1px #dee2e6;">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="v2assessment-list">
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="print_v2ledger" type="button" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-v2transactions" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="height: 38em">
                <div id="modalhead" class="modal-header bg-primary">
                    <h4 class="modal-title">Cashier Transactions</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Terminal</label>
                            <select id="v2_transactionterminalno" class="form-control">
                                @if(auth()->user()->email == 'ckgroup' || auth()->user()->type == 15)
                                    @foreach(DB::table('chrngterminals')->get() as $terminal)
                                        @if($terminal->owner == auth()->user()->id)

                                            <option value="{{$terminal->id}}" selected="">{{$terminal->description}}</option>
                                            }
                                        @else
                                            <option value="{{$terminal->id}}">{{$terminal->description}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    @php
                                        $terminal = DB::table('chrngterminals')
                                        ->where('owner', auth()->user()->id)
                                        ->first();
                                    @endphp
                                    @if($terminal)
                                        <option value="{{$terminal->id}}">{{$terminal->description}}</option>    
                                    @endif
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Date Range</label>
                            <div class="row input-group">
                                <div class="col-md-6">
                                    <input id="v2_transdatefrom" type="date" name="" class="form-control v2_transdate-range">
                                </div>    
                                <div class="col-md-6">
                                    <input id="v2_transdateto" type="date" name="" class="form-control v2_transdate-range">
                                </div>    
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Search</label>
                            <input id="v2_transsearch" type="search" name="" class="form-control" placeholder="Search OR/Payor">
                        </div>
                        <div class="col-md-1">
                            <button id="v2_btntranssearch" class="btn btn-primary btn-block" style="margin-top: 2em">Search</button>
                        </div>
                        <div class="col-md-2">
                            <button id="trans_collection" class="btn btn-secondary btn-block" style="margin-top: 2em">Collection</button>
                        </div>
                    </div>
                    <div class="col-md-12 table-responsive mt-3 tableFixHead" style="height: 22em">
                        <table class="table table-striped table-sm text-sm">
                            <thead class="bg-gray-dark">
                                <tr>
                                    <th class="bg-gray-dark side-border"></th>
                                    <th class="bg-gray-dark center-border">DATE</th>
                                    <th class="bg-gray-dark center-border">OR NO.</th>
                                    <th class="bg-gray-dark center-border">NAME</th>
                                    <th class="bg-gray-dark center-border">AMOUNT</th>
                                    <th class="bg-gray-dark center-border">CASHIER</th>
                                    <th class="bg-gray-dark center-border">PAYMENT TYPE</th>
                                    <th class="bg-gray-dark"></th>
                                    <th class="bg-gray-dark"></th>
                                    <th class="bg-gray-dark side-border"></th>
                                </tr>
                            </thead>
                            <tbody id="v2_transactionlist">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="print_v2cashtransaction" type="button" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-v2CRS" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="height: 38em">
                <div id="modalhead" class="modal-header bg-olive">
                    <h4 class="modal-title">Cash Receipt Summary</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label>Date Range</label>
                            <div class="row input-group">
                                @php
                                    $dtfrom = date_format(App\CashierModel::getServerDateTime(), 'Y-m-01');
                                    $dtnow = date_format(App\CashierModel::getServerDateTime(), 'Y-m-d');
                                @endphp
                                <div class="col-md-6">

                                    <input id="crs_transdatefrom" type="date" name="" class="form-control v2_transdate-range" value="{{$dtfrom}}">
                                </div>    
                                <div class="col-md-6">
                                    <input id="crs_transdateto" type="date" name="" class="form-control v2_transdate-range" value="{{$dtnow}}">
                                </div>    
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button id="v2_btngencrs" class="btn btn-primary" style="margin-top: 2em">Generate</button>
                        </div>
                    </div>
                    <div class="col-md-12 table-responsive mt-3 tableFixHead" style="height: 22em">
                        <table class="table table-striped table-sm text-sm">
                            <thead class="bg-gray-dark">
                                <tr>
                                    <th class="bg-gray-dark center-border">ACCOUNT</th>
                                    <th class="bg-gray-dark center-border">DEPARTMENT</th>
                                    <th class="bg-gray-dark center-border">DEBIT</th>
                                    <th class="bg-gray-dark center-border">CREDIT</th>
                                </tr>
                            </thead>
                            <tbody id="v2_crslist">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="print_v2genCRS" type="button" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-viewtrans" aria-modal="true" view-id="0" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg mt-5">
            <div class="modal-content text-sm">
                <div id="modalhead" class="modal-header bg-secondary">
                    <h4 class="modal-title">Terminal: <span id="terminalno" class="text-bold"></span> | OR: <span id="head-ornum" class="text-bold"></span> <span id="lblvoid" class="text-bold"> - VOID</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="height: 28em; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-3">
                                  ID No.:
                                </div>
                                <div class="col-md-2">
                                    <span id="lblidno" class="text-bold"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                  NAME:
                                </div>
                                <div class="col-md-9">
                                    <span id="lblstudname" class="text-bold"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    GRADE|SECTION:
                                </div>
                                <div class="col-md-9">
                                    <span id="lblgrade" class="text-bold"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    Date Trans: <span class="text-bold" id="lbltransdate"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    Cashier: <span class="text-bold" id="lblcashier"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    Payment Type: <span class="text-bold" id="lblpaytype"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th class="">PARTICULARS</th>
                                            <th class="text-center">AMOUNT</th>
                                        </tr>
                                  </thead>
                                  <tbody id="list-detail">
                                    
                                  </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
              <button id="v2_viewtrans-close" type="button" class="btn btn-default">Close</button>
              <button id="printtrans" type="button" class="btn btn-primary" data-dismiss="modal">Print</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div id="modal-voidpermission" class="modal fade" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 id="voidheader" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" autocomplete="off">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="voiduname">Usernames</label>
                                <input type="text" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control" id="voiduname" placeholder="Enter Username">
                            </div>
                            <div class="form-group">
                                <label for="voidpword">Password</label>
                                <input type="password" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control" id="voidpword" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="voidremarks">Remarks</label>
                                <textarea autocomplete="off" class="form-control" id="voidremarks" placeholder="Remarks"></textarea>
                            </div>
                        </div>
                    <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button id="btnconfirm" type="button" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-enteramount" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div id="modalhead" class="modal-header bg-warning">
                    <h4 class="modal-title">Enter Amount</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input id="v2_enteramount" type="text" name="currency-field" class="form-control text-xl" style="height: 60px" placeholder="0.00" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" data-toggle="tooltip" title="Enter Amount">
                        </div>
                    </div>
                    {{-- <div class="row mt-2">
                        <div class="col-md-12">
                            <select id="ea-classitem" class="form-control">
                                
                            </select>
                        </div>
                    </div> --}}
                    
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="v3_btnenteramount" type="button" class="btn btn-primary"><i class="fas fa-share"></i> Continue</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-uinfo" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="modalhead" class="modal-header bg-info">
                    <h4 class="modal-title">Cashier Information</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            SCHOOL NAME: <label>{{db::table('schoolinfo')->first()->schoolname}}</label>
                        </div>                        
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            School Year: <label>{{App\CashierModel::ActiveSYDesc()}}</label> |
                            Semester: <label>{{App\CashierModel::ActiveSemDesc()}}</label>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            Cashier Name: 
                            <label>{{strtoupper(auth()->user()->name)}}</label>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Terminal </label>
                            <select id="ci_terminalno" class="form-control select2" style="width: 100%">
                                <option value="0">Select Terminal</option>
                                @foreach(db::table('chrngterminals')->get() as $terminal)                                
                                    @if($terminal->owner == auth()->user()->id)
                                        <option value="{{$terminal->id}}" selected="">{{$terminal->description}}</option>
                                    @else
                                        <option value="{{$terminal->id}}">{{$terminal->description}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3" style="margin-top: 35px">
                            <button id="setTerminalno" class="btn btn-primary"><i class="fas fa-download"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default text-right" data-dismiss="modal">Close</button>
                    <button id="btnlogout" type="button" class="btn btn-warning text-right" data-dismiss="modal">Logout <i class="fas fa-sign-out-alt"></i></button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div id="modal-onlinepay" class="modal fade" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Approved Online Payment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>School Year</label>
                            <select id="online_sy" class="select2" style="width: 100%;">
                                @foreach(db::table('sy')->get() as $sy)
                                    @if($sy->id == App\CashierModel::getSYID())
                                        <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                                    @else
                                        <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Semester</label>
                            <select id="online_sem" class="select2" style="width: 100%;">
                                @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                                    @if($sem->id == App\CashierModel::getSYID())
                                        <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                                    @else
                                        <option value="{{$sem->id}}">{{$sem->semester}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div class="input-group mb-1">
                                <input type="search" id="online_filter" class="form-control" placeholder="Search">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="table_ollist" class="col-md-12 table-responsive">
                            <table class="table table-striped table-sm text-sm">
                                <thead>
                                    <th>NAME</th>
                                    <th>SCHOOL YEAR</th>
                                    <th>TYPE</th>
                                    <th>AMOUNT</th>
                                    <th>REFERENCE NO</th>
                                    <th>DATE</th>
                                </thead>
                                <tbody id="pay-list" style="cursor: pointer;"></tbody>
                            </table>            
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> --}}
                    <button id="" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="modal-v2_paymentplan" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h4 class="modal-title">Select Fees</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <div class="modal-body">
                <div id="loadfeelist" class="row">
                
                </div>    
            </div>
            <div class="modal-footer ">
                <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                <button id="btnreloadproceed" type="button" class="btn btn-primary">PROCEED</button>
            </div>
          </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-changeChrngInfo" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">S.Y Information</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <div class="modal-body">
                <div id="" class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-sm text-sm">
                            <thead>
                                <tr>
                                    <th>SCHOOL YEAR</th>
                                    <th>SEMESTER</th>
                                    <th>GRADE LEVEL</th>
                                    <th>BALANCE</th>
                                </tr>
                            </thead>
                            <tbody id="li_list" style="cursor: pointer;"></tbody>
                        </table>
                    </div>
                </div>    
            </div>
            {{-- <div class="modal-footer ">
                <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                <button id="info_btnproceed" type="button" class="btn btn-primary">PROCEED</button>
            </div> --}}
          </div>
        </div> {{-- dialog --}}
    </div>

    <div id="modal-bookentry-list" class="modal fade show" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-pink">
              <h4 class="modal-title">Book Entry</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body text-left">
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group mb-3">
                      <input id="be_studsearch" type="search" name="" class="form-control filter" placeholder="Search Student">  
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-search"></i> </span>
                      </div>
                    </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="fa fa-calendar"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right dtrangepicker filter" id="dtrange" data-toggle="tooltip" title="Date Range">
                        <div class="input-group-append">
                          <button id="be_create" class="btn btn-primary input-group-text" data-toggle="tooltip" title="Create"><i class="fa fa-plus"></i></button>        
                        </div>
                      </div>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div id="be_mainlist" class="table-responsive" style="overflow-y: auto;height: 373px;">
                    <table class="table table-striped table-head-fixed table-sm text-sm">
                      <thead>
                        <tr>
                          <th>STUDENT NAME</th>
                          <th>DESCRIPTION</th>
                          <th>AMOUNT</th>
                          <th>DATE</th>
                          <th>STATUS</th>
                        </tr>
                      </thead>
                      <tbody id="be_list" style="cursor: pointer;">

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>

    <div id="modal-bookentry" class="modal fade show mt-5" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-secondary">
              <h4 class="modal-title">Book Entry</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body text-left">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <select id="be_studlist" class="select2 form-control" style="width: 100%!important">
                      
                    </select>    
                  </div>
                </div> 
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <select id="be_booklist" class="select2 form-control" style="width: 100%!important">
                      <option value="0">BOOK LIST</option>
                      @foreach(db::table('items')->where('deleted', 0)->where('book', 1)->orderBy('description')->get() as $item)
                        <option data-amount="{{number_format($item->amount, 2)}}" value="{{$item->id}}">
                            {{$item->description}}
                        </option>
                      @endforeach
                    </select>    
                  </div>
                </div> 
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <input type="text" placeholder="0.00" name="currency-field" id="be_amount" class="form-control form-control-lg text-xl" height="60px" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" data-toggle="tooltip" title="Enter Amount">
                  </div>
                </div>            
              </div>

              <div class="row">
                <div class="col-md-4">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="text-align: left !important">Close</button>
                </div>
                <div class="col-md-8 text-right">
                  <button id="be_delete" type="button" class="btn btn-danger btn-action">Delete</button>
                  <button id="be_approve" type="button" class="btn btn-success btn-action">Approve</button>
                  <button id="be_proceed" type="button" class="btn btn-primary" data-action="" data-id="">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div id="modal-payhistory" class="modal fade show mt-5" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-danger">
              <h4 class="modal-title">Payment History</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body text-left">
              <div class="row">
                <div id="table_payhistory" class="col-md-12 table-responsive">
                  <table class="table table-striped table-sm text-sm">
                      <thead>
                          <th>DATE</th>
                          <th>OR NUMBER</th>
                          <th class="text-center">AMOUNT</th>
                      </thead>
                      <tbody id="payhistory_list"></tbody>
                  </table>
                </div> 
              </div>
              <div class="row">
                <div class="col-md-4">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="text-align: left !important">Close</button>
                </div>
                <div class="col-md-8 text-right">
                  {{-- <button id="be_delete" type="button" class="btn btn-danger btn-action">Delete</button>
                  <button id="be_approve" type="button" class="btn btn-success btn-action">Approve</button> --}}
                  <button id="payhistory_print" type="button" class="btn btn-primary" data-action="" data-id="">Print</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade show" id="modal-soa" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="height: 38em">
                <div id="modalhead" class="modal-header bg-danger">
                    <h4 class="modal-title">Statement of Account</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <select id="soa_levelid" class="select2" style="width: 100%;">
                                <option value="0">Grade Level</option>
                                @foreach(db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
                                    <option value="{{$level->id}}">{{$level->levelname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input id="soa_filter" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-2">
                           <button id="soa_generate" class="btn btn-primary btn-block">Generate</button> 
                        </div>
                    </div>
                    <div class="col-md-12 table-responsive mt-3 tableFixHead" style="height: 22em">
                        <table class="table table-hover table-sm text-sm">
                            <thead class="bg-gray-dark">
                                <tr>
                                    <th class="bg-gray-dark side-border center-border">NAME</th>
                                    <th class="bg-gray-dark center-border text-center">GRADE</th>
                                    <th class="bg-gray-dark center-border text-center">GRANTEE</th>
                                    <th class="bg-gray-dark center-border text-center">SUB TOTAL</th>
                                    <th class="bg-gray-dark center-border text-center">LESS</th>
                                    <th class="bg-gray-dark center-border text-center">PAYMENT</th>
                                    
                                    <th class="bg-gray-dark">BALANCE</th>
                                    <th class="bg-gray-dark"></th>
                                    <th class="bg-gray-dark side-border"></th>
                                </tr>
                            </thead>
                            <tbody id="soa_list" style="cursor: pointer;">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{-- <button id="print_v2cashtransaction" type="button" class="btn btn-primary">Print</button> --}}
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-items" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="height: 38em">
                <div id="modalhead" class="modal-header bg-indigo">
                    <h4 class="modal-title">Items</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            
                        </div>
                        <div class="col-md-3">
                            <div class="input-group mb-1">
                                <input id="item_filter" class="form-control" placeholder="Search">
                                <div class="input-group-append">
                                    <span id="item_search" class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>    
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button id="item_create" class="btn btn-primary btn-block">Create</button>
                        </div>
                        {{-- <div class="col-md-2">
                           <button id="item_search" class="btn btn-primary btn-block">Search</button> 
                        </div> --}}
                    </div>
                    <div class="col-md-12 table-responsive mt-3 tableFixHead" style="height: 22em">
                        <table class="table table-hover table-sm text-sm">
                            <thead class="bg-gray-dark">
                                <tr>
                                    <th class="bg-gray-dark side-border center-border">ITEM CODE</th>
                                    <th class="bg-gray-dark center-border text-center">DESCRIPTION</th>
                                    <th class="bg-gray-dark center-border text-center">CLASSIFICATION</th>
                                    <th class="bg-gray-dark center-border text-center">AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody id="item_list" style="cursor: pointer;">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{-- <button id="print_v2cashtransaction" type="button" class="btn btn-primary">Print</button> --}}
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
    <div class="modal fade show" id="modal-items_detail" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-sm" style="height: 38em; margin-top: 4em;">
                <div id="modalhead" class="modal-header bg-info">
                    <h4 class="modal-title">Items <span id="item_action"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="item_code" class="col-sm-2 col-form-label">Item Code</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control validation" id="item_code" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-sm-5">
                          <select id="item_classcode" class="select2" style="width:100%">
                            <option value="0"></option>
                            @foreach(db::table('items_classcode')->get() as $itemclass)
                              <option value="{{$itemclass->id}}">{{$itemclass->description}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_desc" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control validation" id="item_desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_classid" class="col-sm-2 col-form-label">Classification</label>
                        <div class="col-sm-10">
                            <select class="select2 " id="item_classid" style="width: 100%;">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_amount" class="col-sm-2 col-form-label">Amount</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control validation" id="item_amount" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_cash">
                                <label for="item_cash">
                                    Cash
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_receivable" disabled>
                                <label for="item_receivable">
                                    Receivable
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_expense" disabled>
                                <label for="item_expense">
                                    Expense
                                </label>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>

                    <hr>
                    <div class="form-group row">
                        <label for="item_glid" class="col-sm-2 col-form-label">GL Account</label>
                        <div class="col-sm-10">
                          <select id="item_glid" class="select2" style="width: 100%;">
                          </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="item_save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-oldaccount" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="height: 38em">
                <div id="modalhead" class="modal-header bg-success">
                    <h4 class="modal-title">Old Account Management</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <select id="old_sy" class="select2" style="width: 100%;">
                                @foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
                                    @if($sy->id == App\CashierModel::getSYID())
                                        <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                                    @else
                                        <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <select id="old_sem" class="select2 w-100" style="width: 100%;">
                                @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                                    @if($sem->id == App\CashierModel::getSemID())
                                        <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                                    @else
                                        <option value="{{$sem->id}}">{{$sem->semester}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <select id="old_gradelevel" class="select2" style="width: 100%;">
                                <option value="0">Grade Level</option>
                                @foreach(DB::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
                                    <option value="{{$level->id}}">{{$level->levelname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button id="old_generate" class="btn btn-primary btn-lg" data-toggle="tooltip" title="Generate"><i class="fas fa-sync-alt"></i></button>
                            <button id="old_add" class="btn btn-success btn-lg" data-toggle="tooltip" title="Add"><i class="fas fa-plus-circle"></i></button>
                        </div>
                    </div>
                    <div class="col-md-12 table-responsive mt-3 tableFixHead" style="height: 22em">
                        <table class="table table-striped table-sm text-sm">
                            <thead class="bg-gray-dark">
                                <tr>
                                    <th class="bg-gray-dark side-border center-border">Name</th>
                                    <th class="bg-gray-dark center-border">Level</th>
                                    <th class="bg-gray-dark center-border">Particulars</th>
                                    <th class="bg-gray-dark center-border  text-center">Amount</th>
                                    <th class="bg-gray-dark center-border"></th>
                                </tr>
                            </thead>
                            <tbody id="old_list">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{-- <button id="cd_print" type="button" class="btn btn-primary">Print</button> --}}
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-old_add" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin-top: 110px;">
                <div id="modalhead" class="modal-header bg-success">
                    <h4 class="modal-title">Add Old Accounts</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <select id="old_add_studlist" class="select2 old_req is-invalid" style="width: 100%;">
                                <option value="0">NAME</option>
                                {{-- @foreach(db::table('studinfo')->where('deleted', 0)->orderBy('lastname')->orderBy('firstname')->get() as $stud)
                                    <option value="{{$stud->id}}">
                                        {{$stud->sid . ' - ' . $stud->lastname . ', ' . $stud->firstname}}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            Level: <span id="old_add_levelname" class="text-bold"></span>
                        </div>
                        <div class="form-group col-md-6">
                            Section/Course: <span id="old_add_section" class="text-bold"></span>
                        </div>
                        <div class="form-group col-md-2 old_add_granteelabel" style="display: block;">
                            Grantee: <span id="old_add_grantee" class="text-bold"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <h6>Old Account Info</h6>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-8">
                            <div class="form-group col-md-12" style="display: block;">
                                Level|Section: <span id="old_info_level" class="text-bold"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Old Accounts from</label>            
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <select id="old_add_sy" class="select2 w-100 old_req is-invalid" style="width: 100%;">
                                        <option value="0">SCHOOL YEAR</option>
                                        {{-- @foreach(db::table('sy')->orderBy('sydesc')->where('sydesc', '<', App\CashierModel::getSYDesc())->get() as $sy)
                                            <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <select id="old_add_sem" class="select2 w-100 old_req is-invalid" style="width: 100%;">
                                        <option value="0">SEMESTER</option>
                                        {{-- @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                                            <option value="{{$sem->id}}">{{$sem->semester}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Amount</label>
                                    <input id="old_add_amount" type="number" class="form-control is-invalid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="old_post" type="button" class="btn btn-primary" disabled="">POST</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-othmlist" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title othmlist_title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            <div class="modal-body">
                <div id="" class="row">
                    <div class="col-md-12">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="othmlist">
                                
                            </tbody>
                            <tfoot class="bg-gray-dark">
                                <tr>
                                    <th class="border-top text-right">TOTAL: </th>
                                    <th id="othmamount" class="text-bold text-right border-top"></th>
                                </tr>
                            </tfoot>
                        </table>                        
                    </div>
                </div>    
            </div>
            <div class="modal-footer ">
                <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                <button id="othmlist_proceed" type="button" class="btn btn-primary">PROCEED</button>
            </div>
          </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-fees" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="margin-top: 10em;">
                <div class="modal-body" style="height: 405px">
                    <div class="row form-group">
                        <div class="col-md-11">
                            <h3>Fees</h3>
                        </div>
                        <div class="col-md-1">
                            <button type="button" id="fees_close" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-6">
                            <select id="fees_level" class="select2" style="width: 100%;">
                                @foreach(db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
                                    <option value="{{$level->id}}">{{$level->levelname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 text-right" style="font-size: 21px;">
                            <label>Balance:</label>
                            <span id="fees_balance" class="text-bold text-danger">0.00</span>
                        </div>
                    </div>
                    
                    <div class="col-md-12 table-responsive" style="overflow-y: scroll;">
                        <table class="table table-hover table-sm text-sm">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="fees_list" style="cursor: pointer;"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-denomination" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="margin-top: 1em;">
                <div class="modal-body" style="height: 605px">
                    <div class="row form-group">
                        <div class="col-md-10">
                            <h3>Collection Report</h3>
                        </div>
                        <div class="col-md-1">
                            <button id="collection_print" data-date="" data-terminal="" class="btn btn-primary btn-block">Print</button>
                        </div>
                        <div class="col-md-1">
                            <button type="button" id="collection_close" class="btn btn-danger btn-block" aria-label="Close">
                                {{-- <span aria-hidden="true"><i class="fas fa-undo-alt"></i></span> --}}
                                Close
                            </button>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12 table-responsive" style="overflow-y: scroll; height: 14em;">
                            <table class="table table-hover table-sm text-sm">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="collection_list" class="" style="cursor: pointer;"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header bg-warning" style="height: 32px;">
                                    <div class="row">
                                        <div class="col-md-6 text-xs">
                                            <b>CASH DENOMINATION</b>
                                        </div>
                                        <div class="col-md-6 text-right text-bold text-xs">
                                            TOTAL: <span id="denomination_amount">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="card-body p-0" style="height: 14em;">
                                    <div class="col-md-12">
                                        <div class="row p-1">
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                1,000.00 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_1000" class="form-control form-control-xs deno" value="0">
                                            </div>
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                500.00 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_500" class="form-control form-control-xs deno" value="0">
                                            </div>
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                200.00 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_200" class="form-control form-control-xs deno" value="0">
                                            </div>
                                        </div>
                                        <div class="row p-1">
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                100.00 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_100" class="form-control form-control-xs deno" value="0">
                                            </div>
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                50.00 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_50" class="form-control form-control-xs deno" value="0">
                                            </div>
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                20.00 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_20" class="form-control form-control-xs deno" value="0">
                                            </div>
                                        </div>
                                        <div class="row p-1">
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                10.00 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_10" class="form-control form-control-xs deno" value="0">
                                            </div>
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                5.00 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_5" class="form-control form-control-xs deno" value="0">
                                            </div>
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                1.00 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_1" class="form-control form-control-xs deno" value="0">
                                            </div>
                                        </div>
                                        <div class="row p-1">
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                0.50 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_050" class="form-control form-control-xs deno" value="0">
                                            </div>
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                0.25 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_025" class="form-control form-control-xs deno" value="0">
                                            </div>
                                            <div class="col-md-2 text-right mt-1" style="font-size: 13px;">
                                                0.10 X
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="denomination_010" class="form-control form-control-xs deno" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-primary" style="height: 32px;">
                                            <div class="row">
                                                <div class="col-md-6 text-xs">
                                                    <b>CHECKS</b>
                                                </div>
                                                <div class="col-md-6 text-right text-bold text-xs">
                                                    TOTAL: <span id="check_amount">0.00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0" style="height: 14em;">
                                            <table class="table table-striped table-sm text-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Check No.</th>
                                                        <th class="text-center">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="check_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-danger" style="height: 32px;">
                                            <div class="row">
                                                <div class="col-md-6 text-xs">
                                                    <b>Online</b>
                                                </div>
                                                <div class="col-md-6 text-right text-bold text-xs">
                                                    TOTAL: <span id="online_amount">0.00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0" style="height: 14em;">
                                            <table class="table table-striped table-sm text-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Payment</th>
                                                        <th>Reference</th>
                                                        <th class="text-center">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="online_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-pi" aria-modal="true" style="padding-right: 17px; display: none; left: 13em; top: 7em;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <span id="pi_type"></span>
                </div>
            <div class="modal-body">
                <div id="" class="row">
                    <div class="col-md-12 form-group bank">
                        <label>Account Number</label>
                        <input id="pi_accno" class="form-control">    
                    </div>
                    <div class="col-md-12 form-group bank">
                        <label>Account Name</label>
                        <input id="pi_accname" class="form-control">    
                    </div>
                    <div class="col-md-12 form-group bank check">
                        <label>Bank Name</label>
                        <input id="pi_bank" class="form-control">    
                    </div>

                    <div class="col-md-12 form-group check">
                        <label>Check Number</label>
                        <input id="pi_checkno" class="form-control">    
                    </div>
                    <div class="col-md-12 form-group check">
                        <label>Check Date</label>
                        <input type="date" id="pi_checkdate" class="form-control">    
                    </div>
                    <div class="col-md-12 form-group remittance bank">
                        <label>Reference Number</label>
                        <input type="" id="pi_remittance" class="form-control">    
                    </div>
                </div>    
            </div>
            <div class="modal-footer ">
                <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                <button id="pi_proceed" type="button" class="btn btn-primary">PROCEED</button>
            </div>
          </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-addpay" aria-modal="true" style="padding-right: 17px; display: none; left: 13em; top: 7em;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <span id="pi_type"></span>
                </div>
            <div class="modal-body">
                <div id="" class="row">
                    <div class="col-md-12">
                        <label>Amount</label>
                        <input type="number" id="addpay_amount" class="form-control" placeholder="0.00">
                    </div>
                </div>    
            </div>
            <div class="modal-footer ">
                <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                <button id="addpay_proceed" type="button" class="btn btn-primary" data-kind="">PROCEED</button>
            </div>
          </div>
        </div> {{-- dialog --}}
    </div>
	
	<div class="modal fade show" id="modal-ul_fees" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg" style="margin-top: 74px;">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h4 class="modal-title">Select Fees</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="ul_feelist" class="row">
                
                    </div>    
                </div>
                <div class="modal-footer ">
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                    <button id="ul_proceed" type="button" class="btn btn-primary" data-id="0" data-dismiss="modal">PROCEED</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-overlay" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content bg-gray-dark" style="opacity: 78%; margin-top: 15em">
                <div class="modal-body" style="height: 250px">
                    <div class="row">
                        <div class="col-md-12 text-center text-lg text-bold b-close">
                            Please Wait
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: -30px">
                        <div class="col-md-12 text-center text-lg text-bold">
                            Processing...
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>



@endsection

@section('js')



<script>
  //Datemask dd/mm/yyyy
  $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
  //Datemask2 mm/dd/yyyy
  $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
  //Money Euro
  $('[data-mask]').inputmask()  

  // function printFunction(){
  //     window.print();
  //   }

  // $(document).ready(function(){
  //   $(document).on('click','#printwindow',function(){
  //     console.log('sdfhkjhfd');
  //     window.print();
  //   })
  // })
  
  
</script>
<style type="text/css">
    .pointer{
        cursor: pointer;
    }

    .loader{
        width: 100px;
        height: 100px;
        margin: 50px auto;
        position: relative;
    }
    .loader:before,
    .loader:after{
        content: "";
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: solid 8px transparent;
        position: absolute;
        -webkit-animation: loading-1 1.4s ease infinite;
        animation: loading-1 1.4s ease infinite;
    }
    .loader:before{
        border-top-color: #d72638;
        border-bottom-color: #07a7af;
    }
    .loader:after{
        border-left-color: #ffc914;
        border-right-color: #66dd71;
        -webkit-animation-delay: 0.7s;
        animation-delay: 0.7s;
    }
    @-webkit-keyframes loading-1{
        0%{
            -webkit-transform: rotate(0deg) scale(1);
            transform: rotate(0deg) scale(1);
        }
        50%{
            -webkit-transform: rotate(180deg) scale(0.5);
            transform: rotate(180deg) scale(0.5);
        }
        100%{
            -webkit-transform: rotate(360deg) scale(1);
            transform: rotate(360deg) scale(1);
        }
    }
    @keyframes loading-1{
        0%{
            -webkit-transform: rotate(0deg) scale(1);
            transform: rotate(0deg) scale(1);
        }
        50%{
            -webkit-transform: rotate(180deg) scale(0.5);
            transform: rotate(180deg) scale(0.5);
        }
        100%{
            -webkit-transform: rotate(360deg) scale(1);
            transform: rotate(360deg) scale(1);
        }
    }

    table td {
        position: relative;
    }

    table td input {
        position: absolute;
        display: block;
        top:0;
        left:0;
        margin: 0;
        height: 100%;
        width: 100%;
        border: none;
        padding: 10px;
        box-sizing: border-box;
    }

    .tableFixHead{ 
        overflow-y: auto; height: 100px; 
    }
    .tableFixHead thead th { 
        position: sticky; top: -1; 

        z-index: 100;
    }
    .side-boder{
        border-right: solid 1px #dee2e6 !important; border-left: solid 1px #dee2e6 !important;
    }
    .center-border{
        border-right: solid 1px #dee2e6;
    }
    .shadow-border{
        border: .3px solid #dee2e6 !important;
        box-shadow: 1px 1px 4px #272727c9!important;
        border-radius: 5px;
    }
</style>
<script>
    
  $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() { 
        formatCurrency($(this), "blur");
      }
  });

  function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  }

  function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.
    
    // get input value
    var input_val = input.val();
    
    // don't validate empty input
    if (input_val === "") { return; }
    
    // original length
    var original_len = input_val.length;

    // initial caret position 
    var caret_pos = input.prop("selectionStart");
      
    // check for decimal
    if (input_val.indexOf(".") >= 0) {

      // get position of first decimal
      // this prevents multiple decimals from
      // being entered
      var decimal_pos = input_val.indexOf(".");

      // split number by decimal point
      var left_side = input_val.substring(0, decimal_pos);
      var right_side = input_val.substring(decimal_pos);

      // add commas to left side of number
      left_side = formatNumber(left_side);

      // validate right side
      right_side = formatNumber(right_side);
      
      // On blur make sure 2 numbers after decimal
      if (blur === "blur") {
        right_side += "00";
      }
      
      // Limit decimal to only 2 digits
      right_side = right_side.substring(0, 2);

      // join number by .
      input_val = left_side + "." + right_side;

    } else {
      // no decimal entered
      // add commas to number
      // remove all non-digits
      input_val = formatNumber(input_val);
      input_val = input_val;
      
      // final formatting
      if (blur === "blur") {
        input_val += ".00";
      }
    }
    
    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
  }

  function forceKeyPressUppercase(e)
  {
    var charInput = e.keyCode;
    if((charInput >= 97) && (charInput <= 122)) { // lowercase
      if(!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
        var newChar = charInput - 32;
        var start = e.target.selectionStart;
        var end = e.target.selectionEnd;
        e.target.value = e.target.value.substring(0, start) + String.fromCharCode(newChar) + e.target.value.substring(end);
        e.target.setSelectionRange(start+1, start+1);
        e.preventDefault();
      }
    }
  }

</script>
@endsection

@section('jsUP')
  <script type="text/javascript">
    
  </script>
@endsection