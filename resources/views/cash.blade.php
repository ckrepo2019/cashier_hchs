    
@extends('layouts.app')

@section('content')
<style>
  @media print
  {body{
      scale: 1;
      font-size: 150px !important;
    }
    .pos .pos-receipt-container {
      border: hidden !important;
    }
    .pos-receipt{
      display: block !important;
    }
  }
  
  
}
</style>
<span id="tAmountCopy" style="display: none"></span> {{--shane--}}

<form action="/viewCashTrans" method="get" name="viewtransactionid" target="_blank"> {{--shane--}}
  <input type="hidden" name="formtrans-id"/>
</form>
  <div class="o_action_manager">
    <div class="pos-receipt-print">
    </div>
      
      <div class="pos">
        <div class="pos-topheader">
          <div class="pos-branding">
              <img class="pos-logo" src="{{asset('assets/essentiel.png')}}" alt="Logo">
          </div>
          <div class="pos-rightheader">
            <div class="order-selector">
              <span class="orders touch-scrollable">
                
              
              </span>
              <span id="setTerminal" class="text-secondary mt-3 btnterminal" style="cursor: pointer;">
                <h5 class="text-sm"> TERMINAL: <span id="tDesc" terminal-id=""></span></h5>
              </span>
              
              {{-- <span class="order-button square neworder-button">
                  <i class="fa fa-plus" role="img" aria-label="New order" title="New order"></i>
              </span>
              <span class="order-button square deleteorder-button">
                  <i class="fa fa-minus" role="img" aria-label="Delete order" title="Delete order"></i>
              </span> --}}
            </div> 
            <div class="order-selector">
              <span id="setDay" class="text-warning mt-3 ml-1" style="cursor: pointer;" day-id="" data-target="">
                <h5 id="textDay" class="text-secondary text-sm"> DAY: <span>CLOSE</span></h5>
              </span>
            </div>
            <div class="oe_status">
              <span class="username text-sm">
                {{-- Andrei Raran Cabrera  --}}
                {{Auth::user()->name}}
                {{-- cashier name --}}
              </span>
            </div>
            
          </div>
        </div>

        <div class="pos-content">

          <div class="window">
            <div class="subwindow">
              <div class="subwindow-container">
                <div class="subwindow-container-fix screens">              
                  <div class="scale-screen screen oe_hidden">
                    <div class="screen-content">
                      <div class="top-content">
                          <span class="button back">
                              <i class="fa fa-angle-double-left"></i>
                              Back
                          </span>
                          <h1 class="product-name">Unnamed Product</h1>
                      </div>
                      <div class="centered-content">
                        <div class="weight js-weight">
                            0.000 Kg
                        </div>
                        <div class="product-price">
                            0.00
                        </div>
                        <div class="computed-price">
                            123.14 €
                        </div>
                        <div class="buy-product">
                            Order
                            <i class="fa fa-angle-double-right"></i>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="mainview" class="product-screen screen">
                    <div class="leftpane">
                      <div class="window">
                        <div class="subwindow">
                          <div class="subwindow-container">
                            <div class="subwindow-container-fix">
                              <div class="order-container">
                                <div class="order-scroller touch-scrollable">
                                  <div id="divOrder" class="order">
                      
                                    <div class="order-empty">
                                        <i class="fa fa-shopping-cart" role="img" aria-label="Shopping cart" title="Shopping cart"></i>
                                        <h1>No data to display</h1>
                                    </div>

                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="subwindow collapsed">
                          <div class="subwindow-container">
                            <div class="subwindow-container-fix pads">
                              <div class="control-buttons oe_hidden"></div>
                              <div class="actionpad p-0" style="width: 95%">
                                <button id="selstud" class="button set-student bg-secondary" stud-id="" stud-name="" trans-no="" fees-id="" or-num="" ol-id="" style="border-top-right-radius: 4px">
                                  <i id="userimg" class="fa fa-user" role="img" aria-label="Customer" title="Customer"></i>
                                  STUDENT [F3]
                                </button>

                                <div class="row">
                                  <div class="col-md-4">
                                    <button id="pay" class="button pay bg-success" style="border-radius: 0px !important;">
                                      <div class="pay-circle">
                                        <i class="fa fa-chevron-right" role="img" aria-label="Pay" title="Pay"></i>
                                      </div>
                                      PAYMENT [F5]
                                    </button>  
                                  </div>
                                  <div class="col-md-8">
                                    <div class="row">
                                      <div class="col-md-3">
                                        <button id="btnmenu" class="button cash-opt bg-primary">
                                          MENU [F2]
                                        </button>
                                        
                                      </div>
                                      <div class="col-md-3">
                                        <button id="btnqty" class="button cash-opt bg-primary" data-mode="quantity">
                                          QTY [F8]
                                        </button>    
                                      </div>  
                                      <div class="col-md-3">
                                        <button id="btnprice" class="button cash-opt bg-primary" data-mode="price" style="font-size: inherit;">
                                          AMOUNT
                                        </button>
                                      </div>  
                                      <div class="col-md-3">
                                        <button id="backspace" class="button cash-opt bg-danger">
                                            {{-- <img style="pointer-events: none;" src="{{asset('assets/backspace.png')}}" width="24" height="21" alt="Backspace"> --}}
                                            <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                        </button>
                                      </div>  
                                    </div>
                                    <div class="row">
                                      <div class="col-6">
                                        <button id="btnolPay" class="button cash-opt-duo pay-10" data-mode="price">
                                          ONLINE PAYMENT <span id="olcount" class="badge badge-danger" style="width: 40px; height: 20px; padding-top:4px"></span>
                                        </button>
                                      </div>                                      
                                      <div class="col-5">
                                        <button id="btnEnterAmount" class="button cash-opt-duo pay-5" data-mode="" style="margin-left: -15px; width: 142px !important ">
                                          ENTER AMOUNT [F6]
                                        </button>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-6">
                                        <button id="btnnumLedger" class="button cash-opt-duo pay-3" data-mode="price">
                                          STUDENT LEDGER
                                        </button>
                                      </div>                                      
                                      <div class="col-6">
                                        <button id="btnnumAssessment" class="button cash-opt-duo pay-9" data-mode="price" style="border-bottom-right-radius: 0px !important;">
                                          ASSESSMENT
                                        </button>
                                      </div>                                      
                                    </div>
                                  </div>    
                                </div>
                                <button class="button pay-6 text-lg" style="border-bottom-right-radius: 4px !important; border-bottom-left-radius: 4px !important">
                                  SY: <span id="activesy"></span> | Sem: <span id="activesem"></span>
                                </button>
                              </div>
                              
                              {{-- <div class="numpad ">
                                <button class="input-button number-char">1</button>
                                <button class="input-button number-char">2</button>
                                <button class="input-button number-char">3</button>
                                <button id="btnqty" class="mode-button " data-mode="quantity">QTY [F8]</button>
                                <br>
                                <button class="input-button number-char">4</button>
                                <button class="input-button number-char">5</button>
                                <button class="input-button number-char">6</button>
                                <button id="btnmenu" class="mode-button">Menu [F2]</button>
                                <br>
                                <button class="input-button number-char">7</button>
                                <button class="input-button number-char">8</button>
                                <button class="input-button number-char">9</button>
                                <button id="btnprice" class="mode-button" data-mode="price">Price</button>
                                <br>
                                <button class="input-button numpad-minus">+/-</button>
                                <button class="input-button number-char">0</button>
                                <button class="input-button number-char">.</button>
                                <button id="backspace" class="input-button numpad-backspace">
                                    <img style="pointer-events: none;" src="{{asset('assets/backspace.png')}}" width="24" height="21" alt="Backspace">
                                </button>
                              </div> --}}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="rightpane">
                      <table class="layout-table">
                        <tbody>
                          <tr class="header-row">
                            <td class="header-cell">
                              <div>
                                <header class="rightpane-header">
                                  <div class="breadcrumbs">
                                    <span class="breadcrumb">
                                      <span class=" breadcrumb-button breadcrumb-home js-category-switch">
                                          <i class="fa fa-home" role="img" aria-label="Home" title="Home"></i>
                                      </span>
                                    </span>
                                  </div>
                                  @if(DB::table('schoolinfo')->first()->paymentplan == '1')
                                    <div class="rightpane-header text-left">
                                      <button id="btnpaymentplan" class="button bg-default text-md" style="height: 48px; background-color: #6EC89B; color: #fff" hidden="">
                                        PAYMENT PLAN
                                      </button>
                                      <span id="plandescription" class="text-md"></span>
                                    </div>
                                  @endif
                                  <div class="searchbox">
                                    <input id="txtitemsearch" placeholder="Search Items">
                                    <span class="search-clear left">
                                      <i class="fa fa-search"></i>
                                    </span>
                                    <span class="search-clear right">
                                      <i id="searchremove" class="fa fa-remove"></i>
                                    </span>
                                  </div>
                                  
                                </header>
        
                                <div class="categories">
                                  <div class="category-list-scroller touch-scrollable">
                                    <div class="category-list simple">
                                      <span id="cat_tuition" class="sel-category category-simple-button js-category-switch " data-category-id="1">
                                        TUITIONS
                                      </span>
                                      <span id="cat_items" class="category-simple-button js-category-switch" data-category-id="2">
                                        ITEMS
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>

                          <tr class="content-row">
                            <td class="content-cell">
                              <div class="content-container">
                                <div class="product-list-container">
                                  
                                  <div id="paySched" class="product-list-scroller touch-scrollable">
                                    <div id="payscheditems" class="product-list">

                                    </div>
                                  </div>

                                  {{-- <div id="payItem" class="product-list-scroller touch-scrollable oe_hidden"> --}}
                                    <div id="payscheditems" class="payItem product-list table-responsive product-list-scroller p-0 touch-scrollable oe_hidden" disabled="">
                                      <table class="table table-striped client-list table-head-fixed">
                                        <thead>
                                          <tr>
                                            <th class="text-center">DOWNPAYMENT</th>
                                            <th>ITEM CODE</th>
                                            <th>DESCRIPTION</th>
                                            <th>AMOUNT</th>
                                          </tr>
                                        </thead>
                                        <tbody id="item-list" data-id="1" style="cursor: pointer;">
                                          
                                        </tbody>
                                      </table>
                                    </div>
                                  {{-- </div> --}}

                                  <span class="placeholder-ScrollbarWidget"></span>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div id="cashmenu" class="menu-screen screen oe_hidden">
                    <div class="screen-content">
                      <section class="top-content">
                        <span class="button back">
                          <i class="fa fa-angle-double-left"></i>
                          Cancel
                        </span>
                      </section>
                      <section class="full-content">
                        <div class="window">
                          <section class="subwindow collapsed">
                              <div class="subwindow-container collapsed">
                                  <div class="subwindow-container-fix client-details-contents">
                                  </div>
                              </div>
                          </section>
                          <section class="subwindow">
                            <div class="subwindow-container">
                              <div class="subwindow-container-fix touch-scrollable scrollable-y">
                                
                                <article id="btnledger" class="menu pay-3">
                                  <div class="product-img">
                                    
                                    <span class="paysched-list" style="text-align:center">
                                        <i class="fa fa-book fontA mt-2"></i>
                                    </span>
                                  </div>
                                  <div class="menu-bot pay-2" id="7489">
                                    STUDENT LEDGER
                                  </div>
                                </article>

                                <article id="btntransactions" class="menu pay-7">
                                  <div class="product-img">
                                    
                                    <span class="paysched-list" style="text-align:center">
                                        <i class="fa fa-list-alt fontA mt-2"></i>
                                    </span>
                                  </div>
                                  <div class="menu-bot pay-2" id="7489">
                                    TRANSACTIONS
                                  </div>
                                </article>

                                <article id="btncashsum" class="menu pay-5">
                                  <div class="product-img">
                                    
                                    <span class="paysched-list" style="text-align:center">
                                        <i class="fa fa-list-alt fontB mt-2"></i>
                                    </span>
                                  </div>
                                  <div class="menu-bot pay-2" id="7489">
                                    CASH SUMMARY
                                  </div>
                                </article>
                                
                                <article id="btnORSetup" class="menu pay-10" data-toggle="modal" data-target="#modal-orsetup">
                                  <div class="product-img">
                                    <span class="paysched-list" style="text-align:center">
                                        <i class="fa fa-file mt-2"></i>
                                    </span>
                                  </div>
                                  <div class="menu-bot pay-2" id="">
                                    OR SETUP
                                  </div>
                                </article>

                                <article id="btnterminalSetup" class="menu pay-6 btnterminal" data-toggle="modal" data-target="#modal-terminalsetup">  
                                  <div class="product-img">
                                    
                                    <span class="paysched-list" style="text-align:center">
                                        <i class="fa fa-list-alt fontA mt-2"></i>
                                    </span>
                                  </div>
                                  <div class="menu-bot pay-2" id="">
                                    TERMINAL SETUP
                                  </div>
                                </article>

                                <article id="btnassessment" class="menu pay-9">  
                                  <div class="product-img">
                                    
                                    <span class="paysched-list" style="text-align:center">
                                        <i class="fa fa-align-right fontA mt-2"></i>
                                    </span>
                                  </div>
                                  <div class="menu-bot pay-2" id="">
                                    ASSESSMENT
                                  </div>
                                </article>

                                <article id="btnsoa" class="menu pay-3">
                                  <div class="product-img">
                                    
                                    <span class="paysched-list" style="text-align:center">
                                        <i class="fa fa-address-card fontA mt-2"></i>
                                    </span>
                                  </div>
                                  <div class="menu-bot pay-2" id="">
                                    STATEMENT OF ACCOUNT
                                  </div>
                                </article>
                                <article id="btnonlinepay" class="menu pay-10 swalDefaultInfo">  
                                  <div class="product-img">
                                    
                                    <span class="paysched-list" style="text-align:center">
                                        <i class="fa fa-diamond fontA mt-2"></i>
                                    </span>
                                  </div>
                                  <div class="menu-bot pay-2" id="">
                                    PAID ONLINE
                                  </div>
                                </article>

                                <article id="bookentry" class="menu pay-6">  
                                  <div class="product-img">
                                    
                                      <span class="paysched-list" style="text-align:center">
                                          <i class="fa fa-book fontB mt-2"></i>
                                      </span>
                                  
                                  </div>
                                  <div class="menu-bot pay-2" id="">
                                    BOOK ENTRY
                                  </div>
                                </article>

                                <article id="changepass" class="menu bg-orange" style="color: #fff !important">  
                                  <div class="product-img mt-2">
                                    
                                      <span class="paysched-list mt-2" style="text-align:center">
                                          <i class="fa fa-lock"></i>
                                      </span>
                                  
                                  </div>
                                  <div class="menu-bot pay-2 fontA mt-2" id="">
                                    PASSWORD
                                  </div>
                                </article>

                                <article id="logoutcash" class="menu pay-8">  
                                  <div class="product-img">
                                    
                                      <span class="paysched-list" style="text-align:center">
                                          <i class="fa fa-power-off fontA mt-2"></i>
                                      </span>
                                  
                                  </div>
                                  <div class="menu-bot pay-2" id="">
                                    LOGOUT
                                  </div>
                                </article>
                                

                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="oe_hidden">
                                  <button id="btnlogout">logout</button>
                                </a>
                                
                              </div>
                            </div>
                          </section>
                        </div>
                      </section>
                    </div>
                  </div>

                  <div id="studledger" class="studledger-screen screen oe_hidden">
                    <div class="screen-content">
                      <section class="top-content">
                        <span class="button back">
                          <i class="fa fa-angle-double-left"></i>
                          Cancel
                        </span>                          
                      </section>
                      <section class="full-content">
                        <div class="window">
                          <section class="subwindow collapsed">
                              <div class="subwindow-container collapsed">
                                  <div class="subwindow-container-fix client-details-contents">
                                  </div>
                              </div>
                          </section>
                          <section class="subwindow">
                            <div class="subwindow-container">
                              <div class="subwindow-container-fix touch-scrollable scrollable-y">
                                
                                
                                <div class="menu-header">
                                  Student Ledger
                                </div>
                                <div style="display: inline-block; float:right">
                                  <span id="btnprintledger" class="btn btn-info" style="margin-right:10px; margin-top:-25px">
                                    PRINT
                                  </span>
                                  @if(db::table('schoolinfo')->first()->snr == 'hcb')
                                    <span id="btnprintexampermit" class="btn btn-success" style="margin-right:10px; margin-top:-25px">
                                      Exam Permit
                                    </span>
                                  @endif
                                </div>
                                <div id="ledger-name" class="menu-name mt-2">
                                  
                                </div>
                                <div class="table-responsive mt-3">
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th>DATE</th>
                                        <th>PARTICULARS</th>
                                        <th class="text-center">CHARGES</th>
                                        <th class="text-center">PAYMENT</th>
                                        <th class="text-center">BALANCE</th>
                                      </tr>
                                    </thead>
                                    <tbody id="ledger-list" class="">
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </section>
                        </div>
                      </section>
                    </div>
                  </div>


                  <div id="ledger-print" class="receipt-screen screen oe_hidden">
                    <div class="screen-content">
                      <div class="top-content">
                        
                        <span id="" class="button back">
                            <i class="fa fa-angle-double-left"></i>
                            Close
                        </span>
                        
                        <div id="btnprintLedger-list" class="btn btn-primary print text-center float-right mt-3 mr-3" onclick="printFunction()">
                            <i class="fa fa-print"></i> Print
                        </div>
                      </div>
                      <div class="centered-content touch-scrollable">
                        <div class="pos-receipt-container">
                          <div id="cashOR" class="pos-receipt">
                            <div class="row">
                              <div class="col-6">
                                @php
                                  $schoolinfo = App\CashierModel::schoolinfo();
                                @endphp
                                <h5>{{$schoolinfo->schoolname}}</h5>
                              </div>  
                              <div class="col-6 text-right text-bold">
                                STUDENT LEDGER
                              </div>  
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <h6>{{$schoolinfo->address}}</h6>
                              </div>  
                              <div class="col-6 text-right">
                                School Year: <span id="ledger_sy"></span>
                              </div>  
                            </div>

                            <div class="row">
                              <div class="col-6">
                                {{-- <h6>Cagayan de Oro City</h6> --}}
                              </div>  
                            </div>
                            
                            <div class="row text-bold">
                              <div class="col-10 mb-2">
                                Name: <span id="print-ledger-name"></span>
                              </div>
                            </div>

                            <div class="row">
                              <div class="table-responsive">
                                <table class="table">
                                  <tr>
                                    <th>DATE</th>
                                    <th>PARTICULARS</th>
                                    <th class="text-center">CHARGES</th>
                                    <th class="text-center">PAYMENT</th>
                                    <th class="text-center">BALANCE</th>
                                  </tr>
                                  <tbody id="Ledgerprint-list">
                                    
                                  </tbody>
                                </table>
                                <div class="mt-2">
                                  Prepared by: <span class="text-bold"><u>{{Auth::user()->name}}</u></span>
                                </div>
                                
                              </div>
                              
                            </div>
                            
                          </div>
                        
                        </div>
                        <div class="divFooter">
                          <div class="row">
                            <div class="col-6">Printed on: <span id="LedgerprintDT" class="text-bold"></span></div>
                            <div id="" class="pageFooter col-6 text-right"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>


                  <div id="cashiertrans" class="cashiertrans-screen screen oe_hidden">
                    <div class="screen-content">
                      <section class="top-content">
                        <span class="button back">
                          <i class="fa fa-angle-double-left"></i>
                          Cancel
                        </span>
                      </section>
                      <section class="full-content">
                        <div class="window">
                          <section class="subwindow collapsed">
                              <div class="subwindow-container collapsed">
                                  <div class="subwindow-container-fix client-details-contents">
                                  </div>
                              </div>
                          </section>
                          <section class="subwindow">
                            <div class="subwindow-container">

                              <div class="subwindow-container-fix touch-scrollable scrollable-y">
                                <div class="menu-header">
                                  Cashier Transaction
                                </div>
                                <form>
                                  <div style="display: inline-block; float:right">
                                    <span id="btnprintcashtrans" class="btn btn-info" style="margin-right:10px; margin-top:-25px">
                                      PRINT
                                    </span>
                                  </div>
                                  <div class="row">
                                    <div class="mt-2 col-md-2">
                                      <div class="form-group">
                                        <h5>Terminal</h5>
                                        <select id="terminals" class="form-control">
                                          
                                        </select>
                                      </div>
                                    </div>
                                    <div class="mt-2 col-md-3">
                                      <div class="form-group">
                                        <h5>Date From</h5>
                      
                                        <div class="input-group">
                                          <input id="dtFrom" type="date" class="form-control">
                                        </div>
                                        <!-- /.input group -->
                                      </div>
                                    </div>

                                    <div class="mt-2 col-md-3">
                                      <div class="form-group">
                                        <h5>Date To</h5>
                      
                                        <div class="input-group">
                                          <input id="dtTo" type="date" class="form-control">
                                        </div>
                                        <!-- /.input group -->
                                      </div>
                                    </div>

                                    <div class="mt-2 col-2">
                                      <div class="form-group">
                                        <h5>Search</h5>
                      
                                        <div class="input-group">
                                          <input id="strans" type="text" class="form-control" placeholder="Search OR">
                                        </div>
                                        <!-- /.input group -->
                                      </div>
                                    </div>

                                    <div class="col-2" style="margin-top: 23px">
                                      <span id="viewTrans" style="margin-top: 19px" class="btn btn-info btn-sm">SEARCH</span>
                                      <span id="postTrans" style="margin-top: 19px;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-confirm-post">POST</span>
                                    </div>
                                  </div>
                                </form>
                                <div class="table-responsive mt-3">
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th>DATE</th>
                                        <th>OR NO.</th>
                                        <th>NAME</th>
                                        <th>AMOUNT</th>
                                        <th>POSTED</th>
                                        <th>CASHIER</th>
                                        <th>PAYMENT TYPE</th>
                                      </tr>
                                    </thead>
                                    <tbody id="trans-list" class="">
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </section>
                        </div>
                      </section>
                    </div>
                  </div>

                  <div id="cashiertrans-print" class="receipt-screen screen oe_hidden">
                    <div class="screen-content">
                      <div class="top-content">
                        
                        <span id="back-cashtrans" class="button back">
                            <i class="fa fa-angle-double-left"></i>
                            Close
                        </span>
                        
                        <div id="btnprintcashtrans-list" class="btn btn-primary print text-center float-right mt-3 mr-3">
                            <i class="fa fa-print"></i> Print
                        </div>
                      </div>
                      <div class="centered-content touch-scrollable">
                        <div class="pos-receipt-container">
                          <div id="cashOR" class="pos-receipt">
                            <div class="row">
                              <div class="col-6">
                                @php
                                  $schoolinfo = App\CashierModel::schoolinfo();
                                @endphp
                                <h5>{{$schoolinfo->schoolname}}</h5>
                              </div>  
                              <div class="col-6 text-right text-bold">
                                CASHIER TRANSACTIONS
                              </div>  
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <h6>{{$schoolinfo->address}}</h6>
                              </div>  
                              <div class="col-6 text-right">
                                DATE: <span id="cashtrans_DT"></span>
                              </div>  
                            </div>

                            <div class="row">
                              <div class="col-6">
                                {{-- <h6>Cagayan de Oro City</h6> --}}
                              </div>  
                            </div>

                            <div class="row">
                              <div class="table-responsive">
                                <table class="table">
                                  <tr>
                                        <th></th>
                                        <th>DATE</th>
                                        <th>OR NO.</th>
                                        <th>NAME</th>
                                        <th>AMOUNT</th>
                                        <th>POSTED</th>
                                        <th>CASHIER</th>
                                        <th>PAYMENT TYPE</th>
                                  </tr>
                                  <tbody id="trans-list-print">
                                    
                                  </tbody>
                                </table>
                                <div class="mt-2">
                                  Prepared by: <span class="text-bold"><u>{{Auth::user()->name}}</u></span>
                                </div>
                                
                              </div>
                              
                            </div>
                            
                          </div>
                        
                        </div>
                        <div class="divFooter">
                          <div class="row">
                            <div class="col-6">Printed on: <span id="cashtransPrintDate" class="text-bold"></span></div>
                            <div id="" class="pageFooterTrans col-6 text-right"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="cashsummary" class="cashsummary-screen screen oe_hidden">
                    <div class="screen-content">
                      <section class="top-content">
                        <span class="button back">
                          <i class="fa fa-angle-double-left"></i>
                          Cancel
                        </span>
                      </section>
                      <section class="full-content">
                        <div class="window">
                          <section class="subwindow collapsed">
                              <div class="subwindow-container collapsed">
                                  <div class="subwindow-container-fix client-details-contents">
                                  </div>
                              </div>
                          </section>
                          <section class="subwindow">
                            <div class="subwindow-container">
                              <div class="subwindow-container-fix touch-scrollable scrollable-y">
                                <div class="menu-header">
                                  Cash Receipt Summary
                                </div>
                                <div style="display: inline-block; float:right">
                                  <span id="btnprintCRS" class="btn btn-info" style="margin-right:10px; margin-top:-25px">
                                    PRINT
                                  </span>
                                </div>
                                <div class="row">
                                  <div class="mt-2 col-3">
                                    <div class="form-group">
                                      <h5>Date From</h5>
                    
                                      <div class="input-group">
                                        <input id="CRSdtFrom" type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" im-insert="false" placeholder="mm/dd/yyyy">
                                      </div>
                                      <!-- /.input group -->
                                    </div>
                                  </div>

                                  <div class="mt-2 col-3">
                                    <div class="form-group">
                                      <h5>Date To</h5>
                    
                                      <div class="input-group">
                                        <input id="CRSdtTo" type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" im-insert="false" placeholder="mm/dd/yyyy">
                                      </div>
                                      <!-- /.input group -->
                                    </div>
                                  </div>

                                  

                                  <div class="col-2" style="margin-top: 23px">
                                    <span id="genCRS" style="margin-top: 19px" class="btn btn-info btn-sm">GENERATE</span>
                                  </div>
                                </div>

                                <div class="table-responsive mt-3 text-md">
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th>ACCOUNT</th>
                                        <th>DEPARTMENT</th>
                                        <th class="text-center">DEBIT</th>
                                        <th class="text-center">CREDIT</th>
                                      </tr>
                                    </thead>
                                    <tbody id="cashsummary-list" class="">
                                    </tbody>
                                    <tfoot>
                                      <tr id="cashsummaryTotal" class="bg-info oe_hidden">
                                        
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </section>
                        </div>
                      </section>
                    </div>
                  </div>

                  <div id="cashsummary-print" class="receipt-screen screen oe_hidden">
                    <div class="screen-content">
                      <div class="top-content">
                        
                        <span id="" class="button back">
                            <i class="fa fa-angle-double-left"></i>
                            Close
                        </span>
                        
                        <div id="btnprintCRS-list" class="btn btn-primary print text-center float-right mt-3 mr-3" onclick="printFunction()">
                            <i class="fa fa-print"></i> Print
                        </div>
                      </div>
                      <div class="centered-content touch-scrollable">

                        {{-- <button   class="button print">Print Receipt</button> --}}

                        <div class="pos-receipt-container">
                          <div id="cashOR" class="pos-receipt">
                            <div class="row">
                              <div class="col-6">
                                @php
                                  $schoolinfo = App\CashierModel::schoolinfo();
                                @endphp
                                <h5>{{$schoolinfo->schoolname}}</h5>
                              </div>  
                              <div class="col-6 text-right text-bold">
                                CASH RECEIPT SUMMARY
                              </div>  
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <h6>{{$schoolinfo->address}}</h6>
                              </div>  
                              <div class="col-6 text-right">
                                Period: <span id="crs_date"></span>
                              </div>  
                            </div>

                            <div class="row">
                              <div class="col-6">
                                {{-- <h6>Cagayan de Oro City</h6> --}}
                              </div>  
                            </div>
                            
                            <div class="row">
                              <div class="table-responsive">
                                <table class="table">
                                  <tr>
                                    <th>ACCOUNT</th>
                                    <th>DEPARTMENT</th>
                                    <th class="text-center">DEBIT</th>
                                    <th class="text-center">CREDIT</th>
                                  </tr>
                                  <tbody id="CRSprint-list">
                                    
                                  </tbody>
                                  <tfoot id="CRSprint-list-foot">
                                  
                                  </tfoot>
                                </table>
                                
                              </div>
                              
                            </div>
                            
                          </div>
                        
                        </div>
                        <div class="divFooter">
                          <div class="row">
                            <div class="col-6">Printed on: <span id="printDT" class="text-bold"></span></div>
                            <div id="" class="pageFooter col-6 text-right"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="studassessment" class="studledger-screen screen oe_hidden">
                    <div class="screen-content">
                      <section class="top-content">
                        <span class="button back">
                          <i class="fa fa-angle-double-left"></i>
                          Cancel
                        </span>
                      </section>
                      <section class="full-content">
                        <div class="window">
                          <section class="subwindow collapsed">
                              <div class="subwindow-container collapsed">
                                  <div class="subwindow-container-fix client-details-contents">
                                  </div>
                              </div>
                          </section>
                          <section class="subwindow">
                            <div class="subwindow-container">
                              <div class="subwindow-container-fix touch-scrollable scrollable-y">
                                
                                <div class="menu-header">
                                  ASSESSMENT
                                </div>
                                <div style="display: inline-block; float:right">
                                  <span id="btnprintassessment" class="btn btn-info" style="margin-right:10px; margin-top:-25px">
                                    PRINT
                                  </span>
                                </div>
                                <div id="assessment-name" class="menu-name mt-2">
                                  
                                </div>
                                <div class="mt-2 ml-2 row">
                                  <div class="col-1 text-md mt-1">
                                    Month:  
                                  </div>
                                  <div class="col-3">
                                    <div class="form-group">
                                      <select id="monthsetup" class="form-control">
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <span id="btngenassessment" class="btn btn-primary">
                                      Generate
                                    </span>
                                  </div>
                                  <div class="col-2 text-md mt-1" style="margin-left: -52px">
                                    <div class="form-group clearfix">
                                      <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="showall">
                                        <label for="showall" class="text-md">
                                          Show all
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="table-responsive mt-3 text-md">
                                  <table id="tb-assessment" class="table table-striped" hidden="">
                                    <thead>
                                      <tr>
                                        <th>PARTICULARS</th>
                                        <th class="text-center">AMOUNT DUE</th>
                                        <th class="text-center">AMOUNT PAID</th>
                                        <th class="text-center">BALANCE</th>
                                      </tr>
                                    </thead>
                                    <tbody id="assessment-list" class="">
                                      
                                    </tbody>
                                    <tfoot id="assessment-list-footer">
                                      
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </section>
                        </div>
                      </section>
                    </div>
                  </div>
                  @include('include.blade.statementofaccount')
                  <div id="assessment-print" class="receipt-screen screen oe_hidden">
                    <div class="screen-content">
                      <div class="top-content">
                        
                        <span id="" class="button back">
                            <i class="fa fa-angle-double-left"></i>
                            Close
                        </span>
                        
                        <div id="btnprintAssessment-list" class="btn btn-primary print text-center float-right mt-3 mr-3" onclick="printFunction()">
                            <i class="fa fa-print"></i> Print
                        </div>
                      </div>
                      <div class="centered-content touch-scrollable">
                        <div class="pos-receipt-container">
                          <div id="cashOR" class="pos-receipt">
                            <div class="row">
                              <div class="col-6">
                                @php
                                  $schoolinfo = App\CashierModel::schoolinfo();
                                @endphp
                                <h5>{{$schoolinfo->schoolname}}</h5>
                              </div>  
                              <div class="col-6 text-right text-bold">
                                ASSESSMENT
                              </div>  
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <h6>{{$schoolinfo->address}}</h6>
                              </div>  
                              <div class="col-6 text-right">
                                Due for the month of: <span id="assessment_date"></span>
                              </div>  
                            </div>

                            <div class="row">
                              <div class="col-6">
                                {{-- <h6>Cagayan de Oro City</h6> --}}
                              </div>  
                            </div>
                            
                            <div class="row text-bold">
                              <div class="col-10 mb-2">
                                Name: <span id="print-assessment-name"></span>
                              </div>
                            </div>

                            <div class="row">
                              <div class="table-responsive">
                                <table class="table">
                                  <tr>
                                    <th>PARTICULARS</th>
                                    <th>AMOUNT DUE</th>
                                    <th class="text-center">AMOUNT PAID</th>
                                    <th class="text-center">BALANCE</th>
                                  </tr>
                                  <tbody id="Assessmentprint-list">
                                    
                                  </tbody>
                                  <tfoot id="Assessmentprint-list-foot">
                                  
                                  </tfoot>
                                </table>
                                <div class="mt-2">
                                  Prepared by: <span class="text-bold"><u>{{Auth::user()->name}}</u></span>
                                </div>
                                
                              </div>
                              
                            </div>
                            
                          </div>
                        
                        </div>
                        <div class="divFooter">
                          <div class="row">
                            <div class="col-6">Printed on: <span id="AssessmentprintDT" class="text-bold"></span></div>
                            <div id="" class="pageFooter col-6 text-right"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>






                  <div id="studlist" class="clientlist-screen screen oe_hidden">
                    <div class="screen-content">
                      <section class="top-content">
                        <span class="button back">
                          <i class="fa fa-angle-double-left"></i>
                          Cancel
                        </span>
                        <span class="searchbox">
                          <input id="searchstud" placeholder="Search Students" onkeyup="this.value = this.value.toUpperCase();">
                          <span class="search-clear"></span>
                        </span>
                        <span class="searchbox"></span>
                        <span class="button new-customer" role="img" aria-label="Add a customer" title="Add a customer">
                          <i class="fa fa-user"></i>
                          <i class="fa fa-plus"></i>
                        </span>
                        <span id="selcustomer" class="button next oe_hidden highlight" selstud-id="" selstud-name="">
                          Select Student
                          <i class="fa fa-angle-double-right"></i>
                          <input type="hidden" id="selectedstud">
                        </span>
                      </section>
                      <section class="full-content">
                        <div class="window">
                          <section class="subwindow collapsed">
                              <div class="subwindow-container collapsed">
                                  <div class="subwindow-container-fix client-details-contents">
                                  </div>
                              </div>
                          </section>
                          <section class="subwindow">
                            <div class="subwindow-container">
                              <div class="subwindow-container-fix touch-scrollable scrollable-y">
                                <table class="client-list">
                                  <thead>
                                    <tr>
                                      <th>ID NO</th>
                                      <th>NAME</th>
                                      <th>GRADE LEVEL</th>
                                      <th>SECTION</th>
                                      <th>GRANTEE</th>
                                      <th>STATUS</th>
                                    </tr>
                                  </thead>
                                  <tbody class="client-list-contents text-sm">
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </section>
                        </div>
                      </section>
                    </div>
                  </div>

                  <div id="receipt" class="receipt-screen screen oe_hidden">
                    <div class="screen-content">
                      <div class="top-content">
                        <h1>Change: <span class="change-value">0.00</span></h1>
                        <span id="nextTrans" class="button next">
                            Next Transaction
                            <i class="fa fa-angle-double-right"></i>
                        </span>
                      </div>
                      <div class="centered-content touch-scrollable">
                        <div class="button print_invoice" style="display: none;">
                            <i class="fa fa-print"></i> Print Invoice
                        </div>

                        {{-- <button   class="button print">Print Receipt</button> --}}

                        <div class="button print" id="prntOR">

                            <i class="fa fa-print"></i> Print Receipt
                        </div>
                        <div class="pos-receipt-container">

                          <div class="pos-receipt-container">
                            <div id="cashOR" class="pos-receipt">
                              <div class="row">
                                <div class="col-6">
                                  @php
                                    $schoolinfo = App\CashierModel::schoolinfo();
                                  @endphp
                                  <h5>{{$schoolinfo->schoolname}}</h5>
                                </div>  
                                <div class="col-6 text-right">
                                  SAMPLE RECEIPT
                                </div>  
                              </div>
                              <div class="row">
                                <div class="col-6">
                                  <h6>{{$schoolinfo->address}}</h6>
                                </div>  
                                <div class="col-6 text-right">
                                  Date: <span id="r_date"></span>
                                </div>  
                              </div>

                              <div class="row">
                                <div class="col-6">
                                  {{-- <h6>Cagayan de Oro City</h6> --}}
                                </div>  
                                <div class="col-6 text-right">
                                  <h6 class="text-danger">No. <span id="r_ornum" class="text-danger text-bold"></span></h5>
                                </div>  
                              </div>
                              
                              <div class="row mt-2 border-bottom border-secondary">
                                <div class="col-3">ID No. <span id="r_sid" class="text-bold"></span></div>  
                                <div class="col-5">Name: <span id="r_name" class="text-bold"></span></div>
                                <div class="col-4">Grade and Section: <span id="r_gradesection" class="text-bold"></span></div>
                              </div>

                              <div class="row mt-1 mb-1">
                                <div class="col-12">
                                  RECEIVED IN THE AMOUNT OF &nbsp;&nbsp;&nbsp;<span id="words" class="text-bold border-bottom" style="width: 100%"><u></u></span>  
                                </div>
                              </div>

                              <div class="row mt-3 mb-2">
                                <div class="col-12">
                                  AS PAYMENT FOR
                                </div>                                
                              </div>
                              
                              <div class="row">
                                <div class="col-5 border text-center text-bold">PARTICULARS</div>
                                <div class="col-3 border text-bold text-center">AMOUNT</div>
                              </div>
                              <div id="r_items">
                                
                             </div>
                              <div class="row">
                                <div class="col-8 border-bottom text-right text-md text-bold">TOTAL: &nbsp;&nbsp;&nbsp;<span id="number" class="border-bottom border-secondary">275.00</span></div>
                              </div>
                              <div class="row mt-3">
                                Issued by: &nbsp;&nbsp;&nbsp;<span class="text-bold"><u>{{strtoupper(auth()->user()->name)}}</u></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="viewreceipt" class="receipt-screen screen oe_hidden">
                    <div class="screen-content">
                      <div class="top-content">
                        <span id="btnview-back" class="button back">
                            Back
                            <i class="fa fa-angle-double-left"></i>
                        </span>
                      </div>
                      <div class="centered-content touch-scrollable">
                        <div class="button print_invoice" style="display: none;">
                            <i class="fa fa-print"></i> Print Invoice
                        </div>

                        {{-- <button   class="button print">Print Receipt</button> --}}

                        <div class="button print" onclick="printFunction()">

                            <i class="fa fa-print"></i> Print Receipt
                        </div>
                        <div class="pos-receipt-container">
                          <div class="pos-receipt-container">
                            <div id="cashOR" class="pos-receipt">
                                <img class="voidoverlay" hidden src="{{asset('assets/img/void-stamp.png')}}">
                              <div class="row">
                                <div class="col-6">
                                  @php
                                    $schoolinfo = App\CashierModel::schoolinfo();
                                  @endphp
                                  <h5>{{$schoolinfo->schoolname}}</h5>
                                </div>  
                                <div class="col-6 text-right">
                                  SAMPLE RECEIPT
                                </div>  
                              </div>
                              <div class="row">
                                <div class="col-6">
                                  <h6>{{$schoolinfo->address}}</h6>
                                </div>  
                                <div class="col-6 text-right">
                                  Date: <span id="v_date"></span>
                                </div>  
                              </div>

                              <div class="row">
                                <div class="col-6">
                                  {{-- <h6>Cagayan de Oro City</h6> --}}
                                </div>  
                                <div class="col-6 text-right">
                                  <h6 class="text-danger">No. <span id="v_ornum" class="text-danger text-bold"></span></h5>
                                </div>  
                              </div>
                              
                              <div class="row mt-2 border-bottom border-secondary">
                                <div class="col-3">ID No. <span id="v_sid" class="text-bold"></span></div>  
                                <div class="col-5">Name: <span id="v_name" class="text-bold"></span></div>
                                <div class="col-4">Grade and Section: <span id="v_gradesection" class="text-bold"></span></div>
                              </div>

                              <div class="row mt-1 mb-1">
                                <div class="col-12">
                                  RECEIVED IN THE AMOUNT OF &nbsp;&nbsp;&nbsp;<span id="v_words" class="text-bold border-bottom" style="width: 100%"><u></u></span>  
                                </div>
                              </div>

                              <div class="row mt-3 mb-2">
                                <div class="col-12">
                                  AS PAYMENT FOR
                                </div>                                
                              </div>
                              
                              <div class="row">
                                <div class="col-5 border text-center text-bold">PARTICULARS</div>
                                <div class="col-3 border text-bold text-center">AMOUNT</div>
                              </div>
                              <div id="v_items">
                                
                             </div>
                              <div class="row">
                                <div class="col-8 border-bottom text-right text-md text-bold">TOTAL: &nbsp;&nbsp;&nbsp;<span id="v_number" class="border-bottom border-secondary">0.00</span></div>
                              </div>
                              <div class="row mt-3">
                                Issued by: &nbsp;&nbsp;&nbsp;<span class="text-bold"><u>{{strtoupper(auth()->user()->name)}}</u></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="payment-screen screen oe_hidden">
                    <div class="screen-content">
                      <div class="top-content">
                        <span id="backPayment" class="button back">
                          <i class="fa fa-angle-double-left"></i>
                          Back
                        </span>
                        <h3 id="inputOR" class="mt-3"><span id="txtPaymethod"></span> | OR No.: <strong id='cOR'></strong></h3>
                        <span id="btnPay" class="button next disabled" data-validate="">
                          PAY
                          <i class="fa fa-angle-double-right"></i>
                        </span>
                      </div>
                    <div class="left-content pc40 touch-scrollable scrollable-y">

                      <div class="paymentmethods-container">
                      <div class="paymentmethods">
                    <div id="paymenttypes">

                      @foreach(App\CashierModel::paytype() as $type)
                        <div id="{{$type->description}}" class="button paymentmethod" data-id="{{$type->id}}">
                          {{$type->description}}
                        </div>                        
                      @endforeach
                    </div>
                  </div>

                </div>

              </div>
              <div class="right-content pc60 touch-scrollable scrollable-y">

                <section class="paymentlines-container">
                  <div class="paymentlines-empty">
                    <div id="totalamount" class="total">
                      
                    </div>
                    <div class="message">
                      Please select a payment method.
                    </div>
                  </div>
                </section>
                <section id="chequeinfo" class="payment-info oe_hidden">
                  <div class="form-group" style="padding: 1em !important">
                    <div class="row">
                      <div class="col-md-8">    
                        <input id="bankname" type="text" placeholder="Bank Name" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                      </div>
                    </div>
                    <div class="row mb-2 mt-2">
                      <div class="col-md-8">
                        <input id="chequeno" type="text" class="form-control" placeholder="Cheque Number">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <input id="chequedate" type="text" placeholder="Cheque Date" class="form-control" placeholder="Cheque Date" onfocus="(this.type='date')">
                      </div>
                    </div>
                    
                    
                  </div>
                </section>

                <section id="bankinfo" class="payment-info oe_hidden">
                  <div class="form-group" style="padding: 1em !important">
                    <div class="row">
                      <div class="col-md-8">
                        <input id="bankbankname" type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase();" placeholder="Bank Name">
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-md-8">
                        <input id="bankrefno" type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase();" placeholder="Reference Number">
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-md-8">
                        <input id="paytransdate" type="date" class="form-control" placeholder="Transaction Date" data-toggle="tooltip" title="Transaction Date">
                      </div>
                    </div>
                  </div>
                </section>

                <section id="remittanceinfo" class="payment-info oe_hidden">
                  <div class="form-group" style="padding: 1em !important">
                    <div class="row">
                      <div class="col-md-8">
                        <input id="refno" type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase();" placeholder="Reference Number">
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-md-8">
                        <input id="remtransdate" type="date" class="form-control">
                      </div>
                    </div>
                  </div>
                </section>

                <section class="payment-numpad oe_hidden">
                  <div class="numpad">
                    <button class="input-button number-char" data-action="1">1</button>
                    <button class="input-button number-char" data-action="2">2</button>
                    <button class="input-button number-char" data-action="3">3</button>
                    <button class="mode-button" data-action="+10">+10</button>
                    <br>
                    <button class="input-button number-char" data-action="4">4</button>
                    <button class="input-button number-char" data-action="5">5</button>
                    <button class="input-button number-char" data-action="6">6</button>
                    <button class="mode-button" data-action="+20">+20</button>
                    <br>
                    <button class="input-button number-char" data-action="7">7</button>
                    <button class="input-button number-char" data-action="8">8</button>
                    <button class="input-button number-char" data-action="9">9</button>
                    <button class="mode-button" data-action="+50">+50</button>
                    <br>
                    <button class="input-button numpad-char" data-action="-">+/-</button>
                    <button class="input-button number-char" data-action="0">0</button>
                    <button class="input-button number-char" data-action=".">.</button>
                    <button class="input-button numpad-backspace" data-action="BACKSPACE">
                        <img src="{{asset('assets/backspace.png')}}" width="24" height="21" alt="Backspace">
                    </button>
                  </div>
                </section>

                <div class="btn btn-info btn-lg ml-3 mt-3">
                  <div id="custName" class="button" data-value="">
                    <i class="fa fa-user" role="img" aria-label="Customer" title="Customer"></i>
                    <span class="js_customer_name">Payor</span>
                  </div>
                    
                  <div class="button js_email oe_hidden">
                    <i class="fa fa-inbox"></i> Email
                  </div>
                </div>

                <hr>
                <div class="row">
                  <div class="col-md-3 mt-2 ml-3 text-md">
                    ADJUST DATE:
                  </div>
                  <div class="col-md-8">
                    <input type="date" id="adjdate" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="keyboard_frame">
    <ul class="keyboard simple_keyboard">
        <li class="symbol firstitem row_qwerty"><span class="off">q</span><span class="on">1</span></li>
        <li class="symbol"><span class="off">w</span><span class="on">2</span></li>
        <li class="symbol"><span class="off">e</span><span class="on">3</span></li>
        <li class="symbol"><span class="off">r</span><span class="on">4</span></li>
        <li class="symbol"><span class="off">t</span><span class="on">5</span></li>
        <li class="symbol"><span class="off">y</span><span class="on">6</span></li>
        <li class="symbol"><span class="off">u</span><span class="on">7</span></li>
        <li class="symbol"><span class="off">i</span><span class="on">8</span></li>
        <li class="symbol"><span class="off">o</span><span class="on">9</span></li>
        <li class="symbol lastitem"><span class="off">p</span><span class="on">0</span></li>

        <li class="symbol firstitem row_asdf"><span class="off">a</span><span class="on">@</span></li>
        <li class="symbol"><span class="off">s</span><span class="on">#</span></li>
        <li class="symbol"><span class="off">d</span><span class="on">%</span></li>
        <li class="symbol"><span class="off">f</span><span class="on">*</span></li>
        <li class="symbol"><span class="off">g</span><span class="on">/</span></li>
        <li class="symbol"><span class="off">h</span><span class="on">-</span></li>
        <li class="symbol"><span class="off">j</span><span class="on">+</span></li>
        <li class="symbol"><span class="off">k</span><span class="on">(</span></li>
        <li class="symbol lastitem"><span class="off">l</span><span class="on">)</span></li>

        <li class="symbol firstitem row_zxcv"><span class="off">z</span><span class="on">?</span></li>
        <li class="symbol"><span class="off">x</span><span class="on">!</span></li>
        <li class="symbol"><span class="off">c</span><span class="on">"</span></li>
        <li class="symbol"><span class="off">v</span><span class="on">'</span></li>
        <li class="symbol"><span class="off">b</span><span class="on">:</span></li>
        <li class="symbol"><span class="off">n</span><span class="on">;</span></li>
        <li class="symbol"><span class="off">m</span><span class="on">,</span></li>
        <li class="delete lastitem">delete</li>

        <li class="numlock firstitem row_space"><span class="off">123</span><span class="on">ABC</span></li>
        <li class="space">&nbsp;</li>
        <li class="symbol"><span class="off">.</span><span class="on">.</span></li>
        <li class="return lastitem">return</li>
    </ul>
    <p class="close_button">close</p>
  </div>
    <div class="debug-widget oe_hidden">
      <h1>Debug Window</h1>
      <div class="toggle" title="Dismiss" role="img" aria-label="Dismiss"><i class="fa fa-times"></i></div>
      <div class="content">
        <p class="category">Electronic Scale</p>
        <ul>
          <li><input type="text" class="weight"></li>
          <li class="button set_weight">Set Weight</li>
          <li class="button reset_weight">Reset</li>
        </ul>

        <p class="category">Barcode Scanner</p>
        <ul>
          <li><input type="text" class="ean"></li>
          <li class="button barcode">Scan</li>
          <li class="button custom_ean">Scan EAN-13</li>
        </ul>

        <p class="category">Orders</p>
        <ul>
          <li class="button delete_orders">Delete Paid Orders</li>
          <li class="button delete_unpaid_orders">Delete Unpaid Orders</li>
          <li class="button export_paid_orders">Export Paid Orders</li>
          <a><li class="button download_paid_orders oe_hidden">Download Paid Orders</li></a>
          <li class="button export_unpaid_orders">Export Unpaid Orders</li>
          <a><li class="button download_unpaid_orders oe_hidden">Download Unpaid Orders</li></a>
          <li class="button import_orders" style="position:relative">
              Import Orders
              <input type="file" style="opacity:0;position:absolute;top:0;left:0;right:0;bottom:0;margin:0;cursor:pointer">
          </li>
        </ul>

        <p class="category">Hardware Status</p>
        <ul>
          <li class="status weighing">Weighing</li>
          <li class="button display_refresh">Refresh Display</li>
        </ul>
        <p class="category">Hardware Events</p>
        <ul>
          <li class="event open_cashbox">Open Cashbox</li>
          <li class="event print_receipt">Print Receipt</li>
          <li class="event scale_read">Read Weighing Scale</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="popups">              
    <div role="dialog" class="modal-dialog oe_hidden">
      <div class="popup popup-alert">
        <p class="title">Alert</p>
        <p class="body"></p>
        <div class="footer">
          <div class="button cancel">
            Ok
          </div>
        </div>
      </div>
    </div>
    <div role="dialog" class="modal-dialog oe_hidden">
      <div class="popup popup-error">
        <p class="title">Error</p>
        <p class="body"></p>
        <div class="footer">
          <div class="button cancel">
            Ok
          </div>
        </div>
      </div>
    </div>
    <div role="dialog" class="modal-dialog oe_hidden">
      <div class="popup popup-error">
        <header class="title">Error</header>
        <main class="body traceback"></main>
        <footer class="footer">
          <div class="button cancel">
            Ok
          </div>
          <div class="button stop_showing_sync_errors">
            Don't show again
          </div>
        </footer>
      </div>
      </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-error">
            <header class="title">Error</header>
            <main class="body traceback"></main>
            <footer class="footer">
              <div class="button cancel">
                Ok
              </div>
              <a>
                <div class="button icon download_error_file oe_hidden">
                  <i class="fa fa-arrow-down" role="img" aria-label="Download error" title="Download error"></i>
                </div>
              </a>
              <div class="button icon download">
                <i class="fa fa-download" role="img" aria-label="Download" title="Download"></i>
              </div>
              <div class="button icon email">
                <i class="fa fa-paper-plane" role="img" aria-label="Send by email" title="Send by email"></i>
              </div>
            </footer>
          </div>
        </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-barcode">
            <header class="title">Unknown Barcode
              <br>
              <span class="barcode"></span>
            </header>
            <main class="body">
                The Point of Sale could not find any product, client, employee
                or action associated with the scanned barcode.
            </main>
            <footer class="footer">
              <div class="button cancel">
                Ok
              </div>
            </footer>
          </div>
        </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-confirm">
            <header class="title">Confirm ?</header>
            <main class="body"></main>
            <footer class="footer">
              <div class="button confirm">
                Confirm
              </div>
              <div class="button cancel">
                Cancel
              </div>
            </footer>
          </div>
        </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-selection">
            <header class="title">Select</header>
            <div class="selection scrollable-y touch-scrollable">
                
            </div>
            <footer class="footer">
              <div class="button cancel">
                Cancel
              </div>
            </footer>
          </div>
        </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-textinput">
            <header class="title"></header>
            <input type="text" value="">
            <div class="footer">
              <div class="button confirm">
                Ok
              </div>
              <div class="button cancel">
                Cancel
              </div>
            </div>
          </div>
        </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-textinput">
            <header class="title"></header>
            <textarea></textarea>
            <footer class="footer">
              <div class="button confirm">
                Ok
              </div>
              <div class="button cancel">
                Cancel
              </div>
            </footer>
          </div>
        </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-text">
            <header class="title"></header>
            <main class="packlot-lines">
                
            </main>
            <footer class="footer">
              <div class="button confirm">
                Ok
              </div>
              <div class="button cancel">
                Cancel
              </div>
            </footer>
          </div>
        </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-number">
            <header class="title"></header>
            <div class="popup-input value active">
                
            </div>
            <div class="popup-numpad">
              <button class="input-button number-char" data-action="1">1</button>
              <button class="input-button number-char" data-action="2">2</button>
              <button class="input-button number-char" data-action="3">3</button>
              
              <button class="mode-button add" data-action="+10">+10</button>
              
              <br>
              <button class="input-button number-char" data-action="4">4</button>
              <button class="input-button number-char" data-action="5">5</button>
              <button class="input-button number-char" data-action="6">6</button>
              
              <button class="mode-button add" data-action="+20">+20</button>
              
              <br>
              <button class="input-button number-char" data-action="7">7</button>
              <button class="input-button number-char" data-action="8">8</button>
              <button class="input-button number-char" data-action="9">9</button>
              
              <button class="mode-button add" data-action="+50">+50</button>
              
              <br>
              <button class="input-button numpad-char" data-action="CLEAR">C</button>
              <button class="input-button number-char" data-action="0">0</button>
              <button class="input-button number-char dot"></button>
              <button class="input-button numpad-backspace" data-action="BACKSPACE">
                  <img style="pointer-events: none;" src="{{asset('assets/backspace.png')}}" width="24" height="21" alt="Backspace">
              </button>
              <br>
            </div>
            <footer class="footer centered">
                <div class="button cancel">
                    Cancel
                </div>
                <div class="button confirm">
                    Ok
                </div>
            </footer>
          </div>
        </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-number popup-password">
            <header class="title"></header>
            <div class="popup-input value active">
                
            </div>
            <div class="popup-numpad">
              <button class="input-button number-char" data-action="1">1</button>
              <button class="input-button number-char" data-action="2">2</button>
              <button class="input-button number-char" data-action="3">3</button>
              
                <button class="mode-button add" data-action="+10">+10</button>
              
              <br>
              <button class="input-button number-char" data-action="4">4</button>
              <button class="input-button number-char" data-action="5">5</button>
              <button class="input-button number-char" data-action="6">6</button>
              
                <button class="mode-button add" data-action="+20">+20</button>
              
              <br>
              <button class="input-button number-char" data-action="7">7</button>
              <button class="input-button number-char" data-action="8">8</button>
              <button class="input-button number-char" data-action="9">9</button>
              
                <button class="mode-button add" data-action="+50">+50</button>
              
              <br>
              <button class="input-button numpad-char" data-action="CLEAR">C</button>
              <button class="input-button number-char" data-action="0">0</button>
              <button class="input-button number-char dot"></button>
              <button class="input-button numpad-backspace" data-action="BACKSPACE">
                <img style="pointer-events: none;" src="{{asset('assets/backspace.png')}}" width="24" height="21" alt="Backspace">
              </button>
              <br>
            </div>
            <footer class="footer centered">
              <div class="button cancel">
                Cancel
              </div>
              <div class="button confirm">
                Ok
              </div>
            </footer>
          </div>
        </div>
        <div role="dialog" class="modal-dialog oe_hidden">
          <div class="popup popup-import">
            <header class="title">Finished Importing Orders</header>
            
            <footer class="footer">
              <div class="button cancel">
                Ok
              </div>
            </footer>
          </div>
        </div>
      </div>

      <div class="loader oe_hidden" style="opacity: 0;">
        <div class="loader-feedback oe_hidden">
          <h1 class="message">Loading</h1>
          <div class="progressbar">
            <div class="progress" width="50%"></div>
          </div>
          <div class="oe_hidden button skip">
            Skip
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection()

