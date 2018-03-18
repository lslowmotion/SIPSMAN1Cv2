<body>
<!-- Tabel akun -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Data Peminjaman</h1>
				</div>
				
			</div>
			
			
			<div class="row">
				<div class="col-md-12">
        			<?php 
        				if($this->session->flashdata('message')){
        					echo $this->session->flashdata('message');
        				}
        			?>
        			<?php if (isset($data_anggota)){?>
        			<table class="table">
    					<tr>
    						<td class="col-md-3">Kode Transaksi</td>
    						<td class="col-md-1" align="right">:</td>
    						<td class="col-md-8"><?php echo $data_peminjaman->kode_transaksi;?></td>
    					</tr>
    					<tr>
    						<td>Pustaka Dipinjam</td>
    						<td align="right">:</td>
    						<td>
    							<a href="<?php echo base_url('pustaka/datapustaka/'.$data_pustaka->nomor_panggil);?>">
    						    	<?php echo $data_peminjaman->nomor_panggil.' ('.$data_pustaka->judul.')';?>
						    	</a>
					      	</td>
    					</tr>
    					<tr>
    						<td>Identitas Peminjam</td>
    						<td align="right">:</td>
    						<td><?php if($this->session->userdata('level') == 'admin'){?>
    							<a href="<?php echo base_url('anggota/dataanggota/'.$data_anggota->no_induk);?>">
    								<?php echo $data_peminjaman->no_induk.' ('.$data_anggota->nama.')';?>
								</a>
								<?php }else{
								    echo $data_peminjaman->no_induk.' ('.$data_anggota->nama.')';
								}?>
							</td>
    					</tr>
    					<tr>
    						<td>Tanggal Pinjam</td>
    						<td align="right">:</td>
    						<td><?php echo $data_peminjaman->tanggal_pinjam;?></td>
    					</tr>
    					<tr>
    						<td>Tanggal Kembali</td>
    						<td align="right">:</td>
    						<td><?php echo $data_peminjaman->tanggal_kembali;?></td>
    					</tr>
    					<tr>
    						<td>Denda</td>
    						<td align="right">:</td>
    						<td><?php echo $data_peminjaman->denda;?></td>
    					</tr>
    					<tr>
    						<td>
    							<a href="<?php echo base_url('peminjaman');?>">
    								<button class="form-control btn-danger">
    									<i class="fa fa-arrow-left"></i> Kembali ke Status Peminjaman
    								</button>
    							</a>
    						</td>
    						<td></td>
    						<td>
    						<?php if ($data_peminjaman->tanggal_kembali == 'Belum dikembalikan' && $this->session->userdata('level') == 'admin'){?>
    							<button class="form-control btn-primary" data-toggle="modal" data-target="#kembalikanModal"
    								data-kode-transaksi="<?php echo $data_peminjaman->kode_transaksi;?>"
    								data-tanggal-pinjam="<?php echo $data_peminjaman->tanggal_pinjam;?>"
    								data-tanggal-kembali="
    								<?php
        								$now = date('d M Y');
    								    echo $now;
    								?>"
    								data-denda="<?php echo $data_peminjaman->denda;?>"
    								>
									<i class="fa fa-exchange"></i> Kembalikan/Selesai pinjam
								</button>
							<?php }?>
							</td>
    					</tr>
    				</table>
    				<?php }?>
				</div>
			</div>
			
		
			
		</div>
	</div>
<!-- /.Tabel akun -->
<!-- jQuery -->


</body>
<?php if ($data_peminjaman->tanggal_kembali == 'Belum dikembalikan' && $this->session->userdata('level') == 'admin'){?>
<!-- Modal -->
<div class="modal fade" id="kembalikanModal" tabindex="-1" role="dialog" aria-labelledby="kembalikanModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="kembalikanModalLabel">Konfirmasi Transaksi Pengembalian Pustaka</h4>
            </div>
            <div class="modal-body">
            	
                    <table class="table">
                        <tr>
                        	<td>Kode transaksi</td>
                        	<td>:</td>
                        	<td><span class="kode-transaksi"></span></td>
                    	</tr>
                    	<tr>
                    		<td>Tanggal pinjam</td>
                    		<td>:</td>
                    		<td><span class="tanggal-pinjam"></span></td>
                        </tr>
                        <tr>
                        	<td>Tanggal kembali</td>
                        	<td>:</td>
                        	<td><span class="tanggal-kembali"></span></td>
                        </tr>
                        <tr>
                        	<td>Nominal denda</td>
                        	<td>:</td>
                        	<td><span class="denda"></span></td>
                        </tr>
                    </table>
                
                
                	Pengembalian dengan data peminjaman yang bersangkutan akan diproses. Apakah anda yakin ingin memproses transaksi?
                
            </div>
            <div class="modal-footer">
                <form action="<?php echo base_url('peminjaman/kembali'); ?>" method="post">
                    <input type="hidden" class="kode-transaksi" name="kode-transaksi"/>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-exchange"></i> Proses transaksi pengembalian</button>
                </form>
            </div>
        </div>
	</div>
</div>
<!-- /.Modal -->
<script>
$(document).ready( function () {
	$('#kembalikanModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var kode_transaksi = button.data('kode-transaksi') // Extract info from data-* attributes
        var tanggal_pinjam = button.data('tanggal-pinjam')
        var tanggal_kembali = button.data('tanggal-kembali')
        var denda = button.data('denda')
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.kode-transaksi').text(kode_transaksi)
        modal.find('.tanggal-pinjam').text(tanggal_pinjam)
        modal.find('.tanggal-kembali').text(tanggal_kembali)
        modal.find('.denda').text(denda)
        modal.find('input','.kode-transaksi').val(kode_transaksi)
	});
});
</script>
<?php }?>