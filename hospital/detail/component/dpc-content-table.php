<?php

if (!isset($dpcs)) {
    $dpcs = [];
}

if (!isset($avgData)) {
    $avgData = [];
}
?>
<div class="table-responsive">
    <table class="table dpc-tb overflow-auto">
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title col-xs-4">年度</th>
            <th class="table-title col-xs-2">統計價</th>
            <th class="table-title col-xs-2">都道府</th>
            <th class="table-title col-xs-2">地方</th>
            <th class="table-title col-xs-2">全国</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {
            $criteria = ($i == 0) ? '令和3年度' : (($i == 1) ? '令和2年度' : '令和元年度');

            $prefRank = render_html_helper::renderRank($dpcs[$i]['rank_pref_dpc'], '../../img/icons');
            $localRank = render_html_helper::renderRank($dpcs[$i]['rank_area_dpc'], '../../img/icons');
            $totalRank = render_html_helper::renderRank($dpcs[$i]['rank_nation_dpc'], '../../img/icons');

            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td class="criteria">'.$criteria.'</td>';
            $tr .= '<td>' . ($dpcs[$i]['n_dpc'] ? $dpcs[$i]['n_dpc'] . '人' : '-') . '</td>';
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
            <td><?php echo ($avgData['avgDpc'] ? $avgData['avgDpc'] . '人' : '-') ?></td>
            <td>
                <?php
                    $avgPrefDpcRank = render_html_helper::renderRank($avgData['avgPrefDpcRank'], '../../img/icons');
                    echo $avgPrefDpcRank;
                ?>
            </td>
            <td>
                <?php
                    $avgAreaDpcRank = render_html_helper::renderRank($avgData['avgAreaDpcRank'], '../../img/icons');
                    echo $avgAreaDpcRank;
                ?>
            </td>
            <td>
                <?php
                    $avgGlobalDpcRank = render_html_helper::renderRank($avgData['avgGlobalDpcRank'], '../../img/icons');
                    echo $avgGlobalDpcRank;
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
