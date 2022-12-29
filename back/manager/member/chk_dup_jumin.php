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

	$m_reg01			= $_POST['jumin_01'];
	$m_reg02			= $_POST['jumin_02'];
	$m_reg01	= trim(strip_tags(mysql_escape_string($m_reg01)));
	$m_reg02	= trim(strip_tags(mysql_escape_string($m_reg02)));

	$strJumin= $m_reg01.$m_reg02;		// 주민번호
	$strDupJumin= $m_reg01."-".$m_reg02;		// 주민번호

	// 중복 체크
	$enc_strJumin = encrypt($key, $iv, $strDupJumin);

	$enc_strJumin01 = encrypt($key, $iv, $m_reg01);
	$enc_strJumin02 = encrypt($key, $iv, $m_reg02);

	$result = dupCheckMember($conn, "JUMIN", $enc_strJumin);

	if ($result == "T") {
		$iReturnCode = "NON";
	} else {
		$iReturnCode = "DUP";
	}

	echo $iReturnCode;
	
	mysql_close($conn);
?>