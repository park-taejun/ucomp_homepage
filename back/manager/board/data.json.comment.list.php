<?session_start();?>
<?
# =============================================================================
# File Name    : ifrm_comment.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012-02-18
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

# =============================================================================
#	register_globals off 설정에 따른 코드 
#	(하나의 변수 명에 POST, GET을 모두 사용한 페이지에서만 사용 기본으로는 해당 코드 없이 POST, GET 명시)
	extract($_POST);
	extract($_GET);
	$s_adm_no = $_SESSION['s_adm_no'];
	$s_adm_no = $_COOKIE['s_adm_no'];
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================
//	require "../../_common/common_header.php"; 

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/board_ver1.0/board_comment2.php";

#====================================================================
# Request Parameter
#====================================================================
	$parent_bb_code			= $_POST['parent_bb_code']!=''?$_POST['parent_bb_code']:$_GET['parent_bb_code'];
	$parent_bb_no				= $_POST['parent_bb_no']!=''?$_POST['parent_bb_no']:$_GET['parent_bb_no'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	#List Parameter
	$parent_bb_code	= trim($parent_bb_code);
	$parent_bb_no		= trim($parent_bb_no);
	
	// parent 게시판 정보 입력
	$cate_01	= $parent_bb_code;
	$cate_02	= $parent_bb_no;

	$con_cate_01 = $parent_bb_code;
	$con_cate_02 = $parent_bb_no;

	$bb_code		= "R_".$parent_bb_code."_".$parent_bb_no;

	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_use_tf = "Y";
	$del_tf = "N";
	


#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 10000;
	}

	$nPageBlock	= 1000;

	$nListCnt =totalCntBoardComment($conn, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
		$json_ret = "";
		$json_ret .=  '{"total" : "'.$nListCnt.'","infos":{}}';
		echo $json_ret;
		die;
	}

	$arr_rs = listBoardComment($conn, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);


	$nCnt = 0;
	
	$json_ret = "";
	$json_ret .=  '{"total" : "'.$nListCnt.'",';
	$json_ret .=  '"infos":{';
	$li=0;
	
	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
			
			$rn							= trim($arr_rs[$j]["rn"]);
			$BB_NO					= trim($arr_rs[$j]["bb_no"]);

			$BB_DE					= trim($arr_rs[$j]["bb_de"]); 
			$BB_RE					= trim($arr_rs[$j]["bb_re"]); 
			$BB_PO					= trim($arr_rs[$j]["bb_po"]); 

			$BB_CODE				= trim($arr_rs[$j]["bb_code"]);
			$CATE_01				= trim($arr_rs[$j]["cate_01"]);
			$CATE_02				= trim($arr_rs[$j]["cate_02"]);
			$CATE_03				= trim($arr_rs[$j]["cate_03"]);
			$CATE_04				= trim($arr_rs[$j]["cate_04"]);
			$WRITER_NM				= SetStringFromDB($arr_rs[$j]["writer_nm"]);
			$WRITER_PW				= trim($arr_rs[$j]["writer_pw"]);
			$WRITER_ID				= trim($arr_rs[$j]["writer_id"]);
			$TITLE					= SetStringFromDB($arr_rs[$j]["title"]);
			$CONTENTS				= SetStringFromDB($arr_rs[$j]["contents"]);
			$EMAIL					= trim($arr_rs[$j]["email"]);
			$PHONE				= trim($arr_rs[$j]["phone"]);
			$HIT_CNT				= trim($arr_rs[$j]["hit_cnt"]);
			$USE_TF					= trim($arr_rs[$j]["use_tf"]);
			$REF_IP					= trim($arr_rs[$j]["ref_ip"]);
			
			$REG_DATE				= trim($arr_rs[$j]["reg_dt"]);
			
			$REG_DATE				= date("Y-m-d",strtotime($REG_DATE));
	
			#$CONTENTS				= nl2br($CONTENTS);
			#$CONTENTS=str_replace("\n","",$CONTENTS); 
			#$CONTENTS=str_replace("\r","",$CONTENTS); 
			
			$re_CONTENTS= $CONTENTS;
			$CONTENTS= nl2br($CONTENTS);
			$CONTENTS=str_replace("\n","",$CONTENTS); 
			$CONTENTS=str_replace("\r","",$CONTENTS); 
			$CONTENTS=str_replace("\t","",$CONTENTS); 


			$re_CONTENTS= nl2br($re_CONTENTS);
			$re_CONTENTS=str_replace("\n","",$re_CONTENTS); 

			$space = "";
			$sp = 10;

			for ($l = 1; $l < $BB_DE; $l++) {
				if ($l != 1) {
					$sp			= $sp + 15;
				} else {
					$sp			= $sp + 15;
				}
		
				if ($l == ($BB_DE - 1))
					$space .= "┗&nbsp;";		
			}


			$json_str.='';
			
			if($li>0)$json_ret .= ",";
			$json_ret .= '"'.$li.'":';
			$json_ret .= '["'.(int)$BB_NO.'",';		//0
			$json_ret .= '"'.(int)$BB_RE.'",';		//1
			$json_ret .= '"'.(int)$BB_DE.'",';		//2
			$json_ret .= '"'.(int)$BB_PO.'",';		//3
			$json_ret .= '"'.$BB_CODE.'",';			//4
			$json_ret .= '"'.$CATE_01.'",';			//5
			$json_ret .= '"'.$CATE_02.'",';			//6
			$json_ret .= '"'.$CATE_03.'",';			//7
			$json_ret .= '"'.$CATE_04.'",';			//8
			$json_ret .= '"'.$WRITER_NM.'",';		//9
			$json_ret .= '"'.$TITLE.'",';			//10
			$json_ret .= '"'.$CONTENTS.'",';		//11
			$json_ret .= '"'.$space.'",';			//12
			$json_ret .= '"'.$sp.'",';				//13
			$json_ret .= '"'.$REG_DATE.'",';		//14
			$json_ret .= '"'.$WRITER_PW.'",';		//15
			$json_ret .= '"'.$REF_IP.'",';		//16
			$json_ret .= '"'.$EMAIL.'",';		//17
			$json_ret .= '"'.$PHONE.'",';//18
			$json_ret .= '"'.$WRITER_ID.'"]';
			$li++;
		}

	}

	$json_ret .= "}}";

	header("Content-type: application/json; charset=utf-8");
	echo $json_ret;
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
