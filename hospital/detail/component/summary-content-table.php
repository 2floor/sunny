<?php

if (!isset($avgData)) {
    $avgData = [];
}
if (!isset($yearSummary)) {
    $yearSummary = [];
}
?>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="table-title col-xs-4"></th>
            <th class="table-title col-xs-2">実績値</th>
            <th class="table-title col-xs-2">都道府県</th>
            <th class="table-title col-xs-2">地方</th>
            <th class="table-title col-xs-2">全国</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="criteria">年間入院患者数 <?php echo $yearSummary['dpc'] ? '(' .$yearSummary['dpc'] .'年)' : ''?></td>
            <td class="center-icon"><?php echo ($avgData['avgDpc'] ? $avgData['avgDpc'] . '人' : '-') ?></td>
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
        <tr>
            <td class="criteria">年間新規患者数 <?php echo $yearSummary['dpc'] ? '(' .$yearSummary['stage'] .'年)' : ''?></td>
            <td class="center-icon"><?php echo ($avgData['avgNewNum'] ? $avgData['avgNewNum'] . '人' : '-') ?></td>
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
        <tr>
            <td class="criteria">5年後生存率数 <?php echo $yearSummary['dpc'] ? '(' .$yearSummary['survival'] .'年)' : ''?></td>
            <td class="center-icon"><?php echo ($avgData['avgSurvivalRate'] ?? '-') ?></td>
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
