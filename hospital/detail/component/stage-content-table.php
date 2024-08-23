<?php

if (!isset($stages)) {
    $stages = [];
}

if (!isset($avgData)) {
    $avgData = [];
}
?>
<div class="table-responsive">
    <table class="table stage-tb">
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title col-xs-4">年度</th>
            <th class="table-title col-xs-2 center-icon">統計値</th>
            <th class="table-title col-xs-2 center-icon">都道府県</th>
            <th class="table-title col-xs-2 center-icon">地方</th>
            <th class="table-title col-xs-2 center-icon">全国</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {
            $prefRank = render_html_helper::renderRank($stages[$i]['pref_num_rank'], '../../img/icons');
            $localRank = render_html_helper::renderRank($stages[$i]['local_num_rank'], '../../img/icons');
            $totalRank = render_html_helper::renderRank($stages[$i]['total_num_rank'], '../../img/icons');

            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td class="criteria">'.(($stages[$i]['year'] != null && $stages[$i]['year'] != '') ? ($stages[$i]['year'] . '年') : '-').'</td>';
            $tr .= '<td class="center-icon">' . (($stages[$i]['total_num_new'] != null && $stages[$i]['total_num_new'] != '') ? $stages[$i]['total_num_new'] . '人' : '-') . '</td>';
            $tr .= '<td class="center-icon">'.$prefRank.'</td>';
            $tr .= '<td class="center-icon">'.$localRank.'</td>';
            $tr .= '<td class="center-icon">'.$totalRank.'</td>';
            $tr .= '<tr>';

            $html = $tr . $html;
        }

        echo $html;
        ?>
        <tr class="border-top border-bottom">
            <td class="criteria">直近3年平均</td>
            <td class="center-icon"><?php echo (($avgData['avgNewNum'] != null && $avgData['avgNewNum'] != '') ? round($avgData['avgNewNum'], 1) . '人' : '-') ?></td>
            <td class="center-icon">
                <?php
                $avgPrefNewNumRank = render_html_helper::renderRank($avgData['avgPrefNewNumRank'], '../../img/icons');
                echo $avgPrefNewNumRank;
                ?>
            </td>
            <td class="center-icon">
                <?php
                $avgLocalNewNumRank = render_html_helper::renderRank($avgData['avgLocalNewNumRank'], '../../img/icons');
                echo $avgLocalNewNumRank;
                ?>
            </td>
            <td class="center-icon">
                <?php
                $avgGlobalNewNumRank = render_html_helper::renderRank($avgData['avgGlobalNewNumRank'], '../../img/icons');
                echo $avgGlobalNewNumRank;
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>