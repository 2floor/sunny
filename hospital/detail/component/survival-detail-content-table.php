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
            <th class="table-title col-xs-1">集計対象者数</th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1">生在率係数</th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
        </tr>
        </thead>
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-1">ステージI</th>
            <th class="table-title col-xs-1">ステージII</th>
            <th class="table-title col-xs-1">ステージIII</th>
            <th class="table-title col-xs-1">ステージIV</th>
            <th class="table-title col-xs-1">ステージI</th>
            <th class="table-title col-xs-1">ステージII</th>
            <th class="table-title col-xs-1">ステージIII</th>
            <th class="table-title col-xs-1">ステージIV</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {
            $avgSurv = $averageSurv->where('year', $survivals[$i]['year'])->first();

            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td rowspan="5">'.($survivals[$i]['year'] ? ($survivals[$i]['year'] . '-' . ($survivals[$i]['year'] + 1)) : '-').'</td>';
            $tr .= '<td>参考:全国平均</td>';
            $tr .= '<td></td>';
            $tr .= '<td></td>';
            $tr .= '<td></td>';
            $tr .= '<td></td>';
            $tr .= '<td>'.($avgSurv['stage_survival1'] ? ($avgSurv['stage_survival1'] . '%') : '-').'</td>';
            $tr .= '<td>'.($avgSurv['stage_survival2'] ? ($avgSurv['stage_survival2'] . '%') : '-').'</td>';
            $tr .= '<td>'.($avgSurv['stage_survival3'] ? ($avgSurv['stage_survival3'] . '%') : '-').'</td>';
            $tr .= '<td>'.($avgSurv['stage_survival4'] ? ($avgSurv['stage_survival4'] . '%') : '-').'</td>';
            $tr .= '</tr>';

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td>実績価</td>';
            $tr .= '<td>'.($survivals[$i]['stage_target1'] ? ($survivals[$i]['stage_target1'] . '人') : '-').'</td>';
            $tr .= '<td>'.($survivals[$i]['stage_target2'] ? ($survivals[$i]['stage_target2'] . '人') : '-').'</td>';
            $tr .= '<td>'.($survivals[$i]['stage_target3'] ? ($survivals[$i]['stage_target3'] . '人') : '-').'</td>';
            $tr .= '<td>'.($survivals[$i]['stage_target4'] ? ($survivals[$i]['stage_target4'] . '人') : '-').'</td>';
            $tr .= '<td>'.($survivals[$i]['stage_survival_rate1'] ? ($survivals[$i]['stage_survival_rate1'] . '%') : '-').'</td>';
            $tr .= '<td>'.($survivals[$i]['stage_survival_rate2'] ? ($survivals[$i]['stage_survival_rate2'] . '%') : '-').'</td>';
            $tr .= '<td>'.($survivals[$i]['stage_survival_rate3'] ? ($survivals[$i]['stage_survival_rate3'] . '%') : '-').'</td>';
            $tr .= '<td>'.($survivals[$i]['stage_survival_rate4'] ? ($survivals[$i]['stage_survival_rate4'] . '%') : '-').'</td>';
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
            $tr .= '<td>'.$prefStage1.'</td>';
            $tr .= '<td>'.$prefStage2.'</td>';
            $tr .= '<td>'.$prefStage3.'</td>';
            $tr .= '<td>'.$prefStage4.'</td>';
            $tr .= '<td>'.$prefRate1.'</td>';
            $tr .= '<td>'.$prefRate2.'</td>';
            $tr .= '<td>'.$prefRate3.'</td>';
            $tr .= '<td>'.$prefRate4.'</td>';
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
            $tr .= '<td>'.$localStage1.'</td>';
            $tr .= '<td>'.$localStage2.'</td>';
            $tr .= '<td>'.$localStage3.'</td>';
            $tr .= '<td>'.$localStage4.'</td>';
            $tr .= '<td>'.$localRate1.'</td>';
            $tr .= '<td>'.$localRate2.'</td>';
            $tr .= '<td>'.$localRate3.'</td>';
            $tr .= '<td>'.$localRate4.'</td>';
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
            $tr .= '<td>'.$totalStage1.'</td>';
            $tr .= '<td>'.$totalStage2.'</td>';
            $tr .= '<td>'.$totalStage3.'</td>';
            $tr .= '<td>'.$totalStage4.'</td>';
            $tr .= '<td>'.$totalRate1.'</td>';
            $tr .= '<td>'.$totalRate2.'</td>';
            $tr .= '<td>'.$totalRate3.'</td>';
            $tr .= '<td>'.$totalRate4.'</td>';
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
        $avgHtml .= '<td>'.($avgAverageSurv1 ? (round($avgAverageSurv1, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td>'.($avgAverageSurv2 ? (round($avgAverageSurv2, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td>'.($avgAverageSurv3 ? (round($avgAverageSurv3, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td>'.($avgAverageSurv4 ? (round($avgAverageSurv4, 2) . '%') : '-').'</td>';
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
        $avgHtml .= '<td>'.($avgStageTarget1 ? (round($avgStageTarget1) . '人') : '-').'</td>';
        $avgHtml .= '<td>'.($avgStageTarget2 ? (round($avgStageTarget2) . '人') : '-').'</td>';
        $avgHtml .= '<td>'.($avgStageTarget3 ? (round($avgStageTarget3) . '人') : '-').'</td>';
        $avgHtml .= '<td>'.($avgStageTarget4 ? (round($avgStageTarget4) . '人') : '-').'</td>';
        $avgHtml .= '<td>'.($avgSurvivalRate1 ? (round($avgSurvivalRate1, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td>'.($avgSurvivalRate2 ? (round($avgSurvivalRate2, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td>'.($avgSurvivalRate3 ? (round($avgSurvivalRate3, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td>'.($avgSurvivalRate4 ? (round($avgSurvivalRate4, 2) . '%') : '-').'</td>';
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
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefStage1 ? round($avgPrefStage1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefStage2 ? round($avgPrefStage2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefStage3 ? round($avgPrefStage3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefStage4 ? round($avgPrefStage4) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefRate1 ? round($avgPrefRate1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefRate2 ? round($avgPrefRate2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefRate3 ? round($avgPrefRate3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefRate4 ? round($avgPrefRate4) : null), '../../img/icons').'</td>';
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
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalStage1 ? round($avgLocalStage1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalStage2 ? round($avgLocalStage2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalStage3 ? round($avgLocalStage3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalStage4 ? round($avgLocalStage4) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalRate1 ? round($avgLocalRate1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalRate2 ? round($avgLocalRate2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalRate3 ? round($avgLocalRate3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalRate4 ? round($avgLocalRate4) : null), '../../img/icons').'</td>';
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
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalStage1 ? round($avgTotalStage1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalStage2 ? round($avgTotalStage2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalStage3 ? round($avgTotalStage3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalStage4 ? round($avgTotalStage4) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalRate1 ? round($avgTotalRate1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalRate2 ? round($avgTotalRate2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalRate3 ? round($avgTotalRate3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalRate4 ? round($avgTotalRate4) : null), '../../img/icons').'</td>';
        $avgHtml .= '</tr>';


        echo $html . $avgHtml;
        ?>
        </tbody>
    </table>
</div>
