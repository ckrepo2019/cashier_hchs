

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
    <table style="width: 100%; margin:0px;">
        <tr>
            <td style="vertical-align: top; width: 48%; padding: 0px;">
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
                <div style="line-height: 80px;">&nbsp;</div>
                <table style="width:100%; margin: 0px; line-height: 2px;">
                    <tr>
                        <td style="text-align: right; width: 30%;">
                            {{$gradelevel}}&nbsp;&nbsp;
                        </td>
                        <td style=" font-size: 25px; text-align: right; width: 70%;">
                            {{$trans->ornum}}&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <br/>
                <br/>
                <table style="width:100%; margin: 0px; line-height: 2px;">
                    <tr>
                        <td style="padding-left: 85px;font-size: 15px;">{{$trans->sid}}</td>
                        <td style=" font-size: 25px; text-align: right;">
                            {{-- {{$trans->ornum}}&nbsp;&nbsp; --}}
                        </td>
                    </tr>
                </table>
                <table style="width:100%; margin: 0px;">
                    <tr>
                        <td style="padding-left: 45px;font-size: 12px;">{{$trans->studname}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 45px;font-size: 12px;">{{date('m/d/Y',strtotime($trans->transdate))}}</td>
                    </tr>
                </table>
                <br/>
                <br/>
                <br/>
                <div class="table" style="padding-left: 20px;height: 210px; line-height: 10px;">
                    @foreach($transdetail as $detail)
                        <div class="table-row" style=" border: 1px solid black;">
                            <div class="table-cell" style="text-align: left;">
                                {{ucwords(strtolower($detail->items))}}
                            </div>
                            <div class="table-cell" style="text-align: right;">
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
                            {{auth()->user()->name}}
                            </div>
                        </div>
                </div>
            </td>
            <td style="vertical-align: top; width: 4%;"></td>
            <td style="vertical-align: top; width: 48%; padding: 0px;">
                <div style="line-height: 80px;">&nbsp;</div>
                <table style="width:100%; margin: 0px; line-height: 2px;">
                    <tr>
                        <td style="text-align: right; width: 30%;">
                            {{$gradelevel}}&nbsp;&nbsp;
                        </td>
                        <td style=" font-size: 25px; text-align: right; width: 70%;">
                            {{$trans->ornum}}&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <br/>
                <br/>
                <table style="width:100%; margin: 0px; line-height: 2px;">
                    <tr>
                        <td style="padding-left: 85px;font-size: 15px;">{{$trans->sid}}</td>
                        <td style=" font-size: 25px; text-align: right;">
                            {{-- {{$trans->ornum}}&nbsp;&nbsp; --}}
                        </td>
                    </tr>
                </table>
                <table style="width:100%; margin: 0px;">
                    <tr>
                        <td style="padding-left: 55px;font-size: 12px;">{{$trans->studname}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 55px;font-size: 12px;">{{date('m/d/Y',strtotime($trans->transdate))}}</td>
                    </tr>
                </table>
                <br/>
                <br/>
                <br/>
                <div class="table" style="font-size: 14px;padding-left: 30px;height: 210px; line-height: 10px;">
                    @foreach($transdetail as $detail)
                        <div class="table-row" style=" border: 1px solid black;">
                            <div class="table-cell" style="text-align: left;">
                                {{ucwords(strtolower($detail->items))}}
                            </div>
                            <div class="table-cell" style="text-align: right;">
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
                            {{auth()->user()->name}}
                            </div>
                        </div>
                </div>
            </td>
        </tr>
    </table>
    {{-- <div style="page-break-inside: always"></div> --}}
	{{-- <div style="width: 46%;float: left;">
		<br/>
		<div class="table" style="padding-left: 30px;">
			<div class="table-row">
				<div class="table-cell" style="width: 20%;">
                    <img src="{{$schoolinfo->essentiellink . '/' . $schoolinfo->picurl}}" style="width: 65px;">
				</div>
				<div class="table-cell" style="width: 80%; text-align: center;">
                   <span style="font-size: 17px;">{{$schoolinfo->schoolname}}</span>
                   <br>
                   {{$schoolinfo->address}}
                   <br>
                   <br>
                   <em>ACKNOWLEDGEMENT RECEIPT</em>
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;margin-top: 2px;">
			<div class="table-row">
				<div class="table-cell" style="width: 50%; border: none;">
				</div>
				<div class="table-cell" style="width: 50%; border: none; text-align: right;">
					{{date('M d, Y', strtotime($datenow))}}
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;">
			<div class="table-row">
				<div class="table-cell">
                    NAME :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$trans->studname}}
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;margin-top: 10px;;">
			<div class="table-row">
				<div class="table-cell" style="text-align: justify;padding: 0px; line-height: 15px;">
					THE SUM OF <u>{{$amountstring}} (<span style="font-family: DejaVu Sans; sans-serif;">&#8369; {{number_format($amount,2)}}</span>)</u>
				</div>
			</div>
		</div>
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 30px;height: 210px;">
			@foreach($transdetail as $detail)
				<div class="table-row" style=" border: 1px solid black;">
					<div class="table-cell" style="text-align: left;">
						{{$detail->items}}
					</div>
					<div class="table-cell" style="text-align: left;">
						{{number_format($detail->amount,2)}}
					</div>
				</div>
			@endforeach
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;">
				<div class="table-row">
					<div class="table-cell" style="text-align: left">
                    <u>BY: {{auth()->user()->name}}</u>
                    <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;Cashier
					</div>
				</div>
		</div>
	</div> --}}
	{{-- <div style="width: 46%;float: right;">
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 30px;">
			<div class="table-row">
				<div class="table-cell" style="width: 20%;">
                    <img src="{{$schoolinfo->essentiellink . '/' . $schoolinfo->picurl}}" style="width: 65px;">
				</div>
				<div class="table-cell" style="width: 80%; text-align: center;">
                    <strong>{{$schoolinfo->schoolname}}</strong>
                    <br>
                    {{$schoolinfo->address}}
                    <br>
                    <br>
                    <em>ACKNOWLEDGEMENT RECEIPT</em>
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;margin-top: 2px;">
			<div class="table-row">
				<div class="table-cell" style="width: 50%; border: none;">
				</div>
				<div class="table-cell" style="width: 50%; border: none; text-align: right;">
					{{date('M d, Y', strtotime($datenow))}}
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;">
			<div class="table-row">
				<div class="table-cell">
                    NAME :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$trans->studname}}
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;margin-top: 10px;;">
			<div class="table-row">
				<div class="table-cell" style="text-align: justify;padding: 0px; line-height: 15px;">
					THE SUM OF <u>{{$amountstring}} (<span style="font-family: DejaVu Sans; sans-serif;">&#8369; {{number_format($amount,2)}}</span>)</u>
				</div>
			</div>
		</div>
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 30px;height: 210px;">
			@foreach($transdetail as $detail)
				<div class="table-row" style=" border: 1px solid black;">
					<div class="table-cell" style="text-align: left;">
						{{$detail->items}}
					</div>
					<div class="table-cell" style="text-align: left;">
						{{number_format($detail->amount,2)}}
					</div>
				</div>
			@endforeach
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;">
				<div class="table-row">
					<div class="table-cell" style="text-align: left">
                    <u>BY: {{auth()->user()->name}}</u>
                    <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;Cashier
					</div>
				</div>
		</div>
	</div> --}}
</body>
</html>