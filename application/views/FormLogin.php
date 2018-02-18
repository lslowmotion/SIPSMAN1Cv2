<body>
<!-- Form login -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Login</h1>
				</div>
			</div>
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-info" >
				<div class="panel-heading ">Login untuk menggunakan Sistem Informasi Perpustakaan SMA Negeri 1 Cilacap</div>
				<div class="panel-body">
					<form method="post" action="<?php echo base_url('akun/login');?>" >
						<div class="form-group">
							<label class="control-label" for="id">Username:</label>
							<input class="form-control" placeholder="NIP/NIS"  name="id" type="text" />
						</div>
						<div class="form-group">
							<label class="control-label" for="password">Password:</label>
							<input class="form-control" placeholder="Password" name="password" type="password" />
						</div>
						<div class="form-group">
							<input type=hidden name="submit" value=TRUE>
							<button type="submit" id="submit" class="form-control btn-primary">Login</button>
						</div>
					</form>
				</div>
				</div>
			</div>
		</div>
		</div>
	</div>
<!-- Formlogin -->

</body>

<script>
  $('#submit').on('click', function () {
    $(this).button('loading')
  })
</script>