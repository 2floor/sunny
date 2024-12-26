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

        .bg-processing {
            background: #4CA4E5; color: #ffffff
        }

        .bg-completed {
            background: #35C77A; color: #ffffff
        }

        .bg-timeout {
            background: #b8b8c2; color: #ffffff
        }

        .bg-error {
            background: #DC3545; color: #ffffff
        }

        .bg-reAuto {
            background: #FF9674; color: #ffffff
        }

        .searchBoxRight {
            align-items: center;
        }

        .option-radio {
            display: flex;
            gap: 30px;
        }

        .option-radio-item {
            padding: 10px;
            border: 1px solid #289DEB;
            background-color: #ffffff;
            color: #289DEB;
        }

        .option-radio-item label{
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
                            自動処理一覧
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
                            <div class="option-radio">
                                <div class="option-radio-item">
                                    <input type="radio" id="html" name="auto_type" value="1" checked>
                                    <label for="html">ランクを自動作成</label>
                                </div>
                                <div class="option-radio-item">
                                    <input type="radio" id="css" name="auto_type" value="2">
                                    <label for="css">平均データを自動生成</label>
                                </div>
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
                                                <th>がんID</th>
                                                <th>がん種名</th>
                                                <th>年度</th>
                                                <th>データ量</th>
                                                <th>生成データ量</th>
                                                <th>ステータス</th>
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
            <!-- END Data Edit Area -->

            <!-- container -->
        </div>
        <!-- content -->
    </div>

</div>
<!-- END wrapper -->
<?php require_once __DIR__ . '/../required/foot.php'; ?>
<!-- Start Personal script -->
<script src="../assets/admin/js/auto_rank.js"></script>


<!-- End Personal script -->
<!-- Start Personal Input -->
<input type="hidden" id="ct_url" value="../controller/admin/auto_rank_dpc_ct.php">
<input type="hidden" id="id" value="">
<input type="hidden" id="page_type" value="">
<input type="hidden" id="common_ct_url" value="../controller/admin/common_ct.php">
<input type="hidden" id="page_title_js" value="DPC自動処理">
<input type="hidden" id="data_type" value="1">
<!-- 現在のページ位置 -->
<input type="hidden" id="now_page_num" value="1">
<!-- 1ページに表示する件数 -->
<input type="hidden" id="page_num" value="1">
<!-- 1ページに表示する件数 -->
<input type="hidden" id="page_disp_cnt" value="10">

<!-- End Personal Input -->

</body>

</html>