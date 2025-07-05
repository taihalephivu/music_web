<?php
session_start();
$type = isset($_POST['type']) ? $_POST['type'] : '';
$value = isset($_POST['value']) ? $_POST['value'] : '';
if ($type && $value) {
    $_SESSION['voucher'] = [
        'type' => $type, // 'percent' hoặc 'fixed'
        'value' => $value,
        'used' => false
    ];
    echo 'OK';
} else {
    echo 'ERROR';
} 