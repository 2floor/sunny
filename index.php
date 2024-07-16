<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once  __DIR__ . "/required/page_init.php";
require_once  __DIR__ . "/logic/front/auth_logic.php";

$auth_logic = new auth_logic();
$auth_logic->check_authentication();
$page_init = new page_init();
$pageinfo = $page_init->get_info();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php print $pageinfo->html_head; ?>
    <link rel="stylesheet" href="./assets/css/index.css">
</head>
<style>
    .card-container {
        max-width: 700px;
    }
</style>
<body>
<?php print $pageinfo->header; ?>


<section>
    <div class="card-container">
        <h2>検索オプションを選択してください</h2>
        <p></p>
        <a href="./hospital" class="option red">
            病院を検索する
        </a>
        <a href="#" class="option green">
            名医を検索する
        </a>
    </div>
</section>
</body>
<?php print $pageinfo->html_foot; ?>

</html>