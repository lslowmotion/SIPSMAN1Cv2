<style>
table {
    width:100%;
}
.table-peminjaman, .th-peminjaman, .td-peminjaman {
    border: 1px solid black;
    border-collapse: collapse;
}
.th-peminjaman, .td-peminjaman {
    padding: 5px;
    text-align: left;
}
.th-peminjaman	{
	background-color: #eee;
}
.kop {
	font-size:20px;
	text-align:center;
}
.isi-surat{
    text-align:justify;
}

</style>
<body>
    <div class="kop">
    	<strong>PERPUSTAKAAN SMA NEGERI 1 CILACAP</strong>
    </div>
    <hr>
    <h3>Surat Bebas Pinjam</h3>
    <p class="isi-surat">Perpustakaan SMA Negeri 1 Cilacap dengan surat ini menyatakan bahwa siswa dengan identitas:</p>
    <table>
    <tr>
    	<td>Nama</td>
    	<td>:</td>
    	<td><?php echo $nama;?></td>
	</tr>
    <tr>
    	<td>No Induk:</td>
    	<td>:</td>
    	<td><?php echo $no_induk;?></td>
	</tr>
    </table>
    <p class="isi-surat">telah menyelesaikan tanggungjawabnya dalam mengembalikan koleksi pustaka yang telah dipinjam dari Perpustakaan SMA Negeri 1 Cilacap.
    Segala hak dan kewajiban yang bersangkutan sebagai anggota perpustakaan telah berakhir dengan dikeluarkannya Surat Bebas Pinjam ini.</p>
    <h3>Riwayat Peminjaman</h3>
    <table class="table-peminjaman">
    	<thead>
    		<tr>
    			<th class="th-peminjaman">Kode Transaksi</th>
    			<th class="th-peminjaman">Nomor Panggil Pustaka</th>
    			<th class="th-peminjaman">Judul Pustaka</th>
    			<th class="th-peminjaman">Tanggal Pinjam</th>
    			<th class="th-peminjaman">Tanggal Kembali</th>
    			<th class="th-peminjaman">Denda</th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php foreach ($data_peminjaman as $row){?>
    		<tr>
    			<td class="td-peminjaman"><?php echo $row->kode_transaksi;?></td>
    			<td class="td-peminjaman"><?php echo $row->nomor_panggil;?></td>
    			<td class="td-peminjaman"><?php echo $row->judul;?></td>
    			<td class="td-peminjaman"><?php echo $row->tanggal_pinjam;?></td>
    			<td class="td-peminjaman"><?php echo $row->tanggal_kembali;?></td>
    			<td class="td-peminjaman"><?php echo $row->denda;?></td>
    		</tr>
    		<?php }?>
    	</tbody>
    </table>
    <p align="right">
    
    	Cilacap,
    	<?php
    	    $now = date('d/m/Y');
    	    echo $now;
        ?>
    
    <br>
    Petugas Perpustakaan SMA N 1 Cilacap
    <br>
    <br>
    <br>
    <br>
    <br>
    (..............................................)
    </p>
</body>					

