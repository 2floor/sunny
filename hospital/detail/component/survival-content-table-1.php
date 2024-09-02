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
            $prefNumRank = render_html_helper::renderRank($survivals[$i]['pref_stage_total_taget'], '../../img/icons');
            $localNumRank = render_html_helper::renderRank($survivals[$i]['local_stage_total_taget'], '../../img/icons');
            $totalNumRank = render_html_helper::renderRank($survivals[$i]['total_stage_total_taget'], '../../img/icons');

            $tr = '<tr class="border-top border-bottom">';
            $tr .= '<td class="center-icon">'.(($survivals[$i]['year'] != null && $survivals[$i]['year'] != '') ? (($survivals[$i]['year'] . '年') . '～' . (($survivals[$i]['year'] + 1)). '年') : '-').'</td>';
            $tr .= '<td class="center-icon">' . (($survivals[$i]['total_num'] != null && $survivals[$i]['total_num'] != '') ? $survivals[$i]['total_num'] . '人' : '-') . '</td>';
            $tr .= '<td class="center-icon">'.$prefNumRank.'</td>';
            $tr .= '<td class="center-icon">'.$localNumRank.'</td>';
            $tr .= '<td class="center-icon">'.$totalNumRank.'</td>';
            $tr .= '</tr>';

            $html = $tr . $html;
        }

        echo $html;
        ?>
        <tr class="border-top border-bottom">
            <td class="center-icon">直近3年平均</td>
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
        </tr>
        </tbody>
    </table>
</div>