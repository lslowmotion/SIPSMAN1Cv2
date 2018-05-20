<link href="<?php echo base_url('assets/DataTables-1.10.12/css/dataTables.bootstrap.min.css')?>" rel="stylesheet"/>
<body>
<!-- Tabel akun -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Status Peminjaman</h1>
				</div>
			</div>
			
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			<div class="row">
				<div class="col-md-12" style="overflow-x:auto;">
					
					<table class="table table-striped table-bordered table-hover" id="dataTables-peminjaman">
						<thead>
							<tr>
								<th width="17%">Kode Transaksi</th>
								<th width="10%">No Induk</th>
								<th>Tanggal Pinjam</th>
								<th>Tanggal Kembali</th>
								<th>Denda</th>
								<th width="18%">Menu</th>
								
							</tr>
						</thead>
						<tbody>
						
						</tbody>
						<tfoot>
        					<tr>
            					<td>
        						<?php if($this->session->userdata('level') == 'admin'){?>
            						<a href="<?php echo base_url('peminjaman/pinjam');?>"><button type="button" class="btn btn-primary"><i class="fa fa-exchange"></i> Peminjaman baru</button></a>
        						<?php }?>
        						<?php if(($this->session->userdata('level') == 'anggota')){?>
            						<button type="button" class="btn" data-toggle="modal" data-target="#suratModal">
            							<i class="fa fa-print"></i> Cetak surat bebas pinjam
        							</button>
        						<?php }?>
        						</td>
            					<td>
            					<?php if($this->session->userdata('level') == 'admin'){?>
                					<button type="button" class="btn" data-toggle="modal" data-target="#cetakModal">
                    					<i class="fa fa-print"></i> Cetak daftar peminjaman
                    				</button>
                				<?php }?>
                				</td>
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

<?php 
//jika admin, parse modal cetak daftar peminjaman
if ($this->session->userdata('level') == 'admin'){?>
<!-- Modal -->
<div class="modal fade" id="cetakModal" tabindex="-1" role="dialog" aria-labelledby="cetakModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="cetakModalLabel">Cetak Daftar Peminjaman</h4>
            </div>
            <div class="modal-body">
				<form class="form-horizontal" action="<?php echo base_url('peminjaman/cetakdaftarpeminjaman');?>" method="post" target="_blank">
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

<?php 
//jika anggota, parse modal cetak daftar peminjaman
if ($this->session->userdata('level') == 'anggota'){?>
<!-- Modal -->
<div class="modal fade" id="suratModal" tabindex="-1" role="dialog" aria-labelledby="suratLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="suratModalLabel">Cetak Surat Bebas Pinjam</h4>
      </div>
      <div class="modal-body">
      Apakah anda yakin ingin mencetak surat bebas pinjam?
      Keanggotaan anda akan diarsipkan, akses login akan ditutup, dan anda tidak akan dapat meminjam lagi.
      </div>
      <div class="modal-footer">
      <form action="<?php echo base_url('peminjaman/cetaksuratbebaspinjam'); ?>">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn"><i class="fa fa-print"></i> Cetak surat</button>
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
	$('#dataTables-peminjaman').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax":{
    	     "url": "<?php echo base_url('peminjaman/daftarpeminjaman/'.$this->uri->segment('3')); ?>",
    	     "dataType": "json",
    	     "type": "POST",
     	},

     	"columns": [
     		{"name": "kode-transaksi", "orderable": true},
     		{"name": "no-induk", "orderable": true},
     		{"name": "tanggal-pinjam", "orderable": true},
     		{"name": "tanggal-kembali", "orderable": true},
     		{"name": "denda", "orderable": false},
     		{"name": "menu", "orderable": false}
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

