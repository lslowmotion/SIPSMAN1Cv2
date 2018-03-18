<body>
<!-- Tabel akun -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Data Pustaka</h1>
				</div>
				
			</div>
			
			
			<div class="row">
				<div class="col-md-12">
        			<?php 
        				if($this->session->flashdata('message')){
        					echo $this->session->flashdata('message');
        				}
        			?>
        			<?php if (isset($data_pustaka)){?>
        			<table class="table">
    					<tr>
    						<td class="col-md-3">Nomor Panggil</td>
    						<td class="col-md-1" align="right">:</td>
    						<td class="col-md-8"><?php echo $data_pustaka->nomor_panggil;?></td>
    					</tr>
    					<tr>
    						<td>ISBN</td>
    						<td align="right">:</td>
    						<td><?php echo $data_pustaka->isbn;?></td>
    					</tr>
    					<tr>
    						<td>Kategori</td>
    						<td align="right">:</td>
    						<td><?php echo $data_pustaka->kode_klasifikasi;?>: <?php echo $data_kategori->nama_kategori;?></td>
    					</tr>
    					<tr>
    						<td>Judul Pustaka</td>
    						<td align="right">:</td>
    						<td><?php echo $data_pustaka->judul;?></td>
    					</tr>
    					<tr>
    						<td>Nama Pengarang</td>
    						<td align="right">:</td>
    						<td><?php echo $data_pustaka->pengarang;?></td>
    					</tr>
    					<tr>
    						<td>Penerbit</td>
    						<td align="right">:</td>
    						<td><?php echo $data_pustaka->penerbit;?></td>
    					</tr>
    					<tr>
    						<td>Kota Terbit</td>
    						<td align="right">:</td>
    						<td><?php echo $data_pustaka->kota_terbit;?></td>
    					</tr>
    					<tr>
    						<td>Tahun Terbit</td>
    						<td align="right">:</td>
    						<td><?php echo $data_pustaka->tahun_terbit;?></td>
    					</tr>
    					<tr>
    						<td>Gambar Sampul</td>
    						<td align="right">:</td>
    						<td>
    							<a href="#">
        							<img data-toggle="modal" data-target="#sampulModal" data-sampul="
    							    <?php echo base_url($data_pustaka->sampul);?>
                                    " data-judul="<?php echo $data_pustaka->judul;?>" src="
                                    <?php echo base_url($data_pustaka->sampul);?>
                                    " alt="<?php echo $data_pustaka->judul;?>" style="width:80px;">
                                </a>
                            </td>
    					</tr>
    					<tr>
    						<td>Jumlah Koleksi Pustaka</td>
    						<td align="right">:</td>
    						<td><?php echo $data_pustaka->jumlah_pustaka;?> eksemplar</td>
    					</tr>
    					<tr>
    						<td>Koleksi Dipinjam</td>
    						<td align="right">:</td>
    						<td><?php echo $data_pustaka->jumlah_dipinjam;?> eksemplar</td>
    					</tr>
    					<tr>
    						<td>
    							<a href="<?php echo base_url('pustaka');?>">
    								<button class="form-control btn-danger">
    									<i class="fa fa-arrow-left"></i> Kembali ke Daftar Koleksi
    								</button>
    							</a>
    						</td>
    						<td></td>
    						<td><?php if($this->session->userdata('level') == 'admin'){?>
    							<a href="<?php echo base_url('pustaka/editpustaka/'.$data_pustaka->nomor_panggil);?>">
    								<button class="form-control btn-primary">
    									<i class="fa fa-edit"></i> Edit Data
    								</button>
    							</a><?php }?>
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
<script>
$(document).ready( function () {
	
	$('#sampulModal').on('show.bs.modal', function (event) {
		  var img = $(event.relatedTarget) // Button that triggered the modal
		  var sampul = img.data('sampul') // Extract info from data-* attributes
		  var judul = img.data('judul')
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  var modal = $(this)
		  modal.find('.judul').text(judul)
		  modal.find('.sampul').attr("src", sampul)
		  
		});  
});
</script>