@section('modal')

  <div id="modal-bookentry-list" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
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
              <div class="table-responsive" style="overflow-y: auto;height: 373px;">
                <table class="table table-striped table-head-fixed">
                  <thead>
                    <tr>
                      <th>STUDENT NAME</th>
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


  <div id="modal-bookentry" class="modal fade mt-5" style="display: none;" aria-hidden="true">
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
                <input type="text" placeholder="0.00" name="currency-field" id="be_amount" class="form-control form-control-lg text-xl" height="60px" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" data-toggle="tooltip" title="Enter Amount">
              </div>
            </div>            
          </div>

          <div class="row">
            <div class="col-md-4">
              <button type="button" class="btn btn-default" data-dismiss="modal" style="text-align: left !important">Close</button>
            </div>
            <div class="col-md-8 text-right">
              <button id="be_delete" type="button" class="btn btn-danger btn-action" data-dismiss="modal">Delete</button>
              <button id="be_approve" type="button" class="btn btn-success btn-action" data-dismiss="modal">Approve</button>
              <button id="be_proceed" type="button" class="btn btn-primary" data-action="" data-id="">Proceed</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-paymentplan" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title">Payment Plan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-12">
              <select id="paymentplan_levelid" class="select2 form-control" style="width: 40%!important">
                
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Description</th>
                    <th>Plan</th>
                    <th>Grade Level</th>
                    <th>Grantee</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody id="fees-list" style="cursor: pointer;">
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="btnpayplanselect" type="button" class="btn btn-primary" data-id="0">Select Plan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div id="modal-orsetup" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">OR Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <h3>Current OR: <span><strong id="curOR"></strong></span></h3>
          <div class="form-group">
            <input id="txtor" type="number" class="form-control" placeholder="Enter OR Number">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="btnsave" type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  

  <div id="modal-terminalsetup" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Terminal Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>TERMINAL</label>
            <select id="terminal" class="form-control">
          
            </select>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="btnterminalsave" type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-opendaysetup" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Begin Day Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <tr>
                <th>START DATE TIME</th>
                <th>END DATE TIME</th>
                <th></th>
              </tr>
              <tr>
                <td id="opendatetime"></td>
                <td id="closedatetime"></td>
                <td><button id="btnopenclose" class="btn btn-danger">CLOSE DAY</button></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="modal-footer right-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          {{-- <button id="btnterminalsave" type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button> --}}
        </div>
      </div>
    </div>
  </div>

  <div id="modal-confirm-post" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Post Transactions</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to post all transactions?
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button id="btnpostTrans" type="button" class="btn btn-primary" data-dismiss="modal">Post</button>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-confirm-closeDay" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">


        <div class="modal-header">
          <h4 class="modal-title">End Day</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          End current day?
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button id="btncloseDay" type="button" class="btn btn-primary" data-dismiss="modal">End Day</button>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-custname" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">


        <div class="modal-header">
          <h4 class="modal-title">Receipt Name</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input id="cname" type="text" name=""  class="form-control" placeholder="Name" onkeyup="this.value = this.value.toUpperCase();">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button id="btnnamesave" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div>
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
                <label for="voiduname">Username</label>
                <input type="text" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control" id="voiduname" placeholder="Enter Username">
              </div>
              <div class="form-group">
                <label for="voidpword">Password</label>
                <input type="password" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" class="form-control" id="voidpword" placeholder="Password">
              </div>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button id="btnconfirm" type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
        </div>
      </div>
    </div>
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
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <th>NAME</th>
                <th>CODE</th>
                <th>PAYMENT TYPE</th>
                <th>AMOUNT</th>
                <th>REFERENCE NO</th>
                <th>TRANSACTION DATE</th>
              </thead>
              <tbody id="pay-list">
                
              </tbody>
            </table>            
          </div>
        </div>
        <div class="modal-footer text-right">
          {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> --}}
          <button id="" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-checkpostedday" class="modal fade" style="display: none;" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title">Unposted Transactions</h4>
          {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button> --}}
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <span>You have unposted tansactions. Please post all previous transactions to continue.</span>
            </div>
          </div>
        </div>
        <div class="modal-footer text-right">
          {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> --}}
          <button id="btncheckposted_post" type="button" class="btn btn-primary" data-dismiss="modal">Post Transactions</button>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-changeor" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Enter OR Number</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="text" name="" id="txtchangeor" class="form-control form-control-lg text-xl" height="60px">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div id="divReuse" class="icheck-primary d-inline float-left oe_hidden">
                <input type="checkbox" id="chkreuse">
                <label for="chkreuse">
                  Re-use OR number
                </label>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-6 text-right">
              <button id="submitChangeOR" class="btn btn-primary btn-lg text-right">Submit</button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-secondary btn-lg float-left" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-cashamount" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Enter Tendered Amount</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="text" placeholder="0.00" name="currency-field" id="txtcashamount" class="form-control form-control-lg text-xl" height="60px" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <select id="ea_class" class="form-control">
                  
                </select>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-6 text-right">
              <button id="btnCashAmount" class="btn btn-primary btn-lg text-right">Submit</button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-secondary btn-lg float-left" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="onlinepayinfo" class="toasts-bottom-right fixed">
    <div id="onlinepayinfocontainer" class="toast bg-info fade show" role="alert" aria-live="assertive" aria-atomic="true" style="width: 350px;">
      <div class="toast-header">
        <strong id="oltoast-studname" class="mr-auto"></strong>
        <small>Online Payment</small>
        <button type="button" class="ml-2 mb-1 close ol-close" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="toast-body">
        <div class="row">
          <div>
            Payment Type: <span id="oltoast-paytype" data-id=""></span>
          </div>
        </div>
        <div class="row">
          <div>
            Amount Pay: <span id="oltoast-amountpay"></span>
          </div>
        </div>
        <div class="row">
          <div>
            Reference Number: <span id="oltoast-refnum"></span>
          </div>
        </div>
        <div class="row">
          <div>
            Transaction Date: <span id="oltoast-transdate"></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <input type="hidden" name="" id="olinfo-amount">
  <input type="hidden" name="" id="olinfo-bankname">

  <div id="modal-changepass" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title">Change Password</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{-- <span class="text-left">Old Password</span> --}}
                <input id="oldpass" class="form-control" autofocus type="password" name="" placeholder="Old Password" data-toggle="tooltip" title="Old Password">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{-- <span class="text-left">Old Password</span> --}}
                <input id="newpass" class="form-control is-invalid val-changepass" autofocus type="password" name="" placeholder="New Password" data-toggle="tooltip" title="New Password">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                {{-- <span class="text-left">Old Password</span> --}}
                <input id="confirmpass" class="form-control is-invalid val-changepass" autofocus type="password" name="" placeholder="Confirm Password" data-toggle="tooltip" title="Confirm Password">
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button id="btnsavechangepass" type="button" class="btn btn-primary">Proceed</button>
          </div>
        </div>
      <!-- /.modal-content -->
      </div>
    <!-- /.modal-dialog -->
    </div>
  </div>

  <div id="modal-changeparticulars" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Enter Particulars</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-md-12">
            <input id="changeparticulars" class="form-control text-lg" style="height: 58px" type="" name="">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="savechangeparticulars" type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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