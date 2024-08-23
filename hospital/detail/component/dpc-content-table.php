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
            <th class="table-title col-xs-2 center-icon">統計價</th>
            <th class="table-title col-xs-2 center-icon">都道府</th>
            <th class="table-title col-xs-2 center-icon">地方</th>
            <th class="table-title col-xs-2 center-icon">全国</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {
//            $criteria = ($i == 0) ? '令和3年度' : (($i == 1) ? '令和2年度' : '令和元年度');
            $prefRank = render_html_helper::renderRank($dpcs[$i]['rank_pref_dpc'], '../../img/icons');
            $localRank = render_html_helper::renderRank($dpcs[$i]['rank_area_dpc'], '../../img/icons');
            $totalRank = render_html_helper::renderRank($dpcs[$i]['rank_nation_dpc'], '../../img/icons');

            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td class="criteria">'.(($dpcs[$i]['year'] != null && $dpcs[$i]['year'] != '') ? ($dpcs[$i]['year'] . '年') : '-').'</td>';
            $tr .= '<td class="center-icon">' . (($dpcs[$i]['n_dpc'] != null && $dpcs[$i]['n_dpc'] != '') ? $dpcs[$i]['n_dpc'] . '人' : '-') . '</td>';
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
            <td class="center-icon"><?php echo (($avgData['avgDpc'] != null && $avgData['avgDpc'] != '') ? round($avgData['avgDpc'], 1) . '人' : '-') ?></td>
            <td class="center-icon">
                <?php
                    $avgPrefDpcRank = render_html_helper::renderRank($avgData['avgPrefDpcRank'], '../../img/icons');
                    echo $avgPrefDpcRank;
                ?>
            </td>
            <td class="center-icon">
                <?php
                    $avgAreaDpcRank = render_html_helper::renderRank($avgData['avgAreaDpcRank'], '../../img/icons');
                    echo $avgAreaDpcRank;
                ?>
            </td>
            <td class="center-icon">
                <?php
                    $avgGlobalDpcRank = render_html_helper::renderRank($avgData['avgGlobalDpcRank'], '../../img/icons');
                    echo $avgGlobalDpcRank;
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
