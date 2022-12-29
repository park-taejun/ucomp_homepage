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
	
	$request_name			= SetStringToDB($request_name);
	$request_title		= SetStringToDB($request_title);
	$request_contents	= SetStringToDB($request_contents);

	$request_tel			= $req_tel01."-".$req_tel02."-".$req_tel03;
	$request_email		= $req_email01."@".$req_email02;
	$reply_state			= "0";

			
	$SUBJECT	= "고객문의가 접수 되었습니다.";
	$mailto		= "jk0601@ucomp.co.kr";
	$EMAIL		= "orion70kr@naver.com";
	//$EMAIL		= "park@ucomp.co.kr";
	$NAME			= "유컴패니온";

	$CONTENT  = "메일 수신 테스트";

	$mail_flag = sendMail($EMAIL, $NAME, $SUBJECT, $CONTENT, $mailto);


?>