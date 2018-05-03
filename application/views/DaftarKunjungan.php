<link href="<?php echo base_url('assets/DataTables-1.10.12/css/dataTables.bootstrap.min.css')?>" rel="stylesheet"/>
<body>
<!-- Tabel akun -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Riwayat Kunjungan</h1>
				</div>
			</div>
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			
			<div class="row">
				<div class="col-md-12" style="overflow-x:auto;">
					<table class="table table-striped table-bordered table-hover" id="dataTables-kunjungan">
						<thead>
							<tr>
								<th width="16%">ID Kunjungan</th>
								<th>No Induk</th>
								<th>Tanggal Kunjungan</th>
								<?php if($this->session->userdata('level') == 'admin'){?>
								<th width="17%">Menu</th>
								<?php }?>
							</tr>
						</thead>
						<tbody>
						
						</tbody>
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
	$('#dataTables-kunjungan').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax":{
    	     "url": "<?php echo base_url('kunjungan/daftarkunjungan/'.$this->uri->segment('3')); ?>",
    	     "dataType": "json",
    	     "type": "POST",
     	},

     	"columns": [
     		{"name": "id-kunjungan", "orderable": true},
     		{"name": "no-induk", "orderable": true},
     		{"name": "tanggal-kunjungan", "orderable": true},
     		<?php if($this->session->userdata('level') == 'admin'){?>
     		{"name": "menu", "orderable": false}
     		<?php }?>
 		],
 		"order": [[0, 'desc']],
	});
	
});
</script>

