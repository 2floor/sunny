<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';
require_once __DIR__ . '/../logic/common/common_logic.php';

$common_logic = new common_logic();

$data_cancer = $common_logic->select_logic_no_param("SELECT id, cancer_type FROM m_cancer");
$data_cancer_option = '';
foreach ($data_cancer as $dco_row) {
	$data_cancer_option .= '<option value="' . $dco_row['id'] . '"' . ($dco_row === reset($data_cancer) ? ' selected' : '') . '>' . $common_logic->zero_padding($dco_row['id'], 5) . "-" . $dco_row['cancer_type'] . '</option>';
}

$data_area = $common_logic->select_logic_no_param("SELECT id as area_id, area_name FROM m_area");
$data_area_option = '';
foreach ($data_area as $dao_row) {
	$data_area_option .= '<option value="' . $dao_row['area_id'] . '">' . $common_logic->zero_padding($dao_row['area_id'], 5) . "-" . $dao_row['area_name'] . '</option>';
}

?>
<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__ . '/../required/html_head.php'; ?>
	<!-- <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.9/dist/sweetalert2.min.css" rel="stylesheet"> -->
	<style>
		#dataTable th {
			background-color: #fff;
			color: #0b0b0b;
		}

		#dataTable th>i {
			position: relative;
			float: right;
			right: 20px;
		}

		.action-footer {
			display: flex;
			align-items: center;
			gap: 10px;
			width: fit-content;
		}

		.action-select {
			padding: 5px;
			font-size: 14px;
		}

		.btn-submit {
			padding: 5px 10px;
			background-color: #007bff;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		.btn-submit:hover {
			background-color: #0056b3;
		}

		.mm_status_confirmed {
			background-color: #AFF4C6;
		}

		.searchBox .row {
			margin-bottom: 10px;
		}

		.searchTxt {
			font-size: 14px;
			color: #333333;
			font-weight: bold;
			background-color: transparent;
			border: none;
			border-radius: unset;
			padding: 7px 0px;
			height: 38px;
			max-width: 100%;
			box-shadow: none;
			transition: all 300ms linear;
			line-height: 1.42857143;
			display: block;
			width: 100%;
		}

		.list-button-search {
			text-align: right;
		}

		.select2-container--default .select2-selection--single {
			background-color: #fff;
			border: 1px solid #e3e3e3;
			border-radius: 4px;
			height: 38px;
		}

		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 38px;
		}

		.select2-container--default .select2-selection--single .select2-selection__rendered {
			line-height: 38px;
		}

		.select2-container--default .select2-selection--single .select2-selection__clear {
			font-size: 22px;
		}

        .same-size-btn {
            width: 120px;
            height: 40px;
        }

        .custom-reset-btn {
            background-color: #ffffff;
            color: #007bff;
            border: 1px solid #007bff;
        }

        .custom-reset-btn:hover {
            background-color: #007bff;
            color: #ffffff;
        }

		a.accepted,
		a.accepted:hover,
		a.accepted:focus,
		a.accepted:active {
			color: #9dd2b0;
			text-decoration: none;
		}

		#confirmModalLabel {
			font-weight: bold;
			font-size: 18px;
		}

		#confirmCheckModalBody>div {
			margin: 0 0 10px;
			font-size: 16px;
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
                                医療機関名寄せ
							</h2>
						</div>
					</div>
				</div>
				<!-- /pageTitle -->

				<!-- Start Data List Area -->
				<div class="disp_area list_show list_disp_area">
					<!-- searchBox -->
					<div class="container table-rep-plugin">
						<div class="searchBox row">

							<form name="search_form" class="searchArea col-sm-12">
								<div class="col-sm-12">
									<div class="row">
										<!-- <div class="col-sm-2"> -->
										<span class="searchTxt">
											絞り込み検索
										</span>
										<!-- </div> -->
										<div class="col-sm-6">
											<select class="selection2 cancer-selection form-control col-sm-5" name="search_cancer">
												<?= $data_cancer_option ?>
											</select>
										</div>
										<div class="col-sm-6">
											<select class="selection2 area-selection form-control col-sm-5" name="search_area">
												<option value="">-- セレクト --</option>
												<?= $data_area_option ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="row">
										<div class="input-group-search col-sm-6">
											<input type="text" id="multitext" name="multitext" class="form-control" placeholder="フリーワードを入力">
										</div>

										<div class="col-sm-6">
											<div class="list-button-search">
                                                <button type="reset" class="btn waves-effect waves-light btn-secondary same-size-btn custom-reset-btn">リセット</button>
                                                <button type="button" name="search_submit" class="btn waves-effect waves-light btn-primary same-size-btn">検索</button>
											</div>
										</div>
									</div>
								</div>
							</form>

							<!-- <div class="searchBoxRight">
								<div class="serachW110">
									<button type="button" name="new_entry" class="btn btn-primary waves-effect w-md waves-light m-b-5">新規登録</button>
								</div>
							</div> -->
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
										<div class="btn-toolbar" style="margin-left: 0;">
											<div class="action-footer pull-left" style="display: none;">
												<select id="actionSelect" class="btn btn-default dropdown-toggle text-muted">
													<option value="">-- アクションを選択する --</option>
													<option value="accept_list">紐付けの設定</option>
													<option value="cancel_list">紐付けの解除</option>
												</select>
												<button id="submitAction" class="btn btn-default btn-primary">実行する</button>
											</div>
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
											<table class="table parts" id="dataTable">
												<thead class="tableHeadArea">
													<tr>
														<th><input type="checkbox" id="selectAllCheckbox"></th>
														<th>No</th>
														<th>都道府県名</th>
														<th>がん種<br><span class="thead_type"></span></th>
														<th style="width: 250px">医療機関名<br>(基本)</th>
														<th style="width: 250px">
															医療機関名<br>
															<span id="thead_year_2"></span>
														</th>
														<th style="width: 250px">
															医療機関名<br>
															<span id="thead_year_1"></span>
														</th>
														<th style="width: 250px">
															医療機関名<br>
															<span id="thead_year_0"></span>
														</th>
														<th>類似度</th>
														<th style="width: 25px;"></th>
														<th style="width: 25px;"></th>
														<th style="width: 25px;"></th>
														<!-- <th>操作</th> -->
														<!-- <th>公開</th> -->
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

				<div class="modal fade" id="confirmCheckModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="confirmModalLabel">確認</h5>
							</div>
							<div class="modal-body" id="confirmCheckModalBody">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
								<button type="button" id="confirmCheckButton" class="btn btn-warning">有効にする</button>
							</div>
						</div>
					</div>
				</div>
				<!-- container -->
			</div>
			<!-- content -->
		</div>

	</div>
	<!-- END wrapper -->
	<?php require_once __DIR__ . '/../required/foot.php'; ?>
	<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.9/dist/sweetalert2.min.js"></script> -->
	<!-- Start Personal script -->
	<script src="../assets/admin/js/missmatch.js"></script>




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