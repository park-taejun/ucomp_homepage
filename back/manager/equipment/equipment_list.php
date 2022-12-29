<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : equipment_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-10-16
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
	$menu_right = "EQ003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/equipment/equipment.php";

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

	$nListCnt =totalCntEquipment($conn, $con_eq_type, $con_eq_user, $con_eq_state, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";

	$arr_rs = listEquipment($conn, $con_eq_type, $con_eq_user, $con_eq_state, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "자제 관리 리스트 조회", "List");

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
		document.location.href = "equipment_write.php";
	}

	function js_view(eq_no) {

		var frm = document.frm;
		
		frm.eq_no.value = eq_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "equipment_read.php";
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
<input type="hidden" name="eq_no" value="">
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
								<th>자재구분</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"EQUIPMENT","con_eq_type","","전체","",$con_eq_type)?>
									</span>
								</td>						
								<th>자재상태</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"EQ_STATE","con_eq_state","","전체","",$con_eq_state)?>
									</span>
								</td>
								<th>사용자</th>
								<td>
									<span class="optionbox">
										<?=makeEmpSelectBox($conn, "con_eq_user" , "" , "전체", "", $con_eq_user)?>
									</span>
								</td>
							</tr>
							<tr>
								<th>검색조건</th>
								<td colspan="5">
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
												<option value="EQ_CD" <? if ($search_field == "EQ_CD") echo "selected"; ?> >자재번호</option>
												<option value="EQ_MODEL" <? if ($search_field == "EQ_MODEL") echo "selected"; ?> >모델명</option>
												<option value="EQ_CONAME" <? if ($search_field == "EQ_CONAME") echo "selected"; ?> >제조사</option>
												<option value="EQ_MEMO" <? if ($search_field == "EQ_MEMO") echo "selected"; ?> >메모</option>
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
							<col style="width:10%" /><!-- 자재구분 -->
							<col style="width:10%" /><!-- 자재번호 -->
							<col style="width:18%" /><!-- 모델명 -->
							<col style="width:17%" /><!-- 제조사 -->
							<col style="width:8%" /><!-- 제조일 -->
							<col style="width:8%" /><!-- 자재상태 -->
							<col style="width:8%" /><!-- 사용자 -->
							<col style="width:8%" /><!-- 지급일 -->
							<col style="width:8%" /><!-- 등록일 -->
						</colgroup>
						<thead>
							<tr>
								<th scope="col">번호</th>
								<th scope="col">자재구분</th>
								<th scope="col">자재번호</th>
								<th scope="col">모델명</th>
								<th scope="col">제조사</th>
								<th scope="col">제조일</th>
								<th scope="col">자재상태</th>
								<th scope="col">사용자</th>
								<th scope="col">지급일</th>
								<th class="last">등록일</th>
							</tr>
						</thead>
						<tbody>

						<?
							$nCnt = 0;
							
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn						= trim($arr_rs[$j]["rn"]);
									$EQ_NO				= trim($arr_rs[$j]["EQ_NO"]);
									$EQ_CD				= trim($arr_rs[$j]["EQ_CD"]);
									$EQ_TYPE			= trim($arr_rs[$j]["EQ_TYPE"]);
									$EQ_CONAME		= SetStringFromDB($arr_rs[$j]["EQ_CONAME"]);
									$EQ_MDATE			= trim($arr_rs[$j]["EQ_MDATE"]);
									$EQ_MODEL			= trim($arr_rs[$j]["EQ_MODEL"]);
									$EQ_INFO01		= SetStringFromDB($arr_rs[$j]["EQ_INFO01"]);
									$EQ_INFO02		= SetStringFromDB($arr_rs[$j]["EQ_INFO02"]);
									$EQ_INFO03		= SetStringFromDB($arr_rs[$j]["EQ_INFO03"]);
									$EQ_INFO04		= SetStringFromDB($arr_rs[$j]["EQ_INFO04"]);
									$EQ_INFO05		= SetStringFromDB($arr_rs[$j]["EQ_INFO05"]);
									$EQ_INFO06		= SetStringFromDB($arr_rs[$j]["EQ_INFO06"]);
									$EQ_INFO07		= SetStringFromDB($arr_rs[$j]["EQ_INFO07"]);
									$EQ_INFO08		= SetStringFromDB($arr_rs[$j]["EQ_INFO08"]);
									$EQ_RECDATE		= trim($arr_rs[$j]["EQ_RECDATE"]);
									$EQ_RETDATE		= trim($arr_rs[$j]["EQ_RETDATE"]);
									$EQ_DISDATE		= trim($arr_rs[$j]["EQ_DISDATE"]);
									$EQ_STATE			= trim($arr_rs[$j]["EQ_STATE"]);
									$EQ_MEMO			= SetStringFromDB($arr_rs[$j]["EQ_MEMO"]);
									$EQ_USER			= trim($arr_rs[$j]["EQ_USER"]);
									$USE_TF				= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF				= trim($arr_rs[$j]["DEL_TF"]);
									$REG_DATE			= trim($arr_rs[$j]["REG_DATE"]);
									$ADM_NAME			= SetStringFromDB($arr_rs[$j]["ADM_NAME"]);
									

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
						?>

							<tr>
								<td><?=$rn?></td>
								<td><?=getDcodeName($conn, "EQUIPMENT", $EQ_TYPE) ?></td>
								<td><a href="javascript:js_view('<?= $EQ_NO ?>');"><?= $EQ_CD ?></a></td>
								<td class="bizname"><?=$EQ_MODEL?></td>
								<td class="bizname"><?=$EQ_CONAME?></td>
								<td><?= $EQ_MDATE ?></td>
								<td><?= getDcodeName($conn, "EQ_STATE", $EQ_STATE) ?></td>
								<td><?= $ADM_NAME ?></td>
								<td><?= $EQ_RECDATE ?></td>
								<td class="filedown"><?= $REG_DATE ?></td>
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
							$strParam = $strParam."&nPageSize=".$nPageSize."&con_eq_type=".$con_eq_type."&con_eq_user=".$con_eq_user."&con_eq_state=".$con_eq_state."&search_field=".$search_field."&search_str=".$search_str;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
				</p>

				<!-- // E: mwidthwrap -->
				<? if ($con_eq_user <> "") { ?>
				<?
					$arr_rs = listEquipmentUserHistory($conn, $con_eq_type, $con_eq_user);
				?>
				<h2>지급 내역</h2>

				<div class="boardlist">
					<table>

						<colgroup>
							<col style="width:5%" /><!-- 번호 -->
							<col style="width:8%" /><!-- 자제구분 -->
							<col style="width:9%" /><!-- 자재번호 -->
							<col style="width:8%" /><!-- 이전사용자 -->
							<col style="width:8%" /><!-- 반납일 -->
							<col style="width:8%" /><!-- 다음사용자 -->
							<col style="width:8%" /><!-- 다음사용자 -->
							<col style="width:7%" /><!-- 변경구분 -->
							<col style="width:21%" /><!-- 변경메모 -->
							<col style="width:8%" /><!-- 변경관리자 -->
							<col style="width:10%" /><!-- 등록일 -->
						</colgroup>
						<thead>
							<tr>
								<th scope="col">번호</th>
								<th scope="col">자제구분</th>
								<th scope="col">자재번호</th>
								<th scope="col">이전수령</th>
								<th scope="col">반납일</th>
								<th scope="col">다음수령</th>
								<th scope="col">지급일</th>
								<th scope="col">변경구분</th>
								<th scope="col">변경메모</th>
								<th scope="col">변경관리자</th>
								<th scope="col">등록일</th>
							</tr>
						</thead>
						<tbody>
						<?
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

									$EQ_TYPE				= trim($arr_rs[$j]["EQ_TYPE"]);
									$EQ_CD					= trim($arr_rs[$j]["EQ_CD"]);
									$EQ_RECDATE			= trim($arr_rs[$j]["EQ_RECDATE"]);
									$EQ_RETDATE			= trim($arr_rs[$j]["EQ_RETDATE"]);
									$REG_ADM_NAME		= trim($arr_rs[$j]["REG_ADM_NAME"]);
									$PRE_ADM_NAME		= trim($arr_rs[$j]["PRE_ADM_NAME"]);
									$NEXT_ADM_NAME	= trim($arr_rs[$j]["NEXT_ADM_NAME"]);
									$EQ_MEMO				= trim($arr_rs[$j]["EQ_MEMO"]);
									$EQ_STATE				= trim($arr_rs[$j]["EQ_STATE"]);
									$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

									if (($PRE_ADM_NAME == "NONE") || ($PRE_ADM_NAME == "")) $PRE_ADM_NAME = "미지급";
									if (($NEXT_ADM_NAME == "NONE") || ($NEXT_ADM_NAME == "")) $NEXT_ADM_NAME = "미지급";
						?>
							<tr>
								<td><?=sizeof($arr_rs) - $j?></td>
								<td class="bizname"><?=getDcodeName($conn, "EQUIPMENT", $EQ_TYPE) ?></td>
								<td class="bizname"><?=$EQ_CD?></td>
								<td class="bizname"><?=$PRE_ADM_NAME?></td>
								<td class="bizname"><?=$EQ_RETDATE?></td>
								<td class="bizname"><?=$NEXT_ADM_NAME?></td>
								<td class="bizname"><?=$EQ_RECDATE?></td>
								<td><?=$EQ_STATE?></td>
								<td><?=$EQ_MEMO?></td>
								<td><?=$REG_ADM_NAME?></td>
								<td class="filedown"><?= $REG_DATE ?></td>
							</tr>
						<?
								}
							}
						?>
						</tbody>
					</table>
				</div>
				<? } ?>
				
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
