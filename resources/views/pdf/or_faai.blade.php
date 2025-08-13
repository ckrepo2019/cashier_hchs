

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<style>
		.table { display: table; width: 100%; border-collapse: collapse; }
		.table-row { display: table-row; }
		.table-cell { display: table-cell; padding:2px;}
	* {
		font-family: Arial, Helvetica, sans-serif,"DejaVu Sans";
		font-size: 11px;
	}

    #watermarkleft {
        position: fixed;
        top: 5%;
        width: 100%;
        text-align: left;
        opacity: .2;
        transform: rotate(-30deg);
        transform-origin: 50% 50%;
        z-index: -1000;
        font-size: 100px;
    }
    #watermarkright {
        position: fixed;
        top: 28%;
        width: 100%;
        text-align: right;
        opacity: .19;
        transform: rotate(-30deg);
        transform-origin: 50% 50%;
        z-index: -1000;
        font-size: 100px;
    }
    table{
        border-collapse: collapse;
    }
@page{
    margin: 2px 30px;
    size: 4.13in 6.29in;
}
body{
    margin: 0px;
    /* margin: 2px 20px; */
}
@media print 
{
   @page
   {
    /* size: 8.5in 5.5in; */
    /* size: landscape; */
  }
}



</style>
	
<body>
    
	@php
		$totalamount = 0;
	@endphp
	@foreach($transdetail as $detail)
		@php
			$totalamount += $detail->amount;
		@endphp
	@endforeach
    <table style="width: 100%; margin:20px 0px;">
        <tr>
            <td style="vertical-align: top; width: 100%; padding: 0px;">
                {{-- <div class="table" >
                    <div class="table-row">
                        <div class="table-cell" style="width: 15%;">
                            <img src="{{$schoolinfo->essentiellink . '/' . $schoolinfo->picurl}}" style="width: 65px;">
                        </div>
                        <div class="table-cell" style="width: 85%; text-align: center;">
                           <strong style="font-size: 16px;">{{$schoolinfo->schoolname}}</strong>
                           <br>
                           <strong>NON-VAT REG. TIN 000-519-450-000</strong>
                           <br>
                           Purok 9 McArthur Highway, Toril, Brgy. bayabas
                           <br>
                           Crossing Toril District Davao City
                           <br>
                           Business Style: Educational Institution (Elementary to College)
                           <br>
                           Non-Stock/Non-Profit
                           <br/>
                           <strong style="font-size: 17px;"><u>OFFICIAL RECEIPT</u></strong>
                        </div>
                    </div>
                </div> --}}
                <div style="line-height: 70px;">&nbsp;</div>
                <table style="width:100%; margin: 0px; line-height: 10px;">
                    <tr>
                        <td style="text-align: left; width: 65%;">
                            {{$gradelevel}}&nbsp;&nbsp;&nbsp;
                        </td>
                        <td style=" font-size: 20px; text-align: right; width: 35%;">
                            {{$trans->ornum}}&nbsp;&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center; font-size: 13px;">{{date('m/d/Y',strtotime($trans->transdate))}}</td>
                    </tr>
                </table>
                {{-- <br/>
                <br/>
                <table style="width:100%; margin: 0px; line-height: 2px;">
                    <tr>
                        <td style="padding-left: 85px;font-size: 15px;">{{$trans->sid}}</td>
                        <td style=" font-size: 25px; text-align: right;">
                            
                        </td>
                    </tr>
                </table> --}}
                <table style="width:100%; margin-top: 40px; line-height: 15px;">
                    <tr>
                        <td colspan="2" style="padding-left: 45px;font-size: 12px; text-align: right;">{{ucwords(strtolower($trans->studname))}}&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; width: 25%;">&nbsp;</td>
                        <td style="padding-right: 7px; font-size: 12px; text-align: right; width: 75%;">{{ucwords(strtolower($amountstring))}}</td>
                    </tr>
                </table>
                <table style="width:100%; margin-top: 10px; line-height: 15px;">
                    @foreach($transdetail as $detail)
                        <tr>
                            <td style="font-size: 12px; width: 30%;">&nbsp;</td>
                            <td style="padding-right: 7px; font-size: 10.5px; text-align: left; width: 35%;">{{ucwords(strtolower($detail->items))}}</td>
                            <td style="padding-right: 7px; font-size: 10.5px; text-align: right; width: 35%;">{{number_format($detail->amount,2)}}</td>
                        </tr>
                    @endforeach
                </table>
                <br/>
                <br/>
                <div class="table" style="font-size: 18px !important;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: right">
                            {{number_format(collect($transdetail)->sum('amount'),2)}}
                            </div>
                        </div>
                </div>
                <div class="table" style="font-size: 14px;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left; width: 60%;">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: center">
                            {{-- {{auth()->user()->name}} --}}
                            </div>
                        </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>