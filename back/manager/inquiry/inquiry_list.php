<?session_start();?>
<?
# =============================================================================
# File Name    : inquiry_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright : Copyright @C&C Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AS001"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/inquiry/inquiry.php";



	if ($mode == "T") {
		$row_cnt = count($chk_no);
		for ($k = 0; $k < $row_cnt; $k++) {
			$str_seq_no = $chk_no[$k];
			$result = updateStateInquiry($conn, $s_adm_no, $str_seq_no);
		}
	}


	if ($mode == "D") {
		$row_cnt = count($chk_no);
		for ($k = 0; $k < $row_cnt; $k++) {
			$str_seq_no = $chk_no[$k];
			$result = deleteInquiry($conn, $s_adm_no, $str_seq_no);
		}
	}

#====================================================================
# Request Parameter
#====================================================================

	if ($start_date == "") {
		$start_date = date("Y-m-d",strtotime("-24 month"));;
	} else {
		$start_date = trim($start_date);
	}

	if ($end_date == "") {
		$end_date = date("Y-m-d",strtotime("0 month"));;
	} else {
		$end_date = trim($end_date);
	}

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

	if ($nPage == 0) $nPage = 1;
	if ($nPageSize == 0) $nPageSize = "";

	if ($nPage <> "") {
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
														
	$nListCnt =totalCntInquiry($conn, $con_lang, $con_cate_code, $con_ask_code, $start_date, $end_date, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";
	$arr_rs = listInquiry($conn, $con_lang, $con_cate_code, $con_ask_code, $start_date, $end_date, $con_use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize, $nListCnt);

	#echo sizeof($arr_rs);
	$strParam = "";
	$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str;
	$strParam = $strParam."&start_date=".$start_date."&end_date=".$end_date."&con_lang=".$con_lang;
	$strParam = $strParam."&con_cate_code=".$con_cate_code;

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
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
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/goods_common.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" >

	function js_write() {
		document.location.href = "inquiry_read.php";
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";

		frm.target = "";
		frm.method = "get";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_toggle(seq_no, state_tf) {
		var frm = document.frm;

		bDelOK = confirm('상태 여부를 변경 하시겠습니까?');
		
		if (bDelOK==true) {

			if (state_tf == "Y") {
				state_tf = "N";
			} else {
				state_tf = "Y";
			}

			frm.seq_no.value = seq_no;
			frm.state_tf.value = state_tf;
			frm.mode.value = "T";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_excel_print() {
		
		var frm = document.frm;
		
		frm.target = "";
		frm.action = "<?=str_replace("list","excel_list",$_SERVER[PHP_SELF])?>";
		frm.submit();

	}

	function js_all_check() {
		var frm = document.frm;
		
		if (frm['chk_no[]'] != null) {
			
			if (frm['chk_no[]'].length != null) {

				if (frm.all_chk.checked == true) {
					for (i = 0; i < frm['chk_no[]'].length; i++) {
						frm['chk_no[]'][i].checked = true;
					}
				} else {
					for (i = 0; i < frm['chk_no[]'].length; i++) {
						frm['chk_no[]'][i].checked = false;
					}
				}
			} else {
			
				if (frm.all_chk.checked == true) {
					frm['chk_no[]'].checked = true;
				} else {
					frm['chk_no[]'].checked = false;
				}
			}
		}
	}

	function js_delete() {
		var frm = document.frm;

		bDelOK = confirm('선택한 문의를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_update() {
		var frm = document.frm;

		bDelOK = confirm('선택한 문의를 답변완료로 변경 하시겠습니까?');
		
		if (bDelOK==true) {
			
			frm.mode.value = "T";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}




	function js_view(seq_no) {

		var frm = document.frm;
		
		frm.seq_no.value = seq_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "inquiry_read.php";
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


<form  id="bbsList" name="frm" method="post" onsubmit="return js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="depth" value="" />
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="state_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<!--<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">-->

<input type="hidden" name="con_cate_03" value="<?=$con_cate_03?>">
<input type="hidden" name="cp_type" value="">

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							<tr>
								<th>문의 구분</th>
								<td colspan="3">
									<?= makeSelectBox($conn,"ASK_CODE","con_ask_code","125","전체","",$con_ask_code)?>
								</td>
							</tr>

							<tr class="last">
								<th>정렬</th>
								<td>
									<select name="order_field" style="width:84px;">
										<option value="REG_DATE" <? if ($order_field == "REG_DATE") echo "selected"; ?> >등록일</option>
										<option value="TITLE" <? if ($order_field == "TITLE") echo "selected"; ?> >제목</option>
										<option value="IN_NAME" <? if ($order_field == "IN_NAME") echo "selected"; ?> >작성자</option>
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
										<option value="TITLE" <? if ($search_field == "TITLE") echo "selected"; ?> >제목</option>
										<option value="IN_NAME" <? if ($search_field == "IN_NAME") echo "selected"; ?> >상품번호</option>
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
						</ul>

						<p class="fRight">
							<!--<a href="javascript:js_excel_print();" class="btn_type6">선택한 항목 엑셀로 받기</a>-->
						</p>
					</div>

					<div class="sp0"></div>

					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
					
					<colgroup>
						<col width="3%" />
						<col width="5%" />
						<col width="10%" />
						<col width="42%"/>
						<col width="10%"/>
						<col width="10%"/>
						<col width="10%" />
						<col width="10%" />
					</colgroup>
					<thead>
						<tr>
							<th><input type="checkbox" name="all_chk" onClick="js_all_check();"></th>
							<th>번호</th>
							<th>문의구분</th>
							<th>제목</th>
							<th>이름</th>
							<th>연락처</th>
							<th>등록일</th>
							<th class="end">처리여부</th>
						</tr>
					</thead>
					<tbody>

				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							$rn								= trim($arr_rs[$j]["rn"]);
							$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
							$LANG						= trim($arr_rs[$j]["LANG"]);
							$CATE_CODE			= trim($arr_rs[$j]["CATE_CODE"]);
							$ASK_CODE				= trim($arr_rs[$j]["ASK_CODE"]);
							$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
							$COM_NAME				= SetStringFromDB($arr_rs[$j]["COM_NAME"]);
							$IN_NAME				= SetStringFromDB($arr_rs[$j]["IN_NAME"]);
							$AREA						= trim($arr_rs[$j]["AREA"]);
							$PHONE					= trim($arr_rs[$j]["PHONE"]);
							$HPHONE					= trim($arr_rs[$j]["HPHONE"]);
							$EMAIL					= trim($arr_rs[$j]["EMAIL"]);
							$REPLY_STATE		= trim($arr_rs[$j]["REPLY_STATE"]);
							$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

							if ($REPLY_STATE == "Y") {
								$STR_REPLY_STATE_TF = "<font color='navy'>답변완료</font>";
							} else {
								$STR_REPLY_STATE_TF = "<font color='red'>답변전</font>";
							}

							$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
				
				?>
						<tr>
							<td>
								<input type="checkbox" name="chk_no[]" value="<?=$SEQ_NO?>">
							</td>
							<td><?= $rn ?></td>
							<td><?= getDcodeName($conn, "ASK_CODE", $ASK_CODE) ?></td>
							<td style="text-align:left;padding-left:20px"><a href="javascript:js_view('<?= $SEQ_NO ?>');"><?=$TITLE?></a></td>
							<td><?= $IN_NAME ?></td>
							<td><?= $HPHONE ?></td>
							<td><?= $REG_DATE ?></td>
							<td class="filedown"><?=$STR_REPLY_STATE_TF ?></a></td>
						</tr>

				<?			
						}
					} else { 
				?> 
						<tr>
							<td align="center" height="50" colspan="12">데이터가 없습니다. </td>
						</tr>
				<? 
					}
				?>
						</tbody>
					</table>
				</fieldset>
			</form>

			<div style="width: 97%; text-align: right; margin: 10px 0px 20px 0px;">
			<? if ($sPageRight_U == "Y") {?>
			<input type="button" name="aa" value=" 선택한 문의 답변완료로 변경 " class="btntxt" onclick="js_update();" style="cursor:hand">
			<? } ?>
			<? if ($sPageRight_D == "Y") {?>
				<input type="button" name="aa" value=" 선택한 문의 삭제 " class="btntxt" onclick="js_delete();" style="cursor:hand"> 
			<? } ?>
			</div>
			<div id="bbspgno">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							
							$str_list_param = "";
							$str_list_param = $str_list_param."&nPageSize=".$nPageSize;
							$str_list_param = $str_list_param."&gd_cate_01=".$gd_cate_01."&gd_cate_02=".$gd_cate_02."&gd_cate_03=".$gd_cate_03;
							$str_list_param = $str_list_param."&start_date=".$start_date."&end_date=".$end_date;
							$str_list_param = $str_list_param."&order_field=".$order_field."&order_str=".$order_str."&search_field=".$search_field."&search_str=".$search_str;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$str_list_param) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
			</div>
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