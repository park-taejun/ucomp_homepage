<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_top.php
	// 	Description : 
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";

	$sYY = date(Y);
	$sMM = date(m);
	$sDD = date(d);

	$sHour = date(H);
	$sMin = date(i);
	$sSec = date(s);

	$today = $sYY."-".$sMM."-".$sDD;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
</head>
<body>
<br><br>
<br><br>
<center>
<TABLE width="100%">
<TR>
	<TD align="center"><B><?echo $g_site_name?> 관리자 시스템에 오신것을 환영 합니다. [<?echo $today?>]</B></TD>
</TR>
</TABLE>
</center>
</body>
</html>