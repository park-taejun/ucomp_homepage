<?
	if ($is_top == "Y") {
		$rn							= trim($arr_rs_top[$j]["rn"]);
		$B_NO						= trim($arr_rs_top[$j]["B_NO"]);
		$B_RE						= trim($arr_rs_top[$j]["B_RE"]);
		$B_PO						= trim($arr_rs_top[$j]["B_PO"]);
		$B_CODE					= trim($arr_rs_top[$j]["B_CODE"]);
		$CATE_01				= trim($arr_rs_top[$j]["CATE_01"]);
		$CATE_02				= trim($arr_rs_top[$j]["CATE_02"]);
		$CATE_03				= SetStringFromDB($arr_rs_top[$j]["CATE_03"]);
		$CATE_04				= trim($arr_rs_top[$j]["CATE_04"]);
		$WRITER_ID			= trim($arr_rs_top[$j]["WRITER_ID"]);
		$WRITER_NM			= trim($arr_rs_top[$j]["WRITER_NM"]);
		$WRITER_NICK		= trim($arr_rs_top[$j]["WRITER_NICK"]);
		$TITLE					= SetStringFromDB($arr_rs_top[$j]["TITLE"]);
		$TITLE					= check_html($TITLE);
		$CONTENTS				= SetStringFromDB($arr_rs_top[$j]["CONTENTS"]);
		$CONTENTS_THUMB	= SetStringFromDB($arr_rs_top[$j]["CONTENTS"]);
		$cc_i_arr = array("<form",	"</form",	"<input",	"<textarea",	"</textarea",	"girin_comment" ,	"javascript:gbc_" );
		$cc_o_arr = array("<orm",	"</orm",	"<nput",	"<extarea",		"</extarea",	"glgln_comment" ,	"javascript:gbcc_");
		$CONTENTS				= replace_tag_parts($CONTENTS, $cc_i_arr, $cc_o_arr);

		$HOMEPAGE				= trim($arr_rs_top[$j]["HOMEPAGE"]);
		$HIT_CNT				= trim($arr_rs_top[$j]["HIT_CNT"]);
		$LINK01					= trim($arr_rs_top[$j]["LINK01"]);
		$LINK02					= trim($arr_rs_top[$j]["LINK02"]);
		$FILE_CNT				= trim($arr_rs_top[$j]["F_CNT"]);
		$REF_IP					= trim($arr_rs_top[$j]["REF_IP"]);
		$USE_TF					= trim($arr_rs_top[$j]["USE_TF"]);
		$REG_DATE				= trim($arr_rs_top[$j]["REG_DATE"]);
		$SECRET_TF			= trim($arr_rs_top[$j]["SECRET_TF"]);
		$REPLY_DATE			= trim($arr_rs_top[$j]["REPLY_DATE"]);
		$REPLY_STATE		= trim($arr_rs_top[$j]["REPLY_STATE"]);
		$COMMENT_CNT		= trim($arr_rs_top[$j]["COMMENT_CNT"]);
		$RS_THUMB_IMG		= trim($arr_rs_top[$j]["THUMB_IMG"]);
		$THUMB_IMG_CHK	= trim($arr_rs_top[$j]["THUMB_IMG_CHK"]);
		$bo_table				= trim($arr_rs_top[$j]["bo_table"]);

	} else {
		$rn							= trim($arr_rs[$j]["rn"]);
		$B_NO						= trim($arr_rs[$j]["B_NO"]);
		$B_RE						= trim($arr_rs[$j]["B_RE"]);
		$B_PO						= trim($arr_rs[$j]["B_PO"]);
		$B_CODE					= trim($arr_rs[$j]["B_CODE"]);
		$CATE_01				= trim($arr_rs[$j]["CATE_01"]);
		$CATE_02				= trim($arr_rs[$j]["CATE_02"]);
		$CATE_03				= SetStringFromDB($arr_rs[$j]["CATE_03"]);
		$CATE_04				= trim($arr_rs[$j]["CATE_04"]);
		$WRITER_ID			= trim($arr_rs[$j]["WRITER_ID"]);
		$WRITER_NM			= trim($arr_rs[$j]["WRITER_NM"]);
		$WRITER_NICK		= trim($arr_rs[$j]["WRITER_NICK"]);
		$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
		$TITLE					= check_html($TITLE);
		$CONTENTS				= SetStringFromDB($arr_rs[$j]["CONTENTS"]);
		$CONTENTS_THUMB	= SetStringFromDB($arr_rs[$j]["CONTENTS"]);

		$cc_i_arr = array("<form",	"</form",	"<input",	"<textarea",	"</textarea",	"girin_comment" ,	"javascript:gbc_" );
		$cc_o_arr = array("<orm",	"</orm",	"<nput",	"<extarea",		"</extarea",	"glgln_comment" ,	"javascript:gbcc_");
		$CONTENTS				= replace_tag_parts($CONTENTS, $cc_i_arr, $cc_o_arr);

		$HOMEPAGE				= trim($arr_rs[$j]["HOMEPAGE"]);
		$HIT_CNT				= trim($arr_rs[$j]["HIT_CNT"]);
		$LINK01					= trim($arr_rs[$j]["LINK01"]);
		$LINK02					= trim($arr_rs[$j]["LINK02"]);
		$FILE_CNT				= trim($arr_rs[$j]["F_CNT"]);
		$REF_IP					= trim($arr_rs[$j]["REF_IP"]);
		$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
		$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
		$SECRET_TF			= trim($arr_rs[$j]["SECRET_TF"]);
		$REPLY_DATE			= trim($arr_rs[$j]["REPLY_DATE"]);
		$REPLY_STATE		= trim($arr_rs[$j]["REPLY_STATE"]);
		$COMMENT_CNT		= trim($arr_rs[$j]["COMMENT_CNT"]);
		$RS_THUMB_IMG		= trim($arr_rs[$j]["THUMB_IMG"]);
		$THUMB_IMG_CHK	= trim($arr_rs[$j]["THUMB_IMG_CHK"]);
		$bo_table				= trim($arr_rs[$j]["bo_table"]);

	}

	//?????? ?????? ???????????? ????????? ????????? ?????? ?????????. ?????? ???????????? ?????? ??? ??? ??????..
	$str_link = "javascript:js_board_view('".$B_NO."');";

	if ($_SESSION['s_adm_no'] && $arr_page_nm[1] == "manager") {
		// ???????????? ?????? ?????? ??????  ??????
	} else {
		if ($r_right) {
			// ????????? ??????
			if ($_SESSION['s_m_id']) { // ????????? ??? ????????????
				if ($SECRET_TF == "Y" && $_SESSION['s_m_id'] != $WRITER_ID) {
					$str_link = "javascript:js_board_secret('".$B_NO."');";
				}
			} else { // ????????? ?????????
				if ($SECRET_TF == "Y") {
					$str_link = "javascript:js_board_secret('".$B_NO."');";
				}
			}
		} else {
			$str_link = "javascript:alert('?????? ???????????? ?????? ?????? ????????? ????????????. ??????????????? ?????? ????????????.');";
		}
	}

	//?????? ???
	$is_secret = "";
	if ($SECRET_TF == "Y")
		$is_secret = "<img src='../images/bbs/ic_lock.jpg' alt='?????????' /> ";

	//??????
	$is_new = "";
	if ($REG_DATE >= date("Y-m-d H:i:s", (strtotime("0 day") - ($b_new_hour * 3600))))
		$is_new = "<img src='../images/bu/ic_new.png' alt='??????' /> ";

	//?????? ????????? ???
	$is_hot = "";
	if ($HIT_CNT >= $b_hot_cnt)
		$is_hot = "<img src='../images/bu/ic_hot.png' alt='?????? ????????? ???' /> ";
	
	//?????? ??????
	$is_file = "";
	$arr_rs_files = null;
	
	//echo "ssss";
	//echo $FILE_CNT;

	if ($FILE_CNT > 0) {
		$is_file = "<img src='../images/icon_file.png' alt='????????????'> ";
		if ($no_file_info <> "Y") {
			$arr_rs_files = listBoardFile($conn, $B_CODE, $B_NO);
		}
	} 
	
	//?????? ??????
	$is_comment = "";
	$is_comment_moblie = "";
	
	if (($COMMENT_CNT > 0) && ($b_comment_tf =="Y")) {
		$is_comment = "<em>(".number_format($COMMENT_CNT).")</em>";
		$is_comment_moblie = "<span>(".number_format($COMMENT_CNT).")</span>";
	}
	

	//if ($arr_page_nm[1] == "m2014") {
	//$b_max_title = 148;
	//}
	//?????? 
	if (strlen($TITLE) > $b_max_title) {
		$str_title = u8_strcut($TITLE, $b_max_title,"..");
	} else {
		$str_title = $TITLE;
	}

	// ?????????.
	if ($b_realname_tf == "Y") {
		$str_writer_name = $WRITER_NM;
	} else {
		$str_writer_name = $WRITER_NICK;
	}
	
	// ?????? ?????? ??? 
	$str_contents = strip_tags($CONTENTS);
	$str_contents = str_replace("&nbsp;","",$str_contents);
	$str_main_contents = u8_strcut($str_contents,420,"..");
	$str_mobile_contents = u8_strcut($str_contents,220,"..");
	$str_contents = u8_strcut($str_contents,300,"..");
	
	$str_reg_date = date("Y.m.d",strtotime($REG_DATE));

	//<img src="../images/bu/ic_new.png" alt="" /> 
	
	// ????????? ???????????? ????????? 
	if ($RS_THUMB_IMG) {
		$thumbnail_save_path = $g_physical_path."/upload_data/board/simg/".$RS_THUMB_IMG;

		//echo $thumbnail_save_path;
		if (!file_exists($thumbnail_save_path)) {
			$THUMB_IMG = "";
		} else {
			$THUMB_IMG = $RS_THUMB_IMG;
		}
	} else {
		$THUMB_IMG = "";
	}

	$temp_thumb_img= "";

	$space = "";
	
	$DEPTH = strlen($B_PO);
	
	$str_depth_class = "";
	$str_depth_list_class = "";
	$str_depth_re = "";
	if ($DEPTH) {
		$str_depth_class = "class='depth0".$DEPTH."'";
		$str_depth_list_class = "depth0".$DEPTH;
		$str_depth_re = "<figure><img src='../images/bu/ic_re.gif' alt='??????' /></figure>";
	}

	if ($b_board_cate && $CATE_01 == "??????") $CATE_01 = ""; 
?>