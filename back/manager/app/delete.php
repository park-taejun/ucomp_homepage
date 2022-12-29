<?php
date_default_timezone_set('Asia/Seoul'); 
$offset = 0;
$limit = 100;
$curl = curl_init();
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-API-KEY: TBTczEe7fMIZkEOoAWrr4e1LuNJfi9Cw'));
curl_setopt($curl, CURLOPT_URL, 'https://api.itsbee.io/v1/message/63d4a745-38da-4c3f-ad74-30f1fb296ff7/2368aff2-e9b6-484c-8800-ec0d8b2cc9e6');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($curl, CURLOPT_FAILONERROR, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($curl);

if ($output === FALSE) {
	echo 'An error has occurred: ' . curl_error($curl) . PHP_EOL;
}
else {
    $message = json_decode($output);
    print_r($message);
}
/*
    나머지 값은 무시하셔도 됩니다.
    [createdAt] => 생성일
    [id] => 메시지ID
    [channel] => 채널명
    [message] => 메시지
    [title] => 제목
    [description] => 설명
    [scheduleAt] => 예약시간
    [data] => Array
        (
            [0] => stdClass Object
                (
                    [menu] => /menu.php // 이동할 메뉴명
                )

        )
    [status] => 상태 ( 1: 성공, 2: 실패, 3: 진행중 )
*/
?>