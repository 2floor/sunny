<!DOCTYPE html>
<html>
<head>
        <?php require_once __DIR__ . '/../required/html_head.php';?>
    </head>
<body>
	<div class="account-pages"></div>
	<div class="clearfix"></div>
	<div class="wrapper-page">
		<div class="m-t-40 card-box">
			<div class="text-center">
				<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>
			</div>
			<div class="panel-body">
				<div class="form-horizontal m-t-20" >
					<div class="form-group ">
						<div class="col-xs-12">
							<input class="form-control" type="text" name="id" id="form_id" placeholder="Login ID">
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12">
							<input class="form-control" type="password" name="pw" id="form_pw"  placeholder="Password">
						</div>
					</div>
					<div class="form-group text-center m-t-30">
						<div class="col-xs-12">
							<button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" name="submit"  type="submit" id="form_submit" method="login">Log In</button>
							<p id="error_area"></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end wrapper page -->
	<?php require_once __DIR__ . '/../required/foot.php';?>
<!-- Start Personal script -->
<script type="text/javascript" src="../assets/admin/js/login.js"></script>

<!-- End Personal script -->
<!-- Start Personal Input -->
<input type="hidden" id="ct_url" value="../controller/admin/login_ct.php">
</body>
</html>