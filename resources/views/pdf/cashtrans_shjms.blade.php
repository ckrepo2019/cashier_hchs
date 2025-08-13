




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
		.table-cell { display: table-cell; padding: 2px;}
		* {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10px;
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
        top: 15px;
        width: 25%;
        text-align: right;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 11px;
    }
    #orright {
        position: fixed;
		/* float: right; */
        top: 15px;
        width: 75%;
        text-align: right;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 11px;
    }
@page{
	margin:5%;
	margin-top:5%;
	/* margin-left: 2px */
}





</style>
	
</head>
<body>
    @if($void == 0)
    @else
        <div id="watermarkleft">
        VOID
        </div>
        <div id="watermarkright">
        VOID
        </div>
    @endif
	<br>
	<div class="table" >
		<div class="table-row">
			<div class="table-cell" style="width: 45%; border: none; height: 280px;">
				<div id="orleft">
				OR {{$ornum}}
				</div>
				<div class="table" style="margin-left: 45px;">
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;">
							{{-- {{$studname}} --}}
						</div>
						<div class="table-cell" style="width: 30%; padding-right: 5px;border: none; text-align: right;">
							 <span>{{$tdate}}</span>&nbsp;&nbsp;&nbsp;
						</div>
                    </div>
                    <br/>
				</div>
				<div class="table" style="margin-left: 45px;">
					<div class="table-row">
						<div class="table-cell" style="width: 10%; border: none; padding-top: 10px;">
							&nbsp;
						</div>
						<div class="table-cell" style="width: 80%; padding-right: 5px;border: none; text-align: left;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$studname}}
						</div>
                    </div>
					<div class="table-row">
						<div class="table-cell" style="width: 10%; border: none; text-align: right;">
						</div>
						<div class="table-cell" style="width: 80%; border: none;">
                            <br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$gradelevel}}
						</div>
					</div>
					<div class="table-row">
						<div class="table-cell" style="width: 10%; border: none; text-align: right;">
						</div>
						<div class="table-cell" style="width: 80%; font-size: 9px; border: none;">
                            <br>
							&nbsp;{{$numtowords}}
						</div>
					</div>
				</div>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<div class="table" style="border:none; height:90px">
					@php
						$totalamount=0;
					@endphp
					@foreach($items as $item)
						@php
						$totalamount += $item->amount
						@endphp
						<div class="table-row">
							<div class="table-cell" style="width: 70%;">
								<span style="margin-left: 15px;">{{$item->items}}</span>
							</div>
							<div class="table-cell" style="width: 30%;">
								<span style="padding-left: 20px;">&nbsp;&nbsp;&nbsp;<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span>  {{number_format($item->itemprice, 2)}}</span>
							</div>
						</div>
					@endforeach
				</div>
			</div>
			<div class="table-cell" style="width: 8%;text-align:center; border: none;height: 280px;">&nbsp;</div>
			<div class="table-cell" style="width: 45%; border: none; height: 280px;">
				<div id="orright">
				OR {{$ornum}}
				</div>
				<div class="table" style="margin-left: 45px;">
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;">
							{{-- {{$studname}} --}}
						</div>
						<div class="table-cell" style="width: 30%; padding-right: 5px;border: none; text-align: right;">
							 <span>{{$tdate}}</span>&nbsp;&nbsp;&nbsp;
						</div>
                    </div>
                    <br/>
				</div>
				<div class="table" style="margin-left: 45px;">
					<div class="table-row">
						<div class="table-cell" style="width: 10%; border: none; padding-top: 10px;">
							&nbsp;
						</div>
						<div class="table-cell" style="width: 80%; padding-right: 5px;border: none; text-align: left;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$studname}}
						</div>
                    </div>
					<div class="table-row">
						<div class="table-cell" style="width: 10%; border: none; text-align: right;">
						</div>
						<div class="table-cell" style="width: 80%; border: none;">
                            <br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$gradelevel}}
						</div>
					</div>
					<div class="table-row">
						<div class="table-cell" style="width: 10%; border: none; text-align: right;">
						</div>
						<div class="table-cell" style="width: 80%; font-size: 9px; border: none;">
                            <br>
							&nbsp;{{$numtowords}}
						</div>
					</div>
				</div>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<div class="table" style="border:none; height:90px">
					@php
						$totalamount=0;
					@endphp
					@foreach($items as $item)
						@php
						$totalamount += $item->amount
						@endphp
						<div class="table-row">
							<div class="table-cell" style="width: 70%;">
								<span style="margin-left: 15px;">{{$item->items}}</span>
							</div>
							<div class="table-cell" style="width: 30%;">
								<span style="padding-left: 20px;">&nbsp;&nbsp;&nbsp;<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span>  {{number_format($item->itemprice, 2)}}</span>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<br/>
	<div class="table">
		<div class="table-row">
			<div class="table-cell" style="width: 46%; border: none;">
				<div class="table" style="">
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;text-align: left;">
							&nbsp;
						</div>
                        <div class="table-cell" style="width: 30%; border: none;">
                            <br>
							<span style="margin-left: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span>  {{number_format($totalamount, 2, '.',',')}}</span>
						</div>
					</div>
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;text-align: left;">
							&nbsp;
						</div>
						<div class="table-cell" style="width: 30%; border: none;">
							<span style="margin-left: 10px;">{{auth()->user()->name}}</span>
						</div>
					</div>
				</div>
			</div>
			<div class="table-cell" style="width: 8%; border: none;">
			</div>
			<div class="table-cell" style="width: 46%; border: none;">
				<div class="table" style="">
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;text-align: left;">
							&nbsp;
						</div>
						<div class="table-cell" style="width: 30%; border: none;">
                            <br>
							<span style="margin-left: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span>  {{number_format($totalamount, 2, '.',',')}}</span>
						</div>
					</div>
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;text-align: left;">
							&nbsp;
						</div>
						<div class="table-cell" style="width: 30%; border: none;">
							<span style="margin-left: 10px;">{{auth()->user()->name}}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>