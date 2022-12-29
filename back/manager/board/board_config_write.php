<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : board_config_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2011.06.01
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "BO001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/menu/menu.php";
	require "../../_classes/biz/board/board.php";

#====================================================================
# DML Process
#====================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$config_no					= $_POST['config_no']!=''?$_POST['config_no']:$_GET['config_no'];
	$bb_code						= $_POST['bb_code']!=''?$_POST['bb_code']:$_GET['bb_code'];
	$bb_no							= $_POST['bb_no']!=''?$_POST['bb_no']:$_GET['bb_no'];
	$board_nm						= $_POST['board_nm']!=''?$_POST['board_nm']:$_GET['board_nm'];
	$board_memo					= $_POST['board_memo']!=''?$_POST['board_memo']:$_GET['board_memo'];
	$board_code					= $_POST['board_code']!=''?$_POST['board_code']:$_GET['board_code'];
	$board_type					= $_POST['board_type']!=''?$_POST['board_type']:$_GET['board_type'];
	$board_cate					= $_POST['board_cate']!=''?$_POST['board_cate']:$_GET['board_cate'];
	$board_group				= $_POST['board_group']!=''?$_POST['board_group']:$_GET['board_group'];

	$list_group					= $_POST['list_group']!=''?$_POST['list_group']:$_GET['list_group'];
	$read_group					= $_POST['read_group']!=''?$_POST['read_group']:$_GET['read_group'];
	$write_group				= $_POST['write_group']!=''?$_POST['write_group']:$_GET['write_group'];
	$reply_group				= $_POST['reply_group']!=''?$_POST['reply_group']:$_GET['reply_group'];
	$comment_group			= $_POST['comment_group']!=''?$_POST['comment_group']:$_GET['comment_group'];
	$link_group					= $_POST['link_group']!=''?$_POST['link_group']:$_GET['link_group'];
	$upload_group				= $_POST['upload_group']!=''?$_POST['upload_group']:$_GET['upload_group'];
	$download_group			= $_POST['download_group']!=''?$_POST['download_group']:$_GET['download_group'];
	
	$search_tf					= $_POST['search_tf']!=''?$_POST['search_tf']:$_GET['search_tf'];
	$like_tf						= $_POST['like_tf']!=''?$_POST['like_tf']:$_GET['like_tf'];
	$unlike_tf					= $_POST['unlike_tf']!=''?$_POST['unlike_tf']:$_GET['unlike_tf'];
	$realname_tf				= $_POST['realname_tf']!=''?$_POST['realname_tf']:$_GET['realname_tf'];
	$ip_tf							= $_POST['ip_tf']!=''?$_POST['ip_tf']:$_GET['ip_tf'];
	$comment_tf					= $_POST['comment_tf']!=''?$_POST['comment_tf']:$_GET['comment_tf'];
	$reply_tf						= $_POST['reply_tf']!=''?$_POST['reply_tf']:$_GET['reply_tf'];
	$html_tf						= $_POST['html_tf']!=''?$_POST['html_tf']:$_GET['html_tf'];
	$file_tf						= $_POST['file_tf']!=''?$_POST['file_tf']:$_GET['file_tf'];
	$file_cnt						= $_POST['file_cnt']!=''?$_POST['file_cnt']:$_GET['file_cnt'];
	$max_title					= $_POST['max_title']!=''?$_POST['max_title']:$_GET['max_title'];
	$new_hour						= $_POST['new_hour']!=''?$_POST['new_hour']:$_GET['new_hour'];
	$hot_cnt						= $_POST['hot_cnt']!=''?$_POST['hot_cnt']:$_GET['hot_cnt'];
	$reply_order				= $_POST['reply_order']!=''?$_POST['reply_order']:$_GET['reply_order'];
	
	$board_badword			= $_POST['board_badword']!=''?$_POST['board_badword']:$_GET['board_badword'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$board_nm		= SetStringToDB($board_nm);
	$board_memo = SetStringToDB($board_memo);

	if ($mode == "I") {

		$use_tf = "Y";

		$arr_data = array("SITE_NO"=>$g_site_no,
											"BOARD_NM"=>$board_nm,
											"BOARD_CODE"=>$board_code,
											"BOARD_TYPE"=>$board_type,
											"BOARD_CATE"=>$board_cate,
											"BOARD_GROUP"=>$board_group,
											"LIST_GROUP"=>$list_group,
											"READ_GROUP"=>$read_group,
											"WRITE_GROUP"=>$write_group,
											"REPLY_GROUP"=>$reply_group,
											"COMMENT_GROUP"=>$comment_group,
											"LINK_GROUP"=>$link_group,
											"UPLOAD_GROUP"=>$upload_group,
											"DOWNLOAD_GROUP"=>$download_group,
											"SECRET_TF"=>$secret_tf,
											"SEARCH_TF"=>$search_tf,
											"LIKE_TF"=>$like_tf,
											"UNLIKE_TF"=>$unlike_tf,
											"REALNAME_TF"=>$realname_tf,
											"IP_TF"=>$ip_tf,
											"COMMENT_TF"=>$comment_tf,
											"REPLY_TF"=>$reply_tf,
											"HTML_TF"=>$html_tf,
											"FILE_TF"=>$file_tf,
											"FILE_CNT"=>$file_cnt,
											"MAX_TITLE"=>$max_title,
											"NEW_HOUR"=>$new_hour,
											"HOT_CNT"=>$hot_cnt,
											"REPLY_ORDER"=>$reply_order,
											"BOARD_MEMO"=>$board_memo,
											"BOARD_BADWORD"=>$board_badword,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$s_adm_no);

		$result =  insertBoardConfig($conn, $g_site_no, $arr_data, $menu_right);

	}

	if ($mode == "U") {
		
		$use_tf = "Y";

		$arr_data = array("SITE_NO"=>$g_site_no,
											"BOARD_NM"=>$board_nm,
											"BOARD_CODE"=>$board_code,
											"BOARD_TYPE"=>$board_type,
											"BOARD_CATE"=>$board_cate,
											"BOARD_GROUP"=>$board_group,
											"LIST_GROUP"=>$list_group,
											"READ_GROUP"=>$read_group,
											"WRITE_GROUP"=>$write_group,
											"REPLY_GROUP"=>$reply_group,
											"COMMENT_GROUP"=>$comment_group,
											"LINK_GROUP"=>$link_group,
											"UPLOAD_GROUP"=>$upload_group,
											"DOWNLOAD_GROUP"=>$download_group,
											"SECRET_TF"=>$secret_tf,
											"SEARCH_TF"=>$search_tf,
											"LIKE_TF"=>$like_tf,
											"UNLIKE_TF"=>$unlike_tf,
											"REALNAME_TF"=>$realname_tf,
											"IP_TF"=>$ip_tf,
											"COMMENT_TF"=>$comment_tf,
											"REPLY_TF"=>$reply_tf,
											"HTML_TF"=>$html_tf,
											"FILE_TF"=>$file_tf,
											"FILE_CNT"=>$file_cnt,
											"MAX_TITLE"=>$max_title,
											"NEW_HOUR"=>$new_hour,
											"HOT_CNT"=>$hot_cnt,
											"REPLY_ORDER"=>$reply_order,
											"BOARD_MEMO"=>$board_memo,
											"BOARD_BADWORD"=>$board_badword,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$s_adm_no);

		$result = updateBoardConfig($conn, $g_site_no, $arr_data, $config_no);
	}

	if ($mode == "D") {
		
	//	$row_cnt = count($chk);
		
	//	for ($k = 0; $k < $row_cnt; $k++) {
		
	//		$tmp_banner_no = $chk[$k];

			$result = deleteBoardConfig($conn, $g_site_no, $config_no, $s_adm_no );
		
//		}
	}

	if ($mode == "S") {

		$arr_rs = selectBoardConfig($conn, $g_site_no, $config_no);

		$rs_config_no				= trim($arr_rs[0]["CONFIG_NO"]); 
		$rs_site_no					= trim($arr_rs[0]["SITE_NO"]); 
		$rs_board_nm				= SetStringFromDB($arr_rs[0]["BOARD_NM"]); 
		$rs_board_code			= trim($arr_rs[0]["BOARD_CODE"]); 
		$rs_board_type			= trim($arr_rs[0]["BOARD_TYPE"]); 
		$rs_board_cate			= trim($arr_rs[0]["BOARD_CATE"]); 
		$rs_board_group			= trim($arr_rs[0]["BOARD_GROUP"]); 
		
		$rs_list_group			= trim($arr_rs[0]["LIST_GROUP"]); 
		$rs_read_group			= trim($arr_rs[0]["READ_GROUP"]); 
		$rs_write_group			= trim($arr_rs[0]["WRITE_GROUP"]); 
		$rs_reply_group			= trim($arr_rs[0]["REPLY_GROUP"]); 
		$rs_comment_group		= trim($arr_rs[0]["COMMENT_GROUP"]); 
		$rs_link_group			= trim($arr_rs[0]["LINK_GROUP"]); 
		$rs_upload_group		= trim($arr_rs[0]["UPLOAD_GROUP"]); 
		$rs_download_group	= trim($arr_rs[0]["DOWNLOAD_GROUP"]); 

		$rs_secret_tf				= trim($arr_rs[0]["SECRET_TF"]); 
		$rs_search_tf				= trim($arr_rs[0]["SEARCH_TF"]); 
		$rs_like_tf					= trim($arr_rs[0]["LIKE_TF"]); 
		$rs_unlike_tf				= trim($arr_rs[0]["UNLIKE_TF"]); 
		$rs_realname_tf			= trim($arr_rs[0]["REALNAME_TF"]); 
		$rs_ip_tf						= trim($arr_rs[0]["IP_TF"]); 
		$rs_comment_tf			= trim($arr_rs[0]["COMMENT_TF"]); 
		$rs_reply_tf				= trim($arr_rs[0]["REPLY_TF"]); 
		$rs_html_tf					= trim($arr_rs[0]["HTML_TF"]); 
		$rs_file_tf					= trim($arr_rs[0]["FILE_TF"]); 

		$rs_file_cnt				= trim($arr_rs[0]["FILE_CNT"]); 
		$rs_max_title				= trim($arr_rs[0]["MAX_TITLE"]); 
		$rs_new_hour				= trim($arr_rs[0]["NEW_HOUR"]); 
		$rs_hot_cnt					= trim($arr_rs[0]["HOT_CNT"]); 
		$rs_reply_order			= trim($arr_rs[0]["REPLY_ORDER"]); 

		$rs_board_memo			= SetStringFromDB($arr_rs[0]["BOARD_MEMO"]); 
		$rs_board_badword		= trim($arr_rs[0]["BOARD_BADWORD"]); 

		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 
		
		//echo $rs_comment_tf;
	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "board_config_list.php<?=$strParam?>";
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
<script language="javascript" type="text/javascript">


function js_list() {
	var frm = document.frm;
	frm.method = "post";
	frm.action = "board_config_list.php";
	frm.submit();
}


function js_save() {

	var frm = document.frm;

	var config_no = "<?=$config_no?>";
	
	frm.board_nm.value = frm.board_nm.value.trim();

	if (isNull(frm.board_nm.value)) {
		alert('게시판 이름을 입력해주세요.');
		frm.board_nm.focus();
		return ;		
	}

	if (frm.board_type.value == "") {
		alert('게시판 유형을 선택해주세요.');
		frm.board_type.focus();
		return ;		
	}

	if (frm.board_group.value == "") {
		alert('게시판 그룹을 선택해주세요.');
		frm.board_group.focus();
		return ;		
	}

	if (frm.list_group.value == "") {
		alert('리스트 권한을 선택해주세요.');
		frm.list_group.focus();
		return ;		
	}

	if (frm.read_group.value == "") {
		alert('읽기 권한을 선택해주세요.');
		frm.read_group.focus();
		return ;		
	}

	if (frm.write_group.value == "") {
		alert('쓰기 권한을 선택해주세요.');
		frm.write_group.focus();
		return ;		
	}

	if (frm.reply_group.value == "") {
		alert('답글 권한을 선택해주세요.');
		frm.reply_group.focus();
		return ;		
	}

	if (frm.comment_group.value == "") {
		alert('댓글 권한을 선택해주세요.');
		frm.comment_group.focus();
		return ;		
	}

	if (frm.link_group.value == "") {
		alert('링크 권한을 선택해주세요.');
		frm.link_group.focus();
		return ;		
	}

	if (frm.upload_group.value == "") {
		alert('업로드 권한을 선택해주세요.');
		frm.upload_group.focus();
		return ;		
	}

	if (frm.download_group.value == "") {
		alert('다운로드 권한을 선택해주세요.');
		frm.download_group.focus();
		return ;		
	}

	if (document.frm.rd_secret_tf == null) {
	} else {
		if (frm.rd_secret_tf[0].checked == true) {
			frm.secret_tf.value = "E";
		} else if (frm.rd_secret_tf[1].checked == true) {
			frm.secret_tf.value = "A";
		} else {
			frm.secret_tf.value = "N";
		}
	}

	if (document.frm.rd_search_tf == null) {
	} else {
		if (frm.rd_search_tf[0].checked == true) {
			frm.search_tf.value = "Y";
		} else {
			frm.search_tf.value = "N";
		}
	}
	
	/*
	if (document.frm.rd_like_tf == null) {
	} else {
		if (frm.rd_like_tf[0].checked == true) {
			frm.like_tf.value = "Y";
		} else {
			frm.like_tf.value = "N";
		}
	}

	if (document.frm.rd_unlike_tf == null) {
	} else {
		if (frm.rd_unlike_tf[0].checked == true) {
			frm.unlike_tf.value = "Y";
		} else {
			frm.unlike_tf.value = "N";
		}
	}
	*/

	if (document.frm.rd_realname_tf == null) {
	} else {
		if (frm.rd_realname_tf[0].checked == true) {
			frm.realname_tf.value = "Y";
		} else {
			frm.realname_tf.value = "N";
		}
	}

	if (document.frm.rd_ip_tf == null) {
	} else {
		if (frm.rd_ip_tf[0].checked == true) {
			frm.ip_tf.value = "Y";
		} else {
			frm.ip_tf.value = "N";
		}
	}

	if (document.frm.rd_comment_tf == null) {
	} else {
		if (frm.rd_comment_tf[0].checked == true) {
			frm.comment_tf.value = "Y";
		} else {
			frm.comment_tf.value = "N";
		}
	}

	if (document.frm.rd_reply_tf == null) {
	} else {
		if (frm.rd_reply_tf[0].checked == true) {
			frm.reply_tf.value = "Y";
		} else {
			frm.reply_tf.value = "N";
		}
	}

	if (document.frm.rd_html_tf == null) {
	} else {
		if (frm.rd_html_tf[0].checked == true) {
			frm.html_tf.value = "Y";
		} else {
			frm.html_tf.value = "N";
		}
	}

	if (document.frm.rd_file_tf == null) {
	} else {
		if (frm.rd_file_tf[0].checked == true) {
			frm.file_tf.value = "Y";
		} else {
			frm.file_tf.value = "N";
		}
	}

	if (isNull(config_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.config_no.value = frm.config_no.value;
	}

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

function js_fileView(obj,idx) {
	
	var frm = document.frm;
	
	if (idx == 01) {
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change").style.display = "inline";
		} else {
			document.getElementById("file_change").style.display = "none";
		}
	}
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

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="config_no" value="<?=$config_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
				
				<div class="boardwrite">

					<table>
						<colgroup>
							<col style="width:20%" />
							<col style="width:80%" />
						</colgroup>
						<tbody>
							<tr>
								<th><label for="boardName">게시판 이름</label></th>
								<td>
									<span class="inpbox"><input type="text" name="board_nm" value="<?= $rs_board_nm ?>" id="boardName" class="txt" itemname="게시판명" required></span>
								</td>
							</tr>
							<tr>
								<th><label for="boardKind">게시판 유형</label></th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"BOARD_TYPE","board_type","","선택","",$rs_board_type)?></span>
								</td>
							</tr>
							<tr>
								<th><label for="boardCate">게시판 카테고리</label></th>
								<td class="cate">
									<span class="inpbox" style="width:50%"><input type="text" name="board_cate" value="<?= $rs_board_cate ?>" id="boardCate" class="txt" itemname="게시판카테고리"></span>
									<b>* 구분을 사용 하실 경우 ; 로 구분해 입력해 주세요. ex) 공지;뉴스</b>
								</td>
							</tr>
							<tr>
								<th><label for="boardKind">게시판 그룹</label></th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"LANG","board_group","","선택","",$rs_board_group)?></span>
								</td>
							</tr>
							<tr>
								<th>리스트 권한</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"MEM_GROUP","list_group","","선택","",$rs_list_group)?></span>
								</td>
							</tr>
							<tr>
								<th>읽기 권한</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"MEM_GROUP","read_group","","선택","",$rs_read_group)?></span>
								</td>
							</tr>
							<tr>
								<th>쓰기 권한</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"MEM_GROUP","write_group","","선택","",$rs_write_group)?></span>
								</td>
							</tr>
							<tr>
								<th>답글 권한</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"MEM_GROUP","reply_group","","선택","",$rs_reply_group)?></span>
								</td>
							</tr>
							<tr>
								<th>댓글 권한</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"MEM_GROUP","comment_group","","선택","",$rs_comment_group)?></span>
								</td>
							</tr>
							<tr>
								<th>링크 권한</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"MEM_GROUP","link_group","","선택","",$rs_link_group)?></span>
								</td>
							</tr>
							<tr>
								<th>업로드 권한</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"MEM_GROUP","upload_group","","선택","",$rs_upload_group)?></span>
								</td>
							</tr>
							<tr>
								<th>다운로드 권한</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"MEM_GROUP","download_group","","선택","",$rs_download_group)?></span>
								</td>
							</tr>

							<tr>
								<th>비밀글 사용여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_secret_tf" value="E" <? if ($rs_secret_tf =="E") echo "checked"; ?>><label>선택</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_secret_tf" value="A" <? if ($rs_secret_tf =="A") echo "checked"; ?>><label>무조건</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_secret_tf" value="N" <? if (($rs_secret_tf =="N") || ($rs_secret_tf =="")) echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="secret_tf" value="<?= $rs_secret_tf ?>"> 
									</div>
								</td>
							</tr>

							<tr>
								<th>검색 사용여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_search_tf" value="Y" <? if ($rs_search_tf =="Y") echo "checked"; ?>><label>사용</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_search_tf" value="N" <? if (($rs_search_tf =="N") || ($rs_search_tf =="")) echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="search_tf" value="<?= $rs_search_tf ?>"> 
									</div>
								</td>
							</tr>
							<!--
							<tr>
								<th>추천 사용여부</th>
								<td>
									<input type="radio" class="radio" name="rd_like_tf" value="Y" <? if ($rs_like_tf =="Y") echo "checked"; ?>> 사용 <span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_like_tf" value="N" <? if (($rs_like_tf =="N") || ($rs_like_tf =="")) echo "checked"; ?>> 사용안함
									<input type="hidden" name="like_tf" value="<?= $rs_like_tf ?>"> 
								</td>
							</tr>
							<tr>
								<th>비추천 사용여부</th>
								<td>
									<input type="radio" class="radio" name="rd_unlike_tf" value="Y" <? if ($rs_unlike_tf =="Y") echo "checked"; ?>> 사용 <span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_unlike_tf" value="N" <? if (($rs_unlike_tf =="N") || ($rs_unlike_tf =="")) echo "checked"; ?>> 사용안함
									<input type="hidden" name="unlike_tf" value="<?= $rs_unlike_tf ?>"> 
								</td>
							</tr>
						-->
							<input type="hidden" name="like_tf" value="N">
							<input type="hidden" name="unlike_tf" value="N">
							<tr>
								<th>실명 사용여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_realname_tf" value="Y" <? if ($rs_realname_tf =="Y") echo "checked"; ?>><label>사용</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_realname_tf" value="N" <? if (($rs_realname_tf =="N") || ($rs_realname_tf =="")) echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="realname_tf" value="<?= $rs_realname_tf ?>"> 
									</div>
								</td>
							</tr>
							<tr>
								<th>아이피노출 사용여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_ip_tf" value="Y" <? if ($rs_ip_tf =="Y") echo "checked"; ?>><label>사용</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_ip_tf" value="N" <? if (($rs_ip_tf =="N") || ($rs_ip_tf =="")) echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="ip_tf" value="<?= $rs_ip_tf ?>">
									</div>
								</td>
							</tr>
							<tr>
								<th>답글 사용 여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_comment_tf" value="Y" <? if ($rs_comment_tf =="Y" || $rs_comment_tf =="") echo "checked"; ?>><label>사용</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_comment_tf" value="N" <? if ($rs_comment_tf =="N") echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="comment_tf" value="<?= $rs_comment_tf ?>"> 
									</div>
								</td>
							</tr>
							<tr>
								<th>답변 사용 여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_reply_tf" value="Y" <? if ($rs_reply_tf =="Y" || $rs_reply_tf =="") echo "checked"; ?>><label>사용</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_reply_tf" value="N" <? if ($rs_reply_tf =="N") echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="reply_tf" value="<?= $rs_reply_tf ?>"> 
									</div>
								</td>
							</tr>
							<tr>
								<th>HTML에디터 사용여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_html_tf" value="Y" <? if (($rs_html_tf =="Y") || ($rs_html_tf =="")) echo "checked"; ?>><label>사용</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_html_tf" value="N" <? if ($rs_html_tf =="N") echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="html_tf" value="<?= $rs_html_tf ?>"> 
									</div>
								</td>
							</tr>
							<tr>
								<th>첨부 파일 사용 여부</th>
								<td>
									<div class="iradiobox">
										<span class="iradio"><input type="radio" class="radio" name="rd_file_tf" value="Y" <? if (($rs_file_tf =="Y") || ($rs_file_tf =="")) echo "checked"; ?>><label>사용</label></span>
										<span class="iradio"><input type="radio" class="radio" name="rd_file_tf" value="N" <? if ($rs_file_tf =="N") echo "checked"; ?>><label>사용안함</label></span>
										<input type="hidden" name="file_tf" value="<?= $rs_file_tf ?>"> 
									</div>
								</td>
							</tr>
							<tr>
								<th>첨부 파일 사용 갯수</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"FILE_CNT","file_cnt","","0개","0",$rs_file_cnt)?></span>
								</td>
							</tr>

							<tr>
								<th>최대 제목 노출 수</th>
								<td>
									<?
										if ($rs_max_title == "") $rs_max_title = "80";
									?>
									<span class="inpbox" style="width:125px"><input type="text" name="max_title" value="<?= $rs_max_title ?>" class="txt" onkeyup="return isNumber(this)"></span> 자 <b>(목록에서의 제목 글자수. 잘리는 글은 … 로 표시)</b>
								</td>
							</tr>

							<tr>
								<th>새글 노출 시간</th>
								<td>
									<?
										if ($rs_new_hour == "") $rs_new_hour = "24";
									?>
									<span class="inpbox" style="width:125px"><input type="text" name="new_hour" value="<?= $rs_new_hour ?>" class="txt" onkeyup="return isNumber(this)"></span> 시간 <b>(글 입력후 new 이미지를 출력하는 시간)</b>
								</td>
							</tr>

							<tr>
								<th>핫 클릭 수 기준</th>
								<td>
									<?
										if ($rs_hot_cnt == "") $rs_hot_cnt = "1000";
									?>
									<span class="inpbox" style="width:125px"><input type="text" name="hot_cnt" value="<?= $rs_hot_cnt ?>" class="txt" onkeyup="return isNumber(this)"></span> 번 <b>(조회수가 설정값 이상이면 hot 이미지 출력)</b>
								</td>
							</tr>

							<tr>
								<th>답변노출순서</th>
								<td>
									<span class="optionbox" style="width:325px"><?= makeSelectBox($conn,"REPLY_ORDER","reply_order","","","",$rs_reply_order)?></span>
								</td>
							</tr>

							<tr> 
								<th>게시판 설명</th>
								<td>
									<span class="textareabox">
										<textarea id="boardExp" name="board_memo"><?=$rs_board_memo?></textarea>
									</span>
								</td>
							</tr>

							<tr> 
								<th>게시판 금지어</th>
								<td>
									<span class="textareabox">
										<textarea id="boardBan" name="board_badword"><?=$rs_board_badword?></textarea>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="btnright">
					<? if ($s_adm_no == $rs_reg_adm || $sPageRight_D == "Y") { ?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<?	if ($config_no <> "") {?>
					<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
					<?	} ?>
					<? } ?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>
		</form>

			</div>
		</div>
	</div>

<script type="text/javascript" src="../js/wrest.js"></script>
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
<script>

</script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>