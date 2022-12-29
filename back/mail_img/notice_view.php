<?
	include "admin_session_check.inc";
	include "../dbconn.inc";

	$query = "select * from tb_board where BoardId = '".$BoardId."' and SeqNo = '".$id."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$SeqNo = $list[SeqNo];
	$BoardId = $list[BoardId];
	$Title = $list[Title];
	$Content = $list[Content];
	$Image = $list[Image];

?>
<?	$_mode = "view"; ?>
<?	include "./inc/notice_title.inc"; ?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="inc/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="notice_list.php";
		document.frm.submit();
	}

	function goIn() {
				
		if (document.frm.Title.value == "") {
			alert("제목를 입력하세요.");
			document.frm.Title.focus();
			return;
		}

		if (document.frm.Content.value == "") {
			alert("내용을 입력하세요.");
			document.frm.Content.focus();
			return;
		}
	
		document.frm.target = "frhidden";
		document.frm.action = "notice_db.php";
		document.frm.submit();
		
	}

//-->
</SCRIPT>
</HEAD>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
	<tr>
		<td height="30" style="padding-left:20px"><a href="#">게시판 관리</a> > <a href="#"><?echo $_menu_str?></a> > <a href="#"><?echo $_mode_str?></a></td>
	</tr>
	<tr>
		<td height="1" bgcolor="#CCCCCC"></td>
	</tr>
</table>

<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
<!-- Form -->				
				<form name='frm' method='post' action='notice_db.php'>
				<input type="hidden" name="page" value="<?echo $page?>">
				<input type="hidden" name="mode" value="mod">
				<input type="hidden" name="id" value="<?echo $SeqNo?>">
				<input type="hidden" name="idxfield" value="<?echo $idxfield?>">
				<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
				<input type="hidden" name="BoardId" value="<?echo $BoardId?>">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td>
									* <?echo $_menu_str?> 수정 화면 입니다.<br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">제 목</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="Title" size="90" value="<?echo $Title?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">내 용</td>
								<td style="padding-left:10px" colspan="3">
									<textarea name="Content" style="width:690px; height:200px"><?echo $Content?></textarea>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<a href="javascript:goIn();"><img src="images/button_modify.gif" border="0"></a>&nbsp;
									<a href="javascript:goBack();"><img src="images/button_list_01.gif" border="0"></a>&nbsp;
									<!--<a href="javascript:goPre();"><img src="images/button_ok.gif" border="0"></a>&nbsp;-->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</form>
			</table>
<!-- Main Section End -->
		</td>
	</tr>
</table>
</form>
</body>
</html>
<?
mysql_close($connect);
?>
