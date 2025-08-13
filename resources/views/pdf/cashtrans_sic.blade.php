




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
    #orleft {
        position: fixed;
		/* float: right; */
        top: 52%;
        width: 40%;
        text-align: right;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 12px;
    }
    #orright {
        position: fixed;
		/* float: right; */
        top: 52%;
        width: 40%;
        text-align: right;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 12px;
    }

@page{
	margin:5px;
	margin-left: 7px;
	margin-right: 55px;
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
		<div id="orleft">
		OR {{$ornum}}
		</div>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		{{-- <br/> --}}
		{{-- <br/> --}}
		<div class="table" style="font-size: 14px;margin-top: 2px;">
			<div class="table-row">
				<div class="table-cell" style="width: 50%; border: none;">
				</div>
				<div class="table-cell" style="width: 50%; border: none; text-align: right;">
					{{date('M d', strtotime($tdate))}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{substr (date('Y', strtotime($tdate)), -2)}}&nbsp;&nbsp;
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 50px;">
			<div class="table-row">
				<div class="table-cell">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$studname}}
				</div>
			</div>
		</div>
		<br/>
		{{-- <br/> --}}
		<div class="table" style="font-size: 14px;padding-left: 50px;margin-top: 10px;;">
			<div class="table-row">
				<div class="table-cell" style="text-align: justify;padding: 0px; line-height: 15px;">
					<span style="margin-left: 30%;">{{$numtowords}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<span style="font-family: DejaVu Sans; sans-serif;">&#8369; {{number_format($totalamount,2)}}</span>)</span>
				</div>
			</div>
		</div>
		{{-- <br/>
		<br/> --}}
		{{-- <br/> --}}
		{{-- <br/> --}}
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 50px;height: 210px;">
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
		<br/>
		<br/>
		<br/>
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 50px;">
				<div class="table-row">
					<div class="table-cell" style="text-align: right">
					{{auth()->user()->name}}
					</div>
				</div>
		</div>
	</div>
	<div style="width: 46%;float: right;">
		<div id="orright">
		OR {{$ornum}}
		</div>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		{{-- <br/> --}}
		{{-- <br/> --}}
		<div class="table" style="font-size: 14px;margin-top: 2px;">
			<div class="table-row">
				<div class="table-cell" style="width: 50%; border: none;">
				</div>
				<div class="table-cell" style="width: 50%; border: none; text-align: right;">
					{{date('M d', strtotime($tdate))}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{substr (date('Y', strtotime($tdate)), -2)}}&nbsp;&nbsp;
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 50px;">
			<div class="table-row">
				<div class="table-cell">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$studname}}
				</div>
			</div>
		</div>
		<br/>
		{{-- <br/> --}}
		<div class="table" style="font-size: 14px;padding-left: 50px;margin-top: 10px;;">
			<div class="table-row">
				<div class="table-cell" style="text-align: justify;padding: 0px; line-height: 15px;">
					<span style="margin-left: 30%;">{{$numtowords}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<span style="font-family: DejaVu Sans; sans-serif;">&#8369; {{number_format($totalamount,2)}}</span>)</span>
				</div>
			</div>
		</div>
		{{-- <br/>
		<br/> --}}
		{{-- <br/> --}}
		{{-- <br/> --}}
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 50px;height: 210px;">
			@foreach($items as $detail)
				<div class="table-row">
					<div class="table-cell" style="text-align: left;">
						{{$detail->items}}
					</div>
					<div class="table-cell" style="text-align: left;">
						{{number_format($detail->amount,2)}}
					</div>
				</div>
			@endforeach
		</div>
		<br/>
		<br/>
		<br/>
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 50px;">
				<div class="table-row">
					<div class="table-cell" style="text-align: right">
					{{auth()->user()->name}}
					</div>
				</div>
		</div>
	</div>
</body>
</html>