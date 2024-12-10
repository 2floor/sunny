<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';
?>
<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__ . '/../required/html_head.php'; ?>
	<style>
		.select2-container .select2-selection--single {
			height: 32px;
		}

		.group-selection {
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
								処理済一覧
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

							<form name="search_form" class="searchBoxLeft searchArea">
								<div class="searchBox1">
									<div class="searchTxt">
										絞り込み検索
									</div>
									<select class="form-control" name="search_area">
									</select>
								</div>
								<div class="searchBox2">
									<div class="input-group">
										<input type="text" id="multitext" name="multitext" class="form-control" placeholder="フリーワードを入力">
										<span class="input-group-btn">
											<button type="button" name="search_submit" class="btn waves-effect waves-light btn-primary">検索</button>
											<!-- <button type="reset" class="btn waves-effect waves-light btn-secondary">リセット</button> -->
										</span>

									</div>
								</div>
							</form>

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
														<th></th>
														<th>No</th>
														<th>都道府県名</th>
														<th>がん種<br><span class="thead_type"></span></th>
														<th>医療機関名<br>(基本)</th>
														<th>
															<span class="thead_type"></span>
															医療機関名<br>
															<span id="thead_year_2"></span>
														</th>
														<th>
															<span class="thead_type"></span>
															医療機関名<br>
															<span id="thead_year_1"></span>
														</th>
														<th>
															<span class="thead_type"></span>
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

				<!-- container -->
			</div>
			<!-- content -->
		</div>

	</div>
	<!-- END wrapper -->
	<?php require_once __DIR__ . '/../required/foot.php'; ?>
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