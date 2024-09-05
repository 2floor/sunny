<?php

if (!isset($infoTreatment)) {
    $infoTreatment = [];
}
?>
<div class="table-responsive" style="overflow-y: hidden">
    <table class="table table-info table-treatment">
        <tbody>
        <tr>
            <th class="alg-center">がん診療拠点区分</th>
            <td><?php echo $infoTreatment['hospitalType'] ?? '' ?></td>
        </tr>
        <tr>
            <th class="alg-center">がんゲノム病院区分</th>
            <td><?php echo $infoTreatment['hospitalGen'] ?? '' ?></td>
        </tr>
        <tr>
            <th class="alg-center">集学的治療体制の状況</th>
            <td>
                <?php
                    echo $infoTreatment['multiTreatment'] ? '<span class="badge bg-secondary">あり</span>' : '';
                ?>
            </td>
        </tr>
        <tr>
            <th class="alg-center">名医の在籍状況</th>
            <td>
                <?php
                    echo $infoTreatment['famousDoctor'] ? '<span class="badge bg-secondary">あり</span>' : '<span class="badge bg-warning">なし</span>';
                ?>
            </td>
        </tr>
        <tr>
            <th class="alg-center">先進医療の提供状況</th>
            <td>
                <?php
                echo $infoTreatment['hasAdvancedMedical'] ? '<span class="badge bg-secondary">あり</span>' : '<span class="badge bg-warning">なし</span>';
                ?>
                <br>
                <?php echo $infoTreatment['advancedMedical'] ? nl2br(e($infoTreatment['advancedMedical'])) : '' ?>
            </td>
        </tr>
        <tr>
            <th class="alg-center">特別な治療の提供状況</th>
            <td>
                <?php echo $infoTreatment['treatment'] ? nl2br(e($infoTreatment['treatment'])) : '<span class="badge bg-warning">なし</span>' ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
