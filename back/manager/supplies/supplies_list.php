<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : supplies_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-01-02
# Modify Date  : 
#	Copyright : Copyright @Ucom Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "SU001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/supplies/supplies.php";

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

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
	$nListCnt = totalCntSupplies($conn, $con_su_type, $con_ask_adm_no, $con_ask_state, $con_buy_state, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";

	$arr_rs = listSupplies($conn, $con_su_type, $con_ask_adm_no, $con_ask_state, $con_buy_state, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "구매 요청 리스트 조회", "List");

	#echo sizeof($arr_rs);
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript" >

	function js_write() {
		document.location.href = "supplies_write.php";
	}

	function js_view(su_no) {

		var frm = document.frm;
		
		frm.su_no.value = su_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "supplies_read.php";
		frm.submit();
		
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

</script>
</head>
<body>

<div id="wrap">
<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>
	<!-- S: container -->
	<div class="container">
		<div class="contentsarea">
			<div class="menu-holiday">
				<h3><strong><?=$p_menu_name?></strong>
					
					<? if ($sPageRight_I == "Y") { ?><button type="button" class="btn-navy" id="btn_write" onclick="js_write()">등록하기</button><? } ?>

				</h3>

<form name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="su_no" value="">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">

				<div class="boardlist search">

					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:auto" />
						</colgroup>
						<tbody>
							<tr>
								<th>장비구분</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"EQUIPMENT","con_su_type","","전체","",$con_su_type)?>
									</span>
								</td>
								<th>기안자</th>
								<td>
									<span class="optionbox">
										<?=makeEmpSelectBox($conn, "con_ask_adm_no" , "" , "전체", "", $con_ask_adm_no)?>
									</span>
								</td>
								<th>승인상태</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"ASK_STATE","con_ask_state","","전체","",$con_ask_state)?>
									</span>
								</td>
							</tr>
							<tr>
								<th>구매상태</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"BUY_STATE","con_buy_state","","전체","",$con_buy_state)?>
									</span>
								</td>
								<th>검색조건</th>
								<td colspan="3">
									<div class="searchbox">
										<span class="optionbox"  style="width:100px">
											<select name="nPageSize">
												<option value="20" <? if ($nPageSize == "20") echo "selected"; ?> >20개씩</option>
												<option value="50" <? if ($nPageSize == "50") echo "selected"; ?> >50개씩</option>
												<option value="100" <? if ($nPageSize == "100") echo "selected"; ?> >100개씩</option>
											</select>
										</span>
										<span class="optionbox" style="width:100px">
											<select name="search_field">
												<option value="TITLE" <? if ($search_field == "TITLE") echo "selected"; ?> >제목</option>
												<option value="SU_MODEL" <? if ($search_field == "SU_MODEL") echo "selected"; ?> >모델명</option>
												<option value="BUY_COMPANY" <? if ($search_field == "BUY_COMPANY") echo "selected"; ?> >구매처</option>
												<option value="MEMO" <? if ($search_field == "MEMO") echo "selected"; ?> >메모</option>
												<option value="BUY_MEMO" <? if ($search_field == "BUY_MEMO") echo "selected"; ?> >구매자메모</option>
											</select>
										</span>
										<span class="inpbox"><input type="text" value="<?=$search_str?>" name="search_str" class="txt" /></span>
										<button type="button" class="btn-border-white" id="btn_search" onClick="js_search();">검색</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<span>총 <?=$nListCnt?> 건</span>
				<div class="boardlist">
					<table>
						<colgroup>
							<col style="width:5%" /><!-- 번호 -->
							<col style="width:38%" /><!-- 제목 -->
							<col style="width:10%" /><!-- 자재구분 -->
							<col style="width:15%" /><!-- 모델명 -->
							<col style="width:8%" /><!-- 기안자 -->
							<col style="width:8%" /><!-- 신청상태 -->
							<col style="width:8%" /><!-- 구매상태 -->
							<col style="width:8%" /><!-- 등록일 -->
						</colgroup>
						<thead>
							<tr>
								<th scope="col">번호</th>
								<th scope="col">제목</th>
								<th scope="col">자재구분</th>
								<th scope="col">모델명</th>
								<th scope="col">기안자</th>
								<th scope="col">신청상태</th>
								<th scope="col">구매상태</th>
								<th class="last">등록일</th>
							</tr>
						</thead>
						<tbody>

						<?
							$nCnt = 0;
							
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn						= trim($arr_rs[$j]["rn"]);
									$SU_NO				= trim($arr_rs[$j]["SU_NO"]);
									$TITLE				= SetStringFromDB($arr_rs[$j]["TITLE"]);
									$SU_TYPE			= trim($arr_rs[$j]["SU_TYPE"]);
									$SU_MODEL			= SetStringFromDB($arr_rs[$j]["SU_MODEL"]);
									$SU_PRICE			= trim($arr_rs[$j]["SU_PRICE"]);
									$BUY_LINK			= trim($arr_rs[$j]["BUY_LINK"]);
									$ASK_ADM_NO		= trim($arr_rs[$j]["ASK_ADM_NO"]);
									$ASK_DATE			= trim($arr_rs[$j]["ASK_DATE"]);
									$ASK_STATE		= trim($arr_rs[$j]["ASK_STATE"]);
									$BUY_STATE		= trim($arr_rs[$j]["BUY_STATE"]);
									$BUY_COMPANY	= SetStringFromDB($arr_rs[$j]["BUY_COMPANY"]);
									$BUY_DATE			= trim($arr_rs[$j]["BUY_DATE"]);
									$PAY_TYPE			= trim($arr_rs[$j]["PAY_TYPE"]);
									$BUY_PRICE		= trim($arr_rs[$j]["BUY_PRICE"]);
									$BUY_DATE			= trim($arr_rs[$j]["BUY_DATE"]);
									$BUY_MEMO			= SetStringFromDB($arr_rs[$j]["BUY_MEMO"]);
									$MEMO					= SetStringFromDB($arr_rs[$j]["MEMO"]);
									$USE_TF				= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF				= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE			= trim($arr_rs[$j]["REG_DATE"]);
									$ADM_NAME			= SetStringFromDB($arr_rs[$j]["ADM_NAME"]);
									
									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
						?>

							<tr>
								<td><?=$rn?></td>
								<td style="text-align:left"><a href="javascript:js_view('<?= $SU_NO ?>');"><?=$TITLE?></a></td>
								<td><?=getDcodeName($conn, "EQUIPMENT", $SU_TYPE) ?></td>
								<td><?=$SU_MODEL?></td>
								<td><?= $ADM_NAME ?></td>
								<td><?= getDcodeName($conn, "ASK_STATE", $ASK_STATE) ?></td>
								<td><?= getDcodeName($conn, "BUY_STATE", $BUY_STATE) ?></td>
								<td><?= $REG_DATE ?></td>
							</tr>

						<?			
								}
							} else { 
						?> 
							<tr>
								<td align="center" height="50" colspan="10">데이터가 없습니다. </td>
							</tr>
						<? 
							}
						?>
						</tbody>
					</table>
				</div>
				<p class="paging">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&con_su_type=".$con_su_type."&con_ask_adm_no=".$con_ask_adm_no."&con_ask_state=".$con_ask_state."&con_buy_state=".$con_buy_state."&search_field=".$search_field."&search_str=".$search_str;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
				</p>
				
</form>

			</div>
		</div>
	</div>
	<!-- //E: container -->

	<!-- S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>
<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
