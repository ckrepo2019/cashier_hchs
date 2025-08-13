<script>
	
var idleTime = 0;
var timer, value;
$(document).ready(function(){
    ver = '2.5.07.22.2022.0831';

    $('#version_id').text('Ver ' + ver);

    $(document).on('click', '.pos-logo', function(){
        // var ver = '2.1.12.18.2020.09.56';

        // CHANGE LOGS
        // 'ver 2.1.10.29.2020.18.34';
        // -- Beta Version

        // 'ver = '2.1.12.03.2020.16.52';;
        // -- FIX (DCC) - Item distribution
        // -- AD CRS display

        // 'ver = '2.1.12.17.2020.09.42';;
        // -- Ledger Payment View Details
        // -- OR Restriction

        // 'ver = '2.1.12.18.2020.09.56';
        // -- OR Restriction add Re-use OR button

        // 'ver = '2.2.12.23.2020.10.12';
        // -- Online Payments

        // 'ver = '2.2.12.27.2020.15.24';
        // -- Opt for SHS Whole Year Fees and Collection Setup
        // -- Studledger Sem/SY Selection

        // 'ver = '2.2.01.11.2021.17.37';
        // -- add Features: Can Change SY/Semester in whole cashier

        // 'ver = '2.2.01.22.2021.17.01';
        // -- Fix Features: Can Change SY/Semester per cashier
        // -- Fetch DP per semester

        // 'ver = '2.2.01.23.2021.08.54';
        // -- Fix DP generation in Payment Info
        
        // 'ver = '2.2.02.05.2021.17.54';
        // -- Fix DP generation in Payment Info for DCC

        // 'ver = '2.2.02.06.2021.15.22';
        // -- Fix Transaction OR Printing
        // 'ver = '2.2.02.22.2021.11132';
        // -- Fix Multiple DP on PayInfo
        // -- Fix Itemized on MISC

        // 'ver = '2.2.05.03.2021.0848';
        // -- Change Enter Amount Proceedure
        // -- Add Purpose on Void
        // -- Fix Greater than amount in cart

        // 'ver = '2.2.05.07.2021.1324';
        // -- Bug fixed in Enter Amount Proceedure 
        // -- Fix Itemized in Void Transaction

        // 'ver = '2.2.05.28.2021.1757';
        // -- Fix Responsive configuration

        // 'ver = '2.2.06.09.2021.1310';
        // -- Fix Student Name Color Coding

        // 'ver = '2.2.06.16.2021.1116';
        // -- Bug fixed on DP ang Early Enrollment

        // 'ver = '2.2.06.24.2021.1252';
        // -- Bug fixed

        // 'ver = '2.2.06.25.2021.2051';
        // -- Book Entry

        // 'ver = '2.2.06.26.2021.1339';
        // -- CRS/Cashier Transaction Report
        // -- Fix Online payment amount

        // 'ver = '2.2.07.03.2021.1341';
        // -- Adjust Sequence on Distributions

        // 'ver = '2.2.07.04.2021.1805';
        // -- Fix Double entry in Book Entry
        // -- Fix Search function in Book Entry

        // 'ver = '2.2.07.13.2021.1605';
        // -- Allow Edit on Online payment transactions

        // 'ver = '2.2.08.09.2021.0211';
        // -- Fix Itemized Payment
        // -- Consolidated Report
        // -- Additional ProgID on cashier transaction

        // 'ver = '2.2.09.18.2021.1834';
        // -- Void Transaction Fixed

        // 'ver = '2.2.09.28.2021.1314';
        // -- Add On Update Student Ledger function
        
        // 'ver = '2.2.10.04.2021.1635';
        // -- SOA print function

        // 'ver = '2.3.11.12.2021.1623';
        // -- Balance Forwarding function
        // -- Monthly on Other Fees

        // 'ver = '2.3.12.21.2021.1442';
        // -- Item functions

        // 'ver = '2.3.12.28.2021.1450';
        // -- Fix Update Ledger

        // 'ver = '2.4.01.03.2022.1838';
        // -- Load Fees and Collection data if selected student is not enrolled

        // 'ver = '2.4.01.15.2022.1629';
        // -- Update Online payment process

        // 'ver = '2.4.01.17.2022.1113';
        // -- Fix Change amount greater thant the original value
        // -- Update feesid when selecting from fees list

        // 'ver = '2.4.02.05.2022.0849';
        // -- Fix Misc monthly payment info when not enrolled
        // -- Fix bug on Enter Amount

        // 'ver = '2.4.02.09.2022.1558';
        // -- Fix Enter amount on not enrolled students
        // -- Fix Void transaction

        // 'ver = '2.4.02.26.2022.1243';
        // -- Fix Itemized setup

        // 'ver = '2.4.04.09.2022.1619';
        // -- Fix Bugs
        // -- Fix Balance forwarding
        // -- Update switch SY and Sem

        // 'ver = '2.4.06.03.2022.1020';
        // -- Fix Minor Bugs
        // -- Online/Bank Transaction Information

        // 'ver = '2.4.06.06.2022.1429';
        // -- Add Student Assessment in Student Ledger

        // 'ver = '2.5.06.18.2022.1852';
        // -- SY Info change to 1 selection
        // -- Summer Ledger filter
        // -- Summer Payment
        // -- Add Bank Name to Cheque

        // 'ver = '2.5.06.20.2022.1808';
        // -- Add Overpayment features

        // 'ver = '2.5.07.04.2022.0812';
        // -- Book Entry with Specific Book
		
		// 'ver = '2.5.07.06.2022.0819';
        // -- Fix bugs on book entry
        // -- Fix bugs on student ledger
		
		// 'ver = '2.5.07.22.2022.0831';
        // -- Update ledger with Fees Selection
        
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

    screenadjust();

    function screenadjust()
    {
        var screen_height = $(window).height();
        var screen_bg = $(window).height() - 59;
        var screen_tui = $(window).height() - 179;
        var screen_misc = $(window).height() - 179;
        console.log('screen_height: ' + screen_height);
        // $('#table_main').css('height', screen_height);
        $('.screen-adj').css('height', screen_height - 223);
        $('.screen-bg').attr('style', 'width: 99%; top: -8; height: ' + screen_bg + ' !important;');
        $('#screen-tui').css('height', screen_tui);
        $('#screen-misc').css('height', screen_misc);
        $('.screen-sidepanel').css('height', (screen_tui / 2) - 27)
        $('#table_ollist').css('height', screen_height - 230);
        $('#table_v2assessment').css('height', screen_height - 230);
        $('#table_payhistory').css('height', screen_height - 270);


    }

    $(window).resize(function(){
        screenadjust();
    })



    $(".select2").select2({
        theme: "bootstrap4"
    });

    $('.dtrangepicker').daterangepicker()

    $('.disabled-input').keypress(function(e){
        return false;
    });

        // $('#adjdate').datepicker();

        // alert(jQuery.fn.jquery);

    var terminalnumber = 0;
    var curDayID = 0;
    var ipadd = '';
    var hostname = '';
    var paymentlist_lock = 0;


    var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
    var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

    function inWords (num) 
    {
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
    var _onlinepay = 0;
    var _onlinepayamount = 0;

    // console.log(inWords(2962));
    $('#words').text(amountinwords);

      // document.getElementById('number').onkeyup = function () {
      //   document.getElementById('words').innerHTML = inWords(document.getElementById('number').value);
      // };
    var idleInterval = setInterval(timerIncrement, 1000); // 1 seconds
    checkOLPay();
    function timerIncrement() 
    {    
        idleTime = idleTime + 1;
        if (idleTime == 20) // 20 sec
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
                $('#uname_sem').text(data.semdesc);
                $('#uname_sy').text(data.sydesc);
                
            }
        });
    }

    getTerminal();

    function format_number(n) {
        return n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    }

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
                // console.log(terminalnumber);
                // console.log('getTerminal: ' + terminalnumber)
                if(data.terminalid != 0)
                {
                  openDay();
                }
                else
                {
                    $('#modal-uinfo').modal('show');

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
                if(data.curOR == 0)
                {
                    $('#modal-orsetup').modal('show');
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
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
            dataType:'',
            success:function(data)
            {
                // console.log(data);
                $('#olcount') .text(data);
                idleTime = 0; 
            }
        });
    }

    $(document).on('click', '#printOR', function(){
        printDiv('cashOR');
    });

    getstudlist('');

    function getstudlist(query='')
    {
        $.ajax({
            url:"{{route('v2_studlist')}}",
            method:'GET',
            data:{
                query:query
            },
            dataType:'json',
            success:function(data)
            {
                $('#studlist').html(data.output);
                $('#searchStud').focus();
            }
        });
    }

    function getstudhistory(studid)
    {
        $.ajax({
            url:"{{route('v2_studhistory')}}",
            method:'GET',
            data:{
                studid:studid
            },
            dataType:'json',
            success:function(data)
            {
                $('#enrolledlist').html(data.list);
            }
        });
    }

    function v2_viewtrans(id)
    {
        $.ajax({
            url:"{{route('v2_viewtrans')}}",
            method:'GET',
            data:{
                transid:id
            },
            dataType:'json',
            success:function(data)
            {
                $('#head-ornum').text(data.ornum);
                $('#lblstudname').text(data.studname);
                $('#lblgrade').text(data.gradelevel);
                $('#lblidno').text(data.idno);
                $('#list-detail').html(data.list);
                $('#lbltransdate').text(data.transdate);
                $('#terminalno').text(data.terminalno);
                $('#lblcashier').text(data.cashier);
                $('#printtrans').attr('data-id', id);
                $('#head-ornum').attr('data-amount', data.totalamount);
                $('#lblpaytype').text(data.paytype);
                
                if(data.cancelled == 0)
                {
                    $('#modalhead').removeClass('bg-danger'); 
                    $('#modalhead').addClass('bg-primary');
                    $('#lblvoid').hide();
                }
                else
                {
                    $('#modalhead').addClass('bg-danger'); 
                    $('#modalhead').removeClass('bg-primary'); 
                    $('#lblvoid').show();
                }

                $('#modal-viewtrans').modal('show');
            }
        });
    }

    function getpayinfo(studid, mode, feesid=0)
    {
        $.ajax({
            url:"{{route('v2_payinfo')}}",
            method:'GET',
            data:{
                studid:studid,
                feesid:feesid
            },
            dataType:'json',
            beforeSend:function()
            {
                $('#modal-overlay').modal('show');
            },
            success:function(data)
            {
                // $('#modal-overlay').modal('hide');
                $('#studname-header').attr('fees-id', data.feesid);
                $('#studname-header').attr('data-sid', data.sid);
                $('#tlevelname').text(data.levelname);
                $('#studname-header').text(data.name + ' - ' + data.levelname + ' ' + data.course + ' ' + data.strand);
                $('#studinformation').html(data.info);


                $('#tuilist').html(data.tui);
                $('#othlist').html(data.oth);
                $('#oldlist').html(data.old);
                $('#misclist').html(data.misc);
                $('#reglist').html(data.reg);

                $('#tuitotal').text(data.tuitotal);
                $('#othtotal').text(data.othtotal);
                $('#fees_balance').text(data.oldtotal);
				$('#oldtotal').text(data.oldtotal);
                $('#misctotal').text(data.misctotal);
                $('#regtotal').text(data.regtotal);

                $('#gtotal').text(data.gtotal);
                $('#studname-header').attr('trans-id', data.tnum);
                $('#studname-header').attr('stud-id', data.studid);
                $('#studname-header').attr('level-id', data.levelid);
                $('#v2_payor').val(data.name);
                $('#v2_payor').prop('disabled', true);
                $('#v2_payor').addClass('bg-dark');
                $('#ledger_info').html(data.ledgerinfo_list);

                $('#v2_acadprog').val(data.acadprogid);
                $('#v2_acadprog').trigger('change');

                $('#ea-classitem').html(data.citem);

                if(mode == 0)
                {
                    $('#divcart').show();
                }

                setTimeout(function(){
                    $('#modal-overlay').modal('hide');
                }, 1000);

                if(data.isEnroll == 0 && feesid == 0)
                {
                    $('#fees_list').html(data.feelist);
                    $('#fees_level').val(data.levelid).change();
                    $('#modal-fees').modal('show');
                }
                else
                {
                    if(_onlinepay == 1)
                    {
                        $("#btn_enteramount").trigger('click');
                        $('#v2_enteramount').val(_onlinepayamount);
                        setTimeout(function(){
                            $('#v2_btnenteramount').trigger('click');
                            _onlinepayamountamount = 0;
                        }, 500)
                    }
                }

                if(data.noloading == 1)
                {
                    $('#nosubjloading').show();
                }
                else
                {
                    $('#nosubjloading').hide();
                }

                var c_sy = data.syid;
                var c_sem = data.semid;

                c_info = c_sy +','+c_sem;

                $('#ledger_info').val(c_info).change;
				console.log(data.oldtotal);
				if(data.oldtotal != null && parseFloat(data.oldtotal) > 0)
                {
                    $('#info_balance').show();
                }
				else
				{
					$('#info_balance').hide();
				}

            }
        });
    }

    function v2_loaditems()
    {
        var filter = $('#filter').val();

        $.ajax({
            url: '{{route('v2_loaditems')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                filter:filter
            },
            success:function(data)
            {
                $('#v2_itemlist').html(data.list);
            }
        });
    }

    function v2_ledger(studid, info)
    {
        $.ajax({
            url: '{{route('v2_studledger')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                studid:studid,
                info:info
            },
            success:function(data)
            {
                console.log('aaa');
                $('#v2ledger-list').html(data.output);
                $('#v2_sid').text(data.sid);
                $('#v2_studname').text(data.studname);
                $('#v2_grade').text(data.gradesection);
                $('#modal-v2ledger').modal('show');
            }
        });
    }

    function v2_assessment(studid, month, showall)
    {
        $.ajax({
            url: '{{route('v2_assessment')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                studid:studid,
                month:month,
                showall:showall
            },
            success:function(data)
            {
                $('#v2assessment-list').html(data.output);
                $('#v2_assessment_sid').text(data.sid);
                $('#v2_assessment_studname').text(data.studname);
                $('#v2_assessment_grade').text(data.levelname);
                $('#modal-v2assessment').modal('show');
            }
        });   
    }

    function v2_transactions(terminalno)
    {
        // var terminalid = $('#terminals').val();
        var search = $('#v2_transsearch').val();
        var dtFrom = $('#v2_transdatefrom').val();
        var dtTo = $('#v2_transdateto').val();

        // console.log(dtFrom);

        $.ajax({
            url:"{{route('v2_transactions')}}",
            method:'GET',
            data:{
                terminalid:terminalno,
                dtFrom:dtFrom,
                dtTo:dtTo,
                search:search
            },
            dataType:'json',
            success:function(data)
            {
                $('#v2_transactionlist').html(data.output);
                $('.menu-name').text(data.studname);
            }
        });
    }

    function v2_items(filter)
    {
        $.ajax({
            url: '{{route('v2_items')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                filter:filter
            },
            success:function(data)
            {
                $('#item_list').html(data.list);
                $('#modal-items').modal('show');        
            }
        });
    }

    $(document).on('click', '#item_create', function(){
        $.ajax({
            url: '{{route('load_classification')}}',
            type: 'GET',
            dataType: 'json',
            success:function(data)
            {
                $('#item_action').text(' - Create');
                $('#item_classid').html(data.classlist);
                $('#item_glid').html(data.coalist);
                $('#item_save').attr('data-id', 0);
                $('#modal-items_detail').modal('show');
            }
        });
    });

    $(document).on('change', '#item_filter', function(){
        v2_items($(this).val());
    });

    $(document).on('click', '#item_save', function(){
        var dataid = $('#item_save').attr('data-id');
        var code = $('#item_code').val();
        var classcode = $('#item_classcode').val();
        var description = $('#item_desc').val();
        var classid = $('#item_classid').val();
        var amount = $('#item_amount').val();
        var glid = $('#item_glid').val();

        if($('#item_cash').prop('checked') == true)
        {
            var cash = 1;
        }
        else
        {
            cash = 0;
        }

        $.ajax({
            url: '{{route('v2_items_save')}}',
            type: 'GET',
            data: {
                dataid:dataid,
                code:code,
                classcode:classcode,
                description:description,
                classid:classid,
                amount:amount,
                glid:glid,
                cash:cash,
            },
            success:function(data)
            {
                if(data == 'done')
                {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'success',
                        title: 'Save successfully'
                    });

                    v2_items($('#item_filter').val());

                    $('#modal-items_detail').modal('hide');
                }
                else
                {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'error',
                        title: 'Item already exist'
                    })   
                }
            }
        });
    });

    $(document).on('click', '#item_list tr', function(){
        var dataid = $(this).attr('data-id');

        $.ajax({
            url: '{{route('v2_items_edit')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                dataid:dataid
            },
            success:function(data)
            {
                $('#item_classid').html(data.classlist);
                $('#item_glid').html(data.coalist);

                setTimeout(function(){
                    $('#item_code').val(data.code);
                    $('#item_classcode').val(data.classcode);
                    $('#item_classcode').trigger('change');
                    $('#item_desc').val(data.description);
                    $('#item_classid').val(data.classid);
                    $('#item_classid').trigger('change');
                    $('#item_amount').val(data.amount);
                    $('#item_glid').val(data.glid);
                    $('#item_glid').trigger('change');
                    $('#item_save').attr('data-id', data.dataid);

                    if(data.cash == 1)
                    {
                        $('#item_cash').prop('checked', true);
                    }
                    else
                    {
                        $('#item_cash').prop('checked', false);
                    }

                    if(data.receivable == 1)
                    {
                        $('#item_receivable').prop('checked', true);
                    }
                    else
                    {
                        $('#item_receivable').prop('checked', false);
                    }

                    if(data.expense == 1)
                    {
                        $('#item_expense').prop('checked', true);
                    }
                    else
                    {
                        $('#item_expense').prop('checked', false);
                    }

                    $('#item_action').text(' - Edit');
                    $('#modal-items_detail').modal('show');

                }, 300)
            }
        });
    })

    $(document).on('click', '#print_v2cashtransaction', function(){

        window.open('printcashtrans/' + terminalnumber + '/' + $('#v2_transdatefrom').val() + '/' + $('#v2_transdateto').val() + '/"' + $('#v2_transsearch').val(), '_blank');
    });

    $(document).on('click', '#studlist tr', function(){    

        $('#studlist tr').each(function(){
            $(this).removeClass('bg-secondary');
        });

        $(this).addClass('bg-secondary');

        // $('#divstudlist').hide();
        
        $('#studname-header').attr('data-status', $(this).attr('data-status'));
        getpayinfo($(this).attr('data-id'), 0);

    });

    $(document).on('click', '#cancel-trans', function(){
        window.location.reload();
    });

    $(document).on('click', '.payment-list tr', function(){
        var payschedid = $(this).attr('data-id');
        var datavalue = $(this).find('.payval').attr('data-value');
        var levelid = $('#studname-header').attr('level-id');
        var studid = $('#studname-header').attr('stud-id');
        var transno = $('#studname-header').attr('trans-id');
        var particulars = $(this).find('.paydesc').text();
        var terminalno = terminalnumber;
        var kind = $(this).attr('data-kind');
        var itemized = $(this).attr('itemized-id');
        var classid = $(this).attr('data-classid');
        var click_source = $(this).attr('data-source');
        var itemid = $(this).attr('data-item');
        var datadue = $(this).attr('data-due');
        var studstatus = $('#studname-header').attr('data-status');
		
		if(terminalno > 0)
		{
            if(paymentlist_lock != 1)
            {
    			if(datavalue == 0)
    			{
    				const Toast = Swal.mixin({
    					toast: true,
    					position: 'top',
    					showConfirmButton: false,
    					timer: 3000,
    					timerProgressBar: true,
    					didOpen: (toast) => {
    						toast.addEventListener('mouseenter', Swal.stopTimer)
    						toast.addEventListener('mouseleave', Swal.resumeTimer)
    					}
    				});

    				Toast.fire({
    				type: 'error',
    				title: 'Item is already paid in full.'
    				})
    			}
    			else if($(this).hasClass('bg-primary'))
    			{
    				const Toast = Swal.mixin({
    					toast: true,
    					position: 'top',
    					showConfirmButton: false,
    					timer: 3000,
    					timerProgressBar: true,
    					didOpen: (toast) => {
    						toast.addEventListener('mouseenter', Swal.stopTimer)
    						toast.addEventListener('mouseleave', Swal.resumeTimer)
    					}
    				});

    				Toast.fire({
    				type: 'warning',
    				title: 'Item is already in the Selected Items.'
    				})   
    			}
    			else
    			{
    				$(this).addClass('bg-primary');
                    console.log($(this).attr('data-due'));

                    var feesid = $('#studname-header').attr('fees-id');

    				if('{{DB::table('schoolinfo')->first()->snr}}' == 'dcc')
    				{
    					$.ajax({
    						url:"{{route('v2_pushtotransDCC')}}",
    						method:'GET',
    						data:{
    							payschedid:payschedid,
    							studid:studid,
    							levelid:levelid,
    							transno:transno,
    							particulars:particulars,
    							terminalno:terminalno,
    							kind:kind,
    							itemized:itemized,
                                datavalue:datavalue
    						},
    						dataType:'json',
    						success:function(data)
    						{
    							
    							$('#orderlist').html(data.line);
    							$('#orderlisttotal').text(data.total);
    						}
    					});   
    				}
    				else
    				{
    					$.ajax({
    						url:"{{route('v2_pushtotrans')}}",
    						method:'GET',
    						data:{
    							payschedid:payschedid,
    							studid:studid,
    							levelid:levelid,
    							transno:transno,
    							particulars:particulars,
    							terminalno:terminalno,
    							kind:kind,
    							itemized:itemized,
                                classid:classid,
                                source:click_source,
                                itemid:itemid,
                                datadue:datadue,
                                datavalue:datavalue,
                                feesid:feesid,
                                studstatus:studstatus
    						},
    						dataType:'json',
    						success:function(data)
    						{
                                if(data.status == 2)
                                {
                                    $('#modal-othmlist').modal('show');
                                    $('#othlist tr[data-id="'+payschedid+'"]').removeClass('bg-primary');
                                    $('.othmlist_title').text(data.particulars);
                                    $('#othmamount').text(data.totalamount);
                                    $('#othmlist').html(data.othmlist);
                                }
                                else
                                {
        							$('#orderlist').html(data.line);
        							$('#orderlisttotal').text(data.total);
                                    
                                    if(_performEnteramount == 1)
                                    {
                                        eamount = $('#v2_enteramount').val();
                                        ordertotal = $('#orderlisttotal').text();

                                        eamount = eamount.replace(',', '');
                                        ordertotal = ordertotal.replace(',', '');

                                        console.log(ordertotal + ' < ' + eamount);

                                        if(parseFloat(ordertotal) < parseFloat(eamount))
                                        {
                                            console.log('processenteramount');
                                            processenteramount();
                                        }
                                        else
                                        {
                                            setTimeout(function(){
                                                fixamount(data.total);
                                            }, 300)    
                                        }
                                            
                                    }
                                }
    						}
    					});   
    				}
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
				didOpen: (toast) => {
					toast.addEventListener('mouseenter', Swal.stopTimer)
					toast.addEventListener('mouseleave', Swal.resumeTimer)
				}
			})

			Toast.fire({
			type: 'error',
			title: 'No Terminal is set.'
			})
		}
    });

    $('#v2_payor').keyup(function(event) {
        $(this).val($(this).val().toUpperCase());
    });

    $('#v2_type').keypress(function(e){
        return false;
    })

    $(document).on('click', '#payprocess', function(){

        if($('#orderlist tr').length > 0)
        {
            $('#v2_due').val($('#orderlisttotal').text());

            distflag();
            $.ajax({
                url: '/getornum',
                type: 'GET',
                dataType: 'json',
                data: {
                    terminalno:terminalnumber,
                    paytypeid:1
                },
                success:function(data)
                {

                    // console.log(data.checkOR);=
                    if(1 > 0)
                    {
                        $('#v2_ornum').val(data.curOR);

                        if(data.checkOR == 1)
                        {
                            $('#v2_performpay').prop('disabled', true);
                        }
                        else
                        {
                            $('#v2_performpay').prop('disabled', false); 
                        }

                        if($('#studname-header').attr('ol-id') > 0)
                        {
                            $('#v2_tendered').val($('#orderlisttotal').text()); 
                            $('#v2_tendered').prop('disabled', true);
                            $('#v2_type').val($('#studname-header').attr('data-paytype'));
                            $('#v2_ornum').focus();
                        }


                        $('#divpayprocess').show();
                        $('#divitems').hide();
                        $('#divpaysched').show();

                        if($('#v2_payor').val() == '')
                        {
                            $('#v2_payor').focus();
                        }
                        else
                        {
                            $('#v2_tendered').focus();
                        }

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
        }
    });

    var _distflag = 0;
    function distflag()
    {
        var ocounter = 0;
        var studstatus = $('#studname-header').attr('data-status');

        $('#orderlist tr').each(function(){
            var olist_amount = $(this).find('.line-val').val().replace(',', '');
            var payschedid = $(this).attr('paysched-id');

            if($(this).attr('paysched-id') == '')
            {
                var payschedid = 0;
            }
            else
            {
                var payschedid = $(this).attr('paysched-id');
            }

            var kind = '';

            if($(this).attr('data-kind') == 'dp')
            {
                var payval = $('#reglist [data-id='+payschedid+']').find('.payval').attr('data-value');    
            }
            else if($(this).attr('data-kind') == 'misc')
            {
                var payval = $('#misclist [data-id='+payschedid+']').find('.payval').attr('data-value');       
            }
            else if($(this).attr('data-kind') == 'tui')
            {
                var payval = $('#tuilist [data-id='+payschedid+']').find('.payval').attr('data-value');       
            }
            else if($(this).attr('data-kind') == 'oth')
            {
                var payval = $('#othlist [data-id='+payschedid+']').find('.payval').attr('data-value');       
            }
            else if($(this).attr('data-kind') == 'old')
            {
                var payval = $('#oldlist [data-id='+payschedid+']').find('.payval').attr('data-value');       
            }
            

            console.log(olist_amount + ' > ' + payval);
            if(olist_amount > payval)
            {
                ocounter += 1;
            }
        })

        if(ocounter > 0)
        {   
            if(studstatus == 1)
            {
                _distflag = 1;
            }
            else
            {
                _distflag = 0;
            }
        }
        else
        {
            _distflag = 0;
        }

        console.log('_distflag: ' + _distflag);
    }

    
    $(document).on('click', '#back-payment', function(){
        $('#divpayprocess').hide();
    });

    $(document).on('click', '.btn-type', function(){
        if($(this).hasClass('active'))
        {
            $('.btn-type').removeClass('active');   
        }
        else
        {
            $('.btn-type').removeClass('active');
            $(this).addClass('active');
        }
    });

    $(document).on('keyup', '#v2_tendered', function(){
        amount = $(this).val().replace(',', '');
        due = $('#v2_due').val().replace(',', '');

        changeamount = amount - due;

        
        // changeamount.number(parseFloat(changeamount), 2);

        if(changeamount > 0)
        {
            var v2change = '';

            $('#colchange').number(parseFloat(changeamount), 2);
            v2change = $('#colchange').text();
            $('#v2_change').val(v2change);


        }
        else
        {
            $('#v2_change').val('');
        }

    });

    $(document).on('click', '#v2_performpay', function(){
        var transno = $('#studname-header').attr('trans-id');
        var studid = $('#studname-header').attr('stud-id');
        var ornum = $('#v2_ornum').val();
        var transdate = $('#v2_transdate').val();
        var payor = $('#v2_payor').val();
        var paymenttype = $('#v2_type option:selected').text();
        var amountdue = $('#v2_due').val();
        var olid = $('#studname-header').attr('ol-id');
        var acadprogid = $('#v2_acadprog').val();

        var due = $('#v2_due').val().replace(',', '');
        var tendered = $('#v2_tendered').val().replace(',', '');

        var checkno = $('#pi_checkno').val();
        var checkdate = $('#pi_checkdate').val();
        var bankname = $('#pi_bank').val();
        var accountno = $('#pi_accno').val();
        var accountname = $('#pi_accname').val();
        var remittance = $('#pi_remittance').val();
            
        if(parseFloat(tendered) >= parseFloat(due))
        {
            if('{{DB::table('schoolinfo')->first()->snr}}' == 'dcc')
            {
                $.ajax({
                    url: '{{route('v2_performpayDCC')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        transno:transno,
                        studid:studid,
                        ornum:ornum,
                        transdate:transdate,
                        payor:payor,
                        paymenttype:paymenttype,
                        amountdue:amountdue,
                        terminalno:terminalnumber,
                        olid:olid
                    },
                    beforeSend:function(){
                        $('#modal-overlay').modal('show');
                        $('#v2_performpay').prop('disabled', true);
                    },
                    success:function(data)
                    {
                        v2_viewtrans(data.transid);
                        $('#printtrans').attr('data-id', data.transid);

                        setTimeout(function(){
                            $('#modal-overlay').modal('hide');    
                        }, 500);
                    }
                });
            }
            else
            {
                $.ajax({
                    url: '{{route('v2_performpay')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        transno:transno,
                        studid:studid,
                        ornum:ornum,
                        transdate:transdate,
                        payor:payor,
                        paymenttype:paymenttype,
                        amountdue:amountdue,
                        terminalno:terminalnumber,
                        olid:olid,
                        distflag:_distflag,
                        acadprogid:acadprogid,
                        checkno:checkno,
                        checkdate:checkdate,
                        bankname:bankname,
                        accountno:accountno,
                        accountname:accountname,
                        remittance:remittance
                    },
                    beforeSend:function(){
                        $('#modal-overlay').modal('show');
                        $('#v2_performpay').prop('disabled', true);
                    },
                    success:function(data)
                    {
                        v2_viewtrans(data.transid);
                        $('#printtrans').attr('data-id', data.transid);

                        setTimeout(function(){
                            $('#modal-overlay').modal('hide');    
                        }, 500);
                    }
                });
            }
        }
        else
        {
            $('#v2_tendered').focus();

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
                title: 'Amount tendered is less than amount due.'
            });
        }
    }); 

    $(document).on('click', '#olpayments', function(){

        
    });

    $(document).on('click', '#printtrans', function(){
        var amounttendered = $('#v2_tendered').val()
        if($(this).attr('data-view', 1))
        {
            window.open('printor/' + $(this).attr('data-id')+'--'+$('#head-ornum').attr('data-amount').replace(',', '')+'?amounttendered='+amounttendered);            
        }
        else
        {
            window.open('printor/' + $(this).attr('data-id')+'--'+$('#v2_due').val().replace(',', '')+'?amounttendered='+amounttendered);
            $('#cancel-trans').trigger('click');    
        }

        
    });

    $(document).on('click', '.btn-remove', function(){
        var dataid = $(this).attr('data-id');
        var transno = $('#studname-header').attr('trans-id');
        
        var particulars = $(this).attr('data-particulars');

        console.log(particulars);

        $.ajax({
            url: '{{route('v2_removeorderline')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                dataid:dataid,
                transno:transno
            },
            success:function(data)
            {
                $('#orderlist').html(data.line);
                $('#orderlisttotal').text(data.total);

                if($('#studname-header').attr('data-status') != 0)
                {
                    $('.payment-list tr[data-id="'+data.schedid+'"][data-kind="'+data.kind+'"]').removeClass('bg-primary');

                }
                else
                {
                    if(data.kind != 'tui')
                    {
                        $('.payment-list tr[data-id="'+data.schedid+'"][data-kind="'+data.kind+'"]').removeClass('bg-primary');   
                    }
                    else
                    {
                        $('.payment-list tr[data-kind="'+data.kind+'"][data-particulars="'+particulars+'"]').removeClass('bg-primary');
                    }
                }
            }
        });
    });

    $(document).on('focus', '.line-val', function(){
        $(this).select();
    })

    $(document).on('change', '.line-val', function(e){
        var dataid = $(this).attr('data-id');
        var amount = $(this).val().replace(',', '');
        var transno = $('#studname-header').attr('trans-id');
        var kind = $(this).closest('.v2_selitems').attr('data-kind');
        var oldamount = 0;
        var oldamoount_text = '';
        var oldflag = 1;
        var old_message = '';
        var payschedid = $(this).closest('.v2_selitems').attr('paysched-id');
        var particulars = $(this).closest('.v2_selitems').find('.sel_particulars').text();

        console.log('payschedid: ' + payschedid);
        console.log('particulars: ' + particulars);
        console.log('kind: ' + kind);
        console.log('amount: ' + amount);

        if(e.which == 21)
        {
            // console.log('aaa');
            $(this).trigger('change');
        }

        if(kind == 'old')
        {
            oldamount = $('#oldlist tr[data-id="'+dataid+'"]').find('.payval').attr('data-value');
            oldamoount_text = $('#oldlist tr[data-id="'+dataid+'"]').find('.payval').text();

            console.log('oldamount: ' + oldamount);

            if(parseFloat(amount) > parseFloat(oldamount))
            {
                oldflag = 0;
                old_message = 'You cannot pay greater than the value.';
                $(this).val(oldamoount_text);
                $(this).trigger('change');
            }
            else
            {
                oldflag = 1;
            }
        }
        else
        {
            oldamount = $('.payment-list tr[data-id="'+payschedid+'"][data-kind="'+kind+'"][data-particulars="'+particulars+'"]').find('.payval').attr('data-value');
            console.log('oldamount: ' + oldamount);
            // oldamount = $('.payment-list tr').find('.payval').attr('data-value');
            oldamoount_text = $('.payment-list tr[data-id="'+payschedid+'"]').find('.payval').text();

            console.log(amount + ' > ' + oldamount)
            if(parseFloat(amount) > parseFloat(oldamount))
            {
                console.log('oldamount: ' + oldamoount_text);
                oldflag = 0;
                old_message = 'You cannot pay greater than the value.';
                $(this).val(oldamount);
                $(this).trigger('change');
            }
            else
            {
                oldflag = 1;
            }
        }

        
        if(oldflag == 1)
        {
            $.ajax({
                async:true,
                url: '{{route('v2_updatelineamount')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    dataid:dataid,
                    amount:amount,
                    transno:transno
                },
                success:function(data)
                {
                    $('#orderlist').html(data.line);
                    $('#orderlisttotal').text(data.total);
                    $('#v2_due').val(data.total);

                    $('.payment-list tr[data-id="'+data.schedid+'"][data-kind="'+data.kind+'"]').removeClass('bg-primary');
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
                title: old_message
            });
        }
    });

    $(document).on('keydown', '.line-val', function(e)
    {
        if(e.which==116)
        {
            if($('#studname-header').attr('ol-id') > 0)
            {
                $(this).trigger('change');
            }
        }
    })

    $(document).on('keyup', '#v2_tendered', function(e){
        // console.log(e.which);

        if(e.which == 13)
        {
            var due = $('#v2_due').val().replace(',', '');
            var tendered = $('#v2_tendered').val().replace(',', '');

            $('#v2_performpay').focus();
            
            if(parseFloat(tendered) < parseFloat(due))
            {
                $('#v2_tendered').focus();

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
                    title: 'Amount tendered is less than amount due.'
                });
            }
        }
    });

    $(document).on('focus', '#v2_tendered', function(){
        $(this).select();
    })

    $('#modal-viewtrans').on('hidden.bs.modal', function(){
        if($(this).attr('view-id') == 0)
        {
            window.location.reload();
        }

        $(this).attr('view-id', 0);
    });

    $(document).on('click', '#btn_items', function(){
        v2_loaditems();
        $('#divitems').show();
        $('#divpaysched').hide();
        $('#divmenu').hide();
    });

    $(document).on('click', '#back-items', function(){
       $('#divitems').hide();
        $('#divpaysched').show(); 
    });

    $(document).on('keydown', '#filter', function(){
        v2_loaditems();
    });

    $(document).on('click', '#v2_itemlist tr', function(){
        var payschedid = $(this).attr('data-id');
        var datavalue = $(this).find('.payval').text();
        var levelid = $('#studname-header').attr('level-id');
        var studid = $('#studname-header').attr('stud-id');
        var transno = $('#studname-header').attr('trans-id');
        var particulars = $(this).find('.paydesc').text();
        var terminalno = terminalnumber;
        var kind = 'item';
        var itemized = 0;
        var classid = $(this).attr('class-id');

        if(transno == '')
        {
            transno = {{App\CashierModel::getTransNo()}}
            $('#studname-header').attr('trans-id', transno);
        }

        $.ajax({
            url:"{{route('v2_pushtotrans')}}",
            method:'GET',
            data:{
                payschedid:payschedid,
                studid:studid,
                levelid:levelid,
                transno:transno,
                particulars:particulars,
                terminalno:terminalno,
                kind:kind,
                itemized:itemized,
                amount:datavalue,
                classid:classid
            },
            dataType:'json',
            success:function(data)
            {
                $('#orderlist').html(data.line);
                $('#orderlisttotal').text(data.total);
            }
        });

        $('#divcart').show();
    });

    $(document).on('click', '#btn_ledger', function(){
        var studid = $('#studname-header').attr('stud-id');
        var info = $('.ledgerinfo').val();

        if(studid != '')
        {
            v2_ledger(studid, info);
        }
        else
        {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              type: 'warning',
              title: 'Please select a student'
            })
        }
    });

    $(document).on('click', '#btn_assessment', function(){
        studid = $('#studname-header').attr('stud-id');
        month = '{{date_format(App\CashierModel::getServerDateTime(), 'Y-m-d')}}';
        showall = true;

        v2_assessment(studid, month, showall);
    });

    $(document).on('change', '.assessement-info', function(){
        studid = $('#studname-header').attr('stud-id');
        month = "JANFEBMARAPRMAYJUNJULAUGSEPOCTNOVDEC".indexOf($('#assessment_month').val()) / 3 + 1;
        showall = '';
        year = {{date_format(App\CashierModel::getServerDateTime(), 'Y')}}
        day = {{date_format(App\CashierModel::getServerDateTime(), 'd')}}

        month = year + '-' + month + '-' + day;

        if($('#showall').prop('checked') == true)
        {
            showall = true;
        }
        else
        {
            showall = false;
        }

        // console.log(month);

        v2_assessment(studid, month, showall);
    });

    $(document).on('click', '#menu_studledger', function(){
        // v2_ledger($('#studname-header').attr('stud-id'));
        var studid = $('#studname-header').attr('stud-id');
        var info = $('.ledgerinfo').val();


        v2_ledger(studid, info);
    });

    $(document).on('click', '#menu_assessment', function(){
        studid = $('#studname-header').attr('stud-id');
        month = '{{date_format(App\CashierModel::getServerDateTime(), 'Y-m-d')}}';
        showall = true;

        v2_assessment(studid, month, showall);
    });

    $(document).on('click', '#menu_items', function(){
        var filter = $('#item_filter').val();
        v2_items(filter)
    })

    $(document).on('click', '#back-menu', function(){
        $('#divmenu').hide();
        $('#divpaysched').show();
    });

    $(document).on('click', '#btn_menu', function(){
        $('#divpaysched').hide();
        $('#divitems').hide();
        $('#divmenu').show();
    });

    $(document).on('click', '#menu_onlinepayments', function(){
        $('#olpayments').trigger('click');
    })

    $(document).on('click', '#menu_transaction', function(){
        datenow = '{{date_format(App\CashierModel::getServerDateTime(), 'Y-m-d')}}';

        $('.v2_transdate-range').val(datenow);

        v2_transactions(terminalnumber);
        $('#modal-v2transactions').modal('show');
    });

    $(document).on('click', '#v2_btntranssearch', function(){
        v2_transactions($('#v2_transactionterminalno').val());
    });

    $(document).on('keyup', '#v2_transsearch', function(){
       v2_transactions($('#v2_transactionterminalno').val()); 
    });

    $(document).on('click', '.btn-view', function(){
        $('#modal-viewtrans').attr('view-id', 1);
        $('#pritntrans').attr('data-view', 1);
        v2_viewtrans($(this).attr('data-id'));
    });

    $(document).on('click', '#v2_viewtrans-close', function(){
        $('#modal-viewtrans').modal('hide');
    });

    $(document).on('click', '.btn-void', function(){
        $('#voiduname').val('');
        $('#voidpword').val('');
        $('#voidremarks').val('');
        $('#voidheader').text('Confirm Void - ' + $(this).attr('data-or'));
        $('#btnconfirm').attr('data-id', $(this).attr('data-id'));
    });

    $(document).on('click', '#btnconfirm', function(){
        var transid = $(this).attr('data-id');
        var uname = $('#voiduname').val();
        var pword = $('#voidpword').val();
        var remarks = $('#voidremarks').val();
        // console.log(uname)

        $.ajax({
            url:"{{route('v2_voidtransactions')}}",
            method:'GET',
            data:{
                transid:transid,
                uname:uname,
                pword:pword,
                remarks:remarks
            },
            dataType:'json',
            success:function(data)
            {
                console.log(data.feesid);
                if(0 > 1)
                {
                    $('#v2_btntranssearch').trigger('click');
                    $('#modal-voidpermission').modal('hide');
                    updateledger(data.studid, data.syid, data.semid, data.feesid, data.esURL);

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

                    if(data.return == 1)
                    {
                        Toast.fire({
                          type: 'success',
                          title: 'Transaction successfully voided.'
                        }); 
                               
                        $('#v2_btntranssearch').trigger('click');
                        $('#modal-voidpermission').modal('hide');
                        updateledger(data.studid, data.syid, data.semid, data.feesid, data.esURL);
                    }
                    else if(data.return == 2)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: 'Please fill the remarks to void the transaction.'
                        });

                        $('#voidremarks').focus();
                    }
                    else if(data.return == 5)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: 'User has no permission to void transactions'
                        });
                    }
                    else if(data.return == 0)
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

    $(document).on('click', '#menu_cashsummary', function(){
        $('#modal-v2CRS').modal('show');
    });

    $(document).on('click', '#studname-header', function(){
        getpayinfo($(this).attr('stud-id'), 0);
    });

    $(document).on('click', '#btn_enteramount', function(){
        if(terminalnumber > 0)
		{
			if($('#studname-header').attr('stud-id') > 0)
			{
				$('#v2_enteramount').val('');

				$('#modal-enteramount').modal();
				setTimeout(function(){
					$('#v2_enteramount').select();
				}, 300);
			}
            else
            {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'warning',
                  title: 'Please select a student'
                })
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
				didOpen: (toast) => {
					toast.addEventListener('mouseenter', Swal.stopTimer)
					toast.addEventListener('mouseleave', Swal.resumeTimer)
				}
			})

			Toast.fire({
			type: 'error',
			title: 'No Terminal is set.'
			})
		}

        $('#v2_btnenteramount').prop('disabled', false);
    });

    $(document).on('keyup', '#v2_enteramount', function(e){
        if(e.which == 13)
        {
            $('#v3_btnenteramount').trigger('click');
        }
    });


    $(document).on('click', '#v2_btnenteramount', function(){
        var enteramount = $('#v2_enteramount').val().replace(',', '');
        v2_enteramount = $('#v2_enteramount').val().replace(',', '');
        _performEnteramount = 1;

        $(this).prop('disabled', true);

        ecount = 0;
        
        function eamount(kind)
        {
            if(enteramount > 0)
            {
                $(kind).each(function(){
                    trID = $(this).attr('data-id');
                    console.log(kind);

                    tdamount = $(this).find('.payval').attr('data-value');

                    if(enteramount > 0)
                    {
                        if(tdamount > 0)
                        {
                            
                            if(parseFloat(enteramount) >= parseFloat(tdamount))
                            {
                                // console.log('tui-: ' + enteramount + ' > ' + tdamount);
                                $(this).trigger('click');
                                enteramount -= tdamount;
                            }
                            else
                            {
                                totalafterclick = tdamount - enteramount;
                                $(this).trigger('click');
                                enteramount = 0;
                            }
                        }
                    }
                    console.log(enteramount + ' >= ' + tdamount + ' | ' + trID);
                });
            }    
        }

        //REG
        // eamount('#reglist tr');

        //MISC
        eamount('#misclist tr');

        //TUI
        eamount('#tuilist tr');

        //OTH
        eamount('#othlist tr');        

        //OLD
        eamount('#oldlist tr');



        
        
        // setTimeout(function(){
        //     // console.log('v2_enteramount: ' + v2_enteramount);

        //     $('.v2_selitems').each(function(){
                
        //         curlineval = $(this).find('.line-val').val().replace(',', '');

        //         if(parseFloat(v2_enteramount) >= parseFloat(curlineval))
        //         {
        //             // console.log('curlineval: ' + curlineval);
        //             v2_enteramount -= curlineval;
        //             // console.log('enteramount: ' + v2_enteramount);
        //         }
        //         else
        //         {
        //             // console.log('enteramount: ' + v2_enteramount);
        //             $(this).find('.line-val').val(v2_enteramount);
        //             $(this).find('.line-val').trigger('change');
        //             v2_enteramount = 0;
        //         }
        //     });

            // $('#modal-enteramount').modal('hide');

        // }, 3000);

        

    });

    _performEnteramount = 0;
    v2_enteramount = 0;

    function fixamount(due)
    {
        finaldue = $('#orderlisttotal').text().replace(',', '');
        due = due.replace(',', '');
        // console.log('due: ' + due + ' >= ' + v2_enteramount);
        console.log(finaldue + ' != ' + v2_enteramount);
		v2_enteramount = $('#v2_enteramount').val().replace(',', '');
        if(finaldue != v2_enteramount && v2_enteramount > 0)
        {
            if(parseFloat(due) >= parseFloat(v2_enteramount))
            {   
                
                
                
                    $('.v2_selitems').each(function(){
                        
                        curlineval = $(this).find('.line-val').val().replace(',', '');
                        console.log(v2_enteramount + ' >= ' + curlineval);
                        if(parseFloat(v2_enteramount) >= parseFloat(curlineval))
                        {
                          
                            v2_enteramount -= curlineval;
                              console.log('curlineval: ' + curlineval);
                            
                        }
                        else
                        {
                            console.log('enteramountelse: ' + v2_enteramount);
                            $(this).find('.line-val').val(v2_enteramount);
                            $(this).find('.line-val').trigger('change');

                            v2_enteramount = 0;
                        }

                        
                    });
                

                $('#modal-enteramount').modal('hide');
            }
        }

        // _performEnteramount = 0;
        if(_onlinepay == 1)
        {
            console.log(_onlinepay);
            paymentlist_lock = 1;
        }
        
    }

    $(document).on('click', '.u-info', function(){
        $('#modal-uinfo').modal('show');
    });

    $(document).on('click', '#setTerminalno', function(){
	var terminalno = $('#ci_terminalno').val();
		$.ajax({
			url:"{{route('v2_setterminalno')}}",
			method:'GET',
			data:{
				terminalno:terminalno
			},
			dataType:'json',
			success:function(data)
			{
				if(data == 1)
				{
					$('#setTerminalno').removeClass('btn-primary');
					$('#setTerminalno').addClass('btn-success');
					location.reload();
				}
				else
				{
					$('#setTerminalno').removeClass('btn-primary');
					$('#setTerminalno').addClass('btn-danger');
				}
			}
		});
	});

    function genCRSv2()
    {
        var dfrom = $('#crs_transdatefrom').val();
        var dto = $('#crs_transdateto').val();

        $.ajax({
            url:"{{route('v2_genCRS')}}",
            method:'GET',
            data:{
                dfrom:dfrom,
                dto:dto,
                terminalno:terminalnumber
            },
            dataType:'json',
            success:function(data)
            {
                $('#v2_crslist').html(data.output);
            }
        });
    }

	$(document).on('click', '#v2_btngencrs', function(){
		genCRSv2();
	});

    $(document).on('click', '#print_v2genCRS', function(){
        var dfrom = $('#crs_transdatefrom').val();
        var dto = $('#crs_transdateto').val();

        window.open('/cashreceiptsummary/genCRSexport?dfrom='+dfrom+'&dto='+dto+'&terminalno='+terminalnumber+'', '_blank');
        
    });

    $(document).on('click', '#btnlogout', function(){
        window.location.replace('/logout');
    });

    function checkusedor(ornum)
    {
        $.ajax({
            url:"{{route('checkornum')}}",
            method:'GET',
            data:{
                ornum:ornum
            },
            dataType:'json',
            success:function(data)
            {
                // console.log(data);

                if(data == 1)        
                {
                    $('#v2_performpay').prop('disabled', true);
                    $('#v2_ornum').addClass('is-invalid');
                    $('#v2_reuse').show();
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                      type: 'warning',
                      title: 'OR number already been used.'
                    })
                }
                else
                {
                    $('#v2_performpay').prop('disabled', false);
                    $('#v2_ornum').removeClass('is-invalid');
                    $('#v2_reuse').hide();
                }
            }
        });
    }

    $('#v2_ornum').bind('keyup', function(){
        clearTimeout(timer);
        var str = $(this).val();
        if(str.length > 2 && value != str)
        {
            timer = setTimeout(function(){
                value = str;

                checkusedor(value);

            }, 100)
        }
    })

    $(document).on('keyup', '#searchStud', function(){
        var str = $(this).val();

        if(str.length > 2 && value != str)
        {
            timer = setTimeout(function(){
                value = str;
                getstudlist(value);
            }, 500);
        } 
    });

    $(document).on('click', '#v2ledger-list tr', function(){
        var transid = $(this).attr('trans-id');

        if($(this).attr('trans-id') > 0)
        {
            $('#modal-viewtrans').attr('view-id', 1);
            v2_viewtrans(transid);
        }
    });

    $(document).on('click', '#v2_reuse', function(){
        var ornum = $('#v2_ornum').val();

        $.ajax({
            url: '{{route('reuseornum')}}',
            type: 'GET',
            dataType: '',
            data: {
                ornum:ornum
            },
            success:function(data)
            {
                checkusedor(ornum);
            }
        })
        
    });

    $(document).on('click', '#olpayments', function(){
        var syid = $('#online_sy').val();
        var semid = $('#online_sem').val();
        var filter = $('#online_filter').val();
        $.ajax({
            url:"{{route('v2_onlinepay')}}",
            method:'GET',
            data:{
                syid:syid,
                semid:semid,
                filter:filter
            },
            dataType:'json',
            success:function(data)
            {
                $('#pay-list').html(data.list)
                $('#modal-onlinepay').modal('show');
            }
        });
    });

    $(document).on('change', '#online_sy', function(){
        $('#olpayments').trigger('click');
    });

    $(document).on('change', '#online_sem', function(){
        $('#olpayments').trigger('click');
    });

    $(document).on('change', '#online_filter', function(){
        $('#olpayments').trigger('click');
    });

    function reloadselitems(transno, schedid, particulars)
    {
        $.ajax({
            url: '{{route('reloadselitems')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                transno:transno,
                schedid:schedid,
                particulars:particulars
            },
            success:function(data)
            {
                $('#orderlist').html(data.line);
                $('#orderlisttotal').text(data.total);
            }
        });
        
    }
    

    $(document).on('click', '#pay-list tr', function(){
        var studid = $(this).attr('data-id');
        var olamount = $(this).attr('data-amount');
        var olid = $(this).attr('ol-id');
        var paytype = $(this).attr('data-paytype');
        var transno;

        getstudlist(studid);

        _olamount = olamount;

        setTimeout(function(){
            $('#studlist tr').each(function(){
                if($(this).attr('data-id') == studid)
                {
                    $(this).trigger('click');            
                    _onlinepay = 1;  
                    _onlinepayamount = olamount;
                    $('#studname-header').attr('ol-id', olid);
                    $('#studname-header').attr('data-paytype', paytype);
                    $('#modal-onlinepay').modal('hide');
                }
            });

            // setTimeout(function(){
            //     transno = $('#studname-header').attr('trans-id');

            //     $.ajax({
            //         url:"{route('v2_loadpaysched')}}",
            //         method:'GET',
            //         data:{
            //             studid:studid,
            //             olid:olid,
            //             transno:transno,
            //             terminalno:terminalnumber
            //         },
            //         dataType:'json',
            //         success:function(data)
            //         {
            //             paymentlist_lock = 1;

            //             $('#orderlist').html(data.line);
            //             $('#orderlisttotal').text(data.total);
            //             $('#studname-header').attr('ol-id', olid)

            //             $('#v2_tendered').val(data.total);
            //             $('#v2_tendered').prop('disabled', true);
            //             $('#v2_type').val(data.paytype);

            //             $('#modal-onlinepay').modal('hide');

            //             setTimeout(function(){
            //                 console.log($('#v2_selitems').length)
            //                 $('.v2_selitems').each(function(){
            //                     var schedid = $(this).attr('paysched-id');

            //                     $('.payment-list tr').each(function(){
            //                         if($(this).attr('data-id') == schedid && $(this).attr('data-kind') == 'tui')
            //                         {
            //                             var particulars = $(this).attr('data-particulars');
            //                             reloadselitems(transno, schedid, particulars);            
            //                         }
            //                     });
            //                 });
            //             }, 0)
            //         }
            //     });
            // }, 1500)
        }, 1000)
    });


    $(document).on('click', '#btn_plans', function(){
        var studid = $('#studname-header').attr('stud-id');
        $.ajax({
            url:"{{route('viewPayplans')}}",
            method:'GET',
            data:{
                studid:studid
            },
            dataType:'json',
            success:function(data)
            {
                $('#loadfeelist').html(data.feelist);
                $('#modal-v2_paymentplan').modal();
                $('#btnreloadproceed').attr('data-id', 0);
            }
        });        

    });

    $(document).on('click', '.col-fees', function(){
        dataid = $(this).attr('data-id');

        $('.col-fees').each(function(){
            if($(this).attr('data-id') == dataid)
            {
                $(this).find('.card-header').removeClass('bg-info');
                $(this).find('.card-header').addClass('bg-success');            
                $(this).find('.card-body').addClass('bg-light');
            }
            else
            {
                $(this).find('.card-header').removeClass('bg-success');
                $(this).find('.card-header').addClass('bg-info');
                $(this).find('.card-body').removeClass('bg-light');
            }
            
        });

        $('#btnreloadproceed').attr('data-id', dataid);
    });

    $(document).on('click', '#btnreloadproceed', function(){
        var studid = $('#studname-header').attr('stud-id');
        var feesid = $(this).attr('data-id');

        Swal.fire({
            title: 'Select Payment Plan?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Proceed'
        }).then((result) => {
            if (result.value){
                $.ajax({
                    url:"{{route('changepayplans')}}",
                    method:'GET',
                    data:{
                        studid:studid,
                        feesid:feesid
                    },
                    dataType:'',
                    success:function(data)
                    {
                        $('#modal-v2_paymentplan').modal('hide');
                    }
                });      
            }
        });
    });

    $(document).on('click', '#syinfo', function(){
        var studid = $('#studname-header').attr('stud-id');

        $.ajax({
            url: '{{route('syinfo')}}',
            type: 'GET',
            dataType:'json',
            data: {
                studid:studid
            },
            success:function(data)
            {
                var levelid = $('#studname-header').attr('level-id');

                $('#li_list').html(data.list);

                $('#li_list tr').each(function(){
                    if(levelid >= 17 || levelid <= 21)
                    {
                        if($(this).attr('data-sy') == data.activesy && $(this).attr('data-sem') == data.activesem)
                        {
                            $(this).closest('td').addClass('text-bold');
                        }
                    }
                    else
                    {
                        if($(this).attr('data-sy') == data.activesy)
                        {
                            console.log('active')
                            $(this).closest('td').addClass('text-bold');
                        }   
                    }
                })

                $('#modal-changeChrngInfo').modal();
            }
        });
        

        
    });

    $(document).on('click', '#info_btnproceed', function(){
        var syid = $('#info_sy').val();
        var semid = $('#info_sem').val();

        $.ajax({
            url: '{{route('info_changesysem')}}',
            type: 'GET',
            dataType: '',
            data: {
                syid:syid,
                semid:semid
            },
            success:function(data)
            {
                // location.reload();

                if($('#studname-header').attr('stud-id') == '')
                {
                    location.reload();
                }
                else
                {
                    $('#studname-header').trigger('click');
                    ActiveInfo();
                    $('#modal-changeChrngInfo').modal('hide');
                }
            }
        });
        
    });

    $(document).on('click', '#menu_bookentry', function(){
        $('#modal-bookentry-list').modal('show');
        be_loadstudents();
        loadbookentries();
    });

    function be_clear()
    {
        studid = $('#studname-header').attr('stud-id');
        if(studid > 0)
        {
            $('#be_studlist').val(studid);    
        }
        else
        {
            $('#be_studlist').val('');    
        }

        
        $('#be_studlist').trigger('change');
        $('#be_amount').val('');
        $('#be_proceed').prop('disabled', false);
        $('#be_booklist').val('').change();

        
        
    }

    function be_loadstudents()
    {
        $.ajax({
            url: '{{route('be_loadstud')}}',
            type: 'GET',
            dataType: '',
            success:function(data)
            {
                $("#be_studlist").html(data);
                        
            }
        });         
    }

    $(document).on('click', '#be_create', function(){

        $('#be_proceed').attr('data-action', 'create');
        be_btns(0);
        be_clear();
        $('#modal-bookentry').modal('show');
    });

    function loadbookentries()
    {
        
        var dtrange = $('#dtrange').val();

        var studid = $('#studname-header').attr('stud-id');

        if(studid > 0)
        {
            var filter = $('#studname-header').attr('data-sid');
        }
        else
        {
            var filter = $('#be_studsearch').val();    
        }

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
                $('#be_studsearch').val($('#studname-header').attr('data-sid'));
            }
        });
    }

    function be_btns(show)
    {
        if(show == 1)
        {
            $('.btn-action').show();
        }
        else
        {
            $('.btn-action').hide();
        }
    }

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
                  // $('#be_proceed').prop('disabled', true);
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

    $(document).on('click', '#be_proceed', function(){

        var studid = $('#be_studlist').val();
        var amount = $('#be_amount').val();
        var bookid = $('#be_booklist').val();
        var action = $('#be_proceed').attr('data-action');

        if($('#be_proceed').attr('data-action') == 'create')
        {
            $.ajax({
                url:"/beappend",
                method:'GET',
                data:{
                    studid:studid,
                    amount:amount,
                    bookid:bookid,
                    action:action
                },
                dataType:'',
                success:function(data)
                {
                    var be_id = data;
                    $('#be_proceed').attr('data-id', be_id);


                    loadbookentries();
                    be_btns(1);
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
                    updledgerv3(studid);
                    $('#modal-bookentry').modal('hide');
                }
            }); 
        }
    });

    $(document).on('click', '#be_approve', function(){
        var dataid = $('#be_proceed').attr('data-id');
        $(this).prop('disabled', true);
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
                $('#be_approve').prop('disabled', false);
            }
        })
    });

    $(document).on('click', '#print_v2ledger', function(){
        var studid = $('#studname-header').attr('stud-id');
        var info = $('.ledgerinfo').val();

        window.open('/v2_printledger?studid='+studid+'&info='+info,'_blank');
    });

    $(document).on('click', '#updledger', function(){


        var studid = $('#studname-header').attr('stud-id');
        var syid = '{{App\CashierModel::getSYID()}}'
        var semid = '{{App\CashierModel::getsemID()}}'
        var feesid = $('#studname-header').attr('fees-id');
        var esURL = "{{db::table('schoolinfo')->first()->essentiellink}}"

        console.log(esURL);

        Swal.fire({
          title: 'Update student ledger?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Update Ledger'
        }).then((result) => {
          if (result.value == true) {
            $.ajax({
                url: esURL + 'api_updateledger',
                type: 'GET',
                dataType: '',
                data: {
                    studid:studid,
                    syid:syid,
                    semid:semid,
                    feesid:feesid
                },
                success:function(data)
                {
                    console.log('data');

                    if(data == 'done')
                    {
                        $('#btn_ledger').trigger('click');
                        $('#studname-header').trigger('click');

                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'success',
                          title: 'Student ledger is updated.'
                        })
                    }
                },
                complete:function(data)
                {
                    console.log('data');
                }
            });
            
          }
        })
    })

    function updledgerv3(studid)
    {
        var syid = '{{App\CashierModel::getSYID()}}'
        var semid = '{{App\CashierModel::getsemID()}}'
        var feesid = 0;

        $.ajax({
            url: 'http://es_holycross.ck/api_updateledger',
            type: 'GET',
            data: {
                studid:studid,
                syid:syid,
                semid:semid,
                feesid:feesid
            },
            success:function(data)
            {

            }
        });
        
    }

    $(document).on('click', '#payhistory', function(){
        var studid = $('#studname-header').attr('stud-id');
        var action = 'load';
        $.ajax({
            url: '{{route('payhistory')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                studid:studid,
                action:action
            },
            success:function(data)
            {
                $('#payhistory_list').html(data.list);
                $('#modal-payhistory').modal('show');
            }
        });
        
    });

    $(document).on('click', '#payhistory_print', function(){
        var studid = $('#studname-header').attr('stud-id');
        var action = 'print';

        window.open('/payhistory?studid='+studid+'&action=print', '_blank');
    });

    $(document).on('click', '#soa_generate', function(){
        soa_filter();
    });

    function soa_filter()
    {
        var levelid = $('#soa_levelid').val();
        var filter = $('#soa_filter').val();

        $.ajax({
            url: '{{route('soa_generate')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                levelid:levelid,
                filter:filter
            },
            success:function(data)
            {
                $('#soa_list').html(data.list);
            }
        });
        
    }

    $(document).on('click', '#soa_list tr', function(){
        var dataid = $(this).attr('data-id');

        window.open('/soa_print?studid='+dataid, '_blank');
    });

    $(document).on('click', '#menu_soa', function(){
        $('#modal-soa').modal('show');
    });

    $(document).on('click', '#menu_balforward', function(){
        old_generate();
        $('#modal-oldaccount').modal('show');
    })

    $(document).on('click', '#old_add', function(){
        old_add_clearinputs();

        $.ajax({
            url: '{{route('old_loadstud')}}',
            type: 'default GET (Other values: POST)',
            dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
            data: {param1: 'value1'},
        })
        .done(function() {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        


        $('#modal-old_add').modal('show');
    });

    function old_add_clearinputs()
    {
        $('#old_add_studlist').val(0);
        $('#old_add_studlist').trigger('change');
        $('#old_add_sy').val(0);
        $('#old_add_sy').trigger('change');
        $('#old_add_sem').val(0);
        $('#old_add_sem').trigger('change');
        $('#old_add_amount').val('');
        $('#old_add_amount').removeClass('is-valid');
        $('#old_add_amount').addClass('is-invalid');
    }

    function old_generate()
    {
        var syid = $('#old_sy').val();
        var semid = $('#old_sem').val();
        var levelid = $('#old_gradelevel').val();

        $.ajax({
            url: '{{route('old_load')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                syid:syid,
                semid:semid,
                levelid:levelid
            },
            success:function(data)
            {
                $('#old_list').html(data.list);
            }
        });
        
    }  

    $(document).on('click', '#old_generate', function(){
        old_generate();
    });

    $(document).on('change', '#old_add_studlist', function(){
        var studid = $(this).val();

        $.ajax({
            url: '{{route('old_add_studlist')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                studid:studid
            },
            success:function(data)
            {
                if(studid > 0)
                {
                    $('#old_add_levelname').text(data.levelname);
                    $('#old_add_section').text(data.section);
                    $('#old_add_grantee').text(data.grantee);
                    $('#old_add_sy').html(data.sylist);
                    $('#old_add_sem').html(data.semlist);

                    console.log(data.levelid);

                    if(data.levelid >= 17 && data.levelid <= 21)
                    {
                        $('.old_add_granteelabel').hide();
                    }
                    else
                    {
                        $('.old_add_granteelabel').show();
                    }
                }
                else
                {
                    $('#old_add_studlist').html(data.studlist);
                }
            }
        });
        
    });

    var valcount = 0;
    $(document).on('change', '.old_req', function(){
        if($(this).val() > 0)
        {
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
        }
        else
        {
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid');   
        }

        checkreq();
    });

    $(document).on('keyup', '#old_add_amount', function(){
        if($(this).val() != '')
        {
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
        }
        else
        {
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid');   
        }

        checkreq();
    });

    function checkreq()
    {
        thiscount = 0;

        $('.old_req').each(function(){
            if($(this).hasClass('is-invalid'))
            {
                thiscount = 0;
            }
            else
            {
                thiscount += 1;
            }
        }); 

        if(thiscount == 3 && $('#old_add_amount').hasClass('is-valid'))
        {
            $('#old_post').attr('disabled', false);
        }
        else
        {
            $('#old_post').attr('disabled', true);
        }
    }

    $(document).on('click', '#old_post', function(){
        var studid = $('#old_add_studlist').val();
        var syfrom = $('#old_add_sy').val();
        var semfrom = $('#old_add_sem').val();
        var amount = $('#old_add_amount').val();

        $.ajax({
            url: '{{route('old_post')}}',
            type: 'GET',
            dataType: '',
            data: {
                studid:studid,
                syfrom:syfrom,
                semfrom:semfrom,
                amount:amount
            },
            success:function(data)
            {
                if(data == 'done')
                {
                    $('#modal-old_add').modal('hide');
                    old_generate()
                }
                else
                {
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                      }
                    })

                    Toast.fire({
                      type: 'error',
                      title: 'Old accounts already forwarded'
                    })
                }
            }
        });

    });

    $(document).on('click', '#othmlist tr', function(){
        var datavalue = $(this).find('.payval').attr('data-value');


        if(!$(this).hasClass('bg-primary') && datavalue > 0)
        {
            $(this).addClass('bg-primary');
        }
        else
        {
            $(this).removeClass('bg-primary');
        }
    });

    $(document).on('click', '#othmlist_proceed', function(){
        $('#othmlist tr').each(function(){
            if($(this).hasClass('bg-primary'))
            {
                var payschedid = $(this).attr('data-id');
                var datavalue = $(this).find('.payval').attr('data-value');
                var levelid = $('#studname-header').attr('level-id');
                var studid = $('#studname-header').attr('stud-id');
                var transno = $('#studname-header').attr('trans-id');
                var particulars = $(this).find('.paydesc').text();
                var terminalno = terminalnumber;
                var kind = $(this).attr('data-kind');
                var itemized = $(this).attr('itemized-id');
                var classid = $(this).attr('data-classid');
                var click_source = $(this).attr('data-source');
                var datadue = $(this).attr('data-due');

                $.ajax({
                    url:"{{route('v2_pushtotrans')}}",
                    method:'GET',
                    data:{
                        payschedid:payschedid,
                        studid:studid,
                        levelid:levelid,
                        transno:transno,
                        particulars:particulars,
                        terminalno:terminalno,
                        kind:kind,
                        itemized:itemized,
                        classid:classid,
                        source:click_source,
                        datavalue:datavalue,
                        datadue:datadue
                    },
                    dataType:'json',
                    success:function(data)
                    {
                        $('#orderlist').html(data.line);
                        $('#orderlisttotal').text(data.total);
                        
                        if(_performEnteramount == 1)
                        {
                            // console.log('perform: ' + _performEnteramount);
                            
                            setTimeout(function(){
                                fixamount(data.total);
                            }, 300)
   
                        }

                        $('#modal-othmlist').modal('hide');
                    }
                });

            }
        })
    });

    function setfeesid(studid, feesid)
    {
        $.ajax({
            url: '{{route('addfeesid')}}',
            type: 'GET',
            data: {
                studid:studid,
                feesid:feesid
            }
        });
        
    }

    $(document).on('click', '#fees_close', function(){
        // $('#cancel-trans').trigger('click');
        $('#modal-fees').modal('hide');
    });

    $(document).on('click', '#fees_list tr', function(){
        var studid = $("#studname-header").attr('stud-id');
        var feesid = $(this).attr('data-id');
        setfeesid(studid, feesid);

        setTimeout(function(){
            getpayinfo(studid, 0, feesid);
        }, 500)
            


        $('#modal-fees').modal('hide');
    });

    $(document).on('shown.bs.modal', '#modal-fees', function(){
        $('#modal-overlay').modal('hide');
    })

    function updateledger(studid, syid, semid, feesid, esURL)
    {
        $.ajax({
            url: esURL + 'api_updateledger',
            type: 'GET',
            dataType: '',
            data: {
                studid:studid,
                syid:syid,
                semid:semid,
                feesid:feesid
            },
            success:function(data)
            {
                console.log(data);

                if(data == 'done')
                {
                    // $('#btn_ledger').trigger('click');
                    // $('#studname-header').trigger('click');

                    // const Toast = Swal.mixin({
                    //   toast: true,
                    //   position: 'top-end',
                    //   showConfirmButton: false,
                    //   timer: 3000,
                    //   timerProgressBar: true,
                    //   didOpen: (toast) => {
                    //     toast.addEventListener('mouseenter', Swal.stopTimer)
                    //     toast.addEventListener('mouseleave', Swal.resumeTimer)
                    //   }
                    // })

                    // Toast.fire({
                    //   type: 'success',
                    //   title: 'Student ledger is updated.'
                    // })
                }
            }
        });
    }

    $(document).on('click', '#trans_collection', function(){
        var terminalno = $('#v2_transactionterminalno').val();
        var datefrom = $('#v2_transdatefrom').val();
        var dateto = $('#v2_transdateto').val();

        $.ajax({
            url: '{{route('collection')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                terminalno:terminalno,
                datefrom:datefrom,
                dateto:dateto,
            },
            success:function(data)
            {
                if(data.deno != null)
                {
                    $('#denomination_1000').val(data.deno['denomination_1000']);
                    $('#denomination_500').val(data.deno['denomination_500']);
                    $('#denomination_200').val(data.deno['denomination_200']);
                    $('#denomination_100').val(data.deno['denomination_100']);
                    $('#denomination_50').val(data.deno['denomination_50']);
                    $('#denomination_20').val(data.deno['denomination_20']);
                    $('#denomination_10').val(data.deno['denomination_10']);
                    $('#denomination_5').val(data.deno['denomination_5']);
                    $('#denomination_1').val(data.deno['denomination_1']);
                    $('#denomination_050').val(data.deno['denomination_050']);
                    $('#denomination_025').val(data.deno['denomination_025']);
                    $('#denomination_010').val(data.deno['denomination_010']);
                }

                $('#denomination_010').trigger('keyup');

                $('#collection_list').html(data.list);
                $('#collection_print').attr('data-date', datefrom);
                $('#collection_print').attr('data-terminal', terminalno);
                $('#check_list').html(data.check);
                $('#check_amount').text(data.checktotal);
                $('#online_list').html(data.online);
                $('#online_amount').text(data.onlinetotal);
                $('#modal-denomination').modal('show');

            }
        });
    });

    $(document).on('click', '#collection_close', function(){
       $('#modal-denomination').modal('hide'); 
    });

    function denomination()
    {
        var total = 0;

        total += parseFloat($('#denomination_1000').val()) * 1000;
        total += parseFloat($('#denomination_500').val()) * 500;
        total += parseFloat($('#denomination_200').val()) * 200;
        total += parseFloat($('#denomination_100').val()) * 100;
        total += parseFloat($('#denomination_50').val()) * 50;
        total += parseFloat($('#denomination_20').val()) * 20;
        total += parseFloat($('#denomination_10').val()) * 10;
        total += parseFloat($('#denomination_5').val()) * 5;
        total += parseFloat($('#denomination_1').val()) * 1;
        total += parseFloat($('#denomination_050').val()) * .50;
        total += parseFloat($('#denomination_025').val()) * .25;
        total += parseFloat($('#denomination_010').val()) * .10;

        total = format_number(total);

        

        $('#denomination_amount').text(total);
    }

    function savedeno(id, count, transdate, terminalno)
    {
        $.ajax({
            url: '{{route('savedeno')}}',
            type: 'GET',
            data: {
                id:id,
                count:count,
                transdate:transdate,
                terminalno:terminalno
            },
            success:function(data)
            {
                console.log(id + ' - ' + count + ' - ' + transdate + ' - ' + terminalno);
            }
        });
        
    }

    $(document).on('keyup', '.deno', function(){
        denomination();
    });

    $(document).on('change', '.deno', function(){
        denomination();

        id = $(this).attr('id');
        count = $(this).val();
        transdate = $('#collection_print').attr('data-date');
        terminalno = $('#collection_print').attr('data-terminal');

        savedeno(id, count, transdate, terminalno);
    });

    $(document).on('change', '.old_req', function(){
        var studid = $('#old_add_studlist').val();
        var old_sy = $('#old_add_sy').val();
        var old_sem = $('#old_add_sem').val();

        $.ajax({
            url: '{{route('old_loadamount')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                studid:studid,
                syid:old_sy,
                semid:old_sem
            },
            success:function(data)
            {
                $('#old_info_level').text(data.info);
                $('#old_add_amount').val(data.amount).trigger('keyup');
            }
        });
        
    });

    function old_getsem()
    {
        var syid = $('#old_add_sy').val();

        $.ajax({
            url: '{{route('old_getsem')}}',
            type: 'GET',
            data: {
                syid:syid
            },
            success:function(data)
            {
                $('#old_add_sem').html(data);
            }
        });
        
    }

    $(document).on('change', '#old_add_sy', function(){
        old_getsem();
    });

    $(document).on('change', '#fees_level', function(){
        var levelid = $(this).val();

        $.ajax({
            url: '{{route('loadfees')}}',
            type: 'GET',
            dataType: 'json',
            data: {
                levelid:levelid
            },
            success:function(data)
            {
                $('#fees_list').html(data.feelist);
            }
        });
        
    });

    $(document).on('change', '#v2_type', function(){
        var paytype = $(this).val();
        var paytypetext = $(this).find(':selected').text();

        console.log(paytypetext);

        if(paytype == 2)
        {
            $('.bank').hide();
            $('.check').show();
            $('.remittance').hide();
            $('#modal-pi').modal('show');
            $('#pi_type').text(paytypetext);
            $('#v2_tendered').val($('#v2_due').val());
            $('#v2_tendered').prop('disabled', true);
        }
        else if(paytype == 3)
        {
            $('.remittance').hide();
            $('.bank').show();
            $('.check').hide();
            $('#modal-pi').modal('show');
            $('#pi_type').text(paytypetext);
            $('#v2_tendered').val($('#v2_due').val());
            $('#v2_tendered').prop('disabled', true);   
        }
        else if(paytype == 4 || paytype == 5)
        {
            $('.bank').hide();
            $('.check').hide();
            $('.remittance').show();
            $('#modal-pi').modal('show');
            $('#pi_type').text(paytypetext);
            $('#v2_tendered').val($('#v2_due').val());
            $('#v2_tendered').prop('disabled', true);   
        }

    });

    $(document).on('click', '#pi_proceed', function(){
        $('#modal-pi').modal('hide');
    });

    $(document).on('click', '#collection_print', function(){
        var terminalno = $('#v2_transactionterminalno').val();
        var datefrom = $('#v2_transdatefrom').val();
        var dateto = $('#v2_transdateto').val();
        var action = 'pdf';

        if($('#denomination_amount').text() == '0.00')
        {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                type: 'error',
                title: 'Please fill in Cash Denomination'
            })
        }
        else
        {
            window.open('/collection?terminalno=' + terminalno + '&datefrom=' + datefrom + '&dateto='+ dateto + '&action='+ action,'_blank');    
        }
    })

    $(document).on('click', '#ledger_sa', function(){
        var studid = $('#studname-header').attr('stud-id');
        var ledgerinfo = $('#ledger_info').val();

        window.open('/assessment?studid='+studid+'&ledgerinfo='+ledgerinfo,'_blank');
    });

    $(document).on('select2:close', '.ledgerinfo', function(){
        var studid = $('#studname-header').attr('stud-id');
        var info = $(this).val();
        // var syid = $('#ledger_sy').val();

        v2_ledger(studid, info);
    
    });    

    $(document).on('change', '.ledgerinfo', function(){
        var studid = $('#studname-header').attr('stud-id');
        var info = $(this).val();
        // var syid = $('#ledger_sy').val();

        v2_ledger(studid, info);
    
    });

    $(document).on('click','#li_list tr', function(){
        var syid = $(this).attr('data-sy');
        var semid = $(this).attr('data-sem');

        if(syid > 0)
        {
            $.ajax({
                url: '{{route('info_changesysem')}}',
                type: 'GET',
                dataType: '',
                data: {
                    syid:syid,
                    semid:semid
                },
                success:function(data)
                {
                    // location.reload();

                    if($('#studname-header').attr('stud-id') == '')
                    {
                        location.reload();
                    }
                    else
                    {
                        $('#studname-header').trigger('click');
                        ActiveInfo();
                        $('#modal-changeChrngInfo').modal('hide');
                    }
                }
            });
        }
    });

    $(document).on('click', '#addpayment_tuition', function(){
        if($('#studname-header').attr('stud-id') != '')
        {
            $('#modal-addpay').modal('show');
            $('#addpay_proceed').attr('data-kind', 'tui');

            setTimeout(function(){
                $('#addpay_amount').focus();
            }, 300);
        }
        else
        {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                type: 'warning',
                title: 'Please select a student'
            })
        }
    })

    $(document).on('click', '#addpay_proceed', function(){
        var amount = $('#addpay_amount').val();
        var studid = $('#studname-header').attr('stud-id');
        var transno = $('#studname-header').attr('trans-id');
        var terminalno = terminalnumber;

        $.ajax({
            url: '{{route('addpayment_tui')}}',
            type: 'GET',
            dataType:'json',
            data: {
                amount:amount,
                studid:studid,
                transno:transno,
                terminalno:terminalno
            },
            success:function(data)
            {
                $('#orderlist').html(data.line);
                $('#orderlisttotal').text(data.total);
                $('#modal-addpay').modal('hide');
                $('#addpay_amount').val('');
            }
        });
        

    });

    $(document).on('select2:close', '#be_booklist', function(){
        amount = $(this).find(':selected').attr('data-amount');
        $('#be_amount').val(amount);
    })

	$(document).on('click', '#v3_btnenteramount', function(){
        // _performEnteramount = 1;
        // $('.payment-list tr').each(function(){
        //     $(this).trigger('click');
        // })

        processenteramount();
    })

    function processenteramount()
    {
        _performEnteramount = 1;
        $('.payment-list tr').each(function(){
            var datavalue = $(this).find('.payval').attr('data-value');
            if(datavalue == 0 || $(this).hasClass('bg-primary'))
            {
                // console.log('aaa');
            }
            else
            {   
                console.log('bbb');         
                $(this).trigger('click');
                return false;
            }
        })  
    }
	
	$(document).on('click', '#v2_updledger', function(){
        var studid = $('#studname-header').attr('stud-id');
        var syid = '{{App\CashierModel::getSYID()}}'
        var semid = '{{App\CashierModel::getsemID()}}'
        // var feesid = $('#studname-header').attr('fees-id');
        // var esURL = "{db::table('schoolinfo')->first()->essentiellink}}"

        $.ajax({
            url:"{{route('ul_loadfees')}}",
            method:'GET',
            data:{
                studid:studid,
                syid:syid,
                semid:semid
            },
            dataType:'json',
            success:function(data)
            {
                $('#ul_feelist').html(data.feelist);
                $('#modal-ul_fees').modal('show');
            }
        });        

    });

    $(document).on('click', '.col-fees', function(){
        feesid = $(this).attr('data-id');
        $('#ul_proceed').attr('data-id', feesid);
        
    })

    $(document).on('click', '#ul_proceed', function(){
        var esURL = "{{db::table('schoolinfo')->first()->essentiellink}}"
        var studid = $('#studname-header').attr('stud-id');
        var syid = '{{App\CashierModel::getSYID()}}'
        var semid = '{{App\CashierModel::getsemID()}}'

        $('#modal-overlay').modal('show');

        Swal.fire({
          title: 'Update student ledger?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Update Ledger'
        }).then((result) => {
          if (result.value == true) {
            $.ajax({
                url: esURL + '/api_updateledger',
                type: 'GET',
                dataType: '',
                data: {
                    studid:studid,
                    syid:syid,
                    semid:semid,
                    feesid:feesid
                },
                success:function(data)
                {
                    console.log('data');

                    setTimeout(function(){
                        $('#modal-overlay').modal('hide');
                    }, 300)

                    if(data == 'done')
                    {
                        $('#btn_ledger').trigger('click');
                        $('#studname-header').trigger('click');

                        const Toast = Swal.mixin({
                          toast: true,
                          position: 'top-end',
                          showConfirmButton: false,
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'success',
                          title: 'Student ledger is updated.'
                        })
                    }
                },
                complete:function(data)
                {
                    console.log('done');
                }
            });
            
          }
        })
    })






















































































        
        
        






































  // ---------------------------------------------------------

  

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


        // console.log('studstatus: ' + data.studstatus);
        if(data.studstatus == 0)
        {
          $('#cat_items').trigger('click');
        }
        else
        {
          // console.log('click');
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
      // console.log($('#oltoast-paytype').attr('data-id'));
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
		$('#payprocess').trigger('click');	
	}

    if(e.which==117)
    {
      e.preventDefault();
      $('#btn_enteramount').trigger('click');
    }

	if(e.which==113)
	{
		e.preventDefault();
		$('#btn_menu').trigger('click');	
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
      // console.log($('#payscheditems article').length);
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
    // console.log(totalAmount + ' >= ' + coldue);

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
          performPay();
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

    // console.log('adjdate: ' + adjdate);

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

        // console.log('TransID: ' + $('#prntOR').attr('data-id'));

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
        // console.log(data);
        if(data == 1)
        {
          // console.log('done');
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
 

  $(document).on('click', '#btnview-back', function(){
    $('.screen').addClass('oe_hidden');
    $('#cashiertrans').removeClass('oe_hidden');
  });

  $(document).on('click', '#back-cashtrans', function(){
    $('.screen').addClass('oe_hidden');
    $('#cashiertrans').removeClass('oe_hidden');
  });

  

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
    // console.log('wa');
    
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
              // console.log($(this).attr('aria-labelledby'));
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
    // console.log();
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
  // $(document).on('click', '#pay-list tr', function(){
  //   $('#modal-onlinepay').modal('hide');
  //   $('.screen').addClass('oe_hidden');
  //   $('#mainview').removeClass('oe_hidden');

  //   $('#olinfo-amount').val('');
  //   $('#oltoast-paytype').attr('data-id', '');

  //   $('#selcustomer').attr('selstud-id', $(this).attr('data-id'));
  //   $('#selcustomer').attr('selstud-name', $(this).attr('stud-name'));
  //   $('#selcustomer').attr('data-level', $(this).attr('data-level'));
  //   $('#oltoast-paytype').text($(this).find('.ol-paytype').text() + ' (' + $(this).find('.ol-paytype').attr('data-value') + ')');
  //   $('#oltoast-paytype').attr('data-id', $(this).find('.ol-paytype').attr('data-id'));
  //   $('#oltoast-amountpay').text($(this).find('.ol-amount').text());
  //   $('#oltoast-studname').text($(this).find('.ol-studname').text());
  //   $('#oltoast-refnum').text($(this).find('.ol-refnum').text());
  //   $('#olinfo-amount').val($(this).find('.ol-amount').attr('data-value'));
  //   $('#olinfo-bankname').val($(this).find('.ol-paytype').attr('data-value'));
  //   $('#oltoast-transdate').text($(this).find('.ol-transdate').text());
  //   $('#oltoast-transdate').attr('data-value', $(this).find('.ol-transdate').attr('data-value'));

  //   olid = $(this).attr('ol-id');
  //   // $('#selcustomer').trigger('click');
  //   selectstudent();
  // });

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

    $(document).on('click', '.username', function(){
        $('#logoutcash').trigger('click');
    });

    $(document).on('keyup', '#be_studsearch', function(){
        loadbookentries();
    });
  
});

</script>