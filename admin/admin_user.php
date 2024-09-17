<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once __DIR__ . '/../required/html_head.php';?>
</head>
<body class="fixed-left">
	<!-- Begin page -->
	<div id="wrapper">
		<?php require_once __DIR__ . '/../required/menu.php';?>
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
								管理者一覧
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
<!-- 							<div class="searchBoxLeft"> -->
<!-- 								<div class="searchBox1"> -->
<!-- 									<div class="searchTxt"> -->
<!-- 										絞り込み検索 -->
<!-- 									</div> -->
<!-- 									<select class="form-control"> -->
<!-- 										<option>船名</option> -->
<!-- 										<option>船名1</option> -->
<!-- 										<option>船名2</option> -->
<!-- 										<option>船名3</option> -->
<!-- 										<option>船名4</option> -->
<!-- 										<option>船名5</option> -->
<!-- 									</select> -->
<!-- 								</div> -->
<!-- 								<div class="searchBox2"> -->
<!-- 									<div class="input-group"> -->
<!-- 										<input type="text" id="example-input2-group2" name="example-input2-group2" class="form-control" placeholder="フリーワードを入力"> -->
<!-- 										<span class="input-group-btn"> -->
<!-- 											<button type="button" class="btn waves-effect waves-light btn-primary">検索</button> -->
<!-- 										</span> -->
<!-- 									</div> -->
<!-- 								</div> -->
<!-- 							</div> -->
							<div class="searchBoxRight">
								<div class="serachW110">
									<button type="button" name="new_entry" class="btn btn-primary waves-effect w-md waves-light m-b-5">新規登録</button>
								</div>
							</div>
						</div>
					</div>
					<!-- searchBox -->

					<!-- pager -->
<!-- 					<div class="container"> -->
<!-- 						<div class="listPagerBox"> -->
<!-- 							<div class="listPagerTxt now_disp_cnt_str"> -->
<!-- 							</div> -->
<!-- 							<div class="listPager"> -->
<!-- 								<ul class="pagination pager_area"> -->
<!-- 								</ul> -->
<!-- 							</div> -->
<!-- 						</div> -->
<!-- 					</div> -->
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
														<th>管理ID</th>
														<th>削除状態</th>
														<th>ログインID</th>
														<th>パスワード</th>
														<th>ユーザー名</th>
														<th>登録日時</th>
														<th>最終ログイン</th>
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

					<!-- pager -->
<!-- 					<div class="container"> -->
<!-- 						<div class="listPagerBox"> -->
<!-- 							<div class="listPagerTxt now_disp_cnt_str"> -->
<!-- 							</div> -->
<!-- 							<div class="listPager"> -->
<!-- 								<ul class="pagination pager_area"> -->
<!-- 								</ul> -->
<!-- 							</div> -->
<!-- 						</div> -->
<!-- 					</div> -->
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
											ユーザーID
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate required password" name="user_id" id="user_id" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											ユーザー名
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate required" name="id" id="id" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											パスワード
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="password" class="form-control validate required password" name="password" id="password" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											パスワード(確認)
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="password" class="form-control validate required password_conf" name="conf_password" id="conf_password" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											管理画面権限設定
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<table class="admin_authority_table">
												<tr id="admin_authority_tr">
												</tr>
											</table>
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
<?php require_once __DIR__ . '/../required/foot.php';?>
<!-- Start Personal script -->
<script src="../assets/admin/js/admin_user.js"></script>




<!-- End Personal script -->
<!-- Start Personal Input -->
<input type="hidden" id="ct_url" value="../controller/admin/admin_user_ct.php">
<input type="hidden" id="id" value="">
<input type="hidden" id="page_type" value="">
<input type="password" id="before_password" value="" style="display: none;">
<input type="hidden" id="common_ct_url" value="../controller/admin/common_ct.php">
<!--  //ファイル即時アップロード用CT -->
<input type="hidden" id="img_path1" value="admin_user/">
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