<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title></title>

	<style>
	.body{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 10px;
	}
	hr {
	  box-sizing: content-box;
	  height: 0;
	  overflow: visible;
	}

	.table {
	  width: 100%;
	  margin-bottom: 1rem;
	  color: #212529;
	}





.table th,
.table td {
  padding: 0.25rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}

.table thead th {
  vertical-align: bottom;
  border-bottom: 0px solid #dee2e6;
}

.table tbody + tbody {
  border-top: 2px solid #dee2e6;
}

.table-sm th,
.table-sm td {
  padding: 0.3rem;
}

.table-bordered {
  border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6;
}

.table-bordered thead th,
.table-bordered thead td {
  border-bottom-width: 0px;
}

.table-borderless th,
.table-borderless td,
.table-borderless thead th,
.table-borderless tbody + tbody {
  border: 0;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, 0.05);
}

.table-hover tbody tr:hover {
  color: #212529;
  background-color: rgba(0, 0, 0, 0.075);
}

.table-primary,
.table-primary > th,
.table-primary > td {
  background-color: #b8daff;
}

.table-primary th,
.table-primary td,
.table-primary thead th,
.table-primary tbody + tbody {
  border-color: #7abaff;
}

.table-hover .table-primary:hover {
  background-color: #9fcdff;
}

.table-hover .table-primary:hover > td,
.table-hover .table-primary:hover > th {
  background-color: #9fcdff;
}

.table-secondary,
.table-secondary > th,
.table-secondary > td {
  background-color: #d6d8db;
}

.table-secondary th,
.table-secondary td,
.table-secondary thead th,
.table-secondary tbody + tbody {
  border-color: #b3b7bb;
}

.table-hover .table-secondary:hover {
  background-color: #c8cbcf;
}

.table-hover .table-secondary:hover > td,
.table-hover .table-secondary:hover > th {
  background-color: #c8cbcf;
}

.table-success,
.table-success > th,
.table-success > td {
  background-color: #c3e6cb;
}

.table-success th,
.table-success td,
.table-success thead th,
.table-success tbody + tbody {
  border-color: #8fd19e;
}

.table-hover .table-success:hover {
  background-color: #b1dfbb;
}

.table-hover .table-success:hover > td,
.table-hover .table-success:hover > th {
  background-color: #b1dfbb;
}

.table-info,
.table-info > th,
.table-info > td {
  background-color: #bee5eb;
}

.table-info th,
.table-info td,
.table-info thead th,
.table-info tbody + tbody {
  border-color: #86cfda;
}

.table-hover .table-info:hover {
  background-color: #abdde5;
}

.table-hover .table-info:hover > td,
.table-hover .table-info:hover > th {
  background-color: #abdde5;
}

.table-warning,
.table-warning > th,
.table-warning > td {
  background-color: #ffeeba;
}

.table-warning th,
.table-warning td,
.table-warning thead th,
.table-warning tbody + tbody {
  border-color: #ffdf7e;
}

.table-hover .table-warning:hover {
  background-color: #ffe8a1;
}

.table-hover .table-warning:hover > td,
.table-hover .table-warning:hover > th {
  background-color: #ffe8a1;
}

.table-danger,
.table-danger > th,
.table-danger > td {
  background-color: #f5c6cb;
}

.table-danger th,
.table-danger td,
.table-danger thead th,
.table-danger tbody + tbody {
  border-color: #ed969e;
}

.table-hover .table-danger:hover {
  background-color: #f1b0b7;
}

.table-hover .table-danger:hover > td,
.table-hover .table-danger:hover > th {
  background-color: #f1b0b7;
}

.table-light,
.table-light > th,
.table-light > td {
  background-color: #fdfdfe;
}

.table-light th,
.table-light td,
.table-light thead th,
.table-light tbody + tbody {
  border-color: #fbfcfc;
}

.table-hover .table-light:hover {
  background-color: #ececf6;
}

.table-hover .table-light:hover > td,
.table-hover .table-light:hover > th {
  background-color: #ececf6;
}

.table-dark,
.table-dark > th,
.table-dark > td {
  background-color: #c6c8ca;
}

