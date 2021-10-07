<!DOCTYPE html>
<html>
<head charset=utf-8>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .container{
            max-width: 90%;
            text-align: center;
        }
        .header{
            width: 100%;
            margin: auto;
            text-align: center;
        }
        .header .header-left{
            width: 30%;
            font-size: 9.5px;
        }
        .header .header-left img{
            width: 60px;
            margin: 0;
        }
        .header .header-right{
            line-height: 5px;
            font-size: 13px;
            font-weight: bold;
            vertical-align: text-top;
        }
        .day-time{
            text-align: right !important;
            float: right !important;
            display: block;
        }.day-time p{
            display: inline;
            text-align: end !important;
            font-size: 11.5px;
            font-style: italic;
        }
        .form{
            margin-top: 50px;
            font-size: 21.5px;
            line-height: 15px;
        }
        .send{
            font-size: 13px;

            padding-right: 100px;
        }
        .content{
            text-align: start !important;
            padding-top: 20px;
            vertical-align: text-top;
            font-size: 13px;
        }
        .content-table{
            width: 100%;
            text-align: left;
        }
        .content-left{
            width: 40%;
        }
        .content-right{
            padding-left: 10px;
            vertical-align: text-top;
        }
        .content i{
            display: block !important;
            margin-top: 30px;
            font-weight: bold;
        }
        .reason{
            margin: 0;
            padding: 0;
        }
        .footer{
            margin-top: 20px;
            width: 100%;
            text-align: center;
        }
        .table-footer{
            width: 100%;
        }
        .table-footer-left, .table-footer-center, .table-footer-right{
            text-align: center;
        }
        .table-footer-left, .table-footer-center, .table-footer-right p{
            line-height: 10px;
        }
        .page_break { page-break-after: always; }
        .page_break:last-child { page-break-after: avoid; }
    </style>
    <title>Export PDF</title>
</head>
<body class="container">
    @if (isset($data))
      @foreach ($data as $item => $datapdf)
    <div class="table-borderless page_break row">
        <table class="header">
            <tbody>
                <tr>
                    <td class="header-left" >
                        <img src="uploads/logo/logo-bw-HQgroup.png" alt="Logo">
                        <p><strong>CÔNG TY CỔ PHẦN TRUYỀN THÔNG VÀ DỊCH VỤ HQ GROUP</strong></p>
                        <p>Số: ....... /ĐNTƯ-HQG</p>
                    </td>
                    <td class="header-right">
                        <p>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                        <p>Độc lập - Tự do - Hạnh phúc</p>
                        <p>------o0o------</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="day-time"><p>{{$datapdf->companyAsset->city}}, Ngày {{$datapdf->updated_at ? date('d', strtotime($datapdf->updated_at)) : date('d', strtotime($datapdf->created_at))}} tháng {{$datapdf->updated_at ? date('m', strtotime($datapdf->updated_at)) : date('m', strtotime($datapdf->created_at))}} năm {{$datapdf->updated_at ? date('Y', strtotime($datapdf->updated_at)) : date('Y', strtotime($datapdf->created_at))}}</p></div>
        <div class="head-line">
            <p class="form"><strong>ĐỀ NGHỊ TẠM ỨNG</strong></p>
            <p class="send"><strong>Kính gửi: </strong> - Ban giám đốc công ty <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Bộ phận kế toán</p>
        </div>
        <div class="content">
            <div><strong>- Họ và tên: </strong><span>{{$datapdf->employeeAsset->last_name}} {{$datapdf->employeeAsset->first_name}}</span></div>
            <div>
            <table class="content-table">
                <tbody>
                    <tr>
                        <td class="content-left">
                            <strong>- Chức vụ: &nbsp;</strong><span>{{$designation[$item]}}</span>
                        </td>
                        <td class="content-right">
                            <span>- Bộ phận: &nbsp;</span><span>{{$department[$item]}}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div><i>Tôi làm đơn này, kính đề nghị Ban Giám Đốc cho tôi tạm ứng:</i></div>
            <div>
                <table class="content-table">
                <tbody>
                    <tr>
                        <td class="content-left">
                            <strong>- Số tiền: &nbsp;</strong><strong>{{$datapdf->advance_amount}} đ</strong>
                        </td>
                        <td class="content-right">
                            <span>- Bằng chữ: &nbsp;</span><span>{{$word_money[$item]}}</span>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
            <p class="reason"><strong>- Lý do: &nbsp;&nbsp;&nbsp;&nbsp;</strong><span>{{$datapdf->reason}}</span></p>
            <div><strong>- Thời Hạn Thanh Toán:  </strong><span>Thanh toán vào T{{date('m/Y', strtotime($datapdf->month_year))}}</span></div>
            <div><strong>- Hình Thức Thanh Toán:  </strong><span>Trừ vào lương T &nbsp;&nbsp;/{{$datapdf->updated_at ? date('Y', strtotime($datapdf->updated_at)) : date('Y', strtotime($datapdf->created_at))}}</span></div>
            <div><i>Tôi xin chân thành cảm ơn!</i></div>
        </div>
        <div class="footer">
            <table class="table-footer">
                <tbody>
                    <tr>
                        <td class="table-footer-left">
                            <p><strong>Ban Giám Đốc</strong></p>
                            <p><i>(Ký & ghi rõ họ tên)</i></p>
                        </td>
                        <td class="table-footer-center">
                            <p><strong>Phòng Kế Toán</strong></p>
                            <p><i>(Ký & ghi rõ họ tên)</i></p>
                        </td>
                        <td class="table-footer-right">
                            <p><strong>Người Đề Nghị</strong></p>
                            <p><i>(Ký & ghi rõ họ tên)</i></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
    @endif
</body>
</html>
