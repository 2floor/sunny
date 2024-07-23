<?php

if (!isset($infoTreatment)) {
    $infoTreatment = [];
}
?>
<div class="table-responsive" style="overflow-y: hidden">
    <table class="table table-info table-treatment">
        <tbody>
        <tr>
            <th>がん診療拠点区分</th>
            <td><?php echo $infoTreatment['hospitalType'] ?? '' ?></td>
        </tr>
        <tr>
            <th>がんゲノム病院区分</th>
            <td><?php echo $infoTreatment['hospitalGen'] ?? '' ?></td>
        </tr>
        <tr>
            <th>集学的治療体制の状況</th>
            <td>
                <p>
                    <?php
                        echo $infoTreatment['multiTreatment'] ? '<span class="badge bg-secondary">あり</span>' : '<span class="badge bg-warning">なし</span>';
                    ?>
                </p>
            </td>
        </tr>
        <tr>
            <th>名医の在籍状況</th>
            <td>
                <p>
                    <?php
                        echo $infoTreatment['famousDoctor'] ? '<span class="badge bg-secondary">あり</span>' : '<span class="badge bg-warning">なし</span>';
                    ?>
                </p>
            </td>
        </tr>
        <tr>
            <th>先進医療の提供状況</th>
            <td>
                <p>
                    <?php
                    echo $infoTreatment['hasAdvancedMedical'] ? '<span class="badge bg-secondary">あり</span>' : '<span class="badge bg-warning">なし</span>';
                    ?>
                </p>
                <p><?php echo $infoTreatment['advancedMedical'] ? nl2br(e($infoTreatment['advancedMedical'])) : '' ?></p>
            </td>
        </tr>
        <tr>
            <th>特別な治療の提供状況</th>
            <td>
                <p><b><?php echo $infoTreatment['treatment'] ? nl2br(e($infoTreatment['treatment'])) : '<span class="badge bg-warning">なし</span>' ?></b></p>
            </td>
        </tr>
        </tbody>
    </table>
</div>
