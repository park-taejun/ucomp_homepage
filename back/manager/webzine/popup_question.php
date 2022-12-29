<?session_start();?>
<?
# =============================================================================
# File Name    : popup_question.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2017-02-06
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
	$menu_right = "WB002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";
	
#====================================================================
# common_header
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/webzine/webzine.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$seq_no					= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$eseq_no				= $_POST['eseq_no']!=''?$_POST['eseq_no']:$_GET['eseq_no'];
	$title					= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$type						= $_POST['type']!=''?$_POST['type']:$_GET['type'];
	$use_tf					= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$title					= SetStringToDB($title);
	
	$result = false;
#====================================================================
# DML Process
#====================================================================

	if ($mode == "D") {

	}

	if (($mode == "S") || ($mode == "R")) {

		$arr_rs = selectQuestion($conn, $eseq_no);
		
		$rs_qseq_no				= trim($arr_rs[0]["QSEQ_NO"]); 
		$rs_title					= trim($arr_rs[0]["TITLE"]); 
		$rs_type					= trim($arr_rs[0]["TYPE"]); 

	}

	$use_tf			= "";
	$del_tf			= "N";

	$arr_rs_dcode = listQuestion($conn, $eseq_no);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "문항 조회", "List");

	if ($result) {
?>	
<script language="javascript">
	//alert('정상 처리 되었습니다.');
</script>
<?
		//exit;
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<script type="text/javascript" src="../js/common.js"></script>
	<script src="../js/jquery-1.11.2.min.js"></script>
<style type="text/css">
	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }
</style>
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  overflow: auto; height: 568px; }
-->
</style>
<script language="javascript">

	var type = "";

	$(document).ready(function(){
		searchList();
	});


	function js_add_question(type) {
		
		var qseq_no = "";
		var url = "question_"+type+".php";
		
		var request = $.ajax({
			url:url,
			type:"POST",
			data:{qseq_no:qseq_no},
			dataType:"html"
		});
		
		request.done(function(msg) {
			$("#question_form").html(msg);
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

	}

	function js_question_cancel() {
		$("#question_form").html("");
	}

	$(document).on("click", ".add_option", function(){
		var add_html = $("#option_txt").html();
		$("#add_option").append(add_html);
	});

	$(document).on("click", ".del_option", function(){
		
		if ($(".add_option").length <= 2) {
			alert('보기는 하나 이상 있어야 합니다.');
			return;
		}

		$(this).parent().remove();
	});

	function js_question_save() {

			var mode = "I";
			var qseq_no = "";
			var eseq_no = "<?=$eseq_no?>";
			
			type = $("#type").val();

			if ($("#qseq_no").val() == "") {
				mode = "I";
			} else {
				mode = "U";
				qseq_no = $("#qseq_no").val();
			}

			if ($("#title").val().trim() == "") {
				alert('질문을 입력해주세요.');
				return;
			}

			var option_data = new Array();

			$(".option_in").each(function(){
				if ($(this).val().trim() != "") {
					//alert($(this).val());
					option_data.push($(this).val()+""+$(this).attr("alt"));
				}
			});
			
			var request = $.ajax({
				url:"question.dml.ajax.php",
				type:"POST",
				data:{mode:mode, eseq_no:eseq_no, type:type, qseq_no:qseq_no, title:$("#title").val().trim(), option_data:option_data},
				dataType:"html"
			});
			
			request.done(function(msg) {
				$("#question_form").html("");
				searchList();
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});

	}

	function searchList() {

			var request = $.ajax({
				url:"question.list.ajax.php",
				type:"POST",
				data:{eseq_no:<?=$eseq_no?>},
				dataType:"html"
			});
			
			request.done(function(msg) {
				$(".boardwrite").html(msg);
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});
	}


	function view_question(qseq_no, type) {
			
			var mode = "S";
			var url = "question_"+type+".php";

			var request = $.ajax({
				url:url,
				type:"POST",
				data:{mode:mode, qseq_no:qseq_no},
				dataType:"html"
			});
			
			request.done(function(msg) {
				$("#question_form").html(msg);
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});
		}


	function delete_question(seq_no) {

			var mode = "D";

			bDelOK = confirm('문항을 삭제 하시겠습니까?\n문항을 삭제 하면 해당 문항의 예문도 같이 삭제 됩니다.');
			
			if (bDelOK==true) {
		
				var request = $.ajax({
					url:"question.dml.ajax.php",
					type:"POST",
					data:{mode:mode, qseq_no:seq_no},
					dataType:"html"
				});
			
				request.done(function(msg) {
					searchList();
				});

				request.fail(function(jqXHR, textStatus) {
					alert("Request failed : " +textStatus);
					return false;
				});
			}
	}

</script>

</head>
<body id="popup_stock">

<form name="frm" method="post">
<input type="hidden" name="mode" id="mode" value="" >
<input type="hidden" name="eseq_no" id="eseq_no" value="<?= $eseq_no?>">

<div id="popupwrap_stock">
	<h1>설문 항목 등록</h1>
	<div id="postsch">
		<h2>* 웹진 독자의견에 질문을 등록하는 화면 입니다.</h2>
		<div class="addr_inp">
		
		<div style="width:95%; padding:20px 5px 5px 5px">
			문항 추가 : 
			<input type="button" name="aa" value=" 객관식 문항 추가 " class="btntxt"  style="cursor:pointer;height:25px;" onclick="js_add_question('type01');">&nbsp;
			<input type="button" name="bb" value=" 주관식 문항 추가 " class="btntxt"  style="cursor:pointer;height:25px;" onclick="js_add_question('type02');"> 
		</div>

		<div id="question_form">

		</div>

		</div>
		<div class="btn">
			<input type="button" name="aa" value=" 저장 " class="btntxt"  style="cursor:pointer;height:25px;" onclick="js_question_save();">
			<input type="button" name="aa" value=" 취소 " class="btntxt"  style="cursor:pointer;height:25px;" onclick="js_question_cancel();">
		</div>

		<div id="pop_table_scroll">
			<div class="testformbox">
				<div class="boardwrite">
					
				</div>
			</div>
		</div>
	</div>
	<br />
</div>

<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>