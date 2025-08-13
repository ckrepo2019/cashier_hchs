




<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title></title>

        <style>
            * {
                font-family: Arial, Helvetica, sans-serif;
            }
            table{
                border-collapse: collapse;
            }
        </style>
        
    </head>
    <body>
        <table style="width: 100%; margin-top: 30px; font-size: 14px; table-layout: fixed;">
            <tr>
                <td style="width: 90cm;">
                    <div style="width: 100%; text-align: right;">{{date('m/d/Y')}}</div>
                    <div style="width: 100%; text-align: right;">{{$trans->studname}}</div>
                    <div style="width: 100%; text-align: right;">{{$gradelevel}} - {{$section}}</div>
                    <div style="width: 100%; text-align: right; padding-left: 50px;"> {{ucwords(strtolower($amountstring))}} &nbsp;&nbsp;&nbsp; {{number_format($amount,2)}}</div>   
                    <br/>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 90cm; height: 290px; vertical-align: top;">
                    <br/>
                    <table style="width: 100%;">
                        @foreach($transdetail as $detail)
                            <tr>
                                <td style="font-size: 12px; width: 30%;">&nbsp;</td>
                                <td style="padding-right: 7px; font-size: 10.5px; text-align: left; width: 50%;">{{ucwords(strtolower($detail->items))}}</td>
                                <td style="padding-right: 7px; font-size: 10.5px; text-align: right; width: 20%;">{{number_format($detail->amount,2)}}</td>
                            </tr>
                        @endforeach  
                    </table>   
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: right;">
                    {{number_format($amount,2)}}&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td style="text-align; right;"></td>
            </tr>
        </table>
    </body>
</html>