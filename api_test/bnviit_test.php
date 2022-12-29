<?
	header("Content-Type: text/html; charset=UTF-8"); 

	function requestExList($mode, $category, $op, $page, $pagesize) {

	// list
		$mode = "LIST";

		$api_server = "https://bnviit.com/app_api/index_table.php";

		$data	=array("mode" => $mode, 
								 "category" => $category,
								 "op" => $op,
								 "page" => $page,
								 "pagesize" => $pagesize
								);

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
			return $return_data;
		}

	}

	$mode = "LIST";
	
	if ($mode == "LIST") {
		$page = "1";
		$pagesize = "20";
		$category = "STAR|BASIC";
		$po = "EX01|EX02";
	}

	if ($mode == "USER"){
		$user = "장예원" ;
		$phone = "" ;
	}

	if ($mode == "INSERT"){
		$user = "테스트_홍길동" ;
		$phone = "010-1234-5678" ;
		$category = "BASIC";
		$op = "EX01";
	}

	if ($mode == "UPDATE"){
		$user = "테스트_홍길동" ;
		$phone = "010-1234-5678" ;
		$category = "STAR";
		$op = "EX03";
		$ex_no = "";
	}

	if ($mode == "DELETE"){
		$ex_no = "";
	}

	$res = requestExList($mode, $category, $op, $page, $pagesize);

	$res = json_decode($res);
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
		echo " THUMB_IMG : <img width=300px' src='http://bnviit.com/upload_data/exboard/".$res->record[$j]->EX_TYPE."/".$res->record[$j]->THUMB_IMG."'>";
		echo "<br></br>";

		/*
		SEQ, EX_NO, EX_TYPE, M_NM,
		M_PHONE, M_HP, M_JOB, M_JOB_ETC, AGE, OPER_NAME, OPER_DATE,
		TITLE, IS_HTML, CONTENTS, THUMB_IMG, MO_THUMB_IMG, DOC_NAME,
		HIT_CNT, REG_DATE
		*/

	}

?>

