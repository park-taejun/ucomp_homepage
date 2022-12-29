<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
#	include "../dbconn.inc";

	function str_cut($str,$len){
		$slen = strlen($str);
		if (!$str || $slen <= $len) $tmp = $str;
		else	$tmp = preg_replace("/(([\x80-\xff].)*)[\x80-\xff]?$/", "\\1", substr($str,0,$len))."...";
		return $tmp;
	}

	$oid = trim($oid);	
	$oid_sub = trim($oid_sub);	

?>		
<HTML>
<HEAD>
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
<title><?echo $g_site_title?></title>
<SCRIPT language="javascript">
<!--

	function goIn() {
						
		var frm = document.frm;

		if (frm.title.value == "") {
			alert("제목을 입력해 주세요.");
			frm.title.focus();
			return;
		}

		if (frm.name.value == "") {
			alert("작성자를 입력해 주세요.");
			frm.name.focus();
			return;
		}

		if (frm.Lfile.value == "") {
			alert("리스트 이미지를 선택하세요.");
			frm.Lfile.focus();
			return;
		}

		if (frm.Bfile.value == "") {
			alert("시안 이미지를 선택하세요.");
			frm.Bfile.focus();
			return;
		}

		if (frm.memo.value == "") {
			alert("메모(전달 사항) 입력해 주세요.");
			frm.memo.focus();
			return;
		}

		frm.action = "sian_db.php"
		frm.submit();
			
		
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
					<td height="30" style="padding-left:20px"><a href="#">주문관리</a> > <a href="#">주문상세</a> > <a href="#">시안등록</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<form name="frm" method="post" action="sian_db.php" enctype='multipart/form-data'>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">제목</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="title" size="45" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">작성자</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="name" size="15" value="<?echo $s_adm_name?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">리스트이미지</td>
								<td style="padding-left:10px" colspan="3">
									<input type="file" name="Lfile" size="30" >
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">시안이미지</td>
								<td style="padding-left:10px" colspan="3">
									<input type="file" name="Bfile" size="30" >
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">메모</td>
								<td style="padding-left:10px" colspan="3">
									<textarea name="memo" style="width:250px; height:60px"></textarea>
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
									<a href="javascript:goIn();"><img src="images/button_save.gif" border="0"></a>&nbsp;
									<a href="javascript:self.close();"><img src="images/button_cancle.gif" border="0"></a>&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
<!-- Main Section End -->
		</td>
	</tr>
</table>
<input type="hidden" name="oid" value="<?echo $oid?>">
<input type="hidden" name="oid_sub" value="<?echo $oid_sub?>">
</form>
</body>
</html>
<?
	mysql_close($connect);
?>