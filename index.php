<?php
require "koneksi.php";

$conn = connectDb();
$var_selisih = 5000;
?>
<!DOCTYPE html>

<head>
	<title>Update Harga Emas</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">


	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<!--data tables-->
	<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>

	<script>
		$(document).ready(function() {
			$('#myTable').DataTable();
		});
	</script>
</head>

<body>
	<section>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<div class="card mb-3">
						<div class="card-header">
							<i class="fa fa-table"></i> Update Harga Emas 1 Bulan Terakhir
						</div>

						<div class="card-body">
							<canvas id="barChart"></canvas>
						</div>
					</div><!-- end card-->
				</div>
			</div>
		</div>
	</section>
	<?php
	$qry	= mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` WHERE DATE_FORMAT(UPDATE_AT, '%m-%Y') = DATE_FORMAT(NOW(), '%m-%Y') ORDER BY `UPDATE_AT` ASC") or die(mysqli_error($conn));
	$qry2	= mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` WHERE DATE_FORMAT(UPDATE_AT, '%m-%Y') = DATE_FORMAT(NOW(), '%m-%Y') ORDER BY `UPDATE_AT` ASC") or die(mysqli_error($conn));
	$qry_terbaru	= mysqli_query($conn, "SELECT `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` ORDER BY `UPDATE_AT` DESC LIMIT 2") or die(mysqli_error($conn));

	$qry3	= mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs`") or die(mysqli_error($conn));
	?>
	<script>
		// barChart
		var ctx1 = document.getElementById("barChart").getContext('2d');
		var barChart = new Chart(ctx1, {
			type: 'line',
			data: {
				labels: [
					<?php
					while ($data_jual_ubs = mysqli_fetch_assoc($qry)) {
						?> "<?php echo $data_jual_ubs['UPDATE_AT']; ?>",
					<?php
					}
					?>
				],
				datasets: [{
					label: 'Tabung Emas Digital',
					data: [
						<?php
						while ($data_njual_pl = mysqli_fetch_assoc($qry2)) {
							$hrgjual	= explode(',', $data_njual_pl['HRG_JUAL']);
							$hj			= implode("", $hrgjual);
							$hj_pl		= $hj + $var_selisih;
							echo $hj_pl . ",";
						}
						?>
					],
					backgroundColor: [
						'rgba(255, 159, 64, 0.2)'
					],
					borderColor: [
						'rgba(255, 159, 64, 1)'
					],
					borderWidth: 1
				}]
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				}
			}
		});
	</script>

	<section>
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
		$new_beli_fix	= $new_beli_implode - $var_selisih;

		$new_jual	= $ahah[0]['HRG_JUAL'];
		$new_jual_explode = explode(",", $new_jual);
		$new_jual_implode = implode("", $new_jual_explode);
		$new_jual_fix	= $new_jual_implode + $var_selisih;

		$olddate	= $ahah[1]['UPDATE_AT'];
		$old_beli	= $ahah[1]['HRG_BELI'];
		$old_beli_explode = explode(",", $old_beli);
		$old_beli_implode = implode("", $old_beli_explode);
		$old_beli_fix	= $old_beli_implode - $var_selisih;

		$old_jual	= $ahah[1]['HRG_JUAL'];
		$old_jual_explode = explode(",", $old_jual);
		$old_jual_implode = implode("", $old_jual_explode);
		$old_jual_fix	= $old_jual_implode + $var_selisih;

		$beli_persen = (($new_beli_fix - $old_beli_fix) / $old_beli_fix) * 100;
		$jual_persen = (($new_jual_fix - $old_jual_fix) / $old_jual_fix) * 100;

		if ($new_beli_fix < $old_beli_fix) {
			$keterangan_beli = "Harga beli turun " . number_format($beli_persen, 2, ",", ".") . "%";
		} else {
			$keterangan_beli = "Harga beli naik " . number_format($beli_persen, 2, ",", ".") . "%";
		}

		if ($new_jual_fix < $old_jual_fix) {
			$keterangan_jual = "Harga jual turun " . number_format($jual_persen, 2, ",", ".") . "%";
		} else {
			$keterangan_jual = "Harga jual naik " . number_format($jual_persen, 2, ",", ".") . "%";
		}

		?>
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
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
				<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
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
		</div>
	</section>

	<section class="py-3">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="card mb-3">
						<div class="card-header">
							<i class="fa fa-table"></i> Tabel Update Harga Emas
						</div>
						<div class="card-body">
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
									while ($data = mysqli_fetch_assoc($qry3)) :

										$hrgbeli	= explode(',', $data['HRG_BELI']);
										$hb			= implode("", $hrgbeli);

										$hrgjual	= explode(',', $data['HRG_JUAL']);
										$hj			= implode("", $hrgjual);

										$hbaruna	= $hb - $var_selisih;
										$hjaruna	= $hj + $var_selisih;
										?>
										<tr>
											<td><?= $data['UPDATE_AT']; ?></td>
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
	</section>
</body>
<?php

closeDb($conn);
?>

</html>