.table-dark th,
.table-dark td,
.table-dark thead th,
.table-dark tbody + tbody {
  border-color: #95999c;
}

.table-hover .table-dark:hover {
  background-color: #b9bbbe;
}

.table-hover .table-dark:hover > td,
.table-hover .table-dark:hover > th {
  background-color: #b9bbbe;
}

.table-active,
.table-active > th,
.table-active > td {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover > td,
.table-hover .table-active:hover > th {
  background-color: rgba(0, 0, 0, 0.075);
}

.table .thead-dark th {
  color: #fff;
  background-color: #343a40;
  border-color: #454d55;
}

.table .thead-light th {
  color: #495057;
  background-color: #e9ecef;
  border-color: #dee2e6;
}

.table-dark {
  color: #fff;
  background-color: #343a40;
}

.table-dark th,
.table-dark td,
.table-dark thead th {
  border-color: #454d55;
}

.table-dark.table-bordered {
  border: 0;
}

.table-dark.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(255, 255, 255, 0.05);
}

.table-dark.table-hover tbody tr:hover {
  color: #fff;
  background-color: rgba(255, 255, 255, 0.075);
}

@media (max-width: 575.98px) {
  .table-responsive-sm {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-sm > .table-bordered {
    border: 0;
  }
}

@media (max-width: 767.98px) {
  .table-responsive-md {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-md > .table-bordered {
    border: 0;
  }
}

@media (max-width: 991.98px) {
  .table-responsive-lg {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-lg > .table-bordered {
    border: 0;
  }
}

@media (max-width: 1199.98px) {
  .table-responsive-xl {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-xl > .table-bordered {
    border: 0;
  }
}

.table-responsive {
  display: block;
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table-responsive > .table-bordered {
  border: 0;
}

.text-left {
  text-align: left !important;
}

.text-right {
  text-align: right !important;
}

.text-center {
  text-align: center !important;
}

.text-danger {
  color: #dc3545 !important;
}

.text-warning {
  color: #ffc107 !important;
}

.text-info {
  color: #17a2b8 !important;
}

.text-success {
  color: #28a745 !important;
}

.text-primary {
  color: #007bff !important;
}
.page-break {
    page-break-inside: avoid;
    page-break-after: auto; 
}





</style>
	
</head>
<body>
	<script type="text/php">
    
	    if (isset($pdf)) {
	        $x = 530;
	        $y = 980;
	        $text = "Page {PAGE_NUM} of {PAGE_COUNT} pages";
	        $font = null;
	        $size = 7;
	        $color = array(0,0,0);
	        $word_space = 0.0;  //  default
	        $char_space = 0.0;  //  default
	        $angle = 0.0;   //  default
	        $pdf->page_text($x, $y, $text, $font, $size, $color);
	    }
	    
	    	if (isset($pdf)) {
	        $x = 34;
	        $y = 980;
	        $text = "Date Printed: " . '{{App\CashierModel::getServerDateTime()}}';
	        $font = null;
	        $size = 7;
	        $color = array(0,0,0);
	        $word_space = 0.0;  //  default
	        $char_space = 0.0;  //  default
	        $angle = 0.0;   //  default
	        $pdf->page_text($x, $y, $text, $font, $size, $color);
	    }
	    
	@php
		$schoolinfo = db::table('schoolinfo')
			->first();

		$schoolname = $schoolinfo->schoolname;
		$schooladdress = $schoolinfo->address;
		$trxtotal = 0;
		$balance = 0;
		$schedname = '';
	@endphp
	</script>
	<div class="body">
		<div style="width: 100%; margin-top: -10px;">
			<div class="text-center">
				<h3>{{$schoolname}}</h3>
			</div>
			<div class="text-center" style="margin-top: -27px;">
				<p style="">{{$schooladdress}}</p>
			</div>
			<div class="text-center" style="margin-top: -10px;">
				<b>STUDENT ASSESSMENT</b>
			</div>
			<div class="text-center">
				
			</div>
		</div>
		<br><br>

		<table cellspacing="0" width="100%">
			<tr>
				<td style="font-weight: bold; font-size: 12px; width: 100px; ">Name: </td>
				<td style="font-weight: bold; font-size: 12px; ">{{$stud->sid}} - {{$stud->lastname}}, {{$stud->firstname}} {{$stud->middlename}}</td>
			</tr>
			<tr>
				<td style="font-weight: bold; font-size: 12px; width: 100px;">Grade Level:</td>
				<td style="font-weight: bold; font-size: 12px;">{{$einfo[0]->levelname}} - {{$einfo[0]->sectionname}}</td>
			</tr>
		</table>

		<br><br>

		<div style="font-weight: bold;">
			Student Ledger
		</div>
		<table class="table">
			<tr>
				<th>DATE</th>
				<th>PARTICULARS</th>
				<th>BILLING</th>
				<th>PAYMENTS</th>
				<th>BALANCE</th>
			</tr>
			@foreach($ledger as $led)
				@php
					$balance = $balance + $led->amount - $led->payment;
				@endphp
				<tr>
					<td>{{date_format(date_create($led->createddatetime), 'm-d-Y')}}</td>
					<td>{{$led->particulars}}</td>
						@if($led->amount > 0)
							<td class="text-right">{{number_format($led->amount, 2)}}</td>
						@else
							<td></td>
						@endif
						@if($led->payment > 0)
								<td class="text-right">{{number_format($led->payment, 2)}}</td>
							@else
								<td></td>
							@endif
						@if($balance > 0)
							<td class="text-right">{{number_format($balance, 2)}}</td>
						@else
							<td></td>
						@endif
				</tr>
			@endforeach
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="6" style="font-weight: bold;" class="text-right"><u>TOTAL BALANCE: {{number_format($balance, 2)}}</u></td>
			</tr>
		</table>

		<div style="width: 100%; border-bottom: solid 1px;">
			&nbsp;
		</div>

		<br><br>
		<div style="font-weight: bold;">
			Student Assessment
		</div>

		<table class="table">
			<tr>
				<th>DUE</th>
				<th>DESCRIPTION</th>
				<th>AMOUNT</th>
				<th>PAYMENT</th>
				<th>BALANCE</th>
			</tr>
			@foreach($paysched as $sched)
				@php
					if($sched->duedate == null)
					{
						$schedname = 'UPON ENROLLMENT';
					}
					else
					{
						$schedname = strtoupper(date_format(date_create($sched->duedate), 'F') . ' PAYABLE');
					}
				@endphp
				<tr>
					<td>{{date_format(date_create($sched->duedate), 'm-d-Y')}}</td>
					<td>{{$schedname}}</td>
						@if($sched->amount > 0)
							<td class="text-right">{{number_format($sched->amount, 2)}}</td>
						@else
							<td></td>
						@endif
						@if($sched->payment > 0)
								<td class="text-right">{{number_format($sched->payment, 2)}}</td>
							@else
								<td></td>
							@endif
						@if($sched->balance > 0)
							<td class="text-right">{{number_format($sched->balance, 2)}}</td>
						@else
							<td></td>
						@endif
				</tr>
			@endforeach
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="6" style="font-weight: bold;" class="text-right"><u>TOTAL BALANCE: {{number_format($balance, 2)}}</u></td>
			</tr>
		</table>
		
		<br><br><br>

		@php
			$sig = db::table('finance_sigs')
				->where('id', 4)
				->first();
		@endphp

		<table style="width: 100%;">
			<tr>
				<td style="width: 180px;">
					<div style="width: 100%;">
						{{$sig->title_1}}
					</div>
					<br><br><br>
					<div class="text-center" style="width: 100%; border-bottom: 1px solid; font-weight: bold;">
						{{strtoupper($sig->sig_1)}}
					</div>
					<div class="text-center" style="width: 100%;">
						{{$sig->designation_1}}
					</div>			
				</td>
				<td style="width: 130px;">
					&nbsp;
				</td>
				<td style="width: 180px;">
					<div style="width: 100%;">
						{{$sig->title_2}}
					</div>
					<br><br><br>
					<div class="text-center" style="width: 100%; border-bottom: 1px solid; font-weight: bold;">
						{{strtoupper($sig->sig_2)}}&nbsp;
					</div>
					<div class="text-center" style="width: 100%;">
						{{$sig->designation_2}}
					</div>			
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
			

	</div>
</body>
</html>