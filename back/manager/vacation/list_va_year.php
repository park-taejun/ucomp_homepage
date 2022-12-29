<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : list_va_year.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-11-02
# Modify Date  : 
#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "VA001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/vacation/vacation.php";

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	

	if ($mode == "I") {

		$row_cnt = count($adm_no);

		for ($k = 0; $k < $row_cnt; $k++) {

			$result = setVacationCnt($conn, $con_yyyy, $adm_no[$k], $y_cnt[$k], $s_adm_no);

			//echo $adm_no[$k]."-----";
			//echo $y_cnt[$k];
			//echo $con_yyyy;
			//echo "<br>";
		}
	
	}

	$this_yyyy = date("Y",strtotime("0 day"));

	if ($con_yyyy == "") { 
		$con_yyyy = date("Y",strtotime("0 day"));
	} else {
		$con_yyyy = $con_yyyy;
	}
	
	//echo $this_yyyy;

	$del_tf = "N";
#============================================================
# Page process
#============================================================

#===============================================================
# Get Search list count
#===============================================================

	$arr_rs = listUserVacationCnt($conn, $con_yyyy, $con_dept_code, $con_position_code, $search_field, $search_str);

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
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_save() {
	
		var frm = document.frm;
		frm.mode.value = "I";
		frm.method = "post";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();

	}

	function js_change_year() {
		var frm = document.frm;
		frm.method = "post";
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
				</h3>

<form name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="mode" value="">

				<div class="boardlist search">
					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:32%" />
							<col style="width:10%" />
							<col style="width:42%" />
							<col style="width:6%" />
						</colgroup>
						<tbody>
							<tr>
								<th>직책</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"POSITION","con_position_code","","전체","",$con_position_code)?>
									</span>
								</td>
								<th>부서명</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"DEPT","con_dept_code","","전체","",$con_dept_code)?>
									</span>
								</td>
							</tr>
							<tr>
								<th>검색조건</th>
								<td colspan="3">
									<div class="searchbox">
										<span class="optionbox">
											<select name="search_field">
												<option value="ADM_NAME" <? if ($search_field == "ADM_NAME") echo "selected"; ?> >이름</option>
												<option value="ADM_ID" <? if ($search_field == "ADM_ID") echo "selected"; ?> >ID</option>
											</select>
										</span>
										<span class="inpbox"><input type="text" value="<?=$search_str?>" name="search_str" size="15" class="txt" /></span>
										<button type="button" class="btn-border-white" id="btn_search" onClick="js_search();">검색</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="btnright">
					<? if ($sPageRight_I == "Y") {?>
						<span class="optionbox">
							<select name="con_yyyy" onChange="js_change_year();">
								<?
									$start_yyyy = $this_yyyy + 2;
									$end_yyyy = $this_yyyy - 5;
									for ($i = $start_yyyy;$i > $end_yyyy;$i--) { 
								?>
								<option value="<?=$i?>" <? if ($i == $con_yyyy) echo "selected"; ?> ><?=$i?></option>
								<?
									}
								?>
							</select>
						</span>
						<button type="button" class="btn-navy" style="width:100px; height:32px" onClick="js_save();">저장</button>
					<? } else { ?>
						&nbsp;
					<? } ?>

				</div>
				<div style="margin: -30px 0 10px 0;"></div>
				<span class="fn_gray">총 <?=sizeof($arr_rs)?> 명</span>
				<div class="sp5"></div>
				<div class="boardlist">
					<table>
						<colgroup>
							<col style="width:5%" />
							<col style="width:15%" />
							<col style="width:15%" />
							<col style="width:15%" />
							<col style="width:15%" />
							<col style="width:15%" />
							<col style="width:10%" />
							<col style="width:auto" />
						</colgroup>
						<tbody>
							<tr>
								<th scope="col">번호</th>
								<th scope="col">직책</th>
								<th scope="col">부서명</th>
								<th scope="col">이름</th>
								<th scope="col">ID</th>
								<th scope="col">연락처</th>
								<th scope="col">입사일</th>
								<th scope="col">발생 연차 일수</th>
							</tr>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
										
										//ADM_ID, ADM_NO, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, 
										//GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
										//GROUP_NAME

										//$rn								= trim($arr_rs[$j]["rn"]);
										$ADM_ID						= trim($arr_rs[$j]["ADM_ID"]);
										$ADM_NO						= trim($arr_rs[$j]["ADM_NO"]);
										$ADM_NAME					= SetStringFromDB($arr_rs[$j]["ADM_NAME"]);
										$ADM_HPHONE				= trim($arr_rs[$j]["ADM_HPHONE"]);
										$GROUP_NO					= trim($arr_rs[$j]["GROUP_NO"]);
										$COM_CODE					= trim($arr_rs[$j]["COM_CODE"]);
										$DEPT_CODE				= trim($arr_rs[$j]["DEPT_CODE"]);
										$POSITION_CODE		= trim($arr_rs[$j]["POSITION_CODE"]);
										$DEPT_NAME				= trim($arr_rs[$j]["DEPT_NAME"]);
										$POSITION_NAME		= trim($arr_rs[$j]["POSITION_NAME"]);
										$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
										$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
										$ENTER_DATE				= trim($arr_rs[$j]["ENTER_DATE"]);
										$VA_CNT						= trim($arr_rs[$j]["VA_CNT"]);

										$GROUP_NAME			= getGroupName($conn, $GROUP_NO); 
										//$DEPT_NAME			= getDcodeName($conn, "DEPT", $DEPT_CODE); 
										//$POSITION_NAME	= getDcodeName($conn, "POSITION", $POSITION_CODE); 
										$CP_NM					= getCompanyName($conn, $COM_CODE); 

										if ($USE_TF == "Y") {
											$STR_USE_TF = "<font color='navy'>사용중</font>";
										} else {
											$STR_USE_TF = "<font color='red'>사용안함</font>";
										}

										$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

										$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;
				
							?>
							<tr>
								<td><?=sizeof($arr_rs) - $j?></td>
								<td><?= $POSITION_NAME ?></td>
								<td><?= $DEPT_NAME ?></td>
								<td><?= $ADM_NAME ?></td>
								<td><?= $ADM_ID?></td>
								<td><?= $ADM_HPHONE ?></td>
								<td><?= $ENTER_DATE ?></td>
								<td>
									<input type="hidden" name="adm_no[]" value="<?=$ADM_NO?>">
									<span class="inpbox"><input type="text" class="txt" style="width:100%;text-align:right" name="y_cnt[]" value="<?=$VA_CNT?>"></span>
								</td>
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
</form>

			</div>
		</div>
	</div>
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

