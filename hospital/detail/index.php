<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . "/../../required/page_init.php";
require_once __DIR__ . "/../../controller/front/f_hospital_ct.php";

$page_init = new page_init();
$pageinfo = $page_init->get_info();

$id = $_GET['id'] ?? null;
$cancerId = $_GET['cancerId'] ?? null;

$f_hospital_ct = new f_hospital_ct();
$initData = $f_hospital_ct->getDetailById($id, $cancerId);
$cancerName = $initData['cancerName'] ?? '';
$avgData = $initData['avgData'] ?? [];
$infoHospital = $initData['infoHospital'] ?? [];
$infoTreatment = $initData['infoTreatment'] ?? [];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php print $pageinfo->html_head; ?>
    <link rel="stylesheet" href="./../../assets/css/detail_hospital.css">
</head>

<body>
<?php print $pageinfo->header; ?>

<main>
    <div class="container">
        <div class="main-detail">
            <div class="title">
                <h3 class="text-center"><?php echo $cancerName; ?></h3>
                <h1 class="text-center"><?php echo $infoHospital['name'] ?? ''; ?></h1>
            </div>


            <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="true">サマリー</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">医療機関情報</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="treatment-tab" data-toggle="tab" href="#treatment" role="tab" aria-controls="treatment" aria-selected="false">提供する治療情報</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="results-tab" data-toggle="tab" href="#results" role="tab" aria-controls="results" aria-selected="false">治療実績</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="tabContent">
                <div class="tab-pane fade active in" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                    <?php include 'component/summary-content-tab.php'; ?>
                </div>
                <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                    <?php include 'component/info-content-tab.php'; ?>
                </div>
                <div class="tab-pane fade" id="treatment" role="tabpanel" aria-labelledby="treatment-tab">
                    <?php include 'component/treatment-content-tab.php'; ?>
                </div>
                <div class="tab-pane fade" id="results" role="tabpanel" aria-labelledby="results-tab">
                    <p>治療実績の詳細がここに表示されます。</p>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
<?php print $pageinfo->html_foot; ?>
</html>
