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
    <div class="card-container">
        <a href="../index.php" class="close-btn"><i class="fa fa-times-circle"></i></a>
        <h2>がん治療実績データベース　病院検索</h2>
        <p></p>
        <a href="./first-search.php" class="option red">
            新規医療機関検索<br>がんの疑いがあり、治療を受ける病院を検索する<br>（確定診断を受けていない方向け）
        </a>
        <a href="./second-search.php" class="option green">
            セカンドオピニオン検索<br>セカンドオピニオンを受ける病院や特殊な治療方法などから病院を検索する<br>（確定診断を受けている方向け）
        </a>
    </div>
</section>
</body>
<?php print $pageinfo->html_foot; ?>

</html>