<body>
<!-- Form tambah anggota -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Edit Kategori</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
        			<?php 
        				if($this->session->flashdata('message')){
        					echo $this->session->flashdata('message');
        				}
        			?>
        			<?php if (isset($data_kategori)){?>
        			<form class="form-horizontal" action="<?php echo base_url('kategori/editkategori/'.$data_kategori->kode_klasifikasi);?>" method="post">
        			<div class="form-group">
        				<label class="control-label col-md-2" for="kode-klasifikasi">Kode Klasifikasi:</label>
        				<div class="col-md-10" >
        					<input class="form-control numdot" value="<?php echo $data_kategori->kode_klasifikasi;?>" placeholder="Kode klasifikasi DDC (Dewey Decimal Classification)"  name="kode-klasifikasi" type="text" autocomplete="off" readonly/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="no-induk">Nama Kategori:</label>
        				<div class="col-md-10" >
        					<input class="form-control alphaspcomma" value="<?php echo $data_kategori->nama_kategori;?>" placeholder="Nama kategori"  name="nama-kategori" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<div class="col-md-2 col-md-offset-2">
        					<input type="hidden" value="submit" name="submit">
        					<button type="submit" id="submit" class="form-control btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        				</div>
        				<div class="col-md-2">
        					<button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#hapusModal" data-kode-klasifikasi="<?php echo $data_kategori->kode_klasifikasi?>" data-nama-kategori="<?php echo $data_kategori->nama_kategori?>"><i class="fa fa-trash"></i> Hapus</button>
        				</div>
        			</div>
        			</form>
        			<?php }?>
    			</div>
			</div>
		</div>
	</div>
</body>
<!-- Modal -->
<div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Hapus Data</h4>
      </div>
      <div class="modal-body">
      Apakah anda yakin ingin menghapus kategori <span class="kode-klasifikasi"></span> (<span class="nama-kategori"></span>)?

      </div>
      <div class="modal-footer">
      <form action="<?php echo base_url('kategori/hapuskategori'); ?>" method="post">
      	<input type="hidden" class="kode-klasifikasi" name="kode-klasifikasi"/>
      	<input type="hidden" class="url" name="url"/>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Hapus Data</button>
       </form>
      </div>
    </div>
  </div>
</div>
<!-- /.Modal -->
<script src="<?php echo base_url('assets/js/killnonalphanum.js');?>"></script>
<script>

$(document).ready( function () {
  	$('#hapusModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var kode_klasifikasi = button.data('kode-klasifikasi') // Extract info from data-* attributes
      var nama_kategori = button.data('nama-kategori')
      var url = button.data('url') 
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.nama-kategori').text(nama_kategori)
      modal.find('.kode-klasifikasi').text(kode_klasifikasi)
      modal.find('input','.kode-klasifikasi').val(kode_klasifikasi)
      modal.find('.url').val(url)
	});
});
</script>