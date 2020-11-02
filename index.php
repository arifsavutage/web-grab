<?php
require "koneksi.php";

$conn = connectDb();
$qry_selisih = mysqli_query($conn, "SELECT `id`, `selisih_jual`, `selisih_beli` FROM `tb_bonus` WHERE `id`=1") or die(mysqli_error($conn));
$var_selisih = mysqli_fetch_row($qry_selisih);

$var_selisih_beli = $var_selisih[2];
$var_selisih_jual = $var_selisih[1];
?>
<!DOCTYPE html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Update Harga Emas</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">


	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<!--data tables-->
	<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>

	<script>
		$(document).ready(function() {
			$('#myTable').DataTable({
				"order": [
					//angka adalah nomor kolom
					[0, "desc"]
				]
			});
		});
	</script>
</head>

<body>

	<?php
	$qry_terbaru	= mysqli_query($conn, "SELECT `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` ORDER BY `UPDATE_AT` DESC LIMIT 2") or die(mysqli_error($conn));
	?>



	<?php
	$ahah	= [];
	while ($data_baru = mysqli_fetch_assoc($qry_terbaru)) :

		array_push($ahah, $data_baru);

	endwhile;
	//print_r(var_dump($ahah));

	$update		= $ahah[0]['UPDATE_AT'];
	$new_beli	= $ahah[0]['HRG_BELI'];
	$new_beli_explode = explode(",", $new_beli);
	$new_beli_implode = implode("", $new_beli_explode);
	$new_beli_fix	= $new_beli_implode - $var_selisih_beli;

	$new_jual	= $ahah[0]['HRG_JUAL'];
	$new_jual_explode = explode(",", $new_jual);
	$new_jual_implode = implode("", $new_jual_explode);
	$new_jual_fix	= $new_jual_implode + $var_selisih_jual;

	$olddate	= $ahah[1]['UPDATE_AT'];
	$old_beli	= $ahah[1]['HRG_BELI'];
	$old_beli_explode = explode(",", $old_beli);
	$old_beli_implode = implode("", $old_beli_explode);
	$old_beli_fix	= $old_beli_implode - $var_selisih_beli;

	$old_jual	= $ahah[1]['HRG_JUAL'];
	$old_jual_explode = explode(",", $old_jual);
	$old_jual_implode = implode("", $old_jual_explode);
	$old_jual_fix	= $old_jual_implode + $var_selisih_jual;

	$beli_persen = (($new_beli_fix - $old_beli_fix) / $old_beli_fix) * 100;
	$jual_persen = (($new_jual_fix - $old_jual_fix) / $old_jual_fix) * 100;

	if ($new_beli_fix < $old_beli_fix) {
		$keterangan_beli = "Harga beli turun " . number_format($beli_persen, 2, ",", ".") . "% update at " . date('d M Y', strtotime($update));
	} else {
		$keterangan_beli = "Harga beli naik " . number_format($beli_persen, 2, ",", ".") . "% update at " . date('d M Y', strtotime($update));
	}

	if ($new_jual_fix < $old_jual_fix) {
		$keterangan_jual = "Harga jual turun " . number_format($jual_persen, 2, ",", ".") . "% update at " . date('d M Y', strtotime($update));
	} else {
		$keterangan_jual = "Harga jual naik " . number_format($jual_persen, 2, ",", ".") . "% update at " . date('d M Y', strtotime($update));
	}

	?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-3">
				<div class="card text-center">
					<div class="card-header">
						Harga Beli
					</div>
					<div class="card-body">
						<h5 class="card-title">Rp. <?= number_format($new_beli_fix, 0, ',', '.'); ?></h5>
						<!--
						<p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
						<a href="#" class="btn btn-primary">Go somewhere</a>
-->
					</div>
					<div class="card-footer text-muted">
						<?= $keterangan_beli; ?>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-3">
				<div class="card text-center">
					<div class="card-header">
						Harga Jual
					</div>
					<div class="card-body">
						<h5 class="card-title">Rp. <?= number_format($new_jual_fix, 0, ',', '.'); ?></h5>
					</div>
					<div class="card-footer text-muted">
						<?= $keterangan_jual; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-lg-12">
				<div class="card mb-3">
					<div class="card-header">
						<i class="fa fa-table"></i> Tabel Update Harga Emas
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="myTable" class="table">
								<thead>
									<tr>
										<th>Update At</th>
										<th>Harga Beli</th>
										<th>Harga Jual</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$qry3	= mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` ORDER BY `IDX` DESC") or die(mysqli_error($conn));
									while ($data = mysqli_fetch_assoc($qry3)) :

										$hrgbeli	= explode(',', $data['HRG_BELI']);
										$hb			= implode("", $hrgbeli);

										$hrgjual	= explode(',', $data['HRG_JUAL']);
										$hj			= implode("", $hrgjual);

										$hbaruna	= $hb - $var_selisih_beli;
										$hjaruna	= $hj + $var_selisih_jual;
									?>
										<tr>
											<td><?= date('Y-m-d', strtotime($data['UPDATE_AT'])); ?></td>
											<td><?= "Rp. " . number_format($hbaruna, 0, ',', '.'); ?></td>
											<td><?= "Rp. " . number_format($hjaruna, 0, ',', '.'); ?></td>
										</tr>
									<?php
									endwhile;
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<?php

closeDb($conn);
?>

</html>