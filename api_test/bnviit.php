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

/*
$ch = curl_init();																	//curl 초기화
curl_setopt($ch, CURLOPT_URL, $api_server);					//URL 지정하기
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);			//요청 결과를 문자열로 반환 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);				//connection timeout 10초 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		//원격 서버의 인증서가 유효한지 검사 안함
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);				//POST data
curl_setopt($ch, CURLOPT_POST, true);								//true시 post 전송 

$response = curl_exec($ch);
curl_close($ch);

if (curl_errno($ch)) {
	throw new Exception(curl_error($ch));
} else {
	//print_r(curl_getinfo($ch));
	curl_close($ch);
}

return $response;
*/






	exit;

	$api_server = "https://bnviit.com/app_api/index.php";



	if ($mode == "LIST"){

		$page = "1";
		$pagesize = "20";
		$category = "STAR|BASIC";
		$op = "EX01|EX02";

		$data	=array("mode" => $mode, 
								 "category" => $category,
								 "op" => $op,
								 "page" => $page,
								 "pagesize" => $pagesize
								);

	}

	if ($mode == "USER"){
		$user = "장예원" ;
		$phone = "" ;

		$data	=array("mode" => $mode, 
								 "user" => $user,
								 "phone" => $phone
								);

	}

	if ($mode == "INSERT"){
		$user = "테스트_홍길동" ;
		$phone = "010-1234-5678" ;
		$category = "BASIC";
		$op = "EX01";

		$data	=array("mode" => $mode, 
								 "user" => $user,
								 "phone" => $phone,
								 "category" => $category,
								 "op" => $op
								);

	}

	if ($mode == "UPDATE"){
		$ex_no = "5188";
		$phone = "010-1234-0000" ;
		$category = "STAR";
		$op = "EX02";

		$data	=array("mode" => $mode, 
								 "ex_no" => $ex_no,
								 "phone" => $phone,
								 "category" => $category,
								 "op" => $op
								);
	}

	if ($mode == "DELETE"){
		$ex_no = "5188";
		$data	=array("mode" => $mode, 
								 "ex_no" => $ex_no
								);
	}


	$curl_session = curl_init($api_server);
	$opts = array(
		CURLOPT_POST => 1,
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
	$result = $res->result;
	$total = $res->total;
	$totalpage = $res->totalpage;

	echo "result : ".$result."<br>";
	echo "total : ".$total."<br>";
	echo "totalpage : ".$totalpage."<br>";

	for ($j = 0 ; $j < sizeof($res->record) ; $j++) {
		
		echo " SEQ : ".$res->record[$j]->SEQ;
		echo " EX_NO : ".$res->record[$j]->EX_NO;
		echo " EX_TYPE : ".$res->record[$j]->EX_TYPE;
		echo " M_NM : ".$res->record[$j]->M_NM;
		echo " M_PHONE : ".$res->record[$j]->M_PHONE;
		echo " M_HP : ".$res->record[$j]->M_HP;
		echo " TITLE : ".$res->record[$j]->TITLE."<br>";
		echo " THUMB_IMG : ".$res->record[$j]->THUMB_IMG;
		echo "<br></br>";

		/*
		SEQ, EX_NO, EX_TYPE, M_NM,
		M_PHONE, M_HP, M_JOB, M_JOB_ETC, AGE, OPER_NAME, OPER_DATE,
		TITLE, IS_HTML, CONTENTS, THUMB_IMG, MO_THUMB_IMG, DOC_NAME,
		HIT_CNT, REG_DATE
		*/

	}
?>

