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
            <th class="table-title col-xs-2">統計値</th>
            <th class="table-title col-xs-2">都道府県</th>
            <th class="table-title col-xs-2">地方</th>
            <th class="table-title col-xs-2">全国</th>
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
            $tr .= '<td class="criteria">'.($stages[$i]['year'] ?? '-').'</td>';
            $tr .= '<td>' . ($stages[$i]['total_num_new'] ? $stages[$i]['total_num_new'] . '人' : '-') . '</td>';
            $tr .= '<td>'.$prefRank.'</td>';
            $tr .= '<td>'.$localRank.'</td>';
            $tr .= '<td>'.$totalRank.'</td>';
            $tr .= '<tr>';

            $html = $tr . $html;
        }

        echo $html;
        ?>
        <tr class="border-top border-bottom">
            <td class="criteria">直近3年平均</td>
            <td><?php echo ($avgData['avgNewNum'] ? $avgData['avgNewNum'] . '人' : '-') ?></td>
            <td>
                <?php
                $avgPrefNewNumRank = render_html_helper::renderRank($avgData['avgPrefNewNumRank'], '../../img/icons');
                echo $avgPrefNewNumRank;
                ?>
            </td>
            <td>
                <?php
                $avgLocalNewNumRank = render_html_helper::renderRank($avgData['avgLocalNewNumRank'], '../../img/icons');
                echo $avgLocalNewNumRank;
                ?>
            </td>
            <td>
                <?php
                $avgGlobalNewNumRank = render_html_helper::renderRank($avgData['avgGlobalNewNumRank'], '../../img/icons');
                echo $avgGlobalNewNumRank;
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>