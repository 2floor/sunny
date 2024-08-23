<?php

if (!isset($survivals)) {
    $survivals = [];
}
if (!isset($avgData)) {
    $avgData = [];
}
?>
<div class="table-responsive">
    <table class="table surv-tb">
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title col-xs-2">年度</th>
            <th class="table-title col-xs-2 center-icon">集計対象者数</th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-2 center-icon">生在率係数</th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
        </tr>
        </thead>
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-1 center-icon">都道府県</th>
            <th class="table-title col-xs-1 center-icon">地方</th>
            <th class="table-title col-xs-1 center-icon">全国</th>
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-1 center-icon">都道府県</th>
            <th class="table-title col-xs-1 center-icon">地方</th>
            <th class="table-title col-xs-1 center-icon">全国</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {
            $prefNumRank = render_html_helper::renderRank($survivals[$i]['pref_stage_total_taget'], '../../img/icons');
            $localNumRank = render_html_helper::renderRank($survivals[$i]['local_stage_total_taget'], '../../img/icons');
            $totalNumRank = render_html_helper::renderRank($survivals[$i]['total_stage_total_taget'], '../../img/icons');

            $prefRateRank = render_html_helper::renderRank($survivals[$i]['pref_survival_rate'], '../../img/icons');
            $localRateRank = render_html_helper::renderRank($survivals[$i]['local_survival_rate'], '../../img/icons');
            $totalRateRank = render_html_helper::renderRank($survivals[$i]['total_survival_rate'], '../../img/icons');

            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td class="criteria">'.(($survivals[$i]['year'] != null && $survivals[$i]['year'] != '') ? (($survivals[$i]['year'] . '年') . '～' . (($survivals[$i]['year'] + 1)). '年') : '-').'</td>';
            $tr .= '<td class="center-icon">' . (($survivals[$i]['total_num'] != null && $survivals[$i]['total_num'] != '') ? $survivals[$i]['total_num'] . '人' : '-') . '</td>';
            $tr .= '<td class="center-icon">'.$prefNumRank.'</td>';
            $tr .= '<td class="center-icon">'.$localNumRank.'</td>';
            $tr .= '<td class="center-icon">'.$totalNumRank.'</td>';
            $tr .= '<td class="center-icon">' . (($survivals[$i]['survival_rate'] != null && $survivals[$i]['survival_rate'] != '') ? $survivals[$i]['survival_rate'] : '-') . '</td>';
            $tr .= '<td class="center-icon">' . $prefRateRank . '</td>';
            $tr .= '<td class="center-icon">' . $localRateRank . '</td>';
            $tr .= '<td class="center-icon">' . $totalRateRank . '</td>';
            $tr .= '<tr>';

            $html = $tr . $html;
        }

        echo $html;
        ?>
        <tr class="border-top border-bottom">
            <td>直近3年平均</td>
            <td class="center-icon">
                <?php
                    $avgNum = $survivals->avg('total_num');
                    echo (($avgNum != null && $avgNum != '') ? (round($avgNum, 1) . '人') : '-');
                ?>
            </td>
            <td class="center-icon">
                <?php
                    $avgPrefNumRank = $survivals->avg('pref_stage_total_taget');
                    echo render_html_helper::renderRank((($avgPrefNumRank != null && $avgPrefNumRank != '') ? round($avgPrefNumRank) : null), '../../img/icons')
                ?>
            </td>
            <td class="center-icon">
                <?php
                    $avgLocalNumRank = $survivals->avg('local_stage_total_taget');
                    echo render_html_helper::renderRank((($avgLocalNumRank != null && $avgLocalNumRank != '') ? round($avgLocalNumRank) : null), '../../img/icons')
                ?>
            </td>
            <td class="center-icon">
                <?php
                    $avgTotalNumRank = $survivals->avg('total_stage_total_taget');
                    echo render_html_helper::renderRank((($avgTotalNumRank != null && $avgTotalNumRank != '') ? round($avgTotalNumRank) : null), '../../img/icons')
                ?>
            </td>
            <td class="center-icon"><?php echo (($avgData['avgSurvivalRate'] != null && $avgData['avgSurvivalRate'] != '') ? round($avgData['avgSurvivalRate'], 2) : '-') ?></td>
            <td class="center-icon">
                <?php
                    $avgPrefRate = render_html_helper::renderRank($avgData['avgPrefRate'], '../../img/icons');
                    echo $avgPrefRate;
                ?>
            </td>
            <td class="center-icon">
                <?php
                    $avgLocalRate = render_html_helper::renderRank($avgData['avgLocalRate'], '../../img/icons');
                    echo $avgLocalRate;
                ?>
            </td>
            <td class="center-icon">
                <?php
                    $avgGlobalRate = render_html_helper::renderRank($avgData['avgGlobalRate'], '../../img/icons');
                    echo $avgGlobalRate;
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>