<?php

if (!isset($infoHospital)) {
    $infoHospital = [];
}
?>
<div class="table-responsive" style="overflow-y: hidden">
    <table class="table table-info">
        <tbody>
        <tr>
            <th class="alg-center">医療機関名</th>
            <td><?php echo $infoHospital['name'] ?? '' ?></td>
        </tr>
        <tr>
            <th class="alg-center">住所</th>
            <td><?php echo $infoHospital['tel'] ?? '' ?></td>
        </tr>
        <tr>
            <th class="alg-center">代表電話番号</th>
            <td><?php echo $infoHospital['address'] ?? '' ?></td>
        </tr>
        <tr>
            <th class="alg-center">公式HP</th>
            <td><a target="_blank" href="<?php echo $infoHospital['hpUrl'] ?? '#' ?>"><?php echo $infoHospital['hpUrl'] ?? '' ?></a></td>
        </tr>
        <tr>
            <th class="alg-center">がん相談支援センターURL</th>
            <td><a target="_blank" href="<?php echo $infoHospital['supportUrl'] ?? '#' ?>"><?php echo $infoHospital['supportUrl'] ?? '' ?></a></td>
        </tr>
        <tr>
            <th class="alg-center">特別室</th>
            <td><a target="_blank" href="<?php echo $infoHospital['specialClinicUrl'] ?? '#' ?>"><?php echo $infoHospital['specialClinicUrl'] ?? '' ?></a></td>
        </tr>
        </tbody>
    </table>
</div>
