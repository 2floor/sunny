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
            <th class="table-title col-xs-2">集計対象者数</th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-2">生在率係数</th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
            <th class="table-title col-xs-1"></th>
        </tr>
        </thead>
        <thead>
        <tr class="border-top border-bottom">
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-1">都道府県</th>
            <th class="table-title col-xs-1">地方</th>
            <th class="table-title col-xs-1">全国</th>
            <th class="table-title col-xs-2"></th>
            <th class="table-title col-xs-1">都道府県</th>
            <th class="table-title col-xs-1">地方</th>
            <th class="table-title col-xs-1">全国</th>
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
            $tr .= '<td class="criteria">'.($survivals[$i]['year'] ? ($survivals[$i]['year'] . '-' . ($survivals[$i]['year'] + 1)) : '-').'</td>';
            $tr .= '<td>' . ($survivals[$i]['total_num'] ? $survivals[$i]['total_num'] . '人' : '-') . '</td>';
            $tr .= '<td>'.$prefNumRank.'</td>';
            $tr .= '<td>'.$localNumRank.'</td>';
            $tr .= '<td>'.$totalNumRank.'</td>';
            $tr .= '<td>' . ($survivals[$i]['survival_rate'] ?? '-') . '</td>';
            $tr .= '<td>' . $prefRateRank . '</td>';
            $tr .= '<td>' . $localRateRank . '</td>';
            $tr .= '<td>' . $totalRateRank . '</td>';
            $tr .= '<tr>';

            $html = $tr . $html;
        }

        echo $html;
        ?>
        <tr class="border-top border-bottom">
            <td>直近3年平均</td>
            <td>
                <?php
                    $avgNum = $survivals->avg('total_num');
                    echo ($avgNum ? (round($avgNum) . '人') : '-');
                ?>
            </td>
            <td>
                <?php
                    $avgPrefNumRank = $survivals->avg('pref_stage_total_taget');
                    echo render_html_helper::renderRank(($avgPrefNumRank ? round($avgPrefNumRank) : null), '../../img/icons')
                ?>
            </td>
            <td>
                <?php
                    $avgLocalNumRank = $survivals->avg('local_stage_total_taget');
                    echo render_html_helper::renderRank(($avgLocalNumRank ? round($avgLocalNumRank) : null), '../../img/icons')
                ?>
            </td>
            <td>
                <?php
                    $avgTotalNumRank = $survivals->avg('total_stage_total_taget');
                    echo render_html_helper::renderRank(($avgTotalNumRank ? round($avgTotalNumRank) : null), '../../img/icons')
                ?>
            </td>
            <td><?php echo ($avgData['avgSurvivalRate'] ?? '-') ?></td>
            <td>
                <?php
                    $avgPrefRate = render_html_helper::renderRank($avgData['avgPrefRate'], '../../img/icons');
                    echo $avgPrefRate;
                ?>
            </td>
            <td>
                <?php
                    $avgLocalRate = render_html_helper::renderRank($avgData['avgLocalRate'], '../../img/icons');
                    echo $avgLocalRate;
                ?>
            </td>
            <td>
                <?php
                    $avgGlobalRate = render_html_helper::renderRank($avgData['avgGlobalRate'], '../../img/icons');
                    echo $avgGlobalRate;
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>