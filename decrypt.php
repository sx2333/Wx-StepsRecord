<?php

include_once "wxBizDataCrypt.php";


$appid = 'wx5ae850173812258d';
$sessionKey = $_REQUEST['session'];

$encryptedData=$_REQUEST['encryptedData'];

$iv = $_REQUEST['iv'];

$pc = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data );

if ($errCode == 0) {
    print($data . "\n");
} else {
    print($errCode . "\n");
}
