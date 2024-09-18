<?php

if (!isset($stages)) {
    $stages = [];
}

if (!isset($avgData)) {
    $avgData = [];
}
?>

<div class="table-responsive">
    <table class="table stage-detail-tb">
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title center-icon">年</th>
            <th class="table-title center-icon"></th>
            <th class="table-title center-icon">ステージI</th>
            <th class="table-title center-icon">ステージII</th>
            <th class="table-title center-icon">ステージIII</th>
            <th class="table-title center-icon">ステージIV</th>
            <th class="table-title center-icon">合計数</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {

            $totalStage = $stages[$i]['stage_new1'] + $stages[$i]['stage_new2'] + $stages[$i]['stage_new3'] + $stages[$i]['stage_new4'];
            $percentStage1 = (is_numeric($stages[$i]['stage_new1']) && is_numeric($totalStage)) ? ('(' . (round($stages[$i]['stage_new1'] / $totalStage * 100, 2)) . '%)') : '';
            $percentStage2 = (is_numeric($stages[$i]['stage_new2']) && is_numeric($totalStage)) ? ('(' . (round($stages[$i]['stage_new2'] / $totalStage * 100, 2)) . '%)') : '';
            $percentStage3 = (is_numeric($stages[$i]['stage_new3']) && is_numeric($totalStage)) ? ('(' . (round($stages[$i]['stage_new3'] / $totalStage * 100, 2)) . '%)') : '';
            $percentStage4 = (is_numeric($stages[$i]['stage_new4']) && is_numeric($totalStage)) ? ('(' . (round($stages[$i]['stage_new4'] / $totalStage * 100, 2)) . '%)') : '';
            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon rowspan-col" rowspan="4">'.(($stages[$i]['year'] != null && $stages[$i]['year'] !='') ? ($stages[$i]['year'] . '年') : '-').'</td>';
            $tr .= '<td class="center-icon table-title">新規患者数</td>';
            $tr .= '<td class="center-icon">'.(($stages[$i]['stage_new1'] != null && $stages[$i]['stage_new1'] != '') ? ($stages[$i]['stage_new1'] . '人 ' .  $percentStage1) : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($stages[$i]['stage_new2'] != null && $stages[$i]['stage_new2'] != '') ? ($stages[$i]['stage_new2'] . '人 ' .  $percentStage2) : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($stages[$i]['stage_new3'] != null && $stages[$i]['stage_new3'] != '') ? ($stages[$i]['stage_new3'] . '人 ' .  $percentStage3) : '-').'</td>';
            $tr .= '<td class="center-icon">'.(($stages[$i]['stage_new4'] != null && $stages[$i]['stage_new4'] != '') ? ($stages[$i]['stage_new4'] . '人 ' .  $percentStage4) : '-').'</td>';
            $tr .= '<td class="center-icon">'.(is_numeric($totalStage) ? ($totalStage.'人') : '-').'</td>';
            $tr .= '</tr>';


            $prefRank1 = render_html_helper::renderRank($stages[$i]['pref_num_rank_stage1'], '../../img/icons');
            $prefRank2 = render_html_helper::renderRank($stages[$i]['pref_num_rank_stage2'], '../../img/icons');
            $prefRank3 = render_html_helper::renderRank($stages[$i]['pref_num_rank_stage3'], '../../img/icons');
            $prefRank4 = render_html_helper::renderRank($stages[$i]['pref_num_rank_stage4'], '../../img/icons');


            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">都道府県</td>';
            $tr .= '<td class="center-icon">'.$prefRank1.'</td>';
            $tr .= '<td class="center-icon">'.$prefRank2.'</td>';
            $tr .= '<td class="center-icon">'.$prefRank3.'</td>';
            $tr .= '<td class="center-icon">'.$prefRank4.'</td>';
            $tr .= '<td class="center-icon"></td>';
            $tr .= '</tr>';

            $localRank1 = render_html_helper::renderRank($stages[$i]['local_num_rank_stage1'], '../../img/icons');
            $localRank2 = render_html_helper::renderRank($stages[$i]['local_num_rank_stage2'], '../../img/icons');
            $localRank3 = render_html_helper::renderRank($stages[$i]['local_num_rank_stage3'], '../../img/icons');
            $localRank4 = render_html_helper::renderRank($stages[$i]['local_num_rank_stage4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">地方</td>';
            $tr .= '<td class="center-icon">'.$localRank1.'</td>';
            $tr .= '<td class="center-icon">'.$localRank2.'</td>';
            $tr .= '<td class="center-icon">'.$localRank3.'</td>';
            $tr .= '<td class="center-icon">'.$localRank4.'</td>';
            $tr .= '<td class="center-icon"></td>';
            $tr .= '</tr>';

            $totalRank1 = render_html_helper::renderRank($stages[$i]['total_num_rank_stage1'], '../../img/icons');
            $totalRank2 = render_html_helper::renderRank($stages[$i]['total_num_rank_stage2'], '../../img/icons');
            $totalRank3 = render_html_helper::renderRank($stages[$i]['total_num_rank_stage3'], '../../img/icons');
            $totalRank4 = render_html_helper::renderRank($stages[$i]['total_num_rank_stage4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon table-title">全国</td>';
            $tr .= '<td class="center-icon">'.$totalRank1.'</td>';
            $tr .= '<td class="center-icon">'.$totalRank2.'</td>';
            $tr .= '<td class="center-icon">'.$totalRank3.'</td>';
            $tr .= '<td class="center-icon">'.$totalRank4.'</td>';
            $tr .= '<td class="center-icon"></td>';
            $tr .= '</tr>';


            $html = $tr . $html;
        }

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

        $avgHtml = '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon rowspan-col" rowspan="4">直近3年平均</td>';
        $avgHtml .= '<td class="center-icon table-title">新規患者数</td>';
        $avgHtml .= '<td class="center-icon">'.($avgStage1 ? ($avgStage1 . '人' . $percentStage1) : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.($avgStage2 ? ($avgStage2 . '人' . $percentStage2) : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.($avgStage3 ? ($avgStage3 . '人' . $percentStage3) : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.($avgStage4 ? ($avgStage4 . '人' . $percentStage4) : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(is_numeric($totalStage) ? ($totalStage.'人') : '-').'</td>';
        $avgHtml .= '</tr>';

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon table-title">都道府県</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefStage1'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefStage2'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefStage3'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgPrefStage4'], '../../img/icons').'</td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '</tr>';

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon table-title">地方</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalStage1'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalStage2'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalStage3'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgLocalStage4'], '../../img/icons').'</td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '</tr>';

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td class="center-icon table-title">全国</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalStage1'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalStage2'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalStage3'], '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank($avgData['avgGlobalStage4'], '../../img/icons').'</td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '</tr>';

        echo $html . $avgHtml;
        ?>
        </tbody>
    </table>
</div>
