<body>
<!-- Form tambah anggota -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Pengaturan Peminjaman</h1>
				</div>
			</div>
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			<?php if (isset($data_aturan)){?>
			<form class="form-horizontal" action="<?php echo base_url('peminjaman/pengaturanpeminjaman');?>" method="post">
    			<div class="form-group">
    				<label class="control-label col-md-3" for="denda">Nominal Denda:</label>
    				<div class="col-md-9">
    					<div class="input-group">
        					<input value="<?php echo $data_aturan->denda;?>" class="form-control num" placeholder="Nominal denda dalam rupiah (Rp)"  name="denda" type="text" />
        					<span class="input-group-addon">rupiah</span>
    					</div>
    				</div>
    			</div>
    			<div class="form-group">
    				<label class="control-label col-md-3" for="durasi">Maksimal Durasi Peminjaman:</label>
    				<div class="col-md-9">
        				<div class="input-group" >
        					<input value="<?php echo $data_aturan->durasi;?>" class="form-control num" placeholder="Durasi maksimal peminjaman dalam hari"  name="durasi" type="text"/>
        					<span class="input-group-addon">hari</span>
        				</div>
    				</div>
    			</div>
    			<div class="form-group">
    				<label class="control-label col-md-3" for="maksimal-pinjam">Maksimal Peminjaman Koleksi:</label>
    				<div class="col-md-9">
        				<div class="input-group">
        					<input value="<?php echo $data_aturan->maksimal_pinjam;?>" class="form-control num" placeholder="Jumlah maksimal peminjaman koleksi pustaka"  name="maksimal-pinjam" type="text"/>
        					<span class="input-group-addon">eksemplar</span>
    					</div>
    				</div>
    			</div>
    			<div class="form-group">
    				<div class="col-md-2 col-md-offset-3">
    					<input type="hidden" value="submit" name="submit">
    					<button type="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
    				</div>
    			</div>
			</form>
			<?php }?>
		</div>
	</div>
</body>
<script src="<?php echo base_url('assets/js/killnonalphanum.js');?>"></script>