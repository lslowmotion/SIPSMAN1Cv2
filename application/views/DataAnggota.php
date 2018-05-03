<body>
<!-- Tabel akun -->
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h1 class="page-header">Data Anggota</h1>
				</div>
				
			</div>
			
			
			<div class="row">
				<div class="col-md-12" style="overflow-x:auto;">
        			<?php 
        				if($this->session->flashdata('message')){
        					echo $this->session->flashdata('message');
        				}
        			?>
        			<?php if (isset($data_anggota)){?>
        			<table class="table">
    					<tr>
    						<td class="col-md-2">No Induk</td>
    						<td class="col-md-1" align="right">:</td>
    						<td class="col-md-8"><?php echo $data_anggota->no_induk;?></td>
    					</tr>
    					<tr>
    						<td>Nama</td>
    						<td align="right">:</td>
    						<td><?php echo $data_anggota->nama;?></td>
    					</tr>
    					<tr>
    						<td>Alamat</td>
    						<td align="right">:</td>
    						<td><?php echo $data_anggota->alamat;?></td>
    					</tr>
    					<tr>
    						<td>Email</td>
    						<td align="right">:</td>
    						<td><?php echo $data_anggota->email;?></td>
    					</tr>
    					<tr>
    						<td>No Telepon</td>
    						<td align="right">:</td>
    						<td><?php echo $data_anggota->telepon;?></td>
    					</tr>
    					<tr>
    						<td>
    							<a href="<?php echo base_url('anggota');?>">
    								<button class="btn btn-danger">
    									<i class="fa fa-arrow-left"></i> Kembali ke Daftar Anggota
    								</button>
    							</a>
    						</td>
    						<td></td>
    						<td>
    							<a href="<?php echo base_url('anggota/editanggota/'.$data_anggota->no_induk);?>">
    								<button class="btn btn-primary">
    									<i class="fa fa-edit"></i> Edit data
    								</button>
    							</a>
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