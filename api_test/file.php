<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : board_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2014.10.13
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$writer_id	= $s_adm_id;//작성자 아이디:로그인한 사용자 아이디
	$b_code			= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_code			= trim($b_code);

	//echo $b_code;

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	$menu_right = $b_code; // 메뉴마다 셋팅 해 주어야 합니다

	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	#require "../../_classes/biz/category/category.php";
	require "../../_classes/biz/board/board.php";

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$config_no					= $_POST['config_no']!=''?$_POST['config_no']:$_GET['config_no'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$top_tf							= $_POST['top_tf']!=''?$_POST['top_tf']:$_GET['top_tf'];
	$new_tf							= $_POST['new_tf']!=''?$_POST['new_tf']:$_GET['new_tf'];
	$b_code							= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_no								= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];
	$parent_no					= $_POST['parent_no']!=''?$_POST['parent_no']:$_GET['parent_no'];
	$b_po								= $_POST['b_po']!=''?$_POST['b_po']:$_GET['b_po'];
	$b_re								= $_POST['b_re']!=''?$_POST['b_re']:$_GET['b_re'];
	$cate_01						= $_POST['cate_01']!=''?$_POST['cate_01']:$_GET['cate_01'];
	$cate_02						= $_POST['cate_02']!=''?$_POST['cate_02']:$_GET['cate_02'];
	$cate_03						= $_POST['cate_03']!=''?$_POST['cate_03']:$_GET['cate_03'];
	$cate_04						= $_POST['cate_04']!=''?$_POST['cate_04']:$_GET['cate_04'];

	$secret_tf					= $_POST['secret_tf']!=''?$_POST['secret_tf']:$_GET['secret_tf'];
	
	$writer_nm					= $_POST['writer_nm']!=''?$_POST['writer_nm']:$_GET['writer_nm'];
	$writer_pw					= $_POST['writer_pw']!=''?$_POST['writer_pw']:$_GET['writer_pw'];

	$email							= $_POST['email']!=''?$_POST['email']:$_GET['email'];
	$phone							= $_POST['phone']!=''?$_POST['phone']:$_GET['phone'];
	$homepage						= $_POST['homepage']!=''?$_POST['homepage']:$_GET['homepage'];
	$title							= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$contents						= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];
	$link01							= $_POST['link01']!=''?$_POST['link01']:$_GET['link01'];
	$link02							= $_POST['link02']!=''?$_POST['link02']:$_GET['link02'];
	$keyword						= $_POST['keyword']!=''?$_POST['keyword']:$_GET['keyword'];

	$reply							= $_POST['reply']!=''?$_POST['reply']:$_GET['reply'];
	$reply_state				= $_POST['reply_state']!=''?$_POST['reply_state']:$_GET['reply_state'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$reg_date_ymd				= $_POST['reg_date_ymd']!=''?$_POST['reg_date_ymd']:$_GET['reg_date_ymd'];
	$reg_date_time			= $_POST['reg_date_time']!=''?$_POST['reg_date_time']:$_GET['reg_date_time'];

	$comment_tf					= $_POST['comment_tf']!=''?$_POST['comment_tf']:$_GET['comment_tf'];

	$flag01							= $_POST['flag01']!=''?$_POST['flag01']:$_GET['flag01'];
	$old_file_nm				= $_POST['old_file_nm']!=''?$_POST['old_file_nm']:$_GET['old_file_nm'];
	$old_file_rnm				= $_POST['old_file_rnm']!=''?$_POST['old_file_rnm']:$_GET['old_file_rnm'];
	$file_name					= $_POST['file_name']!=''?$_POST['file_name']:$_GET['file_name'];

	if ($b_code == "") $b_code = "B_1_1";
	
	$nPage					= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);

	$cate_01				= SetStringToDB($cate_01);
	$cate_02				= SetStringToDB($cate_02);
	$cate_03				= SetStringToDB($cate_03);
	$cate_04				= SetStringToDB($cate_04);

	$writer_id			= SetStringToDB($writer_id);
	$writer_nm			= SetStringToDB($writer_nm);
	$writer_pw			= SetStringToDB($writer_pw);
	$email					= SetStringToDB($email);

	$phone					= SetStringToDB($phone);
	$homepage				= SetStringToDB($homepage);
	$title					= SetStringToDB($title);
	$contents				= SetStringToDB($contents);
	$recomm					= SetStringToDB($recomm);
	$keyword				= SetStringToDB($keyword);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);

