<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/checklogo', 'CashierController@checklogo');


Route::middleware(['auth', 'isCashier'])->group(function(){

	Route::get('/', 'HomeController@index');
	Route::get('/v1', 'CashierController@index');
	Route::get('/index/studlist', 'CashierController@studlist')->name('studlist');
	Route::get('/loadpaysched', 'CashierController@loadpaysched');
	Route::get('/cashtrans', 'CashierController@cashtrans');
	Route::get('/cashtransdel', 'CashierController@cashtransdel');
	Route::get('/viewPayment', 'CashierController@viewPayment');
	
	Route::get('/performPayment', 'CashierController@performPayment');
	Route::get('/printor/{transid}', 'CashierControllerV2@printor');

	Route::get('/getstudledger', 'CashierController@getstudledger');
	Route::get('/getcashsetup', 'CashierController@getcashsetup');

	Route::get('/printExamP/{id}','CashierController@printExamP')->name('printExamP');
	
	Route::get('/getcashtrans', 'CashierController@getcashtrans');
	

	Route::get('/getornum_setup', 'CashierController@getornum_setup');
	Route::get('/insertor_setup', 'CashierController@insertor_setup');
	
	Route::get('/getterminal', 'CashierController@getterminal');
	Route::get('/saveterminal', 'CashierController@saveterminal');
	Route::get('/loadTerminal', 'CashierController@loadTerminal');
	Route::get('/openday', 'CashierController@openday');
	Route::get('/getdatetime', 'CashierController@getdatetime');
	Route::get('/opendaycash', 'CashierController@opendaycash');
	Route::get('/postTrans', 'CashierController@postTrans');
	Route::get('/endday', 'CashierController@endday');
	Route::get('/genCRS', 'CashierController@genCRS');
	Route::get('/getCRSdatetime', 'CashierController@getCRSdatetime');
	Route::get('/getstudassessment', 'CashierController@getstudassessment');
	Route::get('/genassessment', 'CashierController@genassessment');
	Route::get('/genledger', 'CashierController@genledger');
	Route::get('/itemSearch', 'CashierController@itemSearch');
	Route::get('/getTno', 'CashierController@getTno');
	Route::get('/cashtransitem', 'CashierController@cashtransitem');
	Route::get('/getcashtransPrint', 'CashierController@getcashtransPrint');
	Route::get('/viewCashTrans', 'CashierController@viewCashTrans');
	Route::get('/getQTY', 'CashierController@getQTY');
	Route::get('/editAmount', 'CashierController@editAmount');
	Route::get('/getoline', 'CashierController@getoline');
	Route::get('/voidtrans', 'CashierController@voidtrans');
	Route::get('/onlinepay', 'CashierController@onlinepay');
	Route::get('/onlinedetail', 'CashierController@onlinedetail');
	Route::get('/checkOLPay', 'CashierController@checkOLPay');
	Route::get('/getActiveInfo', 'CashierController@getActiveInfo');
	Route::get('/changepass', 'CashierController@changepass');

	Route::get('/checkusedor', 'CashierController@checkusedor');	

	Route::get('/amountenter', 'CashierController@amountenter');

	Route::get('/bestudlist', 'CashierController@bestudlist');
	
	Route::get('/beedit', 'CashierController@beedit');
	Route::get('/bedelete', 'CashierController@bedelete');
	Route::get('/beapprove', 'CashierController@beapprove');
	Route::get('/loadbookentries', 'CashierController@loadbookentries');

	Route::get('/genfees', 'CashierController@genfees');
	Route::get('/plandesc', 'CashierController@plandesc');
	Route::get('/saveplandesc', 'CashierController@saveplandesc');
	Route::get('/genpayplanperlevel', 'CashierController@genpayplanperlevel');

	Route::get('/changeparticulars', 'CashierController@changeparticulars');


	Route::get('/v2', 'CashierControllerV2@index');

	Route::get('/v2/studlist', 'CashierControllerV2@studlist')->name('v2_studlist');

	Route::get('/v2/v2_studhistory', 'CashierControllerV2@v2_studhistory')->name('v2_studhistory');
	Route::get('/v2/v2_payinfo', 'CashierControllerV2@v3_payinfo')->name('v2_payinfo');
	Route::get('/v2/v2_pushtotrans', 'CashierControllerV2@v2_pushtotrans')->name('v2_pushtotrans');
	Route::get('/v2/v2_pushtotransDCC', 'CashierControllerV2@v2_pushtotransDCC')->name('v2_pushtotransDCC');
	Route::get('/v2/v2_performpay', 'CashierControllerV2@v2_performpay')->name('v2_performpay');
	Route::get('/v2/v2_performpayDCC', 'CashierControllerV2@v2_performpayDCC')->name('v2_performpayDCC');
	Route::get('/v2/v2_viewtrans', 'CashierControllerV2@v2_viewtrans')->name('v2_viewtrans');
	Route::get('/v2/v2_removeorderline', 'CashierControllerV2@v2_removeorderline')->name('v2_removeorderline');
	Route::get('/v2/v2_updatelineamount', 'CashierControllerV2@v2_updatelineamount')->name('v2_updatelineamount');
	Route::get('/v2/v2_loaditems', 'CashierControllerV2@v2_loaditems')->name('v2_loaditems');
	Route::get('/v2/v2_studledger', 'CashierControllerV2@v2_studledger')->name('v2_studledger');
	Route::get('/v2/v2_assessment', 'CashierControllerV2@v2_assessment')->name('v2_assessment');
	Route::get('/v2/v2_transactions', 'CashierControllerV2@v2_transactions')->name('v2_transactions');
	Route::get('/v2/v2_voidtransactions', 'CashierControllerV2@v2_voidtransactions')->name('v2_voidtransactions');
	Route::get('/v2/v2_amountenter', 'CashierControllerV2@v2_amountenter')->name('v2_amountenter');
	Route::get('/v2/v2_setterminalno', 'CashierControllerV2@v2_setterminalno')->name('v2_setterminalno');
	Route::get('/v2/v2_genCRS', 'CashierControllerV2@v2_genCRS')->name('v2_genCRS');
	Route::get('/v2/v2_ledgerinfo', 'CashierControllerV2@v2_ledgerinfo')->name('v2_ledgerinfo');
	Route::get('/v2/v2_items', 'CashierControllerV2@v2_items')->name('v2_items');
	Route::get('/v2/v2_items_save', 'CashierControllerV2@v2_items_save')->name('v2_items_save');
	Route::get('/v2/v2_items_edit', 'CashierControllerV2@v2_items_edit')->name('v2_items_edit');

	Route::get('/v2/load_classification', 'CashierControllerV2@load_classification')->name('load_classification');

	Route::get('/v2/v2_onlinepay', 'CashierControllerV2@v3_onlinepay')->name('v2_onlinepay');
	Route::get('/v2/v2_loadpaysched', 'CashierControllerV2@v2_loadpaysched')->name('v2_loadpaysched');
	Route::get('/v2/reloadselitems', 'CashierControllerV2@reloadselitems')->name('reloadselitems');

	Route::get('/getornum', 'CashierControllerV2@getornum');
	Route::get('/checkornum', 'CashierControllerV2@checkornum')->name('checkornum');
	Route::get('/reuseornum', 'CashierControllerV2@reuseornum')->name('reuseornum');

	Route::get('/viewPayplans', 'CashierControllerV2@viewPayplans')->name('viewPayplans');
	Route::get('/changepayplans', 'CashierControllerV2@changepayplans')->name('changepayplans');

	Route::get('/info_changesysem', 'CashierControllerV2@info_changesysem')->name('info_changesysem');	

	Route::get('/be_loadstud', 'CashierControllerV2@be_loadstud')->name('be_loadstud'); 
	Route::get('/loadbookentries', 'CashierControllerV2@loadbookentries')->name('loadbookentries'); 
	Route::get('/beedit', 'CashierControllerV2@beedit')->name('beedit'); 
	Route::get('/bedelete', 'CashierControllerV2@bedelete')->name('bedelete'); 
	Route::get('/beapprove', 'CashierControllerV2@beapprove')->name('beapprove'); 
	Route::get('/beappend', 'CashierControllerV2@beappend');

	Route::get('/payhistory', 'CashierControllerV2@payhistory')->name('payhistory');
	Route::get('/payhistory_print', 'CashierControllerV2@payhistory_print')->name('payhistory_print');

	Route::get('/v2_printledger', 'CashierControllerV2@v2_printledger')->name('v2_printledger');

	Route::get('/loadfees/', 'CashierControllerV2@loadfees')->name('loadfees');	
	Route::get('/addfeesid/', 'CashierControllerV2@addfeesid')->name('addfeesid');

	Route::get('/soa_generate', 'CashierControllerV2@soa_generate')->name('soa_generate');
	Route::get('/soa_print', 'CashierControllerV2@soa_print')->name('soa_print');

	Route::get('/old_load/', 'CashierControllerV2@old_load')->name('old_load');	
	Route::get('/old_loadstud/', 'CashierControllerV2@old_loadstud')->name('old_loadstud');	
	Route::get('/old_loadamount/', 'CashierControllerV2@old_loadamount')->name('old_loadamount');	
	Route::get('/old_getsem/', 'CashierControllerV2@old_getsem')->name('old_getsem');	

	Route::get('/old_add_studlist/', 'CashierControllerV2@old_add_studlist')->name('old_add_studlist');	
	Route::get('/old_post/', 'CashierControllerV2@old_post')->name('old_post');	

	Route::get('/addfeesid/', 'CashierControllerV2@addfeesid')->name('addfeesid');	

	Route::get('/collection/', 'CashierControllerV2@collection')->name('collection');
	Route::get('/collection/savedeno', 'CashierControllerV2@savedeno')->name('savedeno');

	Route::get('/assessment', 'CashierControllerV2@v3_assessment')->name('v3_assessment');

	Route::get('/syinfo', 'CashierControllerV2@syinfo')->name('syinfo');

	Route::get('/addpayment_tui', 'CashierControllerV2@addpayment_tui')->name('addpayment_tui');	
	
	Route::get('/ul_loadfees', 'CashierControllerV2@ul_loadfees')->name('ul_loadfees');	



	//shane
	Route::get('/reports/selections', 'Reports\ReportStatementofAccountController@selections');
	Route::get('/reports/statementofaccountall', 'Reports\ReportStatementofAccountController@allstudents');
	Route::get('/reports/statementofaccountstudinfo', 'Reports\ReportStatementofAccountController@studinfo');
	Route::get('/reports/statementofaccountview', 'Reports\ReportStatementofAccountController@getaccount');
	Route::get('/reports/statementofacctexport', 'Reports\ReportStatementofAccountController@export');

	Route::get('/cashreceiptsummary/index', 'FinanceControllers\CashReceiptController@index')->name('cashreceiptsummary');
    Route::get('/cashreceiptsummary/filter', 'FinanceControllers\CashReceiptController@filter')->name('cashreceiptfilter');
    Route::get('/cashreceiptsummary/genCRSexport', 'CashierControllerV2@genCRSexport')->name('genCRSexport');

    Route::get('/printcashtrans/{terminalid}/{dtfrom}/{dtto}/{filter}', 'CashierControllerV2@v2_printcashtrans');

	Route::get('/logout', function(){
        Auth::logout();
        return redirect('/');
    });
	
});

Auth::routes();






Route::get('/home', function(){
	Auth::logout();
	return back();
})->name('home');






// Route::get('/', 'HomeController@index')->name('home');

