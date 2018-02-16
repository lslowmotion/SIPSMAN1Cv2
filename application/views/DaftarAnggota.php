<body>
<link href="<?php echo base_url('assets/DataTables-1.10.12/css/dataTables.bootstrap.min.css')?>" rel="stylesheet"/>
<!-- Tabel akun -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Kelola Anggota</h1>
				</div>
			</div>
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			
			<div class="row">
			<div class="col-md-12">
					<form>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>No Induk</th>
								<th>Nama</th>
								<th>Alamat</th>
								<th>Menu</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if (isset($daftar_anggota)){
							foreach ($daftar_anggota as $row){
						?>	
						
						
							<tr>
								<td><?php echo $row->no_induk;?></td>
								<td><?php echo $row->nama;?></td>
								<td><?php echo $row->alamat;?></td>
								<td>
									<a href="<?php echo base_url('Anggota/dataAnggota/'.$row->no_induk);?>"><button type="button" class="btn btn-primary">Detail</button></a>
									
									<!-- Button trigger modal -->
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapusModal" data-no-induk="<?php echo $row->no_induk; ?>" data-nama="<?php echo $row->nama; ?>" data-url="<?php echo current_url();?>">Hapus</button>
									<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#resetModal" data-no-induk="<?php echo $row->no_induk; ?>" data-nama="<?php echo $row->nama; ?>" data-url="<?php echo current_url();?>">Reset Password</button>
									
								</td>
							</tr>
						<?php 
							}
						}
						?>
						
						</tbody>
						<tfoot>
					<tr>
					<td>
					<a href="<?php echo base_url('Anggota/tambahAnggota');?>"><button type="button" class="btn btn-primary"><i class="fa fa-plus "></i> Tambah Anggota</button></a>
					</td>
					<td></td>
					<td></td>
					<td></td>
					</tr>
					</tfoot>
					</table>
					
					</form>
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
      Apakah anda yakin ingin menghapus data <span class="nama"></span> (<span class="no-induk"></span>)?
      Semua data yang berhubungan dengan akun yang bersangkutan juga akan dihapus 
      </div>
      <div class="modal-footer">
      <form action="<?php echo base_url('Anggota/hapusAnggota'); ?>" method="post">
      	<input type="hidden" class="no-induk" name="no-induk"/>
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
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reset Password</h4>
      </div>
      <div class="modal-body">
      Apakah anda yakin ingin mereset password?
      Password yang direset akan disamakan dengan no induk. Segera ganti password untuk keamanan akun!  
      </div>
      <div class="modal-footer">
      <form action="<?php echo base_url('Akun/resetPassword'); ?>" method="post">
      	<input type="hidden" class="no-induk" name="id"/>
      	<input type="hidden" class="url" name="url"/>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-warning">Reset Password</button>
      </form>
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
	$('#dataTables-example').DataTable({
		"lengthChange": false
	});
	 $('#hapusModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var no_induk = button.data('no-induk') // Extract info from data-* attributes
		  var nama = button.data('nama')
		  var url = button.data('url') 
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  var modal = $(this)
		  modal.find('.nama').text(nama)
		  modal.find('.no-induk').text(no_induk)
		  modal.find('input','.no-induk').val(no_induk)
		  modal.find('.url').val(url)
		});
	$('#resetModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var no_induk = button.data('no-induk') // Extract info from data-* attributes
		  var nama = button.data('nama')
		  var url = button.data('url') 
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  var modal = $(this)
		  modal.find('.nama').text(nama)
		  modal.find('.no-induk').text(no_induk)
		  modal.find('input','.no-induk').val(no_induk)
		  modal.find('.url').val(url)
		}); 
});
</script>

