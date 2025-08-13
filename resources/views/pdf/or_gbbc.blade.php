




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
		/* font-size: 9px; */
        margin: 38px 26px 0px 20px;
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
    table{
        border-collapse: collapse;
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
    <table style="width: 100%;">
        <tr>
            <td style="width: 46%; padding 0px; vertical-align: top;">
                <div style="text-align: right; font-size: 18px; color: red; margin: 0px; line-height: 20px;">&nbsp;</div>
                <table style="width: 100%; margin: 0px; margin-top: 3px; margin-left: 5px;; font-size: 12px !important;">
                    <tr>
                        <td style="width: 20%;">&nbsp;</td>
                        <td style="width: 50%;">{{$trans->studname}}</td>
                        <td style="width: 15%; text-align: right;">{{$trans->sid}}&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td style="width: 15%; text-align: right;">{{str_replace('GRADE', '', $gradelevel)}}</td>
                    </tr>
                </table>
                <div style="font-size:12px; margin: 0px; margin-top: 2px;">&nbsp;&nbsp;Date/Time: {{date('m/d/Y H:i:s')}}</div>
                <br/>
                <br/>
                <div class="table" style="line-height: 10px;margin: 2px; height: 95px">
                    @foreach($transdetail as $detail)
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left; font-size: 12px;">
                                {{ucwords(strtolower($detail->items))}}
                            </div>
                            <div class="table-cell" style="text-align: right; font-size: 12px;">
                                {{number_format($detail->amount,2)}}&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="text-align: right; margin: 0px 2px; font-size: 12px;">
                {{number_format(collect($transdetail)->sum('amount'),2)}}&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <br/>
                <div class="table" style="font-size: 12px;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left; width: 60%;">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: center">
                            {{auth()->user()->name}}
                            </div>
                        </div>
                </div>
            </td>
            <td style="width: 8%;">&nbsp;</td>
            <td style="width: 46%; vertical-align: top; padding-left: 8px;">
                <div style="text-align: right; font-size: 18px; color: red; margin: 0px; line-height: 20px;">&nbsp;</div>
                <table style="width: 100%; margin: 0px; margin-top: 3px; margin-left: 5px;; font-size: 12px !important;">
                    <tr>
                        <td style="width: 20%;">&nbsp;</td>
                        <td style="width: 50%;">{{$trans->studname}}</td>
                        <td style="width: 15%; text-align: right;">{{$trans->sid}}&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td style="width: 15%; text-align: right;">{{str_replace('GRADE', '', $gradelevel)}}</td>
                    </tr>
                </table>
                <div style="font-size:12px; margin: 0px; margin-top: 2px;">&nbsp;&nbsp;Date/Time: {{date('m/d/Y H:i:s')}}</div>
                <br/>
                <br/>
                <div class="table" style="line-height: 10px;margin: 2px; height: 95px">
                    @foreach($transdetail as $detail)
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left; font-size: 12px;">
                                {{ucwords(strtolower($detail->items))}}
                            </div>
                            <div class="table-cell" style="text-align: right; font-size: 12px;">
                                {{number_format($detail->amount,2)}}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="text-align: right; margin: 0px 2px; font-size: 12px;">
                {{number_format(collect($transdetail)->sum('amount'),2)}}
                </div>
                <br/>
                <div class="table" style="font-size: 12px;padding-left: 30px;">
                    <div class="table-row">
                        <div class="table-cell" style="text-align: left; width: 70%;">
                            &nbsp;
                        </div>
                        <div class="table-cell" style="text-align: center">
                        {{auth()->user()->name}}
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    {{-- <div style="width: 47%; float: left; border: 1px solid black;">&nbsp;</div>
    <div style="width: 47%; float: right; border: 1px solid black;">&nbsp;</div> --}}
    {{-- <table style="width: 100%;" border="1">
        <tr>
            <td style="vertical-align: top; width: 50%; padding: 0px;">
                <div style="line-height: 120px;">&nbsp;</div>
                <table style="width:100%; margin: 0px; line-height: 2px;">
                    <tr>
                        <td style="padding-left: 85px;font-size: 15px;">{{$trans->sid}}</td>
                        <td style=" font-size: 25px; text-align: right;">
                            {{$trans->ornum}}&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <table style="width:100%; margin: 0px;">
                    <tr>
                        <td style="padding-left: 45px;font-size: 12px;">{{$trans->studname}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 45px;font-size: 12px;">{{date('m/d/Y',strtotime($trans->transdate))}}</td>
                    </tr>
                </table>
                <br/>
                <br/>
                <br/>
                <div class="table" style="padding-left: 30px; line-height: 10px;">
                    @foreach($transdetail as $detail)
                        <div class="table-row" style=" border: 1px solid black;">
                            <div class="table-cell" style="text-align: left;">
                                {{ucwords(strtolower($detail->items))}}
                            </div>
                            <div class="table-cell" style="text-align: right;">
                                {{number_format($detail->amount,2)}}
                            </div>
                        </div>
                    @endforeach
                </div>
                <br/>
                <br/>
                <div class="table" style="font-size: 18px !important;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: right">
                            {{number_format(collect($transdetail)->sum('amount'),2)}}
                            </div>
                        </div>
                </div>
                <div class="table" style="font-size: 14px;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left; width: 60%;">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: center">
                            {{auth()->user()->name}}
                            </div>
                        </div>
                </div>
            </td>
            <td style="vertical-align: top; width: 50%; padding: 0px;">
                <div style="line-height: 120px;">&nbsp;</div>
                <table style="width:100%; margin: 0px; line-height: 2px;">
                    <tr>
                        <td style="padding-left: 95px;font-size: 15px;">{{$trans->sid}}</td>
                        <td style=" font-size: 25px; text-align: right;">
                            {{$trans->ornum}}&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <table style="width:100%; margin: 0px;">
                    <tr>
                        <td style="padding-left: 55px;font-size: 12px;">{{$trans->studname}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 55px;font-size: 12px;">{{date('m/d/Y',strtotime($trans->transdate))}}</td>
                    </tr>
                </table>
                <br/>
                <br/>
                <br/>
                <div class="table" style="font-size: 14px;padding-left: 30px; line-height: 10px;">
                    @foreach($transdetail as $detail)
                        <div class="table-row" style=" border: 1px solid black;">
                            <div class="table-cell" style="text-align: left;">
                                {{ucwords(strtolower($detail->items))}}
                            </div>
                            <div class="table-cell" style="text-align: right;">
                                {{number_format($detail->amount,2)}}
                            </div>
                        </div>
                    @endforeach
                </div>
                <br/>
                <br/>
                <div class="table" style="font-size: 18px !important;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: right">
                            {{number_format(collect($transdetail)->sum('amount'),2)}}
                            </div>
                        </div>
                </div>
                <div class="table" style="font-size: 14px;padding-left: 30px;">
                        <div class="table-row">
                            <div class="table-cell" style="text-align: left; width: 60%;">
                                &nbsp;
                            </div>
                            <div class="table-cell" style="text-align: center">
                            {{auth()->user()->name}}
                            </div>
                        </div>
                </div>
            </td>
        </tr>
    </table> --}}
</body>
</html>