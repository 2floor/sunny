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
							<h2 class="pageTitle">
								<i class="fa fa-list" aria-hidden="true"></i>
								保有船一覧
							</h2>
						</div>
					</div>
				</div>
				<!-- /pageTitle -->

				<!-- searchBox -->
				<div class="container table-rep-plugin">
					<div class="searchBox">
						<div class="searchBoxLeft">
							<div class="searchBox1">
								<div class="searchTxt">
									絞り込み検索
								</div>
								<select class="form-control">
									<option>船名</option>
									<option>船名1</option>
									<option>船名2</option>
									<option>船名3</option>
									<option>船名4</option>
									<option>船名5</option>
								</select>
							</div>
							<div class="serachW110">
								<select class="form-control">
									<option>会社名</option>
									<option>会社名1</option>
									<option>会社名2</option>
									<option>会社名3</option>
									<option>会社名4</option>
									<option>会社名5</option>
								</select>
							</div>
							<div class="searchBox2">
								<div class="input-group">
									<input type="text" id="example-input2-group2" name="example-input2-group2" class="form-control" placeholder="フリーワードを入力">
									<span class="input-group-btn">
										<button type="button" class="btn waves-effect waves-light btn-primary">検索</button>
									</span>
								</div>
							</div>
						</div>
						<div class="searchBoxRight">
							<div class="serachW110">
								<button type="button" class="btn btn-primary waves-effect w-md waves-light m-b-5">新規登録</button>
							</div>
							<div class="serachW110 pull-right">
								<button class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">表示項目<span class="caret"></span></button>
								<ul class="dropdown-menu"><li class="checkbox-row"><input type="checkbox" name="toggle-tech-companies-1-col-1" id="toggle-tech-companies-1-col-1" value="tech-companies-1-col-1"> <label for="toggle-tech-companies-1-col-1">Last Trade</label></li><li class="checkbox-row"><input type="checkbox" name="toggle-tech-companies-1-col-2" id="toggle-tech-companies-1-col-2" value="tech-companies-1-col-2"> <label for="toggle-tech-companies-1-col-2">Trade Time</label></li><li class="checkbox-row"><input type="checkbox" name="toggle-tech-companies-1-col-3" id="toggle-tech-companies-1-col-3" value="tech-companies-1-col-3"> <label for="toggle-tech-companies-1-col-3">Change</label></li><li class="checkbox-row"><input type="checkbox" name="toggle-tech-companies-1-col-4" id="toggle-tech-companies-1-col-4" value="tech-companies-1-col-4"> <label for="toggle-tech-companies-1-col-4">Prev Close</label></li><li class="checkbox-row"><input type="checkbox" name="toggle-tech-companies-1-col-5" id="toggle-tech-companies-1-col-5" value="tech-companies-1-col-5"> <label for="toggle-tech-companies-1-col-5">Open</label></li><li class="checkbox-row"><input type="checkbox" name="toggle-tech-companies-1-col-6" id="toggle-tech-companies-1-col-6" value="tech-companies-1-col-6"> <label for="toggle-tech-companies-1-col-6">Bid</label></li><li class="checkbox-row"><input type="checkbox" name="toggle-tech-companies-1-col-7" id="toggle-tech-companies-1-col-7" value="tech-companies-1-col-7"> <label for="toggle-tech-companies-1-col-7">Ask</label></li><li class="checkbox-row"><input type="checkbox" name="toggle-tech-companies-1-col-8" id="toggle-tech-companies-1-col-8" value="tech-companies-1-col-8"> <label for="toggle-tech-companies-1-col-8">1y Target Est</label></li></ul>
							</div>
						</div>
					</div>
				</div>
				<!-- searchBox -->

				<!-- pager -->
				<div class="container">
					<div class="listPagerBox">
						<div class="listPagerTxt">
							全 85 件中 1 件 〜 20 件を表示
						</div>
						<div class="listPager">
							<ul class="pagination">
								<li><a href="#">前</a></li>
								<li class="active"><a href="#">1</a></li>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li><a href="#">4</a></li>
								<li><a href="#">5</a></li>
								<li><a href="#">次</a></li>
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
								<div class="table-responsive">
									<table class="table parts">
										<thead>
											<tr>
												<th>ID</th>
												<th>船名</th>
												<th>製造年</th>
												<th>載貨重量</th>
												<th>デットウェイト(DWCC)</th>
												<th>ベールキャパ(BCAPA)</th>
												<th>全長</th>
												<th>ホールド数</th>
												<th>操作</th>
												<th>公開</th>
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
												<td>
													21分前
												</td>
												<td>
													21分前
												</td>
												<td>
													<a href="" class="clr1">
														<i class="fa fa-pencil" aria-hidden="true"></i>
													</a>
													<a href="" class="clr2">
														<i class="fa fa-trash" aria-hidden="true"></i>
													</a>
												</td>
												<td>
													<div class="listBtn1">
														<a href="">
															表示
														</a>
													</div>
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
												<td>
													21分前
												</td>
												<td>
													21分前
												</td>
												<td>
													<a href="" class="clr1">
														<i class="fa fa-pencil" aria-hidden="true"></i>
													</a>
													<a href="" class="clr2">
														<i class="fa fa-trash" aria-hidden="true"></i>
													</a>
												</td>
												<td>
													<div class="listBtn2">
														<a href="">
															非表示
														</a>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /list1Col -->

				<!-- pageTopNews -->
				<div class="container">
					<div class="row">
						<div class="pageTopNewsBox">
							<div class="pageTopNewsBatchBox">
								<span class="batch01">緊急</span>
							</div>
							<div class="pageTopNewsDate">
								6月8日
							</div>
							<div class="pageTopNewsTxt">
								この文章はダミーです。文字の大き
							</div>
						</div>
					</div>
				</div>
				<!-- /pageTopNews -->

				<!-- dashboard -->
				<div class="container">
					<div class="row">
						<!-- left -->
						<div class="col-lg-6">
							<!-- col -->
							<h3 class="colTitle">
								現在の契約状況
							</h3>
							<div class="card-box">
								<div class="table-responsive">
									<table class="table parts">
										<thead>
											<tr>
												<th>状態</th>
												<th>件数</th>
												<th>内容</th>
												<th>
													<a href="" class="sortArw">
														更新日
													</a>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<span class="batch02">未提出</span>
												</td>
												<td>10</td>
												<td>この文章はダミーです。文字の大き</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>
													<span class="batch03">最終契約中</span>
												</td>
												<td>10</td>
												<td>この文章はダミーです。文字の大き</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>
													<span class="batch04">契約終了</span>
												</td>
												<td>10</td>
												<td>この文章はダミーです。文字の大き</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>
													<span class="batch05">未確認</span>
												</td>
												<td>10</td>
												<td>この文章はダミーです。文字の大き</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>
													<span class="batch06">対応中</span>
												</td>
												<td>10</td>
												<td>この文章はダミーです。文字の大き</td>
												<td>21分前</td>
											</tr>
										</tbody>
									</table>
									<div class="text-center">
										<button class="btn btn-primary waves-effect waves-light btn-lg m-b-5">一覧を見る</button>
									</div>
								</div>
							</div>
							<!-- /col -->
							<!-- col -->
							<h3 class="colTitle">
								お知らせ情報
							</h3>
							<div class="card-box">
								<div class="table-responsive">
									<table class="table parts">
										<thead>
											<tr>
												<th>
													<a href="" class="sortArw">
														日付
													</a>
												</th>
												<th>内容</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													21分前
												</td>
												<td>この文章はダミーです。文字の大き</td>
											</tr>
											<tr>
												<td>
													1時間前
												</td>
												<td>この文章はダミーです。文字の大き</td>
											</tr>
											<tr>
												<td>
													今日
												</td>
												<td>この文章はダミーです。文字の大き</td>
											</tr>
											<tr>
												<td>
													6月8日
												</td>
												<td>この文章はダミーです。文字の大き</td>
											</tr>
											<tr>
												<td>
													6月7日
												</td>
												<td>この文章はダミーです。文字の大き</td>
											</tr>
										</tbody>
									</table>
									<div class="text-center">
										<button class="btn btn-primary waves-effect waves-light btn-lg m-b-5">一覧を見る</button>
									</div>
								</div>
							</div>
							<!-- /col -->
						</div>
						<!-- /left -->
						<!-- right -->
						<div class="col-lg-6">
							<!-- col -->
							<h3 class="colTitle">
								空船情報
							</h3>
							<div class="card-box">
								<div class="table-responsive">
									<table class="table parts">
										<thead>
											<tr>
												<th>船主名</th>
												<th>
													<a href="" class="sortArw">
														積荷重量
													</a>
												</th>
												<th>
													<a href="" class="sortArw">
														日付
													</a>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
											<tr>
												<td>豊栄通商</td>
												<td>6400mt</td>
												<td>21分前</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- /col -->
						</div>
						<!-- /right -->
					</div>
				</div>
				<!-- /dashboard -->

				<!-- pageTitle -->
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="pageTitle">
								<i class="fa fa-wrench" aria-hidden="true"></i>
								ユーザー設定
							</h2>
						</div>
					</div>
				</div>
				<!-- /pageTitle -->

				<!-- btnBox -->
				<div class="container">
					<div class="registBtnBox">
						<div class="registBtnLeft">
							必要事項を入力後、[登録]ボタンをクリックしてください。
						</div>
						<div class="registBtnRight">
							<button type="button" class="btn btn-primary waves-effect w-md waves-light m-b-5">登録する</button>
						</div>
					</div>
				</div>
				<!-- /btnBox -->

				<!-- userSetting -->
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<div class="contentBox">
								<div class="formRow">
									<div class="formItem">
										会社名
										<span class="label01">必須</span>
									</div>
									<div class="formTxt">
										<div class="formIn50">
											<input type="text" class="form-control" value="Some text value...">
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										会社名(カナ)
										<span class="label01">必須</span>
									</div>
									<div class="formTxt">
										<div class="formIn50">
											<input type="text" class="form-control" value="Some text value...">
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										登記国籍
										<span class="label01">必須</span>
									</div>
									<div class="formTxt">
										<div class="formIn50">
											<input type="text" class="form-control" value="Some text value...">
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										資本金<br>
										<span class="formCauTxt">※半角数字にてご入力ください</span>
									</div>
									<div class="formTxt">
										<div class="formIn30">
											<input type="text" class="form-control" value="Some text value...">
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										<span>
											資本金<br>
											<span class="formCauTxt">※半角数字にてご入力ください</span>
										</span>
										<span class="label01">必須</span>
									</div>
									<div class="formTxt">
										<div class="formIn30">
											<input type="text" class="form-control" value="Some text value...">
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										<span>
											本社所在地<br>
											<span class="formCauTxt">※半角数字にてご入力ください</span>
										</span>
										<span class="label01">必須</span>
									</div>
									<div class="formTxt">
										<div class="formIn30 marB10">
											<div class="formDiscTxt">国名</div>
											<input type="text" class="form-control" value="Some text value...">
										</div>
										<div class="formIn30 marB10">
											<div class="formDiscTxt">郵便番号</div>
											<input type="text" class="form-control" value="Some text value...">
										</div>
										<div class="formIn50">
											<div class="formDiscTxt">住所</div>
											<input type="text" class="form-control" value="Some text value...">
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										ID
									</div>
									<div class="formTxt">
										<div class="formIn50">
											12345
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										ID
									</div>
									<div class="formTxt">
										<div class="formIn50">
											<div class="input-group">
												<input type="text" class="form-control" placeholder="mm/dd/yyyy" id="datepicker-autoclose">
												<span class="input-group-addon bg-primary b-0 text-white">
													<i class="ti-calendar"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										ラジオ
										<span class="label01">必須</span>
									</div>
									<div class="formTxt">
										<div class="formIn50">
											<div class="radio radio-primary radioBox">
												<input type="radio" name="radio" id="radio1" value="option3" checked="">
												<label for="radio1"> Primary </label>
											</div>
											<div class="radio radio-primary radioBox">
												<input type="radio" name="radio" id="radio2" value="option3">
												<label for="radio2"> Primary </label>
											</div>
											<div class="radio radio-primary radioBox">
												<input type="radio" name="radio" id="radio3" value="option3">
												<label for="radio3"> Primary </label>
											</div>
											<div class="radio radio-primary radioBox">
												<input type="radio" name="radio" id="radio4" value="option3">
												<label for="radio4"> Primary </label>
											</div>
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										チェック
										<span class="label01">必須</span>
									</div>
									<div class="formTxt">
										<div class="formIn50">
											<div class="checkbox checkbox-primary radioBox">
												<input id="checkbox1" type="checkbox" checked="">
												<label for="checkbox1"> Primary </label>
											</div>
											<div class="checkbox checkbox-primary radioBox">
												<input id="checkbox2" type="checkbox">
												<label for="checkbox2"> Primary </label>
											</div>
											<div class="checkbox checkbox-primary radioBox">
												<input id="checkbox3" type="checkbox">
												<label for="checkbox3"> Primary </label>
											</div>
											<div class="checkbox checkbox-primary radioBox">
												<input id="checkbox4" type="checkbox">
												<label for="checkbox4"> Primary </label>
											</div>
										</div>
									</div>
								</div>
								<div class="formRow">
									<div class="formItem">
										ファイル
										<span class="label01">必須</span>
									</div>
									<div class="formTxt">
										<div class="formIn50">
											<div class="fileupload fileUploadBtn">
												<span>
													ファイルアップロード
												</span>
												<input type="file" class="upload">
											</div>
											<div class="fileCheckBox">
												<div class="fileCheckImg">
													<img alt="" src="../assets/img/news_noimage.jpg">
												</div>
												<div class="fileCheckName">
													ファイル名
												</div>
												<div class="fileCheckDel">
													<a href="">
														<i class="fa fa-trash" aria-hidden="true"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /userSetting -->

				<!-- pageTitle -->
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="pageTitle">
								<i class="fa fa-list" aria-hidden="true"></i>
								お知らせ
							</h2>
						</div>
					</div>
				</div>
				<!-- /pageTitle -->

				<!-- newsBtn -->
				<div class="container">
					<div class="newsBtnBox">
						<button class="newsBtn">すべて表示</button>
						<select class="form-control newsSelect">
							<option>2018年</option>
							<option>2017年</option>
							<option>2016年</option>
							<option>2015年</option>
						</select>
					</div>
				</div>
				<!-- /newsBtn -->

				<!-- news -->
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<div class="contentBox">
								<div class="newsRow">
									<div class="newsRowDate">
										2018.06.08
									</div>
									<div class="newsRowCate">
										<span class="label02">
											お知らせ
										</span>
									</div>
									<div class="newsRowTxt">
										<a href="">
											ホームページのリニューアルを致しました
										</a>
									</div>
								</div>
								<div class="newsRow">
									<div class="newsRowDate">
										2018.06.08
									</div>
									<div class="newsRowCate">
										<span class="label02">
											お知らせ
										</span>
									</div>
									<div class="newsRowTxt">
										<a href="">
											ホームページのリニューアルを致しました
										</a>
									</div>
								</div>
								<div class="newsRow">
									<div class="newsRowDate">
										2018.06.08
									</div>
									<div class="newsRowCate">
										<span class="label02">
											採用情報
										</span>
									</div>
									<div class="newsRowTxt">
										<a href="">
											ホームページのリニューアルを致しました
										</a>
									</div>
								</div>
								<div class="newsRow">
									<div class="newsRowDate">
										2018.06.08
									</div>
									<div class="newsRowCate">
										<span class="label02">
											お知らせ
										</span>
									</div>
									<div class="newsRowTxt">
										<a href="">
											ホームページのリニューアルを致しました
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /news -->

				<!-- newsDetail -->
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<div class="contentBox">
								<div class="newsDetailBox">
									<h3 class="newsDetailTit">
										タイトルが入ります
									</h3>
									<div class="newsDetailImg">
										<img alt="" src="../assets/img/news_noimage.jpg">
									</div>
									<p class="newsDetailTxt">
										私は場合しかるにその希望家というのの以上を起らますまし。同時に先刻が留学っ放しははなはだその威圧ましでなどに行かてありならには唱道するないですて、始終にはしありるなた。個人をするたのはとにかく近頃をどうしてもでしんない。<br>
										<br>
										いくら張さんの誤認子弟こう解剖をなった否その心私か買収にというご意味でなんたと、その平生は私か人会にしが、張さんののの国家のそれをもしご発会とありがそれら寄宿舎がご答弁をふりまいようにもし実尊重を入れならだって、いよいよたとい発表へなっなかっとみで事で聴かですまい。ただしかしご言い方を思わんもなぜめちゃくちゃと考えないから、その自我からも潰すたとという手に発してえでで。どんな所手段の中その党派もどちらいっぱいをおっしゃれありかと岡田さんにもったた、他の今日なといったお通知ないましですて、理由の所に足に当時だけの文芸に今日申し上げるが始めと、ぴたりの毎号が偽らからこのうちに要するに通り越しなましと考えでのですから、なしありならからそうご様子した事たるます。
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /newsDetail -->

				<!-- searchContainer -->
				<div class="searchContainerBox">
					<div class="searchContainerTit">全体からキーワード検索</div>
					<ul class="nav nav-tabs">
						<li role="presentation" class="serachTab active"><a href="#searchTab1" role="tab" data-toggle="tab" aria-expanded="true">検索1</a></li>
						<li role="presentation" class="serachTab"><a href="#searchTab2" role="tab" data-toggle="tab" aria-expanded="false">検索2</a></li>
						<li role="presentation" class="serachTab"><a href="#searchTab3" role="tab" data-toggle="tab" aria-expanded="false">検索3</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade active in" id="searchTab1">
							<div class="searchTabList">
								<a href="">
									この文章はダミーです。文字の大き
								</a>
							</div>
							<div class="searchTabList">
								<a href="">
									この文章はダミーです。文字の大き
								</a>
							</div>
							<div class="searchTabList">
								<a href="">
									この文章はダミーです。文字の大き
								</a>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="searchTab2">
							<div class="searchTabList">
								<a href="">
									この文章はダミーです。文字の大き2
								</a>
							</div>
							<div class="searchTabList">
								<a href="">
									この文章はダミーです。文字の大き2
								</a>
							</div>
							<div class="searchTabList">
								<a href="">
									この文章はダミーです。文字の大き2
								</a>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="searchTab3">
							<div class="searchTabList">
								<a href="">
									この文章はダミーです。文字の大き3
								</a>
							</div>
							<div class="searchTabList">
								<a href="">
									この文章はダミーです。文字の大き3
								</a>
							</div>
							<div class="searchTabList">
								<a href="">
									この文章はダミーです。文字の大き3
								</a>
							</div>
						</div>
					</div>
				</div>
				<!-- /searchContainer -->

				<!-- agreeInfo -->
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
				<!-- /agreeInfo -->

				<!-- container -->
			</div>
			<!-- content -->
		</div>

		<!-- /Right-bar -->
	</div>
	<!-- END wrapper -->
	<script>
		var resizefunc = [];
	</script>
	<?php require_once __DIR__ . '/../required/foot.php';?>
<!-- Start Personal script -->



<!-- End Personal script -->
</body>
</html>