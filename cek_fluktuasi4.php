<?php
require "koneksi.php";
$conn = connectDb();

require('rmccue-Requests-9da3478/library/Requests.php');

Requests::register_autoloader();

$headers    = array('Accept' => 'application/json');
//$options    = array();
$options = array(
    'verify' => false
);
$request    = Requests::get('https://www.indogold.id/harga-emas-hari-ini', $headers, $options);

//var_dump($request->status_code);
//var_dump($request->headers['content-type']);
//print_r(var_dump($request->body));

//ambil data dalam tabel antara tag <table>
$pecahTabel = explode('<table style="width:100%">', $request->body);

//ambil data dalam tabel antara tag </tabel>
$pecahLagi    = explode('</table>', $pecahTabel[2]);

//ambil data dalam tabel antara tag <tr>
$pecahTr    = explode('<tr>', $pecahLagi[0]);

//ambil data dalam tabel antara tag </tr>
$pecahTrTutup = explode('</tr>', $pecahTr[1]);

//proses ambil data tiap <td></td>
//echo count($pecahTrTutup);
//$jmltd	= count($pecahTrTutup);

#ambil data harga per 1 gram
$pecahTd = explode('<td>', $pecahTrTutup[2]);
//echo count($pecahTd);
$pecahTdTutup = explode('</td>', $pecahTd[0]);

//echo count($pecahTdTutup);
//echo "<p>$pecahTdTutup[0] $pecahTdTutup[1] $pecahTdTutup[2]</p>";

echo "harga emas (per gr) saat ini : <br />";

$str_angka    = [];
for ($i = 1; $i <= 2; $i++) {
    //ambil data rupiah di dalam tag <strong> harga beli
    $strongop    = explode('<strong>', $pecahTdTutup[$i]);

    $strongcl    = explode('</strong>', $strongop[1]);
    //echo $strongcl[0]."<br />";

    //ambil data nominal saja
    $nominal    = explode(" ", $strongcl[0]);

    if ($i == 1) {
        $teks = "Harga Beli : ";
    } else {
        $teks = "Harga Jual : ";
    }

    echo "<strong>$teks</strong>" . $nominal[2] . "<br />";
    array_push($str_angka, $nominal[2]);
}


$qry_cek2    = mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` WHERE UPDATE_AT = CURDATE() ORDER BY `UPDATE_AT` DESC") or die(mysqli_error($conn));
$cek_jml    = mysqli_num_rows($qry_cek2);

//echo $cek_jml;

if ($cek_jml < 1) {
    mysqli_query($conn, "INSERT INTO `t_update_ubs`(`UPDATE_AT`, `HRG_BELI`, `HRG_JUAL`) VALUES ( NOW(), '$str_angka[0]', '$str_angka[1]')") or die(mysqli_error($conn));
} else {

    $qry_get    = mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` WHERE UPDATE_AT = CURDATE() ORDER BY IDX DESC LIMIT 1") or die(mysqli_error($conn));
    $getting    = mysqli_fetch_row($qry_get);

    //echo "IDX " . $getting[0];
    mysqli_query($conn, "UPDATE `t_update_ubs` SET UPDATE_AT = NOW(), `HRG_BELI`='$str_angka[0]',`HRG_JUAL`='$str_angka[1]' WHERE `IDX` = $getting[0]") or die(mysqli_error($conn));
}

closeDb($conn);
