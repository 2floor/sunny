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
            <th class="table-title">ステージI</th>
            <th class="table-title">ステージII</th>
            <th class="table-title">ステージIII</th>
            <th class="table-title">ステージIV</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {
            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td rowspan="4">'.($stages[$i]['year'] ?? '-').'</td>';
            $tr .= '<td>産患者数</td>';
            $tr .= '<td>'.($stages[$i]['stage_new1'] ? ($stages[$i]['stage_new1'] . '人') : '-').'</td>';
            $tr .= '<td>'.($stages[$i]['stage_new2'] ? ($stages[$i]['stage_new2'] . '人') : '-').'</td>';
            $tr .= '<td>'.($stages[$i]['stage_new3'] ? ($stages[$i]['stage_new3'] . '人') : '-').'</td>';
            $tr .= '<td>'.($stages[$i]['stage_new4'] ? ($stages[$i]['stage_new4'] . '人') : '-').'</td>';
            $tr .= '</tr>';


            $prefRank1 = render_html_helper::renderRank($stages[$i]['pref_num_rank_stage1'], '../../img/icons');
            $prefRank2 = render_html_helper::renderRank($stages[$i]['pref_num_rank_stage2'], '../../img/icons');
            $prefRank3 = render_html_helper::renderRank($stages[$i]['pref_num_rank_stage3'], '../../img/icons');
            $prefRank4 = render_html_helper::renderRank($stages[$i]['pref_num_rank_stage4'], '../../img/icons');


            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td>都道府県</td>';
            $tr .= '<td>'.$prefRank1.'</td>';
            $tr .= '<td>'.$prefRank2.'</td>';
            $tr .= '<td>'.$prefRank3.'</td>';
            $tr .= '<td>'.$prefRank4.'</td>';
            $tr .= '</tr>';

            $localRank1 = render_html_helper::renderRank($stages[$i]['local_num_rank_stage1'], '../../img/icons');
            $localRank2 = render_html_helper::renderRank($stages[$i]['local_num_rank_stage2'], '../../img/icons');
            $localRank3 = render_html_helper::renderRank($stages[$i]['local_num_rank_stage3'], '../../img/icons');
            $localRank4 = render_html_helper::renderRank($stages[$i]['local_num_rank_stage4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td>地方</td>';
            $tr .= '<td>'.$localRank1.'</td>';
            $tr .= '<td>'.$localRank2.'</td>';
            $tr .= '<td>'.$localRank3.'</td>';
            $tr .= '<td>'.$localRank4.'</td>';
            $tr .= '</tr>';

            $totalRank1 = render_html_helper::renderRank($stages[$i]['total_num_rank_stage1'], '../../img/icons');
            $totalRank2 = render_html_helper::renderRank($stages[$i]['total_num_rank_stage2'], '../../img/icons');
            $totalRank3 = render_html_helper::renderRank($stages[$i]['total_num_rank_stage3'], '../../img/icons');
            $totalRank4 = render_html_helper::renderRank($stages[$i]['total_num_rank_stage4'], '../../img/icons');

            $tr .= '<tr class="border-top border-bottom">';
            $tr .= '<td>全国</td>';
            $tr .= '<td>'.$totalRank1.'</td>';
            $tr .= '<td>'.$totalRank2.'</td>';
            $tr .= '<td>'.$totalRank3.'</td>';
            $tr .= '<td>'.$totalRank4.'</td>';
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
        $avgHtml .= '<td>'.($avgStage1 ? (round($avgStage1) . '人') : '-').'</td>';
        $avgHtml .= '<td>'.($avgStage2 ? (round($avgStage2) . '人') : '-').'</td>';
        $avgHtml .= '<td>'.($avgStage3 ? (round($avgStage3) . '人') : '-').'</td>';
        $avgHtml .= '<td>'.($avgStage4 ? (round($avgStage4) . '人') : '-').'</td>';
        $avgHtml .= '</tr>';

        $avgPrefRank1 = $stages->avg('pref_num_rank_stage1');
        $avgPrefRank2 = $stages->avg('pref_num_rank_stage2');
        $avgPrefRank3 = $stages->avg('pref_num_rank_stage3');
        $avgPrefRank4 = $stages->avg('pref_num_rank_stage4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>都道府県</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefRank1 ? round($avgPrefRank1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefRank2 ? round($avgPrefRank2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefRank3 ? round($avgPrefRank3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgPrefRank4 ? round($avgPrefRank4) : null), '../../img/icons').'</td>';
        $avgHtml .= '</tr>';

        $avgLocalRank1 = $stages->avg('local_num_rank_stage1');
        $avgLocalRank2 = $stages->avg('local_num_rank_stage2');
        $avgLocalRank3 = $stages->avg('local_num_rank_stage3');
        $avgLocalRank4 = $stages->avg('local_num_rank_stage4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>地方</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalRank1 ? round($avgLocalRank1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalRank2 ? round($avgLocalRank2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalRank3 ? round($avgLocalRank3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgLocalRank4 ? round($avgLocalRank4) : null), '../../img/icons').'</td>';
        $avgHtml .= '</tr>';

        $avgTotalRank1 = $stages->avg('total_num_rank_stage1');
        $avgTotalRank2 = $stages->avg('total_num_rank_stage2');
        $avgTotalRank3 = $stages->avg('total_num_rank_stage3');
        $avgTotalRank4 = $stages->avg('total_num_rank_stage4');

        $avgHtml .= '<tr class="border-top border-bottom">';
        $avgHtml .= '<td>全国</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalRank1 ? round($avgTotalRank1) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalRank2 ? round($avgTotalRank2) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalRank3 ? round($avgTotalRank3) : null), '../../img/icons').'</td>';
        $avgHtml .= '<td>'.render_html_helper::renderRank(($avgTotalRank4 ? round($avgTotalRank4) : null), '../../img/icons').'</td>';
        $avgHtml .= '</tr>';

        echo $html . $avgHtml;
        ?>
        </tbody>
    </table>
</div>
