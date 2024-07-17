<?php

if (!isset($infoTreatment)) {
    $infoTreatment = [];
}
?>
<div class="treatment-content-tab">
    <div class="table-responsive" style="overflow-y: hidden">
        <table class="table table-info table-treatment overflow-auto">
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
                <th>業者的治療体制の状況 <i style="color: red" class="fa fa-question-circle"></i></th>
                <td><span class="badge bg-secondary">あり</span></td>
            </tr>
            <tr>
                <th>各区の医療状況 <i style="color: red" class="fa fa-question-circle"></i></th>
                <td>
                    <p><span class="badge bg-secondary">あり</span></p>
                    <p>外科：坂本 直人</p>
                    <p>放射線科：牧元 信夫</p>
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
                <th>特別治療の提供状況</th>
                <td>
                    <p><b><?php echo $infoTreatment['treatment'] ? nl2br(e($infoTreatment['treatment'])) : '<span class="badge bg-warning">なし</span>' ?></b></p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
