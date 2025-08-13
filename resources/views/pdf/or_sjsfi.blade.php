

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
    table{
        border-collapse: collapse;
    }
@page{
    margin: 30px 20px;
}
body{
    margin: 0px;
    /* margin: 2px 20px; */
}
@media print 
{
   @page
   {
    /* size: 8.5in 5.5in; */
    /* size: landscape; */
  }
}



</style>
@php
if($amounttendered>$amount)
{
    $amountchange = ((float)$amounttendered)-((float)$amount);
}else{
$amountchange = 0;
}
@endphp
<body>
    <table style="width: 245px; font-size: 9.5px;">
        <tr>
            <td colspan="2">ST. JOSEPH SCHOOL</td>
        </tr>
        <tr>
            <td colspan="2">FOUNDATION, INC.</td>
        </tr>
        <tr>
            <td colspan="2">TEL# 991-6675 Fax# 992-2231</td>
        </tr>
        <tr>
            <td colspan="2">TIN 001-747-398-000 NON VAT</td>
        </tr>
        <tr>
            <td colspan="2">--- OR #: {{$trans->ornum}} ---</td>
        </tr>
        <tr>
            <td colspan="2">OR Date: {{date('m/d/Y h:i:s A',strtotime($trans->transdate))}}</td>
        </tr>
        <tr>
            <td colspan="2">Student#: {{$trans->sid}}</td>
        </tr>
        <tr>
            <td colspan="2">Student Name: {{$trans->studname}}</td>
        </tr>
        <tr>
            <td colspan="2">Course: {{$gradelevel}} - {{$section}}</td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom: 1px dashed black;"></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom: 5px;"></td>
        </tr>
        @foreach($transdetail as $detail)
            <tr>
                <td style="width: 70%;">{{ucwords(strtolower($detail->items))}}</td>
                <td style="text-align: right;">{{number_format($detail->amount,2)}}</td>
            </tr>
        @endforeach  
        <tr>
            <td colspan="2" style="padding-top: 5px;"></td>
        </tr>
        <tr>
            <td colspan="2" style="border-top: 1px dashed black;"></td>
        </tr>
        <tr>
            <td style="text-align: right;">TOTAL PAID</td>
            <td style="text-align: right;">{{number_format($amount,2)}}</td>
        </tr>
        <tr>
            <td style="text-align: right;">AMOUNT RECEIVED</td>
            <td style="text-align: right;">{{number_format($amounttendered,2)}}</td>
        </tr>
        <tr>
            <td style="text-align: right;">CHANGE</td>
            <td style="text-align: right;">{{number_format($amountchange,2)}}</td>
        </tr>
        <tr>
            <td style="text-align: right;">OUT. BALANCE</td>
            <td style="text-align: right;"></td>
        </tr>
        <tr>
            <td colspan="2">Cashier: {{auth()->user()->name}}</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px dashed black;">&nbsp;</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 5px 0px;">THIS IS YOUR OFFICIAL RECEIPT</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style="border-top: 1px dashed black;">&nbsp;</td>
            <td></td>
        </tr>
    </tr>
    </table>
</body>
</html>