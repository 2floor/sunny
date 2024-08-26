<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . "/../../required/page_init.php";
require_once __DIR__ . "/../../controller/front/f_hospital_ct.php";
require_once __DIR__ . "/../../logic/helpers/render_html_helper.php";

$page_init = new page_init();
$pageinfo = $page_init->get_info();

$id = $_GET['id'] ?? null;
$cancerId = $_GET['cancerId'] ?? null;

$f_hospital_ct = new f_hospital_ct();
$initData = $f_hospital_ct->getDetailById($id, $cancerId);
$cancerName = $initData['cancerName'] ?? '';
$avgData = $initData['avgData'] ?? [];
$yearSummary = $initData['yearSummary'] ?? [];
$infoHospital = $initData['infoHospital'] ?? [];
$infoTreatment = $initData['infoTreatment'] ?? [];
$stages = $initData['stages'] ?? [];
$dpcs = $initData['dpcs'] ?? [];
$survivals = $initData['survivals'] ?? [];
$averageSurv = $initData['averageSurv'] ?? [];
$remarks = $initData['remarks']['remarks'] ?? '';
$approved_time = '';

if ($initData['remarks']['approved_time']) {
    $date = new DateTime($initData['remarks']['approved_time']);
    $approved_time = $date->format('Y-m-d');
}
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
            <div class="search-result-header">
                <div class="search-result-header-right">
                    <span>チェックした対象を<br></span>
                    <button class="confirm-button" id="printButton">印刷</button>
                </div>
            </div>
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
                <li class="nav-item">
                    <a class="nav-link" id="notification-tab" data-toggle="tab" href="#notification" role="tab" aria-controls="notification" aria-selected="false">特記事項</a>
                </li>
            </ul>
            <div class="tab-content mt-3" id="tabContent">
                <div class="tab-pane fade active in" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                    <div class="summary-content-tab">
                        <div class="treatment-results">
                            <h3>治療実績 (直近3年平均)</h3>
                            <?php include 'component/summary-content-table.php'; ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                    <div class="info-content-tab">
                        <?php include 'component/info-content-table.php';?>
                    </div>
                </div>
                <div class="tab-pane fade" id="treatment" role="tabpanel" aria-labelledby="treatment-tab">
                    <div class="treatment-content-tab">
                        <?php include 'component/treatment-content-table.php'; ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="results" role="tabpanel" aria-labelledby="results-tab">
                    <div class="result-content-tab">
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    <h4 class="panel-title">
                                        年間入院患者数
                                        <span class="glyphicon glyphicon-chevron-down arrow"></span>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <?php include 'component/dpc-content-table.php'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    <h4 class="panel-title">
                                        年間新規入院患者数
                                        <span class="glyphicon glyphicon-chevron-down arrow"></span>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php include 'component/stage-content-table.php'; ?>
                                        <div><h3><b>ステージ別</b></h3></div>
                                        <?php include 'component/stage-detail-content-table.php'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                    <h4 class="panel-title">
                                        5年後生在率・生存幸係数
                                        <span class="glyphicon glyphicon-chevron-down arrow"></span>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php include 'component/survival-content-table.php'; ?>
                                        <div><h3><b>ステージ別</b></h3></div>
                                        <?php include 'component/survival-detail-content-table.php'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
                    <div class="notification-content-tab">
                        <div class="note-container">
                            <div>
                                <a class="btn btn-edit-memo" id="btnEditMemo">
                                    <img src="../../img/icons/green-edit.png" alt="Hospital Icon"><span class="text">印刷</span>
                                </a>
                            </div>
                            <div class="note-content">
                                <div class="header">
                                    <span class="label">更新日</span>
                                    <span class="date"><?= $approved_time?></span>
                                </div>
                                <div class="content">
                                    <div class="text-with-lines" id="text-content"><?= $remarks;?></div>
                                    <input type="hidden" name="remarks" value="<?= $remarks;?>">
                                </div>
                            </div>
                            <div>
                                <a class="btn btn-edit-memo" style="float: left; display:none; margin-bottom: 2px;" id="btnSaveMemo">
                                    <img src="../../img/icons/green-save.png" alt="Hospital Icon"><span class="text">保存</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loading-overlay">
                <div class="loading-spinner"></div>
            </div>
            <?php include '../../hospital/component/print-pdf-result.php';?>
        </div>
    </div>
</main>
</body>
<?php print $pageinfo->html_foot; ?>
<script type="text/javascript" src="../../assets/js/print-pdf.js"></script>
<script type="text/javascript" src="../../assets/js/hospital_detail.js"></script>
</html>
