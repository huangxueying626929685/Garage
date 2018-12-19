<?php
$params = [
    'appid' => 'wx55a21e80247bfb13',
    'secret' => 'ae19b4c3eb6d5c6aea60fb9234929f7a',
    'js_code' => $_GET['js_code'], // 小程序传来的ticket
    'grant_type' => 'authorization_code',
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, 'https://api.weixin.qq.com/sns/jscode2session');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
$output = curl_exec($ch);

if (false === $output) {
    echo 'CURL Error:' . curl_error($ch);
}

echo $output;
