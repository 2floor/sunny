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
        <h2>当システムの使用目的をお知らせください</h2>
        <p>当システムでは、おユーザーのご状況に応じた適切な情報を提供いたします。以下の中から該当する選択肢をお選びください。</p>
        <a href="#" class="option red">
            初めてご利用の方で、ご自身のがんの種類のみご存知の場合。がんの種類に関する適切な情報をお探しの方はこちら。
        </a>
        <a href="#" class="option green">
            ご自身のがんの種類についてある程度ご理解いただいているものの、まだお悩みの方。セカンドオピニオンをお探しの方はこちら。
        </a>
    </div>
</section>
</body>
<?php print $pageinfo->html_foot; ?>

</html>