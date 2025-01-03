<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php';




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
								会員一覧
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
														<th>法人名・営業所名</th>
														<th>詳細</th>
														<th>メール</th>
														<th>作成日時</th>
														<th>更新日時</th>
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
											法人名
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate required" name="name" id="name" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											法人名ふりがな
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate required" name="name_kana" id="name_kana" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											営業所名
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate " name="office_name" id="office_name" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											営業所名ふりがな
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate " name="office_name_kana" id="office_name_kana" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											郵便番号
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="tel" class="form-control validate number required" name="zip" id="zip" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											都道府県
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<select class="form-control validate required" name="pref" id="pref">
													<?php print PREF_SELECT_HTML3 ?>
												</select>
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											住所
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate required" name="addr" id="addr" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											電話番号
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="tel" class="form-control validate number required" name="tel" id="tel" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											FAX
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="tel" class="form-control validate number required" name="fax" id="fax" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											代表者名
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate required" name="resp_name" id="resp_name" value="">
											</div>
										</div>
									</div>
									<div class="formRow" style="display:none;">
										<div class="formItem">
											役職
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate" name="job" id="job" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											メールアドレス
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="email" class="form-control validate required mail" name="mail" id="mail" value="">
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
												<input type="password" class="form-control validate password required" name="password" id="password" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											紹介企業コード
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate  " name="s_code" id="s_code" value="">
											</div>
										</div>
									</div>
									<div class="formRow" style="display:none">
										<div class="formItem">
											支払い条件
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate " name="payment" id="payment" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											事業内容
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<!-- <textarea type="text" class="form-control validate " name="jigyou" id="jigyou"></textarea> -->
												<div class="chk_cl">
													<input type="checkbox" name="jigyou" id="jigyou" value="0" class="validate checkboxRequired"><label for="jigyou" class="">倉庫業</label>
												</div>
												<div class="chk_cl">
													<input type="checkbox" name="jigyou" id="jigyou_2" value="1" class="validate checkboxRequired"><label for="jigyou" class="">運送行</label>
												</div>
												<div class="chk_cl">
													<input type="checkbox" name="jigyou" id="jigyou_3" value="2" class="validate checkboxRequired"><label for="jigyou" class="">メーカー</label>
												</div>
												<div class="chk_cl">
													<input type="checkbox" name="jigyou" id="jigyou_4" value="3" class="validate checkboxRequired"><label for="jigyou" class="">その他</label>
												</div>
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											トラック保有
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<!-- <input type="tel" class="form-control validate number" name="truck_num" id="truck_num" value=""> -->
												<select class="form-control validate number required" name="truck_num" id="truck_num">
													<option value="">選択して下さい</option>
													<option value="1">あり</option>
													<option value="0">なし</option>
												</select>
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											URL
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<input type="text" class="form-control validate" name="URL" id="URL" value="">
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											利用プラン
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<select class="form-control validate number required" name="etc2" id="etc2">
													<option value="">選択して下さい</option>
													<option value="0">無料プラン</option>
													<option value="1">有料プラン</option>
												</select>
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formItem">
											利用開始日
											<span class="label01 require_text">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50" style="display: flex; align-items: center;">
												<input type="date" class="form-control validate required" name="etc1" id="etc1" value="0">
											</div>
										</div>
									</div>
									<div class=" formRow">
										<div class="formItem">
											認証状態
											<span class="label01">必須</span>
										</div>
										<div class="formTxt">
											<div class="formIn50">
												<select class="form-control" name="questionnaire" id="questionnaire">
													<option value="0">未認証</option>
													<option value="1">認証済み</option>
													<option value="99">認証不可</option>
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

				<!-- container -->
			</div>
			<!-- content -->
		</div>

	</div>
	<!-- END wrapper -->
	<?php require_once __DIR__ . '/../required/foot.php'; ?>
	<!-- Start Personal script -->
	<script src="../assets/admin/js/member.js"></script>




	<!-- End Personal script -->
	<!-- Start Personal Input -->
	<input type="hidden" id="ct_url" value="../controller/admin/member_ct.php">
	<input type="hidden" id="id" value="">
	<input type="hidden" id="page_type" value="">
	<input type="password" id="before_password" value="" style="display: none;">
	<input type="hidden" id="common_ct_url" value="../controller/admin/common_ct.php">
	<!--  //ファイル即時アップロード用CT -->
	<input type="hidden" id="img_path1" value="member/">
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