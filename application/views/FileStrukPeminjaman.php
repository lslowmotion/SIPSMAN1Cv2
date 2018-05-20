<style>
table {
    width:100%;
}
table, th, td {
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;
}
th	{
	background-color: #eee;
}
.kop {
	font-size:20px;
	text-align:center;
}
</style>
<body>
	<div class="kop">
    	<strong>PERPUSTAKAAN<br>SMA NEGERI 1 CILACAP</strong>
    </div>
    <hr>
    <h4>Bukti Pengembalian Pustaka</h4>
    <table>
    	<tbody>
    		<tr>
    			<td>Kode Transaksi</td>
    			<td>:</td>
    			<td><?php echo $data_peminjaman->kode_transaksi;?></td>
    		</tr>
    		<tr>
    			<td>Pustaka Dipinjam</td>
    			<td>:</td>
    			<td><?php echo $data_peminjaman->nomor_panggil.' ('.$data_pustaka->judul.')';?></td>
    		</tr>
    		<tr>
    			<td>Identitas Peminjam</td>
    			<td>:</td>
    			<td><?php echo $data_peminjaman->no_induk.' ('.$data_anggota->nama.')';?></td>
    		</tr>
    		<tr>
    			<td>Tanggal Pinjam</td>
    			<td>:</td>
    			<td><?php echo $data_peminjaman->tanggal_pinjam;?></td>
    		</tr>
    		<tr>
    			<td>Tanggal Kembali</td>
    			<td>:</td>
    			<td><?php echo $data_peminjaman->tanggal_kembali;?></td>
    		</tr>
    		<tr>
    			<td>Denda</td>
    			<td>:</td>
    			<td><?php echo $data_peminjaman->denda;?></td>
    		</tr>
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