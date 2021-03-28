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
$request    = Requests::get('https://harga-emas.org/', $headers, $options);

//var_dump($request->status_code);
//var_dump($request->headers['content-type']);
//print_r(var_dump($request->body));

//ambil data antara tag <div class="row space30">
$pecahDiv = explode('<div class="row space30">', $request->body);

//ambil data antara tab </div>
$pecahDivLagi = explode('</div>', $pecahDiv[6]);

//ambil data dari <div class="col-md-12"></div>
$pecahCol = explode('<div class="col-md-12">', $pecahDivLagi[0]);
$pecahColLagi = explode('</div">', $pecahCol[1]);

//ambil data dalam tabel antara tag <table></table>
$pecahTabel = explode('<table width="100%" class="in_table">', $pecahColLagi[0]);
$pecahLagi    = explode('</table>', $pecahTabel[1]);

//ambil data antara <tbody></tbody>
$pecahTBody = explode('<tbody>', $pecahLagi[0]);
$pecahTBodyLagi    = explode('</table>', $pecahTBody[0]);

//ambil data dalam tabel antara tag <tr></tr>
$pecahTr    = explode('<tr>', $pecahLagi[0]);
$pecahTrTutup = explode('</tr>', $pecahTr[0]);


//proses ambil data tiap <td></td>
/*print_r(var_dump($pecahTrTutup));

echo "<br />";
echo html_entity_decode($pecahTrTutup[8]);

$jmltd    = count($pecahTrTutup);*/

#ambil data harga per 1 gram <td>
$pecahTd = explode('<td>', $pecahTrTutup[8]);
$pecahTdLagi = explode('<td>', $pecahTrTutup[8]);
//print_r(var_dump($pecahTdLagi));

//ambil index ke 2 & 4
//echo "<br/>$pecahTd[2] & $pecahTd[4] <br/>";

$beliX = explode(" ", $pecahTdLagi[2]);
$jualX = explode(" ", $pecahTdLagi[4]);

$beliXX = explode("</td>", $pecahTdLagi[2]);
$jualXX = explode("</td>", $pecahTdLagi[4]);

//print_r(var_dump($jualX));


//ubah titik jadi koma
$fixBeli = str_replace(".", ",", $beliXX[0]);
$fixJual = str_replace(".", ",", $jualXX[0]);
//print_r(var_dump($fixJual));


echo "harga emas UBS (per gr) saat ini : <br />";

echo "<strong>Harga Beli</strong> = " . $fixBeli . "<br />";
echo "<strong>Harga Jual</strong> = " . $fixJual . "<br />";


$qry_cek2    = mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` WHERE UPDATE_AT = CURDATE() ORDER BY `UPDATE_AT` DESC") or die(mysqli_error($conn));
$cek_jml    = mysqli_num_rows($qry_cek2);

//echo $cek_jml;

if ($cek_jml < 1) {
    mysqli_query($conn, "INSERT INTO `t_update_ubs`(`UPDATE_AT`, `HRG_BELI`, `HRG_JUAL`) VALUES ( NOW(), '$fixBeli', '$fixJual')") or die(mysqli_error($conn));
} else {

    $qry_get    = mysqli_query($conn, "SELECT `IDX`, `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` WHERE UPDATE_AT = CURDATE() ORDER BY IDX DESC LIMIT 1") or die(mysqli_error($conn));
    $getting    = mysqli_fetch_row($qry_get);

    //echo "IDX " . $getting[0];
    mysqli_query($conn, "UPDATE `t_update_ubs` SET UPDATE_AT = NOW(), `HRG_BELI`='$fixBeli',`HRG_JUAL`='$fixJual' WHERE `IDX` = $getting[0]") or die(mysqli_error($conn));
}

closeDb($conn);
