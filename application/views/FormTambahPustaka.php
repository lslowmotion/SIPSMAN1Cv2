<body>
<!-- Form tambah anggota -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Tambah Pustaka</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
        			<?php 
        				if($this->session->flashdata('message')){
        					echo $this->session->flashdata('message');
        				}
        			?>
        			<form class="form-horizontal" action="<?php echo base_url('pustaka/tambahpustaka');?>" enctype="multipart/form-data" method="post">
        			<div class="form-group">
        				<label class="control-label col-md-2" for="nomor-panggil">Nomor Panggil:</label>
        				<div class="col-md-10" >
        					<input class="form-control" placeholder="Nomor panggil"  name="nomor-panggil" type="text" id="nomor-panggil" readonly/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="isbn">ISBN:</label>
        				<div class="col-md-10" >
        					<input class="form-control num" placeholder="ISBN"  name="isbn" type="text" autocomplete="off" />
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="kode-klasifikasi">Kode Klasifikasi:</label>
        				<div class="col-md-10" >
        					<select name="kode-klasifikasi" id="kode-klasifikasi" class="form-control">
								<option selected value="">-- Pilih kategori --</option>
    							<?php 
    							foreach ($daftar_kategori as $row){
    								echo '<option value="'.$row->kode_klasifikasi.'">'.$row->kode_klasifikasi.': '.$row->nama_kategori.'</option>';
    							}
    							?>
							</select>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="judul">Judul:</label>
        				<div class="col-md-10" >
        					<input class="form-control" placeholder="Judul pustaka"  name="judul" type="text" id="judul" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="pengarang">Pengarang:</label>
        				<div class="col-md-10" >
        					<input class="form-control nama" placeholder="Nama pengarang"  name="pengarang" type="text" id="pengarang"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="penerbit">Penerbit:</label>
        				<div class="col-md-10" >
        					<input class="form-control" placeholder="Penerbit"  name="penerbit" type="text" />
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="pengarang">Kota Terbit:</label>
        				<div class="col-md-10" >
        					<input class="form-control nama" placeholder="Kota terbit"  name="kota-terbit" type="text" />
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="pengarang">Tahun Terbit:</label>
        				<div class="col-md-10" >
        					<input class="form-control num" placeholder="Tahun terbit"  name="tahun-terbit" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="sampul">Sampul:</label>
        				<div class="col-md-10" >
 							<input type="file" class="form-control-file" name="sampul" id="sampul" aria-describedby="sampulHelp">
    						<small id="sampulHelp" class="form-text text-muted">FIle sampul harus berupa gambar (jpg/jpeg/png/bmp/gif).</small>
  
        				</div>
        			</div>
        			<div class="form-group">
        				<label class="control-label col-md-2" for="jumlah">Jumlah:</label>
        				<div class="col-md-10" >
        					<input class="form-control num" placeholder="Jumlah eksemplar"  name="jumlah" type="text" autocomplete="off"/>
        				</div>
        			</div>
        			<div class="form-group">
        				<div class="col-md-2 col-md-offset-2">
        					<input type="hidden" value="submit" name="submit">
        					<input type="hidden" value="upload" name="upload">
        					<button type="submit" id="submit" class="form-control btn-primary">Simpan</button>
        				</div>
        			</div>
        			</form>
    			</div>
			</div>
		</div>
	</div>
</body>
<script src="<?php echo base_url('assets/js/killnonalphanum.js');?>"></script>
<script>
$(document).ready( function () {
    document.getElementById("kode-klasifikasi").onchange = function() {isiNomorPanggil()};
    document.getElementById("pengarang").onchange = function() {isiNomorPanggil()};
    document.getElementById("judul").onchange = function() {isiNomorPanggil()};
    
    function isiNomorPanggil() {
        var kode = document.getElementById("kode-klasifikasi").value;
        var pengarang = (document.getElementById("pengarang").value).substr(0,3);
        var judul = (document.getElementById("judul").value).substr(0,1);
       // document.getElementById("nomor-panggil").value = kode;
        document.getElementById("nomor-panggil").value = kode.concat("-",pengarang.concat("-",judul.toLowerCase()));
    }
});
/* $('#submit').on('click', function () {
    $(this).button('loading')
   
  }) */
</script>