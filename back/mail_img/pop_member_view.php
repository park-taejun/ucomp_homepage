<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";

	function str_cut($str,$len){
		$slen = strlen($str);
		if (!$str || $slen <= $len) $tmp = $str;
		else	$tmp = preg_replace("/(([\x80-\xff].)*)[\x80-\xff]?$/", "\\1", substr($str,0,$len))."...";
		return $tmp;
	}


	$query = "select * from tb_member where id = '".$member_no."'";
	
#	echo $query;	

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$id = $list[id];
	$passwd = $list[passwd];
	$name = $list[name];
	$reg_no = $list[reg_no];
	$email = $list[email];
	$hpage = $list[hpage];
	$bname = $list[bname];
	$birth = $list[birth];
	$zip = $list[zip];
	$addr1 = $list[addr1];
	$addr2 = $list[addr2];
	$phone = $list[phone];
	$hphone = $list[hphone];
	$email_flag = $list[email_flag];
	$s_member_kind = $list[member_kind];
	$s_del_yn = $list[del_yn];
	$date_last_visit = $list[date_last_visit];
	$visit_count = $list[visit_count];
	$reg_date = $list[reg_date];
	$del_date = $list[del_date];
	
	$zip_ep = explode("-", $zip);
	$reg_no_ep = explode("-", $reg_no);
	
	if ($reg_date != null) {
		$date_s1 = date("Y-m-d [H:i]", strtotime($reg_date));
	} else {
		$date_s1 = "";
	}

	if ($date_last_visit != null) {
		$date_s2 = date("Y-m-d [H:i]", strtotime($date_last_visit));
	} else {
		$date_s2 = "";
	}

	if ($del_date != null) {
		$date_s3 = date("Y-m-d [H:i]", strtotime($del_date));
	} else {
		$date_s3 = "";
	}

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
		document.frm.action="member_list.php";
		document.frm.submit();
	}

	function goIn() {
						
		var frm = document.frm;

		if (frm.name.value == "") {
			alert("이름을 입력해 주세요.");
			frm.name.focus();
			return;
		}

		if (frm.passwd.value == "") {
			alert("비밀번호를 입력해 주세요.");
			frm.passwd.focus();
			return;
		}

		if (frm.reg_no.value == "") {
			alert("주민등록번호를 입력해 주세요.");
			frm.reg_no.focus();
			return;
		}

		if (frm.email.value == "") {
			alert("사용하실 이메일을 입력해 주세요.");
			frm.email.focus();
			return;
		}

		if (!isValidEmail(frm.email)) {
			alert("이메일을 정확히 입력해 주세요.");
			frm.email.focus();
			return;
		}

		if (frm.bname.value == "") {
			alert("아기이름을 입력해 주세요.");
			frm.bname.focus();
			return;
		}

		if (frm.birth.value == "") {
			alert("아기생일을 입력해 주세요.");
			frm.birth.focus();
			return;
		}

		if (frm.addr1.value == "") {
			alert("주소를 입력해 주세요.");
			NewWindow('/common/pop_postnum.html','pop','350','330','no');
			return;
		}

		if (frm.addr2.value == "") {
			alert("나머지 주소를 입력해 주세요.");
			frm.addr2.focus();
			return;
		}

		if (frm.hphone.value == "") {
			alert("휴대전화번호를 입력해 주세요.");
			frm.hphone.focus();
			return;
		}

		if (frm.email_flag.checked == true) {
			frm.m_flag.value = "Y";
		} else {
			frm.m_flag.value = "N";
		}

		frm.target = "frhidden";
		frm.action = "member_db.php"
		frm.submit();
			
		
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

	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
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
					<td height="30" style="padding-left:20px"><a href="#">회원관리</a> > <a href="#">회원관리</a> > <a href="#">조회</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<form name="frm" method="post" action="member_db.php">
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
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">회원 ID</td>
								<td style="padding-left:10px" colspan="3">
									<?echo $id?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">비밀번호</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="passwd" size="15" value="<?echo $passwd?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">회원명</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="name" size="15" value="<?echo $name?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">주민등록번호</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="reg_no" size="20" value="<?echo $reg_no?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">E-Mail</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="email" size="30" value="<?echo $email?>"> <a href="mailto:<?echo $email?>">메일보내기</a>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">홈페이지</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="hpage" size="30" value="<?echo $hpage?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">아기이름</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="bname" size="15" value="<?echo $bname?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">아기생일</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="birth" size="15" value="<?echo $birth?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">주소</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="zip1" size="4" value="<?echo $zip_ep[0]?>" readonly="1" onClick="NewWindow('/common/pop_postnum.html','pop','350','330','no');"> -
									<input type="text" name="zip2" size="4" value="<?echo $zip_ep[1]?>" readonly="1" onClick="NewWindow('/common/pop_postnum.html','pop','350','330','no');"><br>
									<input type="text" name="addr1" size="50" value="<?echo $addr1?>" readonly="1" onClick="NewWindow('/common/pop_postnum.html','pop','350','330','no');"><br>
									<input type="text" name="addr2" size="50" value="<?echo $addr2?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">연락처</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="phone" size="15" value="<?echo $phone?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">휴대전화</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="hphone" size="15" value="<?echo $hphone?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">E-Mail 수신여부</td>
								<td style="padding-left:10px" colspan="3">
									<input type="checkbox" name="email_flag" value="Y" <? if ($email_flag == "Y") echo "checked";?>> 수신
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">등록일</td>
								<td style="padding-left:10px" colspan="3">
									<b><?echo $date_s1?></b>&nbsp;&nbsp;<br>유료회원의 경우 유료 전환일자가 등록일 입니다.
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">회원종류</td>
								<td style="padding-left:10px" colspan="3">
									<select name="s_member_kind">
										<option value="F">무료회원</option>
										<option value="T">유료회원신청</option>
										<option value="P">유료회원</option>
									</select>
									<script language="javascript">document.frm.s_member_kind.value="<?echo $s_member_kind?>";</script>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">탈퇴구분</td>
								<td style="padding-left:10px" colspan="3">
									<select name="s_del_yn">
										<option value="N">활동회원</option>
										<option value="Y">탈퇴회원</option>
									</select>
									<script language="javascript">document.frm.s_del_yn.value="<?echo $s_del_yn?>";</script>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">탈퇴일</td>
								<td style="padding-left:10px" colspan="3">
									<?echo $date_s3?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td height="10"></td>
							</tr>
							<!--
							<tr>
								<td colspan="4" align="center">
									<a href="javascript:goIn();"><img src="images/button_save.gif" border="0"></a>&nbsp;
									<a href="javascript:document.frm.reset();"><img src="images/button_cancle.gif" border="0"></a>&nbsp;
									<a href="javascript:goBack();"><img src="images/button_list_01.gif" border="0"></a>&nbsp;
								</td>
							</tr>
							-->
						</table>
					</td>
				</tr>
			</table>
<!-- Main Section End -->
		</td>
	</tr>
</table>
<input type="hidden" name="mode" value="mod">
<input type="hidden" name="m_flag" value="">
<input type="hidden" name="member_no" value="<?echo $member_no?>">
<input type="hidden" name="p_member_kind" value="<?echo $s_member_kind?>">
<input type="hidden" name="p_del_yn" value="<?echo $s_del_yn?>">
<INPUT type="hidden" name="member_kind" value="<?echo $member_kind?>">
<INPUT type="hidden" name="del_yn" value="<?echo $del_yn?>">
<INPUT type="hidden" name="idxfield" value="<?echo $idxfield?>">
<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="sort" value="<?echo $sort?>">
<input type="hidden" name="order" value="<?echo $order?>">
</form>
</body>
</html>
<?
	mysql_close($connect);
?>