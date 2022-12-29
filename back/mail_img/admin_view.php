<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_view.php
	// 	Description : 관리자 보기 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";


	if (empty($id)) {
		$id = $s_adm_id;
	}

	$query = "select * from tb_admin where id = '".$id."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$id = $list[id];
	$passwd = $list[passwd];
	$UserName = $list[UserName];
	$UserInfo = $list[UserInfo];
	$Phone1 = $list[Phone1];
	$Phone2 = $list[Phone2];
	$Email = $list[Email];
	$temp1 = $list[temp1];
	
	$query = "select group_id, group_name  from tb_admin_group order by group_id";
	$result = mysql_query($query);
	
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
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="admin_list.php";
		document.frm.submit();
	}

	function goIn() {
				
		if (document.frm.passwd.value == "") {
			alert("비밀번호를 입력하세요.");
			document.frm.passwd.focus();
			return;
		}
		
		if (document.frm.UserName.value == "") {
			alert("관리자 성명을 입력하세요.");
			document.frm.UserName.focus();
			return;
		}

		if (document.frm.Email.value == "" ) {
			alert("이메일을 입력해 주세요.");
			document.frm.Email.focus();
			return;
		}

		if (!isValidEmail(document.frm.Email)) {
			alert("이메일을 정확히 입력해 주세요.");
			document.frm.Email.focus();
			return;
		}
	
		document.frm.target = "frhidden";
		document.frm.action = "admin_db.php";
		document.frm.submit();
		
	}

	function isValidEmail(input) {
//    var format = /^(\S+)@(\S+)\.([A-Za-z]+)$/;
    	var format = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
    	return isValidFormat(input,format);
	}
	
	function isValidFormat(input,format) {
    	if (input.value.search(format) != -1) {
        	return true; //올바른 포맷 형식
    	}
    	return false;
	}

	function containsCharsOnly(input,chars) {
    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
           		return false;
    	}
    	return true;	
	}

	function isNumber(input) {
    	var chars = "0123456789";
    	return containsCharsOnly(input,chars);
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
					<td height="30" style="padding-left:20px"><a href="#">관리자권한 관리</a> > <a href="#">관리자 관리</a> > <a href="#">수정</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<form name='frm' method='post' action='admin_db.php'>				
				<INPUT type="hidden" name="id" value="<?echo $id?>">
				<input type="hidden" name="mode" value="mod">
				<input type="hidden" name="idxfield" value="<?echo $idxfield?>">
				<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
				<input type="hidden" name="page" value="<?echo $page?>">
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
									* 관리자정보를 수정하는 화면 입니다.<br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">관리자 그룹</td>
								<td style="padding-left:10px" colspan="3">
									<select name="temp1">
								<?
									while($row = mysql_fetch_array($result)) {
										if (trim($temp1) == $row[group_id]) {
								?>
										<option value="<?echo $row[group_id]?>" selected><?echo $row[group_name]?></option>
								<?
										} else {
								?>
										<option value="<?echo $row[group_id]?>"><?echo $row[group_name]?></option>
								<?
										}	
									}
								?>
									</select>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">관리자 ID</td>
								<td style="padding-left:10px" colspan="3">
									<?echo $id?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">Password</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="passwd" size="15" value="<?echo $passwd?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">관리자 성명</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="UserName" size="15" value="<?echo $UserName?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">관리자 설명</td>
								<td style="padding-left:10px" colspan="3">
									<textarea name="UserInfo" cols="60" rows="3"><?echo $UserInfo?></textarea>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">연락처 1</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="Phone1" size="15" value="<?echo $Phone1?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">연락처 2</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="Phone2" size="15" value="<?echo $Phone2?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">E-Mail</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="Email" size="25" value="<?echo $Email?>">
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
									<a href="javascript:goBack();"><img src="images/button_list_01.gif" border="0"></a>&nbsp;
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
</form>
</body>
</html>
<?
	mysql_close($connect);
?>