<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_ok.php
	// 	Description : 인증 페이지
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "../dbconn.inc";

	function alertMsg ($strMsg) {
		echo "<script langauge=\"JavaScript\">\n
			alert('$strMsg');
			history.back();
			</script>\n";
		exit;
	}

	function no_cache () {
		Header("Cache-Control: no-cache, must-revalidate");
		Header("Pragma: no-cache");
		Header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
	}

	no_cache();

	$query = "select id, Email, UserName, temp1 from tb_admin where id = '$adminid' and passwd = '$adminpasswd'";
	#echo $query;
	$result = mysql_query($query);

	session_start();

	if ($row = mysql_fetch_row($result)){

		session_register("s_adm_id"); 
		session_register("s_adm_email"); 
		session_register("s_adm_name"); 
		session_register("s_flag"); 

		$s_adm_id = $row[0];
		$s_adm_email = $row[1];
		$s_adm_name = $row[2];
		$s_flag = $row[3];

		mysql_close($connect);

	} else {
		alertMsg("아이디 또는 비밀번호가 일치하지 않습니다.                                  \\n\\n");
		mysql_close($connect);
		exit;
	}

?>
<html>
<script language="javascript">
	document.location = "admin_main.php";
</script>
</html>