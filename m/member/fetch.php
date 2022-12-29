<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : admin_list.php
# Modlue       : 
# Writer       : JeGal Jeong 
# Create Date  : 2022-04-28
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AD002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header_mobile.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";

	if ($con_use_tf == "") $con_use_tf = "Y";

	$limit							= $_POST['limit']!=''?$_POST['limit']:$_GET['limit'];
	$start							= $_POST['start']!=''?$_POST['start']:$_GET['start'];

	$del_tf = "N";

	//$query = "SELECT * FROM TBL_ADMIN_INFO ORDER BY ADM_NO DESC LIMIT ".$start.", ".$limit. "";
	//$arr_rs = mysql_query($query, $conn);

	$arr_rs = listAdminTestScroll($conn, $con_group_no, $con_headquarters_code, $con_dept_code, $con_position_code, $con_use_tf, $del_tf, $search_field, $search_str, $start, $limit);

	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs) ; $j++) {
			
			$ADM_ID						= trim($arr_rs[$j]["ADM_ID"]);
			$ADM_NO						= trim($arr_rs[$j]["ADM_NO"]);
			$ADM_NAME					= SetStringFromDB($arr_rs[$j]["ADM_NAME"]);
			$ADM_HPHONE				= trim($arr_rs[$j]["ADM_HPHONE"]);
			$ADM_EMAIL				= trim($arr_rs[$j]["ADM_EMAIL"]);
			$GROUP_NO					= trim($arr_rs[$j]["GROUP_NO"]);
			$COM_CODE					= trim($arr_rs[$j]["COM_CODE"]);
			$HEADQUARTERS_CODE= trim($arr_rs[$j]["HEADQUARTERS_CODE"]);
			$DEPT_CODE				= trim($arr_rs[$j]["DEPT_CODE"]);
			$POSITION_CODE		= trim($arr_rs[$j]["POSITION_CODE"]);
			$LEADER_TITLE			= trim($arr_rs[$j]["LEADER_TITLE"]);
			$DEPT_NAME				= trim($arr_rs[$j]["DEPT_NAME"]);
			$POSITION_NAME		= trim($arr_rs[$j]["POSITION_NAME"]); //관리자그룹이름
			$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
			$DEL_TF						= trim($arr_rs[$j]["DEL_TF"]);
			$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
			$ENTER_DATE				= trim($arr_rs[$j]["ENTER_DATE"]);

			$ADM_PROFILE			= trim($arr_rs[$j]["PROFILE"]); //사진 추가
			if ($ADM_PROFILE) { 
					$IMG_SRC="/upload_data/profile/".$ADM_PROFILE;
			} else {
					$IMG_SRC="/upload_data/profile/sys1.png";	
			}

			$GROUP_NAME			= getGroupName($conn, $GROUP_NO); 
			//$DEPT_NAME			= getDcodeName($conn, "DEPT", $DEPT_CODE); 
			//$POSITION_NAME	= getDcodeName($conn, "POSITION", $POSITION_CODE); 
			$CP_NM					= getCompanyName($conn, $COM_CODE); 

			$VA_TYPE		= selectAdminVacation($conn, $ADM_NO); //사용자 연차 및 스마트데이 확인용
			$VA_NAME		= getDcodeName($conn, "VA_TYPE", $VA_TYPE);

			switch ($VA_NAME) {																						////////////////////// ① 확인
				case "스마트데이" : $va_display = "type-fill accent-03";
					break;
				case "연차" : $va_display = "type-line normal-04";
					break;
				case "오전반차" : $va_display = "type-line normal-04";
					break;
				case "하계,동계휴가" : $va_display = "type-line normal-04";
					break;
				case "미사용연차" : $va_display = "type-line normal-04";
					break;
				case "미사용반차" : $va_display = "type-line normal-04";
					break;
				case "오후반차" : $va_display = "type-line normal-04";
					break;
				default : $va_display = "type-line normal-10";
			}

			$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

			//$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;

/*
			echo "
				<div id= 'load_data'>".$ADM_NAME."</div>
			";
*/
?>
<!--
	<div id="load_data">
		<?=$ADM_NAME?>
	</div>
-->
	<div class="goods-item">
		<div class="goods-wrap" style="--default-picture: url(<?=$IMG_SRC?>);">
			<div class="goods-inform">
				<div class="goods-head">
					<p class="goods-title">
						<span class="goods-name"><?=$ADM_NAME?></span>
						<span class="goods-position"><?=$POSITION_NAME?></span>
						<? if ($HEADQUARTERS_CODE <> "") { ?>
							<span class="goods-department"><?=$HEADQUARTERS_CODE?></span>
						<? } ?>
						<? if ($DEPT_CODE <> "") { ?>
							<span class="goods-team"><?=$DEPT_CODE?></span>
						<? } ?>
					</p>
				</div>
				<div class="goods-type">
					<p class="data-list">
						<span class="data-item"><span class="lamp module-a style-c <?=$va_display?> small"><span class="lamp-text"><?=$VA_NAME?></span></span></span>
					</p>
				</div>
				<div class="goods-data">
					<ul class="data-list">
						<li class="data-item mobilephone">
							<div class="head">휴대전화</div>
							<div class="body">
								<div class="text" tabindex="0"><?=$ADM_HPHONE?></div>
								<input type="hidden" id="adm_hp_<?=$j?>" value="<?=$ADM_HPHONE?>">
								<!-- tooltip -->
								<div class="tooltip module-a style-a type-b">
									<div class="tooltip-wrap">
										<div class="tooltip-body">
											<ul class="data-list">
												<li class="data-item"><a href="tel:010-1234-5678">전화걸기</a></li>
												<li class="data-item"><a href="javascript:void(0);" class="clipboard" data-clipboard-text="<?=$ADM_HPHONE?>">복사하기</a></li>
											</ul>
										</div>
									</div>
								</div>
								<!-- //tooltip -->
							</div>
						</li>
						<li class="data-item email">
							<span class="head">이메일</span>
							<span class="body"><a class="text" href="mailto:<?=$ADM_EMAIL?>"><?=$ADM_EMAIL?></a></span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

<?	
		}
	}// else { 
?> 
<?
//	} 
?>
