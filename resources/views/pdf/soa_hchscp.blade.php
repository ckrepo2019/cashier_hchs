<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Statement of Account</title>

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

	.schoolname{
		font-family: Times New Roman !important;
		font-weight: bold;
		font-size: 24px !important;	
	}

	.address{
		font-weight: bold !important;
		font-size: 10px !important;
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

.text-bold {
  font-weight: bold !important;
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

.pl-5{
	text-indent: 3rem !important;
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
	        $text = "Date Printed: " . '{{date_format(date_create(App\CashierModel::getServerDateTime()), 'm-d-Y h:i A')}}';
	        $font = null;
	        $size = 7;
	        $color = array(0,0,0);
	        $word_space = 0.0;  //  default
	        $char_space = 0.0;  //  default
	        $angle = 0.0;   //  default
	        $pdf->page_text($x, $y, $text, $font, $size, $color);
	    }
	    

	</script>

	@php
		$schoolinfo = DB::table('schoolinfo')
			->first();

		$schoolname = $schoolinfo->schoolname;
		$schooladdress = $schoolinfo->address;
	@endphp

	<div class="body">
		<div style="display: inline-block;">
			<div style="">
				
				<table>
					<tr>
						<td ><img  src="{{$schoolinfo->essentiellink . '/' . $schoolinfo->picurl}}" alt="" width="100"></td>
						<td class="text-center" style="width: 6in;">
							<span class="schoolname" style="font-size:24px;">{{$schoolinfo->schoolname}}</span><br>
							<span style="font-size: 10px">{{$schoolinfo->address}}</span><br>
							<span>
								By This Sign You Shall Conquer <br>In God's Mercy, We Serve With Joy
							</span>
						</td>
					</tr>
				</table>
			</div>
			<div style="margin-top: 10px;">
				<table>
					<tr>
						<td style="width: 60px; font-weight: bold; font-size: 11px;">Name</td>
						<td class="text-center" style="font-weight: bold; font-size: 12px; border-bottom: solid; width: 260px;">
							{{$studname}}
						</td>
						<td width="40px">&nbsp;</td>
						<td style="width: 70px; font-weight: bold; font-size: 11px;">Section</td>
						<td class="text-center" style="font-size: 12px; border-bottom: solid; width: 160px;">
							{{$section}}
						</td>
					</tr>
					<tr>
						<td style="width: 60px; font-weight: bold; font-size: 11px;">Grade</td>
						<td class="text-center" style="font-weight: bold; font-size: 12px; border-bottom: solid; width: 260px;">
							{{$levelname}}
						</td>
						<td width="40px">&nbsp;</td>
						<td style="width: 70px; font-weight: bold; font-size: 11px;">School Year</td>
						<td class="text-center" style="font-size: 12px; border-bottom: solid; width: 160px;">
							{{$sy}}
						</td>
					</tr>
				</table>
			</div>
		</div>

		<table>
			<tr>
				<td>
					<div style="border: solid 0.5px; width:300px; height: 7in;">
						<table width="100%" style="font-size: 11px;">
							<tr>
								<td style="width: 100%; text-align: center; font-weight: bold; font-size: 10px;">Statement of Account</td>
							</tr>
							<tr>
								<td height="30px">&nbsp;</td>
							</tr>
						</table>
						<table width="100%" style="font-size: 11px;">
							<tr>
								<td style="width: 130px !important;">Tuition</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;">{{$tuition}}</td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Other Fees</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;">{{$oth}}</td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Textbook</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;">{{$bookamount}}</td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Workbook</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;"></td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Old Account</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;">{{$oldaccount}}</td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">P.E Uniform</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;"></td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Enrichment</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;"></td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Remedial</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;"></td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Summer</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;"></td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" style="border-bottom:solid 1px; height: 20px;"></td>
							</tr>
							<tr>
								<td style="width: 130px !important; font-weight: bold;">Sub-Total</td>
								<td class="text-right" style="width: 50px; "></td>
								<td class="text-right text-bold" style="width: 50px;">{{$subtotal}}</td>
							</tr>

							<tr>
								<td colspan="3" style="height: 20px;"></td>
							</tr>

							<tr>
								<td style="width: 130px !important;">ESC</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;">{{$esc}}</td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">10% DISC.</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;">{{$discount}}</td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Alumni</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;"></td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Academic</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;"></td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">Rebisco</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;"></td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="width: 130px !important;">VCMA</td>
								<td class="text-right" style="border-bottom: solid .5px; width: 50px;"></td>
								<td class="text-right" style="width: 50px;">&nbsp;</td>
							</tr>

							<tr>
								<td colspan="3" style="border-bottom:solid 1px; height: 20px;"></td>
							</tr>

							<tr>
								<td style="width: 130px !important; font-weight: bold;">Less</td>
								<td class="text-right" style="width: 50px; "></td>
								<td class="text-right" style="width: 50px;">{{$less}}</td>
							</tr>

							<tr>
								<td colspan="3" style="height: 20px;"></td>
							</tr>

							<tr>
								<td style="width: 130px !important; font-weight: bold;">TOTAL PAYABLE</td>
								<td class="text-right" style="width: 50px; "></td>
								<td class="text-right" style="width: 50px; border-bottom: double;">{{$totalpayable}}</td>
							</tr>

							<tr>
								<td colspan="3" style="height: 20px;"></td>
							</tr>

							<tr>
								<td style="width: 130px !important; font-weight: bold;">MONTHLY PAYABLE</td>
								<td class="text-right" style="width: 50px; "></td>
								<td class="text-right" style="width: 50px; border-bottom: double;">{{$monthlypayable}}</td>
							</tr>

						</table>
					</div>			
				</td>
				<td>
					<div style="border: solid 1px; width:380px; height: 7in;">
						<table width="100%" style="font-size: 11px;">
							<tr>
								<td style="width: 100%; text-align: center; font-weight: bold; font-size: 10px;">Payment Breakdown</td>
							</tr>
							<tr>
								<td height="0px">&nbsp;</td>
							</tr>
						</table>
						<table width="100%" style="font-size: 11px;">
							<tr class="text-bold">
								<td>Date Paid</td>
								<td>OR #</td>
								<td class="text-center">Amount</td>
								<td class="text-center">Balance</td>
								<td>Remarks</td>
							</tr>
							{!!$paylist!!}
						</table>
					</div>			
				</td>
			</tr>
		</table>
		<div style="bottom: 1.4in; left: 10px; position: absolute;">
			<b>Note:</b><br>
			P.E Uniform is not included<br>
			Subject to change
		</div>
		<div style="bottom: 100px; left: 10px; position: absolute; font-weight: bold;">
			*This is free. If lost, a duplicate copy will cost PHP 100.00.<br>
			* Please present this statement every time you pay.
		</div>

		<div class="table-responsive" style="margin-top: 30px; position: absolute; margin-left: 480px; width: 180px;">
			<div class="text-center text-bold" style="border-bottom: solid .5px">
				{{auth()->user()->name}}<br>
			</div>
			<div class="text-center">
				Cashier
			</div>

		</div>

	</div>
</body>
</html>