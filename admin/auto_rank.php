<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';
require_once __DIR__ . '/../controller/admin/auto_rank_ct.php';

$auto_rank_ct = new auto_rank_ct();
$initData = $auto_rank_ct->init_entry_new();
$cancers = $initData['cancers'] ?? [];

$htmlRankOption = '';
foreach (RANK_DATA_TYPE as $key => $item) {
    $htmlRankOption .= '<option value="'.$key.'">'.$item.'</option>';
}

$htmlAvgOption = '';
foreach (AVG_RANK_DATA_TYPE as $key => $item) {
    $htmlAvgOption .= '<option value="'.$key.'">'.$item.'</option>';
}

$htmlCancerOption = '';
foreach ($cancers as $cancer) {
    $htmlCancerOption .= '<option value="'.$cancer['id'].'">'.$cancer['cancer_type'].'</option>';
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . '/../required/html_head.php'; ?>
    <style>
        .auto-rank-container {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .auto-rank-filter {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .auto-rank-filter .title-rank {
            font-size: 14px;
            color: #333333;
            font-weight: bold;
            width: 100px;
        }

        .auto-rank-filter input {
            width: 100px;
            border: 1px solid #aaaaaa;
            height: 35px;
        }

        .auto-rank-filter .title-for-input {
            width: 70px;
        }

        .select2-container .select2-selection--single {
            height: 35px;
        }

        .select2-container .select2-selection__rendered {
            line-height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
        }

        .auto-rank-title {
            font-size: 18px;
            color: #333333;
            font-weight: bold;
            background: #fff;
            margin-bottom: 20px;
            padding: 10px;
            margin-top: 0;
            display: flex;
            align-items: center;
        }

        .auto-rank-title i {
            font-size: 22px;
            margin-right: 10px;
        }

        .auto-rank-submit {
            text-align: center;
            margin-top: 60px;
            margin-bottom: 10px;
        }

        .auto-rank-main {
            padding: 15px 0;
        }

        .card-box.auto-rank {
            padding-bottom: 0;
        }

        .panel-group {
             margin-bottom: 0;
        }

    </style>
</head>

<body class="fixed-left">
<!-- Begin page -->
<div id="wrapper">
    <?php require_once __DIR__ . '/../required/menu.php'; ?>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <!-- pageTitle -->
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="pageTitle" id="page_title">
                            <i class="fa fa-list" aria-hidden="true"></i>
                            データ自動生成機能
                        </h2>
                    </div>
                </div>
            </div>
            <!-- /pageTitle -->

            <!-- Start Data List Area -->
            <div class="disp_area list_show list_disp_area">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card-box auto-rank">
                                <div>
                                    <h3 class="auto-rank-title"><i class="fa fa-circle-o-notch" aria-hidden="true"></i>自動ランキング
                                    </h3>
                                </div>
                                <div class="auto-rank-main">
                                    <div class="auto-rank-container">
                                        <div class="auto-rank-filter">
                                            <div class="title-rank">
                                                データ型
                                            </div>
                                            <select class="form-control filter-selection" name="rank_type">
                                                <?= $htmlRankOption ?>;
                                            </select>
                                        </div>

                                        <div class="auto-rank-filter">
                                            <div class="title-rank">
                                                がんの種類
                                            </div>
                                            <select class="form-control filter-selection" name="rank_cancer">
                                                <?= $htmlCancerOption ?>
                                            </select>
                                        </div>

                                        <div class="auto-rank-filter">
                                            <div class="title-rank title-for-input">
                                                年度
                                            </div>
                                            <div class="panel-group">
                                                <input type="text" name="rank_year" class="form-control"
                                                       placeholder="年を入力">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="auto-rank-submit">
                                        <button type="button" id="genRankSM" class="btn btn-primary waves-effect w-md waves-light m-b-5">自動生成</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card-box auto-rank">
                                <div>
                                    <h3 class="auto-rank-title"><i class="fa fa-circle-o-notch" aria-hidden="true"></i>平均データ
                                    </h3>
                                </div>
                                <div class="auto-rank-main">
                                    <div class="auto-rank-container">
                                        <div class="auto-rank-filter">
                                            <div class="title-rank">
                                                データ型
                                            </div>
                                            <select class="form-control filter-selection" name="avg_type">
                                                <?= $htmlAvgOption ?>;
                                            </select>
                                        </div>

                                        <div class="auto-rank-filter">
                                            <div class="title-rank">
                                                がんの種類
                                            </div>
                                            <select class="form-control filter-selection" name="avg_cancer">
                                                <?= $htmlCancerOption ?>
                                            </select>
                                        </div>

                                        <div class="auto-rank-filter">
                                            <div class="title-rank title-for-input">
                                                年度
                                            </div>
                                            <input type="text" name="avg_year" class="form-control"
                                                   placeholder="年を入力">
                                        </div>
                                    </div>

                                    <div class="auto-rank-submit">
                                        <button type="button" id="genAVGSM" class="btn btn-primary waves-effect w-md waves-light m-b-5">自動生成</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- searchBox -->
                <!-- /list1Col -->
            </div>
            <!-- END Data List Area -->

            <!-- Start Data Edit Area -->
            <!-- END Data Edit Area -->

            <!-- container -->
        </div>
        <!-- content -->
    </div>
</div>
<!-- END wrapper -->
<?php require_once __DIR__ . '/../required/foot.php'; ?>
<!-- Start Personal script -->
<script src="../assets/admin/js/common/validate.js"></script>
<script>
    $( document ).ready(function() {
        $('.filter-selection').select2({
            placeholder: '地域を選択',
            allowClear: true
        });

        $('.loading').hide()

        $('#genRankSM').on('click', function() {
            $('input[name="rank_year"]').addClass('validate required integer')

            if (validate_all(true)) {
                let data_type = $('select[name="rank_type"]').val();
                let auto_type = '1';
                let cancer_id = $('select[name="rank_cancer"]').val()
                let year = $('input[name="rank_year"]').val();

                var formData = new FormData();
                formData.append('data_type', data_type);
                formData.append('auto_type', auto_type);
                formData.append('cancer_id', cancer_id);
                formData.append('year', year);
                formData.append('method', 'check_auto_rank');

                sendAjaxToRanking(formData)
            }
        })

        $('#genAVGSM').on('click', function() {
            let notValidation = $('input[name="rank_year"]')
            notValidation.removeClass('validate required integer')
            notValidation.removeClass('error-form');
            notValidation.closest('.panel-group').find('.error').remove();
        })

        let callAjaxAutoRank = function (auto_rank_id)
        {
            let formData = new FormData();
            formData.append('auto_rank_id', auto_rank_id);
            formData.append('method', 'handle_auto_rank');


            $.ajax({
                url: '../controller/admin/auto_rank_ct.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                async: true
            });
        };

        function sendAjaxToRanking(formData)
        {
            $.ajax({
                url: '../controller/admin/auto_rank_ct.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".loading").show();
                },
                success: function (response) {
                    response = JSON.parse(response);
                    let message = response.data.message || '';

                    if (response.data.status) {
                        callAjaxAutoRank(response.data.auto_rank_id);

                        setTimeout(function() {
                            $(".loading").hide();
                            swal({
                                title: "処理中!",
                                text: message,
                                type: "success",
                                confirmButtonText: "処理状況を確認",
                                closeOnConfirm: true
                            }, function(isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "auto_rank_list.php";
                                } else {
                                    swal.close();
                                }
                            });
                        }, 1000);
                    } else {
                        $(".loading").hide();
                        swal({
                            title : "失敗!",
                            text : message,
                            type : "error",
                            confirmButtonText : "近い",
                            closeOnConfirm : true
                        });
                    }
                },
                error: function () {
                    $(".loading").hide();
                    swal({
                        title : "失敗!",
                        text : 'リクエストは成功しませんでした',
                        type : "error",
                        confirmButtonText : "近い",
                        closeOnConfirm : true
                    });
                }
            });
        }
    });
</script>
</body>

</html>