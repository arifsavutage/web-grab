<?php
require "koneksi.php";
function bacaHTML($url)
{
	$data = curl_init();
	curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($data, CURLOPT_URL, $url);

	$hasil = curl_exec($data);
	curl_close($data);

	return $hasil;
}


$conn = connectDb();

$kodeHTML = bacaHTML('https://harga-emas.org/');

//pecah div utama
$pecahDiv = explode('<div class="row space30">', $kodeHTML);

//pecah div penutup
$pecahDivLagi	= explode('</div>', $pecahDiv[6]);

//pecah <div class="col-md-12">
$pecahDivmd = explode('<div class="col-md-12">', $pecahDivLagi[0]);

//pecah div md tutup
$pecahDivmdclose = explode('</div">', $pecahDivmd[1]);

//ambil data dalam tabel antara tag <table>
$pecahTabel 	= explode('<table width="100%" class="in_table">', $pecahDivmdclose[0]);
$pecahTabel2	= explode('</table>', $pecahTabel[1]);

//ambil data dalam <tr></tr>
$pecahTr		= explode('<tr style="text-align: right;">', $pecahTabel2[0]);
$pecahTr2		= explode('</tr>', $pecahTr[5]);

//ambil data dalam <td></td>
$pecahTd		= explode('<td>', $pecahTr2[0]);

$rawhrgbeli		= explode('</td>', $pecahTd[2]);
$explhrgbeli	= explode(" ", $rawhrgbeli[0]);
$hrgbeli		= str_replace(".", ",", $explhrgbeli[0]);

$rawhrgjual		= explode('</td>', $pecahTd[4]);
$explhrgjual	= explode(" ", $rawhrgjual[0]);
$hrgjual		= str_replace(".", ",", $explhrgjual[0]);

print_r(var_dump($kodeHTML));
//echo $kodeHTML;
echo "harga beli :" . $hrgbeli . "<br />";
echo "harga jual :" . $hrgjual . "<br />";


$tgl_ini = date('Y-m-d');
$qry_tgl	= mysqli_query($conn, "SELECT `UPDATE_AT` FROM t_update_ubs WHERE date_format(UPDATE_AT, '%Y-%m-%d') = '" . $tgl_ini . "'") or die(mysqli_error($conn));
$jml		= mysqli_num_rows($qry_tgl);

echo $jml;

$qry_harga	= mysqli_query($conn, "SELECT `IDX`, `HRG_BELI`, `HRG_JUAL` FROM t_update_ubs WHERE date_format(UPDATE_AT, '%Y-%m-%d') = '" . $tgl_ini . "'") or die(mysqli_error($conn));
$row		= mysqli_fetch_row($qry_harga);

if ($jml < 1) {
	if (!empty($hrgbeli) || !empty($hrgjual)) {
		mysqli_query($conn, "INSERT INTO `t_update_ubs`(`UPDATE_AT`, `HRG_BELI`, `HRG_JUAL`) VALUES ( NOW(), '$hrgbeli', '$hrgjual')") or die(mysqli_error($conn));
	}
} else {
	$id 	  = $row['IDX'];
	$hb_exist = $row['HRG_BELI'];
	$hj_exist = $row['HRG_JUAL'];

	if ($hb_exist != $hrgbeli) {
		//update harga beli
		mysqli_query($conn, "UPDATE t_update_ubs SET HRG_BELI = '$hrgbeli' WHERE IDX = $id") or die(mysqli_error($conn));
	}

	if ($hj_exist != $hrgjual) {
		//update harga beli
		mysqli_query($conn, "UPDATE t_update_ubs SET HRG_JUAL = '$hrgjual' WHERE IDX = $id") or die(mysqli_error($conn));
	}
}


closeDb($conn);
