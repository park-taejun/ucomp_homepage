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

	#List Parameter
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$mode 				= trim($mode);
	$m_no				= trim($m_no);
	
	$result = false;
#====================================================================
# DML Process
#====================================================================


	if ($mode == "I") {

	}

	if ($new_mem_no) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&m_no=".$m_no."&mode=S&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree;;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "member_list.php<?=$strParam?>";
</script>
<?
		exit;
	}
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
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
<script src="../js/common.js"></script>

<script language="javascript">
	$(document).ready(function() {

		$(".date").datepicker({
			dateFormat: "yy-mm-dd"
		});

	});

	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "specialparty_list.php";
		frm.submit();
	}

	function set_member_info(m_no) {

		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "specialparty_write.php";
		frm.submit();

	}

	function chk_data() {
		
		var mode = "I";
		var title = $("#title").val().trim();
		var memo = $("#memo").val().trim();
		var pay_date = $("#pay_date").val().trim();

		if (title == "") {
			alert("특별당비 제목을 입력해주세요.");
			return;
		}

		if (pay_date == "") {
			alert("출금일을 선택 하세요.");
			return;
		}

		var request = $.ajax({
			url:"/manager/payment/specialparty_dml.php",
			type:"POST",
			data:{mode:mode, title:title, memo:memo, pay_date:pay_date},
			dataType:"html"
		});

		request.done(function(msg) {
			
			if (msg == "T") {
				
				bDelOK = confirm('특별당비가 등록 처리 되었습니다.\n계속 등록 하시겠습니까?');
				
				if (bDelOK==true) {
					frm.reset();
					set_member_info(0);
				} else {
					js_list();
				}
				return;
			}

			if (msg == "F") {
				alert("특별당비 등록을 실패 하였습니다.");
				return;
			}

		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});


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

		<h3 class="conTitle"><?=$p_menu_name?></h3>
			<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
				<caption>게시판 생성</caption>
				<colgroup>
						<col width="10%" />
						<col width="90%" />
				</colgroup>
				<tbody>
					<tr>
						<th>제목</td>
						<td>
							<input type="Text" name="title" value="" id="title" style="width:500px;" class="txt" placeholder="특별당비 제목을 입력해주세요.">
						</td>
					</tr>
					<tr>
						<th>출금일</td>
						<td>
							<input type="Text" name="pay_date" value="" id="pay_date" style="width:95px;" class="date" >
						</td>
					</tr>
					<tr>
						<th>메모</td>
						<td>
							<textarea id="memo" name="memo" style="width:80%;" ></textarea>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="btnArea">
				<ul class="fRight">
					<? 
						if ($s_adm_no == $rs_reg_adm || $sPageRight_I == "Y") {
							echo '<li><a href="javascript:chk_data();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>';
							if ($m_no <> "") {
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
