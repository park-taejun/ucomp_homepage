<?

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";
		
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and com_name like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and name like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_contact where con_no is not null ".$que;
		$query2 = "select * from tb_contact where con_no is not null ".$que. " order by regDate desc";

	} else {
		$query = "select count(*) from tb_contact where con_no is not null ";
		$query2 = "select * from tb_contact where  con_no is not null order by regDate desc";
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
	document.frmSearch.id.value = id; 
	document.frmSearch.action= "contact_view.php";
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
		alert("삭제하실 견적을 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "contact_db.php";
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
					<td height="30" style="padding-left:20px"><a href="#">사이트 관리</a> > <a href="#">Contact us</a> > <a href="#">리스트</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
 				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30">
						<img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Form --->					
						<FORM name="frmSearch" method="post" action="contact_list.php">
						<table width="95%" border="0" cellpadding="0" cellspacing="0" class="body">
							<tr height="2" bgcolor="#CCCCCC"> 
								<td></td>
							</tr>
							<tr height="32"> 
								<td align="right" width="100%" style="padding-left:5px" bgcolor="#F1F1F1">
									<SELECT NAME="idxfield">
										<OPTION VALUE="0">회사명</OPTION>
										<OPTION VALUE="1">담당자명</OPTION>
									</SELECT>
									<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
									<INPUT TYPE="submit" VALUE="검색">
									<!--
									<a href="javascript:goIn();"><img src="images/button_write.gif" border="0" align="absmiddle"></a>
									<a href="javascript:goDel();"><img src="images/button_delete.gif" border="0" align="absmiddle"></a>&nbsp;
									-->
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
								<td>번 호</td>
								<td>회사명</td>
								<td>담당자명</td>
								<td>전화번호</td>
								<td>휴대전화번호</td>
								<td>이메일</td>
								<td>등록일</td>
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
	
							?>	
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<!--<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->con_no?>"></TD>-->
								<TD><A HREF="javascript:onView('<?echo $obj->con_no?>')"><?echo $obj->con_no?></A></TD>
								<TD><?echo $obj->com_name?></TD>
								<TD><?echo $obj->name?></TD>
								<TD><?echo $obj->phone1?>-<?echo $obj->phone2?>-<?echo $obj->phone3?></TD>
								<TD><?echo $obj->hphone1?>-<?echo $obj->hphone2?>-<?echo $obj->hphone3?></TD>
								<TD><a href="mailto:<?echo $obj->email?>"><?echo $obj->email?></a></TD>
								<TD><?echo $date_s?></TD>
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

							if ($TotalArticle > $ListArticle)
							{

								if ($page != 1)
										echo "[<a href='".$PHP_SELF."?page=1&BoardId=$BoardId&idxfield=$idxfield&qry_str=$qry_str'>맨앞</a>]";
								// 이전페이지
								if (($TotalArticle + 1) > ($ListArticle * $PageScale))
								{
									$PrevPage = ($Scale - 1) * $PageScale;

									if ($PrevPage >= 0)
											echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&BoardId=$BoardId&qry_str=$qry_str'>이전".$PageScale."개</a>]";
								}

								echo "&nbsp;";

								// 페이지 번호
								for ($vj = 0; $vj < $PageScale; $vj++)
								{
							//		$ln = ($Scale * $PageScale + $vj) * $ListArticle + 1;
									$vk = $Scale * $PageScale + $vj + 1;
									if ($vk < $TotalPage + 1)
									{
										if ($vk != $page)
												echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&BoardId=$BoardId&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
										else
											echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
									}

									
								}

								echo "&nbsp;";
								// 다음 페이지
								if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
								{
									$NextPage = ($Scale + 1) * $PageScale + 1;
										echo "[<a href='".$PHP_SELF."?page=".$NextPage."&BoardId=$BoardId&idxfield=$idxfield&qry_str=$qry_str'>이후".$PageScale."개</a>]";
								}

								if ($page != $TotalPage)
										echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&BoardId=$BoardId&idxfield=$idxfield&qry_str=$qry_str'>맨뒤</a>]&nbsp;&nbsp;";
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
<input type="hidden" name="BoardId" value="<?echo $BoardId?>">
<input type="hidden" name="id" value="">
<input type="hidden" name="mode" value="del">
</form>
</body>
</html>
<?
mysql_close($connect);
?>