<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . "/../../required/page_init.php";
require_once __DIR__ . "/../../controller/front/f_hospital_ct.php";
require_once __DIR__ . "/../../logic/helpers/render_html_helper.php";
require_once __DIR__ . "/../../logic/front/auth_logic.php";

$auth_logic = new auth_logic();
$permVDH = $auth_logic->check_permission('view.detail.hospital');

if (!$permVDH) {
    header("Location: " . BASE_URL . "error/403_page.php");
    exit();
}

$permPrH = $auth_logic->check_permission('print.hospital.pdf');
$permAHN = $auth_logic->check_permission('add.hospital.note');
$isTooltip = $auth_logic->check_is_show_tooltip();

$page_init = new page_init();
$pageinfo = $page_init->get_info();

$id = $_GET['id'] ?? null;
$cancerId = $_GET['cancerId'] ?? null;

$f_hospital_ct = new f_hospital_ct();
$initData = $f_hospital_ct->getDetailById($id, $cancerId);
$cancerName = $initData['cancerName'] ?? '';
$cancerNameDPC = $initData['cancerNameDPC'] ?? '';
$cancerNameStage = $initData['cancerNameStage'] ?? '';
$cancerNameSurv = $initData['cancerNameSurv'] ?? '';
$avgData = $initData['avgData'] ?? [];
$yearSummary = $initData['yearSummary'] ?? [];
$infoHospital = $initData['infoHospital'] ?? [];
$infoTreatment = $initData['infoTreatment'] ?? [];
$stages = $initData['stages'] ?? [];
$dpcs = $initData['dpcs'] ?? [];
$survivals = $initData['survivals'] ?? [];
$averageSurv = $initData['averageSurv'] ?? [];
$remarks = $initData['remarks'] ?? [];
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
            <?php if ($permPrH) { ?>
            <div class="search-result-header">
                <div class="search-result-header-right">
                    <a class="btn btn-edit-memo" id="printButton" style="border-color: #0A74B0">
                        <img src="../../img/icons/print-icon.png" alt="Hospital Icon"><span class="text">印刷</span>
                    </a>
                </div>
            </div>
            <?php } ?>
            <div class="title">
                <h3 class="text-center"><?php echo $cancerName; ?></h3>
                <h1 class="text-center"><?php echo $infoHospital['name'] ?? ''; ?></h1>
            </div>
            <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="true"><span>サマリー</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false"><span>医療機関情報</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="treatment-tab" data-toggle="tab" href="#treatment" role="tab" aria-controls="treatment" aria-selected="false"><span>提供する治療情報</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="results-tab" data-toggle="tab" href="#results" role="tab" aria-controls="results" aria-selected="false"><span>治療実績</span></a>
                </li>
                <?php if ($permAHN) { ?>
                <li class="nav-item">
                    <a class="nav-link" id="notification-tab" data-toggle="tab" href="#notification" role="tab" aria-controls="notification" aria-selected="false"><span>特記事項</span></a>
                </li>
                <?php } ?>
            </ul>
            <div class="tab-content mt-3" id="tabContent">
                <div class="tab-pane fade active in" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                    <div class="summary-content-tab">
                        <div class="treatment-results">
                            <div class="header">
                                <h4>治療実績 (直近3年平均)</h4>
                            </div>
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
                        <div class="nav-section">
                            <a href="#dpc-table" id="navDpcTb"><img src="../../img/icons/bed-icon.png" alt="Hospital Icon"><span>年間入院患者数</span></a>
                            <a href="#stage-table" id="navStageTb"><img src="../../img/icons/list-icon.png" alt="Hospital Icon"><span>年間新規患者数</span></a>
                            <a href="#survival-table" id="navSurvivalTb"><img src="../../img/icons/healthy-icon.png" alt="Hospital Icon"><span>生存率係数・ステージ別5年実測生存率</span></a>
                        </div>
                        <section id="dpc-table" class="table-sec">
                            <div class="header">
                                <h4>年間入院患者数 ・ <?= ($cancerNameDPC ? $cancerNameDPC : $cancerName) ?>
                                    <?php if ($isTooltip) {?>
                                    <div class="custom-tooltip-container">
                                        <i style="font-size: 20px" class="fa fa-question-circle-o" aria-hidden="true"></i>
                                        <div class="custom-tooltip">
                                            <div class="custom-tooltip-content">
                                                厚生労働省の指導による包括評価制度（DPC）導入の影響評価に関する調査「退院患者調査」に報告されている、当該施設における特定のがんに対しての1年間の退院（≒入院）した患者数です。年間入院患者数が多い施設ほど、そのがんに対する治療実績が豊富であると考えられます。
                                            </div>
                                            <a target="_blank" href="../../faq.php" class="custom-tooltip-more">もっと見る →</a>
                                        </div>
                                    </div>
                                    <?php }?>
                                </h4>
                            </div>
                            <?php include 'component/dpc-content-table.php'; ?>
                        </section>

                        <section id="stage-table" class="table-sec">
                            <div class="header">
                                <h4>年間新規患者数・ <?= ($cancerNameStage ?  $cancerNameStage : $cancerName) ?>
                                    <?php if ($isTooltip) {?>
                                    <div class="custom-tooltip-container">
                                        <i style="font-size: 20px" class="fa fa-question-circle-o" aria-hidden="true"></i>
                                        <div class="custom-tooltip">
                                            <div class="custom-tooltip-content">
                                                国立がん研究センターでは、がん登録等の推進に関する法律及び院内がん登録の実施に係る指針(厚生労働省告示470号)に基づいてデータ収集をされている、協力している病院における各がん種における病期（ステージ）別の新規の患者数です。どの病期の新規の患者を受け入れているか、などが分かります。
                                            </div>
                                            <a target="_blank" href="../../faq.php" class="custom-tooltip-more">もっと見る →</a>
                                        </div>
                                    </div>
                                    <?php }?>
                                </h4>
                            </div>
                            <?php include 'component/stage-content-table.php'; ?>
                            <?php include 'component/stage-detail-content-table.php'; ?>
                        </section>

                        <section id="survival-table" class="table-sec">
                            <div class="header">
                                <h4>生存率係数・ステージ別5年実測生存率・ <?= ($cancerNameSurv ? $cancerNameSurv : $cancerName) ?>
                                    <?php if ($isTooltip) {?>
                                    <div class="custom-tooltip-container">
                                        <i style="font-size: 20px" class="fa fa-question-circle-o" aria-hidden="true"></i>
                                        <div class="custom-tooltip">
                                            <div class="custom-tooltip-content">
                                                国立がん研究センターが全国のがん診療連携拠点病院から院内がん登録情報 を収集しており、5年生存率報告書では、がん診療連携拠点病院等全体での病期、 治療の実施別に生存率を推定するだけでなく、都道府県、施設別の生存率集計にお いても、がんの病期別に生存率を推定しています。 この5年生存率データを基に、日本経済新聞社が算出した係数で、全国平均を100とし、数字が大きいほど生存率が高い病院と考えられる指標です。
                                            </div>
                                            <a target="_blank" href="../../faq.php" class="custom-tooltip-more">もっと見る →</a>
                                        </div>
                                    </div>
                                    <?php }?>
                                </h4>
                            </div>
                            <div class="note-sec">
                                <h5>集計対象者数</h5>
                            </div>
                            <?php include 'component/survival-content-table-1.php'; ?>
                            <?php include 'component/survival-detail-content-table-1.php'; ?>
                            <div class="note-sec">
                                <h5>生存率係数・ステージ別5年実測生存率</h5>
                            </div>
                            <?php include 'component/survival-content-table-2.php'; ?>
                            <?php include 'component/survival-detail-content-table-2.php'; ?>
                        </section>
                    </div>
                </div>
                <div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
                    <div class="notification-content-tab">
                        <div class="memo-container">
                            <div class="card-container" id="cardContainer">
                                <?php
                                    if (!empty($remarks)) {
                                        foreach ($remarks  as $remark) {
                                            echo '<div class="card">
                                                <div class="card-content">
                                                    <div class="card-header">
                                                       <div class="author"><span>'.$remark['author'].'</span><span>作成日時: '.$remark['approved_time'].'</span>'.(($remark['updated_at'] && $remark['updated_at'] != $remark['approved_time']) ? '<span>更新日時: '.$remark['updated_at'].'</span>' : '').'</div>
                                                       <div class="card-actions">
                                                            <a class="btnEditMemo">
                                                                <img src="../../img/icons/edit-memo-icon.png" alt="alt">
                                                            </a>
                                                            <a class="btnSaveEditMemo" style="display: none" data-remark-id="'.$remark['id'].'">
                                                                <img src="../../img/icons/blue-save.png" alt="alt">
                                                            </a>
                                                            <a class="btnDeleteMemo" data-remark-id="'.$remark['id'].'">
                                                                <img src="../../img/icons/delete-memo-icon.png" alt="alt">
                                                            </a>
                                                       </div>
                                                    </div>
                                                    <div class="card-body">
                                                       <p>'.($remark['remarks'] ?? '').'</p>
                                                    </div>
                                                </div>
                                              </div>';
                                        }
                                    } else {
                                        echo '<p id="NoMemoText">まだ特記事項を追加していません!</p>';
                                    }
                                ?>
                            </div>
                            <div class="add-memo-container">
                                <div class="card-add">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <textarea id="text-content"  placeholder="新規の特記事項をここに入力ください..." class="text-with-lines"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a class="btn btn-add-memo" id="btnSaveMemo">
                                        <span class="text">追加</span>
                                    </a>
                                </div>
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
