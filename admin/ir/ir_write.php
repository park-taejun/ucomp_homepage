<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : brochure_write.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-11-22
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

	$menu_right = "IR001"; // 메뉴마다 셋팅 해 주어야 합니다
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
	require "../../_classes/biz/brochure/brochure.php";
	  
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$file_no					= $_POST['file_no']!=''?$_POST['file_no']:$_GET['file_no'];
	$use_tf						= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];	
	$del_tf						= $_POST['del_tf']!=''?$_POST['del_tf']:$_GET['del_tf'];	
	
	$nPage						= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$old_file_nm				= $_POST['old_file_nm']!=''?$_POST['old_file_nm']:$_GET['old_file_nm'];
	$old_file_rnm				= $_POST['old_file_rnm']!=''?$_POST['old_file_rnm']:$_GET['old_file_rnm'];

	$nPage					= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);

	#====================================================================
	$savedir1 = $g_physical_path."upload_data/brochure";
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
		
		$file_nm = "";
		
		if (($_FILES["file_nm"]["name"] != "") || ($old_file_nm != "")) {
			$file_nm	= upload($_FILES["file_nm"], $savedir1, 1000 , array('pdf', 'PDF'));
			$file_rnm	= $_FILES["file_nm"]["name"];
		}
		 
		$arr_data = array("FILE_NM"=>$file_nm,
											"FILE_RNM"=>$file_rnm,
											"USE_TF"=>$use_tf,
											"DEL_TF"=>$del_tf,
											"REG_ADM"=>$_SESSION['s_adm_no']
										);

		$result = insertBrochure($conn, $arr_data, $use_tf  );

	} 

	if ($mode == "U") {
		if ($_FILES["file_nm"]["name"] != "") {
			$file_nm	= upload($_FILES["file_nm"], $savedir1, 1000 , array('pdf', 'PDF'));
			$file_rnm	= $_FILES["file_nm"]["name"];
		} else {
			$file_nm	= $old_file_nm;
			$file_rnm	= $old_file_rnm;
		}
		 
		$arr_data = array("FILE_NM"=>$file_nm,
											"FILE_RNM"=>$file_rnm,
											"USE_TF"=>$use_tf,
											"DEL_TF"=>$del_tf,
											"UP_ADM"=>$_SESSION['s_adm_no']
										);
			
		$result = updateBrochure($conn, $arr_data, $file_no);

	}

	if ($mode == "S") {
		
		$arr_rs = updateViewCntBrochure($conn, $file_no );
		$arr_rs = selectBrochure($conn, $file_no);

		$rs_file_no							= trim($arr_rs[0]["FILE_NO"]); 
		$rs_file_nm							= trim($arr_rs[0]["FILE_NM"]); 
		$rs_file_rnm						= trim($arr_rs[0]["FILE_RNM"]); 
		$rs_hit_cnt							= trim($arr_rs[0]["HIT_CNT"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf							= trim($arr_rs[0]["DEL_TF"]); 
		
	}
	
	if ($mode == "D") {		
		$result = deleteBrochure($conn, $_SESSION['s_adm_no'], $file_no);
	}
	
	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {

?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "ir_list.php<?=$strParam?>";
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
<!--<script type="text/javascript" src="../js/jquery.form.js"></script>-->
<script type="text/javascript" src="/admin/js/jquery.form.js"></script>
<script language="javascript" type="text/javascript">
<!--

	$(document).ready(function() {
		//
	});

	function js_list() {
		document.location = "ir_list.php<?=$strParam?>";
	}

	function js_save() {

		var frm = document.frm;
		var file_no = "<?= $file_no ?>";
		
		if ( document.frm.file_nm.value == "" && document.frm.old_file_nm.value == "" ) {		
			alert('파일을 선택해주세요.');
			document.frm.file_nm.focus();
			return;
		}
		
		if (document.frm.rd_use_tf == null) {
			//alert(document.frm.rd_use_tf);
		} else {
			
			if (frm.rd_use_tf[0].checked == true) {
				frm.use_tf.value = "Y";
				frm.del_tf.value = "N";
			} else {
				frm.use_tf.value = "N";
				frm.del_tf.value = "Y";
			}
		}		 
		
		if ( frm.use_tf.value == null ) {
			frm.use_tf.value = "Y";
			frm.del_tf.value = "N";
		}

		if (isNull(file_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
			frm.file_no.value = frm.file_no.value;
		}
		
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";	
		frm.submit(); 
		
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
<input type="hidden" name="file_no" value="<?=$file_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
				
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
								<th scope="row">파일(pdf)</th>
								<td colspan="3">
								<?
									if (strlen($rs_file_nm) > 3) {
								?>
									<a href="/_common/new_download_file.php?menu=brochure&file_no=<?=$rs_file_no?>"><?=$rs_file_rnm?></a>&nbsp;&nbsp;
									<input type="file" size="40%" name="file_nm">
									<input type="hidden" name="old_file_nm" value="<?=$rs_file_nm?>">
									<input type="hidden" name="old_file_rnm" value="<?=$rs_file_rnm?>">
									<!--<input type="text" name="use_tf" value="<?= $rs_use_tf ?>">-->
									 
								<?
									} else {	
								?>
									<input type="file" size="40%" name="file_nm">
									<input type="hidden" name="old_file_nm" value="">
									<input type="hidden" name="old_file_rnm" value="">
								<?
									}
								?>
								</td>
							</tr>

							<tr> 
								<th scope="row">사용여부</th>
								<td colspan="3">									 
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('Y');" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('N');" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 사용안함 
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									<input type="hidden" name="del_tf" value=""> 
								</td>
							</tr>
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
					<?
						if($sPageRight_D=="Y"){
							echo '<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>';
						}
					?>
				</div>

			</div>
		</div>
	</div>

</form>

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
