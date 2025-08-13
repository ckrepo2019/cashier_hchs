<script>
    
var loadingtext = 'Getting ready...';
function viewselections()
{
    $.ajax({
        url: '/reports/selections',
        type: 'GET',
        dataType: 'json',
        success:function(data)
        {
            $('#soaschoolyear').empty();
            $('#soasemester').empty();
            $('#soamonthsetup').empty();
            $.each(data.schoolyears, function(key, value){
                if(value.isactive == 1)
                {
                    $('#soaschoolyear').append(
                        '<option value="'+value.id+'" selected>'+value.sydesc+'</option>'
                    );
                }else{
                    $('#soaschoolyear').append(
                        '<option value="'+value.id+'">'+value.sydesc+'</option>'
                    );
                }
            })
            $.each(data.semesters, function(key, value){
                if(value.isactive == 1)
                {
                    $('#soasemester').append(
                        '<option value="'+value.id+'" selected>'+value.semester+'</option>'
                    );
                }else{
                    $('#soasemester').append(
                        '<option value="'+value.id+'">'+value.semester+'</option>'
                    );
                }
            })
            $('#soamonthsetup').append(
                '<option value="" selected>ALL</option>'
            );
            $.each(data.monthsetups, function(key, value){
                if(value.isactive == 1)
                {
                    $('#soamonthsetup').append(
                        '<option value="'+value.description+'" selected>'+value.description+'</option>'
                    );
                }else{
                    $('#soamonthsetup').append(
                        '<option value="'+value.description+'">'+value.description+'</option>'
                    );
                }
            })
            
        }

    })
}
function viewstudents(viewtype)
{

    Swal.fire({
        title: loadingtext,
        onBeforeOpen: () => {
            Swal.showLoading()
        },
        allowOutsideClick: false
    })
    $.ajax({
        url: '/reports/statementofaccountall',
        type: 'GET',
        success:function(data)
        {
            $('#soa-studenscontainer').empty()
            $('#soa-studenscontainer').append(data)
            $('[data-toggle="tooltip"]').tooltip()
            $(".swal2-container").remove();
            $('body').removeClass('swal2-shown')
            $('body').removeClass('swal2-height-auto')
        }

    })
}
  function soa_viewdetails(studid,viewtype)
  {
        var selectedschoolyear = $('#soaschoolyear').val();
        var selectedsemester = $('#soasemester').val();
        var selectedmonth = $('#soamonthsetup').val();
        $.ajax({
            url: '/reports/statementofaccountview',
            type: 'GET',
            data: {
                studid: studid,
                selectedschoolyear: selectedschoolyear,
                selectedsemester: selectedsemester,
                selectedmonth: selectedmonth
            },
            success:function(data){
                if(viewtype == null)
                {
                    $('#soa-stud-'+studid).empty()
                    $('#soa-stud-'+studid).append(data)
                }else{
                    $('#soa-studenscontainer').empty()
                    $('#soa-studenscontainer').append(
                        '<div class="col-12 text-right mb-2">'+
                            '<button type="button" class="btn text-info printstatementofacct mr-2" exporttype="excel"  studid="'+studid+'" data-toggle="tooltip" data-placement="bottom" title="Excel"><i class="fa fa-file-excel-o"></i> Excel</button>'+
                            '<button type="button" class="btn text-info printstatementofacct" exporttype="pdf"   studid="'+studid+'" data-toggle="tooltip" data-placement="bottom" title="Pdf"><i class="fa fa-file-pdf-o"></i> PDF</button>'+
                        '</div>'
                    )
                    $('#soa-studenscontainer').append(data)
                }
            }
        })
  }
  $(document).on('click', '#btnsoa', function(){
    var studid = $('#selstud').attr('stud-id');

    $('.screen').addClass('oe_hidden');
    
    $('#soaallstudentscontainer').removeClass('oe_hidden');
       
    if($('#selstud').attr('stud-id'))
    {
        var viewtype = 'solo';
        $('.soa-filterstudent').hide();
        $.ajax({
            url: '/reports/statementofaccountstudinfo',
            type: 'GET',
            data: {
                studid: studid
            },
            success:function(data){
                $('#soa-stud-name').empty()
                $('#soa-stud-name').append(

                '<h4>'+data.lastname+', '+data.firstname+' '+data.middlename+' '+data.suffix+'</h4>'
                )
            }
        })
        $('#soa-studenscontainer').empty()
        $('#btn-soa-generate').attr('generatetype','solo')
        viewselections()
    }else{
        var viewtype = 'all';
        viewstudents(viewtype);
        $('#soa-studenscontainer').empty()
        $('.soa-filterstudent').show();
        $('#btn-soa-generate').attr('generatetype','all')
        viewselections()
    }
    $(document).on('click','#btn-soa-generate', function(){
        if($(this).attr('generatetype') == 'all')
        {
            viewstudents(viewtype)
        }else{
            soa_viewdetails(studid,'solo')
        }
    })
    $(document).on('click','.printstatementofacct', function(){
        var selectedschoolyear = $('#soaschoolyear').val();
        var selectedsemester = $('#soasemester').val();
        var selectedmonth = $('#soamonthsetup').val();
        var studid = $(this).attr('studid');
        var exporttype = $(this).attr('exporttype')
        var paramet = {
            selectedschoolyear  : selectedschoolyear,
            selectedsemester    : selectedsemester, 
            selectedmonth       : selectedmonth, 
            studid              : studid
        }
        window.open("/reports/statementofacctexport?exporttype="+exporttype+"&"+$.param(paramet));
    })
  });
  $(document).on('click','.soa-btn-viewdetailsexpand', function(){
        var studid = $(this).attr('id');
        if($(this).closest('.card').hasClass('maximized-card') == false)
        {
            // console.log('get')
            soa_viewdetails(studid,null);
        }else{
            $('#soa-stud-'+studid).empty()
            // console.log('nonono')
        }
  })
  $(document).on('click','.soa-btn-viewdetailscollapse', function(){
        var studid = $(this).attr('id');
        if($(this).closest('.card').hasClass('maximized-card') == false)
        {
            // console.log('get')
            soa_viewdetails(studid,null);
        }else{
            $('#soa-stud-'+studid).empty()
            // console.log('nonono')
        }
  })
// viewdetailscollapse
</script>