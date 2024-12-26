<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';
?>
<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . '/../required/html_head.php'; ?>
    <style>
        .clr1 {
            color: #ffffff;
        }

        .table > tbody > tr > td {
            padding: 15px;
            line-height: 2;
            border-top: 1px solid #ddd;
        }

        .form-control:focus:read-only {
            background-color: #eee;
            opacity: 1;
        }

        .form-control:read-only {
            cursor: not-allowed;
        }

        .input-group input#file-name-display {
            background-color: #ffffff;
        }

        .dsp-block {
            display: block;
        }

        .input-group input#file-name-display:disabled {
            cursor: not-allowed;
            background-color: #f9f9f9;
        }

        a:not([href]):not(.edit) {
            cursor: not-allowed;
        }

        .table-responsive {
            width: 100%;
        }

        .table-header {
            font-size: 14px;
            color: #333333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .bg-processing {
            background: #4CA4E5; color: #ffffff
        }

        .bg-completed {
            background: #35C77A; color: #ffffff
        }

        .bg-error {
            background: #DC3545; color: #ffffff
        }

        .bg-timeout {
            background: #b8b8c2; color: #ffffff
        }

        .bg-reimport {
            background: #FF9674; color: #ffffff
        }

        #tbodyReImport a{
            color: #ffffff;
            text-decoration: underline;
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
                            インポート一覧
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
                                    <input type="text" id="search_input" name="search_input" class="form-control"
                                           placeholder="フリーワードを入力">
                                    <span class="input-group-btn">
											<button type="button"
                                                    class="btn waves-effect waves-light btn-primary callSearch">検索</button>
										</span>
                                </div>
                            </div>
                        </div>
                        <div class="searchBoxRight">
                            <!--                            <div class="serachW110">-->
                            <!--                                <button type="button" name="new_entry" class="btn btn-primary waves-effect w-md waves-light m-b-5">新規登録</button>-->
                            <!--                            </div>-->
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
                        <div id="pagination-container"
                             class="paginationjs paginationjs-theme-blue paginationjs-big"></div>
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
                                            <button class="btn btn-default btn-primary" name="colDispChangeAll">
                                                すべて表示
                                            </button>
                                            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="true">
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
                                                <th>データ型</th>
                                                <th>ファイル名</th>
                                                <th>地位</th>
                                                <th>成功数</th>
                                                <th>失敗の数</th>
                                                <th>作成日時</th>
                                                <th>完了日時</th>
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
                                        データ型
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="data_type" id="data_type"
                                                   value="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ファイル名
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="file_name" id="file_name"
                                                   value="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地位
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="status" id="status" value=""
                                                   readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        成功数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="success" id="success" value=""
                                                   readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        失敗の数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="error" id="error" value=""
                                                   readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        エラーデータに関する情報
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <a id="linkErrorFile" style="text-decoration: underline">エラー情報のファイル</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        作成日時
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="created_at" id="created_at"
                                                   value="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        完了日時
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="completed_time"
                                                   id="completed_time" value="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        失敗した値を再インポートする
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <div class="input-group dsp-block">
                                                <input style="width: 80%" type="text" id="file-name-display"
                                                       class="form-control" placeholder="ファイルを選択" readonly>
                                                <span class="input-group-btn">
                                                    <button style="width: 100%" type="button"
                                                            class="btn waves-effect waves-light btn-primary callUpload"
                                                            disabled>アップロード</button>
                                                </span>
                                                <input type="file" id="upload-file" name="upload-file"
                                                       class="form-control upload-file-hidden upload-csv"
                                                       accept=".csv,.xlsx,.xls">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow" style="margin-top: 40px">
                                    <span class="table-header">再インポート処理の結果情報</span>
                                    <div class="table-responsive">
                                        <table class="table overflow-auto">
                                            <tbody id="tbodyReImport">
                                            <tr>
                                                <td class="table-title center-icon">No</td>
                                                <td class="table-title center-icon">ファイル名</td>
                                                <td class="table-title center-icon">地位</td>
                                                <td class="table-title center-icon">成功数</td>
                                                <td class="table-title center-icon">失敗の数</td>
                                                <td class="table-title center-icon">作成日時</td>
                                                <td class="table-title center-icon">完了日時</td>
                                                <td class="table-title center-icon">エラー</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
<script src="../assets/admin/js/import.js"></script>


<!-- End Personal script -->
<!-- Start Personal Input -->
<input type="hidden" id="ct_url" value="../controller/admin/import_ct.php">
<input type="hidden" id="id" value="">
<input type="hidden" id="page_type" value="">
<input type="hidden" id="common_ct_url" value="../controller/admin/common_ct.php">
<input type="hidden" id="upload_csv_ct_url" value="../controller/admin/upload_csv_ct.php">
<!-- 現在のページ位置 -->
<input type="hidden" id="now_page_num" value="1">
<!-- 1ページに表示する件数 -->
<input type="hidden" id="page_num" value="1">
<!-- 1ページに表示する件数 -->
<input type="hidden" id="page_disp_cnt" value="10">

<!-- End Personal Input -->

</body>

</html>