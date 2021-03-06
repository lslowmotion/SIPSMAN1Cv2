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
						<?php if($this->session->userdata('level') == 'admin'){?>
						<tfoot>
        					<tr>
            					<td>
            						<button type="button" class="btn" data-toggle="modal" data-target="#cetakModal">
                    					<i class="fa fa-print"></i> Cetak daftar kunjungan
                    				</button>
        						</td>
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

<!-- /.Tabel akun -->

<?php 
//jika admin, parse modal
if ($this->session->userdata('level') == 'admin'){?>
<!-- Modal -->
<div class="modal fade" id="cetakModal" tabindex="-1" role="dialog" aria-labelledby="cetakModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="cetakModalLabel">Cetak Daftar Kunjungan</h4>
            </div>
            <div class="modal-body">
				<form class="form-horizontal" action="<?php echo base_url('kunjungan/cetakdaftarkunjungan');?>" method="post" target="_blank">
        			<div class="form-group">
        				<label class="control-label col-md-2" for="bulan">Bulan:</label>
        				<div class="col-md-10" >
        					<select name="bulan" id="bulan" class="form-control">
                				<option selected value="">Semua bulan</option>
                				<option value="Jan">Januari</option>
                				<option value="Feb">Februari</option>
                				<option value="Mar">Maret</option>
                				<option value="Apr">April</option>
                				<option value="May">Mei</option>
                				<option value="Jun">Juni</option>
                				<option value="Jul">Juli</option>
                				<option value="Aug">Agustus</option>
                				<option value="Sep">September</option>
                				<option value="Oct">Oktober</option>
                				<option value="Nov">November</option>
                				<option value="Dec">Desember</option>
                			</select>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="tahun">Tahun:</label>
        				<div class="col-md-10" >
        					<input class="form-control num" placeholder="Ketik tahun atau kosongkan untuk cetak semua tahun"  name="tahun" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<div class="col-md-5 col-md-offset-2">
        					<input type="hidden" value="submit" name="submit">
        					<button type="submit" id="submit" class="btn"><i class="fa fa-print"></i> Cetak</button>
        				</div>
        			</div>	
            	</form>		
            </div>
        </div>
	</div>
</div>
<!-- /.Modal -->
<?php }?>
<!-- jQuery -->


<script src="<?php echo base_url('assets/DataTables-1.10.12/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('assets/DataTables-1.10.12/js/dataTables.bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/killnonalphanum.js');?>"></script>
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

	<?php 
	//jika admin, parse script modal
	if ($this->session->userdata('level') == 'admin'){?>
	$('#cetakModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        //var modal = $(this)
	});
	<?php }?>
	
});
</script>

