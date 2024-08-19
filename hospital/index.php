<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once  __DIR__ . "/../required/page_init.php";
require_once  __DIR__ . "/../logic/front/auth_logic.php";

$auth_logic = new auth_logic();
$auth_logic->check_authentication();
$page_init = new page_init();
$pageinfo = $page_init->get_info();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php print $pageinfo->html_head; ?>
    <link rel="stylesheet" href="./../assets/css/index.css">
</head>

<body>
<?php print $pageinfo->header; ?>

<section>
    <div class="outer-card">
        <div class="card-container container-sub-text">
            <a href="../index.php" class="close-btn"><i class="fa fa-times-circle"></i></a>
            <h2>がん治療実績データベース　病院検索</h2>
            <p></p>
            <div class="button-sub-text">
                <a href="./first-search.php" class="btn btn-hospital">
                    <span class="icon"><img src="../img/icons/first-search-icon.png" alt="Hospital Icon"></span>
                    <span class="text">新規医療機関検索</span>
                </a>
                <span class="sub-text">がんの疑いがあり,治療を受ける病院を検索する<br>（確定診断を受けていない方向け）</span>
                <a href="./second-search.php" class="btn btn-doctor">
                    <span class="icon"><img src="../img/icons/second-search-icon.png" alt="Doctor Icon"></span>
                    <span class="text">セカンドオピニオン検索</span>
                </a>
                <span class="sub-text">セカンドオピニオンを受ける病院や特殊な治療方法などから病院を検索する<br>（確定診断を受けている方向け）</span>
            </div>
        </div>
    </div>
</section>
</body>
<?php print $pageinfo->html_foot; ?>

</html>