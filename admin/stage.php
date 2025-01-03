<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';
require_once __DIR__ . '/../controller/admin/stage_ct.php';

$stage_ct = new stage_ct();
$initData = $stage_ct->init_entry_new();
$cancers = $initData['cancers'] ?? [];
$hospitals = $initData['hospitals'] ?? [];

$htmlCancerOption = '';
foreach ($cancers as $cancer) {
    $htmlCancerOption .= '<option value="'.$cancer['id'].'">'.$cancer['cancer_type'].($cancer['cancer_type_stage'] ? ('—'.$cancer['cancer_type_stage']): '').'</option>';
}

$htmlHospitalsOption = '';
foreach ($hospitals as $hospital) {
    $htmlHospitalsOption .= '<option value="'.$hospital['id'].'">'.$hospital['hospital_code'].'—'.$hospital['hospital_name'].'</option>';
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . '/../required/html_head.php'; ?>
    <style>
        .select2-container .select2-selection--single {
            height: 32px;
        }

        .hospital-selection, .cancer-selection {
            width: 100%;
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
                            年間新規入院患者数（ステージ)一覧
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
                            <div class="input-group">
                                <input type="text" id="file-name-display" class="form-control" placeholder="ファイルを選択" readonly>
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-primary callUpload" disabled>アップロード</button>
                                </span>
                                <input type="file" id="upload-file" data-type="stage" name="upload-file" class="form-control upload-file-hidden upload-csv" accept=".csv,.xlsx,.xls">
                            </div>
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
                                                <th>年</th>
                                                <th>医療機関名</th>
                                                <th>がん種(Stage)</th>
                                                <th>合計者数</th>
                                                <th>作成日時</th>
                                                <th>更新日時</th>
                                                <th style="width: 50px">操作</th>
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
                                        病院
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <select class="selection2 hospital-selection validate required" name="hospital_id">
                                                <option value="" disabled selected hidden></option>
                                                <?php
                                                echo $htmlHospitalsOption;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        がんの種類
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <select class="selection2 cancer-selection validate required" name="cancer_id">
                                                <option value="" disabled selected hidden></option>
                                                <?php
                                                echo $htmlCancerOption;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        年
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate required integer" name="year" id="year" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        合計年間新規患者数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_num_new" id="total_num_new" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージI年間新規患者数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="stage_new1" id="stage_new1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージ2年間新規患者数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="stage_new2" id="stage_new2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージ3年間新規患者数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="stage_new3" id="stage_new3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージ4年間新規患者数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="stage_new4" id="stage_new4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(合計年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_num_rank" id="total_num_rank" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(合計年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_num_rank" id="local_num_rank" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(合計年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_num_rank" id="pref_num_rank" value="">
                                        </div>
                                    </div>
                                </div>


                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージI年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_num_rank_stage1" id="total_num_rank_stage1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージI年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_num_rank_stage1" id="local_num_rank_stage1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージI年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_num_rank_stage1" id="pref_num_rank_stage1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ2年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_num_rank_stage2" id="total_num_rank_stage2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ2年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_num_rank_stage2" id="local_num_rank_stage2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ2年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_num_rank_stage2" id="pref_num_rank_stage2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ3年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_num_rank_stage3" id="total_num_rank_stage3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ3年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_num_rank_stage3" id="local_num_rank_stage3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ3年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_num_rank_stage3" id="pref_num_rank_stage3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ4年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_num_rank_stage4" id="total_num_rank_stage4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ4年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_num_rank_stage4" id="local_num_rank_stage4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ4年間新規患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_num_rank_stage4" id="pref_num_rank_stage4" value="">
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
<script src="../assets/admin/js/stage.js"></script>




<!-- End Personal script -->
<!-- Start Personal Input -->
<input type="hidden" id="ct_url" value="../controller/admin/stage_ct.php">
<input type="hidden" id="id" value="">
<input type="hidden" id="page_type" value="">
<input type="hidden" id="common_ct_url" value="../controller/admin/common_ct.php">
<!-- 現在のページ位置 -->
<input type="hidden" id="now_page_num" value="1">
<!-- 1ページに表示する件数 -->
<input type="hidden" id="page_num" value="1">
<!-- 1ページに表示する件数 -->
<input type="hidden" id="page_disp_cnt" value="10">
<input type="hidden" id="upload_csv_ct_url" value="../controller/admin/upload_csv_ct.php">
<input type="hidden" id="upload_csv_type" value="stage">

<!-- End Personal Input -->

</body>

</html>