<script>
	
var idleTime = 0;
$(document).ready(function(){

  $(document).on('click', '.pos-logo', function(){
    var ver = 'ver 1.2.10.29.2020.18.34';

    // CHANGE LOGS
    // 'ver 1.2.07.22.2020.14.03';
    // -- Change Search Product to Search Item
    // -- Change Price button to Amount
    // -- OR - if olreceipt == 0 then ommit "OL" on ornumber
    // -- Fix delete transaction issue

    //'ver 1.2.07.23.2020.09.56';
    // CHANGE LOGS
    // -- Fix Disbalance on Cashier and Ledger.


    // var ver = 'ver 1.2.07.27.2020.18.56';
    //CHANGE LOGS
    // -- Combine all school receipts 

    //ver = 'ver 1.2.08.19.2020.09.21'
    //CHANGE LOGS
    // -- Change Terminal ID from IP to USER ID
    // -- Add Transaction items and Ledger Itemized

    //ver = 'ver 1.2.08.24.2020.09.38'
    //CHANGE LOGS
    // -- Add SELECT MODE module for Fees and Collection

    //ver = 'ver 1.2.08.29.2020.10.34';
    // -- Add Grade Level to Payment plan selection
    // -- Prompt if Previous day transaction not posted
    // -- Fix Generation of Cash Receipt Summary

    //ver = 'ver 1.2.09.24.2020.10.36';
    // -- Remove negative value on Assessment

    //ver = 'ver 1.2.10.05.2020.18.02';
    // -- Add ClassID on Enter Amount function

    //ver = 'ver 1.2.10.15.2020.13.57';
    // -- Add Studstatus ug Search Student
    // -- Add Adjust Date

    //ver = 'ver 1.2.10.29.2020.18.34';
    // -- Add Change Particulars
    // -- Allow change amount on Cards

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: false,
      onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })

    Toast.fire({
      type: 'success',
      title: ver
    })
  });



  $(".select2").select2({
    theme: "bootstrap4"
  });

  $('.dtrangepicker').daterangepicker();

  // $('#adjdate').datepicker();

  // alert(jQuery.fn.jquery);

  var terminalnumber = 0;
  var curDayID = 0;
  var ipadd = '';
  var hostname = '';


  var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
  var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

  function inWords (num) {
      if ((num = num.toString()).length > 9) return 'overflow';
      n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
      if (!n) return; var str = '';
      str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
      str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
      str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
      str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
      str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
      return str;

  }


  


  var amountinwords = inWords($('#number').text());

  /*keyON : 
    2 = main
    1 = payment

  */


  var keyON = 2;
  var payTrans = 0;

  // console.log(inWords(2962));
  $('#words').text(amountinwords);

  // document.getElementById('number').onkeyup = function () {
  //   document.getElementById('words').innerHTML = inWords(document.getElementById('number').value);
  // };
  var idleInterval = setInterval(timerIncrement, 1000); // 1 seconds
  checkOLPay();
  function timerIncrement() {
        
    idleTime = idleTime + 1;
    if (idleTime == 10) // 10 sec
    { 
      // window.location.reload();

      checkOLPay();

    }
  }

  ActiveInfo();

  

  function ActiveInfo()
  {
    $.ajax({
      url:"/getActiveInfo",
      method:'GET',
      data:{
        
      },
      dataType:'json',
      success:function(data)
      {
        $('#activesy').text(data.sydesc);
        $('#activesem').text(data.semdesc);
      }
    });
  }

  
  getTerminal();
  

  // $('#onlinepayinfocontainer').removeClass('show');
  $('#onlinepayinfo').addClass('oe_hidden');

  function getTerminal()
  {
    $.ajax({
      url:"/loadTerminal",
      method:'GET',
      data:{
        
      },
      dataType:'json',
      success:function(data)
      {
        $('#tDesc').text(data.terminalDesc);
        $('#tDesc').attr('terminal-id', data.terminalid);
        terminalnumber = data.terminalid;
        
        console.log('getTerminal: ' + terminalnumber)
        if(data.terminalid != 0)
        {
          openDay();
        }
        else
        {
          $('.btnterminal').trigger('click');
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: false,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            type: 'warning',
            title: 'No Terminal Setup'
          })
        }
      }
    });
  }

  function openDay()
  {
    // console.log('openday: ' +terminalnumber);
    $.ajax({
      url:"/openday",
      method:'GET',
      data:{
        terminalid:terminalnumber
      },
      dataType:'json',
      success:function(data)
      {
        // console.log(data.result)
        getCurOR(terminalnumber);
        
        if(data.result == 1)
        {
          $('#setDay').attr('day-id', data.openday);
          curDayID = data.openday;

          if(curDayID > 0)
          {
            $('#textDay').addClass('text-success');
            $('#textDay').removeClass('text-secondary');
            $('#textDay').text('DAY: STARTED - ' + data.opendatetime);  

            if(data.nopost == 1)
            {
              $('#modal-checkpostedday').modal('show');              
            }
          }
          else
          {
            $('#textDay').addClass('text-secondary');
            $('#textDay').removeClass('text-success');
            $('#textDay').text('DAY: END DAY');

            $('#setDay').trigger('click');
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 5000,
              timerProgressBar: false,
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              type: 'warning',
              title: 'No active day is set.'
            })



          }
        }
      }
      
    });
  }

  function getCurOR(terminalno)
  {
    $.ajax({
      url:"/getornum",
      method:'GET',
      data:{
        terminalno:terminalnumber,
        paytypeid:1
      },
      dataType:'json',
      success:function(data)
      {
        // console.log(data.curOR);

        if(data.curOR == 0)
        {
          $('#modal-orsetup').modal('show');
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            // timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });

          Toast.fire({
            type: 'warning',
            title: 'No OR has been set.'
          }); 
        }
      }
    });
  }

  function printDiv(divName)
  {
      var printContents = document.getElementById(divName).innerHTML;
      var originalContents = document.body.innerHTML;

      document.body.innerHTML = printContents;

      window.print();

      document.body.innerHTML = originalContents;

  }

  function itemSearch(itemname)
  {
    var studid = $('#selstud').attr('stud-id');

    $.ajax({
      url:"/itemSearch",
      method:'GET',
      data:{
        studid:studid,
        itemname:itemname
      },
      dataType:'json',
      success:function(data)
      {
        $('#item-list').html(data.list);
      }
    });
  }

  function checkOLPay()
  {
    $.ajax({
      url:"/checkOLPay",
      method:'GET',
      data:{
        
      },
      dataType:'json',
      success:function(data)
      {
        $('#olcount') .text(data);
        idleTime = 0; 
      }
    });
  }

  $(document).on('click', '#printOR', function(){
    // console.log('printOR');
    printDiv('cashOR');
  });


  $(document).on('click', '.set-student', function(){
    // $('#mainview').attr('class', 'product-screen screen oe_hidden');
    // $('#studlist').attr('class', 'clientlist-screen screen');


    // $('#studledger').attr('class', 'menu-screen screen oe_hidden'); 
    // $('#cashmenu').attr('class', 'menu-screen screen oe_hidden'); 
    // $('#cashiertrans').attr('class', 'menu-screen screen oe_hidden'); 

    $('.screen').addClass('oe_hidden');
    $('#studlist').removeClass('oe_hidden');


    $('#searchstud').focus();
    var query = $('#searchstud').val(); 
    getstudlist(query);
  });

  function getstudlist(query='')
  {
    $.ajax({
      url:"/index/studlist",
      method:'GET',
      data:{
        query:query
      },
      dataType:'json',
      success:function(data)
      {
        $('.client-list-contents').html(data.output);
      }
    });
  }

  function checkDayopen()
  {

  }

  $(document).on('click', '.back', function(){
    $('.screen').addClass('oe_hidden');
    $('#mainview').removeClass('oe_hidden');

		// $('#studlist').attr('class', 'clientlist-screen screen oe_hidden');
		// $('#cashmenu').attr('class', 'menu-screen screen oe_hidden');	
  //   $('#studledger').attr('class', 'menu-screen screen oe_hidden');	
  //   $('#cashiertrans').attr('class', 'menu-screen screen oe_hidden');

  });

  $(document).on('click', '.client-line', function(){
    $('.client-line').attr('class', 'client-line');
    $(this).attr('class', 'client-line highlight');
    $('#selcustomer').attr('selstud-id', $(this).attr('data-id'));
    $('#selcustomer').attr('selstud-name', $(this).attr('data-name') + ' - ' + $(this).attr('data-level'))
    $('#selcustomer').attr('class', 'button next highlight');
    
  });

  $(document).on('keyup', '#searchstud', function(){
    getstudlist($(this).val());
  });

  $(document).on('click', '#cat_items', function(){
    $('#paySched').addClass('oe_hidden');
    $('.payItem').removeClass('oe_hidden');
    $(this).addClass('sel-category');
    $('#cat_tuition').removeClass('sel-category');
    $('#txtitemsearch').val('');
    $('#txtitemsearch').focus();
    itemSearch();
  });

  $(document).on('click', '#cat_tuition', function(){
    $('#paySched').removeClass('oe_hidden');
    $('.payItem').addClass('oe_hidden');
    $(this).addClass('sel-category');
    $('#cat_items').removeClass('sel-category');
  });

  $(document).on('dblclick', '.client-line', function(){
    $('#selcustomer').trigger('click');
  })

  $(document).on('click', '#selcustomer', function(){
  	$('#mainview').attr('class', 'product-screen screen');
    $('#studlist').attr('class', 'clientlist-screen screen oe_hidden');
    $('#selstud').attr('stud-id', $('#selcustomer').attr('selstud-id'));
    $('#selstud').text($('#selcustomer').attr('selstud-name'));
    $('#selstud').attr('class', 'button set-student highlight');
    $('#userimg').hide();
    $('.payment-info').addClass('oe_hidden');
    $('#onlinepayinfocontainer').removeClass('show');
    $('#olinfo-amount').val('');
    $('#oltoast-paytype').attr('data-id', '');
    olid = '';

    var studid = $('#selstud').attr('stud-id');
    $.ajax({
      url:"/loadpaysched",
      method:'GET',
      data:{
        studid:studid
      },
      dataType:'json',
      success:function(data)
      {
        $('#payscheditems').html(data.output);
        $('#selstud').attr('trans-no', data.transno);
    		$('#selstud').attr('or-num', data.ornum);
        $('#selstud').attr('fees-id', data.feesid);
    		$('#divOrder').html(data.orderlines);
        $('#ea_class').html(data.classitem);


        console.log('studstatus: ' + data.studstatus);
        if(data.studstatus == 0)
        {
          $('#cat_items').trigger('click');
        }
        else
        {
          console.log('click');
          $('#cat_tuition').trigger('click');
        }

        if(data.payplan == 1)
        {
          $('#btnpaymentplan').prop('hidden', false);
          $('#plandescription').prop('hidden', false);
          plandescription();
        }
        else
        {
          $('#btnpaymentplan').prop('hidden', true);
          $('#plandescription').prop('hidden', true);
        }

        itemSearch();
      }
    });

  });

  $(document).on('click', '#searchremove', function(){
    $('#txtitemsearch').val('');
    itemSearch();
  });

  $(document).on('click', '.product', function(){
    var detailid = $(this).attr('aria-labelledby');
    var classid = $(this).attr('class-id');
    var monthdue = $(this).attr('month-due');
    var duedate = $(this).attr('data-due');
    var studid = $('#selstud').attr('stud-id');
    var terminalno = terminalnumber;
    var transno = $('#selstud').attr('trans-no');
    var ornum = $('#selstud').attr('or-num');
	
	// console.log(terminalno);

    if(curDayID > 0 && terminalnumber > 0)
    {
    	$(this).hide();

    	$.ajax({
        url:"/cashtrans",
        method:'GET',
        data:{
          studid:studid,
         	detailid:detailid,
         	classid:classid,
         	terminalno:terminalno, 
         	transno:transno,
         	ornum:ornum,
  		    monthdue:monthdue,
  		    duedate:duedate,
          dayid:curDayID
        },
        dataType:'json',
        success:function(data)
        {
          $('#divOrder').html(data.output);
        }
      });
    }
    else
    {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        // timerProgressBar: true,
        onOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });

      Toast.fire({
        type: 'warning',
        title: 'No active day is set.'
      }); 
    }
  });

  $(document).on('click', '.orderline', function(){
  	$('.orderline').attr('class', 'orderline');
  	$(this).attr('class', 'orderline bg-success');
  });

  $(document).on('click', '#backspace', function(){
  	var cashid = 0;
  	var detailid = 0;
  	var transno = $('#selstud').attr('trans-no');
  	var ornum = $('#selstud').attr('or-num');
  	
  	$('.orderline').each(function(){
  		if($(this).attr('class') == 'orderline bg-success')
  		{
  			cashid = $(this).attr('data-id');
  			detailid = $(this).attr('detail-id');
  			
  			// console.log(cashid);
				// console.log(detailid);

  		}
  	});

  	$('.product').each(function(){
  		if($(this).attr('aria-labelledby')==detailid)
  		{
  			$(this).show();	
  		}
  	});

  	$.ajax({
      url:"/cashtransdel",
      method:'GET',
      data:{
        cashid:cashid,
        transno:transno,
        ornum:ornum
      },
      dataType:'json',
      success:function(data)
      {
        $('#divOrder').html(data.output);
      }
    });

  });

  $(document).on('click', '#pay', function(){

  	var itemCount = 0;
  	$('.orderlines').each(function(){
  		itemCount += 1;
  	});

  	if(itemCount > 0)
  	{
  		// $('.payment-screen').attr('class', 'payment-screen screen');
	  	// $('#mainview').attr('class', 'product-screen screen oe_hidden');
      console.log($('#oltoast-paytype').attr('data-id'));
      if($('#olinfo-amount').val() > 0)
      {
        if($('#tAmount').attr('data-value') == $('#olinfo-amount').val())
        {
          var olpaymethod = $('#oltoast-paytype').attr('data-id');

          if(olpaymethod == 2)
          {

            $('#CHEQUE').trigger('click');
          }
          else if(olpaymethod == 3)
          {
            $('#BANK').trigger('click'); 
            $('#bankbankname').val($('#olinfo-bankname').val());
            $('#bankrefno').val($('#oltoast-refnum').text());
            $('#paytransdate').val($('#oltoast-transdate').attr('data-value'));
          }
          else if(olpaymethod == 4)
          {
            $('#GCASH').trigger('click');  
            $('#refno').val($('#oltoast-refnum').text());
            $('#remtransdate').val($('#oltoast-transdate').attr('data-value'));
          }
          else if(olpaymethod == 5)
          {
            $('#PALAWAN').trigger('click');  
            $('#refno').val($('#oltoast-refnum').text());
            $('#remtransdate').val($('#oltoast-transdate').attr('data-value'));
          }
          else
          {
            $('#CASH').trigger('click');   
          }


          $('.screen').addClass('oe_hidden');
          $('.payment-screen').removeClass('oe_hidden');


    	  	var tAmount = $('#tAmount').text();
    	  	// console.log(tAmount);
          $('#totalamount').text(tAmount);
          if($('#selstud').attr('stud-id') != '')
          {
            $('#custName span').text($('#selstud').text());
          }
        }
        else
        {
          Swal.fire({
            position: 'top',
            type: 'error',
            title: 'Total payment is not equal to online payment',
            showConfirmButton: false,
            timer: 3000
          })
        }

      }
      else
      {
        var olpaymethod = $('#oltoast-paytype').attr('data-id')

        if(olpaymethod == 2)
        {

          $('#CHEQUE').trigger('click');
        }
        else if(olpaymethod == 3)
        {
          $('#BANK').trigger('click'); 
        }
        else if(olpaymethod == 4)
        {
          $('#GCASH').trigger('click');
          
        }
        else if(olpaymethod == 5)
        {
          $('#PALAWAN').trigger('click');  
          $('#refno').val($('#oltoast-refnum').text());
        }
        else
        {
          $('#CASH').trigger('click');   
        }


        $('.screen').addClass('oe_hidden');
        $('.payment-screen').removeClass('oe_hidden');


        var tAmount = $('#tAmount').text();
        // console.log(tAmount);
        $('#totalamount').text(tAmount);
        $('#tAmountCopy').text($('#tAmount').attr('data-value')); //-------------[shane]
        if($('#selstud').attr('stud-id') != '')
        {
          $('#custName span').text($('#selstud').text());
        }
      }
  	}
  });

  $(document).on('click', '.paymentmethod', function(){
    var paytypeid = $(this).attr('data-id');
    $.ajax({
      url:"/getornum",
      method:'GET',
      data:{
        terminalno:terminalnumber,
        paytypeid:paytypeid
      },
      dataType:'json',
      success:function(data)
      {
        // console.log(data.curOR);

        if(data.curOR > 0)
        {
          $('#cOR').text(data.curOR);
        }
        else
        {
          Swal.fire({
            position: 'top',
            type: 'error',
            title: 'No OR has been set',
            showConfirmButton: false,
            timer: 1500
          })
        }
      }
    });
  });

  $(document).on('click', '#backPayment', function(){
    $('.screen').addClass('oe_hidden');
  	$('#mainview').removeClass('oe_hidden');
  });

  $(document).on('click', '.paymentmethod', function(){
  
  	var transno = $('#selstud').attr('trans-no');
    var paytype = $(this).attr('data-id');
    var paytypetext = $(this).attr('id');
    keyON = 1;
  	$('.paymentlines-empty').hide();
    $('#txtPaymethod').text(paytypetext)
    
    $('.paymentmethod').removeClass('highlight');
    $(this).addClass('highlight');
  	
  	$.ajax({
      url:"/viewPayment",
      method:'GET',
      data:{
        transno:transno,

      },
      dataType:'json',
      success:function(data)
      {
        $('.paymentlines-container').html(data.output);

        if(paytype == 2)
        {
          $('.payment-info').addClass('oe_hidden');
          $('#chequeinfo').removeClass('oe_hidden');
        }
        else if(paytype == 3)
        {
          $('.payment-info').addClass('oe_hidden');
          $('#bankinfo').removeClass('oe_hidden'); 
          // $('#refno').removeClass('eo_hidden');
        }
        else if(paytype >= 4 && paytype <= 9)
        {
          $('.payment-info').addClass('oe_hidden');
          $('#remittanceinfo').removeClass('oe_hidden');  
        }
        else
        {
          $('.payment-info').addClass('oe_hidden');
        }
        $('#col-paytype').text(paytypetext)

        if($('#olinfo-amount').val() > 0)
        {
          $('#tendered').text($('#olinfo-amount').val());
          $('#tendered').attr('data-value', $('#olinfo-amount').val())
          checkAmount($('#olinfo-amount').val(), $('.col-due').attr('data-value'))
        }

      }
    });
    
  });

  var prevnum = '';

  function checkAmount(curAmount = 0, dueAmount = 0)
  {
    // console.log(curAmount + ' >= ' + dueAmount);
  	if(parseFloat(curAmount) >= parseFloat(dueAmount))
  	{
  		$('#btnPay').attr('class', 'button next highlight');

  		// console.log('curamount' + curAmount);
  		// console.log('dueAmount' + dueAmount);

  		if(parseFloat(curAmount) > parseFloat(dueAmount))
  		{
  			var changeAmount = 0;
  			changeAmount = parseFloat(curAmount) - parseFloat(dueAmount);
  			$('.col-change').attr('class', 'col-change highlight');
  			$('.col-change').number(parseFloat(changeAmount), 2);
  			
  		}
  		else
  		{
  			$('.col-change').attr('class', 'col-change');
  			$('.col-change').text('');
  			
  		}

  		$('#btnPay').attr('data-validate', 1);

  	}
  	else
  	{
  		$('#btnPay').attr('class', 'button next');
  		$('.col-change').attr('class', 'col-change');
  		$('.col-change').text('');

  		$('#btnPay').attr('data-validate', 0);
  	}
  }


  $(document).on('click', '.input-button', function(){
  	
  	if($('#tendered').attr('data-input') == 1)
  	{
	  	if($(this).attr('data-action') =='BACKSPACE')
	  	{
	  		prevnum = prevnum.slice(0, prevnum.length - 1);
	  	}
	  	else
	  	{
	  		var enterednum = $(this).attr('data-action');
		  	prevnum += enterednum.toString();

		  }

		  var tAmount = $('.col-due').attr('data-value');

		  checkAmount(prevnum, tAmount);

		  $('#tendered').attr('data-value', prevnum);
			$('#tendered').number(parseFloat(prevnum), 2);
		}

		else if($('.col-due').attr('data-input') == 1)
		{
			// console.log(prevnum);

			if($(this).attr('data-action') =='BACKSPACE')
	  	{
	  		prevnum = prevnum.slice(0, prevnum.length - 1);
	  	}
	  	else
	  	{
	  		var enterednum = $(this).attr('data-action');
		  	prevnum += enterednum.toString();

		  }

		  var cAmount = $('#tendered').attr('data-value');

		  checkAmount(cAmount, prevnum);

		  $('.col-due').attr('data-value', prevnum);
			$('.col-due').number(parseFloat(prevnum), 2);	
		}

  });


  $(document).on('click', '.col-due', function(){
    var transno = $('#selstud').attr('trans-no');
    $.ajax({
      url:"/getoline",
      method:'GET',
      data:{
        transno:transno
      },
      dataType:'',
      success:function(data)
      {
        if(data == 0)
        {
          $('#tendered').attr('class', 'col-tentered');
          $('#tendered').attr('data-input', '0');
          $('.col-due').addClass('edit');
          $('.col-due').attr('data-input', '1');
          prevnum = $('.col-due').attr('data-value');    
        }
        else
        {
          Swal.fire({
            position: 'top',
            type: 'info',
            title: 'You can edit Due amount in receivable items only',
            showConfirmButton: true,
            timer: 2000
          }) 
        }
      }
    }); 

    	
  });

  $(document).on('click', '#tendered', function(){
  	$('.col-due').attr('class', 'col-due');
  	$('.col-due').attr('data-input', '0');
  	$(this).attr('class', 'col-tentered edit');
  	$(this).attr('data-input', '1');
  	prevnum = $('#tendered').attr('data-value');
    keyON = 1;
  });

  // $(document).keypress(function(e)
  $(document).on('keypress', function(e)
	{
		// console.log(e.keyCode);

    if(keyON == 1)
    {
      var keyval = String.fromCharCode(e.which);
      if (keyval.match(/[.\0-9]/))
      {
      	if($('#tendered').attr('data-input') == 1)
      	{
  	      var enterednum = keyval;
  		  	prevnum += enterednum.toString();

  		  	var tAmount = $('.col-due').attr('data-value');

  			  checkAmount(prevnum, tAmount);

  			  $('#tendered').attr('data-value', prevnum);
  				$('#tendered').number(parseFloat(prevnum), 2);
  			}
  			else if($('.col-due').attr('data-input') == 1)
  	    {
  	    	var enterednum = keyval;
  	  		prevnum += enterednum.toString();

  				var cAmount = $('#tendered').attr('data-value');

  			  checkAmount(cAmount, prevnum);

  			  $('.col-due').attr('data-value', prevnum);
  				$('.col-due').number(parseFloat(prevnum), 2);			  		
  	    }
      }
    }
    
	});

	$(document).on('keydown', function(e){
		// console.log(e.which);
		if(e.which == 8)
		{
      if(keyON == 1)
      {
  			if($('#tendered').attr('data-input') == 1)
  			{
  				prevnum = prevnum.slice(0, prevnum.length - 1);	
  				var tAmount = $('.col-due').attr('data-value');

  			  checkAmount(prevnum, tAmount);

  			  $('#tendered').attr('data-value', prevnum);
  				$('#tendered').number(parseFloat(prevnum), 2);
  			}
  			else if($('.col-due').attr('data-input') == 1)
  			{
  				prevnum = prevnum.slice(0, prevnum.length - 1);

  				var cAmount = $('#tendered').attr('data-value');

  			  checkAmount(cAmount, prevnum);

  			  $('.col-due').attr('data-value', prevnum);
  				$('.col-due').number(parseFloat(prevnum), 2);	
  			}
      }
		}

		if(e.which==114)
		{
			e.preventDefault();
			$('#selstud').trigger('click');
		}

		if(e.which==27)
		{
			e.preventDefault();
      // $('.back').trigger('click');
      $('.screen').addClass('oe_hidden');
      $('#mainview').removeClass('oe_hidden');
      $('.modal').modal('hide');
		}

    if(e.which==119)
    {

      $('#btnqty').trigger('click');
    }

		if(e.which==116)
		{
			e.preventDefault();
			$('#pay').trigger('click');	
		}

    if(e.which==117)
    {
      e.preventDefault();
      $('#btnEnterAmount').trigger('click');
      $('#txtcashamount').focus();
    }

		if(e.which==113)
		{
			e.preventDefault();
			$('#btnmenu').trigger('click');	
    }

    if (e.which == 46)
    {
      $('#backspace').trigger('click');
    }

    if(e.which==09)
    {
      if(payTrans = 1)
      {
        if(keyON == 1)
        {
          e.preventDefault();
          if($('#tendered').hasClass('edit') == true)
          {
            $('.col-due').trigger('click');
          }
          else if($('.col-due').hasClass('edit') == true)
          {
            $('.col-tentered').trigger('click');
          }
        }

      }
      else
      {
        e.allowdefault();
      }

    }

    if(e.which==115)
    {
      console.log($('#payscheditems article').length);
      if($('#payscheditems').length > 0)
      {

        $('.orderline').each(function(){
          if($(this).hasClass('bg-success'))
          {
            var item = $(this).find('.product-name').text();
            var dataid = $(this).attr('data-id');
            $('#changeparticulars').val(item);
            $('#changeparticulars').attr('data-id', dataid)
            $('#changeparticulars').focus(function(){
              $(this).select();
            });
          }
        });

        $('#modal-changeparticulars').modal('show');
        
      }
    }



    // if(e.which==123)
    // {
    //   e.preventDefault();
    // }
    
		
	});

	$(document).on('click', '#btnPay', function(){
    var coldue = parseFloat($('.col-due').attr('data-value'));
    var totalAmount = parseFloat($('#tAmount').attr('data-value'));
    var previousbalance = parseFloat($('#tAmountCopy').text());
    console.log(totalAmount + ' >= ' + coldue);

    // if(totalAmount >= coldue)
    if(1 > 0)
    {
      if($('#btnPay').attr('data-validate') == 1)
      {
        if($('#selstud').attr('stud-id') == '' && $('#custName').attr('data-value') == '')
        {
  		    const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });

          Toast.fire({
            type: 'warning',
            title: 'Please enter Payor.'
          });
        }
        else if($('#cOR').text() == '')
        {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });

          Toast.fire({
            type: 'warning',
            title: 'No OR has been set.'
          }); 
        }
        else
        {
          if($('#adjdate').val() != '')
          {
            performPay();
          }
          else
          {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            });
            
            Toast.fire({
              type: 'warning',
              title: 'No value on Adjust Date.'
            });  
          }
        }
      }
      else
      {
        // console.log('test');
          const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });

          Toast.fire({
            type: 'warning',
            title: 'Tendered amount is less than the due amount.'
          }); 
      }
    }
    else
    {
      Swal.fire({
        type: 'error',
        title: 'Invalid Payment',
        text: 'The amount you entered is greater than the total amount of your selected item(s).',
        footer: ''
      });
    }
    
	});

	function performPay()
	{
		//TRANS ornum, transdate, transdesc, totalamount, amountpaid, studid, syid, terminalno, transby, cancelled, studname, posted, sid, glevel-section
		// TRANSDETAIL transno, payscheddetailid, particulars, amount, qty, classid

		var ornum = $('#cOR').text();
		var transDesc = 0;
		var previousbalance = $('#tAmountCopy').text(); //------[shane]
		var totalamount = $('#tAmount').attr('data-value');
		var amountpaid = $('.col-due').attr('data-value');
		var studid = $('#selstud').attr('stud-id');
		var terminalno = terminalnumber;
		var transby = 0;
    var dayid = $('setDay').attr('day-id');
		var studname = $('#selstud').text();
		var transno = $('#selstud').attr('trans-no');
    var custName = $('#custName').attr('data-value');
    var accountno = $('#accountno').val();
    var accountname = $('#accountname').val();
    var bankname = $('#bankname').val();
    var bank_bankname = $('#bankbankname').val();
    var chequeno = $('#chequeno').val();
    var chequedate = $('#chequedate').val();
    var creditcardno = '';
    var cardtype = '';
    var refno = $('#refno').val();
    var paytransdate = $('#paytransdate').val();
    var remtransdate = $('#remtransdate').val();
    var paytype = $('#col-paytype').text();
    var adjdate = $('#adjdate').val();
    // var olid = $('#selstud').attr('ol-id');

    console.log('adjdate: ' + adjdate);

    if(paytype == 'CHEQUE')
    {
      bname = bankname;
    }
    else if(paytype == 'BANK')
    {
      bname = bank_bankname;
    }
    else
    {
      bname = '';
    }

    if(paytype == 'GCASH' || paytype == 'PALAWAN')
    {
      transdate = remtransdate;
    }
    else
    {
      transdate = paytransdate;
    }

		// console.log('performpay ' + dayid);
		if($('.col-name').text()=='Cash')
		{
			transDesc = 1;
		}
		else if($('.colname').text()=='Cheque')
		{
			transDesc = 2;
		}

		$.ajax({
      url:"/performPayment",
      method:'GET',
      beforeSend: function(){
        $('#btnPay').attr('data-validate', 0);
    },
      data:{
      	ornum:ornum,
        transno:transno,
        transDesc:transDesc,
        totalamount:totalamount,
        amountpaid:amountpaid,
        studid:studid,
        terminalno:terminalno,
        transby:transby,
        studname:studname,
        transno:transno,
        dayid:curDayID,
        custName:custName,
        accountno:accountno,
        accountname:accountname,
        bname:bname,
        chequeno:chequeno,
        chequedate:chequedate,
        creditcardno:creditcardno,
        cardtype:cardtype,
        refno:refno,
        paytype:paytype,
        paytransdate:transdate,
        olid:olid,
        adjdate:adjdate
      },
      dataType:'json',
      success:function(data)
      {
        numtowords = parseFloat(data.amountpaid);
        words = inWords(numtowords);
        // console.log(numtowords);
        // console.log(words);


				
        // console.log(inWords(numtowords).toUpperCase());

        $('.payment-screen').attr('class', 'payment-screen screen oe_hidden');
        $('#receipt').attr('class', 'receipt-screen screen');
        $('#r_date').text(data.curdate);
        $('#r_ornum').text(data.ornum);
        $('#r_sid').text(data.sid);
        $('#r_name').text(data.studname);
        $('#r_gradesection').text(data.gradesection);
        $('#words').text(data.numtowords);
        $('#number').text(data.formatpaid);
        $('#r_items').html(data.items);
        $('#prntOR').attr('data-id', data.transid);

        console.log('TransID: ' + $('#prntOR').attr('data-id'));

        var tChange = $('.col-change').text();
        $('.change-value').text(tChange);

        checkOLPay();
      }
    });

	}

	$(document).on('click', '#btnmenu', function(){
    $('.screen').addClass('oe_hidden');
    $('#cashmenu').removeClass('oe_hidden');

		// $('#mainview').attr('class', 'product-screen screen oe_hidden');
  //   $('#studlist').attr('class', 'clientlist-screen screen oe_hidden');
  //   $('#studledger').attr('class', 'menu-screen screen oe_hidden');	
  //   $('#cashmenu').attr('class', 'menu-screen screen');	
  //   $('#cashiertrans').attr('class', 'menu-screen screen oe_hidden');	
	});

	$(document).on('click', '#btnledger', function(){
		$('#mainview').attr('class', 'product-screen screen oe_hidden');
		$('#studlist').attr('class', 'clientlist-screen screen oe_hidden');
		$('#cashmenu').attr('class', 'menu-screen screen oe_hidden');	
    $('#studledger').attr('class', 'menu-screen screen');	
    $('#cashiertrans').attr('class', 'menu-screen screen oe_hidden');
  });

  $(document).on('click', '#btntransactions', function(){
		// $('#mainview').attr('class', 'product-screen screen oe_hidden');
		// $('#studlist').attr('class', 'clientlist-screen screen oe_hidden');
		// $('#cashmenu').attr('class', 'menu-screen screen oe_hidden');	
  //   $('#studledger').attr('class', 'menu-screen screen oe_hidden');	
  //   $('#cashiertrans').attr('class', 'menu-screen screen');

    $('.screen').addClass('oe_hidden');
    $('#cashiertrans').removeClass('oe_hidden');


  });

  $(document).on('click', '#btncashsum', function(){
    $('.screen').addClass('oe_hidden');
    $('#cashsummary').removeClass('oe_hidden');

    $.ajax({
      url:"/getCRSdatetime",
      method:'GET',
      data:{
        
      },
      dataType:'json',
      success:function(data)
      {
        
        $('#CRSdtFrom').val(data.datefrom);
        $('#CRSdtTo').val(data.dateto);
        $('#genCRS').trigger('click');

        
      }
    });

  });

  $(document).on('click', '#btntransactions', function(){

    $.ajax({
      url:"/getcashsetup",
      method:'GET',
      data:{
        terminalid:terminalnumber
      },
      dataType:'json',
      success:function(data)
      {

        $('#terminals').html(data.terminal);
        $('#dtFrom').val(data.from);
        $('#dtTo').val(data.to);

        viewtrans()
      }
    });
  });
  
  $(document).on('click', '#btnledger', function(){
    var studid = $('#selstud').attr('stud-id');
    // console.log(studid);
    $.ajax({
      url:"/getstudledger",
      method:'GET',
      data:{
      	studid:studid
      },
      dataType:'json',
      success:function(data)
      {
        $('#ledger-list').html(data.output);
        $('.menu-name').text(data.studname);
      }
    });
  });

  function viewtrans(search)
  {
    var terminalid = $('#terminals').val();
    var dtFrom = $('#dtFrom').val();
    var dtTo = $('#dtTo').val();

    $.ajax({
      url:"/getcashtrans",
      method:'GET',
      data:{
        terminalid:terminalid,
        dtFrom:dtFrom,
        dtTo:dtTo,
        search:search
      },
      dataType:'json',
      success:function(data)
      {
        $('#trans-list').html(data.output);
        $('.menu-name').text(data.studname);
      }
    });
  }

  $(document).on('click', '#viewTrans', function(){
    var search = $('#strans').val();

      viewtrans(search);
    
  });

  $(document).on('keyup', '#strans', function(){
    viewtrans($(this).val());
  });

  $(document).on('click', '#btnORSetup', function(){
    var ornum = $('#txtor').val();

    $.ajax({
      url:"/getornum_setup",
      method:'GET',
      data:{
        terminalno:terminalnumber
      },
      dataType:'json',
      success:function(data)
      {
        $('#curOR').text(data.curOR);
      }
    });

  });

  $(document).on('click', '#btnsave', function(){
    var ornum = $('#txtor').val();

    $.ajax({
      url:"/insertor_setup",
      method:'GET',
      data:{
       ornum:ornum,
       terminalno:terminalnumber
      },
      dataType:'json',
      success:function(data)
      {
        $('#curOR').text(data.curOR);
        $('#modal-orsetup').modal('hide');
      }
    });

  });

  $(document).on('click', '.btnterminal', function(){
    $.ajax({
      url:"/getterminal",
      method:'GET',
      data:{
        
      },
      dataType:'json',
      success:function(data)
      {
        $('#terminal').html(data.output);
        $('#modal-terminalsetup').modal('show')
      }
    });    
  });

  $(document).on('click', '#btnterminalsave', function(){
    var terminalid = $('#terminal').val();

    $.ajax({
      url:"/saveterminal",
      method:'GET',
      data:{
        terminalid:terminalid
      },
      dataType:'',
      success:function(data)
      {
        console.log(data);
        if(data == 1)
        {
          console.log('done');
          location.reload(true);
        }
        else
        {
          Swal.fire({
            position: 'top',
            type: 'error',
            title: 'Terminal already been used.',
            showConfirmButton: false,
            timer: 1500
          })
            
        }
      }
    }); 
  });

  $(document).on('click', '#setDay', function(){

    if(terminalnumber > 0)
    {

      $('#modal-opendaysetup').modal('show');


      $.ajax({
        url:"/getdatetime",
        method:'GET',
        data:{
          curDayID:curDayID
        },
        dataType:'',
        success:function(data)
        {
          if(curDayID == 0)
          {
            $('#opendatetime').text(data);
            $('#btnopenclose').attr('class', 'btn btn-success');
            $('#btnopenclose').text('START DAY');  
          }
          else
          {
            $('#opendatetime').text(data);
            $('#btnopenclose').attr('class', 'btn btn-danger');
            $('#btnopenclose').text('END DAY');    
          }
          
        }
      }); 
    }
    else
    {
      Swal.fire({
        position: 'top',
        type: 'error',
        title: 'No terminal has been set',
        showConfirmButton: false,
        timer: 1500
      })
    }
  });

  $(document).on('click', '#btnopenclose', function(){

    if(curDayID == 0)
    {
      $.ajax({
        url:"/opendaycash",
        method:'GET',
        data:{
          terminalid:terminalnumber
        },
        dataType:'',
        success:function(data)
        {
          $('#btnopenclose').attr('class', 'btn btn-danger');
          $('#btnopenclose').text('CLOSE');
          openDay();
        }
      });
    }
    else
    {
      $('#modal-confirm-closeDay').modal('show');
    }
  });

  $(document).on('click', '#nextTrans', function(){
    location.reload();
  });

  $(document).on('click', '#btnpostTrans', function(){
    // console.log(curDayID);
    $.ajax({
        url:"/postTrans",
        method:'GET',
        data:{
          // terminalid:terminalnumber,
          dayid:curDayID
        },
        dataType:'',
        success:function(data)
        {
          viewtrans(); 

        }
      });
  });

  $(document).on('click', '#btncloseDay', function(){
    $.ajax({
      url:"/endday",
      method:'GET',
      data:{
        terminalid:terminalnumber,
        dayid:curDayID
      },
      dataType:'json',
      success:function(data)
      {
        if(data.validation == 0)
        {

          const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });

          Toast.fire({
            type: 'warning',
            title: 'Please post all transactions.'
          });


          // Swal.fire('Any fool can use a computer');
        }
        else if(data.validation == 1)
        {

          $('#modal-opendaysetup').modal('hide');

          getTerminal();

          const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });

          Toast.fire({
            type: 'warning',
            title: 'Day ended.'
          });
        }

      }
    });
  });

  $(document).on('click', '#genCRS', function(){
    var dtFrom = $('#CRSdtFrom').val();
    var dtTo = $('#CRSdtTo').val();

    $.ajax({
        url:"/genCRS",
        method:'GET',
        data:{
          dtFrom:dtFrom,
          dtTo:dtTo,
          terminalno:terminalnumber
        },
        dataType:'json',
        success:function(data)
        {
          $('#cashsummary-list').html(data.output);
          $('#cashsummaryTotal').html(data.gtotal);
          $('#cashsummaryTotal').removeClass('oe_hidden');
        }
      });
  });

  $(document).on('click', '#btnprintCRS', function(){
    $('.screen').addClass('oe_hidden');
    $('#cashsummary-print').removeClass('oe_hidden');
  });

  $(document).on('click', '#btnprintCRS', function(){
    $('.crs-print').addClass('rpt-h');
    var block = $('#cashsummary-list').html();
    var block_f = $('#cashsummaryTotal').html();
    // console.log('<tr">' + block_f + '</tr>');
    $('#CRSprint-list').html(block);
    $('#CRSprint-list-foot').html('<tr>' + block_f + '</tr>');

    $.ajax({
        url:"/getCRSdatetime",
        method:'GET',
        data:{
          
        },
        dataType:'json',
        success:function(data)
        {
          var from = $('#CRSdtFrom').val();
          var to = $('#CRSdtTo').val();

          $('#crs_date').text(from + ' - ' + to);
          $('#printDT').text(data.dateto);
        }
      });

  });

  $(document).on('click', '#btnassessment', function(){
    var studid = $('#selstud').attr('stud-id');
    $('.screen').addClass('oe_hidden');
    $('#studassessment').removeClass('oe_hidden');
    $('#tb-assessment').prop('hidden', true);

    $.ajax({
      url:"/getstudassessment",
      method:'GET',
      data:{
        studid:studid
      },
      dataType:'json',
      success:function(data)
      {
        $('#assesment-list').html(data.output);
        $('.menu-name').text(data.studname);
        $('#monthsetup').html(data.month);
      }
    });

  });

  $(document).on('click', '#btngenassessment', function(){
    var strmonth = $('#monthsetup').val();
    var studid = $('#selstud').attr('stud-id');
    var showall = $('#showall').prop('checked');
    $.ajax({
      url:"/genassessment",
      method:'GET',
      data:{
        showall:showall,
        strmonth:strmonth,
        studid:studid
      },
      dataType:'json',
      success:function(data)
      {
        $('#tb-assessment').prop('hidden', false);
        $('#assessment-list').html(data.output);
        $('#assessment-list-footer').html(data.footer);
      }
    });
  });

  $(document).on('click', '#btnprintassessment', function(){
    $('.screen').addClass('oe_hidden');
    $('#assessment-print').removeClass('oe_hidden');

    $('#assessment_date').text($('#monthsetup').val());
    $('#print-assessment-name').text($('#assessment-name').text());
    $('#Assessmentprint-list').html($('#assessment-list').html());
    $('#Assessmentprint-list-foot').html($('#assessment-list-footer').html());

    $.ajax({
        url:"/getCRSdatetime",
        method:'GET',
        data:{
          
        },
        dataType:'json',
        success:function(data)
        {
          $('#AssessmentprintDT').text(data.dateto);
        }
      });
  });

  $(document).on('click', '#btnprintledger', function(){
    $('.screen').addClass('oe_hidden');
    $('#ledger-print').removeClass('oe_hidden');
    $('#print-ledger-name').text($('#ledger-name').text());
    $('#Ledgerprint-list').html($('#ledger-list').html());
    
    $.ajax({
        url:"/getCRSdatetime",
        method:'GET',
        data:{
          
        },
        dataType:'json',
        success:function(data)
        {
          $('#ledger_sy').text(data.syDesc);
          $('#LedgerprintDT').text(data.dateto);
        }
      });
  });

  $(document).on('click', '#btnprintcashtrans', function(){
    $('#btnprintcashtrans-list').trigger('click');
    // $('.screen').addClass('oe_hidden');
    // $('#cashiertrans-print').removeClass('oe_hidden');

    // var terminalid = $('#terminals').val();
    // var dtFrom = $('#dtFrom').val();
    // var dtTo = $('#dtTo').val();
    // var search = $('#strans').val();

    // $.ajax({
    //   url:"/getcashtransPrint",
    //   method:'GET',
    //   data:{
    //     terminalid:terminalid,
    //     dtFrom:dtFrom,
    //     dtTo:dtTo,
    //     search:search
    //   },
    //   dataType:'json',
    //   success:function(data)
    //   {

    //     // console.log(data);
    //     $('#trans-list-print').html(data.output);
    //     $('.menu-name').text(data.studname);
    //     $('#cashtrans_DT').text(data.dt);
    //     $('#cashtransPrintDate').text(data.datenow);
    //   }
    // });
  });

  $(document).on('click', '#showall', function(){
    $('#btngenassessment').trigger('click');
  });

  $(document).on('keyup', '#txtitemsearch', function(){
    var itemname = $(this).val();
    itemSearch(itemname);
  });

  $(document).on('mouseover', '#item-list tr', function(){
    $(this).addClass('bg-secondary');
  });

  $(document).on('mouseout', '#item-list tr', function(){
    $(this).removeClass('bg-secondary');
  });

  
  $(document).on('click', '#item-list tr', function(){
    if(curDayID > 0 && terminalnumber > 0)
    {
      var studid = $('#selstud').attr('stud-id');

      if(studid == '')
      {
        studid = 0;
      }

      detailid = $(this).attr('data-id');

      var transno = $('#selstud').attr('trans-no');
      var classid = $(this).attr('class-id');
      var particulars = $(this).children('.desc').text();
      var amount = $(this).children('.amount').text().replace(',', '');
      

      if(transno == '')
      {
        $.ajax({
          url:"/getTno",
          method:'GET',
          data:{
            
          },
          dataType:'',
          success:function(data)
          {
            transno = data;
            $('#selstud').attr('trans-no', data);
          
            $.ajax({
              url:"/cashtransitem",
              method:'GET',
              data:{
                studid:studid,
                detailid:detailid,
                classid:classid,
                particulars:particulars,
                amount:amount,
                terminalno:terminalnumber, 
                transno:transno,
                dayid:curDayID
              },
              dataType:'json',
              success:function(data)
              {
                $('#divOrder').html(data.output);
              }
            });
            
          }  
        });        
      }
      else
      {
        if($('#item-list').attr('data-id') == 1)
        {
          $.ajax({
            url:"/cashtransitem",
            method:'GET',
            data:{
              studid:studid,
              detailid:detailid,
              classid:classid,
              particulars:particulars,
              amount:amount,
              terminalno:terminalnumber, 
              transno:transno,
              dayid:curDayID
            },
            dataType:'json',
            success:function(data)
            {
              $('#divOrder').html(data.output);
            }
          });
        }
      }
      
    }
    else
    {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });

      Toast.fire({
        type: 'warning',
        title: 'No active day is set.'
      }); 
    }
  });

  $(document).on('click', '#custName', function(){
    $('#modal-custname').modal('show');
  });

  $(document).on('click', '#btnnamesave', function(){
    $('#custName').attr('data-value', $('#cname').val());
    $('#custName span').text($('#cname').val());
  });
  @php
    $schoolinfo = DB::table('schoolinfo')
      ->first()
      ->snr;
  @endphp//-----------------[shane]
    $(document).on('click', '.btn-view', function(){
      var transid = $(this).attr('data-id');
      $('input[name=formtrans-id]').val(transid)
      $('form[name=viewtransactionid]').submit();
    });
  //   $(document).on('click', '.btn-view', function(){
  //     $('.screen').addClass('oe_hidden');
  //     $('#viewreceipt').removeClass('oe_hidden');

  //     var transid = $(this).attr('data-id');
  //     // console.log(transid);

  //     $.ajax({
  //       url:"/viewCashTrans",
  //       method:'GET',
  //       data:{
  //         transid:transid
  //       },
  //       dataType:'json',
  //       success:function(data)
  //       {
  //         // console.log(data.totalamountpay);
  //         $('#v_items').html(data.items);
  //         $('#v_ornum').text(data.ornum);
  //         $('#v_sid').text(data.sid);
  //         $('#v_name').text(data.studname);
  //         $('#v_gradesection').text(data.gradesection);
  //         $('#v_number').text(data.totalamountpay);
  //         $('#v_date').text(data.tdate);
  //         $('#v_words').text(data.numtowords);

  //         if(data.void == 1)
  //         {
  //           $('.voidoverlay').prop('hidden', false);
  //         }
  //         else
  //         {
  //           $('.voidoverlay').prop('hidden', true);
  //         }
  //       }
  //     });

  //   });

  $(document).on('click', '#btnview-back', function(){
    $('.screen').addClass('oe_hidden');
    $('#cashiertrans').removeClass('oe_hidden');
  });

  $(document).on('click', '#back-cashtrans', function(){
    $('.screen').addClass('oe_hidden');
    $('#cashiertrans').removeClass('oe_hidden');
  });

  // $(document).on('click', '#')
  // $('#cashsummary').removeClass('oe_hidden');

  // $(document).on('click', '#inputOR', function(){
  //   keyON = 0;
  //   Swal.fire({
  //     title: 'Enter OR Number',
  //     input: 'text',
  //     inputAttributes: {
  //       autocapitalize: 'off'
  //     },
  //     showCancelButton: true,
  //     confirmButtonText: 'Submit',
  //     showLoaderOnConfirm: false,
  //     allowOutsideClick: () => !Swal.isLoading()
  //   }).then((result) => {

  //     $.ajax({
  //         url:"/checkusedor",
  //         method:'GET',
  //         data:{
  //           curor:result.value
  //         },
  //         dataType:'',
  //         success:function(data)
  //         {
  //           if(data == 0)
  //           {
  //             $('#cOR').text(result.value);
  //             keyON = 1;      
  //           }
  //           else
  //           {
  //             const Toast = Swal.mixin({
  //               toast: true,
  //               position: 'top',
  //               showConfirmButton: false,
  //               timer: 3000,
  //               timerProgressBar: true,
  //               onOpen: (toast) => {
  //                 toast.addEventListener('mouseenter', Swal.stopTimer)
  //                 toast.addEventListener('mouseleave', Swal.resumeTimer)
  //               }
  //             })

  //             Toast.fire({
  //               type: 'warning',
  //               title: 'OR Number already been used.'
  //             }) 
  //           }
  //         }
  //       });


        
      
  //   })
  // });

  $(document).on('click', '#inputOR', function(){
    $('#modal-changeor').modal('show');
    $('#txtchangeor').val('');
    $('#chkreuse').prop('checked', false);
    $('#divReuse').addClass('oe_hidden');
    $('#txtchangeor').prop('placeholder', $('#cOR').text());
    keyON = 0;
  });

  $('#modal-changeor').on('shown.bs.modal', function(){
    $('#txtchangeor').focus();
  });

  $(document).on('keyup', '#txtchangeor', function(e){
    if(e.keyCode == 13)
    {
      $('#submitChangeOR').trigger('click');
    }
  });
  
  $(document).on('click', '#submitChangeOR', function(){
    var curor = $('#txtchangeor').val();
    
    if($('#chkreuse').prop('checked') == true)
      var reuse = 1;
    else
      var reuse = 0;

    $.ajax({
      url:"/checkusedor",
      method:'GET',
      data:{
        curor:curor,
        reuse:reuse
      },
      dataType:'',
      success:function(data)
      {
        if(data == 0)
        {
          $('#cOR').text($('#txtchangeor').val());
          keyON = 1;
          $('#modal-changeor').modal('hide');
        }
        else
        {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            type: 'warning',
            title: 'OR Number already been used.'
          })

          $('#divReuse').removeClass('oe_hidden');
        }
      }
    });
  });

  $(document).on('focus', '.form-control', function(){
    // console.log(keyON);
    keyON = 0;
    $('.paymentline td').removeClass('edit');
  });



  $(document).on('click', '#btnqty', function(){

    var itemCount = 0;
    $('.orderlines').each(function(){
      itemCount += 1;
    });
    if(itemCount > 0)
    {
      Swal.fire({
        title: 'Enter QTY',
        input: 'number',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Submit',
        showLoaderOnConfirm: false,
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if(result.value)
        {
          if(result.value > 0)
          {
            $('.orderline').each(function(){
              if($(this).attr('class') == 'orderline bg-success')
              {
                cashtransid = $(this).attr('data-id');
              }
            });

            var qty = result.value;
            var transno = $('#selstud').attr('trans-no');
            $.ajax({
              url:"/getQTY",
              method:'GET',
              data:{
                cashtransid:cashtransid,
                qty:qty,
                transno:transno
              },
              dataType:'json',
              success:function(data)
              {
                if(data.return == 1)
                {
                  $('#divOrder').html(data.output);
                }
                else
                {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  });

                  Toast.fire({
                    type: 'warning',
                    title: 'You cannot change QTY on Tuition items.'
                  });
                }
              }
            });
          }
        }

      }) 
        
    }
  });

  $(document).on('click', '#btnprice', function(){
    var itemCount = 0;
    $('.orderlines').each(function(){
      itemCount += 1;
      // console.log(itemCount);
    });
    if(itemCount > 0)
    {
      Swal.fire({
        title: 'Enter Item Price',
        input: 'text',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Submit',
        showLoaderOnConfirm: false,
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if(result.value)
        {
          // console.log(result.value);
          if(result.value > 0)
          {
            $('.orderline').each(function(){
              if($(this).attr('class') == 'orderline bg-success')
              {
                cashtransid = $(this).attr('data-id');
                // console.log(cashtransid);
              }
            });

            var price = result.value;
            var transno = $('#selstud').attr('trans-no');
            $.ajax({
              url:"/editAmount",
              method:'GET',
              data:{
                cashtransid:cashtransid,
                price:price,
                transno:transno
              },
              dataType:'json',
              success:function(data)
              {
                if(data.return == 1)
                {
                  $('#divOrder').html(data.output);
                }
                else
                {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  });

                  Toast.fire({
                    type: 'warning',
                    title: 'You cannot change PRICE on Tuition items.'
                  });
                }
              }
            });
          }
        }

      }) 
    }
  });

  $(document).on('click', '.btn-void', function(){
    $('#voiduname').val('');
    $('#voidpword').val('');
    $('#voidheader').text('Confirm Void - ' + $(this).attr('data-or'));
    $('#btnconfirm').attr('data-id', $(this).attr('data-id'));
  });

  $(document).on('click', '#btnconfirm', function(){
    var transid = $(this).attr('data-id');
    var uname = $('#voiduname').val();
    var pword = $('#voidpword').val();
    // console.log(uname)

    $.ajax({
      url:"/voidtrans",
      method:'GET',
      data:{
        transid:transid,
        uname:uname,
        pword:pword
      },
      dataType:'json',
      success:function(data)
      {
        if(data.return == 1)
        {
          $('#divOrder').html(data.output);
        }
        else
        {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });

          if(data == 1)
          {
            Toast.fire({
              type: 'success',
              title: 'Transaction successfully voided.'
            }); 
            viewtrans()           
          }
          else if(data == 5)
          {
            Toast.fire({
              type: 'warning',
              title: 'User has no permission to void transactions'
            });
          }
          else if(data == 0)
          {
            Toast.fire({
              type: 'warning',
              title: 'Invalid User or Password.'
            });  
          } 
        }
      }
    });
  });

  $(document).on('click', '#btnonlinepay', function(){
    $('#modal-onlinepay').modal('show');


    $.ajax({
      url:"/onlinepay",
      method:'GET',
      data:{
        
      },
      dataType:'json',
      success:function(data)
      {
        $('#pay-list').html(data.list)
      }
    });
  });

  

  $(document).on('mouseetner', '#pay-list tr', function(){
    $(this).addClass('bg-info');
  });

  $(document).on('mouseout', '#pay-list tr', function(){
    $(this).removeClass('bg-info');
  });

  function olPaytrans(olid)
  {
    // $('#selstud').attr('trans-no', data);
    var dataid = olid;
  
  // var terminalno = terminalnumber;
    console.log('wa');
    
    $.ajax({
      url:"/onlinedetail",
      method:'GET',
      data:{
        dataid:dataid,
        terminalno:terminalnumber
      },
      dataType:'json',
      success:function(data)
      {
        var detailid;
        var paykind;
        
        $('#divOrder').html(data.output);
        
        $('.orderline').each(function(){
          
          detailid = $(this).attr('detail-id');
          
          $('.product').each(function(){

            if($(this).attr('aria-labelledby') == detailid)
            {
              console.log($(this).attr('aria-labelledby'));
              $(this).hide();
            }
          });
        });
      }
    });
  }
  // // $(document).on('click', '#pay-list tr', function(){
    
  // });
  // $(document).on('click', '#pay-list tr', function(){
  //   $('#selcustomer').trigger('click');
  //   console.log('test');
  // });

  function selectstudent()
  {
    $('#mainview').attr('class', 'product-screen screen');
    // $('#studlist').attr('class', 'clientlist-screen screen oe_hidden');
    console.log();
    $('#selstud').attr('stud-id', $('#selcustomer').attr('selstud-id'));
    $('#selstud').text($('#selcustomer').attr('selstud-name'));
    $('#selstud').attr('class', 'button set-student highlight');
    $('#userimg').hide();
    $('.payment-info').addClass('oe_hidden');
    $('#onlinepayinfocontainer').removeClass('show');
    // $('#olinfo-amount').val('');
    // $('#oltoast-paytype').attr('data-id', '');

    var studid = $('#selcustomer').attr('selstud-id');
    $.ajax({
      url:"/loadpaysched",
      method:'GET',
      data:{
        studid:studid
      },
      dataType:'json',
      success:function(data)
      {
        $('#payscheditems').html(data.output);
        $('#selstud').attr('trans-no', data.transno);
        $('#selstud').attr('or-num', data.ornum);
        $('#divOrder').html(data.orderlines);

        $('#selstud').attr('ol-id', $(this).attr('ol-id'));

        $('#onlinepayinfo').removeClass('oe_hidden');
        $('#onlinepayinfocontainer').addClass('show');        

        olPaytrans(olid);

        // if(data.payplan == 1)
        // {
        //   $('#btnpaymentplan').prop('hidden', false);

        // }
        // else
        // {
        //   $('#btnpaymentplan').prop('hidden', true);
        // }


        // itemSearch();
      }
    });
  }
  var olid;
  $(document).on('click', '#pay-list tr', function(){
    $('#modal-onlinepay').modal('hide');
    $('.screen').addClass('oe_hidden');
    $('#mainview').removeClass('oe_hidden');

    $('#olinfo-amount').val('');
    $('#oltoast-paytype').attr('data-id', '');

    $('#selcustomer').attr('selstud-id', $(this).attr('data-id'));
    $('#selcustomer').attr('selstud-name', $(this).attr('stud-name'));
    $('#selcustomer').attr('data-level', $(this).attr('data-level'));
    $('#oltoast-paytype').text($(this).find('.ol-paytype').text() + ' (' + $(this).find('.ol-paytype').attr('data-value') + ')');
    $('#oltoast-paytype').attr('data-id', $(this).find('.ol-paytype').attr('data-id'));
    $('#oltoast-amountpay').text($(this).find('.ol-amount').text());
    $('#oltoast-studname').text($(this).find('.ol-studname').text());
    $('#oltoast-refnum').text($(this).find('.ol-refnum').text());
    $('#olinfo-amount').val($(this).find('.ol-amount').attr('data-value'));
    $('#olinfo-bankname').val($(this).find('.ol-paytype').attr('data-value'));
    $('#oltoast-transdate').text($(this).find('.ol-transdate').text());
    $('#oltoast-transdate').attr('data-value', $(this).find('.ol-transdate').attr('data-value'));

    olid = $(this).attr('ol-id');
    // $('#selcustomer').trigger('click');
    selectstudent();
  });

  $(document).on('click', '.ol-close', function(){
    location.reload();
  });

  $(document).on('click', '#btnolPay', function(){
    $('#btnonlinepay').trigger('click');
  });

  $(document).on('click', '#btnnumLedger', function(){
    $('#btnledger').trigger('click');
  });

  $(document).on('click', '#btnnumAssessment', function(){
    $('#btnassessment').trigger('click');
  });

  $(document).on('click', '#logoutcash', function(){

    Swal.fire({
      title: 'Are you sure you want to logout?',
      text: "",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Logout'
    }).then((result) => {
      if (result.value) {
        $('#btnlogout').trigger('click');
      }
    })

  });

  $(document).on('click', '#btnprintcashtrans-list', function(){
    window.open('printcashtrans/' + $('#terminals').val() + '/' + $('#dtFrom').val() + '/' + $('#dtTo').val() + '/"' + $('#strans').val() + '"', '_blank');
  });

  $(document).on('click', '#prntOR', function(){
    //-----------------[shane]
      window.open('printor/' + $(this).attr('data-id')+'--'+$('#tAmountCopy').text());
      $('#nextTrans').trigger('click');
  });

  $(document).on('click', '#changepass', function(){
    $('#modal-changepass').modal('show');
    val_changepass();
  })

  $(document).on('keyup', '#newpass', function(){
    if($(this).val().length >= 8)
    {
      $(this).addClass('is-valid');
      $(this).removeClass('is-invalid');
    }
    else
    {
      $(this).removeClass('is-valid');
      $(this).addClass('is-invalid'); 
    }

    val_changepass();
  });

  $(document).on('keyup', '#confirmpass', function(){
    if($(this).val() == $('#newpass').val() && $(this).val().length >= 8)
    {
      $(this).addClass('is-valid');
      $(this).removeClass('is-invalid'); 
    }
    else
    {
      $(this).removeClass('is-valid');
      $(this).addClass('is-invalid');
    }
    val_changepass();
  });

  function val_changepass()
  {
    $('.val-changepass').each(function(){
      var vcount = 0;
      if($(this).hasClass('is-invalid'))
      {
        vcount += 1;
      }

      if(vcount > 0)
      {
        $('#btnsavechangepass').prop('disabled', true)
      }
      else
      {
        $('#btnsavechangepass').prop('disabled', false) 
      }
    })
  }

  $(document).on('click', '#btnsavechangepass', function(){
    if($('#confirmpass').val() == $('#newpass').val())
    {
      var oldpass = $('#oldpass').val();
      var newpass = $('#newpass').val();
      var confirmpass = $('#confirmpass').val();

        $.ajax({
        url:"/changepass",
        method:'GET',
        data:{
          oldpass:oldpass,
          newpass:newpass,
          confirmpass:confirmpass
        },
        dataType:'json',
        success:function(data)
        {
          if(data == 1)
          {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              type: 'success',
              title: 'Password successfully changed.'
            })

            $('#modal-changepass').modal('hide');

            $('#logoutcash').trigger('click');
          }
          else if(data == 2)
          {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              type: 'error',
              title: 'Invalid Old password.'
            });
          }
        }
      });
    }
    else
    {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })

      Toast.fire({
        type: 'error',
        title: 'Password not match.'
      })
    }
  });
  
  function checkfile()
  {
    $.ajax({
      url:"/checklogo",
      method:'GET',
      dataType:'',
      success:function(data)
      {
        // if(data == 0)
        // {
        //   window.location.reload();
        // }
      }
    });
  }

  checkfile();

  $(document).on('keypress', '#txtchangeor', function(key){
    if(key.charCode < 48 || key.charCode > 57) return false;
  });


  $(document).on('click', '#btnEnterAmount', function(){
      
    {
      $('#modal-cashamount').modal('show');
      $('#txtcashamount').val('');
    }


  });

  $(document).on('shown.bs.modal', '#modal-cashamount', function(){
    $('#txtcashamount').focus();
  });

  $(document).on('keyup', '#txtcashamount', function(e){
    if(e.keyCode == 13)
    {
      $('#btnCashAmount').trigger('click');
    }
  });

  $(document).on('click', '#btnCashAmount', function(){
    $('#modal-cashamount').modal('hide'); 
    genItemAmountEnter();
  });

  function genItemAmountEnter()
  {
    var amount = $('#txtcashamount').val();
    var studid = $('#selstud').attr('stud-id');
    var transno = $('#selstud').attr('trans-no')
    var classid = $('#ea_class').val();

    $.ajax({
      url:"/amountenter",
      method:'GET',
      data:{
        studid:studid,
        amount:amount,
        transno:transno,
        terminalno:terminalnumber,
        dayid:curDayID,
        classid:classid
      },
      dataType:'json',
      success:function(data)
      {
        $('#divOrder').html(data.output);

        $('.orderline').each(function(){
          
          detailid = $(this).attr('detail-id');
          detailamount = $(this).attr('data-value');
          
          $('.product').each(function(){

            if($(this).attr('aria-labelledby') == detailid)
            {
              if($(this).attr('data-value') == detailamount)  
              {
                $(this).hide();  
              }
              else
              {
                amountprod = $(this).attr('data-value') - detailamount 

                $('#' + detailid).html('&#8369; '+ amountprod.toLocaleString());
              }
              
            }
          });
        });
      }
    });
  }

  // $(document).on('mouseenter', '.pos-logo', function(){
  //   var ver = 'ver 1.2.06.26.2020';
    
  //   setTimeout(function(){
  //     const Toast = Swal.mixin({
  //           toast: true,
  //           position: 'top-end',
  //           showConfirmButton: false,
  //           timer: 5000,
  //           timerProgressBar: false,
  //           onOpen: (toast) => {
  //             toast.addEventListener('mouseenter', Swal.stopTimer)
  //             toast.addEventListener('mouseleave', Swal.resumeTimer)
  //           }
  //         })

  //         Toast.fire({
  //           type: 'success',
  //           title: ver
  //         })
  //   }, 5000)  
  // });

  $(document).on('click', '.username', function(){
    $('#logoutcash').trigger('click');
  });
























  

  $(document).on('click', '#bookentry', function(){

    $.ajax({
      url:"/bestudlist",
      method:'GET',
      data:{
        
      },
      dataType:'json',
      success:function(data)
      {
        console.log('testing');
        $('#be_studlist').html(data.list);
        loadbookentries();
        $('#modal-bookentry-list').modal('show');
      }
    });
  });

  $(document).on('click', '#be_create', function(){
    $('#be_studlist').val(0);
    $('#be_studlist').trigger('change');
    $('#be_amount').val('');
    $('#be_proceed').text('Proceed');
    $('#be_proceed').attr('data-action', 'create')
    $('#be_proceed').prop('disabled', false);
    $('.btn-action').hide();

    $('#modal-bookentry').modal('show');
  });

  $(document).on('click', '#be_proceed', function(){

    var studid = $('#be_studlist').val();
    var amount = $('#be_amount').val();
    var action = $('#be_proceed').attr('data-action');

    if($('#be_proceed').attr('data-action') == 'create')
    {
      $.ajax({
        url:"/beappend",
        method:'GET',
        data:{
          studid:studid,
          amount:amount,
          action:action
        },
        dataType:'',
        success:function(data)
        {
          loadbookentries();
          $('#modal-bookentry').modal('hide');
        }
      });
    }
    else
    {
      var dataid = $('#be_proceed').attr('data-id');

      $.ajax({
        url:"/beappend",
        method:'GET',
        data:{
          studid:studid,
          amount:amount,
          dataid:dataid
        },
        dataType:'',
        success:function(data)
        {
          loadbookentries();
          $('#modal-bookentry').modal('hide');
        }
      }); 
    }
  });

  function loadbookentries()
  {
    var filter = $('#be_studsearch').val();
    var dtrange = $('#dtrange').val();

    $.ajax({
      url:"/loadbookentries",
      method:'GET',
      data:{
        filter:filter,
        dtrange:dtrange
      },
      dataType:'json',
      success:function(data)
      {
        $('#be_list').html(data.list);
      }
    });
  }

  $(document).on('change', '.filter', function(){
    loadbookentries();
  });


  $(document).on('mouseenter', '#be_list tr', function(){
    $(this).addClass('bg-secondary')
  });

  $(document).on('mouseout', '#be_list tr', function(){
    $(this).removeClass('bg-secondary')
  });

  $(document).on('click', '#be_list tr', function(){
    var dataid = $(this).attr('data-id');

    $.ajax({
      url: '/beedit',
      type: 'GET',
      dataType: 'json',
      data: {
        dataid:dataid
      },
      success:function(data)
      {
        $('#be_studlist').val(data.studid)
        $('#be_studlist').trigger('change');
        $('#be_amount').val(data.amount);

        if(data.status == 'DRAFT')
        {
          $('.btn-action').show();
          $('#be_proceed').prop('disabled', false);
        }
        else if(data.status == 'APPROVED')
        {
          $('.btn-action').hide(); 
          $('#be_proceed').prop('disabled', true);
        }

        $('#be_proceed').attr('data-action', 'edit');
        $('#be_proceed').attr('data-id', dataid);
        $('#be_proceed').text('Save');
        // $('.btn-action').show();

        $('#modal-bookentry').modal('show');
      }
    })
    
    
  });

  $(document).on('click', '#be_delete', function(){
    var dataid = $('#be_proceed').attr('data-id');

    Swal.fire({
      title: 'Delete book entry?',
      text: "",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        
        $.ajax({
          url: '/bedelete',
          type: 'GET',
          dataType: '',
          data: {
            dataid:dataid
          },
          success:function()
          {
            Swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )
            
            loadbookentries();
            $('#modal-bookentry').modal('hide');
          }
        })

      }
    })
  });

  $(document).on('click', '#be_approve', function(){
    var dataid = $('#be_proceed').attr('data-id');

    $.ajax({
      url: '/beapprove',
      type: 'GET',
      dataType: '',
      data: {
        dataid:dataid
      },
      success:function(data)
      {
        loadbookentries();
        $('#modal-bookentry').modal('hide');
      }
    })
  });

  $(document).on('click', '#btnpaymentplan', function(){
    var studid = $('#selstud').attr('stud-id');

    $.ajax({
      url: '/genfees',
      type: 'GET',
      dataType: 'json',
      data: {
        studid:studid
      },
      success:function(data)
      {
        var feesid = $('#selstud').attr('fees-id');

        $('#paymentplan_levelid').html(data.glevel);

        $('#fees-list').html(data.list);

        $('#fees-list tr').each(function(){
          if($(this).attr('data-id') == feesid)
          {
            $(this).addClass('bg-success');
          }
        });

        $('#btnpayplanselect').attr('data-id', data.dataid);
        $('#modal-paymentplan').modal('show');
      }
    });
  });

  $(document).on('mouseenter', '#fees-list tr', function(){
    $(this).addClass('bg-secondary');
  });

  $(document).on('mouseout', '#fees-list tr', function(){
    $(this).removeClass('bg-secondary');
  });

  $(document).on('click', '#fees-list tr', function(){

    $('.feesplan').removeClass('bg-success');

    $(this).removeClass('bg-secondary');
    $(this).addClass('bg-success');

    if($('#btnpayplanselect').attr('data-id') != $(this).attr('data-id'))
    {
      $(this).addClass('bg-success');
      $('#btnpayplanselect').attr('data-id', $(this).attr('data-id'))
    }
    else
    {
      $(this).removeClass('bg-success');
      $('#btnpayplanselect').attr('data-id', 0)
    }
  })

  function plandescription()
  {
    var studid = $('#selstud').attr('stud-id');

    $.ajax({
      url: '/plandesc',
      type: 'GET',
      dataType: 'json',
      data: {
        studid:studid
      },
      success:function(data)
      {
        console.log(data.plan);
        $('#plandescription').text(data.plan);
      }
    });
    
  }

  $(document).on('click', '#btnpayplanselect', function(){
    var feesid = $(this).attr('data-id');
    var studid = $('#selstud').attr('stud-id');

    $.ajax({
      url: '/saveplandesc',
      type: 'GET',
      dataType: '',
      data: {
        feesid:feesid,
        studid:studid
      },
      success:function(data)
      {
        $('#modal-paymentplan').modal('hide');
        plandescription();
      }
    });

  });

  $(document).on('select2:close', '#paymentplan_levelid', function(){
    var levelid = $(this).val();
    var studid = $('#selstud').attr('stud-id');

    // console.log('studid: ' + studid);

    $.ajax({
      url: '/genpayplanperlevel',
      type: 'GET',
      dataType: 'json',
      data: {
        levelid:levelid,
        studid:studid
      },
      success:function(data)
      {
        console.log(data.list);
        $('#fees-list').html(data.list);
      }
    })
    
  })

  $(document).on('click', '#btncheckposted_post', function(){
    $.ajax({
        url:"/postTrans",
        method:'GET',
        data:{
          // terminalid:terminalnumber,
          dayid:curDayID
        },
        dataType:'',
        success:function(data)
        {
          $('#btncloseDay').trigger('click');
        }
      });
  });

  $(document).on('click', '#btnprintexampermit', function(){
    var studid = $('#selstud').attr('stud-id');
    
    window.open("{{url('printExamP/')}}/" + studid);

  });

  $(document).on('click', '#savechangeparticulars', function(){
    var particulars = $('#changeparticulars').val();
    var dataid = $('#changeparticulars').attr('data-id');
    var transno = $('#selstud').attr('trans-no');

    $.ajax({
        url:"/changeparticulars",
        method:'GET',
        data:{
          particulars:particulars,
          dataid:dataid,
          transno:transno
        },
        dataType:'json',
        success:function(data)
        {
          $('#divOrder').html(data.output);
          $('#modal-changeparticulars').modal('hide');
        }
      });
  });

});

</script>