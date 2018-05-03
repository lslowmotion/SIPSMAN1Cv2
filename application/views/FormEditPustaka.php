<body>
<!-- Form tambah anggota -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Edit Pustaka</h1>
				</div>
			</div>
			<?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>
			<?php if (isset($data_pustaka)){?>
			<form class="form-horizontal" action="<?php echo base_url('pustaka/editpustaka/'.$data_pustaka->nomor_panggil);?>" enctype="multipart/form-data" method="post">
			<div class="form-group">
				<label class="control-label col-md-2" for="nomor-panggil">Nomor Panggil:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_pustaka->nomor_panggil;?>" class="form-control" placeholder="Nomor panggil"  name="nomor-panggil" type="text" readonly />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="no-induk">ISBN:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_pustaka->isbn;?>" class="form-control num" placeholder="ISBN (International Standard Book Number)"  name="isbn" type="text"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="kode-klasifikasi">Kode klasifikasi:</label>
				<div class="col-md-10" >
					<select name="kode-klasifikasi" id="kode-klasifikasi" class="form-control">
						<option selected value="<?php echo $data_pustaka->kode_klasifikasi;?>"><?php echo $data_pustaka->kode_klasifikasi?>: <?php echo $data_kategori->nama_kategori;?></option>
						<option value="">-- Ganti kategori --</option>
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
					<input value="<?php echo $data_pustaka->judul;?>" class="form-control" placeholder="Judul pustaka"  name="judul" type="text" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="pengarang">Nama Pengarang:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_pustaka->pengarang;?>" class="form-control pengarang" placeholder="Nama pengarang"  name="pengarang" type="text" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="penerbit">Penerbit:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_pustaka->penerbit;?>" class="form-control" placeholder="Nama penerbit"  name="penerbit" type="text" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="kota-terbit">Kota Terbit:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_pustaka->kota_terbit;?>" class="form-control nama" placeholder="Kota tempat terbit"  name="kota-terbit" type="text" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="tahun-terbit">Tahun Terbit:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_pustaka->tahun_terbit;?>" class="form-control num" placeholder="Tahun terbit"  name="tahun-terbit" type="text" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="sampul">Gambar Sampul:</label>
				<div class="col-md-10" >
					<input type="file" class="form-control-file" name="sampul" id="sampul" aria-describedby="sampulHelp">
					<small id="sampulHelp" class="form-text text-muted">FIle sampul harus berupa gambar (jpg/jpeg/png/bmp/gif).</small>

				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2" for="jumlah">Jumlah Koleksi:</label>
				<div class="col-md-10" >
					<input value="<?php echo $data_pustaka->jumlah_pustaka;?>" class="form-control num" placeholder="Jumlah koleksi yang dimiliki"  name="jumlah" type="text" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2 col-md-offset-2">
					<input type="hidden" value="submit" name="submit">
					<input type="hidden" value="upload" name="upload">
					<button type="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
				</div>
			</div>
			</form>
			<?php }?>
		</div>
	</div>
</body>
<script src="<?php echo base_url('assets/js/killnonalphanum.js');?>"></script>
