<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : vacation_dml.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-05-02
# Modify Date  : 
#	Copyright : Copyright @ucom Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "VA003"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

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
	require "../../_classes/biz/vacation/vacation.php";
	require "../../_classes/biz/holiday/holiday.php";
	require "../../_classes/biz/admin/admin.php";

	$seq_no				= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$selectedDate		= $_POST['selectedDate']!=''?$_POST['selectedDate']:$_GET['selectedDate'];

	$arr_rs				= selectVacation($conn, $seq_no);

	$year				= "202206";

	$VA_USER			= $arr_rs[0]["VA_USER"];
	$VA_TYPE			= $arr_rs[0]["VA_TYPE"];
	$VA_SDATE			= $arr_rs[0]["VA_SDATE"];
	$VA_EDATE			= $arr_rs[0]["VA_EDATE"];
	$VA_MDATE			= $arr_rs[0]["VA_MDATE"];
	$VA_HPHONE			= $arr_rs[0]["VA_HPHONE"];
	$VA_WAY				= $arr_rs[0]["VA_WAY"];

	$VA_WAY				= str_replace("phone", "전화 ", $VA_WAY);
	$VA_WAY				= str_replace("teams", "팀즈 ", $VA_WAY);
	$VA_WAY				= str_replace("email", "이메일 ", $VA_WAY);

	if($VA_SDATE <> $VA_EDATE) {
		$va_date = $VA_SDATE." ~ ". substr($VA_EDATE, 5, 5);
	} else {
		if($VA_SDATE <> "") {
			$va_date = $VA_SDATE;
		} else {
			$va_date = $VA_MDATE;
		}
	}

	$VA_MEMO			= str_replace("\n\n", "\n", $arr_rs[0]["VA_MEMO"]);
	$VA_MEMO			= str_replace("\n", "<br>", $VA_MEMO);
	$ADM_NAME			= selectAdminName($conn, $VA_USER);
	$HEADQUARTERS_CODE	= selectAdminHeadquarters($conn, $VA_USER, $year);
	$DEPT_CODE			= selectAdminDept($conn, $VA_USER, $year);
	$POSITION_CODE		= selectAdminPosition($conn, $VA_USER, $year);

?>
					<!-- popup-content-body -->
					<div class="popup-content-body">
						<!-- goods-display -->
						<div class="goods-display module-a style-b type-a">
							<div class="goods-list">
								<div class="goods-item">
									<div class="goods-wrap">
										<div class="goods-inform">
											<div class="goods-head">
												<p class="goods-title">
													<span class="goods-name" id="goods-name"><?=$ADM_NAME?></span>
													<span class="goods-position" id="goods-position"><?=$POSITION_CODE?></span>
													<span class="goods-department" id="goods-department"><?=$HEADQUARTERS_CODE?></span>
													<span class="goods-team" id="goods-team"><?=$DEPT_CODE?></span>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- //goods-display -->
						<? if ($VA_TYPE == 5) { ?>
						<!-- data-display -->
						<div class="data-display module-a style-a type-a small">
							<ul class="data-list">
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">일자</span></div>
										<div class="data-body"><?=$va_date?></div>
									</div>
								</li>
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">업무연락</span></div>
										<div class="data-body"><?=$VA_WAY?></div>
									</div>
								</li>
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">업무내역</span></div>
										<div class="data-body"><?=$VA_MEMO?></div>
									</div>
								</li>
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">연락처</span></div>
										<div class="data-body"><?=$VA_HPHONE?></div>
									</div>
								</li>
							</ul>
						</div>
						<!-- //data-display -->
						<? } else { ?>
						<!-- data-display -->
						<div class="data-display module-a style-a type-a small">
							<ul class="data-list">
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">일자</span></div>
										<div class="data-body"><?=$va_date?></div>
									</div>
								</li>
								<li class="data-item">
									<div class="data-wrap">
										<div class="data-head"><span class="data-name">사유</span></div>
										<div class="data-body"><?=$VA_MEMO?></div>

									</div>
								</li>
							</ul>
						</div>
						<!-- //data-display -->
						<? } ?>
					</div>
					<!-- //popup-content-body -->

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>