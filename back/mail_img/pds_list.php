<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and title like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and ( title like '%$qry_str%' or data like '%$qry_str%' ) ";
		} 
		
		$query = "select count(*) from tb_bbs where code = '".$code."' ".$que;
		$query2 = "select * from tb_bbs where code = '".$code."' ".$que. " order by po";

	} else {
		$query = "select count(*) from tb_bbs where code = '".$code."'";
		$query2 = "select * from tb_bbs where code = '".$code."' order by po";
	}

	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$ListArticle = 15;
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


	#echo $query2;
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
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

function onView(no, re, po, de) {
	document.frmSearch.no.value = no; 
	document.frmSearch.re.value = re; 
	document.frmSearch.po.value = po; 
	document.frmSearch.de.value = de; 
	document.frmSearch.action= "pds_view.php";
	document.frmSearch.submit();
}

function onWrite() {
		document.frmSearch.action= "pds_input.php";
		document.frmSearch.submit();
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
					<? 
						$title_mode = "List";
						include "./inc/pds_title.inc"; 
					?>
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
					<FORM name="frmSearch" method="post">
						<table width="95%" border="0" cellpadding="0" cellspacing="0" class="body">
							<tr height="2" bgcolor="#CCCCCC"> 
								<td></td>
							</tr>
							<tr height="32"> 
								<td align="right" width="100%" style="padding-left:5px" bgcolor="#F1F1F1">
									<SELECT NAME="idxfield">
										<option value="0">제목</option>
										<option value="1">제목 + 내용</option>
									</select>&nbsp;
									<INPUT TYPE="text" size="10" NAME="qry_str" VALUE="">
									<a href="javascript:document.frmSearch.submit();"><img src="images/button_serch.gif" border="0" align="absmiddle" hspace="5"></a>
									<a href="javascript:onWrite();"><img src="images/button_write.gif" border="0" align="absmiddle"></a>
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
								<td height="2" bgcolor="#000000" colspan="3"></td>
							</tr>
							<tr align="center" bgcolor="#E9E9E9" height="25">
								<td width="70%">제 목</td>
								<td width="15%">글쓴이</td>
								<td width="15%">등록일</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="3"></td>
							</tr>
							<?
								$result2 = mysql_query($query2);

								if ($TotalArticle) {

									for ($i = 0; $i < $Last; ++$i) {
										mysql_data_seek($result2,$i);
										$obj = mysql_fetch_object($result2);

										if ($i >= $First) {
				
											$no = $obj->no;
											$po = $obj->po;
											$re = $obj->re;
											$de = $obj->de;
				
											if (empty($obj->title))
												$obj->title = "";
				
											$title = $obj->title;
											$name = $obj->name;
											$wdate = $obj->wdate;

											$date_s = date("Y-m-d [H:i]", strtotime($wdate));
				
											if (empty($obj->recomm))
												$obj->recomm = "0";
				
											$recomm = $obj->recomm;
				
											if (empty($obj->wadd1)) {
												$obj->wadd1 = "0";
												$wadd1 = "";
											} else
												$wadd1 = $obj->wadd1;

											if (empty($obj->wadd2)) {
												$obj->wadd2 = "0";
												$wadd2 = "";
											} else
												$wadd2 = $obj->wadd2;

											if (empty($obj->wadd3)) {
												$obj->wadd3 = "0";
												$wadd3 = "";
											} else
												$wadd3 = $obj->wadd3;

											if (empty($obj->pds) || ($obj->pds == "none")) {
												$obj->pds = "";
												$pds = $obj->pds;
												$obj->file_ext = "";
												$file_ext = $obj->file_ext;
											} else {
												$pds = $obj->pds;
												$file_ext = $obj->file_ext;
											}
			
											if (empty($obj->count)) 
												$obj->count = "0";
				
											$count = $obj->count;
											$space = "";

							?>
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td width=70% align="left" style="padding: 0 0 0 10">
							<?
											for ($l = 1; $l < $de; $l++) {
												if ($l != 1)
													$space .= "&nbsp;&nbsp;&nbsp;";
												else
													$space .= "&nbsp;";
					
												if ($l == ($de - 1))
													$space .= "&nbsp;Re:";
					
												$space .= "&nbsp;";
											}		
							?>
									<?echo $space?>
									<a href="javascript:onView('<?echo $no?>', '<?echo $re?>', '<?echo $po?>', '<?echo $de?>');" class=blue_u><?echo $title?></a> 
							<?
											if (strlen($pds) > 3) {
							?>				
				
									<img src="images/download01.gif">
							<?
											}
							?>				
								</td>
								<td align=center width=15%><?echo $name?></td>
								<td align=center width=15%><?echo $date_s?></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="3"></td>
							</tr>
							<?
										}
									}
								}
							?>
							<tr>
								<td align="center" colspan="3">
							<?
								
								$Scale = floor(($page - 1) / $PageScale);
								if ($TotalArticle > $ListArticle) {

									if ($page != 1)
										echo " <a href='".$PHP_SELF."?page=1&code=$code&idxfield=$idxfield&qry_str=$qry_str' class=list>[맨앞]</a> ";
										// 이전페이지
									if (($TotalArticle + 1) > ($ListArticle * $PageScale)) {
										$PrevPage = ($Scale - 1) * $PageScale;
										if ($PrevPage >= 0)
											echo " <a href='".$PHP_SELF."?page=".($PrevPage + 1)."&code=$code&idxfield=$idxfield&qry_str=$qry_str' class=list>[이전".$PageScale."개]</a> ";
									}
									echo "&nbsp;";

									// 페이지 번호
									for ($vj = 0; $vj < $PageScale; $vj++) {
										//$ln = ($Scale * $PageScale + $vj) * $ListArticle + 1;
										$vk = $Scale * $PageScale + $vj + 1;
									
										if ($vk < $TotalPage + 1) {
											if ($vk != $page)
												echo " <a href='".$PHP_SELF."?page=".$vk."&code=$code&idxfield=$idxfield&qry_str=$qry_str' class=list>[".$vk."]</a> ";
											else
												echo " <b>[".$vk."]</b> ";
										}
									}

									echo "&nbsp;";
									// 다음 페이지
									if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale)) {
										$NextPage = ($Scale + 1) * $PageScale + 1;
										echo " <a href='".$PHP_SELF."?page=".$NextPage."&code=$code&idxfield=$idxfield&qry_str=$qry_str' class=list>[이후".$PageScale."개]</a> ";
									}

									if ($page != $TotalPage)
										echo " <a href='".$PHP_SELF."?page=".$TotalPage."&code=$code&idxfield=$idxfield&qry_str=$qry_str' class=list>[맨뒤]</a> ";
								}
								else 
									echo " [1] ";	
							?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</TABLE>
		</td>
	</tr>
</table>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="no" value="">
<input type="hidden" name="po" value="">
<input type="hidden" name="re" value="">
<input type="hidden" name="de" value="">
<input type="hidden" name="code" value="<?echo $code?>">
</form>
</body>
</html>
<?
mysql_close($connect);
?>