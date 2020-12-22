<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>OIG-PIMIS</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url('assets/admin_lte/plugins/fontawesome-free/css/all.min.css') ?>">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="<?= base_url('assets/admin_lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url('assets/admin_lte/dist/css/adminlte.min.css') ?>">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<a href="<?= site_url('welcome') ?>">
				<b>OIG-PIMIS</b>
			</a>
		</div>
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg">
					<img src="<?= base_url('assets/images/logo.png'); ?>" width="125px" alt="สจร.ทหาร">
					<div class="text-center text-sm">ระบบสารสนเทศเพื่อการบริหารผลการตรวจการปฏิบัติราชการ</div>
					<div class="text-center text-sm">Performance Inspection Management Information System</div>
				</p>

				<form id="login-form">
					<div class="input-group mb-3">
						<input type="text" class="form-control" name="email" placeholder="RTARF Mail">
						<div class="input-group-append">
							<span class="input-group-text" style="background-color:#f1f1f1;">@rtarf.mi.th</span>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" class="form-control" name="password" placeholder="Password">
						<div class="input-group-append">
							<div class="input-group-text" style="background-color:#f1f1f1;">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-4">
							<button type="submit" id="submit-login-form" class="btn btn-primary btn-block">Sign In</button>
						</div>

					</div>
					<div class="form-group">
						Result: <span id="result-login-form"></span>
					</div>
				</form>

				<p class="mt-3">
					<div class="text-center">สำนักงานจเรทหาร กองบัญชาการกองทัพไทย</div>
				</p>
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="<?= base_url('assets/admin_lte/plugins/jquery/jquery.min.js') ?>"></script>
	<!-- Bootstrap 4 -->
	<script src="<?= base_url('assets/admin_lte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url('assets/admin_lte/dist/js/adminlte.min.js') ?>"></script>

	<script>
		$(document).ready(function() {
			console.log('ok');
			$("#login-form").submit(function(event) {
				event.preventDefault();
				$("#submit-login-form").prop('disabled', true);
				$("#result-login-form").text('Loading...');

				let formData = $(this).serialize();
				$.post({
					url: '<?= site_url('welcome/ajax_adlogin_process') ?>',
					data: formData,
					dataType: 'json'
				}).done(res => {
					if (res.status) {
						$("#result-login-form").text(res.data.nameth);
						$("#result-login-form").prop('class', 'text-success');
						setTimeout(() => {
							let userTypes = res.data.usertype;
							let index = 0
							while (index < userTypes.length) {
								console.log(userTypes[index]);
								if (userTypes[index].TYPE_NAME == 'admin') {
									window.location.replace('<?= site_url('admin/index') ?>');
									break;
								} else if (userTypes[index].TYPE_NAME == 'control') {
									window.location.replace('<?= site_url('controller_user/index') ?>');
									break;
								} else if (userTypes[index].TYPE_NAME == 'auditor') {
									window.location.replace('<?= site_url('auditor/index') ?>');
									break;
								} else if (userTypes[index].TYPE_NAME == 'user') {
									window.location.replace('<?= site_url('user/index') ?>');
									break;
								} else if (userTypes[index].TYPE_NAME == 'viewer') {
									window.location.replace('<?= site_url('viewer/index') ?>');
									break;
								} else {
									window.location.replace('<?= site_url('welcome/login') ?>');
									break;
								}
								index++;
							}
						}, 1500);
					} else {
						$("#result-login-form").text(res.text);
						$("#result-login-form").prop('class', 'text-danger');
						$("#submit-login-form").prop('disabled', false);
					}

				}).fail((jhr, status, error) => console.error(jhr, status, error));
			});
		});
	</script>

</body>

</html>