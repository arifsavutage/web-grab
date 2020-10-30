<?php
require_once("koneksi.php");

$conn = connectDb();
$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);

$content = file_get_contents('https://harga-emas.org/', false, stream_context_create($arrContextOptions));

$pecahDiv = explode('<div class="row space30">', $content);
$pecahDivLagi    = explode('</div>', $pecahDiv[6]);

$pecahDivmd = explode('<div class="col-md-12">', $pecahDivLagi[0]);
$pecahDivmdclose = explode('</div">', $pecahDivmd[1]);

//ambil data dalam tabel antara tag <table>
$pecahTabel     = explode('<table width="100%" class="in_table">', $pecahDivmdclose[0]);
$pecahTabel2    = explode('</table>', $pecahTabel[1]);

//ambil data dalam <tr></tr>
$pecahTr        = explode('<tr style="text-align: right;">', $pecahTabel2[0]);
$pecahTr2        = explode('</tr>', $pecahTr[5]);

//ambil data dalam <td></td>
$pecahTd        = explode('<td>', $pecahTr2[0]);

$rawhrgbeli        = explode('</td>', $pecahTd[2]);
$explhrgbeli    = explode(" ", $rawhrgbeli[0]);
$hrgbeli        = str_replace(".", ",", $explhrgbeli[0]);

$rawhrgjual        = explode('</td>', $pecahTd[4]);
$explhrgjual    = explode(" ", $rawhrgjual[0]);
$hrgjual        = str_replace(".", ",", $explhrgjual[0]);

//print_r(var_dump($kodeHTML));
//echo $kodeHTML;
echo "harga beli :" . $hrgbeli . "<br />";
echo "harga jual :" . $hrgjual . "<br />";

//print_r(var_dump($pecahDivmdclose));

$tgl_ini = date('Y-m-d');
$qry_tgl    = mysqli_query($conn, "SELECT `UPDATE_AT` FROM t_update_ubs WHERE date_format(UPDATE_AT, '%Y-%m-%d') = '" . $tgl_ini . "'") or die(mysqli_error($conn));
$jml        = mysqli_num_rows($qry_tgl);

//echo $jml;

$qry_harga    = mysqli_query($conn, "SELECT `IDX`, `HRG_BELI`, `HRG_JUAL` FROM t_update_ubs WHERE date_format(UPDATE_AT, '%Y-%m-%d') = '" . $tgl_ini . "'") or die(mysqli_error($conn));
$row        = mysqli_fetch_row($qry_harga);

if ($jml < 1) {
    if (!empty($hrgbeli) || !empty($hrgjual)) {
        mysqli_query($conn, "INSERT INTO `t_update_ubs`(`UPDATE_AT`, `HRG_BELI`, `HRG_JUAL`) VALUES ( NOW(), '$hrgbeli', '$hrgjual')") or die(mysqli_error($conn));
    }
} else {
    $id       = $row[0];
    $hb_exist = $row[1];
    $hj_exist = $row[2];

    if ($hb_exist != $hrgbeli) {
        //update harga beli
        mysqli_query($conn, "UPDATE t_update_ubs SET HRG_BELI = '$hrgbeli' WHERE IDX = $id") or die(mysqli_error($conn));

        echo "harga beli sudah di update <br />";
    }

    if ($hj_exist != $hrgjual) {
        //update harga beli
        mysqli_query($conn, "UPDATE t_update_ubs SET HRG_JUAL = '$hrgjual' WHERE IDX = $id") or die(mysqli_error($conn));

        echo "harga jual sudah di update <br />";
    }
}


closeDb($conn);
