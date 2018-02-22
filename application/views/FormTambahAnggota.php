<body>
<!-- Form tambah anggota -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Tambah Anggota</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
        			<?php 
        				if($this->session->flashdata('message')){
        					echo $this->session->flashdata('message');
        				}
        			?>
        			<form class="form-horizontal" action="<?php echo base_url('anggota/tambahanggota');?>" method="post">
        			<div class="form-group">
        				<label class="control-label col-md-2" for="nama">Nama:</label>
        				<div class="col-md-10" >
        					<input class="form-control nama" placeholder="Nama"  name="nama" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="no-induk">No Induk:</label>
        				<div class="col-md-10" >
        					<input class="form-control num" placeholder="No Induk"  name="no-induk" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="alamat">Alamat:</label>
        				<div class="col-md-10" >
        					<input class="form-control alphanumspsy" placeholder="Alamat"  name="alamat" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="email">Email:</label>
        				<div class="col-md-10" >
        					<input class="form-control" placeholder="Email"  name="email" type="email" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="no-telepon">No Telepon:</label>
        				<div class="col-md-10" >
        					<input class="form-control num" placeholder="No Telepon"  name="no-telepon" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<div class="col-md-2 col-md-offset-2">
        					<input type="hidden" value="submit" name="submit">
        					<button type="submit" id="submit" class="form-control btn-primary"><i class="fa fa-plus"></i> Simpan</button>
        				</div>
        			</div>
        			</form>
    			</div>
			</div>
		</div>
	</div>
</body>
<script src="<?php echo base_url('assets/js/killnonalphanum.js');?>"></script>
<script>
/* $('#submit').on('click', function () {
    $(this).button('loading')
   
  }) */
</script>