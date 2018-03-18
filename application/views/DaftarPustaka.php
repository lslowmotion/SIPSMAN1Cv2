<link href="<?php echo base_url('assets/DataTables-1.10.12/css/dataTables.bootstrap.min.css')?>" rel="stylesheet"/>
<body>
<!-- Tabel akun -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Koleksi Pustaka</h1>
				</div>
			</div>
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			
			<div class="row">
			<div class="col-md-12">
					
					<table class="table table-striped table-bordered table-hover" id="dataTables-pustaka">
						<thead>
							<tr>
								<th width="14%">Nomor Panggil</th>
								<th>Judul</th>
								<th>Pengarang</th>
								<th>Sampul</th>
								<th>Ketersediaan</th>
								<th>Menu</th>
							</tr>
						</thead>
						<tbody>
						
						</tbody>
						<?php if($this->session->userdata('level')=='admin'){ ?>
						<tfoot>
        					<tr>
            					<td>
            					<a href="<?php echo base_url('pustaka/tambahpustaka');?>"><button type="button" class="btn btn-primary"><i class="fa fa-plus "></i> Tambah Koleksi</button></a>
            					</td>
            					<td></td>
            					<td></td>
            					<td></td>
            					<td></td>
            					<td></td>
        					</tr>
    					</tfoot>
    					<?php }?>
					</table>
					
					
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
      Apakah anda yakin ingin menghapus pustaka <span class="judul"></span> (<span class="nomor-panggil"></span>)?
      Semua data peminjaman terkait pustaka yang bersangkutan juga akan terhapus 
      </div>
      <div class="modal-footer">
      <form action="<?php echo base_url('pustaka/hapuspustaka'); ?>" method="post">
      	<input type="hidden" class="nomor-panggil" name="nomor-panggil"/>
      	<input type="hidden" class="url" name="url"/>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Hapus Data</button>
       </form>
      </div>
    </div>
  </div>
</div>
<!-- /.Modal -->

<!-- Modal -->
<div class="modal fade" id="sampulModal" tabindex="-1" role="dialog" aria-labelledby="sampulModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sampulModalLabel"><span class="judul"></span></h4>
      </div>
      <div class="modal-body">
          <img class="sampul center-block" width="380" alt="Sampul">
      </div>
      
    </div>
  </div>
</div>
<!-- /.Modal -->
<!-- /.Tabel akun -->
<!-- jQuery -->


<script src="<?php echo base_url('assets/DataTables-1.10.12/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('assets/DataTables-1.10.12/js/dataTables.bootstrap.min.js');?>"></script>

<script>
$(document).ready( function () {
	$('#dataTables-pustaka').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax":{
    	     "url": "<?php echo base_url('pustaka/daftarpustaka/'.$this->uri->segment('3')); ?>",
    	     "dataType": "json",
    	     "type": "POST"
     	},

     	"columns": [
     		{"name": "nomor-panggil", "orderable": true},
     		{"name": "judul", "orderable": true},
     		{"name": "pengarang", "orderable": true},
     		{"name": "sampul", "orderable": false},
     		{"name": "ketersediaan", "orderable": false},
     		{"name": "menu", "orderable": false}
 		],
 		"order": [[0, 'asc']],
	});
	$('#hapusModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var nomor_panggil = button.data('nomor-panggil') // Extract info from data-* attributes
		  var judul = button.data('judul')
		  var url = button.data('url') 
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  var modal = $(this)
		  modal.find('.judul').text(judul)
		  modal.find('.nomor-panggil').text(nomor_panggil)
		  modal.find('input','.nomor-panggil').val(nomor_panggil)
		  modal.find('.url').val(url)
		});

	$('#sampulModal').on('show.bs.modal', function (event) {
		  var img = $(event.relatedTarget) // Button that triggered the modal
		  var sampul = img.data('sampul') // Extract info from data-* attributes
		  var judul = img.data('judul')
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  var modal = $(this)
		  modal.find('.judul').text(judul)
		  modal.find('.sampul').attr("src", sampul)
		  /* modal.find('.sampul').src(sampul)  */
		});  
});
</script>

