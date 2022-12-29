<?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn.inc";
	
	#echo $source;	
	
	$sel_source = trim($sel_source);

	if ($sel_source <> "") {
			$que = " and source = '$sel_source' ";	
	}
	
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = $que." and Title like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = $que." and eng_Title like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_other_board a, tb_code_detail b where a.source = b.dcode and  a.BoardId = '".$BoardId."' ".$que;
		$query2 = "select * from tb_other_board a, tb_code_detail b where a.source = b.dcode and  a.BoardId = '".$BoardId."' ".$que. " order by SeqNo desc";

	} else {
		$query = "select count(*) from tb_other_board a, tb_code_detail b where a.source = b.dcode and  a.BoardId = '".$BoardId."' ".$que;
		$query2 = "select * from tb_other_board a, tb_code_detail b where a.source = b.dcode and  a.BoardId = '".$BoardId."' ".$que. " order by SeqNo desc";
	}
	
	#echo $query2;
	
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
	document.frmSearch.id.value = id; 
	document.frmSearch.action= "movie_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "movie_input.php";
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
		alert("삭제하실 자료를 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "movie_db.php";
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

function setBshow(iBshow, num) {
	document.frmSearch.bshow.value = iBshow;
	document.frmSearch.mode.value = "bshow";
	document.frmSearch.id.value = num; 
	document.frmSearch.action= "movie_db.php";
	document.frmSearch.submit();
}

function setBshow2(iBshow, num) {
	document.frmSearch.eng_bshow.value = iBshow;
	document.frmSearch.mode.value = "eng_bshow";
	document.frmSearch.id.value = num; 
	document.frmSearch.action= "movie_db.php";
	document.frmSearch.submit();
}

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="init();">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->

<?	$_mode = "list"; ?>
<?	include "../inc/other_title.inc"; ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
	<tr>
		<td height="30" style="padding-left:20px"><a href="#">연구실적관리</a> > <a href="#"><?echo $_menu_str?></a> > <a href="#"><?echo $_mode_str?></a></td>
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
						<img src="../images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Form --->					
					<FORM name="frmSearch" method="post" action="movie_list.php">
						<table width="95%" border="0" cellpadding="0" cellspacing="0" class="body">
							<tr height="2" bgcolor="#CCCCCC"> 
								<td colspan="2"></td>
							</tr>
							<tr height="32"> 
								<td width="20%" style="padding-left:5px" bgcolor="#F1F1F1">
									<select name="sel_source">
										<option value="">분류 선택</option>
										<?
											$query = "select * from tb_code_detail where pcode='KIND' and view_chk = 'Y' order by dcode_seq ";
											$result = mysql_query($query);
	
  										while($row = mysql_fetch_array($result)) {				
									
												$dcode = $row[dcode];
												$dcode_no = $row[dcode_no];
												$dcode_name = $row[dcode_name];
										?>
										<option value="<?echo $dcode?>" <? if ($dcode == $sel_source) echo "selected"; ?>><?echo $dcode_name?></option>
										<?
											}
										?>
									</select>									
								</td>
								<td align="right" width="80%" style="padding-right:5px" bgcolor="#F1F1F1">
									<SELECT NAME="idxfield">
										<OPTION VALUE="0">제 목</OPTION>
										<OPTION VALUE="1">영문제목</OPTION>
									</SELECT>&nbsp;
									<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
									<a href="javascript:document.frmSearch.submit();"><img src="../images/button_serch.gif" border="0" align="absmiddle"></a>
									<a href="javascript:goIn();"><img src="../images/button_write.gif" border="0" align="absmiddle"></a>
									<a href="javascript:goDel();"><img src="../images/button_delete.gif" border="0" align="absmiddle"></a>
								</td>
							</tr>
							<tr height="2" bgcolor="#CCCCCC"> 
								<td  colspan="2"></td>
						    </tr>
						</table>
						    <!-- 검색조건 끝 -->
						<br>
						<table cellpadding="0" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="2" bgcolor="#000000" colspan="8"></td>
							</tr>
							<tr align="center" bgcolor="#E9E9E9" height="25">
								<td width="3%">&nbsp;</td>
								<td width="7%">번호</td>
								<td width="27%">제목</td>
								<td width="10%">제작일</td>
								<td width="20%">분 류</td>
								<td width="15%">등록일</td>
								<td width="8%">보이기</td>
								<td width="8%">영문보이기</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="8"></td>
							</tr>
							<?
								$result2 = mysql_query($query2);

								if ($TotalArticle) {

									for ($i = 0; $i < $Last; ++$i) {
										mysql_data_seek($result2,$i);
										$obj = mysql_fetch_object($result2);

										if ($i >= $First) {
				
											$date_s = date("Y-m-d [H:i]", strtotime($obj->RegDate));
											$date_in = date("Y-m-d", strtotime($obj->in_date));
	
							?>	
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->SeqNo?>"></td>
								<td><?echo $obj->SeqNo?></td>
								<td align="left" style="padding: 0 0 0 10"><A HREF="javascript:onView('<?echo $obj->SeqNo?>')"><?echo $obj->Title?></a></td>
								<td><?echo $date_in?></td>
								<td><?echo $obj->dcode_name?></td>
								<!--<td><img src="/<?echo $_path_str?>/<?echo $obj->FileName01?>" border="0"></td>-->
								<td><?echo $date_s?></td>
								<td>
							<?
											if ($obj->bshow == "") {
												$t_bshow = "0";
											} else {
												$t_bshow = $obj->bshow; 
											}
	
											if ($t_bshow == "0") {
												echo "<a href='javascript:setBshow(1,$obj->SeqNo);'><img src='../images/ico_show0.gif' border=0></a>";
											} else {
												echo "<a href='javascript:setBshow(0,$obj->SeqNo);'><img src='../images/ico_show1.gif' border=0></a>";
											}
							?>	
								</td>
								<td>
							<?
											if ($obj->eng_bshow == "") {
												$t_bshow = "0";
											} else {
												$t_bshow = $obj->eng_bshow; 
											}
	
											if ($t_bshow == "0") {
												echo "<a href='javascript:setBshow2(1,$obj->SeqNo);'><img src='../images/ico_show0.gif' border=0></a>";
											} else {
												echo "<a href='javascript:setBshow2(0,$obj->SeqNo);'><img src='../images/ico_show1.gif' border=0></a>";
											}
							?>	
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="8"></td>
							</tr>							
							<?
										}
									}
								}
							?>
							<tr>
								<td align="center" colspan="8">
								
							<?
								$Scale = floor(($page - 1) / $PageScale);

								if ($TotalArticle > $ListArticle) {
									if ($page != 1)
										echo "[<a href='".$PHP_SELF."?page=1&BoardId=$BoardId&sel_source=$sel_source&idxfield=$idxfield&qry_str=$qry_str'>맨앞</a>]";
									
									// 이전페이지
									if (($TotalArticle + 1) > ($ListArticle * $PageScale)) {
										$PrevPage = ($Scale - 1) * $PageScale;
										if ($PrevPage >= 0)
											echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&BoardId=$BoardId&sel_source=$sel_source&qry_str=$qry_str'>이전".$PageScale."개</a>]";
									}
									echo "&nbsp;";
									// 페이지 번호
									for ($vj = 0; $vj < $PageScale; $vj++) {
									//		$ln = ($Scale * $PageScale + $vj) * $ListArticle + 1;
										$vk = $Scale * $PageScale + $vj + 1;
										if ($vk < $TotalPage + 1) {
											if ($vk != $page)
												echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&sel_source=$sel_source&idxfield=$idxfield&BoardId=$BoardId&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
											else
												echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
										}
									}
										
									echo "&nbsp;";
									// 다음 페이지
									if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale)) {
										$NextPage = ($Scale + 1) * $PageScale + 1;
										echo "[<a href='".$PHP_SELF."?page=".$NextPage."&BoardId=$BoardId&sel_source=$sel_source&idxfield=$idxfield&qry_str=$qry_str'>이후".$PageScale."개</a>]";
									}
										
									if ($page != $TotalPage)
										echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&BoardId=$BoardId&sel_source=$sel_source&idxfield=$idxfield&qry_str=$qry_str'>맨뒤</a>]&nbsp;&nbsp;";
								} else 
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
<input type="hidden" name="bshow" value="">
<input type="hidden" name="eng_bshow" value="">
<input type="hidden" name="mode" value="del">
</form>
</body>
</html>
<?
mysql_close($connect);
?>