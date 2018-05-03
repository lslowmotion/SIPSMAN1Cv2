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
.judul{
    font-size: 5pt;
}
</style>
<body>
	<table>
		<tr>
        <?php
            $kolom = 1;
            foreach ($data_pustaka as $row){
                for($i = 1;$i <= $row->jumlah_pustaka;$i++){
                    echo '<td width="15%" align="center">';
                    echo '<barcode code="'.$row->nomor_panggil.'" type="QR" error="M" class="barcode" />';
                    echo '</td>';
                    echo '<td width="35%" align="center">';
                    echo '<b>PERPUSTAKAAN SMA N 1 CILACAP</b><br>';
                    echo $row->kode_klasifikasi.'<br>';
                    echo substr($row->pengarang,0,3).'<br>';
                    echo strtolower(substr($row->judul,0,1));
                    echo '<p class="judul">'.substr($row->judul,0,70).'</p>';
                    echo '</td>';
                    if($kolom % 2==0)
                    {
                      echo '</tr><tr>';
                    }
                    $kolom++;
                }
            }
        ?>
    	</tr>
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

