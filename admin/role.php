<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';
require_once __DIR__ . '/../controller/admin/role_ct.php';

$role_ct = new role_ct();
$initData = $role_ct->init_entry_new();
$perms = $initData['perms'] ?? [];
?>
<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . '/../required/html_head.php'; ?>
    <style>
        .select2-container .select2-selection--single {
            height: 32px;
        }

        .supper-selection {
            width: 100%;
        }

        .panel-heading {
            cursor: pointer;
        }

        .panel-title {
            font-size: 14px;
            font-weight: bold;
        }
        .panel-default {
            border: 1px solid #ddd !important;
            margin-bottom: 10px !important;
        }

        .panel-heading.active {
            background-color: #337ab7;
            color: white;
        }
        .panel-heading:not(.active) {
            color: #4C3333;
        }
        .panel-collapse {
            transition: height 0.75s ease-in-out;
        }

        .arrow {
            float: right;
            font-size: 14px;
        }

        .panel-body {
            padding: 20px;
        }

        .checkbox-content label {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: unset;
        }

        .checkbox-content input[type="checkbox"] {
            margin-right: 5px;
        }

        .checkbox-content input[type="text"] {
            margin-left: 5px;
        }

        .checkbox-content .select2-container {
            margin-left: 5px;
            width: 66% !important;
        }

        .checkbox-content .select2-selection {
            border-color: #ddd;
        }

        .checkbox-content .select2-container .select2-selection--multiple .select2-selection__choice {
            white-space: normal;
            word-wrap: break-word;
            max-width: 200px;
        }

        .checkbox-content .select2-container .select2-results__option {
            white-space: normal;
            word-wrap: break-word;
        }

        .perm-children {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 15px 25px;
        }

        .perm-parent label {
            font-size: 16px;
            font-weight: 700;
        }

        .perm-section {
            display: inline-block;
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
                            役割権限一覧
                        </h2>
                    </div>
                </div>
            </div>
            <!-- /pageTitle -->

            <!-- Start Data List Area -->
            <div class="disp_area list_show list_disp_area">
                <!-- searchBox -->
                <div class="container table-rep-plugin">
                    <div class="searchBox">
                        <div class="searchBoxLeft searchArea">
                            <div class="searchBox1">
                                <div class="searchTxt">
                                    絞り込み検索
                                </div>
                                <select class="form-control searchAreaSelect">
                                </select>
                            </div>
                            <div class="searchBox2">
                                <div class="input-group">
                                    <input type="text" id="search_input" name="search_input" class="form-control" placeholder="フリーワードを入力">
                                    <span class="input-group-btn">
											<button type="button" class="btn waves-effect waves-light btn-primary callSearch">検索</button>
										</span>
                                </div>
                            </div>
                        </div>
                        <div class="searchBoxRight">
                            <div class="serachW110">
                                <button type="button" name="new_entry" class="btn btn-primary waves-effect w-md waves-light m-b-5">新規登録</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- searchBox -->
                <!-- pager -->
                <div class="container">
                    <div class="pagination-info">
                        <div class="total-result" style="display: block;">
                            <span class="badge bg-secondary"></span>
                        </div>
                        <div id="pagination-container" class="paginationjs paginationjs-theme-blue paginationjs-big"></div>
                    </div>
                </div>
                <!-- /pager -->

                <!-- list1Col -->
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card-box">
                                <div class="table-wrapper">
                                    <div class="btn-toolbar">
                                        <div class="btn-group dropdown-btn-group pull-right">
                                            <button class="btn btn-default btn-primary" name="colDispChangeAll">すべて表示</button>
                                            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                表示項目
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu tableColDisp"></ul>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table parts">
                                            <thead class="tableHeadArea">
                                            <tr>
                                                <th>No</th>
                                                <th>ID</th>
                                                <th>役割名</th>
                                                <th>スーパールール</th>
                                                <th>作成日時</th>
                                                <th>更新日時</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody id="list_html_area" class="tableBodyArea">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /list1Col -->
            </div>
            <!-- END Data List Area -->

            <!-- Start Data Edit Area -->
            <div class="disp_area entry_input">
                <!-- btnBox -->
                <div class="container">
                    <div class="registBtnBox">
                        <div class="registBtnLeft">
                            <span class="require_text">必要事項を入力後、[登録]ボタンをクリックしてください。</span>
                            <h3 class="conf_text">下記の内容が登録されます。よろしければ登録ボタンを押してください。</h3>
                        </div>
                        <div class="registBtnRight">
                        </div>
                    </div>
                </div>
                <!-- /btnBox -->

                <!-- userSetting -->
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12" id="frm">
                            <div class="contentBox">
                                <input type="hidden" class="form-control" name="id" id="id">
                                <div class="formRow">
                                    <div class="formItem">
                                        役割名
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate required" name="role_name" id="role_name" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        説明
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <textarea name="description" id="description"  class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        権限設定
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <select class="selection2 supper-selection validate required" name="is_supper_role">
                                                <option value="0" selected>基本会員</option>
                                                <option value="1">プレミアム会</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        <i style="font-size: 18px; margin-right: 5px" class="fa fa-question-circle-o" aria-hidden="true"></i>説明書の表示
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <select class="selection2 supper-selection validate required" name="is_show_tooltip">
                                                <option value="1" selected>表示</option>
                                                <option value="0">非表示</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        許可
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <div class="panel-group" id="accordionPerm">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" data-toggle="collapse" data-parent="#accordionPerm" href="#permCollapseOne">
                                                        <h4 class="panel-title">
                                                            このロールに適用できる権限の種類
                                                            <span class="glyphicon glyphicon-chevron-down arrow"></span>
                                                        </h4>
                                                    </div>
                                                    <div id="permCollapseOne" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <div class="checkbox-content validate required">
                                                                <?php foreach($perms as $perm) { ?>
                                                                    <div class="perm-section">
                                                                        <div class="perm-parent">
                                                                            <label><input style="width: unset" type="checkbox" class="form-control parent-checkbox" name="perms[]" value="<?= $perm['id'] ?>"><?= $perm['permission_name'] ?></label>
                                                                        </div>
                                                                        <div class="perm-children">
                                                                            <?php foreach(($perm['children'] ?? []) as $child) { ?>
                                                                                <label><input style="width: unset" type="checkbox" class="form-control child-checkbox" name="perms[]" value="<?= $child['id'] ?>"><?= $child['permission_name'] ?></label>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary waves-effect w-md waves-light m-b-5 button_input button_form" name='conf' id="conf">確認する</button>
                                <button type="button" class="btn btn-inverse waves-effect w-md waves-light m-b-5 button_conf button_form" name='return' id="return">戻る</button>
                                <button type="button" class="btn btn-info waves-effect w-md waves-light m-b-5 button_conf button_form" name='submit' id="submit">登録する</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /userSetting -->
            </div>
            <!-- END Data Edit Area -->

            <!-- container -->
        </div>
        <!-- content -->
    </div>

</div>
<!-- END wrapper -->
<?php require_once __DIR__ . '/../required/foot.php'; ?>
<!-- Start Personal script -->
<script src="../assets/admin/js/role.js"></script>

<script>
    $(document).ready(function() {
        $("#accordionPerm").on('show.bs.collapse', function(e) {
            $(e.target).prev('.panel-heading').addClass('active');
            $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        });

        $('#accordionPerm').on('hide.bs.collapse', function(e) {
            $(e.target).prev('.panel-heading').removeClass('active');
            $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        });

        $('.parent-checkbox').on('change', function() {
            let isChecked = $(this).is(':checked');
            $(this).closest('.perm-section').find('.child-checkbox').prop('checked', isChecked);
        });

        $('.child-checkbox').on('change', function() {
            let section = $(this).closest('.perm-section');
            let anyChildChecked = section.find('.child-checkbox:checked').length > 0;
            section.find('.parent-checkbox').prop('checked', anyChildChecked);
        });
    })
</script>
<!-- End Personal script -->
<!-- Start Personal Input -->
<input type="hidden" id="ct_url" value="../controller/admin/role_ct.php">
<input type="hidden" id="id" value="">
<input type="hidden" id="page_type" value="">
<input type="hidden" id="common_ct_url" value="../controller/admin/common_ct.php">
<!-- 現在のページ位置 -->
<input type="hidden" id="now_page_num" value="1">
<!-- 1ページに表示する件数 -->
<input type="hidden" id="page_num" value="1">
<!-- 1ページに表示する件数 -->
<input type="hidden" id="page_disp_cnt" value="10">

<!-- End Personal Input -->

</body>

</html>