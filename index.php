<?php
header("Content-Type: application/json");

$api_key = 'ecd46e3c7344bbb8';
$secret_key = 'NjcxNTNkMzg0YTJhZjE1YTk2NGE5OGQ5NjlkMWE2MzA2YmRkY2U4ODI3NmRkOWQzN2ExMThhNGI4N2QwODU5MQ';

// Read Flutter POST data
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['phone']) || !isset($data['message'])) {
    echo json_encode(["success" => false, "error" => "Missing phone or message"]);
    exit;
}

$phone = $data['phone'];
$message = $data['message'];

// Auto-convert phone to 255 format
$phone = preg_replace('/\s+/', '', $phone);

if (substr($phone, 0, 1) === '0') {
    $phone = '255' . substr($phone, 1);
}

if (substr($phone, 0, 4) === '+255') {
    $phone = substr($phone, 1);
}

$postData = [
    "source_addr"  => "KAGIMBOSHOP",
    "encoding"     => 0,
    "schedule_time"=> "",
    "message"      => $message,
    "recipients"   => [
        [
            "recipient_id" => "1",
            "dest_addr"    => $phone
        ]
    ]
];

$url = 'https://apisms.beem.africa/v1/send';

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => [
        'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode($postData),
]);

$response = curl_exec($ch);

if ($response === FALSE) {
    echo json_encode(["success" => false, "error" => curl_error($ch)]);
    exit;
}

echo $response;

