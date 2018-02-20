<body>
<!-- Form tambah anggota -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Tambah Kategori</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
        			<?php 
        				if($this->session->flashdata('message')){
        					echo $this->session->flashdata('message');
        				}
        			?>
        			<form class="form-horizontal" action="<?php echo base_url('kategori/tambahkategori');?>" method="post">
        			<div class="form-group">
        				<label class="control-label col-md-3" for="kode-klasifikasi">Kode Klasifikasi DDC:</label>
        				<div class="col-md-9" >
        					<input class="form-control numdot" placeholder="Kode klasifikasi DDC (Dewey Decimal Classification)"  name="kode-klasifikasi" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-3" for="no-induk">Nama Kategori:</label>
        				<div class="col-md-9" >
        					<input class="form-control alphaspcomma" placeholder="Nama kategori"  name="nama-kategori" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<div class="col-md-2 col-md-offset-3">
        					<input type="hidden" value="submit" name="submit">
        					<button type="submit" id="submit" class="form-control btn-primary">Simpan</button>
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