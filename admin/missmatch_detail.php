<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../required/view_common_include.php';
require_once __DIR__ . '/../controller/admin/missmatch_ct.php';

use App\Models\MissMatch;

$cancer_id = $_GET['cancer_id'];
$hospital_id = $_GET['hospital_id'];
$type = $_GET['type'] ? (int)$_GET['type']: null;

if (!$cancer_id || !$hospital_id || !$type) {
    header("Location: missmatch.php");
    exit();
}

if (!in_array($type, MissMatch::getTypeList())) {
    header("Location: missmatch.php");
    exit();
}

$missmatch_ct = new missmatch_ct();
$initData = $missmatch_ct->get_mm_detail($hospital_id, $cancer_id, $type);

if (empty($initData)) {
    header("Location: missmatch.php");
    exit();
}

$spec_cancer_name = match ($type) {
    MissMatch::TYPE_DPC => "がん種(DPC)",
    MissMatch::TYPE_STAGE => "がん種(Stage)",
    MissMatch::TYPE_SURVIVAL => "がん種(Surv)",
    default => '',
};

$missmatch_detail_page_title = '';
if ($type === 1) {
    $missmatch_detail_page_title = '医療機関名寄せ編集（年間入院患者数データ)';
} elseif ($type === 2) {
    $missmatch_detail_page_title = '医療機関名寄せ編集（年間新規入院患者数（ステージ)';
} elseif ($type === 3) {
    $missmatch_detail_page_title = '医療機関名寄せ編集（生存率のデータ)';
}

?>
<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . '/../required/html_head.php'; ?>
    <style>
        .card-box {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .header-buttons span {
            background-color: #ffd200;
            color: #000;
            padding: 10px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table th, .data-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .data-table th {
            background-color: #ffd87f;
        }

        .data-table .remove-icon {
            color: red;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            font-size: 20px;
        }

        .footer-buttons {
            display: flex;
            justify-content: space-between;
        }

        .footer-buttons button {
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .bg-warning {
            color: #FFFFFF;
        }

        .bg-warning:hover {
            background-color: #fbb610 !important;
        }

        .bg-primary:hover {
            background-color: #0872c3 !important;
        }

        .warning {
            background-color: #ffe1d6;
            color: #ff4500;
            font-size: 12px;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .status_absolutely, .status_confirmed {
            background-color: #AFF4C6;
        }

        .status_not_match {
            background-color: #FFE1D6;
        }
        .sweet-alert .btn {
            width: 30% !important;
            margin: 0 1% !important;
        }

        .sweet-alert .swal-button--cancel {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
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
                            <?= $missmatch_detail_page_title ?>
                        </h2>
                    </div>
                </div>
            </div>
            <!-- /pageTitle -->

            <!-- Start Data List Area -->
            <div class="disp_area list_show list_disp_area">

                <!-- list1Col -->
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card-box">
                                <div class="header-buttons">
                                </div>
                                <table class="data-table">
                                    <thead>
                                    <tr>
                                        <th>エリアID</th>
                                        <th>医療機関名</th>
                                        <th>医療機関ID</th>
                                        <th>年度</th>
                                        <th>がん種名</th>
                                        <th><?= $spec_cancer_name ?></th>
                                        <th>年間入院患者</th>
                                        <th>類似度</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($initData as $value) { ?>

                                        <?php
                                            $op_mm = '';
                                            if ($value['status'] != MissMatch::STATUS_ABSOLUTELY_MATCH) {
                                                $list_mm = $missmatch_ct->get_not_confirm_mm_list($value['year'], $value['cancer_id'], $value['type']);
                                                foreach ($list_mm as $mm) {
                                                    if ($mm['hospital_name'] == $value['hospital_name']) {
                                                        continue;
                                                    } else {
                                                        $op_mm .= '<option value="'.$mm['id'].'">'.$mm['hospital_name'].'</option>';
                                                    }
                                                }

                                            }
                                        ?>
                                        <tr class="<?= ($value['status'] == MissMatch::STATUS_ABSOLUTELY_MATCH ? 'status_absolutely' : ($value['status'] == -1 ? 'status_not_match' : '') ) ?> mm-info">
                                            <td class="dpcArea"><?= ($value['area_id'] ?? '') ?></td>
                                            <td class="<?= ($value['status'] == MissMatch::STATUS_CONFIRMED ? 'status_confirmed' : '' )?>">
                                                <select class="form-control searchMM">
                                                    <option value="" selected><?= ($value['hospital_name'] ?? '') ?></option>
                                                    <?= $op_mm ?>
                                                </select>
                                            </td>
                                            <td><?= ($value['hospital_id'] ?? '') ?></td>
                                            <td class="yearMM"><?= ($value['year'] ?? '') ?></td>
                                            <td><?= ($value['cancer_type'] ?? '') ?></td>
                                            <td><?php
                                                    echo match ($type) {
                                                        MissMatch::TYPE_DPC => ($value['cancer_type_dpc'] ?? ''),
                                                        MissMatch::TYPE_STAGE => ($value['cancer_type_stage'] ?? ''),
                                                        MissMatch::TYPE_SURVIVAL => ($value['cancer_type_surv'] ?? ''),
                                                        default => '',
                                                    };
                                                ?>
                                            </td>
                                            <td class="dpcMM"><?= ($value['import_data'] ?? '') ?></td>
                                            <td class="percentMM"><?= ($value['percent_match'] ?? '') ?></td>
                                            <?php if($value['status'] != MissMatch::STATUS_ABSOLUTELY_MATCH && $value['status'] !=-1) { ?>
                                                <td class="remove-icon">×</td>
                                            <?php } else { ?>
                                                <td></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <div class="footer-buttons">
                                    <button class="bg-warning" id="cancer_all_mm">紐付けを解除する</button>
                                    <button class="bg-primary" id="confirm_mm">確認する</button>
                                </div>
                                <div class="warning">
                                    [紐付けを解除する] ボタンを選択すると、各年度の医療機関名と基本の医療機関名の紐付け設定が解除されます。
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /list1Col -->
            </div>
            <!-- END Data List Area -->

            <!-- container -->
        </div>
        <!-- content -->
    </div>

</div>
<!-- END wrapper -->
<?php require_once __DIR__ . '/../required/foot.php'; ?>
<script src="../assets/admin/js/missmatch_detail.js"></script>
<!-- Start Personal script -->

<!-- End Personal script -->
<!-- Start Personal Input -->
<input type="hidden" id="ct_url" value="../controller/admin/missmatch_ct.php">
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