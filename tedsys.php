<?php
require "koneksi.php";

define('BOT_TOKEN', '2036819147:AAFq6EaNfBRDon2ggGMw705bBdaMvpB_m-M');
define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

function apiRequestWebhook($method, $parameters)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");
        return false;
    }

    if (!$parameters) {
        $parameters = array();
    } else if (!is_array($parameters)) {
        error_log("Parameters must be an array\n");
        return false;
    }

    $parameters["method"] = $method;

    $payload = json_encode($parameters);
    header('Content-Type: application/json');
    header('Content-Length: ' . strlen($payload));
    echo $payload;

    return true;
}

function exec_curl_request($handle)
{
    $response = curl_exec($handle);

    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl returned error $errno: $error\n");
        curl_close($handle);
        return false;
    }

    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);

    if ($http_code >= 500) {
        // do not wat to DDOS server if something goes wrong
        sleep(10);
        return false;
    } else if ($http_code != 200) {
        $response = json_decode($response, true);
        error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
        if ($http_code == 401) {
            throw new Exception('Invalid access token provided');
        }
        return false;
    } else {
        $response = json_decode($response, true);
        if (isset($response['description'])) {
            error_log("Request was successful: {$response['description']}\n");
        }
        $response = $response['result'];
    }

    return $response;
}

function apiRequest($method, $parameters)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");
        return false;
    }

    if (!$parameters) {
        $parameters = array();
    } else if (!is_array($parameters)) {
        error_log("Parameters must be an array\n");
        return false;
    }

    foreach ($parameters as $key => &$val) {
        // encoding to JSON array parameters, for example reply_markup
        if (!is_numeric($val) && !is_string($val)) {
            $val = json_encode($val);
        }
    }
    $url = API_URL . $method . '?' . http_build_query($parameters);

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);

    return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");
        return false;
    }

    if (!$parameters) {
        $parameters = array();
    } else if (!is_array($parameters)) {
        error_log("Parameters must be an array\n");
        return false;
    }

    $parameters["method"] = $method;

    $handle = curl_init(API_URL);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
    curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

    return exec_curl_request($handle);
}

function processMessage($message)
{
    // process incoming message
    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];
    $name = $message['from']['first_name'] . " " . $message['from']['last_name'];
    if (isset($message['text'])) {
        // incoming text message
        $text = $message['text'];

        if (strpos($text, "/start") === 0) {
            apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello ' . $name . ', apa yang bisa kami bantu?', 'reply_markup' => array(
                'keyboard' => array(array('Tentang TED', 'Cek Harga Emas')),
                'one_time_keyboard' => true,
                'resize_keyboard' => true
            )));

            /**
             * keyboard dan inline_keyboard tdk bisa di gabung
             */

            /*apiRequestJson("sendMessage", array(
                'chat_id' => $chat_id,
                "text" => 'Hello ' . $name . ', apa yang bisa kami bantu?',
                'reply_markup' => array(
                    'inline_keyboard' => array(
                        array(
                            array('text' => "Tentang TED", 'url' => "https://www.tabungemas.com/tentang-kami/"),
                            array('text' => "Hello", 'switch_inline_query' => "Hello")
                        )
                    ),
                ),
            ));*/
        } else if ($text === "Tentang TED" || strtolower($text) == 'tentang ted') {
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'https://www.tabungemas.com/tentang-kami/', 'parse_mode' => 'HTML'));
        } else if ($text === "Cek Harga Emas" || strtolower($text) == 'cek harga emas') {

            $conn = connectDb();
            $qry_selisih = mysqli_query($conn, "SELECT `id`, `selisih_jual`, `selisih_beli` FROM `tb_bonus` WHERE `id`=1") or die(mysqli_error($conn));
            $var_selisih = mysqli_fetch_row($qry_selisih);

            $var_selisih_beli = $var_selisih[2];
            $var_selisih_jual = $var_selisih[1];

            $qry_terbaru    = mysqli_query($conn, "SELECT `UPDATE_AT`, `HRG_BELI`, `HRG_JUAL` FROM `t_update_ubs` ORDER BY `UPDATE_AT` DESC LIMIT 2") or die(mysqli_error($conn));
            $harga_baru     = mysqli_fetch_row($qry_terbaru);

            $update      = $harga_baru[0];
            $new_beli    = $harga_baru[1];
            $new_beli_explode = explode(",", $new_beli);
            $new_beli_implode = implode("", $new_beli_explode);
            $new_beli_fix    = $new_beli_implode - $var_selisih_beli;

            $new_jual    = $harga_baru[1];
            $new_jual_explode = explode(",", $new_jual);
            $new_jual_implode = implode("", $new_jual_explode);
            $new_jual_fix    = $new_jual_implode + $var_selisih_jual;

            closeDb($conn);

            $str_tb = "<b>Harga Jual : $new_jual_fix</b>" . PHP_EOL . "<b>Harga Beli : $new_beli_fix</b>" . PHP_EOL . "<i>update at " . date('d/m/Y H:i:s', strtotime($update)) . "</i>";

            apiRequest("sendMessage", array(
                'chat_id' => $chat_id,
                "text" => $str_tb,
                'parse_mode' => 'HTML',
                'reply_markup' => array(
                    'inline_keyboard' => array(
                        array(
                            array('text' => "Detail Update Harga", 'url' => "https://hargaemas.tabungemas.com/"),
                        )
                    ),
                ),
            ));
        } else if ($text === "Hello" || $text === "Hi") {
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Senang berjumpa dengan Anda, apa yang bisa saya bantu?'));
        } else if (strpos($text, "/stop") === 0) {
            // stop now
        } else if (strpos($text, "/bantuan") === 0) {
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Anda bisa menggunakan baris perintah /start untuk memulai'));
        } else {
            apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'maaf saya belum bisa memahaminya, ketik /start untuk memulai lagi'));
        }
    } else {
        apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'maaf saya belum bisa memahaminya, ketik /start untuk memulai lagi'));
    }
}


define('WEBHOOK_URL', 'https://tabungemas.com/bot/tedsys.php');

if (php_sapi_name() == 'cli') {
    // if run from console, set or delete webhook
    apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
    exit;
}


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
    // receive wrong update, must not happen
    exit;
}

if (isset($update["message"])) {
    processMessage($update["message"]);
}
