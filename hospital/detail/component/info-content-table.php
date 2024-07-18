<?php

if (!isset($infoHospital)) {
    $infoHospital = [];
}
?>
<div class="table-responsive" style="overflow-y: hidden">
    <table class="table table-info">
        <tbody>
        <tr>
            <th>医療機関名</th>
            <td><?php echo $infoHospital['name'] ?? '' ?></td>
        </tr>
        <tr>
            <th>住所</th>
            <td><?php echo $infoHospital['tel'] ?? '' ?></td>
        </tr>
        <tr>
            <th>代表電話番号</th>
            <td><?php echo $infoHospital['address'] ?? '' ?></td>
        </tr>
        <tr>
            <th>公式HP</th>
            <td><a target="_blank" href="<?php echo $infoHospital['hpUrl'] ?? '#' ?>"><?php echo $infoHospital['hpUrl'] ?? '' ?></a></td>
        </tr>
        <tr>
            <th>外科URL <i style="color: red" class="fa fa-question-circle"></i></th>
            <td><a target="_blank" href="https://www.juntendo.ac.jp/hospital/clinic/cancer/patient/method/comedicall.html">https://www.juntendo.ac.jp/hospital/clinic/cancer/patient/method/comedicall.html</a></td>
        </tr>
        <tr>
            <th>内科URL <i style="color: red" class="fa fa-question-circle"></i></th>
            <td><a target="_blank" href="https://www.juntendo.ac.jp/hospital/common/pdf/patient/hospitalization/roomguide/shitsuryou sp.pdf">https://www.juntendo.ac.jp/hospital/common/pdf/patient/hospitalization/roomguide/shitsuryou sp.pdf</a></td>
        </tr>
        <tr>
            <th>がん相談支援センターURL</th>
            <td><a target="_blank" href="<?php echo $infoHospital['supportUrl'] ?? '#' ?>"><?php echo $infoHospital['supportUrl'] ?? '' ?></a></td>
        </tr>
        <tr>
            <th>特別室</th>
            <td><a target="_blank" href="<?php echo $infoHospital['specialClinicUrl'] ?? '#' ?>"><?php echo $infoHospital['specialClinicUrl'] ?? '' ?></a></td>
        </tr>
        </tbody>
    </table>
</div>
