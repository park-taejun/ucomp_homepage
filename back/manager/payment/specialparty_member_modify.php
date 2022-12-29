<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : payment_write.php
# Modlue       : 
# Writer       : GIRINGRIM 
# Create Date  : 2016-04-06
# Modify Date  : 
#	Copyright : Copyright @GIRINGRIM.Com. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "PM003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/payment/payment.php";

#====================================================================
# Request Parameter
#====================================================================

	$mm_subtree	 = "7";

	$seq_no				= trim($seq_no);

	$arr_rs = selectSpeMember($conn, $seq_no);
//SEQ_NO, M_NAME, M_BIRTH, M_HP, AMOUNT
	$rs_seq_no					= trim($arr_rs[0]["SEQ_NO"]); 
	$rs_m_name				= trim($arr_rs[0]["M_NAME"]); 
	$rs_m_birth				= trim($arr_rs[0]["M_BIRTH"]); 
	$rs_m_hp					= trim($arr_rs[0]["M_HP"]);
	$rs_amount					= trim($arr_rs[0]["AMOUNT"]);

	$str_m_hp = decrypt($key, $iv, $rs_m_hp);

	$rs_m_birth_yy = left($rs_m_birth,4);
	$rs_m_birth_mm = right(left($rs_m_birth,6),2);
	$rs_m_birth_dd = right($rs_m_birth,2);


	$arr_m_tel = explode("-",$str_m_hp);


	#List Parameter
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$mode 				= trim($mode);

	$result = false;
#====================================================================
# DML Process
#====================================================================

	if ($mode == "D") {
		$result = deleteToRealSpeMember($conn, $seq_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "특별당비대상 삭제 (".$seq_no.")", "Delete");
	}

	if ($mode == "U") {
		$result = updateSpeMember($conn, $seq_no, $amount, $s_adm_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "특별당비대상 수정 (".$seq_no.")", "Update");

	}


	if (($mode == "D")&&($result)) {
		?>
		<script type="text/javascript">
		<!--
			alert('삭제되었습니다.');
			location.href="specialparty_member_list.php?p_no=<?=$p_no?>&nPage=<?=$nPage?>&search_field=<?=$search_field?>&search_str=<?$search_str?>";
		//-->
		</script>
		<?
		die;
	}

	if (($mode == "U")&&($result)) {
		?>
		<script type="text/javascript">
		<!--
			alert('수정되었습니다.');
			location.href="specialparty_member_list.php?p_no=<?=$p_no?>&nPage=<?=$nPage?>&search_field=<?=$search_field?>&search_str=<?$search_str?>";
		//-->
		</script>
		<?
		die;
	}
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />
<link href="../js/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/common.js"></script>
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

<script language="javascript">

	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "specialparty_member_list.php";
		frm.submit();
	}

	function js_delete() {
		var frm = document.frm;
		var mode = "D";
		var seq_no = <?=$seq_no?>;

		bDelOK = confirm('특별당비회원 정보를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.seq_no.value = seq_no;
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}

	}



	function chk_data() {
		var frm = document.frm;
		var mode = "U";
		var seq_no = <?=$seq_no?>;
/*
		if (frm.m_name.value == "") {
			alert("이름을 입력해주세요.");
			return;
		}

		if (frm.sel_year.value == "") {
			alert("년도를 선택해주세요.");
			return;
		}

		if (frm.sel_month.value == "") {
			alert("월을 선택해주세요.");
			return;
		}

		if (frm.sel_day.value == "") {
			alert("일을 선택해주세요.");
			return;
		}

		frm.m_birth.value=frm.sel_year.value+frm.sel_month.value+frm.sel_day.value;
		
		if (frm.mtel_01.value == "") {
			alert("휴대전화번호를 입력해주세요.");
			return;
		}

		if (frm.mtel_02.value == "") {
			alert("휴대전화번호를 입력해주세요.");
			return;
		}

		if (frm.mtel_03.value == "") {
			alert("휴대전화번호를 입력해주세요.");
			return;
		}
*/
		if (frm.amount.value == "") {
			alert("특별당비금액을 입력해주세요.");
			return;
		}

		frm.seq_no.value = seq_no;
		frm.mode.value = "U";
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
		<form name="frm" method="post">
	
		<input type="hidden" name="mode" >
		<input type="hidden" name="seq_no" >
		<input type="hidden" name="p_no" value="<?=$p_no?>">
		<input type="hidden" name="nPage" value="<?=$nPage?>">
		<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
		<input type="hidden" name="search_field" value="<?=$search_field?>">
		<input type="hidden" name="search_str" value="<?=$search_str?>">

		<input type="hidden" id="m_no" name="m_no">

		<h3 class="conTitle"><?=$p_menu_name?></h3>
			<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
				<caption>게시판 생성</caption>
				<colgroup>
						<col width="10%" />
						<col width="90%" />
				</colgroup>
				<tbody>


					<tr>
						<th>이름</td>
						<td ><?=$rs_m_name?></td>
					</tr>
					<tr>
						<th>생년월일</td>
						<td><?=$rs_m_birth?></td>
					</tr>
					<tr>
						<th>휴대전화</td>
						<td><?=$str_m_hp?></tr>
					<tr>
						<th>특별당비금액</td>
						<td>
							<input type="Text" name="amount" id="amount" style="width:95px;" class="txt" value="<?=$rs_amount?>">
						</td>
					</tr>
				</tbody>
			</table>

			<div class="btnArea">
				<ul class="fRight">
					<? 
						if ($s_adm_no == $rs_reg_adm || $sPageRight_U == "Y") {
							echo '<li><a href="javascript:chk_data();"><img src="../images/btn/btn_rewrite.gif" alt="수정" /></a></li>';
							if ($seq_no <> "") {
								echo '<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>';
							}
						}
					?>
					<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
				</ul>
			</div>

		</section>

<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>
