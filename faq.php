<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once  __DIR__ . "/required/page_init.php";
require_once  __DIR__ . "/logic/front/auth_logic.php";
require_once  __DIR__ . "/controller/front/f_faq_ct.php";

$auth_logic = new auth_logic();
$permFAQ = $auth_logic->check_permission('view.faq');

if (!$permFAQ) {
    header("Location: " . BASE_URL . "error/403_page.php");
    exit();
}


$page_init = new page_init();
$pageinfo = $page_init->get_info();

$ct = new f_faq_ct();
$initData = $ct->pageIndex();
$faqs = $initData['faqs'] ?? [];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php print $pageinfo->html_head; ?>
</head>

<style>

    body {
        font-size: 16px;
    }

    .page__title--jp {
        color: #505458;
    }

    .h1 {
        font-size: 36px;
    }

    .h3 {
        font-size: 24px;
    }

    .nav-section {
        display: flex;
        flex-direction: row;
        gap: 10px;
        margin-bottom: 70px;
    }

    .nav-section a {
        display: flex;
        flex-direction: row;
        align-items: flex-end;
        padding: 10px 15px;
        color: #505468;
        border: 1px solid #E5E5E5;
        position: relative;
    }

    .nav-section a.active {
        color: #44B8B8;
    }

    .nav-section a.active:before {
        width: 100%;
    }

    .nav-section a:hover, .nav-section a.nav-section-active {
        color: #44B8B8;
    }

    section .header {
        position: relative;
        padding: 5px 20px;
    }

    section .header h4 {
        font-weight: 700;
    }

    section .header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 10px;
        height: 100%;
        background: linear-gradient(to right, #44B8B8, #ffffff);
    }

    .panel-group {
        margin-top: 30px;
    }

    .arrow {
        float: right;
    }

    .panel {
        border: none;
    }

    .panel-heading {
        border-bottom: 1px solid #E5E5E5 !important;
        background-color: transparent !important;
        display: flex;
        gap: 30px;
        font-weight: 700;
    }

    .panel-heading h4 {
        width: 100%;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-icon--question {
        color: #54A8E7;
    }

    .answer-section {
        display: flex;
        gap: 30px;
        font-weight: 700;
        margin-left: 25px;
    }

    .answer-section h4 {
        display: flex;
        align-items: center;
    }

    .faq-icon--answer {
        color: #44B8B8;
    }

    .section-faq {
        margin-bottom: 70px;
    }
</style>

<body>
  <!-- header -->
  <?php print $pageinfo->header; ?>

<!--  <div class="page-titles page-title--red">-->
<!--    <div class="page-titles__inner">-->
<!--      <div class="page-titles__content">-->
<!--        <p class="page-title">よくある質問</p>-->
<!--        <span class="page-title--en">FAQ</span>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->

  <p class="pan"><a href="index.php"><i class="fa fa-home"></i></a>　|　よくある質問</p>

  <section class="parts2 faq-section">
    <div class="products__inner l-inner">
      <p class="page__title--jp mt30 h1">FAQ</p>
      <p class="page__title--jp h3">よくある質問</p>
        <p class="t-center mt20 title-header">ソフトウェアに関する具体的なご質問がございましたら、弊社または担当者までお問い合わせください。</p>
      <div class="faq-list">
          <div class="nav-section">
              <?php foreach (GROUP_FAQ as $key => $group) {?>
              <a href="#faqG<?= $key ?>"><span><?= $group ?></span></a>
              <?php }?>
          </div>

          <?php foreach ($faqs as $key => $faq) {?>
          <section id="faqG<?= $key ?>" class="section-faq">
              <div class="header">
                  <h4><?= GROUP_FAQ[$key] ?? '' ?></h4>
              </div>

              <div class="panel-group" id="accordionTopicId<?= $key ?>">
                  <div class="panel panel-default">
                      <?php foreach ($faq as $item) {?>
                          <div class="panel-heading" data-toggle="collapse" data-parent="#accordionTopicId<?= $key ?>" href="#panelQuestionId<?= $item['id'] ?>" aria-expanded="true">
                              <span class="faq-icon faq-icon--question">Q</span>
                              <h4 class="panel-title">
                                  <?= $item['question'] ?>
                                  <span class="glyphicon glyphicon-plus arrow"></span>
                              </h4>
                          </div>
                          <div id="panelQuestionId<?= $item['id'] ?>" class="panel-collapse collapse" aria-expanded="true" style="">
                              <div class="panel-body">
                                  <div class="answer-section">
                                      <span class="faq-icon faq-icon--answer">A</span>
                                      <h4 class="panel-title">
                                          <?= $item['answer'] ?>
                                      </h4>
                                  </div>
                              </div>
                          </div>
                      <?php }?>
                  </div>
              </div>
          </section>
          <?php }?>
      </div>
    </div>
  </section>
</body>
<?php print $pageinfo->html_foot; ?>
<script>
    $(document).ready(function() {
        $('.panel-group').on('show.bs.collapse', function(e) {
            $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-plus').addClass('glyphicon-minus');
        });

        $('.panel-group').on('hide.bs.collapse', function(e) {
            $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-minus').addClass('glyphicon-plus');
        });

        $('.nav-section a').on('click', function () {
            $('.nav-section a').removeClass('active');
            $(this).addClass('active');
        });
    })
</script>
</html>