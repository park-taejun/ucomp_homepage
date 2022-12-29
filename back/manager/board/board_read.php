<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : board_read.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2011.06.01
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
	$b_code						= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];

	$b_code = trim($b_code);

	//echo $bb_code;

	if ($b_code == "")
		$b_code = "B_1_1";

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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/member/member.php";
	require "../../_classes/biz/board/board.php";

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$config_no					= $_POST['config_no']!=''?$_POST['config_no']:$_GET['config_no'];
	$b_code						= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_no							= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];

	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];


#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/config_info.php";
#====================================================================
# Board Config End
#====================================================================

	$arr_recom_count = countRecom($conn, $b_code, $b_no);
	$arr_Nrecom_count = countNRecom($conn, $b_code, $b_no);
#====================================================================
# DML Process
#====================================================================

	if ($mode == "D") {
		require "../../_common/board/del.php";
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
		$rs_writer_pw = $s_adm_no;
	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&mode=S&b_code=".$b_code."&b_no=".$b_no."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;

	if ($mode == "AU") {

		$reply = SetStringToDB($reply);
		$result = updateQnaAnswer($conn, $reply, $s_adm_no, $reply_state, $bb_code, $bb_no);

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

	if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "board_list.php<?=$strParam?>";
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
<style>

table.rowstable03 { width: 95%; }
table.rowstable03 td.dot { color: #555555; text-align: center; vertical-align: middle; }
table.rowstable03 td.modeual_nm { text-align: left;  padding-top: 15px; padding-left: 10px; padding-right: 15px;}
table.rowstable03 td.modeual_cont { text-align: left;  padding-top: 5px; padding-bottom: 5px; padding-left: 15px; padding-right: 15px;  }
table.rowstable03 td.end { text-align: left;  padding-top: 5px; padding-left: 15px; padding-right: 15px;}

.recom { clear: both; margin-bottom: 30px; padding-left:30px;width:94%}
.recom li { background: url(../images/bbs/recomBg.gif) left top repeat-x;padding:0 20px; }
.recom dl { clear: both;  padding: 15px 0; }
.recom dt { width: 100%; font-size:12px;font-family:"Dotum"; }
.recom dt strong { margin-left:15px; }
.recom dd { clear: both; display: block; padding-top: 20px;font-size:12px;font-family:"Dotum";line-height:1.6em; }
.recom dl.depth01 { clear: both; padding: 15px 0 15px 15px; background:url(../images/bu/bu_re.gif) 0px 17px no-repeat; }
.recom dl.depth02 { clear: both; padding: 15px 0 15px 30px; background:url(../images/bu/bu_re.gif) 15px 17px no-repeat; }
.recom dl.depth03 { clear: both; padding: 15px 0 15px 45px; background:url(../images/bu/bu_re.gif) 30px 17px no-repeat; }
.recom dl.depth04 { clear: both; padding: 15px 0 15px 60px; background:url(../images/bu/bu_re.gif) 45px 17px no-repeat; }
.recom dl.depth05 { clear: both; padding: 15px 0 15px 75px; background:url(../images/bu/bu_re.gif) 60px 17px no-repeat; }
.recom dl.depth06 { clear: both; padding: 15px 0 15px 90px; background:url(../images/bu/bu_re.gif) 75px 17px no-repeat; }
.recom dl.depth07 { clear: both; padding: 15px 0 15px 105px; background:url(../images/bu/bu_re.gif) 90px 17px no-repeat; }
.recom dl.depth08 { clear: both; padding: 15px 0 15px 120px; background:url(../images/bu/bu_re.gif) 105px 17px no-repeat; }
.recom dl.depth09 { clear: both; padding: 15px 0 15px 135px; background:url(../images/bu/bu_re.gif) 120px 17px no-repeat; }
.recom dl.depth10 { clear: both; padding: 15px 0 15px 150px; background:url(../images/bu/bu_re.gif) 135px 17px no-repeat; }
.recom div { border:1px solid #eaeaea;padding:10px 20px;font-size:12px;font-family:"Dotum";margin-top:15px; }
.recom div span { display:block;text-align:left;margin:0 0 5px 5px; }
.recom div a { margin-left:10px; }
.recom div textarea { width:87%;height:74px; }
.recom dl.depth08 textarea, .recom dl.depth09 textarea, .recom dl.depth10 textarea { width:86%; }


</style>
<script type="text/javascript" src="../../_common/SE2.1.1.8141/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/board.js"></script>


<script language="javascript" type="text/javascript">

	$(document).ready(function() {
	// 댓글 달기화면이 활성화 되어 있다면
<?	if ($b_comment_tf) { ?>
	js_getList();
<?	} ?>
	});

	function js_getList() {
		
		var frm		= document.frm;
		var mode	= "L";
		var b			= frm.b.value;
		var bn		= frm.bn.value;

		$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
			{ mode:mode, b:b, bn:bn}, 
			function(data){
				//data = decodeURIComponent(data);
				$("#div_recomm_list").html(data); 
			}
		);
	}

	function js_comment_save() {

		var frm		= document.frm;
		var mode	= "I";
		var b			= frm.b.value;
		var bn		= frm.bn.value;
		var secret_tf = "";
		if ($("#secret_tf").is(":checked") == true) {
			secret_tf = "Y";
		}

		var contents		= $("#contents").val();

		if (bn == "") {
			return;
		}
		
		var writer_nm = "";
		var writer_pw = "";

		if (contents.trim() == "") {
			alert("내용를 입력해 주십시오.");
			$("#contents").focus();
			return;
		}

		//writer_nm = encodeURIComponent(writer_nm);
		//writer_pw = encodeURIComponent(writer_pw);
		//contents	= encodeURIComponent(contents);
		
		var request = $.ajax({
			url: "<?=$g_base_dir?>/_common/board/ajax.comment.php",
			type: "POST",
			data: {mode: mode, b:b, bn:bn, writer_nm:writer_nm, writer_pw:writer_pw, secret_tf:secret_tf, contents:contents},
			dataType: "html"
		});

		request.done(function(msg) {
			//msg = decodeURIComponent(msg);
			if (msg != "") {
				alert(msg);
			} else {
				$("#writer_nm").val("");
				$("#writer_pw").val("");
				document.frm_comment.secret_tf.checked = false;
				$("#contents").val("");
				js_getList();
			}

			return false;
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed:" + textStatus);
			return false;
		});

	}

	function js_comment_delete(cno) {
		var frm = document.frm;
		bDelOK = confirm('댓글을 삭제 하시겠습니까?');

		if (bDelOK == true) {
	
			var mode	= "D";
			var b			= frm.b.value;
			var bn		= frm.bn.value;
			var cno		= cno;

			$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
				{ mode:mode, b:b, bn:bn, cno:cno}, 
				function(msg){
					if (msg != "") {
						alert(msg);
					} else {
						js_getList();
					}
				}
			);
		}
	}

	var active_obj = "";
	var active_act = "";

	function js_comment_reply(cno) {

		var obj = "#reply_"+cno;
		var con_obj		= "#contents_"+cno;
		var secret_obj	= "#secret_tf_"+cno;
		var mode	= "R";
		var frm_omment = eval("document.frm_comment_"+cno);

		$(con_obj).val("");
		frm_omment.secret_tf.checked = false;
		frm_omment.mode.value = "reply";

		if ($(obj).css("display") == "none") {
			if (active_obj) {
				$(active_obj).hide();
			}
			$(obj).show();
			active_obj = obj;
			active_act = mode;
			$("#write_comment").hide();
		} else {
			if (active_act == mode) {
				$(obj).hide();
				$("#write_comment").show();
			} else {
				active_act = mode;
			}
		}		//write_comment
	}

	function js_comment_modify(cno) {
		var obj = "#reply_"+cno;
		var con_obj		= "#contents_"+cno;
		var secret_obj	= "#secret_tf_"+cno;
		var mode	= "S";

		$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
			{ mode:mode, cno:cno}, 
			function(msg){
				if (msg == "작성자만 수정 가능합니다.") {
					alert(msg);
				} else {
					$(obj).html(msg);
				}
			}
		);
		
		if ($(obj).css("display") == "none") {

			if (active_obj) {
				$(active_obj).hide();
			}
			$(obj).show();
			active_obj = obj;
			active_act = mode;
			$("#write_comment").hide();

		} else {
			if (active_act == mode) {
				$(obj).hide();
				$("#write_comment").show();
			} else {
				active_act = mode;
			}
		}
	}

	function js_comment_reply_save(cno) {
		
		var frm = document.frm;
		var frm_omment = eval("document.frm_comment_"+cno);
		
		if (frm_omment.mode.value == "reply") {
			var mode	= "IR";
		} else {
			var mode	= "U";
		}
		var b			= frm.b.value;
		var bn		= frm.bn.value;
		var secret_tf = "";
		
		if (frm_omment.secret_tf.checked == true) {
			secret_tf = "Y";
		}

		var contents		= frm_omment.contents.value;

		if (bn == "") {
			return;
		}
		
		var writer_nm = "";
		var writer_pw = "";

		if (contents.trim() == "") {
			alert("내용를 입력해 주십시오.");
			frm_omment.contents.focus();
			return;
		}

		//writer_nm = encodeURIComponent(writer_nm);
		//writer_pw = encodeURIComponent(writer_pw);
		//contents	= encodeURIComponent(contents);

		var request = $.ajax({
			url: "<?=$g_base_dir?>/_common/board/ajax.comment.php",
			type: "POST",
			data: {mode: mode, b:b, bn:bn, writer_nm:writer_nm, writer_pw:writer_pw, secret_tf:secret_tf, contents:contents, cno:cno},
			dataType: "html"
		});
		

		request.done(function(msg) {
			//msg = decodeURIComponent(msg);
			if (msg != "") {
				alert(msg);
			} else {
				frm_omment.secret_tf.checked = false;
				frm_omment.contents.value = "";
				js_getList();
				$("#write_comment").show();
			}
			return false;
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed:" + textStatus);
			return false;
		});

	}


function js_list() {
	document.location = "board_list.php<?=$strParam?>";
}

function js_reply() {
	var frm = document.frm;
	frm.mode.value = "R";
	frm.target = "";
	frm.method = "get";
	frm.action = "board_write.php";
	frm.submit();
}

function js_save() {

	var frm = document.frm;
	var b_no = "<?= $b_no ?>";
	
	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (isNull(b_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.b_no.value = frm.b_no.value;
	}

<? if ($mode == "R") {?>
		frm.mode.value = "IR";
<? }?>

	frm.method = "post";
	frm.target = "";
	//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_answer() {

	var frm = document.refrm;
	var b_no = "<?= $b_no ?>";
	frm.reply.value=frm.reply.value.trim();
	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.
	if(frm.reply.value!=""){
		frm.reply_state.value = "Y";
	}else{
		alert('답변내용이 없습니다.');
		return;
	}
	if (isNull(b_no)) {
		return;
	} else {
		frm.mode.value = "AU";
		frm.b_no.value = b_no;
	}

	frm.method = "post";
	frm.target = "";
	//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
	frm.action = "board_read.php";
	frm.submit();

}

function js_view() {
	var frm = document.frm;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "post";
	frm.action = "board_write.php";
	frm.submit();
		
}

function file_change(file) { 
	document.getElementById("file_name").value = file; 
}


function js_delete() {

	var frm = document.frm;

		bDelOK = confirm('자료를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}

//	}
}

function js_move() {
	var frm = document.frm;

	NewWindow('about:blank', 'pop_move_board', '390', '240', 'NO');
	frm.target = "pop_move_board";
	frm.action = "pop_move_board.php";
	frm.submit();
}

function js_copy() {
	var frm = document.frm;

	NewWindow('about:blank', 'pop_copy_board', '390', '240', 'NO');
	frm.target = "pop_copy_board";
	frm.action = "pop_copy_board.php";
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

				<div class="boardlist search">
					<table>
						<colgroup>
							<col style="width:12%" />
							<col style="width:38%" />
							<col style="width:12%" />
							<col style="width:38%" />
						</colgroup>
						<tbody>
							<? if ($rs_cate_01) {?>
							<tr>
								<th scope="row" style="width:12%">NEW </th>
								<td style="width:38%">
								<? if ($rs_main_tf == "N" ) { echo "사용안함"; } else {echo "사용"; } ?>
								</td>
								<th scope="row" style="width:12%">카테고리</th>
								<td style="width:38%">
								[<?=str_replace("^"," & ",$rs_cate_01)?>]
								</td>
							</tr>
							<? } else { ?>
							<tr>
								<th scope="row" style="width:12%">공지 여부</th>
								<td olspan="3" style="width:38%">
								<? if ($rs_top_tf == "Y" ) { echo "공지"; } else {echo "공지안함"; } ?>
								</td>
								<th scope="row" style="width:12%">게시판</th>
								<td style="width:38%">
									<?=$b_board_type?>
								</td>
							</tr>
							<? } ?>
							<tr>
								<th scope="row">제목</th>
								<td colspan="3" style="text-align:left;">
								<?
									if ($rs_secret_tf == "Y") 
										$str_lock = "<img src='../images/bu/ic_lock.jpg' alt='' />";
									else 
										$str_lock = "";
								?>

								<?=$rs_title?> <?=$str_lock?>
								</td>
							</tr>

							<? if ($b_board_type == "EVENT") {?>
							<tr>
								<th scope="row">기간</th>
								<td><?=$rs_cate_03?>  ~ <?=$rs_cate_04?></td>
								<th scope="row">당첨자발표</th>
								<td><?=$rs_cate_02?></td>
							</tr>
							<? } ?>
							<tr>
								<th scope="row">작성자</th>
								<td style="text-align:left;"><?=$rs_writer_nm?> <? if ($rs_writer_id) { echo "[".$rs_writer_id."]"; } else { echo "[비회원]"; }?>&nbsp;&nbsp;[<?=$rs_ref_ip?>]</td>
								<th scope="row">이메일</th>
								<td><?=$rs_email?></td>
							</tr>
							<? if ($b_board_type == "MOVIE") {?>
							<?
								$HOMEPAGE = SetStringFromDB($rs_homepage);
								if($HOMEPAGE){
									if(strpos($HOMEPAGE,"youtu.be") !== false){
										$temp_youtobe01=explode("/" ,$HOMEPAGE);
										$temp_youtobe01_count=count($temp_youtobe01);
										$play_url=$temp_youtobe01[$temp_youtobe01_count-1];
									}
								}

								$youtobe_img="http://i4.ytimg.com/vi/".$play_url."/1.jpg";
							?>
							<tr>
								<th scope="row">영상</th>
								<td colspan="3">
									<? if (($rs_cate_03) && ($play_url == "")) { ?>
									<?=SetStringFromDB($rs_cate_03)?>
									<? } else { ?>
									<iframe width="591" id="play_now" height="369" src="http://www.youtube.com/embed/<?=$play_url?>?wmode=opaque" frameborder="0" allowfullscreen></iframe>
									<? } ?>
								</td>
							</tr>
							<? } ?>

							<? if ($b_board_type == "QNA") {?>
							<tr style='display:none'>
								<th scope="row">연락처</th>
								<td><?=$rs_phone?></td>
								<th scope="row">답변 여부</th>
								<td><?=$str_reply_state?></td>
							</tr>
							<? } ?>

							<? if ($rs_thumb_img) { ?>
							<!-- 썸네일 사용 안함
							<tr>
								<th scope="row">썸네일<br>이미지</th>
								<td colspan="3" style="text-align:left">
									<img src="<?=$str_thumb_img?>" width="100px">
								</td>
							</tr>
							-->
							<? } ?>

							<? if ($rs_file_nm) { ?>
							<tr>
								<th scope="row">썸네일<br>이미지</th>
								<td colspan="3" style="text-align:left">
									<img src="/upload_data/board/<?=$rs_file_nm?>" width="100px">
								</td>
							</tr>
							<? } ?>

							<?
								if ($b_file_tf == "Y") {

									# ==========================================================================
									# Result List
									# ==========================================================================
							
									#Cnt = 0
									$f_Cnt = 0;
							?>
							<tr>
								<th scope="row">첨부파일</th>
								<td colspan="3" style="text-align:left;">
								<?
									if (sizeof($arr_rs_files) > 0) {
										for ($j = 0 ; $j < sizeof($arr_rs_files); $j++) {
							
											//FILE_NO, BB_CODE, BB_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT 
											$RS_FILE_NO			= trim($arr_rs_files[$j]["FILE_NO"]);
											$RS_BB_CODE			= trim($arr_rs_files[$j]["BB_CODE"]);
											$RS_BB_NO				= trim($arr_rs_files[$j]["BB_NO"]);
											$RS_FILE_NM			= trim($arr_rs_files[$j]["FILE_NM"]);
											$RS_FILE_RNM		= trim($arr_rs_files[$j]["FILE_RNM"]);
											$RS_FILE_PATH		= trim($arr_rs_files[$j]["FILE_PATH"]);
											$RS_FILE_SIZE		= trim($arr_rs_files[$j]["FILE_SIZE"]);
											$RS_FILE_EXT		= trim($arr_rs_files[$j]["FILE_EXT"]);
											$RS_HIT_CNT			= trim($arr_rs_files[$j]["HIT_CNT"]);

											if ($RS_FILE_NM <> "") {
							?>
								<a href="../../_common/new_download_file.php?menu=boardfile&file_no=<?= $RS_FILE_NO ?>"><?=$RS_FILE_RNM?></a>&nbsp;&nbsp;
							<?
											}
											$f_Cnt = $f_Cnt + 1;
										}
									} else {
							?>
							첨부파일이 없습니다.
							<?
									}
							?>
								</td>
							</tr>
							<?
								}
							?>
					
							<?if($rs_link01){?>
							<tr> 
								<th scope="row">링크01</th>
								<td colspan="3">
									<a href="<?=$rs_link01?>" target="_blank"><?=$rs_link01?></a>
								</td>
							</tr>
							<?}?>

							<?if($rs_link02){?>
							<tr> 
								<th scope="row">링크02</th>
								<td colspan="3">
									<a href="<?=$rs_link02?>" target="_blank"><?=$rs_link02?></a>
								</td>
							</tr>
							<?}?>

							<tr style="display:none"> 
								<th scope="row">홈페이지</th>
								<td colspan="3">
									<?if($rs_homepage){?>
									<a href="<?=$rs_homepage?>" target="_blank"><?=$rs_homepage?></a>
									<?}?>
								</td>
							</tr>

							<tr class="conTxt"> 
								<th scope="row">내용</th>
								<td colspan="3" id="contents_td" style="text-align:left;">

					<?
						//첨부 파일중 이미지 파일은 노출 합니다.
						/*
						if (sizeof($arr_rs_files) > 0) {
							for ($j = 0 ; $j < sizeof($arr_rs_files); $j++) {
								$RS_FILE_NO			= trim($arr_rs_files[$j]["FILE_NO"]);
								$RS_FILE_NM			= trim($arr_rs_files[$j]["FILE_NM"]);
								$RS_FILE_RNM		= trim($arr_rs_files[$j]["FILE_RNM"]);
								$RS_FILE_PATH		= trim($arr_rs_files[$j]["FILE_PATH"]);
								$RS_FILE_SIZE		= trim($arr_rs_files[$j]["FILE_SIZE"]);
								$RS_bo_table		= trim($arr_rs_files[$j]["bo_table"]);
								
								$srr_file_info = explode(".",$RS_FILE_NM);
								
								$f_ext = strtoupper($srr_file_info[sizeof($srr_file_info)-1]);

								if ($f_ext == "JPG" || $f_ext == "JPEG" || $f_ext == "GIF" || $f_ext == "PNG") {
									if ($RS_bo_table) {
										$real_file = $g_old_data_path.$RS_bo_table."/".$RS_FILE_NM;
										$img_url = $g_site_url."/home2008/data/file/".$RS_bo_table."/".$RS_FILE_NM;
										$str_src = "/home2008/data/file/".$RS_bo_table."/".$RS_FILE_NM;
									} else {
										$real_file = $g_physical_path."/upload_data/board/".$RS_FILE_NM;
										$img_url = $g_site_url.$g_base_dir."/upload_data/board/".$RS_FILE_NM;
										$str_src = $g_base_dir."/upload_data/board/".$RS_FILE_NM;
									}
									
									if (file_exists($real_file)) {
										$sizesource = @getimagesize($img_url);
										$str_width = "";
										if ($sizesource[0] > 900) $str_width = "width='850'";
										echo "<img src='".$str_src."' ".$str_width."><br />";
									}
								}
							}
						}
						*/
					?>


							<?	
						
								if ($b_html_tf == "Y") { 

										$rs_contents = str_replace("&quot;","\"", $rs_contents);
										$rs_contents = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' \\2 \\3", $rs_contents);
							?>
								<?=$rs_contents?>
							<? } else { ?>
										<?=stripslashes(nl2br($rs_contents))?>
							<? }  ?>
								</td>
							</tr>
							
							<? if (1 == 2) { ?>
							<tr style="display:none"> 
								<th scope="row">키워드</th>
								<td colspan="3">
									<?=$rs_keyword?>
								</td>
							</tr>
							
							<tr> 
								<th scope="row">노출여부</th>
								<td	colspan="3" style="text-align:left;">
								<? if ($rs_use_tf =="Y") echo "보이기"; ?>
								<? if (($rs_use_tf !="Y") || ($rs_use_tf =="")) echo "보이지않기"; ?>
								</td>
							</tr>

							<tr> 
								<th scope="row">댓글사용여부</th>
								<td	colspan="3" style="text-align:left;">
								<? if ($rs_comment_tf =="Y") echo "사용"; ?>
								<? if ($rs_comment_tf !="Y") echo "사용안함"; ?>
								</td>
							</tr>
							<? } ?>

							<tr> 
								<th scope="row">등록일 / 좋아요 수</th>
								<td	colspan="3" style="text-align:left;">
									<?=$rs_reg_date?> / <?=$rs_recomm?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>


				<div class="btnright">

					<? if ($sPageRight_I == "Y" && $b_no <> "") { ?>
					<!--
					<li><a href="javascript:js_move();"><img src="../images/btn/btn_move.gif" alt="게시물 이동" /></a></li>
					<li><a href="javascript:js_copy();"><img src="../images/btn/btn_copy.gif" alt="게시물 목사" /></a></li>
					-->
					<? } ?>

					<? if ($sPageRight_I == "Y" && $b_no <> "" && $b_reply_tf == "Y") { ?>
					<button type="button" class="btn-navy" onClick="js_reply();" style="width:100px">답변</button>
					<? } ?>

					<? if (($sPageRight_U == "Y" && $b_no <> "") || ($s_adm_no == $rs_reg_adm)) { ?>
					<button type="button" class="btn-navy" onClick="js_view();" style="width:100px">수정</button>
					<? } ?>

					<? if (($sPageRight_D == "Y" && $b_no <> "") || ($s_adm_no == $rs_reg_adm)) { ?>
					<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
					<? } ?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>
					
				<? if ($b_board_type == "QNA"){?>
				<br />
			<table summary="이곳에서 <?=$b_board_nm?>을 관리하실 수 있습니다" class="bbsRead">
			<form name="refrm" method="post" >
			<input type="hidden" name="mode" value="" />
			<input type="hidden" name="bb_no" value="<?=$bb_no?>" />
			<input type="hidden" name="bb_code" value="<?=$bb_code?>" />
			<input type="hidden" name="nPage" value="<?=$nPage?>" />
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
			<input type="hidden" name="reply_state" value="">
				<caption><?=$b_board_nm?> 답변</caption>

				<tbody>					
						<tr> 
							<th scope="row">내용</th>
							<td colspan="3" style="padding: 10px 10px 10px 15px">
							<?
								// ================================================================== 수정 부분
							?>
								 <span class="fl" style="padding-left:0px;width:740px;height:400px;"><textarea name="reply" id="reply"  style="padding-left:0px;width:730px;height:300px;"><?=$rs_reply?></textarea></span>
							<?
								// ================================================================== 수정 부분
							?>
							</td>
						</tr>
					<tr> 
						<th scope="row">답변일</th>
						<td>
							<?=$rs_reply_date?>
						</td>
						<th scope="row"></th>
						<td>
						</td>
					</tr>

				</tbody>
				</form>
			</table>
			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fLeft">

					<? if ($sPageRight_I == "Y" && $b_no <> "" && $b_re_tf == "Y") { ?>
					<li><a href="javascript:js_reply();"><img src="../images/btn/btn_reply.gif" alt="답변" /></a></li>
					<? } ?>

					<? if ($sPageRight_U == "Y" && $b_no <> "" && $b_board_type == "QNA") { ?>
					<li><a href="javascript:js_answer();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<? } ?>
				</ul>
			</div>
			<?}?>



<form id="replyFrm" name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="b_no" value="<?=$b_no?>" />
<input type="hidden" name="b_code" value="<?=$b_code?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="b_po" value="<?=$rs_b_po?>">
<input type="hidden" name="b_re" value="<?=$rs_b_re?>">

<input type="hidden" name="reply_state" value="">
<input type="hidden" name="reply_mailtoname" value="<?=$rs_writer_nm?>">
<input type="hidden" name="reply_mailto" value="<?=$rs_email?>">
<input type="hidden" name="reply_title" value="<?=$rs_title?>">

<input type="hidden" name="writer_nm" value="<?=$rs_writer_nm?>" />
<input type="hidden" name="writer_pw" value="<?=$rs_writer_pw?>" />
<input type="hidden" name="email" value="" />
<input type="hidden" name="homepage" value="" />

<input type="hidden" name="b" value="<?=$b_code?>" />
<input type="hidden" name="bn" value="<?=$b_no?>" />
</form>

				<? if ($b_comment_tf == "Y" && $rs_b_no <> "") { ?>

<div class="sp20"></div>


					<div class="replylist">
						<form name="frm_comment" method="get">
						<input type="hidden" name="secret_tf" id="secret_tf" value="N"/>
						<input type="hidden" name="encrypt_str" id="encrypt_str" value="<?=$temp_str?>">
						<fieldset>
							<legend>댓글 등록</legend>
							<div class="pic"><span>
							<?=getProfileImages($conn, $_SESSION['s_adm_id'])?>
							</span><strong><?=$_SESSION['s_adm_nm']?></strong>
							</div>
							
							<p><textarea cols="" rows="" placeholder="댓글을 입력해주세요." name="contents" id="contents"></textarea><button type="button" onClick="js_comment_save();">등록</button></p>
						</fieldset>
						</form>
						
						<ul id="div_recomm_list">
							<li>
								<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
								<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
								<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
								<span class="btn-moreaction"><button type="button">수정/삭제</button><em><a href="#">수정</a><a href="#">삭제</a></em></span>
							</li>
							<li>
								<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
								<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
								<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
							</li>
							<li>
								<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
								<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
								<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
								<span class="btn-moreaction"><button type="button">수정/삭제</button><em><a href="#">수정</a><a href="#">삭제</a></em></span>
							</li>
							<li>
								<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
								<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
								<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
							</li>
						</ul>
					</div>

				<? } ?>



</div><!--wrapper-->
<script type="text/javascript">
	window.onload=function() {

		resizeBoardImage('680');
		//drawFont();
	}

</script>

<? if ($b_board_type == "QNA"){?>
<SCRIPT LANGUAGE="JavaScript">
<!--
<? if ($b_html_tf == "Y") { ?>
var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "reply",
	sSkinURI: "../../_common/SE2.1.1.8141/SmartEditor2Skin.html",
	htParams : {bUseToolbar : true, 
	fOnBeforeUnload : function(){ 
		// alert('야') 
	}
	}, 
	fCreator: "createSEditor2"
});
<? } ?>
//-->
</SCRIPT>
<?}?>
	<!-- S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>
<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
