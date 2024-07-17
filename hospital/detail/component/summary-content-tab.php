<?php

if (!isset($avgData)) {
    $avgData = [];
}
?>
<div class="summary-content-tab">
    <div class="treatment-results">
        <h3>治療実績 (直近3年平均)</h3>
        <div class="table-responsive">
            <table class="table table-bordered overflow-auto">
                <thead>
                <tr>
                    <th></th>
                    <th>実績値</th>
                    <th>都道府県</th>
                    <th>地方</th>
                    <th>全国</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="criteria">年間入院患者数</td>
                    <td class="center-icon"><?php echo ($avgData['avgDpc'] ? $avgData['avgDpc'] . '人' : '-') ?></td>
                    <td class="center-icon">
                        <?php
                        if (in_array($avgData['avgPrefDpcRank'] ?? null, [1, 2, 3])) {
                            echo '<img src="../../img/icons/rank' . $avgData['avgPrefDpcRank'] . '.png" alt="rank-img">';
                        } else {
                            echo ($avgData['avgPrefDpcRank']) ? $avgData['avgPrefDpcRank'] . '位' : '-';
                        }
                        ?>
                    </td>
                    <td class="center-icon">
                        <?php
                        if (in_array($avgData['avgAreaDpcRank'] ?? null, [1, 2, 3])) {
                            echo '<img src="../../img/icons/rank' . $avgData['avgAreaDpcRank'] . '.png" alt="rank-img">';
                        } else {
                            echo ($avgData['avgAreaDpcRank']) ? $avgData['avgAreaDpcRank'] . '位' : '-';
                        }
                        ?>
                    </td>
                    <td class="center-icon">
                        <?php
                        if (in_array($avgData['avgGlobalDpcRank'] ?? null, [1, 2, 3])) {
                            echo '<img src="../../img/icons/rank' . $avgData['avgGlobalDpcRank'] . '.png" alt="rank-img">';
                        } else {
                            echo ($avgData['avgGlobalDpcRank']) ? $avgData['avgGlobalDpcRank'] . '位' : '-';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="criteria">年間新規患者数</td>
                    <td class="center-icon"><?php echo ($avgData['avgNewNum'] ? $avgData['avgNewNum'] . '人' : '-') ?></td>
                    <td class="center-icon">
                        <?php
                        if (in_array($avgData['avgPrefNewNumRank'] ?? null, [1, 2, 3])) {
                            echo '<img src="../../img/icons/rank' . $avgData['avgPrefNewNumRank'] . '.png" alt="rank-img">';
                        } else {
                            echo ($avgData['avgPrefNewNumRank']) ? $avgData['avgPrefNewNumRank'] . '位' : '-';
                        }
                        ?>
                    </td>
                    <td class="center-icon">
                        <?php
                        if (in_array($avgData['avgLocalNewNumRank'] ?? null, [1, 2, 3])) {
                            echo '<img src="../../img/icons/rank' . $avgData['avgLocalNewNumRank'] . '.png" alt="rank-img">';
                        } else {
                            echo ($avgData['avgLocalNewNumRank']) ? $avgData['avgLocalNewNumRank'] . '位' : '-';
                        }
                        ?>
                    </td>
                    <td class="center-icon">
                        <?php
                        if (in_array($avgData['avgGlobalNewNumRank'] ?? null, [1, 2, 3])) {
                            echo '<img src="../../img/icons/rank' . $avgData['avgGlobalNewNumRank'] . '.png" alt="rank-img">';
                        } else {
                            echo ($avgData['avgGlobalNewNumRank']) ? $avgData['avgGlobalNewNumRank'] . '位' : '-';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="criteria">5年後生存率数</td>
                    <td class="center-icon"><?php echo ($avgData['avgSurvivalRate'] ?? '-') ?></td>
                    <td class="center-icon">
                        <?php
                        if (in_array($avgData['avgPrefRate'] ?? null, [1, 2, 3])) {
                            echo '<img src="../../img/icons/rank' . $avgData['avgPrefRate'] . '.png" alt="rank-img">';
                        } else {
                            echo ($avgData['avgPrefRate']) ? $avgData['avgPrefRate'] . '位' : '-';
                        }
                        ?>
                    </td>
                    <td class="center-icon">
                        <?php
                        if (in_array($avgData['avgLocalRate'] ?? null, [1, 2, 3])) {
                            echo '<img src="../../img/icons/rank' . $avgData['avgLocalRate'] . '.png" alt="rank-img">';
                        } else {
                            echo ($avgData['avgLocalRate']) ? $avgData['avgLocalRate'] . '位' : '-';
                        }
                        ?>
                    </td>
                    <td class="center-icon">
                        <?php
                        if (in_array($avgData['avgGlobalRate'] ?? null, [1, 2, 3])) {
                            echo '<img src="../../img/icons/rank' . $avgData['avgGlobalRate'] . '.png" alt="rank-img">';
                        } else {
                            echo ($avgData['avgGlobalRate']) ? $avgData['avgGlobalRate'] . '位' : '-';
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="overall-rating text-left">
        <label for="overall"><h3>総評</h3></label>
        <input type="text" id="overall" name="overall" class="form-control w-50 mx-auto" disabled>
    </div>
</div>
