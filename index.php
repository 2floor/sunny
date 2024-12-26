<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once  __DIR__ . "/required/page_init.php";
require_once  __DIR__ . "/logic/front/auth_logic.php";

$auth_logic = new auth_logic();
$permSH = $auth_logic->check_permission('search.hospital');


$page_init = new page_init();
$pageinfo = $page_init->get_info();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php print $pageinfo->html_head; ?>
    <link rel="stylesheet" href="./assets/css/index.css">
</head>
<body>
<?php print $pageinfo->header; ?>


    <section>
        <div class="outer-card">
            <div class="card-container">
                <h2>がん治療実績データベース</h2>
                <p></p>
                <div class="buttons">
                    <?php if ($permSH) { ?>
                    <a href="./hospital" class="btn btn-hospital">
                        <span class="icon"><span class="border-icon"><img src="img/icons/hospital-icon.png" alt="Hospital Icon"></span></span>
                        <span class="text">病院を検索する</span>
                    </a>
                    <?php } ?>
                    <a href="#" class="btn btn-doctor">
                        <span class="icon"><span class="border-icon"><img src="img/icons/doctor-icon.png" alt="Doctor Icon"></span></span>
                        <span class="text">名医を検索する</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

</body>
<?php print $pageinfo->html_foot; ?>

</html>