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
					<form method="post" class="col-md-4" action="<?php echo base_url('akun/editpassword');?>" >
						<div class="form-group">
							<label>Password Lama</label>
							<div class="input-group col-md-12">
								<input class="form-control" placeholder="Password lama"  name="password-lama" type="password" />
							</div>
						</div>
						<div class="form-group">
							<label>Password Baru</label>
							<div class="input-group col-md-12">
								<input class="form-control" placeholder="Password baru" name="password-baru" type="password" />
							</div>
						</div>
						<div class="form-group">
							<label>Konfirmasi Password Baru</label>
							<div class="input-group col-md-12">
								<input class="form-control" placeholder="Konfimasi password baru" name="konfirmasi-password-baru" type="password" />
							</div>
						</div>
						<div class="form-group">
							<input type=hidden name="submit" value=TRUE>
							<button type="submit" class="form-control btn-primary col-md-6">Ganti Password</button>
						</div>
					</form>	
			</div>
		</div>
	</div>
<!-- Formlogin -->

</body>
