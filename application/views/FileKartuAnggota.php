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
            foreach ($data_anggota as $row){
                
                //yang dituliskan
                echo '<td width="50%" align="center">';
                echo '<b>KARTU ANGGOTA PERPUSTAKAAN<br>SMA NEGERI 1 CILACAP</b><br><br>';
                echo '<barcode code="'.$row->no_induk.'" type="QR" size="0.7" error="M" class="barcode" /><br><br>';
                echo 'No Induk: '.$row->no_induk.'<br>';
                echo 'Nama: '.$row->nama.'<br>';
                echo 'Alamat: '.$row->alamat.'<br>';
                echo '</td>';
                
                //bila cuma ada 1 data dalam array, buat <td> tambahan
                if(count($data_anggota) == 1){
                    echo '<td width="50%" align="center"></td>';
                }
                
                //batasan kolom = 2
                if($kolom % 2 == 0)
                {
                  echo '</tr><tr>';
                }
                $kolom++;
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

