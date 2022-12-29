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
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<LINK rel="stylesheet" HREF="inc/admin_girin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
</HEAD>
<BODY topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<BASE target="frmain">
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#E8E8E8">
	<TR height="45">
	<!--<TR height="45" bgcolor="#3AB2D0">-->
		<TD style="padding: 0 0 0 0"><a href="<?echo $g_site_url?>" target="new"><IMG src="images/logo.jpg" border=0 ></a></TD>
		<TD align="right">
			<a href="admin_view.php"><font color="navy">자기정보수정</font></a><font color="navy"> | </font><a href="logout.php"><font color="navy">로그아웃</font></a>&nbsp;&nbsp;
		</TD>
	</TR>
	<tr>
		<td colspan="2" height="2" bgcolor="#CACACA"></td>
	</tr>
</TABLE>
</BODY>
</HTML>