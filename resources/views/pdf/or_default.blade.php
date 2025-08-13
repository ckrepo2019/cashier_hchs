




<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title></title>

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
@page{
	margin:5px;
	margin-left: 7px;
	margin-right: 50px;
}





</style>
	
</head>
<body>
	@php
		$totalamount = 0;
	@endphp
	@foreach($transdetail as $detail)
		@php
			$totalamount += $detail->amount;
		@endphp
	@endforeach
	<div style="width: 46%;float: left;">
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
			<div class="table-row">
				<div class="table-cell">
                    LEVEL :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$gradelevel}}
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
	</div>
	<div style="width: 46%;float: right;">
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
			<div class="table-row">
				<div class="table-cell">
                    LEVEL :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$gradelevel}}
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
	</div>
</body>
</html>