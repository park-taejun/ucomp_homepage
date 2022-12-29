<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : equipment_read.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-10-16
# Modify Date  : 
#	Copyright    : Copyright @Ucom Corp. All Rights Reserved.
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
	include "../../_common/common_header.php"; 

	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/equipment/equipment.php";

	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================
	
	if ($mode == "S") {
		$arr_rs = selectEquipment($conn, $eq_no);

		$rs_eq_no					= trim($arr_rs[0]["EQ_NO"]); 
		$rs_eq_cd					= trim($arr_rs[0]["EQ_CD"]); 
		$rs_eq_type				= trim($arr_rs[0]["EQ_TYPE"]); 
		$rs_eq_coname			= SetStringFromDB($arr_rs[0]["EQ_CONAME"]); 
		$rs_eq_mdate			= trim($arr_rs[0]["EQ_MDATE"]); 
		$rs_eq_model			= SetStringFromDB($arr_rs[0]["EQ_MODEL"]); 
		$rs_eq_info01			= SetStringFromDB($arr_rs[0]["EQ_INFO01"]); 
		$rs_eq_info02			= SetStringFromDB($arr_rs[0]["EQ_INFO02"]); 
		$rs_eq_info03			= SetStringFromDB($arr_rs[0]["EQ_INFO03"]); 
		$rs_eq_info04			= SetStringFromDB($arr_rs[0]["EQ_INFO04"]); 
		$rs_eq_info05			= SetStringFromDB($arr_rs[0]["EQ_INFO05"]); 
		$rs_eq_info06			= SetStringFromDB($arr_rs[0]["EQ_INFO06"]); 
		$rs_eq_info07			= SetStringFromDB($arr_rs[0]["EQ_INFO07"]); 
		$rs_eq_info08			= SetStringFromDB($arr_rs[0]["EQ_INFO08"]); 
		$rs_eq_indate			= trim($arr_rs[0]["EQ_INDATE"]); 
		$rs_eq_recdate		= trim($arr_rs[0]["EQ_RECDATE"]); 
		$rs_eq_retdate		= trim($arr_rs[0]["EQ_RETDATE"]); 
		$rs_eq_disdate		= trim($arr_rs[0]["EQ_DISDATE"]); 
		$rs_eq_state			= trim($arr_rs[0]["EQ_STATE"]); 
		$rs_eq_memo				= SetStringFromDB($arr_rs[0]["EQ_MEMO"]); 
		$rs_eq_user				= trim($arr_rs[0]["EQ_USER"]); 
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]); 

		$arr_rs = listEquipmentHistory($conn, $eq_no);
	}

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript">

function js_list() {
	var frm = document.frm;
		
	frm.method = "post";
	frm.action = "equipment_list.php";
	frm.submit();
}

function js_modify() {
	var frm = document.frm;

	frm.mode.value = "S";
	frm.method = "post";
	frm.action = "equipment_modify.php";
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

<form name="frm" method="post">
<input type="hidden" name="eq_no" value="<?=$eq_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="con_eq_type" value="<?=$con_eq_type?>">
<input type="hidden" name="con_eq_user" value="<?=$con_eq_user?>">
<input type="hidden" name="con_eq_state" value="<?=$con_eq_state?>">
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="boardlist search">
				
					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:24%" />
						</colgroup>
						<tbody>
							<tr>
								<th>등록구분</th>
								<td>
									<?= getDcodeName($conn, "EQUIPMENT", $rs_eq_type); ?>
								</td>
								<th>자재번호</th>
								<td colspan="3">
									<?= $rs_eq_cd ?>
								</td>
							</tr>
							<tr>
								<th>모델명</th>
								<td><?=$rs_eq_model?></td>
								<th>제조사</th>
								<td><?=$rs_eq_coname?></td>
								<th>제조일</th>
								<td><?=$rs_eq_mdate?></td>
							</tr>

							<? if (($rs_eq_type == "EQ001") || ($rs_eq_type == "EQ002")) { ?>
							<tr>
								<th>CPU</th>
								<td><?=$rs_eq_info01?></td>
								<th>RAM</th>
								<td><?=$rs_eq_info02?></td>
								<th>GPU</th>
								<td><?=$rs_eq_info03?></td>
							</tr>

							<tr>
								<th>HDD</th>
								<td><?=$rs_eq_info04?></td>
								<th>OS</th>
								<td colspan="3"><?=$rs_eq_info05?></td>
							</tr>
							<? } ?>
					
							<? if ($rs_eq_type == "EQ003") { ?>
							<tr>
								<th>해상도</th>
								<td colspan="5"><?=$rs_eq_info06?></td>
							</tr>
							<? } ?>

							<tr>
								<th>메모</th>
								<td colspan="5" class="subject"><?=nl2br($rs_eq_memo)?></td>
							</tr>

							<tr>
								<th>상태</th>
								<td>
									<?= getDcodeName($conn, "EQ_STATE", $rs_eq_state); ?>
								</td>
								<th>입고일</th>
								<td><?=$rs_eq_indate?></td>
								<th>패기일</th>
								<td colspan="3"><?=$rs_eq_disdate?></td>
							</tr>

							<tr>
								<th>사용자</th>
								<td><?=getEmpInfo($conn, $rs_eq_user)?></td>
								<th>지급일</th>
								<td><?=$rs_eq_recdate?></td>
								<th>반납일</th>
								<td><?=$rs_eq_retdate?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="btnright">
				<? if ($eq_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_modify();" style="width:100px">수정</button>
					<? } ?>
				<? }?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>

				<h2>자재 내역</h2>  

				<div class="boardlist">
					<table>
						<colgroup>
							<col style="width:5%" /><!-- 번호 -->
							<col style="width:9%" /><!-- 이전사용자 -->
							<col style="width:9%" /><!-- 반납일 -->
							<col style="width:9%" /><!-- 다음사용자 -->
							<col style="width:9%" /><!-- 다음사용자 -->
							<col style="width:12%" /><!-- 변경구분 -->
							<col style="width:23%" /><!-- 변경메모 -->
							<col style="width:12%" /><!-- 변경관리자 -->
							<col style="width:12%" /><!-- 등록일 -->
						</colgroup>
						<thead>
							<tr>
								<th scope="col">번호</th>
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
								<td><?=$PRE_ADM_NAME?></td>
								<td><?=$EQ_RETDATE?></td>
								<td><?=$NEXT_ADM_NAME?></td>
								<td><?=$EQ_RECDATE?></td>
								<td><?=$EQ_STATE?></td>
								<td><?=$EQ_MEMO?></td>
								<td><?=$REG_ADM_NAME?></td>
								<td><?= $REG_DATE ?></td>
							</tr>
						<?
								}
							}
						?>
						</tbody>
					</table>
				</div>

			<!-- // E: mwidthwrap -->

			</div>
		</div>
	</div>

	<!-- //S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>