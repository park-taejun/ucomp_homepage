<?php
date_default_timezone_set('Asia/Seoul'); 
$offset = 0;
$limit = 100;
$curl = curl_init();

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-API-KEY: TBTczEe7fMIZkEOoAWrr4e1LuNJfi9Cw'));
curl_setopt($curl, CURLOPT_URL, 'https://api.itsbee.io/v1/message/63d4a745-38da-4c3f-ad74-30f1fb296ff7?offset=$offset&limit=$limit');

curl_setopt($curl, CURLOPT_FAILONERROR, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($curl);
if ($output === FALSE) {
	echo 'An error has occurred: ' . curl_error($curl) . PHP_EOL;
}
else {
    $messages = json_decode($output);
    echo $messages->total . "<br>";  //  전체 갯수
    foreach($messages->data as $message)
    {
        $message->scheduleAt = date('Y-m-d H:i:s',strtotime($message->scheduleAt)); // UTC 를 로컬 시간으로 변경 합니다.
        //print_r($message);

				echo $message->_id;
				echo $message->createdAt;
    }
}
/*
    나머지 값은 무시하셔도 됩니다.
    [_id] => 몽고키값
    [createdAt] => 생성일
    [id] => 키값
    [channel] => 채널명
    [message] => 메시지
    [title] => 제목
    [description] => 설명
    [scheduleAt] => 예약시간 (UTC 기준)
    [total] => 전체 발송 수 
    [hit] => 클릭 수
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