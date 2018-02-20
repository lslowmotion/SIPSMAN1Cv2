<link href="<?php echo base_url('assets/DataTables-1.10.12/css/dataTables.bootstrap.min.css')?>" rel="stylesheet"/>
<body>
<!-- Tabel akun -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Daftar Kategori</h1>
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
					<table class="table table-striped table-bordered table-hover" id="dataTables-kategori">
						<thead>
							<tr>
								<th width="16%">Kode Klasifikasi</th>
								<th>Nama Kategori</th>
								
								<th width="13%">Menu</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if (isset($daftar_kategori)){
							foreach ($daftar_kategori as $row){
						?>	
						
						
							<tr>
								<td><?php echo $row->kode_klasifikasi;?></td>
								<td><?php echo $row->nama_kategori;?></td>
								
								<td>
									<a href="<?php echo base_url('pustaka/index/'.$row->kode_klasifikasi);?>"><button type="button" class="btn btn-primary center-block"><i class="fa fa-search"></i> Cari Koleksi</button></a>
									
								</td>
							</tr>
						<?php 
							}
						}
						?>
						
						</tbody>
						<?php if($this->session->userdata('level')=='admin'){ ?>
						<tfoot>
        					<tr>
            					<td>
            					<a href="<?php echo base_url('kategori/tambahkategori');?>"><button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kategori</button></a>
            					</td>
            					<td></td>
            					<td></td>
            					
        					</tr>
    					</tfoot>
    					<?php }?>
					</table>
					
					</form>
					</div>
			</div>
			</div>
		</div>
	
</body>

<!-- jQuery -->


<script src="<?php echo base_url('assets/DataTables-1.10.12/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('assets/DataTables-1.10.12/js/dataTables.bootstrap.min.js');?>"></script>

<script>
$(document).ready( function () {
	$('#dataTables-kategori').DataTable({
		"lengthChange": false
	});
});
</script>

