

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
    size: 8.5in 5.5in;
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
    <table style="width: 100%; margin:10px 0px 0px 0px; ">
        <tr>
            <td style="vertical-align: top; width: 48%; padding: 0px;">
                <div style="line-height: 80px;">&nbsp;</div>
                <table style="width:100%; margin: 0px;">
                    <tr>
                        <td style="padding-left: 50px;">
                            {{date('m/d/Y', strtotime($transdate))}}
                        </td>
                        <td style="padding-left: 50px;">
                            {{$gradelevel}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 50px;" colspan="2">
                            
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 50px;" colspan="2">
                            {{$trans->studname}}
                        </td>
                    </tr>
                </table>
                <br/>
                <br/>
                <br/>
                <div class="table" style="padding-left: 20px;height: 190px; line-height: 10px;">
                    @foreach($transdetail as $detail)
                        <div class="table-row" style=" border: 1px solid black;">
                            <div class="table-cell" style="text-align: left;">
                                {{ucwords(strtolower($detail->items))}}
                            </div>
                            <div class="table-cell" style="text-align: right; padding-right: 25px;">
                                {{number_format($detail->amount,2)}}
                            </div>
                        </div>
                    @endforeach
                </div>
                <br/>
                <br/>
                <div class="table" style="font-size: 18px !important;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: right; padding-right: 25px;">
                            {{number_format(collect($transdetail)->sum('amount'),2)}}
                            </div>
                        </div>
                </div>
                <br/>
                <div class="table" style="font-size: 14px;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left; width: 40%;">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: center">
                            {{auth()->user()->name}}
                            </div>
                        </div>
                </div>
            </td>
            <td style="vertical-align: top; width: 4%;"></td>
            <td style="vertical-align: top; width: 48%; padding: 0px;">
                <div style="line-height: 80px;">&nbsp;</div>
                <table style="width:100%; margin: 0px;">
                    <tr>
                        <td style="padding-left: 50px;">
                            {{date('m/d/Y', strtotime($transdate))}}
                        </td>
                        <td style="padding-left: 50px;">
                            {{$gradelevel}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 50px;" colspan="2">
                            
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 50px;" colspan="2">
                            {{$trans->studname}}
                        </td>
                    </tr>
                </table>
                <br/>
                <br/>
                <br/>
                <div class="table" style="font-size: 14px;padding-left: 30px;height: 190px; line-height: 10px;">
                    @foreach($transdetail as $detail)
                        <div class="table-row" style=" border: 1px solid black;">
                            <div class="table-cell" style="text-align: left;">
                                {{ucwords(strtolower($detail->items))}}
                            </div>
                            <div class="table-cell" style="text-align: right; padding-right: 25px;">
                                {{number_format($detail->amount,2)}}
                            </div>
                        </div>
                    @endforeach
                </div>
                <br/>
                <br/>
                <div class="table" style="font-size: 18px !important;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: right; padding-right: 25px;">
                            {{number_format(collect($transdetail)->sum('amount'),2)}}
                            </div>
                        </div>
                </div>
                <br/>
                <div class="table" style="font-size: 14px;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left; width: 40%;">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: center">
                            {{auth()->user()->name}}
                            </div>
                        </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>