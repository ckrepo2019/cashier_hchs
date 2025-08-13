




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

@page{
	margin:5px;
	margin-left: 7px;
	margin-right: 55px;
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
	<div style="width: 46%; float: left;">
		<div id="orleft">
		OR {{$trans->ornum}}
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
					{{date('M d', strtotime($datenow))}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{substr (date('Y', strtotime($datenow)), -2)}}&nbsp;&nbsp;
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 50px;">
			<div class="table-row">
				<div class="table-cell">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$trans->studname}}
				</div>
			</div>
		</div>
		<br/>
		{{-- <br/> --}}
		<div class="table" style="font-size: 14px;padding-left: 50px;margin-top: 10px;;">
			<div class="table-row">
				<div class="table-cell" style="text-align: justify;padding: 0px; line-height: 15px;">
					<span style="margin-left: 30%;">{{$amountstring}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<span style="font-family: DejaVu Sans; sans-serif;">&#8369; {{number_format($totalamount,2)}}</span>)</span>
				</div>
			</div>
		</div>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 50px;height: 210px;">
			@foreach($transdetail as $detail)
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
		<div class="table" style="font-size: 14px;padding-left: 50px;">
				<div class="table-row">
					<div class="table-cell" style="text-align: right">
					{{$cashier}}
					</div>
				</div>
		</div>
	</div>
	<div style="width: 46%; float: right;">
		<div id="orright">
		OR {{$trans->ornum}}
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
					{{date('M d', strtotime($datenow))}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{substr (date('Y', strtotime($datenow)), -2)}}&nbsp;&nbsp;
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;padding-left: 50px;">
			<div class="table-row">
				<div class="table-cell">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{{$trans->studname}}
				</div>
			</div>
		</div>
		<br/>
		{{-- <br/> --}}
		<div class="table" style="font-size: 14px;padding-left: 50px;margin-top: 10px;;">
			<div class="table-row">
				<div class="table-cell" style="text-align: justify;padding: 0px; line-height: 15px;">
					<span style="margin-left: 30%;">{{$amountstring}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<span style="font-family: DejaVu Sans; sans-serif;">&#8369; {{number_format($totalamount,2)}}</span>)</span>
				</div>
			</div>
		</div>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<div class="table" style="font-size: 14px;padding-left: 50px;height: 210px;">
			@foreach($transdetail as $detail)
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
		<div class="table" style="font-size: 14px;padding-left: 50px;">
				<div class="table-row">
					<div class="table-cell" style="text-align: right">
					{{$cashier}}
					</div>
				</div>
		</div>
	</div>
	{{-- <div class="body">
		<div style="width: 100%; height: 13.5cm;">

			<div style="width: 48%; height: 13.5cm; float: left;">
				
				<div style="width: 100%; height: 1.5cm;">
					<div style="margin-left: 0.8cm; margin-top: 5.2cm; width: 60%; float:left;">
						<span style="padding-left: 0.7cm">{{$trans->studname}}</span>
					</div>
					<div style="margin-left: 0cm; width: 30%; float: right; margin-top: 0cm;">
						<span style="text-align: right; padding-left: 0.7cm">{{$datenow}}</span>
					</div>
				</div>	
				<div style="width: 100%; height: 5.25cm;">
					<div style="margin-left: 0.8cm;">
						<table class="table table-borderless">
							<thead>
								<tr>
									<td></td>
									<td></td>
								</tr>
							</thead>
							<tbody>
								@php
									$totalamount=0;
								@endphp
								@foreach($transdetail as $detail)
									{{$totalamount += $detail->amount}}
									<tr>
										<td>{{$detail->items}}</td>
										<td style="text-align: right">{{number_format($detail->amount, 2)}}</td>
									</tr>
								@endforeach
								<tr style="text-align: right;">
									<td colspan=2>
										<u>
											TOTAL:
											<span style="font-weight: bold">
												{{number_format($totalamount, 2)}}
											</span>
										</u>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div style="float: right; width: 100%">
					<span style="margin-left:5.8cm ">{{$cashier}}</span>
				</div>
			</div>


			<div style="width: 48%; height: 13.5cm; float: right; margin-left: 23.8cm;">
				
				<div style="width: 100%; height: 1.5cm;">
					<div style="margin-left: 0.8cm; margin-top: 5.2cm; width: 60%; float:left;">
						{{$trans->studname}}
					</div>
					<div style="margin-left: 0cm; width: 30%; float: right; margin-top: 0cm;">
						<span style="text-align: right; padding-left: 20px">{{$datenow}}</span>
					</div>
				</div>	
				<div style="width: 100%; height: 5.25cm; ">
					<div style="margin-left: 0.8cm;">
						<table class="table table-borderless">
							<thead>
								<tr>
									<td></td>
									<td></td>
								</tr>
							</thead>
							<tbody>
								@php
									$totalamount=0;
								@endphp
								@foreach($transdetail as $detail)
									{{$totalamount += $detail->amount}}
									<tr>
										<td>{{$detail->items}}</td>
										<td style="text-align: right">{{number_format($detail->amount, 2)}}</td>
									</tr>
								@endforeach
								<tr style="text-align: right;">
									<td colspan=2>
										<u>
											TOTAL:
											<span style="font-weight: bold">
												{{number_format($totalamount, 2)}}
											</span>
										</u>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div style=" float: left; width: 100%">
					<span style="margin-left:5.8cm ">{{$cashier}}</span>
				</div>
			</div>
			



			
		</div>
	</div> --}}
</body>
</html>