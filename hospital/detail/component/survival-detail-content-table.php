<?php

if (!isset($survivals)) {
    $survivals = [];
}
if (!isset($averageSurv)) {
    $averageSurv = [];
}

?>
<div class="table-responsive">
    <table class="table surv-detail-tb">
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title col-xs-2">年度</th>
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-4" colspan="4">集計対象者数</th>
            <th class="table-title col-xs-4" colspan="4">生在率係数</th>
        </tr>
        </thead>
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-1 center-icon">ステージI</th>
            <th class="table-title col-xs-1 center-icon">ステージII</th>
            <th class="table-title col-xs-1 center-icon">ステージIII</th>
            <th class="table-title col-xs-1 center-icon">ステージIV</th>
            <th class="table-title col-xs-1 center-icon">ステージI</th>
            <th class="table-title col-xs-1 center-icon">ステージII</th>
            <th class="table-title col-xs-1 center-icon">ステージIII</th>
            <th class="table-title col-xs-1 center-icon">ステージIV</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {
            $avgSurv = $averageSurv->where('year', $survivals[$i]['year'])->first();

            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td rowspan="5">'.(($survivals[$i]['year'] != null && $survivals[$i]['year'] != '') ? (($survivals[$i]['year'] . '年') . '～' . (($survivals[$i]['year'] + 1))  . '年') : '-').'</td>';
            $tr .= '<td>参考:全国平均</td>';
            $tr .= '<td></td>';
            $tr .= '<td></td>';
            $tr .= '<td></td>';
            $tr .= '<td></td>';
            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival1'] ? ($avgSurv['stage_survival1'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival2'] ? ($avgSurv['stage_survival2'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival3'] ? ($avgSurv['stage_survival3'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival4'] ? ($avgSurv['stage_survival4'] . '%') : '-').'</td>';
            $tr .= '</tr>';

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td>実績価</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target1'] != null && $survivals[$i]['stage_target1'] != '') ? ($survivals[$i]['stage_target1'] . '人') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target2'] != null && $survivals[$i]['stage_target2'] != '') ? ($survivals[$i]['stage_target2'] . '人') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target3'] != null && $survivals[$i]['stage_target3'] != '') ? ($survivals[$i]['stage_target3'] . '人') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_target4'] != null && $survivals[$i]['stage_target4'] != '') ? ($survivals[$i]['stage_target4'] . '人') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate1'] != null && $survivals[$i]['stage_survival_rate1'] != '') ? ($survivals[$i]['stage_survival_rate1'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate2'] != null && $survivals[$i]['stage_survival_rate2'] != '') ? ($survivals[$i]['stage_survival_rate2'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate3'] != null && $survivals[$i]['stage_survival_rate3'] != '') ? ($survivals[$i]['stage_survival_rate3'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate4'] != null && $survivals[$i]['stage_survival_rate4'] != '') ? ($survivals[$i]['stage_survival_rate4'] . '%') : '-').'</td>';
            $tr .= '</tr>';

            $prefStage1 = render_html_helper::renderRank($survivals[$i]['pref_stage_taget1'], '../../img/icons');
            $prefStage2 = render_html_helper::renderRank($survivals[$i]['pref_stage_taget2'], '../../img/icons');
            $prefStage3 = render_html_helper::renderRank($survivals[$i]['pref_stage_taget3'], '../../img/icons');
            $prefStage4 = render_html_helper::renderRank($survivals[$i]['pref_stage_taget4'], '../../img/icons');

            $prefRate1 = render_html_helper::renderRank($survivals[$i]['pref_survival_rate1'], '../../img/icons');
            $prefRate2 = render_html_helper::renderRank($survivals[$i]['pref_survival_rate2'], '../../img/icons');
            $prefRate3 = render_html_helper::renderRank($survivals[$i]['pref_survival_rate3'], '../../img/icons');
            $prefRate4 = render_html_helper::renderRank($survivals[$i]['pref_survival_rate4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td>都道府県順位</td>';
            $tr .= '<td class="center-icon">'.$prefStage1.'</td>';
            $tr .= '<td class="center-icon">'.$prefStage2.'</td>';
            $tr .= '<td class="center-icon">'.$prefStage3.'</td>';
            $tr .= '<td class="center-icon">'.$prefStage4.'</td>';
            $tr .= '<td class="center-icon">'.$prefRate1.'</td>';
            $tr .= '<td class="center-icon">'.$prefRate2.'</td>';
            $tr .= '<td class="center-icon">'.$prefRate3.'</td>';
            $tr .= '<td class="center-icon">'.$prefRate4.'</td>';
            $tr .= '</tr>';

            $localStage1 = render_html_helper::renderRank($survivals[$i]['local_stage_taget1'], '../../img/icons');
            $localStage2 = render_html_helper::renderRank($survivals[$i]['local_stage_taget2'], '../../img/icons');
            $localStage3 = render_html_helper::renderRank($survivals[$i]['local_stage_taget3'], '../../img/icons');
            $localStage4 = render_html_helper::renderRank($survivals[$i]['local_stage_taget4'], '../../img/icons');

            $localRate1 = render_html_helper::renderRank($survivals[$i]['local_survival_rate1'], '../../img/icons');
            $localRate2 = render_html_helper::renderRank($survivals[$i]['local_survival_rate2'], '../../img/icons');
            $localRate3 = render_html_helper::renderRank($survivals[$i]['local_survival_rate3'], '../../img/icons');
            $localRate4 = render_html_helper::renderRank($survivals[$i]['local_survival_rate4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td>地方順位</td>';
            $tr .= '<td class="center-icon">'.$localStage1.'</td>';
            $tr .= '<td class="center-icon">'.$localStage2.'</td>';
            $tr .= '<td class="center-icon">'.$localStage3.'</td>';
            $tr .= '<td class="center-icon">'.$localStage4.'</td>';
            $tr .= '<td class="center-icon">'.$localRate1.'</td>';
            $tr .= '<td class="center-icon">'.$localRate2.'</td>';
            $tr .= '<td class="center-icon">'.$localRate3.'</td>';
            $tr .= '<td class="center-icon">'.$localRate4.'</td>';
            $tr .= '</tr>';

            $totalStage1 = render_html_helper::renderRank($survivals[$i]['total_stage_taget1'], '../../img/icons');
            $totalStage2 = render_html_helper::renderRank($survivals[$i]['total_stage_taget2'], '../../img/icons');
            $totalStage3 = render_html_helper::renderRank($survivals[$i]['total_stage_taget3'], '../../img/icons');
            $totalStage4 = render_html_helper::renderRank($survivals[$i]['total_stage_taget4'], '../../img/icons');

            $totalRate1 = render_html_helper::renderRank($survivals[$i]['total_survival_rate1'], '../../img/icons');
            $totalRate2 = render_html_helper::renderRank($survivals[$i]['total_survival_rate2'], '../../img/icons');
            $totalRate3 = render_html_helper::renderRank($survivals[$i]['total_survival_rate3'], '../../img/icons');
            $totalRate4 = render_html_helper::renderRank($survivals[$i]['total_survival_rate4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td>全国順位</td>';
            $tr .= '<td class="center-icon">'.$totalStage1.'</td>';
            $tr .= '<td class="center-icon">'.$totalStage2.'</td>';
            $tr .= '<td class="center-icon">'.$totalStage3.'</td>';
            $tr .= '<td class="center-icon">'.$totalStage4.'</td>';
            $tr .= '<td class="center-icon">'.$totalRate1.'</td>';
            $tr .= '<td class="center-icon">'.$totalRate2.'</td>';
            $tr .= '<td class="center-icon">'.$totalRate3.'</td>';
            $tr .= '<td class="center-icon">'.$totalRate4.'</td>';
            $tr .= '</tr>';

            $html = $tr . $html;

        }

        $avgAverageSurv1 = $averageSurv->avg('stage_survival1');
        $avgAverageSurv2 = $averageSurv->avg('stage_survival2');
        $avgAverageSurv3 = $averageSurv->avg('stage_survival3');
        $avgAverageSurv4 = $averageSurv->avg('stage_survival4');

        $avgHtml = '<tr class="border-top border-bottom">';
        $avgHtml .= '<td rowspan="5">直近3年平均</td>';
        $avgHtml .= '<td>参考:全国平均</td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv1 != null && $avgAverageSurv1 != '') ? (round($avgAverageSurv1, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv2 != null && $avgAverageSurv2 != '') ? (round($avgAverageSurv2, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv3 != null && $avgAverageSurv3 != '') ? (round($avgAverageSurv3, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv4 != null && $avgAverageSurv4 != '') ? (round($avgAverageSurv4, 2) . '%') : '-').'</td>';
        $avgHtml .= '</tr>';

        $avgStageTarget1 = $survivals->avg('stage_target1');
        $avgStageTarget2 = $survivals->avg('stage_target2');
        $avgStageTarget3 = $survivals->avg('stage_target3');
        $avgStageTarget4 = $survivals->avg('stage_target4');

        $avgSurvivalRate1 = $survivals->avg('stage_survival_rate1');
        $avgSurvivalRate2 = $survivals->avg('stage_survival_rate2');
        $avgSurvivalRate3 = $survivals->avg('stage_survival_rate3');
        $avgSurvivalRate4 = $survivals->avg('stage_survival_rate4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>実績価</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget1) ? (round($avgStageTarget1, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget2) ? (round($avgStageTarget2, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget3) ? (round($avgStageTarget3, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgStageTarget4) ? (round($avgStageTarget4, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate1) ? (round($avgSurvivalRate1, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate2) ? (round($avgSurvivalRate2, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate3) ? (round($avgSurvivalRate3, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate4) ? (round($avgSurvivalRate4, 2) . '%') : '-').'</td>';
        $avgHtml .= '</tr>';

        $avgPrefStage1 = $survivals->avg('pref_stage_taget1');
        $avgPrefStage2 = $survivals->avg('pref_stage_taget2');
        $avgPrefStage3 = $survivals->avg('pref_stage_taget3');
        $avgPrefStage4 = $survivals->avg('pref_stage_taget4');

        $avgPrefRate1 = $survivals->avg('pref_survival_rate1');
        $avgPrefRate2 = $survivals->avg('pref_survival_rate2');
        $avgPrefRate3 = $survivals->avg('pref_survival_rate3');
        $avgPrefRate4 = $survivals->avg('pref_survival_rate4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>都道府県順位</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgPrefStage1) ? round($avgPrefStage1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgPrefStage2) ? round($avgPrefStage2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgPrefStage3) ? round($avgPrefStage3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgPrefStage4) ? round($avgPrefStage4) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgPrefRate1) ? round($avgPrefRate1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgPrefRate2) ? round($avgPrefRate2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgPrefRate3) ? round($avgPrefRate3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgPrefRate4) ? round($avgPrefRate4) : null), '../../img/icons').'</td>';
        $avgHtml .= '</tr>';

        $avgLocalStage1 = $survivals->avg('local_stage_taget1');
        $avgLocalStage2 = $survivals->avg('local_stage_taget2');
        $avgLocalStage3 = $survivals->avg('local_stage_taget3');
        $avgLocalStage4 = $survivals->avg('local_stage_taget4');

        $avgLocalRate1 = $survivals->avg('local_survival_rate1');
        $avgLocalRate2 = $survivals->avg('local_survival_rate2');
        $avgLocalRate3 = $survivals->avg('local_survival_rate3');
        $avgLocalRate4 = $survivals->avg('local_survival_rate4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>地方順位</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgLocalStage1) ? round($avgLocalStage1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgLocalStage2) ? round($avgLocalStage2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgLocalStage3) ? round($avgLocalStage3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgLocalStage4) ? round($avgLocalStage4) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgLocalRate1) ? round($avgLocalRate1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgLocalRate2) ? round($avgLocalRate2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgLocalRate3) ? round($avgLocalRate3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgLocalRate4) ? round($avgLocalRate4) : null), '../../img/icons').'</td>';
        $avgHtml .= '</tr>';

        $avgTotalStage1 = $survivals->avg('total_stage_taget1');
        $avgTotalStage2 = $survivals->avg('total_stage_taget2');
        $avgTotalStage3 = $survivals->avg('total_stage_taget3');
        $avgTotalStage4 = $survivals->avg('total_stage_taget4');

        $avgTotalRate1 = $survivals->avg('total_survival_rate1');
        $avgTotalRate2 = $survivals->avg('total_survival_rate2');
        $avgTotalRate3 = $survivals->avg('total_survival_rate3');
        $avgTotalRate4 = $survivals->avg('total_survival_rate4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>全国順位</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgTotalStage1) ? round($avgTotalStage1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgTotalStage2) ? round($avgTotalStage2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgTotalStage3) ? round($avgTotalStage3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgTotalStage4) ? round($avgTotalStage4) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgTotalRate1) ? round($avgTotalRate1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgTotalRate2) ? round($avgTotalRate2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgTotalRate3) ? round($avgTotalRate3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((is_numeric($avgTotalRate4) ? round($avgTotalRate4) : null), '../../img/icons').'</td>';
        $avgHtml .= '</tr>';


        echo $html . $avgHtml;
        ?>
        </tbody>
    </table>
</div>
