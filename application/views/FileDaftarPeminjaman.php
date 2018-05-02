<style>
table {
    width:100%;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;
}
th	{
	background-color: #eee;
}
</style>
<body>
    <table>
    	<thead>
    		<tr>
    			<th>Kode Transaksi</th>
    			<th>No Induk Anggota</th>
    			<th>Nama Anggota</th>
    			<th>Nomor Panggil Pustaka</th>
    			<th>Judul Pustaka</th>
    			<th>Tanggal Pinjam</th>
    			<th>Tanggal Kembali</th>
    			<th>Denda</th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php foreach ($data_peminjaman as $row){?>
    		<tr>
    			<td><?php echo $row->kode_transaksi;?></td>
    			<td><?php echo $row->no_induk;?></td>
    			<td><?php echo $row->nama;?></td>
    			<td><?php echo $row->nomor_panggil;?></td>
    			<td><?php echo $row->judul;?></td>
    			<td><?php echo $row->tanggal_pinjam;?></td>
    			<td><?php echo $row->tanggal_kembali;?></td>
    			<td><?php echo $row->denda;?></td>
    		</tr>
    		<?php }?>
    	</tbody>
    </table>
    <p align="right">
    <b>
    	Cilacap,
    	<?php
    	    $now = date('d/m/Y');
    	    echo $now;
        ?>
    </b>
    </p>
</body>					

