




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
    @if($void == 0)
    @else
        <div id="watermarkright">
        VOID
        </div>
        <div id="watermarkleft">
        VOID
        </div>
    @endif
	@php
		$totalamount = 0;
	@endphp
	@foreach($items as $detail)
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
                    {{-- <img  src="{{$schoolinfo->essentiellink . '/' . $schoolinfo->picurl}}" alt="" width="172" height="172" > --}}
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
					{{date('M d, Y', strtotime($tdate))}}
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;">
			<div class="table-row">
				<div class="table-cell">
                    NAME :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$studname}}
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;margin-top: 10px;;">
			<div class="table-row">
				<div class="table-cell" style="text-align: justify;padding: 0px; line-height: 15px;">
					THE SUM OF <u>{{$numtowords}}</u>
				</div>
			</div>
		</div>
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 30px;height: 210px;">
			@foreach($items as $detail)
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
						<div class="label" style="display:inline-block;
						background-color:White;
						width: auto; text-align:center; font-size: 12px;">
							<div class="label-text" style=" float:left;
							text-align: center;
							line-height: 30px;
							vertical-align: center;
							white-space: nowrap;
							overflow: hidden;">
							<span style="text-align:center;border-bottom: 1px solid black;">&nbsp;BY: {{auth()->user()->name}}</span>
							<br/>
							<sup style="text-align:center">CASHIER</sup>
							
						</div>
					</div>
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
					{{date('M d, Y', strtotime($tdate))}}
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;">
			<div class="table-row">
				<div class="table-cell">
                    NAME :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$studname}}
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 30px;margin-top: 10px;;">
			<div class="table-row">
				<div class="table-cell" style="text-align: justify;padding: 0px; line-height: 15px;">
					THE SUM OF <u>{{$numtowords}}</u>
				</div>
			</div>
		</div>
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 30px;height: 210px;">
			@foreach($items as $detail)
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
						<div class="label" style="display:inline-block;
						background-color:White;
						width: auto; text-align:center; font-size: 12px;">
							<div class="label-text" style=" float:left;
							text-align: center;
							line-height: 30px;
							vertical-align: center;
							white-space: nowrap;
							overflow: hidden;">
							<span style="text-align:center;border-bottom: 1px solid black;">&nbsp;BY: {{auth()->user()->name}}</span>
							<br/>
							<sup style="text-align:center">CASHIER</sup>
							
						</div>
					</div>
					</div>
				</div>
		</div>
	</div>
</body>
</html>