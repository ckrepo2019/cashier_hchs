




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
		.table-cell { display: table-cell; padding:3px;}
	* {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 11px;
	}

@page{
	margin:5px;
	margin-left: 7px
}





</style>
	
</head>
<body>
	@php
		$totalamount = 0;
	@endphp
	<div style="width: 46%; ">
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
		<div class="table" style="font-size: 14px;">
			<div class="table-row">
				<div class="table-cell" style="text-align: right;">
					{{$trans->studname}}&nbsp;&nbsp;
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;">
			<div class="table-row">
				<div class="table-cell" style="text-align: left;">
					<span style="margin-left: 15%;">{{$trans->gradelevel}}&nbsp;&nbsp;</span>
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;">
			<div class="table-row">
				<div class="table-cell" style="text-align: left;">
					<span style="margin-left: 30%;">{{$amountstring}}&nbsp;&nbsp;</span>
				</div>
			</div>
		</div>
		<div class="table" style="font-size: 14px;">
			{{-- @foreach($transdetail as $detail)
				@php
					$totalamount += $detail->amount;
				@endphp
				<div class="table-row">
					<div class="table-cell" style="text-align: left;">
						&nbsp;
					</div>
					<div class="table-cell" style="text-align: left;">
						
					</div>
				</div>
			@endforeach --}}
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