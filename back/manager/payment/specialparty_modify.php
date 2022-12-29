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
	require "../../_classes/biz/member/member.php";

#====================================================================
# Request Parameter
#====================================================================

	$mm_subtree	 = "7";

	$seq_no				= trim($seq_no);

	$arr_rs = selectSpeParty($conn, $seq_no);
//SEQ_NO, TITLE, PAY_DATE, MEMO
	$rs_seq_no					= trim($arr_rs[0]["SEQ_NO"]); 
	$rs_title						= trim($arr_rs[0]["TITLE"]); 
	$rs_pay_date		= trim($arr_rs[0]["PAY_DATE"]); 
	$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);
	$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]);
	$rs_memo				= trim($arr_rs[0]["MEMO"]);
	$rs_del_adm					= trim($arr_rs[0]["DEL_ADM"]);
	$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]);
	$rs_adm_name				= trim($arr_rs[0]["ADM_NAME"]);

	if ($rs_pay_date) {
		$rs_pay_date = left($rs_pay_date, 4)."-".right(left($rs_pay_date, 6),2)."-".right($rs_pay_date,2);
	}

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

	function js_delete() {

		var mode = "D";
		var seq_no = <?=$seq_no?>;
		
		bDelOK = confirm('특별당비 정보를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {

			var request = $.ajax({
				url:"/manager/payment/specialparty_dml.php",
				type:"POST",
				data:{mode:mode, seq_no:seq_no},
				dataType:"html"
			});

			request.done(function(msg) {
				if (msg == "T") {
					alert('특별당비 정보가 삭제 되었습니다.');
					js_list();
					return;
				}

				if (msg == "F") {
					alert("특별당비 정보 삭제를 실패 하였습니다.");
					return;
				}

			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});


		}

	}

	function set_member_info(m_no) {

		var request = $.ajax({
			url:"/manager/member/get_member_info.php",
			type:"POST",
			data:{m_no:m_no},
			dataType:"html"
		});

		request.done(function(msg) {
			$("#member_info").html(msg);
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

	}

	function chk_data() {
		
		var mode = "U";
		var seq_no = <?=$seq_no?>;
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
			data:{mode:mode, title:title, memo:memo, pay_date:pay_date,seq_no:seq_no},
			dataType:"html"
		});

		request.done(function(msg) {
			
			if (msg == "T") {
				
				alert('특별당비 정보가 수정 되었습니다.');
				js_list();
				
				return;
			}

			if (msg == "F") {
				alert("특별당비 정보 수정이 실패 하였습니다.");
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

		<input type="hidden" name="sel_pay_yyyy" value="<?=$sel_pay_yyyy?>">
		<input type="hidden" name="sel_pay_mm" value="<?=$sel_pay_mm?>">
		<input type="hidden" name="sel_area_cd" value="<?=$sel_area_cd?>">
		<input type="hidden" name="sel_pay_type" value="<?=$sel_pay_type?>">
		<input type="hidden" name="sel_party" value="<?=$sel_party?>">
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
						<td >
							<input type="Text" name="title" value="<?=$rs_title?>" id="title" style="width:500px;" class="txt" placeholder="특별당비 제목을 입력해주세요.">
						</td>
					</tr>

					<tr>
						<th>출금일</td>
						<td>
							<input type="Text" name="pay_date" id="pay_date" style="width:95px;" class="date" value="<?=$rs_pay_date?>">
						</td>
					</tr>
					<tr>
						<th>납부메모</td>
						<td >
							<textarea id="memo" name="memo" style="width:80%;"><?=$rs_memo?></textarea>
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
