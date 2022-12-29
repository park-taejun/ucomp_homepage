<?
	header("Content-Type: text/html; charset=EUC-KR"); 

	$data	=array("mall_id" => "FDS", 
							 "session_id" => "2021FDS00000000001",
							 "session_auth_no" => "7BHBn4Rtp00mJ/jkbvYGWpA9Fy7doLhrzsZdWrJJ2rEwXZ7uoSp0z6ZdS6aK5092m6Q/sWwf4zo6PpUpvl85Cw==",
							 "member_no" => "A0000149"
							 );

	$api_server = "http://www.edienglobalex.com/api/getMallAbleQty";

	$curl_session = curl_init($api_server);
	$opts = array(
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSLVERSION => 1,
		CURLOPT_HEADER => false
	);

	curl_setopt_array($curl_session, $opts);

	$return_data = curl_exec($curl_session);

	if (curl_errno($curl_session)) {
		throw new Exception(curl_error($curl_session));
	} else {
		//print_r(curl_getinfo($curl_session));
		curl_close($curl_session);
	}

	$res = json_decode($return_data);

	$result_msg = $res->result_msg;
	$ordr_able_qty = $res->ordr_able_qty;
	$result_cd = $res->result_cd;


	echo iconv("utf8", "euckr", $result_msg);
	echo $ordr_able_qty;
	echo $result_cd;

	exit;

?>

