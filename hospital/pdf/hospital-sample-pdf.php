<!DOCTYPE html>
<html lang="ja">
<head>
    <title>病院の詳細</title>
    <link href="<?php echo $baseUrl; ?>assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap');

        body {
            font-family: 'Noto Sans JP', sans-serif;
        }

        .main-detail {
            padding: 25px 20px;
        }

        .title {
            margin-bottom: 50px;
        }

        .hospital-name {
            color: #505458;
            font-weight: bolder;
            font-size: 35px;
        }

        .cancer-name {
            color: #505458;
            font-weight: bold;
            font-size: 20px;
        }

        .table th, .table td {
            line-height: 25px !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            white-space: normal !important;
        }

        .table-info th {
            width: 30%;
        }

        .table-info td {
            width: 70%;
        }

        .table-treatment th {
            width: 20%;
        }

        .table-treatment td {
            width: 80%;
        }

        .bg-secondary {
            background-color: #71b6f9;
        }

        .bg-warning {
            background-color: #f9c851 !important;
        }

        .page {
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 10%;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("<?php echo $baseUrl; ?>assets/images/common/logo.png");
            background-size: 460px 120px;;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.1;
        }

        a {
            color: #337ab7;
            text-decoration: none;
        }
    </style>
</head>
<body>
<main>
    <div class="container-fluid">
        <div class="main-detail">
            <div class="page" style="page-break-after: always;">
                <div class="watermark"></div>
                <div class="title">
                    <p class="text-center cancer-name">肝細胞がん（肝細胞癌）</p>
                    <p class="text-center hospital-name">岩手医科大学附属病院</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="table-title col-xs-8 bg-primary">治療実績 (直近3年平均)</th>
                            <th class="table-title col-xs-4 bg-primary">実績値</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="criteria">年間入院患者数 (2020年)</td>
                            <td class="center-icon">492人</td>
                        </tr>
                        <tr>
                            <td class="criteria">年間新規患者数 (2020年)</td>
                            <td class="center-icon">184人</td>
                        </tr>
                        <tr>
                            <td class="criteria">5年後生存率数 (2020年)</td>
                            <td class="center-icon">79.63</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-info">
                        <thead>
                        <tr class="border-top border-bottom bg-primary">
                            <th colspan="2" class="table-title">医療機関情報</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>医療機関名</th>
                            <td>岩手医科大学附属病院</td>
                        </tr>
                        <tr>
                            <th>住所</th>
                            <td>019-613-7111</td>
                        </tr>
                        <tr>
                            <th>代表電話番号</th>
                            <td>紫波郡矢巾町医大通二丁目1番1号</td>
                        </tr>
                        <tr>
                            <th>公式HP</th>
                            <td><a target="_blank" href="https://www.hosp.iwate-med.ac.jp/yahaba/">https://www.hosp.iwate-med.ac.jp/yahaba/</a></td>
                        </tr>
                        <tr>
                            <th>がん相談支援センターURL</th>
                            <td><a target="_blank" href="https://www.hosp.iwate-med.ac.jp/hospital/gancenter/service/sien.html">https://www.hosp.iwate-med.ac.jp/hospital/gancenter/service/sien.html</a></td>
                        </tr>
                        <tr>
                            <th>特別室</th>
                            <td><a target="_blank" href="https://www.hosp.iwate-med.ac.jp/hospital/gancenter/service/sien.html">https://www.hosp.iwate-med.ac.jp/hospital/gancenter/service/sien.html</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="page">
                <div class="watermark"></div>
                <div class="table-responsive">
                    <table class="table table-info table-bordered table-treatment">
                        <thead>
                        <tr class="border-top border-bottom bg-primary">
                            <th colspan="2" class="table-title">提供する治療情報</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>がん診療拠点区分</th>
                            <td>都道府県がん診療連携拠点病院</td>
                        </tr>
                        <tr>
                            <th>がんゲノム病院区分</th>
                            <td>がんゲノム医療連携病院</td>
                        </tr>
                        <tr>
                            <th>集学的治療体制の状況</th>
                            <td><span class="badge bg-secondary">あり</span></td>
                        </tr>
                        <tr>
                            <th>名医の在籍状況</th>
                            <td>
                                <p><span class="badge bg-secondary">あり</span></p>
                                <p>外科：坂本 直人</p>
                                <p>放射線科：牧元 信夫</p>
                            </td>
                        </tr>
                        <tr>
                            <th>先進医療の提供状況</th>
                            <td>
                                <span class="badge bg-warning">なし</span>
                            </td>
                        </tr>
                        <tr>
                            <th>特別な治療の提供状況</th>
                            <td>
                                <span class="badge bg-warning">なし</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
