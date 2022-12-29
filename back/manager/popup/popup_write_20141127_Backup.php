<?session_start();?>
<?
# =============================================================================
# File Name    : board_write.php
# Modlue       :
# Writer       : Park Chan Ho
# Create Date  : 2014.10.13
# Modify Date  :
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$writer_id = $s_adm_id;//작성자 아이디:로그인한 사용자 아이디

#====================================================================
# common_header Check Session
#====================================================================
	$menu_right = "CS005"; // 메뉴마다 셋팅 해 주어야 합니다
	require "../../_common/common_header.php";

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/popup/popup.php";


	$p_menu_name="팝업관리";

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$pop_no					= $_POST['pop_no']!=''?$_POST['pop_no']:$_GET['pop_no'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$use_tf_date							= $_POST['use_tf_date']!=''?$_POST['use_tf_date']:$_GET['use_tf_date'];

	$size_w						= $_POST['size_w']!=''?$_POST['size_w']:$_GET['size_w'];
	$size_h							= $_POST['size_h']!=''?$_POST['size_h']:$_GET['size_h'];
	$top								= $_POST['top']!=''?$_POST['top']:$_GET['top'];
	$left								= $_POST['left']!=''?$_POST['left']:$_GET['left'];
	$scrollbars					= $_POST['scrollbars']!=''?$_POST['scrollbars']:$_GET['scrollbars'];
	$s_date						= $_POST['s_date']!=''?$_POST['s_date']:$_GET['s_date'];
	$s_time							= $_POST['s_time']!=''?$_POST['s_time']:$_GET['s_time'];
	$e_date						= $_POST['e_date']!=''?$_POST['e_date']:$_GET['e_date'];
	$e_time						= $_POST['e_time']!=''?$_POST['e_time']:$_GET['e_time'];

	$title							= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$contents						= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$nPage							= SetStringToDB($nPage);
	$nPageSize					= SetStringToDB($nPageSize);

	$size_w						= SetStringToDB($size_w);
	$size_h							= SetStringToDB($size_h);
	$top								= SetStringToDB($top);
	$left								= SetStringToDB($left);

	$scrollbars					= SetStringToDB($scrollbars);
	$s_date						= SetStringToDB($s_date);
	$s_time							= SetStringToDB($s_time);
	$e_date						= SetStringToDB($e_date);
	$e_time						= SetStringToDB($e_time);
	$contents						= SetStringToDB($contents);
	$title							= SetStringToDB($title);
	$search_field				= SetStringToDB($search_field);
	$search_str					= SetStringToDB($search_str);

#====================================================================
# DML Process
#====================================================================
	if ($mode == "I") {

		$reg_date_ymd = date("Y-m-d",strtotime("0 day"));
		$reg_date_time = date("H:i:s",strtotime("0 day"));
		$reg_date = $reg_date_ymd." ".$reg_date_time;

		$new_bb_no =  insertPopup($conn, $use_tf_date, $size_w, $size_h, $top, $left, $scrollbars, $s_date, $s_time, $e_date, $e_time, $title, $contents, $use_tf, $reg_date);

		if ($new_bb_no) {
			$result = true;
		}
	}

	if ($mode == "U") {
		$reg_date_ymd = date("Y-m-d",strtotime("0 day"));
		$reg_date_time = date("H:i:s",strtotime("0 day"));
		$reg_date = $reg_date_ymd." ".$reg_date_time;


		$result = updatePopup($conn, $use_tf_date, $size_w, $size_h, $top, $left, $scrollbars, $s_date, $s_time, $e_date, $e_time, $title, $contents, $use_tf, $pop_no, $reg_date);
		$result = true;
	}


	if ($mode == "D") {
		$result = deletePopup($conn, $pop_no);
	}

	if ($mode == "S") {

		$arr_rs = selectPopup($conn, $pop_no);

		$rs_pop_no					= trim($arr_rs[0]["POP_NO"]);
		$rs_size_w					= trim($arr_rs[0]["SIZE_W"]);
		$rs_size_h					= SetStringFromDB($arr_rs[0]["SIZE_H"]);
		$rs_title						= SetStringFromDB($arr_rs[0]["TITLE"]);

		$rs_top						= SetStringFromDB($arr_rs[0]["TOP"]);
		$rs_left_						= SetStringFromDB($arr_rs[0]["LEFT_"]);
		$rs_scrollbars				= SetStringFromDB($arr_rs[0]["SCROLLBARS"]);
		$rs_contents					= SetStringFromDB($arr_rs[0]["CONTENTS"]);
		$rs_s_date					= SetStringFromDB($arr_rs[0]["S_DATE"]);
		$rs_s_time					= SetStringFromDB($arr_rs[0]["S_TIME"]);
		$rs_e_date					= SetStringFromDB($arr_rs[0]["E_DATE"]);
		$rs_e_time					= SetStringFromDB($arr_rs[0]["E_TIME"]);
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]);
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]);
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);
		$rs_date_use_tf			= trim($arr_rs[0]["CATE_01"]);	//기간사용여부

		$content  = $rs_contents;

	} else {
		$rs_writer_nm = $s_adm_nm;
		$rs_email			= $s_adm_email;
		$rs_writer_pw = $s_adm_no;
	}


	//echo $rs_reg_date;


	if ($rs_reg_date <> "") {
		$reg_date_ymd = left($rs_reg_date,10);
		$reg_date_time = right($rs_reg_date,8);
	} else {
		$reg_date_ymd = date("Y-m-d",strtotime("0 day"));
		$reg_date_time = date("H:i:s",strtotime("0 day"));
	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&bb_code=".$bb_code."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {
		$board_go_page="popup_list.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />

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
<script type="text/javascript" src="../../_common/SE2.1.1.8141/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script language="javascript" type="text/javascript">

function js_list() {
	document.location = "popup_list.php<?=$strParam?>";
}

function js_reply() {
	var frm = document.frm;

	frm.contents.value = "";
	frm.mode.value = "R";
	frm.target = "";
	frm.method = "get";
	frm.action = "popup_write.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;


	var pop_no = frm.pop_no.value;

	if(document.frm.title.value==""){
		alert('제목을 입력해주세요.');
		document.frm.title.focus();
		return;
	}

	if(document.frm.size_w.value==""){
		alert('팝업 가로사이즈를 입력해주세요.');
		document.frm.size_w.focus();
		return;
	}

	if(document.frm.size_h.value==""){
		alert('팝업 세로사이즈를 입력해주세요.');
		document.frm.size_h.focus();
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

	if (document.frm.date_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.date_use_tf[0].checked == true) {
			frm.use_tf_date.value = "Y";
		} else {
			frm.use_tf_date.value = "N";
		}
	}

	if (frm.use_tf_date.value == "Y") {
		//alert(document.frm.rd_use_tf);
	} else {
		frm.s_date.value = "";
		frm.s_time.value = "";
		frm.e_date.value = "";
		frm.e_time.value = "";
	}

	if (isNull(pop_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}

	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.

	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}

function js_view(rn, seq) {

	var frm = document.frm;

	frm.seq_no.value = seq;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "get";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}


function js_delete() {

	var frm = document.frm;

	var bDelOK = confirm('자료를 삭제 하시겠습니까?');

	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}
}

function FuncUseDate(obj){
	var frm = document.frm;

	if(obj.value == "Y"){
		frm.s_time.readOnly = false;
		frm.e_time.readOnly = false;

		frm.s_date.style.color = "#000000";
		frm.s_time.style.color = "#000000";
		frm.e_date.style.color = "#000000";
		frm.e_time.style.color = "#000000";
	}else{
		frm.s_time.readOnly = true;
		frm.e_time.readOnly = true;

		frm.s_date.style.color = "#b8b8b8";
		frm.s_time.style.color = "#b8b8b8";
		frm.e_date.style.color = "#b8b8b8";
		frm.e_time.style.color = "#b8b8b8";
	}
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
			<h2>메인관리</h2>
		</div>

		<section class="conBox">

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="pop_no" value="<?=$pop_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="reply_state" value="">

			<h3 class="conTitle"><?=$p_menu_name?>&nbsp;&nbsp;&nbsp;&nbsp;</h3>
			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
				<caption>내용 입력란</caption>
				<tr>
					<th scope="row">제목</th>
					<td colspan="3">
						<input type="text" name="title" value="<?=$rs_title?>"/>
					</td>
				</tr>

				<tr>
					<th scope="row">팝업사이즈</th>
					<td colspan="3">
						가로 : <input type="text" name="size_w" value="<?=$rs_size_w?>" style="width:30px;" maxlength="3"/>
						&nbsp;&nbsp;&nbsp;세로 : <input type="text" name="size_h" value="<?=$rs_size_h?>" style="width:30px;" maxlength="3"/>
					</td>
				</tr>
				<tr>
					<th scope="row">팝업위치</th>
					<td colspan="3">
						TOP : <input type="text" name="top" value="<?=$rs_top?>" style="width:30px;" maxlength="3"/>
						&nbsp;&nbsp;&nbsp;Left : <input type="text" name="left" value="<?=$rs_left_?>" style="width:30px;" maxlength="3"/>
					</td>
				</tr>
				<tr>
					<th scope="row">스크롤사용여부</th>
					<td colspan="3">
						<input type="radio" class="radio" name="scrollbars" value="Y" <?if($rs_scrollbars=="Y"){?> checked<?}?>/> 사용
						&nbsp;&nbsp;&nbsp;<input type="radio" class="radio" name="scrollbars" value="N" <?if(($rs_scrollbars=="N")||($rs_scrollbars=="")){?> checked<?}?>/> 사용안함
					</td>
				</tr>
				<tr>
					<th scope="row">게시여부</th>
					<td colspan="3">
						<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 게시함<span style="width:20px;"></span>
						<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 게시안함
						<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>">
					</td>
				</tr>

				<tr>
					<th scope="row">게시기간<br />사용여부</th>
					<td>
						<input type="radio" class="radio" name="date_use_tf" value="Y" <? if(($rs_date_use_tf =="Y") || ($rs_date_use_tf =="")) echo "checked"; ?> onClick="FuncUseDate(this);"> 사용함<span style="width:20px;"></span>
						<input type="radio" class="radio" name="date_use_tf" value="N" <? if($rs_date_use_tf =="N") echo "checked"; ?> onClick="FuncUseDate(this);"> 사용안함
						<input type="hidden" name="use_tf_date" value="<?= $rs_date_use_tf ?>">
					</td>

					<th scope="row">게시기간</th>
					<td>
						<?
							if($date_use_tf == "Y"){
								if($rs_s_time==""){
									$rs_s_time = date("H:i:s",strtotime("0 day"));
								}
								if($rs_e_time==""){
									$rs_e_time = date("H:i:s",strtotime("0 day"));
								}
								if ($rs_s_date== "") {
									$rs_s_date = date("Y-m-d",strtotime("0 day"));
								}
								if ($rs_e_date== "") {
									$rs_e_date = date("Y-m-d",strtotime("0 day"));
								}
							}else if($date_use_tf == "N"){
							}else{
								$rs_s_time = date("H:i:s",strtotime("0 day"));
								$rs_e_time = date("H:i:s",strtotime("0 day"));
								$rs_s_date = date("Y-m-d",strtotime("0 day"));
								$rs_e_date = date("Y-m-d",strtotime("0 day"));
							}
						?>
						<input type="text" name="s_date" value="<?=$rs_s_date?>" readOnly="true" <? if($rs_date_use_tf == "N"){echo "style='width:60px;color:#b8b8b8;'";}else{echo "style='width:60px;color:#000000;'";} ?>>

						<a href="javascript:show_calendar('document.frm.s_date', document.frm.s_date.value);" onFocus="blur();">
							<img src="/home2014/manager/images/bu/ic_calendar.gif" alt="" />
						</a>

						<input type="text" name="s_time" value="<?=$rs_s_time?>" maxlength="8" readonly="false" <? if($rs_date_use_tf == "N"){echo "style='width:60px;color:#b8b8b8;'";}else{echo "style='width:60px;color:#000000;'";} ?>>

						~

						<input type="text" name="e_date" value="<?=$rs_e_date?>" readonly="true" <? if($rs_date_use_tf == "N"){echo "style='width:60px;color:#b8b8b8;'";}else{echo "style='width:60px;color:#000000;'";} ?>>

						<a href="javascript:show_calendar('document.frm.e_date', document.frm.e_date.value);" onFocus="blur();">
							<img src="/home2014/manager/images/bu/ic_calendar.gif" alt="" />
						</a>
						<input type="text" name="e_time" value="<?=$rs_e_time?>" maxlength="8" readonly="false" <? if($rs_date_use_tf == "N"){echo "style='width:60px;color:#b8b8b8;'";}else{echo "style='width:60px;color:#000000;'";} ?>>&nbsp;&nbsp;&nbsp;&nbsp; 예) 2015-01-01 17:50:00
					</td>
				</tr>

				<tr>
					<th scope="row">내용</th>
					<td colspan="3" style="padding: 10px 10px 10px 15px">
					<?
						// ================================================================== 수정 부분
					?>
						 <span class="fl" style="padding-left:0px;width:740px;height:500px;"><textarea name="contents" id="contents"  style="padding-left:0px;width:730px;height:400px;"><?=$rs_contents?></textarea></span>
					<?
						// ================================================================== 수정 부분
					?>
					</td>
				</tr>
			</table>

					<div class="btnArea">
						<ul class="fRight">
					<? if ($s_adm_no == $rs_reg_adm || $sPageRight_I == "Y") { ?>
							<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<?	if ($pop_no <> "") {?>
					<?		if ($mode <> "R") {?>
					<!--
					<a href="javascript:js_reply();"><img src="../images/admin/btn_reply.gif" alt="답변" /></a>
					-->
									<?if($sPageRight_D=="Y"){?>
									<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
									<?}?>
					<?		}?>
 					<?	} ?>
					<? } ?>

					<?	if ($bb_no == "") {?>
					<!--
          <a href="javascript:js_save();"><img src="../images/admin/btn_confirm.gif" alt="확인" /></a>
					-->
					<? } ?>
							<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
						</ul>
					</div>
				</section>
<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</form>
<SCRIPT LANGUAGE="JavaScript">

var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "contents",
	sSkinURI: "../../_common/SE2.1.1.8141/SmartEditor2Skin.html",
	htParams : {
		bUseToolbar : true,
		fOnBeforeUnload : function(){
			// alert('야')
		},
		fOnAppLoad : function(){
		// 이 부분에서 FOCUS를 실행해주면 됩니다.
		this.oApp.exec("EVENT_EDITING_AREA_KEYDOWN", []);
		this.oApp.setIR("");
		//oEditors.getById["ir1"].exec("SET_IR", [""]);
		}
	},
	fCreator: "createSEditor2"
});

</SCRIPT>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>