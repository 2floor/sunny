<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . "/../required/page_init.php";
require_once __DIR__ . "/../controller/front/f_hospital_ct.php";

$page_init = new page_init();
$pageinfo = $page_init->get_info();

$f_hospital_ct = new f_hospital_ct();
$initData = $f_hospital_ct->searchPageIndex('detail');
$cancerData = $initData['cancer'] ?? [];
$areaData = $initData['area'] ?? [];
$category = $initData['category'] ?? [];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php print $pageinfo->html_head; ?>
</head>

<body>
<?php print $pageinfo->header; ?>
<link rel="stylesheet" href="./../assets/css/search_hospital.css">

<main>
    <div class="container">
        <div class="main-search">
            <div class="search-filter">
                <div>
                <div class="search-title">
                    <h3>検索条件を選択</h3>
                    <div class="toggle-button" id="expandedSearchFilter">☰</div>
                </div>
                <div class="filter-group">
                    <div class="filter-header show-popup" id="cancerType">
                        <h3>がん種類</h3>
                        <span class="badge bg-danger">必須</span>
                        <span class="toggle">+</span>
                    </div>
                    <div class="filter-content content-required">
                    </div>
                </div>

                <div class="filter-group">
                    <div class="filter-header show-popup" id="area">
                        <h3>エリア</h3>
                        <span class="badge bg-info">任意</span>
                        <span class="toggle">+</span>
                    </div>
                    <div class="filter-content content-option">
                    </div>
                </div>

                <?php include 'component/filter-category.php'; ?>
                </div>

                <div>
                    <div class="filter-group filter-group-spaced">
                        <div class="filter-header">
                            <h3>その他</h3>
                            <span class="toggle">—</span>
                        </div>
                        <div class="filter-content">
                            <input type="text" class="keyword" id="keyword" placeholder="特に指定がない場合は、こちらに入力してください。">
                        </div>
                    </div>
                    <button class="search-button search-hospital">検索</button>
                </div>
            </div>

            <div class="sort-tab-container">
                <div class="popup-container">
                    <?php include 'component/popup-cancer.php'; ?>
                    <?php include 'component/popup-area.php'; ?>
                    <?php include 'component/popup-category.php';?>
                </div>
                <div class="sort-tab-title"><img src="./../img/icons/sort-icon.png" class="logout_img" alt="sort-icon"><span>並び替え</span></div>
                <div class="sort-tab active" data-value="dpcSort">年間入院患者数</div>
                <div class="sort-tab" data-value="newNumSort">年間新規患者数</div>
                <div class="sort-tab" data-value="survRateSort">5年生存率係数</div>
            </div>

            <div class="search-result">
                <div class="screen-popup-container"></div>
                <div class="search-hospital-pagination">
                    <div id="pagination-container" class="paginationjs paginationjs-theme-blue paginationjs-big"></div>
                </div>
                <div class="search-result-header">
                    <div class="checkbox-label">
                        <input type="checkbox" id="printAll" class="m-r-10 checkbox-print-all">
                        <label for="printAll">全チェック</label>
                    </div>
                    <div class="search-result-header-right">
                        <div class="print-note">
                            <span>チェックした対象を</span>
                            <span style="font-size: 12px">（1回につき最大5件）</span>
                        </div>
                        <a class="btn btn-print" id="printButton" style="border-color: #0A74B0">
                            <img src="../img/icons/print-icon.png" alt="Hospital Icon"><span class="text">印刷</span>
                        </a>
                    </div>
                </div>
                <div class="hospital-list">
                    <div class="hospital-no-data">
                        <div class="no-data-message">検索条件を選択してください。</div>
                    </div>
                </div>
                <div class="loading-overlay">
                    <div class="loading-spinner"></div>
                </div>
                <?php include 'component/print-pdf-result.php';?>
            </div>
        </div>
    </div>
</main>
<button id="backToTop" style="display: none;">↑ TOP</button>
</body>
<?php print $pageinfo->html_foot; ?>
<script>
    const pageType = 'first-search';
</script>
<script type="text/javascript" src="./../assets/js/print-pdf.js"></script>
<script type="text/javascript" src="./../assets/js/search_hospital.js"></script>
</html>