<?php

if (!isset($stages)) {
    $stages = [];
}
?>

<div class="table-responsive">
    <table class="table stage-detail-tb">
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title">年度</th>
            <th class="table-title"></th>
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
            $percentStage1 = is_numeric($stages[$i]['stage_new1']) ? ('(' . (round($stages[$i]['stage_new1'] / $totalStage * 100, 2)) . '%)') : '';
            $percentStage2 = is_numeric($stages[$i]['stage_new2']) ? ('(' . (round($stages[$i]['stage_new2'] / $totalStage * 100, 2)) . '%)') : '';
            $percentStage3 = is_numeric($stages[$i]['stage_new3']) ? ('(' . (round($stages[$i]['stage_new3'] / $totalStage * 100, 2)) . '%)') : '';
            $percentStage4 = is_numeric($stages[$i]['stage_new4']) ? ('(' . (round($stages[$i]['stage_new4'] / $totalStage * 100, 2)) . '%)') : '';
            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td rowspan="4">'.(($stages[$i]['year'] != null && $stages[$i]['year'] !='') ? ($stages[$i]['year'] . '年') : '-').'</td>';
            $tr .= '<td>産患者数</td>';
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
            $tr .= '<td>都道府県</td>';
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
            $tr .= '<td>地方</td>';
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
            $tr .= '<td>全国</td>';
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

        $avgHtml = '<tr class="border-top border-bottom">';
        $avgHtml .= '<td rowspan="4">直近3年平均</td>';
        $avgHtml .= '<td>産患者数</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgStage1 != null && $avgStage1 != '') ? (round($avgStage1, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgStage2 != null && $avgStage2 != '') ? (round($avgStage2, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgStage3 != null && $avgStage3 != '') ? (round($avgStage3, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td class="center-icon">'.(($avgStage4 != null && $avgStage4 != '') ? (round($avgStage4, 1) . '人') : '-').'</td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '</tr>';

        $avgPrefRank1 = $stages->avg('pref_num_rank_stage1');
        $avgPrefRank2 = $stages->avg('pref_num_rank_stage2');
        $avgPrefRank3 = $stages->avg('pref_num_rank_stage3');
        $avgPrefRank4 = $stages->avg('pref_num_rank_stage4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>都道府県</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgPrefRank1 != null && $avgPrefRank1 != '') ? round($avgPrefRank1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgPrefRank2 != null && $avgPrefRank2 != '') ? round($avgPrefRank2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgPrefRank3 != null && $avgPrefRank3 != '') ? round($avgPrefRank3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgPrefRank4 != null && $avgPrefRank4 != '') ? round($avgPrefRank4) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '</tr>';

        $avgLocalRank1 = $stages->avg('local_num_rank_stage1');
        $avgLocalRank2 = $stages->avg('local_num_rank_stage2');
        $avgLocalRank3 = $stages->avg('local_num_rank_stage3');
        $avgLocalRank4 = $stages->avg('local_num_rank_stage4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>地方</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgLocalRank1 != null && $avgLocalRank1 != '') ? round($avgLocalRank1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgLocalRank2 != null && $avgLocalRank2 != '') ? round($avgLocalRank2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgLocalRank3 != null && $avgLocalRank3 != '') ? round($avgLocalRank3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgLocalRank4 != null && $avgLocalRank4 != '') ? round($avgLocalRank4) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '</tr>';

        $avgTotalRank1 = $stages->avg('total_num_rank_stage1');
        $avgTotalRank2 = $stages->avg('total_num_rank_stage2');
        $avgTotalRank3 = $stages->avg('total_num_rank_stage3');
        $avgTotalRank4 = $stages->avg('total_num_rank_stage4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>全国</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgTotalRank1 != null && $avgTotalRank1 != '') ? round($avgTotalRank1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgTotalRank2 != null && $avgTotalRank2 != '') ? round($avgTotalRank2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgTotalRank3 != null && $avgTotalRank3 != '') ? round($avgTotalRank3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td class="center-icon">'.render_html_helper::renderRank((($avgTotalRank4 != null && $avgTotalRank4 != '') ? round($avgTotalRank4) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td></td>';
        $avgHtml .= '</tr>';

        echo $html . $avgHtml;
        ?>
        </tbody>
    </table>
</div>
