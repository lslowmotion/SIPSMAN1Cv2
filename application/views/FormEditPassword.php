<body>

<!-- Formlogin -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Ubah Password</h1>
				</div>
			</div>
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			<div class="row">
				<div class="col-md-12">
					<form method="post" class="form-horizontal" action="<?php echo base_url('akun/editpassword');?>" >
						<div class="form-group">
							<label class="control-label col-md-3" for="password-lama">Password Lama:</label>
							<div class="col-md-9">
								<input class="form-control" placeholder="Password lama"  name="password-lama" type="password" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3" for="password-baru">Password Baru:</label>
							<div class="col-md-9">
								<input class="form-control" placeholder="Password baru" name="password-baru" type="password" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3" for="konfirmasi-password-baru">Konfirmasi Password Baru:</label>
							<div class="col-md-9">
								<input class="form-control" placeholder="Konfimasi password baru" name="konfirmasi-password-baru" type="password" />
							</div>
						</div>
						<div class="form-group">
							<input type=hidden name="submit" value=TRUE>
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
							</div>
						</div>
					</form>	
				</div>
			</div>
		</div>
	</div>
<!-- Formlogin -->

</body>