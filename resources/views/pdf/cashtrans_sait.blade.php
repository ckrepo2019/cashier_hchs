




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
		.table-cell { display: table-cell; }
		* {
			font-family: Arial, Helvetica, sans-serif;
			/* font-size: 13px; */
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
        top: 5%;
        width: 20%;
        text-align: left;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 12px;
    }
    #orright {
        position: fixed;
		/* float: right; */
        top: 5%;
        width: 65%;
        text-align: right;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 12px;
    }
    /* #orleft {
        position: fixed;
        top: 5%;
        width: 10%;
        text-align: left;
        opacity: .3;
        z-index: -1000;
        font-size: 12px;
    }
    #orright {
        position: fixed;
        top: 5%;
        width: 55%;
        text-align: right;
        opacity: .3;
        z-index: -1000;
        font-size: 12px;
    } */

@page{
	margin:5%;
	margin-top:8%;
	/* margin-left: 2px */
}





</style>
	
</head>
<body>
	{{-- <div id="orleft">
	{{$ornum}}
	</div> --}}
	{{-- <div id="orright">
		{{$ornum}}
	</div> --}}
    @if($void == 0)
    @else
        <div id="watermarkright">
        VOID
        </div>
        <div id="watermarkleft">
        VOID
        </div>
    @endif
	<br>
	<br>
	<div class="table" >
		<div class="table-row">
			<div class="table-cell" style="width: 46%; border: none; height: 800px;">
				<div id="orleft">
				OR {{$ornum}}
				</div>
				<div class="table" style="font-size: 13px;">
					<div class="table-row">
						<div class="table-cell" style="width: 50%; border: none;">
							&nbsp;
						</div>
						<div class="table-cell" style="width: 50%; border: none; text-align: right;padding-top: 5px;">
							&nbsp;&nbsp;&nbsp; <span>{{$tdate}}</span>
						</div>
					</div>
				</div>
				<br>
				<div class="table" style="margin-left: 10px;">
					<div class="table-row">
						<div class="table-cell" style="width: 30%; border: none;">
							{{-- Received from: --}}
							&nbsp;
						</div>
						<div class="table-cell" style="width: 70%;font-size: 12px; text-align: right;">
							{{$studname}}
							
						</div>
					</div>
				</div>
				<div class="table" style="margin-left: 10px;margin_-top: 5px;">
					<div class="table-row">
						<div class="table-cell" style="width: 30%; border: none;">
							{{-- Received from: --}}
							&nbsp;
						</div>
						<div class="table-cell" style="width: 70%;font-size: 12px; text-align: right;">
							{{$gradelevel}}
							
						</div>
					</div>
				</div>
				<br>
				<br>
				<br>
				<div class="table" >
					<div class="table-row">
						<div class="table-cell" style="width: 70%; text-align: center;">
							{{-- Description --}}
							&nbsp;
						</div>
						<div class="table-cell" style="width: 20%; text-align: center;">
							{{-- Amount --}}
							&nbsp;
						</div>
					</div>
				</div>
				<br/>
				<div class="table" style="font-size: 12px;">
					@php
						$totalamount=0;
					@endphp
					@foreach($items as $detail)
						@php
						$totalamount += $detail->amount
						@endphp
						<div class="table-row">
							<div class="table-cell" style="width: 70%;padding-left: 15px;">
								<span>{{$detail->items}}</span>
							</div>
                            <div class="table-cell" style="width: 30%;">
                                @if($detail->qty == 1)
                                <span style="margin-left: 10px;">{{number_format((float)$detail->amount, 2)}}</span>
                                    @php
                                        $totalamount+=$detail->amount;
                                    @endphp
                                @else
                                <span style="margin-left: 10px;">{{number_format(((float)$detail->amount*$detail->qty), 2)}}</span>
                                    @php
                                        $totalamount+=($detail->amount*$detail->qty);
                                    @endphp
                                @endif
							</div>
						</div>
					@endforeach
				</div>
			</div>
			<div class="table-cell" style="width: 8%;text-align:center; border: none;height: 800px;">&nbsp;</div>
			<div class="table-cell" style="width: 46%; border: none; height: 800px;">
				<div id="orright">
					OR {{$ornum}}
				</div>
				<div class="table" style="font-size: 13px;">
					<div class="table-row">
						<div class="table-cell" style="width: 50%; border: none;">
							&nbsp;
						</div>
						<div class="table-cell" style="width: 50%; border: none; text-align: right;padding-top: 5px;">
							&nbsp;&nbsp;&nbsp; <span>{{$tdate}}</span>
						</div>
					</div>
				</div>
				<br>
				<div class="table" style="margin-left: 10px;">
					<div class="table-row">
						<div class="table-cell" style="width: 30%; border: none;">
							{{-- Received from: --}}
							&nbsp;
						</div>
						<div class="table-cell" style="width: 70%;font-size: 12px; text-align: right;">
							{{$studname}}
							
						</div>
					</div>
				</div>
				<div class="table" style="margin-left: 10px;margin_-top: 5px;">
					<div class="table-row">
						<div class="table-cell" style="width: 30%; border: none;">
							{{-- Received from: --}}
							&nbsp;
						</div>
						<div class="table-cell" style="width: 70%;font-size: 12px; text-align: right;">
							{{$gradelevel}}
							
						</div>
					</div>
				</div>
				<br>
				<br>
				<br>
				<div class="table" >
					<div class="table-row">
						<div class="table-cell" style="width: 70%; text-align: center;">
							{{-- Description --}}
							&nbsp;
						</div>
						<div class="table-cell" style="width: 20%; text-align: center;">
							{{-- Amount --}}
							&nbsp;
						</div>
					</div>
				</div>
				<br/>
				<div class="table" style="font-size: 12px;">
					@php
						$totalamount=0;
					@endphp
					@foreach($items as $detail)
						@php
						$totalamount += $detail->amount
						@endphp
						<div class="table-row">
							<div class="table-cell" style="width: 70%;padding-left: 15px;">
								<span>{{$detail->items}}</span>
							</div>
                            <div class="table-cell" style="width: 30%;">
                                @if($detail->qty == 1)
                                <span style="margin-left: 10px;">{{number_format((float)$detail->amount, 2)}}</span>
                                    @php
                                        $totalamount+=$detail->amount;
                                    @endphp
                                @else
                                <span style="margin-left: 10px;">{{number_format(((float)$detail->amount*$detail->qty), 2)}}</span>
                                    @php
                                        $totalamount+=($detail->amount*$detail->qty);
                                    @endphp
                                @endif
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<div class="table" style="font-size: 14px;">
		<div class="table-row">
			<div class="table-cell" style="width: 46%; border: none;">
				<div class="table" style="">
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;text-align: left;">
							&nbsp;
						</div>
						<div class="table-cell" style="width: 30%; border: none; font-size: 12px;">
							<span style="margin-left: 10px;">{{number_format((float)$totalamountpay, 2,'.',',') }}</span>
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
						<div class="table-cell" style="width: 30%; border: none; font-size: 12px;">
							<span style="margin-left: 10px;">{{number_format((float)$totalamountpay, 2,'.',',') }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</body>
</html>