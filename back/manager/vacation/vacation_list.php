<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : vacation_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-11-08
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
	$menu_right = "VA004"; // 메뉴마다 셋팅 해 주어야 합니다

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

	$con_year		= $_POST['con_year']!=''?$_POST['con_year']:$_GET['con_year'];

	if ($con_year == "") {
		$con_year = date("Y",time());
	}

#====================================================================
# Request Parameter
#====================================================================

#============================================================
# Page process
#============================================================

	
	$arr_rs = listUserVacationYear ($conn, $con_year, $con_dept_code, $con_va_user);
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script language="javascript">

	//$(document).ready(function() {
		
	///});


	$(document).on("change", "#con_year", function() { 
		var frm = document.frm;
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.method = "post";
		frm.target = "";
		frm.submit();
	});

	$(document).on("change", "#con_dept_code", function() { 
		var frm = document.frm;
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.method = "post";
		frm.target = "";
		frm.submit();
	});

	$(document).on("change", "#con_va_user", function() { 
		var frm = document.frm;
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.method = "post";
		frm.target = "";
		frm.submit();
	});

	function js_write() {
		document.location = "vacation_write.php";
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
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">

				<div class="boardlist search">


					<table>
						<colgroup>
							<col style="width:130px" />
							<col style="width:23%" />
							<col style="width:130px" />
							<col style="width:23%" />
							<col style="width:130px" />
							<col style="width:auto" />
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">년도</th>
								<td>
									<? $arr_rs_year = listVacationYear($conn) ?>
									<span class="optionbox">
										<select name="con_year" id="con_year" >
									<? 
											if (sizeof($arr_rs_year) > 0) {
												for ($j = 0 ; $j < sizeof($arr_rs_year); $j++) {
													$YYYY = trim($arr_rs_year[$j]["YYYY"]);
									?>
											<option value="<?=$YYYY?>" <? if ($con_year == $YYYY) echo "selected"; ?>><?=$YYYY?></option>
									<? 
												}
											} 
									?>
										</select>
									</span>
								</td>
								<th scope="row">부서명</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"DEPT","con_dept_code","125px","전체","",$con_dept_code)?>
									</span>
								</td>
								<th scope="row">직원명</th>
								<td>
									<span class="optionbox">
										<?=makeEmpSelectBox($conn, "con_va_user" , "200px" , " 전체 ", "", $con_va_user)?>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<p class="btnleft">
					<span class="btnset">
						<button type="button" class="btn-border-white" onClick="document.location='vacation.php'">달력</button>
						<button type="button" class="btn-border-white on" onClick="document.location='vacation_list.php'">리스트</button><!-- 활성화시 on클래스 -->
					</span>
				</p>
				
				<div class="boardlist">
					<table>

						<colgroup>
							<col style="width:10%" />
							<col style="width:13%" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:90px" />
							<col style="width:13%" />
							<col style="width:auto" />
						</colgroup>
						<thead>

							<tr>
								<th scope="col">부서명</th>
								<th scope="col">직원명</th>
								<th scope="col">1월</th>
								<th scope="col">2월</th>
								<th scope="col">3월</th>
								<th scope="col">4월</th>
								<th scope="col">5월</th>
								<th scope="col">6월</th>
								<th scope="col">7월</th>
								<th scope="col">8월</th>
								<th scope="col">9월</th>
								<th scope="col">10월</th>
								<th scope="col">11월</th>
								<th scope="col">12월</th>
								<th scope="col">1월</th>
								<th scope="col">발생 / 사용 / 잔여</th>
								<th scope="col">스마트데이</th>
							</tr>
						</thead>
						<tbody>

				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							$ADM_ID						= trim($arr_rs[$j]["ADM_ID"]);
							$ADM_NO						= trim($arr_rs[$j]["ADM_NO"]);
							$ADM_NAME					= SetStringFromDB($arr_rs[$j]["ADM_NAME"]);
							$ADM_HPHONE				= trim($arr_rs[$j]["ADM_HPHONE"]);
							$COM_CODE					= trim($arr_rs[$j]["COM_CODE"]);
							$DEPT_CODE				= trim($arr_rs[$j]["DEPT_CODE"]);
							$POSITION_CODE		= trim($arr_rs[$j]["POSITION_CODE"]);
							$DEPT_NAME				= trim($arr_rs[$j]["DEPT_NAME"]);
							$POSITION_NAME		= trim($arr_rs[$j]["POSITION_NAME"]);
							$VA_CNT						= trim($arr_rs[$j]["VA_CNT"]);
							
							$M_1M							= trim($arr_rs[$j]["1M"]);
							$M_2M							= trim($arr_rs[$j]["2M"]);
							$M_3M							= trim($arr_rs[$j]["3M"]);
							$M_4M							= trim($arr_rs[$j]["4M"]);
							$M_5M							= trim($arr_rs[$j]["5M"]);
							$M_6M							= trim($arr_rs[$j]["6M"]);
							$M_7M							= trim($arr_rs[$j]["7M"]);
							$M_8M							= trim($arr_rs[$j]["8M"]);
							$M_9M							= trim($arr_rs[$j]["9M"]);
							$M_10M						= trim($arr_rs[$j]["10M"]);
							$M_11M						= trim($arr_rs[$j]["11M"]);
							$M_12M						= trim($arr_rs[$j]["12M"]);
							$M_13M						= trim($arr_rs[$j]["13M"]);

							$M_1S							= trim($arr_rs[$j]["1S"]);
							$M_2S							= trim($arr_rs[$j]["2S"]);
							$M_3S							= trim($arr_rs[$j]["3S"]);
							$M_4S							= trim($arr_rs[$j]["4S"]);
							$M_5S							= trim($arr_rs[$j]["5S"]);
							$M_6S							= trim($arr_rs[$j]["6S"]);
							$M_7S							= trim($arr_rs[$j]["7S"]);
							$M_8S							= trim($arr_rs[$j]["8S"]);
							$M_9S							= trim($arr_rs[$j]["9S"]);
							$M_10S						= trim($arr_rs[$j]["10S"]);
							$M_11S						= trim($arr_rs[$j]["11S"]);
							$M_12S						= trim($arr_rs[$j]["12S"]);
							$M_13S						= trim($arr_rs[$j]["13S"]);
							
							$use_tot = $M_1M + $M_2M + $M_3M + $M_4M + $M_5M + $M_6M + $M_7M + $M_8M + $M_9M + $M_10M + $M_11M + $M_12M + $M_13M;  
							$use_sd_tot = $M_1S + $M_2S + $M_3S + $M_4S + $M_5S + $M_6S + $M_7S + $M_8S + $M_9S + $M_10S + $M_11S + $M_12S + $M_13S;  

				?>

							<tr <? if ($ADM_NO == $s_adm_no) { ?> bgcolor="#EFEFEF" <? } ?> >
								<td><?= $DEPT_NAME ?></td>
								<td><strong><?= $ADM_NAME ?></strong> (<?= $POSITION_NAME ?>)</td>
								<td><strong><?= $M_1M?></strong> / <b><?= $M_1S?></b></td>
								<td><strong><?= $M_2M?></strong> / <b><?= $M_2S?></b></td>
								<td><strong><?= $M_3M?></strong> / <b><?= $M_3S?></b></td>
								<td><strong><?= $M_4M?></strong> / <b><?= $M_4S?></b></td>
								<td><strong><?= $M_5M?></strong> / <b><?= $M_5S?></b></td>
								<td><strong><?= $M_6M?></strong> / <b><?= $M_6S?></b></td>
								<td><strong><?= $M_7M?></strong> / <b><?= $M_7S?></b></td>
								<td><strong><?= $M_8M?></strong> / <b><?= $M_8S?></b></td>
								<td><strong><?= $M_9M?></strong> / <b><?= $M_9S?></b></td>
								<td><strong><?= $M_10M?></strong> / <b><?= $M_10S?></b></td>
								<td><strong><?= $M_11M?></strong> / <b><?= $M_11S?></b></td>
								<td><strong><?= $M_12M?></strong> / <b><?= $M_12S?></b></td>
								<td><strong><?= $M_13M?></strong> / <b><?= $M_13S?></b></td>
								<td><?= $VA_CNT?> / <?= $use_tot?> / <em><?= $VA_CNT - $use_tot ?></em></td>
								<td><b><?= $use_sd_tot ?></b></td>
							</tr>

				<?			
						}
					} else { 
				?> 
							<tr>
								<td align="center" height="50" colspan="17">데이터가 없습니다. </td>
							</tr>
				<? 
					}
				?>
						</tbody>
					</table>
				</div>

			<!-- // E: mwidthwrap -->

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
