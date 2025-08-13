




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
	/* .body{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 9px;
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
  padding: 0.20rem;
  vertical-align: top;
  border-top: 0px solid #dee2e6;
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
} */

#orleft {
        position: fixed;
		/* float: right; */
        top: 1%;
        width: 10%;
        text-align: left;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 12px;
    }
    #orright {
        position: fixed;
		/* float: right; */
        top: 1%;
        width: 55%;
        text-align: right;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 12px;
    }
@page{
	margin:5%;
	margin-top:8%;
	/* margin-left: 2px */
}





</style>
	
</head>
<body>
	{{-- <br> --}}
	<br>
	<br>
	<div class="table" >
		<div class="table-row">
			<div class="table-cell" style="width: 46%; border: none; height: 280px;">
				<div id="orleft">
				OR {{$trans->ornum}}
				</div>
				<div class="table" style="margin-left: 45px;">
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;">
							{{$trans->studname}}
						</div>
						<div class="table-cell" style="width: 30%; border: none; text-align: right;">
							&nbsp;&nbsp;&nbsp; <span>{{date('M d, Y',strtotime($datenow))}}</span>
						</div>
					</div>
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;">
							<span  style="margin-left: 20px;">{{$gradelevel}}</span>
						</div>
						<div class="table-cell" style="width: 30%; border: none; text-align: right;">
						</div>
					</div>
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;">
							{{$section}}
						</div>
						<div class="table-cell" style="width: 30%; border: none; text-align: right;">
						</div>
					</div>
				</div>
				<br>
				<br>
				<br>
				{{-- <br/> --}}
				<div class="table">
					@php
						$totalamount=0;
					@endphp
					@foreach($transdetail as $detail)
						@php
						$totalamount += $detail->amount
						@endphp
						{{-- <tr>
							<td>{{$detail->items}}</td>
							<td style="text-align: right">{{number_format($detail->amount, 2)}}</td>
						</tr> --}}
						<div class="table-row">
							<div class="table-cell" style="width: 70%;">
								<span style="margin-left: 15px;">{{$detail->items}}</span>
							</div>
							<div class="table-cell" style="width: 30%;">
								<span style="margin-left: 10px;">{{number_format($detail->amount, 2)}}</span>
							</div>
						</div>
					@endforeach
				</div>
			</div>
			<div class="table-cell" style="width: 8%;text-align:center; border: none;height: 280px;">&nbsp;</div>
			<div class="table-cell" style="width: 46%; border: none; height: 280px;">
				<div id="orright">
				OR {{$trans->ornum}}
				</div>
				<div class="table" style="margin-left: 45px;">
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;">
							{{$trans->studname}}
						</div>
						<div class="table-cell" style="width: 30%; border: none; text-align: right;">
							&nbsp;&nbsp;&nbsp; <span>{{date('M d, Y',strtotime($datenow))}}</span>
						</div>
					</div>
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;">
							<span  style="margin-left: 20px;">{{$gradelevel}}</span>
						</div>
						<div class="table-cell" style="width: 30%; border: none; text-align: right;">
						</div>
					</div>
					<div class="table-row">
						<div class="table-cell" style="width: 70%; border: none;">
							{{$section}}
						</div>
						<div class="table-cell" style="width: 30%; border: none; text-align: right;">
						</div>
					</div>
				</div>
				<br>
				<br>
				<br>
				{{-- <br/> --}}
				<div class="table">
					@php
						$totalamount=0;
					@endphp
					@foreach($transdetail as $detail)
						@php
						$totalamount += $detail->amount
						@endphp
						{{-- <tr>
							<td>{{$detail->items}}</td>
							<td style="text-align: right">{{number_format($detail->amount, 2)}}</td>
						</tr> --}}
						<div class="table-row">
							<div class="table-cell" style="width: 70%;">
								<span style="margin-left: 15px;">{{$detail->items}}</span>
							</div>
							<div class="table-cell" style="width: 30%;">
								<span style="margin-left: 10px;">{{number_format($detail->amount, 2)}}</span>
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
							<span style="margin-left: 10px;">{{number_format($totalamount, 2, '.',',')}}</span>
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
							<span style="margin-left: 20px;">{{number_format($totalamount, 2, '.',',')}}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>