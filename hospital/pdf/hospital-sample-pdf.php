<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo $hospitalName; ?></title>
    <link href="<?php echo $baseUrl; ?>assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap');

        body {
            font-family: 'Noto Sans JP', sans-serif;
            font-size: 10px;
        }

        main, .main-detail, .container-fluid {
            padding: 0;
        }

        .title-print {
            margin-bottom: 30px;
            margin-top: 0;
        }

        .cancer-name {
            color: #505458;
            font-weight: bold;
            font-size: 14px;
            line-height: 7px !important;
        }

        .hospital-name {
            color: #505458;
            font-weight: bold;
            font-size: 16px;
            line-height: 8px !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td, th {
            line-height: 8px !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }

        .table-info {
            border: none !important;
        }

        .table-info th {
            width: 25%;
            background-color: #DEF3FD;
            border-top: 1px solid #b6e8e8 !important;
            border-bottom: 1px solid #b6e8e8 !important;
            border-right: none !important;
            border-left: none !important;
            font-weight: normal !important;
        }

        .table-info td {
            width: 75%;
            border-top: 1px solid #b6e8e8 !important;
            border-bottom: 1px solid #b6e8e8 !important;
            border-right: none !important;
            border-left: none !important;
        }

        .bg-secondary {
            background-color: #71b6f9;
        }

        .bg-warning {
            background-color: #f9c851 !important;
        }

        a {
            color: #337ab7;
            text-decoration: none;
        }

        .badge {
            margin-top: 0 !important;
            padding: 0 4px 4px 5px;
            border-radius: 10px;
            line-height: 6px;
            font-size: 8px;
            border: 1px solid #71b6f9;
        }

        .bg-warning {
            background-color: #FFFFFF !important;
            color: #71b6f9 !important;
        }

        .table-title {
            background-color: #3FB6B6;
            color: #FFFFFF;
            border: 1px solid #c3e4ed;
            font-size: 12px;
            font-weight: 700;
        }

        .table-even-odd tr:nth-child(even) {
            background-color: #DEF3FD;
        }

        .table-odd-even tr:nth-child(odd) {
            background-color: #DEF3FD;
        }

        .center-icon {
            text-align: center;
            vertical-align: middle;
        }

        .footer {
            position: fixed;
            bottom: 2px;
            left: 0;
            width: 100%;
            font-size: 8px;
            height: 70px;
        }

        .info-file {
            float: right;
            margin-top: 22px;
            opacity: 0.3;
            color: #505458;
            font-weight: bold;
            font-size: 12px;
            line-height: 8px;
        }

        .watermark {
            float: left;
            opacity: 0.3;
        }

        .table-summary tbody td{
            line-height: 4px !important;
        }
    </style>
</head>
<body>
<main>
    <div class="container-fluid">
        <div class="main-detail">
            <div>
                <div class="title-print">
                    <p class="text-center hospital-name"><?php echo $hospitalName; ?></p>
                    <p class="text-center cancer-name"><?php echo $cancerName; ?></p>
                </div>
                <table class="table table-bordered table-info">
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
                <table class="table table-info table-bordered">
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
                            <?php
                            echo $multiTreatment ? '<span class="badge bg-secondary">あり</span>' : '';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>名医の在籍状況</th>
                        <td>
                            <?php
                            echo $famousDoctor ? '<span class="badge bg-secondary">あり</span>' : '<span class="badge bg-warning">なし</span>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>先進医療の提供状況</th>
                        <td>
                            <?php
                            echo $hasAdvancedMedical ? '<span class="badge bg-secondary">あり</span>' : '<span class="badge bg-warning">なし</span>';
                            ?>
                            <br>
                            <?php echo $advancedMedical ? nl2br(e($advancedMedical)) : '' ?>
                        </td>
                    </tr>
                    <tr>
                        <th>特別な治療の提供状況</th>
                        <td>
                            <b>
                                <?php
                                echo $infoTreatment ? nl2br(e($infoTreatment)) : '<span class="badge bg-warning">なし</span>';
                                ?>
                            </b>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-summary table-even-odd table-bordered">
                    <thead>
                    <tr>
                        <th class="table-title" colspan="2">治療実績 (直近3年平均)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="criteria"></td>
                        <td class="center-icon">実績値</td>
                    </tr>
                    <tr>
                        <td class="criteria center-icon">年間入院患者数 <?php echo $yearSummaryDpc ? '(' .$yearSummaryDpc .'年)' : ''?></td>
                        <td class="center-icon"><?php echo ($avgDpc ? $avgDpc . '人' : '-') ?></td>
                    </tr>
                    <tr>
                        <td class="criteria center-icon">年間新規患者数 <?php echo $yearSummaryStage ? '(' .$yearSummaryStage .'年)' : ''?></td>
                        <td class="center-icon"><?php echo ($avgNewNum ? $avgNewNum . '人' : '-') ?></td>
                    </tr>
                    <tr>
                        <td class="criteria center-icon">5年生存率係数 <?php echo $yearSummarySurvival ? '(' .$yearSummarySurvival .')' : ''?></td>
                        <td class="center-icon"><?php echo ($avgSurvivalRate ?? '-') ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer" style="page-break-after: always;">
                <div class="watermark"><img src="<?php echo $baseUrl; ?>assets/images/common/logo.png" width="160px" height="60px"></div>
                <div class="info-file">
                    <p class="text-center"><?php echo $hospitalName; ?></p>
                    <p class="text-center"><?php echo $cancerName; ?></p>
                </div>
            </div>
            <div>
                <table class="table table-summary table-even-odd table-bordered">
                    <thead>
                    <tr>
                        <th class="table-title" colspan="2">年間入院患者数</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="center-icon">年</td>
                        <td class="center-icon">統計値</td>
                    </tr>

                    <?php
                        $html = '';
                        for ($i = 0; $i < 3; $i++) {
                            $tr = '<tr>';
                            $tr .= '<td class="center-icon">'.(($dpcs[$i]['year'] != null && $dpcs[$i]['year'] != '') ? ($dpcs[$i]['year'] . '年') : '-').'</td>';
                            $tr .= '<td class="center-icon">' . (($dpcs[$i]['n_dpc'] != null && $dpcs[$i]['n_dpc'] != '') ? $dpcs[$i]['n_dpc'] . '人' : '-') . '</td>';
                            $tr .= '</tr>';
                            $html = $tr . $html;
                        }

                        echo $html;
                    ?>
                    <tr>
                        <td class="center-icon">直近3年平均</td>
                        <td class="center-icon"><?php echo (($avgDpc != null && $avgDpc != '') ? round($avgDpc, 1) . '人' : '-') ?></td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-summary table-even-odd table-bordered">
                    <thead>
                    <tr>
                        <th class="table-title" colspan="6">年間新規患者数</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="center-icon">年</td>
                        <td class="center-icon">統計値</td>
                        <td class="center-icon">ステージI</td>
                        <td class="center-icon">ステージII</td>
                        <td class="center-icon">ステージIII</td>
                        <td class="center-icon">ステージIV</td>
                    </tr>

                    <?php
                        $html = '';
                        for ($i = 0; $i < 3; $i++) {
                            $totalStage = $stages[$i]['stage_new1'] + $stages[$i]['stage_new2'] + $stages[$i]['stage_new3'] + $stages[$i]['stage_new4'];
                            $percentStage1 = (is_numeric($stages[$i]['stage_new1']) && is_numeric($totalStage)) ? ('(' . (round($stages[$i]['stage_new1'] / $totalStage * 100, 2)) . '%)') : '';
                            $percentStage2 = (is_numeric($stages[$i]['stage_new2']) && is_numeric($totalStage)) ? ('(' . (round($stages[$i]['stage_new2'] / $totalStage * 100, 2)) . '%)') : '';
                            $percentStage3 = (is_numeric($stages[$i]['stage_new3']) && is_numeric($totalStage)) ? ('(' . (round($stages[$i]['stage_new3'] / $totalStage * 100, 2)) . '%)') : '';
                            $percentStage4 = (is_numeric($stages[$i]['stage_new4']) && is_numeric($totalStage)) ? ('(' . (round($stages[$i]['stage_new4'] / $totalStage * 100, 2)) . '%)') : '';

                            $tr = '<tr class="border-top border-bottom">';
                            $tr .= '<td class="center-icon">'.(($stages[$i]['year'] != null && $stages[$i]['year'] != '') ? ($stages[$i]['year'] . '年') : '-').'</td>';
                            $tr .= '<td class="center-icon">' . (($stages[$i]['total_num_new'] != null && $stages[$i]['total_num_new'] != '') ? $stages[$i]['total_num_new'] . '人' : '-') . '</td>';
                            $tr .= '<td class="center-icon">'.(($stages[$i]['stage_new1'] != null && $stages[$i]['stage_new1'] != '') ? ($stages[$i]['stage_new1'] . '人 ' .  $percentStage1) : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($stages[$i]['stage_new2'] != null && $stages[$i]['stage_new2'] != '') ? ($stages[$i]['stage_new2'] . '人 ' .  $percentStage2) : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($stages[$i]['stage_new3'] != null && $stages[$i]['stage_new3'] != '') ? ($stages[$i]['stage_new3'] . '人 ' .  $percentStage3) : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($stages[$i]['stage_new4'] != null && $stages[$i]['stage_new4'] != '') ? ($stages[$i]['stage_new4'] . '人 ' .  $percentStage4) : '-').'</td>';

                            $tr .= '</tr>';

                            $html = $tr . $html;
                        }

                        echo $html;
                    ?>
                    <tr>
                        <td class="center-icon">直近3年平均</td>
                        <td class="center-icon"><?php echo (($avgNewNum != null && $avgNewNum != '') ? round($avgNewNum, 1) . '人' : '-') ?></td>
                        <?php
                            $avgStage1 = $stages->avg('stage_new1');
                            $avgStage2 = $stages->avg('stage_new2');
                            $avgStage3 = $stages->avg('stage_new3');
                            $avgStage4 = $stages->avg('stage_new4');


                            $avgStage1 = ($avgStage1 != null && $avgStage1 != '') ? (round($avgStage1, 1)) : null;
                            $avgStage2 = ($avgStage2 != null && $avgStage2 != '') ? (round($avgStage2, 1)) : null;
                            $avgStage3 = ($avgStage3 != null && $avgStage3 != '') ? (round($avgStage3, 1)) : null;
                            $avgStage4 = ($avgStage4 != null && $avgStage4 != '') ? (round($avgStage4, 1)) : null;

                            $totalStage = $avgStage1 + $avgStage2 + $avgStage3 + $avgStage4;

                            $percentStage1 = (is_numeric($avgStage1) && is_numeric($totalStage)) ? ('(' . (round($avgStage1 / $totalStage * 100, 2)) . '%)') : '';
                            $percentStage2 = (is_numeric($avgStage2) && is_numeric($totalStage)) ? ('(' . (round($avgStage2 / $totalStage * 100, 2)) . '%)') : '';
                            $percentStage3 = (is_numeric($avgStage3) && is_numeric($totalStage)) ? ('(' . (round($avgStage3 / $totalStage * 100, 2)) . '%)') : '';
                            $percentStage4 = (is_numeric($avgStage4) && is_numeric($totalStage)) ? ('(' . (round($avgStage4 / $totalStage * 100, 2)) . '%)') : '';

                            $avgHtml = '<td class="center-icon">'.($avgStage1 ? ($avgStage1 . '人' . $percentStage1) : '-').'</td>';
                            $avgHtml .= '<td class="center-icon">'.($avgStage2 ? ($avgStage2 . '人' . $percentStage2) : '-').'</td>';
                            $avgHtml .= '<td class="center-icon">'.($avgStage3 ? ($avgStage3 . '人' . $percentStage3) : '-').'</td>';
                            $avgHtml .= '<td class="center-icon">'.($avgStage4 ? ($avgStage4 . '人' . $percentStage4) : '-').'</td>';

                            echo $avgHtml;
                        ?>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-summary table-even-odd table-bordered">
                    <thead>
                    <tr>
                        <th class="table-title" colspan="3">生存率係数・ステージ別5年実測生存率</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="center-icon">年</td>
                        <td class="center-icon">集計対象者数</td>
                        <td class="center-icon">生存率係数</td>
                    </tr>

                    <?php
                    $html = '';
                    for ($i = 0; $i < 3; $i++) {
                        $tr = '<tr>';
                        $tr .= '<td class="center-icon">'.(($survivals[$i]['year'] != null && $survivals[$i]['year'] != '') ? (($survivals[$i]['year'] . '年') . '～' . (($survivals[$i]['year'] + 1)). '年') : '-').'</td>';
                        $tr .= '<td class="center-icon">' . (($survivals[$i]['total_num'] != null && $survivals[$i]['total_num'] != '') ? $survivals[$i]['total_num'] . '人' : '-') . '</td>';
                        $tr .= '<td class="center-icon">' . (($survivals[$i]['survival_rate'] != null && $survivals[$i]['survival_rate'] != '') ? $survivals[$i]['survival_rate'] : '-') . '</td>';
                        $tr .= '</tr>';
                        $html = $tr . $html;
                    }

                    echo $html;
                    ?>
                    <tr>
                        <td class="center-icon">直近3年平均</td>
                        <td class="center-icon">
                            <?php
                            $avgNum = $survivals->avg('total_num');
                            echo (($avgNum != null && $avgNum != '') ? (round($avgNum, 1) . '人') : '-');
                            ?>
                        </td>
                        <td class="center-icon"><?php echo (($avgSurvivalRate != null && $avgSurvivalRate != '') ? round($avgSurvivalRate, 2) : '-') ?></td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-summary table-odd-even table-bordered">
                    <tbody>
                    <tr>
                        <td class="center-icon" colspan="2">年</td>
                        <td class="center-icon" colspan="4">集計対象者数</td>
                        <td class="center-icon" colspan="4">生存率係数</td>
                    </tr>
                    <tr>
                        <td class="center-icon" colspan="2"></td>
                        <td class="center-icon">ステージI</td>
                        <td class="center-icon">ステージII</td>
                        <td class="center-icon">ステージIII</td>
                        <td class="center-icon">ステージIV</td>
                        <td class="center-icon">ステージI</td>
                        <td class="center-icon">ステージII</td>
                        <td class="center-icon">ステージIII</td>
                        <td class="center-icon">ステージIV</td>
                    </tr>

                    <?php
                        $html = '';
                        for ($i = 0; $i < 3; $i++) {
                            $avgSurv = $averageSurv->where('year', $survivals[$i]['year'])->first();

                            $tr = '<tr>';
                            $tr .= '<td class="center-icon" rowspan="2">'.(($survivals[$i]['year'] != null && $survivals[$i]['year'] != '') ? (($survivals[$i]['year'] . '年') . '<br><br>～<br><br>' . (($survivals[$i]['year'] + 1))  . '年') : '-').'</td>';
                            $tr .= '<td class="center-icon">全国平均</td>';
                            $tr .= '<td class="center-icon"></td>';
                            $tr .= '<td class="center-icon"></td>';
                            $tr .= '<td class="center-icon"></td>';
                            $tr .= '<td class="center-icon"></td>';
                            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival1'] ? ($avgSurv['stage_survival1'] . '%') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival2'] ? ($avgSurv['stage_survival2'] . '%') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival3'] ? ($avgSurv['stage_survival3'] . '%') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival4'] ? ($avgSurv['stage_survival4'] . '%') : '-').'</td>';
                            $tr .= '</tr>';

                            $tr .= '<tr>';
                            $tr .= '<td class="center-icon">実績値</td>';
                            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target1'] != null && $survivals[$i]['stage_target1'] != '') ? ($survivals[$i]['stage_target1'] . '人') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target2'] != null && $survivals[$i]['stage_target2'] != '') ? ($survivals[$i]['stage_target2'] . '人') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target3'] != null && $survivals[$i]['stage_target3'] != '') ? ($survivals[$i]['stage_target3'] . '人') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target4'] != null && $survivals[$i]['stage_target4'] != '') ? ($survivals[$i]['stage_target4'] . '人') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate1'] != null && $survivals[$i]['stage_survival_rate1'] != '') ? ($survivals[$i]['stage_survival_rate1'] . '%') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate2'] != null && $survivals[$i]['stage_survival_rate2'] != '') ? ($survivals[$i]['stage_survival_rate2'] . '%') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate3'] != null && $survivals[$i]['stage_survival_rate3'] != '') ? ($survivals[$i]['stage_survival_rate3'] . '%') : '-').'</td>';
                            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate4'] != null && $survivals[$i]['stage_survival_rate4'] != '') ? ($survivals[$i]['stage_survival_rate4'] . '%') : '-').'</td>';
                            $tr .= '</tr>';

                            $html = $tr . $html;
                        }

                        echo $html;
                    ?>
                    <?php
                        $avgAverageSurv1 = $averageSurv->avg('stage_survival1');
                        $avgAverageSurv2 = $averageSurv->avg('stage_survival2');
                        $avgAverageSurv3 = $averageSurv->avg('stage_survival3');
                        $avgAverageSurv4 = $averageSurv->avg('stage_survival4');

                        $avgStageTarget1 = $survivals->avg('stage_target1');
                        $avgStageTarget2 = $survivals->avg('stage_target2');
                        $avgStageTarget3 = $survivals->avg('stage_target3');
                        $avgStageTarget4 = $survivals->avg('stage_target4');

                        $avgSurvivalRate1 = $survivals->avg('stage_survival_rate1');
                        $avgSurvivalRate2 = $survivals->avg('stage_survival_rate2');
                        $avgSurvivalRate3 = $survivals->avg('stage_survival_rate3');
                        $avgSurvivalRate4 = $survivals->avg('stage_survival_rate4');

                        $avgHtml = '<tr>';
                        $avgHtml .= '<td class="center-icon" rowspan="2">直近3<br><br><br>年平均</td>';
                        $avgHtml .= '<td class="center-icon">全国平均</td>';
                        $avgHtml .= '<td></td>';
                        $avgHtml .= '<td></td>';
                        $avgHtml .= '<td></td>';
                        $avgHtml .= '<td></td>';
                        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv1 != null && $avgAverageSurv1 != '') ? (round($avgAverageSurv1, 2) . '%') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv2 != null && $avgAverageSurv2 != '') ? (round($avgAverageSurv2, 2) . '%') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv3 != null && $avgAverageSurv3 != '') ? (round($avgAverageSurv3, 2) . '%') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv4 != null && $avgAverageSurv4 != '') ? (round($avgAverageSurv4, 2) . '%') : '-').'</td>';
                        $avgHtml .= '</tr>';

                        $avgHtml .= '<tr>';
                        $avgHtml .= '<td class="center-icon">実績値</td>';
                        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget1) ? (round($avgStageTarget1, 1) . '人') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget2) ? (round($avgStageTarget2, 1) . '人') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget3) ? (round($avgStageTarget3, 1) . '人') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget4) ? (round($avgStageTarget4, 1) . '人') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate1) ? (round($avgSurvivalRate1, 2) . '%') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate2) ? (round($avgSurvivalRate2, 2) . '%') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate3) ? (round($avgSurvivalRate3, 2) . '%') : '-').'</td>';
                        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate4) ? (round($avgSurvivalRate4, 2) . '%') : '-').'</td>';
                        $avgHtml .= '</tr>';

                        echo $avgHtml;
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="footer" style="page-break-before: avoid; page-break-after: avoid;">
                <div class="watermark"><img src="<?php echo $baseUrl; ?>assets/images/common/logo.png" width="160px" height="60px"></div>
                <div class="info-file">
                    <p class="text-center"><?php echo $hospitalName; ?></p>
                    <p class="text-center"><?php echo $cancerName; ?></p>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
