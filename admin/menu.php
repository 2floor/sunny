<?php
session_start();
require_once __DIR__ . '/../required/view_common_include.php'; // viewでは必ずrequireすること(各種セキュリティチェック、他管理画面共通処理)
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
							<h2 class="pageTitle html_title_area">
								<i class="fa fa-list" aria-hidden="true"></i>
								管理項目一覧
							</h2>
						</div>
					</div>
				</div>
				<!-- /pageTitle -->
				<div class="container">
					<?php print $menu_html?>
				</div>
				<!-- container -->
			</div>
			<!-- content -->
		</div>

	</div>
	<!-- END wrapper -->
<?php require_once __DIR__ . '/../required/foot.php';?>
<script type="text/javascript">
$(document).ready(function() { $('.loading').addClass('is-hide')});
</script>

</body>
</html>