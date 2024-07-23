<!DOCTYPE html>
<html lang="ja">
<head>
    <title><?php echo $hospitalName; ?></title>
    <link href="<?php echo $baseUrl; ?>assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap');

        body {
            font-family: 'Noto Sans JP', sans-serif;
        }

        .main-detail {
            padding: 25px 20px;
        }

        .title-print {
            margin-bottom: 50px;
        }

        .hospital-name {
            color: #505458;
            font-weight: bolder;
            font-size: 32px;
        }

        .cancer-name {
            color: #505458;
            font-weight: bold;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td, th {
            line-height: 25px !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }

        .table-info th {
            width: 30%;
        }

        .table-info td {
            width: 70%;
        }

        .table-summary {
            margin-bottom: 30px;
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
                <div class="title-print">
                    <p class="text-center cancer-name"><?php echo $cancerName; ?></p>
                    <p class="text-center hospital-name"><?php echo $hospitalName; ?></p>
                </div>
                <table class="table table-summary table-bordered">
                    <thead>
                    <tr>
                        <th class="table-title col-xs-8 bg-primary">治療実績 (直近3年平均)</th>
                        <th class="table-title col-xs-4 bg-primary">実績値</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="criteria">年間入院患者数 <?php echo $yearSummaryDpc ? '(' .$yearSummaryDpc .'年)' : ''?></td>
                        <td class="center-icon"><?php echo ($avgDpc ? $avgDpc . '人' : '-') ?></td>
                    </tr>
                    <tr>
                        <td class="criteria">年間新規患者数 <?php echo $yearSummaryStage ? '(' .$yearSummaryStage .'年)' : ''?></td>
                        <td class="center-icon"><?php echo ($avgNewNum ? $avgNewNum . '人' : '-') ?></td>
                    </tr>
                    <tr>
                        <td class="criteria">5年後生存率数 <?php echo $yearSummarySurvival ? '(' .$yearSummarySurvival .'年)' : ''?></td>
                        <td class="center-icon"><?php echo ($avgSurvivalRate ?? '-') ?></td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-info">
                    <thead>
                    <tr class="border-top border-bottom bg-primary">
                        <th colspan="2" class="table-title">医療機関情報</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>医療機関名</th>
                        <td><?php echo $hospitalName ?? ''; ?></td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td><?php echo $hospitalTel ?? ''; ?></td>
                    </tr>
                    <tr>
                        <th>代表電話番号</th>
                        <td><?php echo $hospitalAddress ?? '' ?></td>
                    </tr>
                    <tr>
                        <th>公式HP</th>
                        <td><a target="_blank" href="#"><?php echo $hospitalUrl ?? '' ?></a></td>
                    </tr>
                    <tr>
                        <th>がん相談支援センターURL</th>
                        <td><a target="_blank" href="#"><?php echo $hospitalSpUrl ?? '' ?></a></td>
                    </tr>
                    <tr>
                        <th>特別室</th>
                        <td><a target="_blank" href="#"><?php echo $hospitalScUrl ?? '' ?></a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="page">
                <div class="watermark"></div>
                <table class="table table-info table-bordered">
                    <thead>
                    <tr class="border-top border-bottom bg-primary">
                        <th colspan="2" class="table-title">提供する治療情報</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>がん診療拠点区分</th>
                        <td><?php echo $hospitalType ?? '' ?></td>
                    </tr>
                    <tr>
                        <th>がんゲノム病院区分</th>
                        <td><?php echo $hospitalGen ?? '' ?></td>
                    </tr>
                    <tr>
                        <th>集学的治療体制の状況</th>
                        <td>
                            <p>
                                <?php
                                echo $multiTreatment ? '<span class="badge bg-secondary">あり</span>' : '';
                                ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>名医の在籍状況</th>
                        <td>
                            <p>
                                <?php
                                echo $famousDoctor ? '<span class="badge bg-secondary">あり</span>' : '<span class="badge bg-warning">なし</span>';
                                ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>先進医療の提供状況</th>
                        <td>
                            <p>
                                <?php
                                echo $hasAdvancedMedical ? '<span class="badge bg-secondary">あり</span>' : '<span class="badge bg-warning">なし</span>';
                                ?>
                            </p>
                            <p><?php echo $advancedMedical ? nl2br(e($advancedMedical)) : '' ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th>特別な治療の提供状況</th>
                        <td>
                            <p><b>
                                    <?php
                                    echo $infoTreatment ? nl2br(e($infoTreatment)) : '<span class="badge bg-warning">なし</span>';
                                    ?>
                                </b></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</body>
</html>
