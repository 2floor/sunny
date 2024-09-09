<?php

if (!isset($survivals)) {
    $survivals = [];
}
if (!isset($averageSurv)) {
    $averageSurv = [];
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
            $avgSurv = $averageSurv->where('year', $survivals[$i]['year'])->first();

            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td rowspan="5" class="center-icon rowspan-col">'.(($survivals[$i]['year'] != null && $survivals[$i]['year'] != '') ? (($survivals[$i]['year'] . '年') . '～' . (($survivals[$i]['year'] + 1))  . '年') : '-').'</td>';
            $tr .= '<td class="center-icon table-title">参考:全国平均</td>';
            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival1'] ? ($avgSurv['stage_survival1'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival2'] ? ($avgSurv['stage_survival2'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival3'] ? ($avgSurv['stage_survival3'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.($avgSurv['stage_survival4'] ? ($avgSurv['stage_survival4'] . '%') : '-').'</td>';
            $tr .= '</tr>';

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">実績価</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate1'] != null && $survivals[$i]['stage_survival_rate1'] != '') ? ($survivals[$i]['stage_survival_rate1'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate2'] != null && $survivals[$i]['stage_survival_rate2'] != '') ? ($survivals[$i]['stage_survival_rate2'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate3'] != null && $survivals[$i]['stage_survival_rate3'] != '') ? ($survivals[$i]['stage_survival_rate3'] . '%') : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['stage_survival_rate4'] != null && $survivals[$i]['stage_survival_rate4'] != '') ? ($survivals[$i]['stage_survival_rate4'] . '%') : '-').'</td>';
            $tr .= '</tr>';

            $prefRate1 = render_html_helper::renderRank($survivals[$i]['pref_survival_rate1'], '../../img/icons');
            $prefRate2 = render_html_helper::renderRank($survivals[$i]['pref_survival_rate2'], '../../img/icons');
            $prefRate3 = render_html_helper::renderRank($survivals[$i]['pref_survival_rate3'], '../../img/icons');
            $prefRate4 = render_html_helper::renderRank($survivals[$i]['pref_survival_rate4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">都道府県順位</td>';
            $tr .= '<td class="center-icon">'.$prefRate1.'</td>';
            $tr .= '<td class="center-icon">'.$prefRate2.'</td>';
            $tr .= '<td class="center-icon">'.$prefRate3.'</td>';
            $tr .= '<td class="center-icon">'.$prefRate4.'</td>';
            $tr .= '</tr>';

            $localRate1 = render_html_helper::renderRank($survivals[$i]['local_survival_rate1'], '../../img/icons');
            $localRate2 = render_html_helper::renderRank($survivals[$i]['local_survival_rate2'], '../../img/icons');
            $localRate3 = render_html_helper::renderRank($survivals[$i]['local_survival_rate3'], '../../img/icons');
            $localRate4 = render_html_helper::renderRank($survivals[$i]['local_survival_rate4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">地方順位</td>';
            $tr .= '<td class="center-icon">'.$localRate1.'</td>';
            $tr .= '<td class="center-icon">'.$localRate2.'</td>';
            $tr .= '<td class="center-icon">'.$localRate3.'</td>';
            $tr .= '<td class="center-icon">'.$localRate4.'</td>';
            $tr .= '</tr>';

            $totalRate1 = render_html_helper::renderRank($survivals[$i]['total_survival_rate1'], '../../img/icons');
            $totalRate2 = render_html_helper::renderRank($survivals[$i]['total_survival_rate2'], '../../img/icons');
            $totalRate3 = render_html_helper::renderRank($survivals[$i]['total_survival_rate3'], '../../img/icons');
            $totalRate4 = render_html_helper::renderRank($survivals[$i]['total_survival_rate4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">全国順位</td>';
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
        $avgHtml .= '<td rowspan="5" class="center-icon rowspan-col">直近3年平均</td>';
        $avgHtml .= '<td class="center-icon table-title">参考:全国平均</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv1 != null && $avgAverageSurv1 != '') ? (round($avgAverageSurv1, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv2 != null && $avgAverageSurv2 != '') ? (round($avgAverageSurv2, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv3 != null && $avgAverageSurv3 != '') ? (round($avgAverageSurv3, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgAverageSurv4 != null && $avgAverageSurv4 != '') ? (round($avgAverageSurv4, 2) . '%') : '-').'</td>';
        $avgHtml .= '</tr>';

        $avgSurvivalRate1 = $survivals->avg('stage_survival_rate1');
        $avgSurvivalRate2 = $survivals->avg('stage_survival_rate2');
        $avgSurvivalRate3 = $survivals->avg('stage_survival_rate3');
        $avgSurvivalRate4 = $survivals->avg('stage_survival_rate4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon table-title">実績価</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate1) ? (round($avgSurvivalRate1, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate2) ? (round($avgSurvivalRate2, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate3) ? (round($avgSurvivalRate3, 2) . '%') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($avgSurvivalRate4) ? (round($avgSurvivalRate4, 2) . '%') : '-').'</td>';
        $avgHtml .= '</tr>';

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td  class="center-icon table-title">都道府県順位</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefRate1'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefRate2'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefRate3'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefRate4'], '../../img/icons').'</td>';
        $avgHtml .= '</tr>';

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td  class="center-icon table-title">地方順位</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalRate1'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalRate2'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalRate3'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalRate4'], '../../img/icons').'</td>';
        $avgHtml .= '</tr>';

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon table-title">全国順位</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalRate1'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalRate2'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalRate3'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalRate4'], '../../img/icons').'</td>';
        $avgHtml .= '</tr>';


        echo $html . $avgHtml;
        ?>
        </tbody>
    </table>
</div>
