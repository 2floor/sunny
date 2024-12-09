<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';
require_once __DIR__ . '/../logic/common/common_logic.php';

$common_logic = new common_logic();

$data_type = $common_logic->select_logic_no_param("SELECT DISTINCT(level1), data_type FROM t_category", []);
$data_type_option = '';
foreach ($data_type as $dto_row) {
	$data_type_option .= '<option value="' . $dto_row['level1'] . '">' . $dto_row['level1'] . '</option>';
}



?>
<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__ . '/../required/html_head.php'; ?>
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
								カテゴリ⼀覧
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
														<th>分類</th>
														<th>見出し</th>
														<th>⼤分類</th>
														<th>アイテム名</th>
														<th>詳細</th>
														<th>登録⽇時</th>
														<th>更新⽇時</th>
														<th></th>
														<th></th>
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

					<!-- pager -->
					<div class="container">
						<div class="listPagerBox">
							<div class="listPagerTxt now_disp_cnt_str">
							</div>
							<div class="listPager">
								<ul class="pagination pager_area">
								</ul>
							</div>
						</div>
					</div>
					<!-- /pager -->
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
								<!-- 								<button type="button" class="btn btn-primary waves-effect w-md waves-light m-b-5">登録する</button> -->
							</div>
						</div>
					</div>
					<!-- /btnBox -->

					<!-- userSetting -->
					<div class="container">
						<div class="row">
							<div class="col-xs-12" id="frm">
								<div class="contentBox">

									<div class="formRow">
										<div class="formItem">
											分類
											<span class="label01">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn30">
												<select class="form-control" name="is_whole_cancer" id="is_whole_cancer">
													<option value="0">医療機関基本</option>
													<option value="1">医療機関がん種</option>
												</select>
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											⼤分類
											<span class="label01">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn30">
												<select class="form-control" name="level1" id="level1">
													<?= $data_type_option ?>
												</select>
											</div>
										</div>
									</div>

									<div class="formRow">
										<div class="formItem">
											カテゴリ名
											<span class="label01">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate required" name="level2" id="level2" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											カテゴリ詳細
											<span class="label01">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<textarea type="text" class="form-control validate required" name="level3" id="level3"></textarea>
											</div>
										</div>
									</div>

									<div class="formRow">
										<div class="formItem">
											有効状態
											<span class="label01">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<select class="form-control" name="public_flg" id="public_flg">
													<option value="0">有効</option>
													<option value="1">無効</option>
												</select>
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

				<!-- Start Data Info Area -->
				<div class="disp_area info_disp_area">
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								<div class="contentBox">
									<div class="agreeInfoDate">
										日付:2018-05-25
									</div>
									<div class="agreeInfoTitBox">
										<div class="agreeInfoLeft">
											<h3 class="agreeInfoTit">
												○○の契約が○○です
												<span class="batch06">対応中</span>
											</h3>
											<p class="agreeDiscTxt">
												設定された1つの計画中。6つの計画が進行中。現在の計画はありません。
											</p>
											<div class="agreeBarBox">
												<div class="agreeBarTxt">
													<div class="agreeBarTxtLeft">計画</div>
													<div class="agreeBarTxtRight">45％</div>
												</div>
												<div id="bar" class="progress progress-bar-primary-alt" style="justify-content: space-between;">
													<div class="bar progress-bar progress-bar-primary" style="width: 33.3333%;"></div>
												</div>
											</div>
										</div>
										<div class="agreeInfoRight">
											<div class="agreeInfoRightDate">契約日：2018/05/25</div>
											<div class="agreeInfoRightId">Order ID：#123456</div>
										</div>
									</div>
									<div class="agreeTbBox">
										<div class="table-responsive">
											<table class="table parts">
												<thead>
													<tr>
														<th>＃</th>
														<th>希望日</th>
														<th>船名</th>
														<th>積荷情報</th>
														<th>希望金額</th>
														<th>備考</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															豊栄通商
														</td>
														<td>
															豊栄通商
														</td>
														<td>
															6400mt
														</td>
														<td>
															21分前
														</td>
														<td>
															21分前
														</td>
														<td>
															21分前
														</td>
													</tr>
													<tr>
														<td>
															豊栄通商
														</td>
														<td>
															豊栄通商
														</td>
														<td>
															6400mt
														</td>
														<td>
															21分前
														</td>
														<td>
															21分前
														</td>
														<td>
															21分前
														</td>
													</tr>
													<tr>
														<td>
															豊栄通商
														</td>
														<td>
															豊栄通商
														</td>
														<td>
															6400mt
														</td>
														<td>
															21分前
														</td>
														<td>
															21分前
														</td>
														<td>
															21分前
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="agreewTotal">
										合計：10,000,000円
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Data Info Area -->

				<!-- container -->
			</div>
			<!-- content -->
		</div>

	</div>
	<!-- END wrapper -->
	<?php require_once __DIR__ . '/../required/foot.php'; ?>
	<!-- Start Personal script -->
	<script src="../assets/admin/js/category.js"></script>




	<!-- End Personal script -->
	<!-- Start Personal Input -->
	<input type="hidden" id="ct_url" value="../controller/admin/category_ct.php">
	<input type="hidden" id="id" value="">
	<input type="hidden" id="page_type" value="">
	<input type="password" id="before_password" value="" style="display: none;">
	<input type="hidden" id="common_ct_url" value="../controller/admin/common_ct.php">
	<!--  //ファイル即時アップロード用CT -->
	<input type="hidden" id="img_path1" value="category/">
	<input type="hidden" id="img_length1" class="hid_img_length" value="9999999999">
	<input type="hidden" id="img_type1" class="hid_img_type" value="jpg,jpeg,JPG,JPEG,png,PNG,gif,GIF">
	<!-- 現在のページ位置 -->
	<input type="hidden" id="now_page_num" value="1">
	<!-- 1ページに表示する件数 -->
	<input type="hidden" id="page_num" value="1">
	<!-- 1ページに表示する件数 -->
	<input type="hidden" id="page_disp_cnt" value="10">

	<!-- End Personal Input -->

</body>

</html>