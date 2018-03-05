<link href="<?php echo base_url('assets/DataTables-1.10.12/css/dataTables.bootstrap.min.css')?>" rel="stylesheet"/>
<body>
<!-- Tabel akun -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Kelola Peminjaman</h1>
				</div>
			</div>
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			
			<div class="row">
			<div class="col-md-12">
					
					<table class="table table-striped table-bordered table-hover" id="dataTables-peminjaman">
						<thead>
							<tr>
								<th>Kode Transaksi</th>
								<th>No Induk</th>
								<th>Tanggal Pinjam</th>
								<th>Tanggal Kembali</th>
								<th>Denda</th>
								<th>Menu</th>
								
							</tr>
						</thead>
						<tbody>
						
						</tbody>
						<tfoot>
					<tr>
					<td>
					<a href="<?php echo base_url('anggota/tambahanggota');?>"><button type="button" class="btn btn-primary"><i class="fa fa-plus "></i> Tambah Anggota</button></a>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					</tr>
					</tfoot>
					</table>
					
					
					</div>
			</div>
			</div>
		</div>
	
</body>

<!-- /.Tabel akun -->
<!-- jQuery -->


<script src="<?php echo base_url('assets/DataTables-1.10.12/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('assets/DataTables-1.10.12/js/dataTables.bootstrap.min.js');?>"></script>

<script>


$(document).ready( function () {
	$('#dataTables-peminjaman').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax":{
    	     "url": "<?php echo base_url('peminjaman/daftarpeminjaman') ?>",
    	     "dataType": "json",
    	     "type": "POST",
     	},

     	"columns": [
     		{"name": "kode-transaksi", "orderable": true},
     		{"name": "no-induk", "orderable": true},
     		{"name": "tanggal-pinjam", "orderable": true},
     		{"name": "tanggal-kembali", "orderable": true},
     		{"name": "denda", "orderable": true},
     		{"name": "menu", "orderable": false}
 		],
 		"order": [[0, 'asc']],
	});
	
});
</script>

