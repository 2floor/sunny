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
                <div class="search-title">
                    <h2>検索条件を選択</h2>
                    <div class="toggle-button" id="expandedSearchFilter">☰</div>
                </div>
                <div class="filter-group">
                    <div class="filter-header show-popup" id="cancerType">
                        <h3>ガン種類</h3>
                        <span class="badge bg-danger">必須</span>
                        <span class="toggle">+</span>
                    </div>
                    <div class="filter-content content-required">
                    </div>
                </div>

                <div class="filter-group">
                    <div class="filter-header show-popup" id="area">
                        <h3>エリア</h3>
                        <span class="badge bg-success">任意</span>
                        <span class="toggle">+</span>
                    </div>
                    <div class="filter-content content-option">
                    </div>
                </div>

                <?php include 'component/filter-category.php'; ?>

                <div class="filter-group filter-group-spaced">
                    <div class="filter-header">
                        <h3>その他</h3>
                        <span class="toggle">—</span>
                    </div>
                    <div class="filter-content">
                        <input type="text" class="keyword" id="keyword" placeholder="特に指定がない場合は、こちらに入力してください。">
                    </div>
                </div>

                <div class="filter-group">
                    <div class="filter-header">
                        <h3>並び替え</h3>
                        <span class="toggle">—</span>
                    </div>
                    <div class="filter-content">
                        <div class="radio-group">
                            <label><input type="radio" name="sort" value="dpcSort" checked>入院患者数</label>
                            <label><input type="radio" name="sort" value="newNumSort">新規がん患者数</label>
                            <label><input type="radio" name="sort" value="survRateSort">生在率係数</label>
                        </div>
                    </div>
                </div>
                <button class="search-button search-hospital">検索</button>
            </div>

            <div class="search-result">
                <div class="popup-container">
                    <?php include 'component/popup-cancer.php'; ?>
                    <?php include 'component/popup-area.php'; ?>
                    <?php include 'component/popup-category.php';?>
                </div>
                <div class="search-result-header">
                    <label><input type="checkbox" class="m-r-10 checkbox-print-all"> 全チェック</label>
                    <div class="search-result-header-right">
                        <span>チェックした対象を<br><span style="font-size: 10px">（1回につき最大5件）</span></span>
                        <button class="confirm-button" id="printButton">印刷</button>
                    </div>
                </div>
                <div class="total-result">
                    <span class="badge bg-secondary"></span> 見つかりました
                </div>
                <div class="hospital-list">
                    <div class="hospital-no-data">
                        <div class="no-data-message">検索条件を選択してください。</div>
                    </div>
                </div>
                <div class="search-hospital-footer">
                    <div id="pagination-container" class="paginationjs paginationjs-theme-blue paginationjs-big"></div>
                </div>
                <div class="loading-overlay">
                    <div class="loading-spinner"></div>
                </div>
                <?php include 'component/print-pdf-result.php';?>
            </div>
        </div>
    </div>
</main>
</body>
<?php print $pageinfo->html_foot; ?>
<script>
    const pageType = 'first-search';
</script>
<script type="text/javascript" src="./../assets/js/print-pdf.js"></script>
<script type="text/javascript" src="./../assets/js/search_hospital.js"></script>
</html>