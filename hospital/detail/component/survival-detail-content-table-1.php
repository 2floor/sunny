<?php

if (!isset($survivals)) {
    $survivals = [];
}

if (!isset($avgData)) {
    $avgData = [];
}
?>
<div class="table-responsive">
    <table class="table surv-detail-tb">
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title center-icon">年度</th>
            <th class="table-title center-icon"></th>
            <th class="table-title center-icon">ステージI</th>
            <th class="table-title center-icon">ステージII</th>
            <th class="table-title center-icon">ステージIII</th>
            <th class="table-title center-icon">ステージIV</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {
            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td rowspan="5" class="center-icon rowspan-col">'.(($survivals[$i]['year'] != null && $survivals[$i]['year'] != '') ? (($survivals[$i]['year'] . '年') . '～' . (($survivals[$i]['year'] + 1))  . '年') : '-').'</td>';
            $tr .= '<td class="center-icon table-title">参考:全国平均</td>';
            $tr .= '<td class="center-icon">-</td>';
            $tr .= '<td class="center-icon">-</td>';
            $tr .= '<td class="center-icon">-</td>';
            $tr .= '<td class="center-icon">-</td>';
            $tr .= '</tr>';

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">実績価</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target1'] != null && $survivals[$i]['stage_target1'] != '') ? ($survivals[$i]['stage_target1'] . '人') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target2'] != null && $survivals[$i]['stage_target2'] != '') ? ($survivals[$i]['stage_target2'] . '人') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target3'] != null && $survivals[$i]['stage_target3'] != '') ? ($survivals[$i]['stage_target3'] . '人') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target4'] != null && $survivals[$i]['stage_target4'] != '') ? ($survivals[$i]['stage_target4'] . '人') : '-').'</td>';
            $tr .= '</tr>';

            $prefStage1 = render_html_helper::renderRank($survivals[$i]['pref_stage_taget1'], '../../img/icons');
            $prefStage2 = render_html_helper::renderRank($survivals[$i]['pref_stage_taget2'], '../../img/icons');
            $prefStage3 = render_html_helper::renderRank($survivals[$i]['pref_stage_taget3'], '../../img/icons');
            $prefStage4 = render_html_helper::renderRank($survivals[$i]['pref_stage_taget4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">都道府県順位</td>';
            $tr .= '<td class="center-icon">'.$prefStage1.'</td>';
            $tr .= '<td class="center-icon">'.$prefStage2.'</td>';
            $tr .= '<td class="center-icon">'.$prefStage3.'</td>';
            $tr .= '<td class="center-icon">'.$prefStage4.'</td>';
            $tr .= '</tr>';

            $localStage1 = render_html_helper::renderRank($survivals[$i]['local_stage_taget1'], '../../img/icons');
            $localStage2 = render_html_helper::renderRank($survivals[$i]['local_stage_taget2'], '../../img/icons');
            $localStage3 = render_html_helper::renderRank($survivals[$i]['local_stage_taget3'], '../../img/icons');
            $localStage4 = render_html_helper::renderRank($survivals[$i]['local_stage_taget4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">地方順位</td>';
            $tr .= '<td class="center-icon">'.$localStage1.'</td>';
            $tr .= '<td class="center-icon">'.$localStage2.'</td>';
            $tr .= '<td class="center-icon">'.$localStage3.'</td>';
            $tr .= '<td class="center-icon">'.$localStage4.'</td>';
            $tr .= '</tr>';

            $totalStage1 = render_html_helper::renderRank($survivals[$i]['total_stage_taget1'], '../../img/icons');
            $totalStage2 = render_html_helper::renderRank($survivals[$i]['total_stage_taget2'], '../../img/icons');
            $totalStage3 = render_html_helper::renderRank($survivals[$i]['total_stage_taget3'], '../../img/icons');
            $totalStage4 = render_html_helper::renderRank($survivals[$i]['total_stage_taget4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">全国順位</td>';
            $tr .= '<td class="center-icon">'.$totalStage1.'</td>';
            $tr .= '<td class="center-icon">'.$totalStage2.'</td>';
            $tr .= '<td class="center-icon">'.$totalStage3.'</td>';
            $tr .= '<td class="center-icon">'.$totalStage4.'</td>';
            $tr .= '</tr>';

            $html = $tr . $html;

        }

        $avgHtml = '<tr class="border-top border-bottom">';
        $avgHtml .= '<td rowspan="5" class="center-icon rowspan-col">直近3年平均</td>';
        $avgHtml .= '<td class="center-icon table-title">参考:全国平均</td>';
        $avgHtml .= '<td class="center-icon">-</td>';
        $avgHtml .= '<td class="center-icon">-</td>';
        $avgHtml .= '<td class="center-icon">-</td>';
        $avgHtml .= '<td class="center-icon">-</td>';
        $avgHtml .= '</tr>';

        $avgStageTarget1 = $survivals->avg('stage_target1');
        $avgStageTarget2 = $survivals->avg('stage_target2');
        $avgStageTarget3 = $survivals->avg('stage_target3');
        $avgStageTarget4 = $survivals->avg('stage_target4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon table-title">実績価</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget1) ? (round($avgStageTarget1, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget2) ? (round($avgStageTarget2, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget3) ? (round($avgStageTarget3, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget4) ? (round($avgStageTarget4, 1) . '人') : '-').'</td>';
        $avgHtml .= '</tr>';

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon table-title">都道府県順位</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefNum1'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefNum2'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefNum3'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefNum4'], '../../img/icons').'</td>';
        $avgHtml .= '</tr>';

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon table-title">地方順位</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalNum1'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalNum2'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalNum3'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalNum4'], '../../img/icons').'</td>';
        $avgHtml .= '</tr>';

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon table-title">全国順位</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalNum1'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalNum2'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalNum3'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalNum4'], '../../img/icons').'</td>';
        $avgHtml .= '</tr>';


        echo $html . $avgHtml;
        ?>
        </tbody>
    </table>
</div>
