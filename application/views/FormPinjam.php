<body>
<!-- Form tambah anggota -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Peminjaman Baru</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
        			<?php 
        				if($this->session->flashdata('message')){
        					echo $this->session->flashdata('message');
        				}
        			?>
        			<form class="form-horizontal" action="<?php echo base_url('peminjaman/pinjam');?>" method="post">
        			<div class="form-group">
        				<label class="control-label col-md-3" for="nomor-panggil">Nomor Panggil Pustaka:</label>
        				<div class="col-md-9" >
        					<input list="nomor-panggil" name="nomor-panggil" autocomplete="off" class="form-control" placeholder="Cari nomor panggil/judul pustaka" autofocus />
        					<datalist id="nomor-panggil">
        						<?php 
        						foreach ($daftar_pustaka as $row){
        						    if($row->jumlah_pustaka - $row->jumlah_dipinjam > 0){
    						          echo '<option value="'.$row->nomor_panggil.'">'.$row->judul.'</option>';
        						    }
        						}
        						?>
        					</datalist>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-3" for="no-induk">No Induk Peminjam:</label>
        				<div class="col-md-9" >
        					<input list="no-induk" name="no-induk" autocomplete="off" class="form-control" placeholder="Cari no induk/nama anggota perpustakaan" />
        					<datalist id="no-induk">
        						<?php 
        						foreach ($daftar_anggota as $row){
        						    echo '<option value="'.$row->no_induk.'">'.$row->nama.'</option>';
        						}
        						?>
        					</datalist>
        				</div>
        			</div>
        			
        			<div class="form-group">
        				<label class="control-label col-md-3" for="tanggal-pinjam">Tanggal Pinjam:</label>
        				<div class="col-md-9" >
        					<input class="form-control" name="tanggal-pinjam" type="date" id="tanggal-pinjam"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-3" for="tanggal-kembali">Maksimal Tanggal Pengembalian:</label>
        				<div class="col-md-9" >
        					<input class="form-control" name="tanggal-kembali" type="date" id="tanggal-kembali" readonly/>
        				</div>
        			</div>
        			<div class="form-group">
        				<div class="col-md-offset-3 col-md-3">
        					<input type="hidden" value="submit" name="submit">
        					<button type="submit" id="submit" class="form-control btn-primary"><i class="fa fa-exchange"></i> Proses transaksi peminjaman</button>
        				</div>
        			</div>
        			</form>
    			</div>
			</div>
		</div>
	</div>
</body>

<script>
	var tanggal_sekarang = new Date();
	var tanggal_kembali = new Date();
	tanggal_kembali.setDate(tanggal_sekarang.getDate() + <?php echo $durasi;?>);

	document.getElementById('tanggal-pinjam').valueAsDate = tanggal_sekarang;
	document.getElementById('tanggal-kembali').valueAsDate = tanggal_kembali;
	
	document.getElementById("tanggal-pinjam").onchange = function() {ubahTanggalKembali()};

	function ubahTanggalKembali() {
		
		var tanggal_pinjam = new Date(document.getElementById('tanggal-pinjam').value)
		var tanggal_kembali = new Date();
		tanggal_kembali.setDate(tanggal_pinjam.getDate() + <?php echo $durasi;?>);
		
		document.getElementById('tanggal-kembali').valueAsDate = tanggal_kembali;
	
	}
</script>