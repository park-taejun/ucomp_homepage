<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_ok.php
	// 	Description : 어드민 메인
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	session_start();

	include "./inc/global_init.inc";

	if(!session_is_registered("s_adm_id")){
?>
	<script language="javascript">
		alert("세션이 종료 되어 다시 로그인 하셔야 합니다.");
		document.location="index.php";	
	</script>
<?
	}

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<TITLE><?echo $g_site_title?></TITLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
	var queryString;
	var queryStringHis;
	var queryStringView;
-->
</SCRIPT>
</HEAD>
<FRAMESET ROWS="0,52,*" BORDER="0" frameborder="no" framespacing="1">
	<FRAME SRC="frhidden.html" NAME="frhidden" NORESIZE MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="no"> 
	<FRAME SRC="admin_top.php" NAME="frtop" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="no">
	<FRAMESET COLS="180, *" frameborder=no border=0 framespacing=0 >
		<FRAME SRC="admin_left.php" NAME="frleft" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="auto">
		<FRAME SRC="admin_body.php" NAME="frmain" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="auto">
	</FRAMESET>
    
	<NOFRAMES>
	<BODY bgcolor="white" text="black" link="blue" vlink="purple" alink="red">
	<p>이 페이지를 보려면, 프레임을 볼 수 있는 브라우저가 필요합니다.</p>
	</BODY>
	</NOFRAMES>
</FRAMESET>
</HTML>