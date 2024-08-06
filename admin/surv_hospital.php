<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';
require_once __DIR__ . '/../controller/admin/surv_hospital_ct.php';

$surv_hospital_ct = new surv_hospital_ct();
$initData = $surv_hospital_ct->init_entry_new();
$cancers = $initData['cancers'] ?? [];
$hospitals = $initData['hospitals'] ?? [];

$htmlCancerOption = '';
foreach ($cancers as $cancer) {
    $htmlCancerOption .= '<option value="'.$cancer['id'].'">'.$cancer['cancer_type'].($cancer['cancer_type_surv'] ? ('—'.$cancer['cancer_type_surv']): '').'</option>';
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
                            生存率一覧
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
<!--                        <div class="searchBoxRight">-->
<!--                            <div class="serachW110">-->
<!--                                <button type="button" name="new_entry" class="btn btn-primary waves-effect w-md waves-light m-b-5">新規登録</button>-->
<!--                            </div>-->
<!--                        </div>-->
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
                                                <th>年度</th>
                                                <th>医療機関名</th>
                                                <th>がん種(Surv)</th>
                                                <th>総数</th>
                                                <th>生存率係数</th>
                                                <th>作成日時</th>
                                                <th>更新日時</th>
                                                <th>操作</th>
                                                <th>公開</th>
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
                                        年度
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
                                        合計
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_num" id="total_num" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージI対象者数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="stage_target1" id="stage_target1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージII対象者数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="stage_target2" id="stage_target2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージIII対象者数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="stage_target3" id="stage_target3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージIV対象者数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="stage_target4" id="stage_target4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        生存率係数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate number" name="survival_rate" id="survival_rate" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        調整済み生存率係数
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate number" name="adjustment_survival_rate" id="adjustment_survival_rate" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージI5年生存率
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate number" name="stage_survival_rate1" id="stage_survival_rate1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージII5年生存率
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate number" name="stage_survival_rate2" id="stage_survival_rate2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージIII5年生存率
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate number" name="stage_survival_rate3" id="stage_survival_rate3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        ステージIV5年生存率
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate number" name="stage_survival_rate4" id="stage_survival_rate4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(全段階の患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_stage_total_taget" id="total_stage_total_taget" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(全段階の患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_stage_total_taget" id="local_stage_total_taget" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(全段階の患者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_stage_total_taget" id="pref_stage_total_taget" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージI対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_stage_taget1" id="total_stage_taget1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージI対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_stage_taget1" id="local_stage_taget1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージI対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_stage_taget1" id="pref_stage_taget1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ2対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_stage_taget2" id="total_stage_taget2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ2対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_stage_taget2" id="local_stage_taget2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ2対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_stage_taget2" id="pref_stage_taget2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ3対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_stage_taget3" id="total_stage_taget3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ3対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_stage_taget3" id="local_stage_taget3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ3対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_stage_taget3" id="pref_stage_taget3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ4対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_stage_taget4" id="total_stage_taget4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ4対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_stage_taget4" id="local_stage_taget4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ4対象者数)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_stage_taget4" id="pref_stage_taget4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(総生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_survival_rate" id="total_survival_rate" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(総生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_survival_rate" id="local_survival_rate" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(総生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_survival_rate" id="pref_survival_rate" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ1合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_survival_rate1" id="total_survival_rate1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ1合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_survival_rate1" id="local_survival_rate1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ1合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_survival_rate1" id="pref_survival_rate1" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ2合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_survival_rate2" id="total_survival_rate2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ2合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_survival_rate2" id="local_survival_rate2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ2合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_survival_rate2" id="pref_survival_rate2" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ3合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_survival_rate3" id="total_survival_rate3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ3合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_survival_rate3" id="local_survival_rate3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ3合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_survival_rate3" id="pref_survival_rate3" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        全国順位(ステージ4合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="total_survival_rate4" id="total_survival_rate4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        地方順位(ステージ4合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="local_survival_rate4" id="local_survival_rate4" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        都道府県順位(ステージ4合計生存率)
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate integer" name="pref_survival_rate4" id="pref_survival_rate4" value="">
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
<script src="../assets/admin/js/surv_hospital.js"></script>




<!-- End Personal script -->
<!-- Start Personal Input -->
<input type="hidden" id="ct_url" value="../controller/admin/surv_hospital_ct.php">
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