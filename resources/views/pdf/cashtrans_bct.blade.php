




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


    #watermarkleft {
		font-family: Arial, Helvetica, sans-serif;
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
		font-family: Arial, Helvetica, sans-serif;
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
        top: 17%;
        width: 15%;
        text-align: right;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 12px;
    }
    #orright {
        position: fixed;
		/* float: right; */
        top: 17%;
        width: 65%;
        text-align: right;
        opacity: .3;
        /* transform-origin: 50% 50%; */
        z-index: -1000;
        font-size: 12px;
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
}

@page{
	margin:0px;
	margin-left: 2px
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
	<div id="orleft">
	OR {{$ornum}}
	</div>
	<div id="orright">
	OR {{$ornum}}
	</div>
<div class="body">
		<div style="width: 100%; height: 13.5cm;">

			<div style="width: 48%; height: 13.5cm; float: left;">
				
				<div style="width: 100%; height: 1.5cm;">
					<div style="margin-left: 0.8cm; margin-top: 5.2cm; width: 60%; float:left;">
						<span style="padding-left: 0.7cm">{{$studname}}</span>
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
								@foreach($items as $detail)
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
					<span style="margin-left:5.8cm ">{{auth()->user()->name}}</span>
				</div>
			</div>


			<div style="width: 48%; height: 13.5cm; float: right; margin-left: 23.8cm;">
				
				<div style="width: 100%; height: 1.5cm;">
					<div style="margin-left: 0.8cm; margin-top: 5.2cm; width: 60%; float:left;">
						{{$studname}}
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
								@foreach($items as $detail)
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
					<span style="margin-left:5.8cm ">{{auth()->user()->name}}</span>
				</div>
			</div>
			



			
		</div>
	</div>
</body>
</html>