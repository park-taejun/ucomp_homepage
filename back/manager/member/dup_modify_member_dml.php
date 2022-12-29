<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/db/DBUtil.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/com/etc/etc.php";

	$conn = db_connection("w");
	require "../../_classes/biz/member/member.php";

	$mode					= $_POST['mode'];
	$m_no					= $_POST['m_no'];
	$m_name				= $_POST['m_name'];
	$m_reg01			= $_POST['m_reg01'];
	$m_reg02			= $_POST['m_reg02'];

	$m_name		= trim(strip_tags(mysql_escape_string($m_name)));
	$m_reg01	= trim(strip_tags(mysql_escape_string($m_reg01)));
	$m_reg02	= trim(strip_tags(mysql_escape_string($m_reg02)));

	$strDupJumin= $m_reg01."-".$m_reg02;		// 주민번호

	// 중복 체크
	$enc_strJumin = encrypt($key, $iv, $strDupJumin);

	$result = dupCheckModifyMember($conn, "JUMIN", $enc_strJumin, $m_no);
		
	if ($result == "T") {
		$iReturnCode = "T";
	} else {
		$iReturnCode = "DUP";
	}

	echo $iReturnCode;
	
	mysql_close($conn);
?>