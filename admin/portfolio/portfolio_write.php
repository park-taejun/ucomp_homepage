<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : portfolio_write.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-11-17
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

	$menu_right = "CO002"; // 메뉴마다 셋팅 해 주어야 합니다
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

	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/portfolio/portfolio.php";

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$con_p_yyyy					= $_POST['con_p_yyyy']!=''?$_POST['con_p_yyyy']:$_GET['con_p_yyyy'];
	$con_p_mm						= $_POST['con_p_mm']!=''?$_POST['con_p_mm']:$_GET['con_p_mm'];
	$con_p_category			= $_POST['con_p_category']!=''?$_POST['con_p_category']:$_GET['con_p_category'];

	$p_no								= $_POST['p_no']!=''?$_POST['p_no']:$_GET['p_no'];
	$p_name01						= $_POST['p_name01']!=''?$_POST['p_name01']:$_GET['p_name01'];
	$p_name02						= $_POST['p_name02']!=''?$_POST['p_name02']:$_GET['p_name02'];
	$p_yyyy							= $_POST['p_yyyy']!=''?$_POST['p_yyyy']:$_GET['p_yyyy'];
	$p_mm								= $_POST['p_mm']!=''?$_POST['p_mm']:$_GET['p_mm'];
	$p_category					= $_POST['p_category']!=''?$_POST['p_category']:$_GET['p_category'];
	$p_client						= $_POST['p_client']!=''?$_POST['p_client']:$_GET['p_client'];
	$p_contents					= $_POST['p_contents']!=''?$_POST['p_contents']:$_GET['p_contents'];
	$keyword						= $_POST['keyword']!=''?$_POST['keyword']:$_GET['keyword'];
	$link01							= $_POST['link01']!=''?$_POST['link01']:$_GET['link01'];
	$link02							= $_POST['link02']!=''?$_POST['link02']:$_GET['link02'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$top_tf							= $_POST['top_tf']!=''?$_POST['top_tf']:$_GET['top_tf'];
	$main_tf						= $_POST['main_tf']!=''?$_POST['main_tf']:$_GET['main_tf'];
	$prize_files				= $_POST['prize_files']!=''?$_POST['prize_files']:$_GET['prize_files'];
	$txt_color					= $_POST['txt_color']!=''?$_POST['txt_color']:$_GET['txt_color'];
	$p_type							= $_POST['p_type']!=''?$_POST['p_type']:$_GET['p_type'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$old_p_img01				= $_POST['old_p_img01']!=''?$_POST['old_p_img01']:$_GET['old_p_img01'];
	$old_p_img02				= $_POST['old_p_img02']!=''?$_POST['old_p_img02']:$_GET['old_p_img02'];
	$old_p_img03				= $_POST['old_p_img03']!=''?$_POST['old_p_img03']:$_GET['old_p_img03'];
	$old_p_img04				= $_POST['old_p_img04']!=''?$_POST['old_p_img04']:$_GET['old_p_img04'];
	$old_p_img05				= $_POST['old_p_img05']!=''?$_POST['old_p_img05']:$_GET['old_p_img05'];
	$old_p_img06				= $_POST['old_p_img06']!=''?$_POST['old_p_img06']:$_GET['old_p_img06'];
	$old_p_img07				= $_POST['old_p_img07']!=''?$_POST['old_p_img07']:$_GET['old_p_img07'];
	$old_p_img08				= $_POST['old_p_img08']!=''?$_POST['old_p_img08']:$_GET['old_p_img08'];
	$old_p_img09				= $_POST['old_p_img09']!=''?$_POST['old_p_img09']:$_GET['old_p_img09'];
	$old_p_img10				= $_POST['old_p_img10']!=''?$_POST['old_p_img10']:$_GET['old_p_img10'];
	
	$nPage					= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);

	$p_name01				= SetStringToDB($p_name01);
	$p_name02				= SetStringToDB($p_name02);
	$p_client				= SetStringToDB($p_client);
	$p_contents			= SetStringToDB($p_contents);
	$keyword				= SetStringToDB($keyword);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);

	//$p_contents			= str_replace("'","''", $p_contents);

	echo " mode : " .$mode. "<br />";
	#====================================================================
	$savedir1 = $g_physical_path."upload_data/portfolio";
	#====================================================================

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($_SESSION['s_adm_no']) {
		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");
	} else {
		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "USER");
	}

	$max_allow_file_size = $allow_file_size * 1024 * 1024;

	$ref_ip = $_SERVER['REMOTE_ADDR'];

	if ($mode == "I") {
		
		$p_img01 = "";
		$p_img02 = "";
		$p_img03 = "";
		$p_img04 = "";
		$p_img05 = "";
		$p_img06 = "";
		$p_img07 = "";
		$p_img08 = "";
		$p_img09 = "";
		$p_img10 = "";

		if (($_FILES["p_img01"]["name"] != "") || ($old_p_img01 != "")) {
			$p_img01	= upload($_FILES["p_img01"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		if (($_FILES["p_img02"]["name"] != "") || ($old_p_img02 != "")) {
			$p_img02	= upload($_FILES["p_img02"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		if (($_FILES["p_img03"]["name"] != "") || ($old_p_img03 != "")) {
			$p_img03	= upload($_FILES["p_img03"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		if (($_FILES["p_img04"]["name"] != "") || ($old_p_img04 != "")) {
			$p_img04	= upload($_FILES["p_img04"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		if (($_FILES["p_img05"]["name"] != "") || ($old_p_img05 != "")) {
			$p_img05	= upload($_FILES["p_img05"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		if (($_FILES["p_img06"]["name"] != "") || ($old_p_img06 != "")) {
			$p_img06	= upload($_FILES["p_img06"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		if (($_FILES["p_img07"]["name"] != "") || ($old_p_img07 != "")) {
			$p_img07	= upload($_FILES["p_img07"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		if (($_FILES["p_img08"]["name"] != "") || ($old_p_img08 != "")) {
			$p_img08	= upload($_FILES["p_img08"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		if (($_FILES["p_img09"]["name"] != "") || ($old_p_img09 != "")) {
			$p_img09	= upload($_FILES["p_img09"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		if (($_FILES["p_img10"]["name"] != "") || ($old_p_img10 != "")) {
			$p_img10	= upload($_FILES["p_img10"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		}

		$arr_data = array("P_NAME01"=>$p_name01,
											"P_NAME02"=>$p_name02,
											"P_YYYY"=>$p_yyyy,
											"P_MM"=>$p_mm,
											"P_CATEGORY"=>$p_category,
											"P_TYPE"=>$p_type,
											"P_CLIENT"=>$p_client,
											"P_CONTENTS"=>$p_contents,
											"P_IMG01"=>$p_img01,
											"P_IMG02"=>$p_img02,
											"P_IMG03"=>$p_img03,
											"P_IMG04"=>$p_img04,
											"P_IMG05"=>$p_img05,
											"P_IMG06"=>$p_img06,
											"P_IMG07"=>$p_img07,
											"P_IMG08"=>$p_img08,
											"P_IMG09"=>$p_img09,
											"P_IMG10"=>$p_img10,
											"PRIZE_FILES"=>$prize_files,
											"LINK01"=>$link01,
											"LINK02"=>$link02,
											"TOP_TF"=>$top_tf,
											"USE_TF"=>$use_tf,
											"MAIN_TF"=>$main_tf,
											"TXT_COLOR"=>$txt_color,
											"REG_ADM"=>$_SESSION['s_adm_no']
										);
			
		$new_p_no = insertPortfolio($conn, $arr_data);

		$file_cnt = count($file_name);

		for($i=0; $i <= $file_cnt; $i++) {

			$file_rname				= $_FILES["file_name"]["name"][$i];
			$old_file_name		= $_POST["old_file_name"][$i];

			if (($file_rname != "") || ($old_file_name != "")) {
				
				if ($file_rname != "") {
					$file_name	= multiupload($_FILES["file_name"], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','txt','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','TXT'));
				} else {
					$file_name	= $old_file_name;
				}

				$arr_data = array("P_NO"=>$new_p_no,
													"FILE_NM"=>$file_name,
													"FILE_RNM"=>$file_name,
													"FILE_SIZE"=>0,
													"FILE_EXT"=>"",
													"REG_ADM"=>$_SESSION['s_adm_no'],
													"REG_DATE"=>$REG_INSERT_DATE
												);

				$result = insertPortfolioFile($conn, $arr_data);

			}
		}
		
		// 수상 내역 등록
		$prize_cnt = count($prize_file);

		// echo "$prize_cnt : ".$prize_cnt;

		for($i=0; $i <= $prize_cnt; $i++) {

			$prize_file_rname			= $_FILES["prize_file"]["name"][$i];
			$old_prize_file				= $_POST["old_prize_file"][$i];
			$prize_name						= $_POST["prize_name"][$i];
			$prize_sub_name				= $_POST["prize_sub_name"][$i];

			if (($prize_file_rname != "") || ($old_prize_file != "")) {
				
				if ($prize_file_rname != "") {
					$prize_file_name	= multiupload($_FILES["prize_file"], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','GIF', 'JPEG', 'JPG','PNG'));
				} else {
					$prize_file_name	= $old_prize_file;
				}

				$arr_data = array("P_NO"=>$new_p_no,
													"PRIZE_NM"=>$prize_name,
													"PRIZE_SUB_NM"=>$prize_sub_name,
													"FILE_NM"=>$prize_file_name,
													"FILE_RNM"=>$prize_file_name,
													"FILE_SIZE"=>0,
													"FILE_EXT"=>"",
													"REG_ADM"=>$_SESSION['s_adm_no'],
													"REG_DATE"=>$REG_INSERT_DATE
												);

				$result = insertPortfolioPrize($conn, $arr_data);

			}
		}
	}


	if ($mode == "U") {

		if ($_FILES["p_img01"]["name"] != "") {
			$p_img01	= upload($_FILES["p_img01"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img01  = $old_p_img01; 
		}

		if ($_FILES["p_img02"]["name"] != "") {
			$p_img02	= upload($_FILES["p_img02"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img02  = $old_p_img02; 
		}

		if ($_FILES["p_img03"]["name"] != "") {
			$p_img03	= upload($_FILES["p_img03"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img03  = $old_p_img03; 
		}

		if ($_FILES["p_img04"]["name"] != "") {
			$p_img04	= upload($_FILES["p_img04"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img04  = $old_p_img04; 
		}

		if ($_FILES["p_img05"]["name"] != "") {
			$p_img05	= upload($_FILES["p_img05"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img05  = $old_p_img05; 
		}

		if ($_FILES["p_img06"]["name"] != "") {
			$p_img06	= upload($_FILES["p_img06"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img06  = $old_p_img06; 
		}

		if ($_FILES["p_img07"]["name"] != "") {
			$p_img07	= upload($_FILES["p_img07"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img07  = $old_p_img07; 
		}

		if ($_FILES["p_img08"]["name"] != "") {
			$p_img08	= upload($_FILES["p_img08"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img08  = $old_p_img08; 
		}

		if ($_FILES["p_img09"]["name"] != "") {
			$p_img09	= upload($_FILES["p_img09"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img09  = $old_p_img09; 
		}

		if ($_FILES["p_img10"]["name"] != "") {
			$p_img10	= upload($_FILES["p_img10"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
		} else {
			$p_img10  = $old_p_img10; 
		}

		$arr_data = array("P_NAME01"=>$p_name01,
											"P_NAME02"=>$p_name02,
											"P_YYYY"=>$p_yyyy,
											"P_MM"=>$p_mm,
											"P_CATEGORY"=>$p_category,
											"P_TYPE"=>$p_type,
											"P_CLIENT"=>$p_client,
											"P_CONTENTS"=>$p_contents,
											"P_IMG01"=>$p_img01,
											"P_IMG02"=>$p_img02,
											"P_IMG03"=>$p_img03,
											"P_IMG04"=>$p_img04,
											"P_IMG05"=>$p_img05,
											"P_IMG06"=>$p_img06,
											"P_IMG07"=>$p_img07,
											"P_IMG08"=>$p_img08,
											"P_IMG09"=>$p_img09,
											"P_IMG10"=>$p_img10,
											"PRIZE_FILES"=>$prize_files,
											"LINK01"=>$link01,
											"LINK02"=>$link02,
											"TOP_TF"=>$top_tf,
											"USE_TF"=>$use_tf,
											"DEL_TF"=>$del_tf,
											"MAIN_TF"=>$main_tf,
											"TXT_COLOR"=>$txt_color,
											"UP_ADM"=>$_SESSION['s_adm_no']
										);
			
		$result = updatePortfolio($conn, $arr_data, $p_no);

		$file_cnt = count($file_name);
		
		$result = deletePortfolioFile($conn, $p_no);

		for($i=0; $i <= $file_cnt; $i++) {

			$file_rname				= $_FILES["file_name"]["name"][$i];
			$old_file_name		= $_POST["old_file_name"][$i];

			if (($file_rname != "") || ($old_file_name != "")) {
				
				if ($file_rname != "") {
					$file_name	= multiupload($_FILES["file_name"], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','txt','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','TXT'));
				} else {
					$file_name	= $old_file_name;
				}

				$arr_data = array("P_NO"=>$p_no,
													"FILE_NM"=>$file_name,
													"FILE_RNM"=>$file_name,
													"FILE_SIZE"=>0,
													"FILE_EXT"=>"",
													"REG_ADM"=>$_SESSION['s_adm_no'],
													"REG_DATE"=>$REG_INSERT_DATE
												);

				$result = insertPortfolioFile($conn, $arr_data);

			}
		}

		// 수상 내역 등록
		$prize_cnt = count($prize_file);

		$result = deletePortfolioPrize($conn, $p_no);

		for($i=0; $i <= $prize_cnt; $i++) {

			$prize_file_rname			= $_FILES["prize_file"]["name"][$i];
			$old_prize_file				= $_POST["old_prize_file"][$i];
			$prize_name						= $_POST["prize_name"][$i];
			$prize_sub_name				= $_POST["prize_sub_name"][$i];

			if (($prize_file_rname != "") || ($old_prize_file != "")) {
				
				if ($prize_file_rname != "") {
					$prize_file_name	= multiupload($_FILES["prize_file"], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','GIF', 'JPEG', 'JPG','PNG'));
				} else {
					$prize_file_name	= $old_prize_file;
				}

				$arr_data = array("P_NO"=>$p_no,
													"PRIZE_NM"=>$prize_name,
													"PRIZE_SUB_NM"=>$prize_sub_name,
													"FILE_NM"=>$prize_file_name,
													"FILE_RNM"=>$prize_file_name,
													"FILE_SIZE"=>0,
													"FILE_EXT"=>"",
													"REG_ADM"=>$_SESSION['s_adm_no'],
													"REG_DATE"=>$REG_INSERT_DATE
												);

				$result = insertPortfolioPrize($conn, $arr_data);

			}
		}


	}

	if ($mode == "S") {
		
		$arr_rs = selectPortfolio($conn, $p_no);

		$rs_p_no								= trim($arr_rs[0]["P_NO"]); 
		$rs_p_name01						= SetStringFromDB($arr_rs[0]["P_NAME01"]); 
		$rs_p_name02						= SetStringFromDB($arr_rs[0]["P_NAME02"]); 
		$rs_p_yyyy							= trim($arr_rs[0]["P_YYYY"]); 
		$rs_p_mm								= trim($arr_rs[0]["P_MM"]); 
		$rs_p_category					= trim($arr_rs[0]["P_CATEGORY"]); 
		$rs_p_type							= trim($arr_rs[0]["P_TYPE"]); 
		$rs_p_client						= SetStringFromDB($arr_rs[0]["P_CLIENT"]); 
		$rs_p_contents					= SetStringFromDB($arr_rs[0]["P_CONTENTS"]); 
		$rs_p_img01							= trim($arr_rs[0]["P_IMG01"]); 
		$rs_p_img02							= trim($arr_rs[0]["P_IMG02"]); 
		$rs_p_img03							= trim($arr_rs[0]["P_IMG03"]); 
		$rs_p_img04							= trim($arr_rs[0]["P_IMG04"]); 
		$rs_prize_files					= trim($arr_rs[0]["PRIZE_FILES"]); 
		$rs_hit_cnt							= trim($arr_rs[0]["HIT_CNT"]); 
		$rs_keyword							= trim($arr_rs[0]["KEYWORD"]); 
		$rs_link01							= trim($arr_rs[0]["LINK01"]); 
		$rs_link02							= trim($arr_rs[0]["LINK02"]); 
		$rs_top_tf							= trim($arr_rs[0]["TOP_TF"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_main_tf							= trim($arr_rs[0]["MAIN_TF"]); 
		$rs_txt_color						= trim($arr_rs[0]["TXT_COLOR"]); 
		
		$arr_rs_file	= listPortfolioFile($conn, $p_no);
		$arr_rs_prize = listPortfolioPrize($conn, $p_no);

	}

	if ($rs_txt_color == "") $rs_txt_color = "#000000";

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_p_yyyy=".$con_p_yyyy."&con_p_mm=".$con_p_mm."&con_p_category=".$con_p_category."&search_field=".$search_field."&search_str=".$search_str;

	if (($result) || ($new_p_no <> "")) {

?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "portfolio_list.php<?=$strParam?>";
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
<script type="text/javascript" src="../js/jquery.form.js"></script>
<link type="text/css" rel="stylesheet" href="/admin/css/jquery.minicolors.css" />
<script src="/admin/js/jquery.minicolors.min.js"></script>

<script language="javascript" type="text/javascript">
<!--

$(document).ready(function() {
	
	js_set_images();

	$('#frm').ajaxForm({

		beforeSubmit: function (data,form,option) {
			return true;
		},
			success: function(response,status){
			
			var tmp_images = $("#prize_files").val();
			
			if (tmp_images == "") {
				tmp_images = response;
			} else { 
				tmp_images = tmp_images+"^"+response; 
			}

			$("#prize_files").val(tmp_images);
			
			var arr_img_list = $("#prize_files").val().split("^");
			var tmp_html = "";

			for (var i = 0; i < arr_img_list.length ; i++) {
				if (arr_img_list[i] != "") {
					tmp_html = tmp_html + "<img src='/upload_data/prize/"+arr_img_list[i]+"' width='56px'><a href='javascript:js_del_prize_img(\""+arr_img_list[i]+"\")'><img src='/admin/images/btn_del.gif'></a>";
				}
			}

			$("#prize_area").html(tmp_html);

			frm.prize_photo.value = "";

		},
			 error: function(){
		}
	});

});

function js_set_images() {

	var tmp_images = $("#prize_files").val();

	var arr_img_list = $("#prize_files").val().split("^");
	var tmp_html = "";

	for (var i = 0; i < arr_img_list.length ; i++) {
		if (arr_img_list[i] != "") {
			tmp_html = tmp_html + "<img src='/upload_data/prize/"+arr_img_list[i]+"' width='56px'><a href='javascript:js_del_prize_img(\""+arr_img_list[i]+"\")'><img src='/admin/images/btn_del.gif'></a>";
		}
	}

	$("#prize_area").html(tmp_html);
}

function js_del_prize_img(del_file) {
	var tmp_images = $("#prize_files").val();

	tmp_images = tmp_images.replace(del_file,"");
	tmp_images = tmp_images.replace("^^","^");
	
	if (left(tmp_images,1) == "^") {
		tmp_images = tmp_images.substring(1, tmp_images.length);
	}

	if (right(tmp_images,1) == "^") {
		tmp_images = tmp_images.substring(0, (tmp_images.length - 1));
	}

	$("#prize_files").val(tmp_images);
	js_set_images();
}



function js_list() {
	document.location = "portfolio_list.php<?=$strParam?>";
}

function js_save() {

	var frm = document.frm;
	var p_no = "<?= $p_no ?>";
	
	if(document.frm.p_name01.value==""){
		alert('프로젝트명을 입력해주세요.');
		document.frm.p_name01.focus();
		return;
	}

	if(document.frm.p_name02.value==""){
		alert('국문 프로젝트명을 입력해주세요.');
		document.frm.p_name02.focus();
		return;
	}

	if (document.frm.rd_top_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_top_tf[0].checked == true) {
			frm.top_tf.value = "Y";
		} else {
			frm.top_tf.value = "N";
		}
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

	if (document.frm.rd_main_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_main_tf[0].checked == true) {
			frm.main_tf.value = "Y";
		} else {
			frm.main_tf.value = "N";
		}
	}

	if (isNull(p_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.p_no.value = frm.p_no.value;
	}

	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
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


function js_add_img() {

	var frm = document.frm;

	var real_file_path;
	real_file_path = frm.prize_photo.value;
	var arr_temp = real_file_path.split(".");
	var extension = arr_temp[arr_temp.length-1];
	extension = extension.toUpperCase();
	if (extension == "JPG" || extension == "JPEG" || extension == "GIF" || extension == "PNG") {
		$('#frm').attr("action", "/_common/prize_photo.php");
		$('#frm').submit();
	} else {
		alert("사진은 이미지 파일만 등록이 가능합니다.");
		return;
	}
}

function js_del_prize_img(del_file) {

	var tmp_images = $("#prize_files").val();
	
	tmp_images = tmp_images.replace(del_file,"");
	tmp_images = tmp_images.replace("^^","^");
	
	if (left(tmp_images,1) == "^") {
		tmp_images = tmp_images.substring(1, tmp_images.length);
	}

	if (right(tmp_images,1) == "^") {
		tmp_images = tmp_images.substring(0, (tmp_images.length - 1));
	}
	

	$("#prize_files").val(tmp_images);
	js_set_images();
}

$(document).on("click", ".btn_add_file", function() {
	
	var add_tr = "<tr>";
			add_tr = add_tr+"<th scope='row'>첨부파일 <img src='/admin/images/btn_plus.gif' alt='파일추가'  style='cursor:pointer' class='btn_add_file' /> <img src='../images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_file' /></th>";
			add_tr = add_tr+"<td colspan='3'>";
			add_tr = add_tr+"<input type='file' size='40%' name='file_name[]'>";
			add_tr = add_tr+"<input type='hidden' name='old_file_name[]' value=''>";
			add_tr = add_tr+"<input type='hidden' name='old_file_rname[]' value=''>";
			add_tr = add_tr+"</td>";
			add_tr = add_tr+"</tr>";

	$(this).parent().parent().after(add_tr);

});

$(document).on("click", ".btn_del_file", function() {
	
	if ($(".btn_add_file").length == 1) {

	var add_tr = "<tr>";
			add_tr = add_tr+"<th scope='row'>첨부파일 <img src='../images/btn_plus.gif' alt='파일추가'  style='cursor:pointer' class='btn_add_file' /></th>";
			add_tr = add_tr+"<td colspan='3'>";
			add_tr = add_tr+"<input type='file' size='40%' name='file_name[]'>";
			add_tr = add_tr+"<input type='hidden' name='old_file_name[]' value=''>";
			add_tr = add_tr+"<input type='hidden' name='old_file_rname[]' value=''>";
			add_tr = add_tr+"</td>";
			add_tr = add_tr+"</tr>";

	$(this).parent().parent().after(add_tr);
	}

	$(this).parent().parent().remove();
});


$(document).on("click", ".btn_add_prize", function() {
	
	var add_tr = "<tr>";
			add_tr = add_tr+"<th scope='row'>수상 내역 <img src='../images/btn_plus.gif' alt='파일추가'  style='cursor:pointer' class='btn_add_prize' /> <img src='../images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_prize' /></th>";
			add_tr = add_tr+"<td colspan='3'>";
			add_tr = add_tr+"수상명 : <input type='text' size='40%' name='prize_name[]'>";
			add_tr = add_tr+"&nbsp;분야명 : <input type='text' size='40%' name='prize_sub_name[]'>";
			add_tr = add_tr+"&nbsp;<input type='file' size='40%' name='prize_file[]'>";
			add_tr = add_tr+"<input type='hidden' name='old_prize_file[]' value=''>";
			add_tr = add_tr+"</td>";
			add_tr = add_tr+"</tr>";

	$(this).parent().parent().after(add_tr);

});

$(document).on("click", ".btn_del_prize", function() {
	
	if ($(".btn_add_prize").length == 1) {

	var add_tr = "<tr>";
			add_tr = add_tr+"<th scope='row'>수상 내역 <img src='../images/btn_plus.gif' alt='파일추가'  style='cursor:pointer' class='btn_add_prize' /></th>";
			add_tr = add_tr+"<td colspan='3'>";
			add_tr = add_tr+"수상명 : <input type='text' size='40%' name='prize_name[]'>";
			add_tr = add_tr+"&nbsp;분야명 : <input type='text' size='40%' name='prize_sub_name[]'>";
			add_tr = add_tr+"&nbsp;<input type='file' size='40%' name='prize_file[]'>";
			add_tr = add_tr+"<input type='hidden' name='old_prize_file[]' value=''>";
			add_tr = add_tr+"</td>";
			add_tr = add_tr+"</tr>";

	$(this).parent().parent().after(add_tr);
	}

	$(this).parent().parent().remove();
});


$(document).on("click", ".cl_ck_p_category", function() { 

	var chk_str = "";
	var chk_cnt = 0;

	$('.cl_ck_p_category').each(function () {
		if ($(this).prop("checked") == true) {
			chk_str = chk_str +"|"+$(this).val()+"|";
		}
	});
	$("#p_category").val(chk_str);
});

$(document).on("click", ".cl_ck_p_type", function() { 

	var chk_str = "";
	var chk_cnt = 0;

	$('.cl_ck_p_type').each(function () {
		if ($(this).prop("checked") == true) {
			chk_str = chk_str +"|"+$(this).val()+"|";
		}
	});
	$("#p_type").val(chk_str);
});

function js_usetf(usetf_val) {
		 
	var frm = document.frm;
	 
	if ( usetf_val == "" ){
		frm.use_tf.value = "Y";
		frm.del_tf.value = "N";
	}
	
	if ( usetf_val == "Y") {
		frm.use_tf.value = "Y";
		frm.del_tf.value = "N";
	} else {
		frm.use_tf.value = "N";			
		frm.del_tf.value = "Y";
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

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="p_no" value="<?=$p_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
<input type="hidden" name="con_p_yyyy" value="<?=$con_p_yyyy?>" />
<input type="hidden" name="con_p_mm" value="<?=$con_p_mm?>" />
<input type="hidden" name="con_p_category" value="<?=$con_p_category?>" />

				<div class="boardwrite">
					<table>
						<colgroup>
							<col style="width:12%">
							<col style="width:38%">
							<col style="width:12%">
							<col style="width:38%">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">프로젝트명</th>
								<td colspan="3">
									<div style="float:left;display:inline;width:30%;">
										<textarea style="width:100%; height:85px" name="p_name01" id="p_name01" ><?=$rs_p_name01?></textarea>
									</div>
									<div style="float:left;display:inline;width:70%;padding-left:10px">
										<b>줄 넘김는 'enter'를 사용 합니다.<br>
											예) SHINHAN<br>OPEN API<br>PLATFORM
										</b>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row">국문 프로젝트명</th>
								<td colspan="3">
									<span class="inpbox" style="width:70%;"><input type="text" class="txt" name="p_name02" id="p_name02" value="<?=$rs_p_name02?>" /></span>
								</td>
							</tr>
							<tr>
								<th scope="row">표시색상</th>
								<td colspan="3">
									<span class="colorpicker"><input type="text" name="txt_color" id="txt_color" class="txt_color" data-control="hue" value="<?=$rs_txt_color?>" /></span>
								</td>
							</tr>
							<tr>
								<th scope="row">프로젝트 수행년</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"YYYY","p_yyyy","","","",$rs_p_yyyy)?></span>
								</td>
								<th scope="row">수행월</th>
								<td>
									<span class="optionbox" style="width:125px"><?= makeSelectBox($conn,"MONTH","p_mm","","","",$rs_p_mm)?></span>
								</td>
							</tr>
							<tr>
								<th scope="row">구분</th>
								<td colspan="3">
									<div class="iradiobox">
										<?=makeCheckBox($conn, "PCATEGORY", "ck_p_category", $rs_p_category)?>
									<input type="hidden" name="p_category" id="p_category" value="<?=$rs_p_category?>" />
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row">운영,구축 구분</th>
								<td colspan="3">
									<div class="iradiobox">
										<?=makeCheckBox($conn, "PROJECT_TYPE", "ck_p_type", $rs_p_type)?>
									<input type="hidden" name="p_type" id="p_type" value="<?=$rs_p_type?>" />
									</div>
								</td>
							</tr>

							<?
								if (sizeof($arr_rs_prize) > 0) {
									for ($j=0 ; $j < sizeof($arr_rs_prize) ; $j++) {
										$PRIZE_NM				= trim($arr_rs_prize[$j]["PRIZE_NM"]);
										$PRIZE_SUB_NM		= trim($arr_rs_prize[$j]["PRIZE_SUB_NM"]);
										$RS_FILE_NM			= trim($arr_rs_prize[$j]["FILE_NM"]);
										$RS_FILE_RNM		= trim($arr_rs_prize[$j]["FILE_RNM"]);
							?>

							<tr>
								<th scope="row">수상 내역 
								<img src="../images/btn_plus.gif" alt="파일추가"  style="cursor:pointer" class="btn_add_prize" />
								<img src='../images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_prize' />
								</th>
								<td colspan="3">
									<img src="/upload_data/portfolio/<?=$RS_FILE_NM?>" >
									수상명 : <input type="text" size="40%" name="prize_name[]" value="<?=$PRIZE_NM?>">
									분야명 : <input type="text" size="40%" name="prize_sub_name[]" value="<?=$PRIZE_SUB_NM?>">
									<input type="file" size="40%" name="prize_file[]">
									<input type="hidden" name="old_prize_file[]" value="<?=$RS_FILE_NM?>">
								</td>
							</tr>

							<?
									}
								} else { 
							?>
							<tr>
								<th scope="row">수상 내역 <img src="../images/btn_plus.gif" alt="파일추가"  style="cursor:pointer" class="btn_add_prize" /></th>
								<td colspan="3">
									수상명 : <input type="text" size="40%" name="prize_name[]" value="">
									분야명 : <input type="text" size="40%" name="prize_sub_name[]" value="">
									<input type="file" size="40%" name="prize_file[]">
									<input type="hidden" name="old_prize_file[]" value="">
								</td>
							</tr>
							<?
								}
							?>

							<tr>
								<th scope="row">수상 이미지</th>
								<td colspan="3">

									<!--<input type=button value='파일찾기' name='find_list_image' id="find_list_image" onclick="NewWindow('/_common/add_image_up.php?type=space', 'add_image', '680', '550', 'Y')" class="_write_btn">-->
									
									<input type="file" name="prize_photo" size="40%" /> <button type="button" class="btn-navy" style="width:130px;height:28px" onClick="js_add_img()">이미지등록</button>
									<!--<span class="tbl_txt"><span class="txt_c02">※ 365 x 365</span></span>-->
									
									<div class="sp5"></div>
									<div id="prize_area" >
										<!--
										<img src="/upload_data/banner/20191115124718_13.jpg" width="170"><a href=""><img src="/manager/images/btn_del.gif"></a>
										<img src="/upload_data/banner/20191115124718_13.jpg" width="170"><a href=""><img src="/manager/images/btn_del.gif"></a>
										<img src="/upload_data/banner/20191115124718_13.jpg" width="170"><a href=""><img src="/manager/images/btn_del.gif"></a>
										<img src="/upload_data/banner/20191115124718_13.jpg" width="170"><a href=""><img src="/manager/images/btn_del.gif"></a>
										<img src="/upload_data/banner/20191115124718_13.jpg" width="170"><a href=""><img src="/manager/images/btn_del.gif"></a>
										-->
									</div>
									<!--20191118075515_6.jpg^20191118075528_20.jpg^20191118075536_11.jpg^20191118075551_19.jpg-->
									<input type="hidden" name="prize_files" id="prize_files" value="<?=$rs_prize_files?>">
								</td>
							</tr>

							<tr>
								<th scope="row">고객명</th>
								<td colspan="3">
									<span class="inpbox" style="width:70%;"><input type="text" class="txt" name="p_client" id="p_client" value="<?=$rs_p_client?>" /></span>
								</td>
							</tr>

							<tr>
								<th scope="row">링크</th>
								<td colspan="3">
									<span class="inpbox" style="width:70%;"><input type="text" class="txt" name="link01" id="link01" value="<?=$rs_link01?>" /></span>
								</td>
							</tr>

							<tr>
								<th scope="row">OVERVIEW</th>
								<td colspan="3">
									<div style="float:left;display:inline;width:70%;">
										<textarea style="width:100%; height:205px" name="p_contents" id="p_contents" ><?=$rs_p_contents?></textarea>
									</div>
								</td>
							</tr>
							<!--
							<tr>
								<th scope="row">썸네일 (PC 메인)<br>(1600*940)</th>
								<td colspan="3">
								<?
									if (strlen($rs_p_img01) > 3) {
								?>
									<img src="/upload_data/portfolio/<?=$rs_p_img01?>" width="310" >
									<input type="file" size="40%" name="p_img01">
									<input type="hidden" name="old_p_img01" value="<?=$rs_p_img01?>">
								<?
									} else {	
								?>
									<input type="file" size="40%" name="p_img01">
									<input type="hidden" name="old_p_img01" value="">
								<?
									}
								?>
								</td>
							</tr>

							<tr>
								<th scope="row">썸네일 (Mobile 메인)<br>(654*940)</th>
								<td colspan="3">
								<?
									if (strlen($rs_p_img02) > 3) {
								?>
									<img src="/upload_data/portfolio/<?=$rs_p_img02?>" width="310" >
									<input type="file" size="40%" name="p_img02">
									<input type="hidden" name="old_p_img02" value="<?=$rs_p_img02?>">
								<?
									} else {	
								?>
									<input type="file" size="40%" name="p_img02">
									<input type="hidden" name="old_p_img02" value="">
								<?
									}
								?>
								</td>
							</tr>
							-->
							<tr>
								<th scope="row">썸네일 (리스트)<br>(370*350)</th>
								<td colspan="3">
								<?
									if (strlen($rs_p_img03) > 3) {
								?>
									<img src="/upload_data/portfolio/<?=$rs_p_img03?>" width="310" >
									<input type="file" size="40%" name="p_img03">
									<input type="hidden" name="old_p_img03" value="<?=$rs_p_img03?>">
								<?
									} else {	
								?>
									<input type="file" size="40%" name="p_img03">
									<input type="hidden" name="old_p_img03" value="">
								<?
									}
								?>
								</td>
							</tr>

							<tr>
								<th scope="row">썸네일 (본문상단)<br>(1388*724)</th>								
								<td colspan="3">
								<?
									if (strlen($rs_p_img04) > 3) {
								?>
									<img src="/upload_data/portfolio/<?=$rs_p_img04?>" width="310" >
									<input type="file" size="40%" name="p_img04">
									<input type="hidden" name="old_p_img04" value="<?=$rs_p_img04?>">
								<?
									} else {	
								?>
									<input type="file" size="40%" name="p_img04">
									<input type="hidden" name="old_p_img04" value="">
								<?
									}
								?>
								</td>
							</tr>

							<?
								if (sizeof($arr_rs_file) > 0) {
									for ($j=0 ; $j < sizeof($arr_rs_file) ; $j++) {
										$RS_FILE_NO			= trim($arr_rs_file[$j]["FILE_NO"]);
										$RS_FILE_NM			= trim($arr_rs_file[$j]["FILE_NM"]);
										$RS_FILE_RNM		= trim($arr_rs_file[$j]["FILE_RNM"]);
							?>
							<tr>
								<th scope="row">
									첨부파일
									<img src="../images/btn_plus.gif" alt="파일추가"  style="cursor:pointer" class="btn_add_file" />
									<img src='../images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_file' />
								</th>
								<td colspan="3">
									<img src="/upload_data/portfolio/<?=$RS_FILE_NM?>" width="310" >
									<!--<a href="../../_common/new_download_file.php?menu=portfoliofile&file_no=<?= $RS_FILE_NO ?>"><?=$RS_FILE_RNM?></a>-->
									<input type="file" size="40%" name="file_name[]">
									<input type="hidden" name="old_file_name[]" value="<?=$RS_FILE_NM?>">
									<input type="hidden" name="old_file_rname[]" value="<?=$RS_FILE_RNM?>">
								</td>
							</tr>
							<?
									}
								} else { 
							?>
							<tr>
								<th scope="row">첨부파일 <img src="../images/btn_plus.gif" alt="파일추가"  style="cursor:pointer" class="btn_add_file" /></th>
								<td colspan="3">
									<input type="file" size="40%" name="file_name[]">
									<input type="hidden" name="old_file_name[]" value="">
									<input type="hidden" name="old_file_rname[]" value="">
								</td>
							</tr>
							<?
								}
							?>
							<tr> 
								<th scope="row">사용여부</th>
								<td colspan="3">
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('Y');" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('N');" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 사용 안함 
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									<input type="hidden" name="del_tf" value=""> 
								</td>
							</tr>
							<!--
							<tr> 
								<th scope="row">썸네일 리스트 노출여부</th>
								<td colspan="3">
									<input type="radio" class="radio" name="rd_top_tf" value="Y" <? if (($rs_top_tf =="Y") || ($rs_top_tf =="")) echo "checked"; ?>> 보이기<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_top_tf" value="N" <? if ($rs_top_tf =="N")echo "checked"; ?>> 보이지않기 
									<input type="hidden" name="top_tf" value="<?= $rs_top_tf ?>"> 
								</td>
							</tr>

							<tr> 
								<th scope="row">메인노출여부</th>
								<td colspan="3">
									<input type="radio" class="radio" name="rd_main_tf" value="Y" <? if (($rs_main_tf =="Y") || ($rs_main_tf =="")) echo "checked"; ?>> 메인노출<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_main_tf" value="N" <? if ($rs_main_tf =="N")echo "checked"; ?>> 메인비노출 
									<input type="hidden" name="main_tf" value="<?= $rs_main_tf ?>"> 
								</td>
							</tr>
							-->
						</tbody>
					</table>
				</div>

				<div class="btnright">
					<? 
						if ($s_adm_no == $rs_reg_adm || $sPageRight_I == "Y") {
							echo '<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>';
							if ($b_no <> "") {
								if (($mode <> "R") && ($b_reply_tf == "Y")) {
									echo '<button type="button" class="btn-navy" onClick="js_reply();" style="width:100px">답변</button>';
								}

								if($sPageRight_D=="Y"){
									echo '<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>';
								}

 							}
						}
					?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>

			</div>
		</div>
	</div>

</form>
<SCRIPT LANGUAGE="JavaScript">

$("#txt_color").minicolors({
	animationSpeed: 50,
	animationEasing: 'swing',
	change: null,
	changeDelay: 0,
	control: 'hue',
	defaultValue: '',
	format: 'hex',
	hide: null,
	hideSpeed: 100,
	inline: false,
	keywords: '',
	letterCase: 'lowercase',
	opacity: false,
	position: 'bottom left',
	show: null,
	showSpeed: 100,
	theme: 'default',
	swatches: []
});

</SCRIPT>

	<!-- S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>
<script type="text/javascript" src="/admin/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
