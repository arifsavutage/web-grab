<?php
require "koneksi.php";

$conn = connectDb();
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
							<i class="fa fa-table"></i> Harga Jual Emas UBS vs MyPlatform
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
	$qry	= mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` ORDER BY `UPDATE_AT` ASC") or die(mysqli_error($conn));

	$qry_hrgjual_ubs	= mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` ORDER BY `UPDATE_AT` ASC") or die(mysqli_error($conn));
	$qry_nilaijual_ubs	= mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` ORDER BY `UPDATE_AT` ASC") or die(mysqli_error($conn));

	$qry_nilaijual_pl	= mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` ORDER BY `UPDATE_AT` ASC") or die(mysqli_error($conn));
	$qry_nilaijual_pl	= mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` ORDER BY `UPDATE_AT` ASC") or die(mysqli_error($conn));
	?>
	<script>
		// barChart
		var ctx1 = document.getElementById("barChart").getContext('2d');
		var barChart = new Chart(ctx1, {
			type: 'line',
			data: {
				labels: [
					<?php
					while ($data_jual_ubs = mysqli_fetch_assoc($qry_hrgjual_ubs)) {
						?> "<?php echo $data_jual_ubs['UPDATE_AT']; ?>",
					<?php
					}
					?>
				],
				datasets: [{
					label: 'UBS',
					data: [
						<?php
						while ($data_njual_ubs = mysqli_fetch_assoc($qry_nilaijual_ubs)) {
							$hrgjual	= explode(',', $data_njual_ubs['HRG_JUAL']);
							$hj			= implode("", $hrgjual);

							echo $hj . ",";
						}
						?>
					],
					backgroundColor: [

						'rgba(75, 192, 192, 0.2)',

					],
					borderColor: [

						'rgba(75, 192, 192, 1)',

					],
					borderWidth: 1
				}, {
					label: 'MyPlatform',
					data: [
						<?php
						while ($data_njual_pl = mysqli_fetch_assoc($qry_nilaijual_pl)) {
							$hrgjual	= explode(',', $data_njual_pl['HRG_JUAL']);
							$hj			= implode("", $hrgjual);
							$hj_pl		= $hj + 7500;
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
		<div class="container">
			<div class="col-lg-12">
				<div class="card mb-3">
					<div class="card-header">
						<i class="fa fa-table"></i> Tabel Harga Emas UBS vs MyPlatform
					</div>
					<div class="card-body">
						<table id="myTable" class="table">
							<thead>
								<tr>
									<th>Update At</th>
									<th>Harga Beli (UBS)</th>
									<th>Harga Jual (UBS)</th>
									<th>Harga Beli (Aruna)</th>
									<th>Harga Jual (Aruna)</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$var_selisih = 7500;
								while ($data = mysqli_fetch_assoc($qry)) :

									$hrgbeli	= explode(',', $data['HRG_BELI']);
									$hb			= implode("", $hrgbeli);

									$hrgjual	= explode(',', $data['HRG_JUAL']);
									$hj			= implode("", $hrgjual);

									$hbaruna	= $hb - $var_selisih;
									$hjaruna	= $hj + $var_selisih;
									?>
								<tr>
									<td><?= $data['UPDATE_AT']; ?></td>
									<td><?= "Rp. " . number_format($hb, 0, ',', '.'); ?></td>
									<td><?= "Rp. " . number_format($hj, 0, ',', '.'); ?></td>
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
	</section>
</body>
<?php

closeDb($conn);
?>

</html>