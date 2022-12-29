<?php
date_default_timezone_set('Asia/Seoul'); 
$offset = 0;
$limit = 100;
$messageId = "e2e3b467-5104-41d8-8066-40c7176628e0";
$curl = curl_init();
curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-API-KEY: TBTczEe7fMIZkEOoAWrr4e1LuNJfi9Cw','Content-Type:application/json'));
curl_setopt($curl, CURLOPT_URL, 'https://api.itsbee.io/v1/message/63d4a745-38da-4c3f-ad74-30f1fb296ff7/' . $messageId);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($curl, CURLOPT_FAILONERROR, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$postData = [
	"channel" => [
		"default"   // 채널명 ( 고정 )
    ],
	"msgtype" => "text",        // 메시지 종류
	"message" => "text message",    // 내용
	"title" => "title2222", // 제목
	"data" => [
		"menu" => "/menu.php"   // 이동할 메뉴 없을 경우 비워두세요.
    ],
	"scheduleAt" => "2018-06-10 23:00:00"  // 예약 시간
];
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
$output = curl_exec($curl);
if ($output === FALSE) {
	echo 'An error has occurred: ' . curl_error($curl) . PHP_EOL;
}
else {
   $response = json_decode($output);
   if($response->status)
    echo "success";
   else
    echo $response->error;
}
?>