#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/config_info.php";
#====================================================================
# Board Config End
#====================================================================

	$ref_ip = $_SERVER['REMOTE_ADDR'];

#====================================================================
# DML Process
#====================================================================

	require "../../_common/board/dml.php";

	if ($mode == "AU") {


		$reply = SetStringToDB($reply);
		$result = updateQnaAnswer($conn, $reply, $s_adm_no, $reply_state, $b_code, $b_no);
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&b_code=".$b_code."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;

		if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "board_read.php<?=$strParam?>";
</script>
</head>
</html>
<?
			exit;
		}
	}

	if ($mode == "D") {
		require "../../_common/board/del.php";
	}

	if ($mode == "T") {
		updateBannerUseTF($conn, $use_tf, $s_adm_no, $b_code, $b_no);
	}
	

	if ($mode == "R") {

		$arr_rs = selectBoard($conn, $b_code, $b_no);

		$rs_b_no						= trim($arr_rs[0]["B_NO"]); 
		$rs_b_re						= trim($arr_rs[0]["B_RE"]); 
		$rs_b_po						= trim($arr_rs[0]["B_PO"]); 
		$rs_b_code					= trim($arr_rs[0]["B_CODE"]); 
		$rs_secret_tf				= trim($arr_rs[0]["SECRET_TF"]); 

		$rs_title						= trim($arr_rs[0]["TITLE"]);

	}

	if ($mode == "S") {

#====================================================================
# Board Config Start
#====================================================================
		require "../../_common/board/read.php";
#====================================================================
# Board Config End
#====================================================================

	} else {
		$rs_writer_nm = $s_adm_nm;
		$rs_email			= $s_adm_email;
	}

	
	//echo $rs_reg_date;


	if ($rs_reg_date <> "") {
		$reg_date_ymd = left($rs_reg_date,10);
		$reg_date_time = right($rs_reg_date,8);
	} else {
		$reg_date_ymd = date("Y-m-d",strtotime("0 day"));
		$reg_date_time = date("H:i:s",strtotime("0 day"));
	}


	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&b_code=".$b_code."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {
		$board_go_page="board_list.php";
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "<?=$board_go_page?><?=$strParam?>";
</script>
</head>
</html>
<?
		exit;
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
<script type="text/javascript" src="../../_common/SE2.1.1.8141/js/HuskyEZCreator.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript">
<!--

$(document).ready(function() {
	$(".date").datepicker({
		changeMonth: true,
    changeYear: true,
		dateFormat: "yy-mm-dd"
		,minDate: new Date(1970, 4-1, 15)	//(연, 월-1, 일)
	//,maxDate: new Date(2012, 9-1, 14)	//(연, 월-1, 일)
	});
});


function js_list() {
	document.location = "board_list.php<?=$strParam?>";
}

function js_reply() {
	var frm = document.frm;

	frm.contents.value = "";
	frm.mode.value = "R";
	frm.target = "";
	frm.method = "get";
	frm.action = "board_write.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var b_no = "<?= $b_no ?>";
	
	if(document.frm.title.value==""){
		alert('제목을 입력해주세요.');
		document.frm.title.focus();
		return;
	}

	if(document.frm.writer_nm.value==""){
		alert('작성자를 입력해주세요.');
		document.frm.writer_nm.focus();
		return;
	}

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (document.frm.rd_comment_tf == null) {
		//alert(document.frm.rd_comment_tf);
	} else {
		if (frm.rd_comment_tf[0].checked == true) {
			frm.comment_tf.value = "Y";
		} else {
			frm.comment_tf.value = "N";
		}
	}

	//alert(frm.main_tf.value);

	if (isNull(b_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.b_no.value = frm.b_no.value;
	}

<? if ($mode == "R") {?>
		frm.mode.value = "IR";
<? }?>

<? if ($b_html_tf == "Y") { ?>
	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.
<? }?>

	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_answer() {

	var frm = document.frm;
	var b_no = "<?= $b_no ?>";
	
	frm.reply_state.value = "Y";

	if (isNull(b_no)) {
		return;
	} else {
		frm.mode.value = "AU";
		frm.b_no.value = b_no;
	}

	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_view(seq) {

	var frm = document.frm;
	frm.seq_no.value = seq;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "get";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}

function file_change(file) { 
	document.getElementById("file_name").value = file; 
}


function js_delete() {

	var frm = document.frm;
//	var chk_cnt = 0;

//	check=document.getElementsByName("chk[]");
	
//	for (i=0;i<check.length;i++) {
//		if(check.item(i).checked==true) {
//			chk_cnt++;
//		}
//	}
	
//	if (chk_cnt == 0) {
//		alert("선택 하신 자료가 없습니다.");
//	} else {

		bDelOK = confirm('자료를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}

//	}
}

/**
* 파일 첨부에 대한 선택에 따른 파일첨부 입력란 visibility 설정
*/
function js_exfileView(idx) {
	
	// fake input 추가 때문에 이렇게 처리 합니다.
	idx++;

	var obj = document.frm["file_flag[]"][idx];
	
	if (obj.selectedIndex == 2) {
		document.frm["file_name[]"][idx].style.visibility = "visible"; 
	} else { 
		document.frm["file_name[]"][idx].style.visibility = "hidden"; 
	}	
}

function js_fileView(obj,idx) {
	
	var frm = document.frm;
	
	if (idx == 01) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change").style.display = "inline";
		} else {
			document.getElementById("file_change").style.display = "none";
		}
	}
	if (idx == 02) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change2").style.display = "inline";
		} else {
			document.getElementById("file_change2").style.display = "none";
		}
	}
}
//-->
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

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="b_no" value="<?=$b_no?>" />
<input type="hidden" name="b_code" value="<?=$b_code?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="bb_po" value="<?=$rs_bb_po?>">
<input type="hidden" name="bb_re" value="<?=$rs_bb_re?>">

<input type="hidden" name="reply_state" value="">

				<div class="boardwrite">
					<table>
						<colgroup>
							<col style="width:12%">
							<col style="width:38%">
							<col style="width:12%">
							<col style="width:38%">
						</colgroup>
						<tbody>
							<? if ($b_board_type == "QNA") {?>
							<input type="hidden" name="main_tf" value="N">
							<? } else { ?>
							<tr>
								<th>공지</th>
								<td colspan="3">
									<span class="ickbox"><input type="checkbox" class="check" name="top_tf" value="Y" <? if ($rs_main_tf == "Y" ) echo "checked" ?>/><label>공지로 등록</label></span>
								</td>
							</tr>
							<tr>
								<th>NEW</th>
								<td colspan="3">
									<span class="ickbox"><input type="checkbox" class="check" name="main_tf" value="N" <? if ($rs_main_tf == "N" ) echo "checked" ?>/><label>표시안하기</label></span>
								</td>
							</tr>
							<? } ?>
							<tr>
								<th scope="row">제목</th>
								<td colspan="3">
								<?
									// 카테고리 사용 게시판인경우 
									if ($b_board_cate) { 
								?>
									<span class="optionbox">
										<?=makeBoardSelectBox("cate_01", "", "선택하세요.", $b_board_cate, "style='width:200px'", $rs_cate_01); ?>
									</span>
								<? 
									}
								?>

								<? $str_rs_title = str_replace("\"","&quot;", $rs_title) ?>
								<span class="inpbox"><input type="text" class="txt" name="title" value="<?=$str_rs_title?>"/></span>
								<? if ($b_secret_tf != "N") { ?>
								&nbsp;<input type="checkbox" class="radio" name="secret_tf" value="Y" <? if ($rs_secret_tf == "Y") echo "checked"?> > 비밀글
								<? } else {  ?>
								<input type="hidden" name="secret_tf" value="<?=$rs_secret_tf?>">
								<? } ?>
								</td>
							</tr>

							<? if ($b_board_type == "EVENT") {?>
							<tr>
								<th scope="row">이벤트기간</th>
								<td colspan="3">
									<span class="inpbox"><input type="text" class="txt date" style="width: 125px;" name="cate_03" value="<?=$rs_cate_03?>" maxlength="20" areadonly="10" /></span>  ~ 
									<span class="inpbox"><input type="text" class="txt date" style="width: 125px;" name="cate_04" value="<?=$rs_cate_04?>" maxlength="20" areadonly="1" /></span>
								</td>
							</tr>
							<tr>
								<th scope="row">당첨자발표일</th>
								<td colspan="3">
									<span class="inpbox"><input type="text" class="txt date" style="width: 125px;" name="cate_02" value="<?=$rs_cate_02?>" maxlength="20" areadonly="1" /></span>
								</td>
							</tr>
							<? }elseif ($b_board_type == "MOVIE") {?>
							<tr>
								<th scope="row">영상URL</th>
								<td colspan="3">
									<span class="inpbox"><input type="text" class="txt" name="homepage" value="<?=$rs_homepage?>" style="width:300px;" /></span> ex) Youtube 경로 
									<div class="sp5"></div>
									<font color ="red">유튜브에 링크를 올릴 경우 소스코드는 입력 하지 않으셔도 됩니다.</font> ex) http://youtu.be/u-tX968hWrY
									<!--Youtube 계정 : dsrcorpwebmaster@gmail.com : qlalfqjsghdsr-->
								</td>
							</tr>
							<tr>
								<th scope="row">소스코드</th>
								<td colspan="3">
									<textarea name="cate_03" style="width:100%;height:300px" /><?=$rs_cate_03?></textarea> 
									<br>prezi 의 경우 Embed 태그 소스 코드로 등록 하시면 됩니다.
									<br>소스코드 등록시 <font color ="red"> width="408" height="268" </font> 화면 사이즈 부분 수정해주세요.
								</td>
							</tr>
							<? }else{ ?>
							<tr style="display:none">
								<th scope="row">홈페이지</th>
								<td colspan="3">
									<span class="inpbox"><input type="text" class="txt" name="homepage" value="<?=$rs_homepage?>" style="width:500px;" /></span>
								</td>
							</tr>
							<? } ?>
							<tr>
								<th scope="row">작성자</th>
								<td>
									<span class="inpbox"><input type="text" class="txt" name="writer_nm" value="<?=$rs_writer_nm?>" style="width: 120px;" /></span>
								</td>
								<th scope="row">EMAIL</th>
								<td>
									<span class="inpbox"><input type="text" class="txt" name="email" value="<?=$rs_email?>" style="width: 220px;" /></span>
								</td>
							</tr>

							<? if ($b_board_type == "EVENT") {?>
							<tr>
								<th scope="row">썸네일</th>
								<td colspan="3">
								<?
									if (strlen($rs_file_nm) > 3) {
								?>
									<img src="/upload_data/board/<?=$rs_file_nm?>" width="310" >

									<select name="flag01" style="width:70px;" onchange="javascript:js_fileView(this,'01')">
										<option value="keep">유지</option>
										<option value="delete">삭제</option>
										<option value="update">수정</option>
									</select>
								
									<input type="hidden" name="old_file_nm" value="<?= $rs_file_nm?>">
									<input type="hidden" name="old_file_rnm" value="<?= $rs_file_rnm?>">

									<div id="file_change" style="display:none;">
											<input type="file" id="file_nm" class="w50per" name="file_nm" /><!--<span class="explain">400 * 162</span>-->
									</div>
								<?
									} else {	
								?>
									<input type="file" id="file_nm" class="w50per" name="file_nm" /><!--<span class="explain">400 * 162</span>-->
									<input type="hidden" name="old_file_nm" value="">
									<input type="hidden" name="old_file_rnm" value="">
									<input TYPE="hidden" name="flag01" value="insert">
								<?
									}	
								?>
								</td>
							</tr>
							<? } ?>

							<tr>
								<th scope="row">첨부파일</th>
								<td colspan="3">
									<input type="hidden" name="file_flag[]" value="insert"> 
									<input type="file" size="40%" name="file_name[]"> <?=$img_size?>
								</td>
							</tr>
					</table>
				</div>

				<div class="btnright">
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">저장</button>
				</div>

			</div>
		</div>
	</div>

</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
