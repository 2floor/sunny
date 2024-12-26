<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../required/view_common_include.php';
require_once __DIR__ . '/../controller/admin/hospital_ct.php';

$hospital_ct = new hospital_ct();
$initData = $hospital_ct->init_entry_new();
$cancers = $initData['cancers'] ?? [];
$groupedCategory = $initData['grouped_category'] ?? [];
$areas = $initData['area'] ?? [];

$htmlCancerOption = '';
foreach ($cancers as $cancer) {
    $htmlCancerOption .= '<option value="'.$cancer['id'].'">'.$cancer['cancer_type'].'</option>';
}

$htmlAreaOption = '';
foreach ($areas as $area) {
    $htmlAreaOption .= '<option value="'.$area['id'].'">'.$area['area_name'] . '—' .$area['pref_name'] .'</option>';
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

        .area-selection {
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
            transition: height 1s ease-in-out;
        }
        .arrow {
            float: right;
            font-size: 14px;
        }

        .panel-body {
            padding: 20px;
        }

        .checkbox-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 15px;
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
                            病院一覧
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
                                <input type="file" id="upload-file" data-type="hospital" name="upload-file" class="form-control upload-file-hidden upload-csv" accept=".csv,.xlsx,.xls">
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
                                                <th>医療機関名</th>
                                                <th>エリア</th>
                                                <th>住所</th>
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
                                        病院Code
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate required" name="hospital_code" id="hospital_code">
                                        </div>
                                    </div>
                                </div>
                                <div class="formRow">
                                    <div class="formItem">
                                        医療機関名
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate required" name="hospital_name" id="hospital_name">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        エリア
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <select class="selection2 area-selection validate required" name="area_id">
                                                <option value="" disabled selected hidden></option>
                                                <?php
                                                    echo $htmlAreaOption;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        医療機関のがんの種類
                                        <span class="label01 require_text">必須</span>
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <div class="panel-group" id="accordionCancer">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" data-toggle="collapse" data-parent="#accordionCancer" href="#cancerCollapseOne">
                                                        <h4 class="panel-title">
                                                            がんの種類のリスト
                                                            <span class="glyphicon glyphicon-chevron-down arrow"></span>
                                                        </h4>
                                                    </div>
                                                    <div id="cancerCollapseOne" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <div class="checkbox-content validate required">
                                                               <?php
                                                                    foreach ($cancers as $cancer) {
                                                                        $html = '<div>';
                                                                        $html .= '<label><input style="width: unset" type="checkbox" class="form-control" name="cancers[]" value="'.($cancer['id'] ?? '').'">'.($cancer['cancer_type'] ?? '').'</label>';
//                                                                        $html .= '<label style="flex-direction: column; gap:5px">学会認定施設情報<input type="text" name="socialInfoCancer'.($cancer['id'] ?? '').'" class="form-control"></label>';
                                                                        $html .= '</div>';

                                                                        echo $html;
                                                                    }
                                                               ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        住所
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="addr" id="addr">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        電話番号
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate tel-text" name="tel" id="tel">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        公式URL
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate url" name="hp_url" id="hp_url">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        学会認定施設情報
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="social_info" id="social_info">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        がん相談支援センターURL
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate url" name="support_url" id="support_url">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        患者紹介方法URL
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control validate url" name="introduction_url" id="introduction_url">
                                        </div>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        カテゴリー
                                    </div>
                                    <div class="form-inline" style="width: 86%">
                                        <?php
                                        $html = '<div class="tab-pane" role="tabpanel">';
                                        $html .= '<div class="result-content-tab">';
                                        $html .= '<div class="panel-group" id="accordion">';
                                        $accordionChild = 0;
                                        foreach ($groupedCategory as $level1 => $category) {
                                            $accordionChild++;
                                            $html .= '<div class="panel panel-default">';
                                            $html .= '<div class="panel-heading collapsed" data-toggle="collapse" data-parent="#accordion" href="#'.$level1.'" aria-expanded="false">';
                                            $html .= '<p class="panel-title">'.$level1.'<span class="glyphicon arrow glyphicon-chevron-down"></span> </p>';
                                            $html .= '</div>';
                                            $html .= '<div id="'.$level1.'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">';
                                            $html .= '<div class="panel-body">';
                                            $html .= '<div class="tab-pane" role="tabpanel">';
                                            $html .= '<div class="result-content-tab">';
                                            $html .= '<div class="panel-group" id="accordion'.$accordionChild.'">';
                                            foreach ($category as $level2 => $item) {
                                                $html .= '<div class="panel panel-default">';
                                                $html .= '<div class="panel-heading collapsed" data-toggle="collapse" data-parent="#accordion'.$accordionChild.'" href="#'.$level2.'" aria-expanded="false">';
                                                $html .= '<p class="panel-title">'.$level2.'<span class="glyphicon arrow glyphicon-chevron-down"></span> </p>';
                                                $html .= '</div>';
                                                $html .= '<div id="'.$level2.'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">';
                                                $html .= '<div class="panel-body">';
                                                $html .= '<div class="checkbox-content">';
                                                foreach ($item as $level3 => $item2) {
                                                    $main = $item2[0] ?? [];
                                                    $html .= '<div>';
                                                    $html .= '<label><input type="checkbox" class="form-control" name="categories[]" value="'.($main['id'] ?? '').'">'.($main['level3'] ?? '').'</label>';

                                                    if (!($main['is_whole_cancer'] ?? 1))
                                                    {
                                                        $html .= '<label>がんの種類<select class="selection2 cate-cancer-selection" name="cateCancer'.($main['id'] ?? '').'" multiple="multiple">';
                                                        $html .= '<option value="" disabled selected hidden></option>';
                                                        $html .= $htmlCancerOption;
                                                        $html .= '</select></label>';
                                                    }
                                                    $html .= '<label>コンテンツ<input type="text" name="cateContent'.($main['id'] ?? '').'" class="form-control"></label>';
                                                    $html .= '</div>';
                                                }
                                                $html .= '</div>';
                                                $html .= '</div>';
                                                $html .= '</div>';
                                                $html .= '</div>';
                                            }

                                            $html .= '</div>';
                                            $html .= '</div>';
                                            $html .= '</div>';
                                            $html .= '</div>';
                                            $html .= '</div>';
                                            $html .= '</div>';
                                        }
                                        $html .= '</div>';
                                        $html .= '</div>';
                                        $html .= '</div>';

                                        echo $html;
                                        ?>
                                    </div>
                                </div>

                                <div class="formRow">
                                    <div class="formItem">
                                        備考
                                    </div>
                                    <div class="formTxt">
                                        <div class="formIn50">
                                            <input type="text" class="form-control" name="remarks" id="remarks">
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
<script src="../assets/admin/js/hospital.js"></script>
<script>
    $('#accordion, #accordionCancer').on('show.bs.collapse', function(e) {
        $(e.target).prev('.panel-heading').addClass('active');
        $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    });

    $('#accordion, #accordionCancer').on('hide.bs.collapse', function(e) {
        $(e.target).prev('.panel-heading').removeClass('active');
        $(e.target).prev('.panel-heading').find('.arrow').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    });
</script>



<!-- End Personal script -->
<!-- Start Personal Input -->
<input type="hidden" id="ct_url" value="../controller/admin/hospital_ct.php">
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
<input type="hidden" id="upload_csv_type" value="hospital">

<!-- End Personal Input -->

</body>

</html>