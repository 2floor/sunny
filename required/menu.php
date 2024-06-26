<?php
require_once __DIR__ . '/../logic/common/common_logic.php';
$common_logic = new common_logic();
$result_dealer = $common_logic->select_logic_no_param ( "select * from t_admin_menu where admin_menu_class_level = '0'" );

$authority_list = explode(',', $_SESSION ['adminer']['user_datas']['authority']);

$where = "";
for ($i = 0; $i < count($authority_list); $i++) {
	if ($i == 0) {
		$where = " (";
	}
	$where .= " admin_menu_id = '".$authority_list[$i] . "' or ";
}

if ($where != "") {
	$where = substr($where, 0, -3) . ")";
}

$contents_menu_html = "";
$menu_html = "";
$side_menu_html = '';
for($i = 0; $i < count ( $result_dealer ); $i ++) {
	$row_dealer = $result_dealer [$i];
	$result_child = $common_logic->select_logic_no_param ( "select * from t_admin_menu where admin_menu_class_level = '1' and admin_dealer_id = '".$row_dealer['admin_menu_id']."' and " . $where );

	if (count ( $result_child ) != 0) {
		$counter = $i + 1;

		$side_menu_html .= '
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect">
						<i class="' . $row_dealer ['admin_menu_icon'] . '" aria-hidden="true"></i>
						<span>'. $row_dealer ['admin_menu_name'] .'</span>
						<span class="menu-arrow"></span>
					</a>
					<ul class="list-unstyled">';

		$menu_html .= '<div class="row">
						<div class="col-md-12">
							<div class="card-box">
								<h1>'. $row_dealer ['admin_menu_name'] .'</h1>';

		for($n = 0; $n < count ( $result_child ); $n ++) {
			$row_child = $result_child [$n];
			$side_menu_html .= '<li><a href="'. MEDICALNET_ADMIN_PATH . $row_child ['admin_menu_link'] . '">'. $row_child ['admin_menu_name'] .'</a></li>';
			$menu_html .= '<blockquote>
								<p><a href="'. MEDICALNET_ADMIN_PATH . $row_child ['admin_menu_link'] . '">'. $row_child ['admin_menu_name'] .'</a></p>
								<footer>
									'.$row_child ['admin_menu_comment'].'
								</footer>
							</blockquote>';
		}

		$side_menu_html .= "
					</ul>
				</li>";
		$menu_html .= "</div>
					</div>
				</div>";
	}
}

$hello = 'おはようございます';
$now = (int)date('H');
if(12 <= $now && $now < 17){
	$hello = 'こんにちは';
}elseif(17 <= $now || $now < 6 ){
	$hello = 'こんばんは';
}

$html = '
		<!-- Top Bar Start -->
		<div class="topbar">
			<!-- Button mobile view to collapse sidebar menu -->
			<div class="navbar navbar-default" role="navigation">
				<div class="container">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<!-- Notification -->
							<div class="notification-box">
								<ul class="list-inline m-b-0">
									<li><a href="./logout.php" title="logout">
											<i class="fa fa-sign-out"></i>
										</a>
									</li>
								</ul>
							</div> <!-- End Notification bar -->
						</li>
					</ul>
					<!-- Page title -->
					<ul class="nav navbar-nav navbar-left">
						<li>
							<button class="button-menu-mobile open-left">
								<i class="zmdi zmdi-menu"></i>
							</button>
						</li>
						<li>
							<h4 class="page-title"></h4>
						</li>
					</ul>
					<!-- Right(Notification and Searchbox -->
				</div>
				<!-- end container -->
			</div>
			<!-- end navbar -->
		</div>
		<!-- Top Bar End -->
		<!-- ========== Left Sidebar Start ========== -->
		<div class="left side-menu">
			<div class="sidebar-inner slimscrollleft">
				<!-- User -->
				<div class="user-box">
					<div class="leftUserBox">
						<div class="leftUserImg">
							<img alt="" src="../assets/admin/img/adminer.png">
						</div>
						<div class="leftUserName hidden-sm">
							'.$hello.'、
							<span>'.$l_name.' さん</span>
						</div>
					</div>
				</div>
				<!-- End User -->
				<!--- Sidemenu -->
				<div id="sidebar-menu">
					<ul>
						'.$side_menu_html.'
					</ul>
					<div class="clearfix"></div>
				</div>
				<!-- Sidebar -->
				<div class="clearfix"></div>
			</div>
		</div>
		<!-- Left Sidebar End -->';

echo $html;