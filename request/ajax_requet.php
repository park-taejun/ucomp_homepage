<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";
	$conn = db_connection("w");

	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/request/request.php";
	require "../_process/mailform.php";

	$mode							= trim($_POST['mode']);
	$request_cate			= trim($_POST['request_cate']);
	$request_name			= trim($_POST['req_name']);
	$req_tel01				= trim($_POST['req_tel01']);
	$req_tel02				= trim($_POST['req_tel02']);
	$req_tel03				= trim($_POST['req_tel03']);
	$req_email01			= trim($_POST['req_email01']);
	$req_email02			= trim($_POST['req_email02']);
	$request_title		= trim($_POST['req_title']);
	$request_contents	= trim($_POST['req_contents']);
	$file_01					= trim($_POST['file_01']);
	
	if (($request_title == "") || ($request_title == " ")) {
		echo "<script>alert('제목이 없습니다!');history.back()</script>";
		exit();
	}

	$request_name			= SetStringToDB($request_name);
	$request_title		= SetStringToDB($request_title);
	$request_contents	= SetStringToDB($request_contents);

	$request_tel			= $req_tel01."-".$req_tel02."-".$req_tel03;
	$request_email		= $req_email01."@".$req_email02;
	$reply_state			= "0";

#===================================================================================
		$savedir1 = $g_physical_path."upload_data/request/";
#===================================================================================

///* recaptcha 추가
	$captcha = $_POST['g-recaptcha'];
	$secretKey = '6Ld87m0aAAAAAB9lDOVUTCfVTZiTBOQr68HihTu2'; // 위에서 발급 받은 "비밀 키"를 넣어줍니다.
	$ip = $_SERVER['REMOTE_ADDR']; // 옵션값으로 안 넣어도 됩니다.

	$data = array(
		'secret' => $secretKey,
		'response' => $captcha,
		'remoteip' => $ip  // ip를 안 넣을거면 여기서도 빼줘야죠
	);

	$url = "https://www.google.com/recaptcha/api/siteverify";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$response = curl_exec($ch);
	curl_close($ch);

	$responseKeys = json_decode($response, true);

	if ($responseKeys["success"]) {
		// 스팸 검사가 통과 했을 때의 처리

		if ($mode == "I") {

			$file_01_name	= upload($_FILES[file_01], $savedir1, 100 , array('jpg','gif','png','jpeg','xls','xlsx','zip','doc','docx','hwp','pdf','ppt','pptx'));
			$file_rnm			= $_FILES[file_01][name];

			$arr_data = array("REQUEST_CATE"=>$request_cate,
												"REQUEST_NAME"=>$request_name,
												"REQUEST_TEL"=>$request_tel,
												"REQUEST_EMAIL"=>$request_email,
												"REQUEST_TITLE"=>$request_title,
												"REQUEST_IP"=>$_SERVER["REMOTE_ADDR"],
												"REQUEST_CONTENTS"=>$request_contents,
												"FILE_NM"=>$file_01_name,
												"FILE_RNM"=>$file_rnm,
												"REPLY_STATE"=>$reply_state,
												"USE_TF"=>"Y",
												"DEL_TF"=>"N"
											);

			$result = insertRequest($conn, $arr_data);


			if ($result) {
				
				$SUBJECT	= "고객문의가 접수 되었습니다.";
				$mailto		= $request_email;
				// $EMAIL		= "park@ucomp.co.kr";
				$EMAIL		= "cadt@ucomp.co.kr";
				$NAME			= "유컴패니온";

				$CONTENT  = $mail_string;

				$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);

				$SUBJECT	= "고객문의가 접수 되었습니다.";
				// $mailto		= "park@ucomp.co.kr";
				$mailto		= "cadt@ucomp.co.kr";
				$EMAIL		= $request_email;
				$NAME			= $request_name;

				$CONTENT  = $mail_string;

				$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);

				$SUBJECT	= "고객문의가 접수 되었습니다.";
				// $mailto		= "management@ucomp.co.kr";
				$mailto		= "cadt@ucomp.co.kr";
				$EMAIL		= $request_email;
				$NAME			= $request_name;

				$CONTENT  = $mail_string;

				$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);

				$SUBJECT	= "고객문의가 접수 되었습니다.";
				// $mailto		= "kweonsehee@ucomp.co.kr";  //권세희책임께 메일 발송 요청 처리 2022-04-22
				$mailto		= "cadt@ucomp.co.kr";
				$EMAIL		= $request_email;
				$NAME			= $request_name;

				$CONTENT  = $mail_string;

				$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);

				$SUBJECT	= "고객문의가 접수 되었습니다.";
				// $mailto		= "ucomp_contact@ucomp.co.kr";
				$mailto		= "cadt@ucomp.co.kr";
				$EMAIL		= $request_email;
				$NAME			= $request_name;

				$CONTENT  = $mail_string;

				$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);

				$SUBJECT	= "고객문의가 접수 되었습니다.";
				// $mailto		= "jk0601@ucomp.co.kr";
				$mailto		= "cadt@ucomp.co.kr";
				$EMAIL		= $request_email;
				$NAME			= $request_name;
 
				$CONTENT  = $mail_string;

				$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);

				// echo "<script>alert('문의가 접수되었습니다.');location.href='http://www.ucomp.co.kr/request';</script>";
				echo "<script>alert('문의가 접수되었습니다.');location.href='http://admin.ucomp.co.kr/request/USR.006.html';</script>";
			}

		}

	} else {
		// 스팸 검사가 실패 했을 때의 처리
		echo "F";
	}

// recaptcha 종료 */
	mysql_close($conn);
?>