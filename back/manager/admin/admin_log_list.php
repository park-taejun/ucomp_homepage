<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : admin_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright : Copyright @jinhak Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AD005"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#$sPageRight_F		= "Y";

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
	require "../../_classes/biz/admin/admin.php";

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$adm_no							= $_POST['adm_no']!=''?$_POST['adm_no']:$_GET['adm_no'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$start_date					= $_POST['start_date']!=''?$_POST['start_date']:$_GET['start_date'];
	$end_date						= $_POST['end_date']!=''?$_POST['end_date']:$_GET['end_date'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$start_date					= $_POST['start_date']!=''?$_POST['start_date']:$_GET['start_date'];
	$end_date						= $_POST['end_date']!=''?$_POST['end_date']:$_GET['end_date'];

	$task_type					= $_POST['task_type']!=''?$_POST['task_type']:$_GET['task_type'];
	//$con_group_no							= $_POST['con_group_no']!=''?$_POST['con_group_no']:$_GET['con_group_no'];

	if ($start_date == "") {
		$start_date = date("Y-m-d",strtotime("-1 month"));
	} else {
		$start_date = trim($start_date);
	}

	if ($end_date == "") {
		$end_date = date("Y-m-d",strtotime("0 month"));
	} else {
		$end_date = trim($end_date);
	}

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= SetStringToDB($nPage);
	$nPageSize	= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf				= SetStringToDB($use_tf);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

	if ($nPage <> "" && $nPageSize <> 0) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "" && $nPageSize <> 0) {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntAdminLog($conn, $start_date, $end_date, $task_type, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	#$del_tf = "Y";

	$arr_rs = listAdminLog($conn, $start_date, $end_date, $task_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	#echo sizeof($arr_rs);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 접속 로그 리스트 조회", "List");

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />

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
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>

<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>

<script type="text/javascript" >

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "admin_log_list.php";
		frm.submit();
	}
	

	function js_excel_print() {

		var frm = document.frm;
		frm.method = "post";
		frm.action = "admin_log_excel_list.php";
		frm.submit();
	}

	$(document).ready(function() {
		$(".date").datepicker({
			dateFormat: "yy-mm-dd"
			,minDate: new Date(2013, 4-1, 15)	//(연, 월-1, 일)
		//,maxDate: new Date(2012, 9-1, 14)	//(연, 월-1, 일)
		});

	});

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

		<form id="bbsList" name="frm" method="post">
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							<tr class="last">
								<th>조회기간</th>
								<td>
									<input type="text" name="start_date" class="date" value="<?=$start_date?>" style="width:70px" readonly=1/> ~ 
									<input type="text" name="end_date" class="date" value="<?=$end_date?>" style="width:70px" readonly=1/>
								</td>
								<th>업무구분</th>
								<td>
									<select name="task_type">
										<option value="">전체</option>
										<option value="Login" <? if ($task_type == "Login") echo "selected"; ?>>로그인</option>
										<option value="Logout" <? if ($task_type == "Logout") echo "selected"; ?>>로그아웃</option>
										<option value="List" <? if ($task_type == "List") echo "selected"; ?>>리스트조회</option>
										<option value="Excel" <? if ($task_type == "Excel") echo "selected"; ?>>엑셀조회</option>
										<option value="Read" <? if ($task_type == "Read") echo "selected"; ?>>조회</option>
										<option value="Insert" <? if ($task_type == "Insert") echo "selected"; ?>>등록</option>
										<option value="Update" <? if ($task_type == "Update") echo "selected"; ?>>수정</option>
										<option value="Delete" <? if ($task_type == "Delete") echo "selected"; ?>>삭제</option>
									</select>
								</td>
								<th>검색조건</th>
								<td>
									<select name="search_field" style="width:84px;">
										<option value="LOG_ID" <? if ($search_field == "LOG_ID") echo "selected"; ?> >아이디</option>
										<option value="LOG_IP" <? if ($search_field == "LOG_IP") echo "selected"; ?> >아이피</option>
										<option value="TASK" <? if ($search_field == "TASK") echo "selected"; ?> >업무내용</option>
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
							<a href="javascript:js_excel_print();" class="btn_type6">선택한 항목 엑셀로 받기</a>
						</p>
					</div>


				<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
					<caption><?=$p_menu_name?></caption>
					<colgroup>
						<col width="6%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="40%" />
						<col width="10%" />
						<col width="14%" />
					</colgroup>
					<thead>
					<tr>
						<th scope="col">번호</th>
						<th scope="col">ID</th>
						<th scope="col">관리자</th>
						<th scope="col">업무구분</th>
						<th scope="col">업무내용</th>
						<th scope="col">접속아이피</th>
						<th scope="col">처리일시</th>
					</tr>
				</thead>
				<tbody>

				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							//ADM_ID, ADM_NO, PASSWD, ADM_NAME, ADM_INFO, ADM_HPHONE, ADM_PHONE, ADM_EMAIL, 
							//GROUP_NO, ADM_FLAG, POSITION_CODE, DEPT_CODE, COM_CODE, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE,
							//GROUP_NAME

							$LOG_ID				= trim($arr_rs[$j]["LOG_ID"]);
							$LOG_IP				= trim($arr_rs[$j]["LOG_IP"]);
							$TASK					= trim($arr_rs[$j]["TASK"]);
							$TASK_TYPE		= trim($arr_rs[$j]["TASK_TYPE"]);
							$LOGIN_DATE		= trim($arr_rs[$j]["LOGIN_DATE"]);
							$ADM_NAME			= trim($arr_rs[$j]["ADM_NAME"]);

							$LOGIN_DATE = date("Y-m-d H:i:s",strtotime($LOGIN_DATE));
							
							$offset = $nPageSize * ($nPage-1);
							$logical_num = ($nListCnt - $offset);
							$rn = $logical_num - $j;
				?>

						<tr>
							<td><?=$rn?></td>
							<td><?= $LOG_ID ?></td>
							<td><?= $ADM_NAME ?></td>
							<td><?= $TASK_TYPE ?></td>
							<td style="text-align:left;padding-left:10px"><?= $TASK ?></td>
							<td><?= $LOG_IP ?></td>
							<td><?= $LOGIN_DATE ?></td>
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
			<br />
			<div id="bbspgno">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&start_date=".$start_date."&end_date=".$end_date."&task_type=".$task_type;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
			</div>

		</section>
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
