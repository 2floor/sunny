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
        <tbody>
        <tr class="border-top border-bottom">
            <td class="table-title center-icon">年度</td>
            <td class="table-title center-icon">生在率係数</td>
            <td class="table-title center-icon">都道府県</td>
            <td class="table-title center-icon">地方</td>
            <td class="table-title center-icon">全国</td>
        </tr>
        <?php
        $html = '';
        for ($i = 0; $i < 3; $i++) {

            $prefRateRank = render_html_helper::renderRank($survivals[$i]['pref_survival_rate'], '../../img/icons');
            $localRateRank = render_html_helper::renderRank($survivals[$i]['local_survival_rate'], '../../img/icons');
            $totalRateRank = render_html_helper::renderRank($survivals[$i]['total_survival_rate'], '../../img/icons');

            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['year'] != null && $survivals[$i]['year'] != '') ? (($survivals[$i]['year'] . '年') . '～' . (($survivals[$i]['year'] + 1)). '年') : '-').'</td>';
            $tr .= '<td class="center-icon">' . (($survivals[$i]['survival_rate'] != null && $survivals[$i]['survival_rate'] != '') ? $survivals[$i]['survival_rate'] : '-') . '</td>';
            $tr .= '<td class="center-icon">' . $prefRateRank . '</td>';
            $tr .= '<td class="center-icon">' . $localRateRank . '</td>';
            $tr .= '<td class="center-icon">' . $totalRateRank . '</td>';
            $tr .= '</tr>';

            $html = $tr . $html;
        }

        echo $html;
        ?>
        <tr class="border-top border-bottom">
            <td class="center-icon">直近3年平均</td>
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