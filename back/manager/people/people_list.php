<?session_start();?>
<?

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../../_common/config.php";
	require "../../../_classes/community/util/util.php";
	require "../../../_classes/community/etc/etc.php";
	require "../../../_classes/community/cpeople/people.php";

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";
	
	if ($order_field == "")
		$order_field = "REG_DATE";
	
	if ($order_str=="")
		$order_str = "DESC";

#============================================================
# Page process
#============================================================

	if (($nPage <> "") && ($nPage <> 0)) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntCommPeople($conn, $comm_no, $con_position_code, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listCommPeople($conn, $comm_no, $con_position_code, $order_field, $order_str, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_comm_name?> 관리자 로그인</title>
<link href="../css/common.css" rel="stylesheet" />

<!--[if IE]>
<script>
document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("section");
document.createElement("figure");
document.createElement("figcaption");
document.createElement("legend");
document.createElement("time");
</script>
<![endif]-->
<!--[if IE]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script type="text/javascript" src="../js/common.js"></script>
<script language="javascript">

	function js_write() {
		var frm = document.frm;
		frm.target = "";
		frm.action = "people_write.php?menu_cd=<?=$menu_cd?>";
		frm.submit();
	}

	function js_view(seq_no) {

		var frm = document.frm;
		
		frm.seq_no.value = seq_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "post";
		frm.action = "people_write.php";
		frm.submit();
		
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

</script>
</head>

<body>

<div class="wrapper">
<section id="container">	

<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>

	<section class="conRight">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../../_common/top_area.php";
?>
		<div class="conTit">
			<h2><?=$p_parent_menu_name?></h2>
		</div>
		
		<section class="conBox">

<form id="bbsList" name="frm" method="post" action="javascript:js_serch();">
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							<tr>
								<th>직책</th>
								<td colspan="3">
									<?= makeSelectBox($conn, "COMM_PEOPLE" ,"con_position_code" ,"250px" , "직책 전체" , "", $con_position_code);?>
								</td>
							</tr>
							<tr class="last">
								<th>정렬</th>
								<td>
									<select name="order_field" style="width:84px;">
										<option value="REG_DATE" <? if ($order_field == "REG_DATE") echo "selected"; ?> >등록일</option>
										<option value="NAME" <? if ($order_field == "NAME") echo "selected"; ?> >성명</option>
										<option value="POSITION_CODE" <? if ($order_field == "POSITION_CODE") echo "selected"; ?> >직책순</option>
									</select>&nbsp;&nbsp;
									<input type='radio' class="radio" name='order_str' value='DESC' <? if (($order_str == "DESC") || ($order_str == "")) echo " checked"; ?> > 오름차순 &nbsp;
									<input type='radio' class="radio" name='order_str' value='ASC' <? if ($order_str == "ASC") echo " checked"; ?>> 내림차순
								</td>
								<th>검색조건</th>
								<td>
									<select name="nPageSize" style="width:84px;">
										<option value="20" <? if ($nPageSize == "20") echo "selected"; ?> >20개씩</option>
										<option value="50" <? if ($nPageSize == "50") echo "selected"; ?> >50개씩</option>
										<option value="100" <? if ($nPageSize == "100") echo "selected"; ?> >100개씩</option>
										<option value="200" <? if ($nPageSize == "200") echo "selected"; ?> >200개씩</option>
										<option value="300" <? if ($nPageSize == "300") echo "selected"; ?> >300개씩</option>
										<option value="400" <? if ($nPageSize == "400") echo "selected"; ?> >400개씩</option>
										<option value="500" <? if ($nPageSize == "500") echo "selected"; ?> >500개씩</option>
									</select>&nbsp;
									<select name="search_field" style="width:84px;">
										<option value="ALL" <? if ($search_field == "ALL") echo "selected"; ?> >통합검색</option>
										<option value="NAME" <? if ($search_field == "NAME") echo "selected"; ?> >성명</option>
										<option value="CAREER" <? if ($search_field == "CAREER") echo "selected"; ?> >약력</option>
									</select>&nbsp;

									<input type="text" value="<?=$search_str?>" name="search_str" size="15"class="txt" />
									<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="검색" /></a>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="expArea">
						<ul class="fLeft">
							<li class="total">총 <?=$nListCnt?>건</li>
							<!--<li class="file">파일 저장하기&nbsp;<a href="#"><img src="../images/bu/ic_file.gif" alt="엑셀파일 아이콘" /></a></li>-->
						</ul>
						<p class="fRight">
							<a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a>
						</p>
					</div>
					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
				
						<colgroup>
							<col width="15%" />
							<col width="10%" />
							<col width="10%" />
							<col width="15%" />
							<col width="15%" />
							<col width="20%" />
							<col width="15%" />
						</colgroup>

						<thead>
							<tr>
								<th class="photo">사진</th>
								<th>성명</th>
								<th>직책</th>
								<th>연락처</th>
								<th class="sns">소셜</th>
								<th>이메일</th>
								<th>활성여부</th>
							</tr>
						</thead>
						<tbody>

						<?
							$nCnt = 0;
							
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn							= trim($arr_rs[$j]["rn"]);
									$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
									$NAME						= trim($arr_rs[$j]["NAME"]);
									$POSITION_CODE	= trim($arr_rs[$j]["POSITION_CODE"]);
									$POSITION				= trim($arr_rs[$j]["POSITION"]);
									$TEL01					= trim($arr_rs[$j]["TEL01"]);
									$TEL02					= trim($arr_rs[$j]["TEL02"]);
									$HOMEPAGE				= trim($arr_rs[$j]["HOMEPAGE"]);
									$TWEETER				= trim($arr_rs[$j]["TWEETER"]);
									$FACEBOOK				= trim($arr_rs[$j]["FACEBOOK"]);
									$METO						= trim($arr_rs[$j]["METO"]);
									$YOZM						= trim($arr_rs[$j]["YOZM"]);
									$EMAIL					= trim($arr_rs[$j]["EMAIL"]);
									$FILE_RNM				= trim($arr_rs[$j]["FILE_RNM"]);
									$FILE_NM				= trim($arr_rs[$j]["FILE_NM"]);
									$FILE_SIZE			= trim($arr_rs[$j]["FILE_SIZE"]);
									$FILE_EXT				= trim($arr_rs[$j]["FILE_EXT"]);
									$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
									$REG_DATE				= date("Y-m-d",strtotime($REG_DATE));

									if($USE_TF=="Y"){
										$use_state="활성";
									}else{
										$use_state="비활성";
									}

						?>

							<tr class="last">
								<td class="photo">
									<?if($FILE_NM){?>
									<a href="javascript:js_view('<?=$SEQ_NO?>');"><img src="/upload_data/people/<?=$FILE_NM?>" width="150"></a>
									<?}else{?>
									<img src="/manager/images/noimg.jpg" width="150">
									<?}?>
								</td>
								<td><a href="javascript:js_view('<?=$SEQ_NO?>');"><?=$NAME?></a></td>
								<td><?=getDcodeName($conn, "COMM_PEOPLE", $POSITION_CODE)?></td>
								<td>							
									<?=$TEL01?>
									<?
										if ($TEL02 <> "") echo "<br>".$TEL02;
									?>
								</td>
								<td class="sns">
									<ul>
										<?	if ($HOMEPAGE) { ?>
										<li><a href="http://<?=str_replace("http://","",$HOMEPAGE)?>" target="_blank"><img src="../images/bu/home.gif"></a></li>
										<?	} ?>
										<?	if ($TWEETER) { ?>
										<?		$TWEETER=str_replace('@','',$TWEETER); ?>
										<li><a href="http://twitter.com/<?=str_replace("http://twitter.com/","",$TWEETER)?>" target="_blank"><img src="../images/bu/twitt.gif"></a></li>
										<?	} ?>
										<?	if ($FACEBOOK) { ?>
										<?		$FACEBOOK=str_replace('@','',$FACEBOOK); ?>
										<li><a href="http://www.facebook.com/<?=str_replace("http://www.facebook.com/","",$FACEBOOK)?>" target="_blank"><img src="../images/bu/facebook.gif"></a></li>
										<?	} ?>
										<?	if ($METO) { ?>
										<?		$METO=str_replace('@','',$METO); ?>
										<li><a href="http://me2day.net/<?=str_replace("http://me2day.net/","",$METO)?>" target="_blank"><img src="../images/bu/metoday.gif"></a></li>
										<?	} ?>
									</ul>
								</td>
								<td>
									<? if ($EMAIL) { ?><a href="mailto:<?=$EMAIL?>"><? } ?><?=$EMAIL?><? if ($EMAIL) { ?></a><? } ?>
								</td>
								<td><!--td에 클래스 on/off로 활성/비활성 제어-->
									<?=$use_state?>
								</td>
							</tr>
						<?			
								}
							} else { 
						?>
							<tr>
								<td align="center" height="50" colspan="7">데이터가 없습니다. </td>
							</tr>
						<? 
							}
						?>

						</tbody>
					</table>
				</fieldset>
			</form>

					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&menu_cd=".$menu_cd;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->

		</section>
		<iframe src="about:blank" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
