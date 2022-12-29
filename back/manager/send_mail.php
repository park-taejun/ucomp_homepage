<?
	require "../_classes/com/util/Util.php";

	function sendMail2($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto){ //비밀번호찾기 메일

		//ini_set("sendmail_from","admin@goupp.org");
		$admin_email = $EMAIL;
		$admin_name  = iconv("UTF-8","EUC-KR",$NAME);
		$SUBJECT		 = iconv("UTF-8","EUC-KR",$SUBJECT);
		$CONTENT		 = iconv("UTF-8","EUC-KR",$CONTENT);
		//	$mcontent=file_get_contents("idpwcheck_mail.html");
		//	$contents=str_replace("###password###",$CONTENT,$mcontent); 

		$header = "Return-Path:".$admin_email."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/html; charset=euc-kr\r\n";
		$header .= "X-Mailer: PHP\r\n";
		$header .= "Content-Transfer-Encoding: 8bit\r\n";
		$header .= "From: ".$admin_name."<".$admin_email.">\r\n";
		$header .= "Reply-To: DSR Company<".$admin_email.">\r\n";
		$subject  = $SUBJECT;
		$contents = $CONTENT;

		$message = $contents;
		//$message = base64_encode($contents);
		flush();
		mail($mailto, $subject, $message, $header, '-f'.$admin_email);
	}

	function makeSpace($str, $length) {
		
		if (strlen($str) > $length) {
			$str = left($str, $length);
		}

		$space = "^";
		$ret = "";
		$temp = "";

		for ($j = 0; $j < ($length - strlen($str)) ; $j++) {
			$temp = $temp.$space;
		}
		
		$ret = $str.$temp;


		return $ret;
	}

	$temp = "1234567890";

	echo makeSpace($temp, 5);


	$EMAIL		= "park@ucomp.co.kr";
	$NAME			= "박찬호";
	$SUBJECT	= "메일 입니다.";
	$CONTENT	= "메일 내용 입니다.";
	$mailto		= "park@ucomp.co.kr";

	$send_flag = sendMail2($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);
?>