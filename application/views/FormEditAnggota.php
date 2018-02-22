<body>
<!-- Form tambah anggota -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Edit Anggota</h1>
				</div>
			</div>
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			<?php if (isset($data_anggota)){?>
			<form class="form-horizontal" action="<?php echo base_url('anggota/editanggota/'.$data_anggota->no_induk);?>" method="post">
			<div class="form-group">
				<label class="control-label col-md-2" for="nama">Nama:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_anggota->nama;?>" class="form-control nama" placeholder="Nama"  name="nama" type="text" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="no-induk">No Induk:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_anggota->no_induk;?>" class="form-control num" placeholder="No Induk"  name="no-induk" type="text" readonly />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="alamat">Alamat:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_anggota->alamat;?>" class="form-control alphanumspsy" placeholder="Alamat"  name="alamat" type="text" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="email">Email:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_anggota->email;?>" class="form-control" placeholder="Email"  name="email" type="email" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="no-telepon">No Telepon:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_anggota->telepon;?>" class="form-control num" placeholder="No Telepon"  name="no-telepon" type="text" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2 col-md-offset-2">
					<input type="hidden" value="submit" name="submit">
					<button type="submit" id="submit" class="form-control btn-primary">Simpan</button>
				</div>
			</div>
			</form>
			<?php }?>
		</div>
	</div>
</body>
<script src="<?php echo base_url('assets/js/killnonalphanum.js');?>"></script>
<script>
/* $('#submit').on('click', function () {
    $(this).button('loading')
   
  }) */
</script>