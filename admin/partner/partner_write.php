<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : partner_write.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-11-14
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

	$menu_right = "PT001"; // 메뉴마다 셋팅 해 주어야 합니다
	
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
	require "../../_classes/biz/partner/partner.php";
  
	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];	
	$partner_nm						= $_POST['txt_partner_nm']!=''?$_POST['txt_partner_nm']:$_GET['txt_partner_nm'];
	$disp_seq						= $_POST['txt_disp_seq']!=''?$_POST['txt_disp_seq']:$_GET['txt_disp_seq'];
	$use_tf							= $_POST['rd_use_tf']!=''?$_POST['rd_use_tf']:$_GET['rd_use_tf'];
	$del_tf							= $_POST['del_tf']!=''?$_POST['del_tf']:$_GET['del_tf'];
	
	$down_img						= $_POST['down_img']!=''?$_POST['down_img']:$_GET['down_img'];
	$up_img							= $_POST['up_img']!=''?$_POST['up_img']:$_GET['up_img'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize						= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field					= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str						= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	
	$down_img						= SetStringToDB($down_img);
	$up_img							= SetStringToDB($up_img);
	
	$portfolio_nm					= $_POST['txt_portfolio_nm']!=''?$_POST['txt_portfolio_nm']:$_GET['txt_portfolio_nm'];
	
	$p_contents						= $_POST['p_contents']!=''?$_POST['p_contents']:$_GET['p_contents'];
  
	#====================================================================
	$savedir1 = $g_physical_path."upload_data/partner";
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
		$down_img = "";
		$up_img = "";
		
		if (($_FILES["down_img"]["name"] != "") || ($old_down_img != "")) {
			$down_img	= upload($_FILES["down_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$down_rnm	= $_FILES[down_img][name];
		}
		
		if (($_FILES["up_img"]["name"] != "") || ($old_overview_img != "")) {
			$up_img	= upload($_FILES["up_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$up_rnm	= $_FILES[up_img][name];
		}
		
		$arr_data = array( "PARTNER_NM"=>$partner_nm,
											"DISP_SEQ"=>$disp_seq,
											"USE_TF"=>$use_tf,
											"DEL_TF"=>$del_tf,
											"CONTENTS"=>$p_contents,
											"DOWN_IMG"=>$down_img,
											"DOWN_REAL_IMG"=>$down_rnm,
											"UP_IMG"=>$up_img,
											"UP_REAL_IMG"=>$up_rnm,
											"PORTFOLIO_NM"=>$portfolio_nm,
											"REG_ADM"=>$_SESSION['s_adm_no']
										);		
		
		$new_partner_no = insertPartner($conn, $arr_data); 
	}
  
	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_p_yyyy=".$con_p_yyyy."&con_p_mm=".$con_p_mm."&con_p_category=".$con_p_category."&search_field=".$search_field."&search_str=".$search_str;

	if ($new_partner_no)  {

?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "partner_list.php<?=$strParam?>";
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

	function js_list() {
		document.location = "partner_list.php<?=$strParam?>";
	}

	function js_save() {

		var frm = document.frm;
		var partner_no = "<?=$partner_no?>";
		
		if(document.frm.txt_partner_nm.value==""){
			alert('제목을 입력해주세요.');
			document.frm.txt_partner_nm.focus();
			return;
		}

		if(document.frm.txt_disp_seq.value==""){
			alert('순번을 입력해주세요.');
			document.frm.txt_disp_seq.focus();
			return;
		}
		
		if ( frm.txt_portfolio_nm.length == "" ) {
			alert('50자 이상을 입력할 수 없습니다.');
			frm.txt_fortfolio.focus();
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
		 
		if (isNull(partner_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
			frm.partner_no.value = frm.partner_no.value;
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
<input type="hidden" name="partner_no" value="<?=$partner_no?>" />
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
								<th scope="row">파트너 명*</th>
								<td colspan="5">
									<span class="inpbox" style="width:70%;"><input type="text" class="txt" name="txt_partner_nm" id="partner_nm" value="<?=$rs_partner_nm?>" /></span>
								</td>
							</tr>
							<tr>
								<th scope="row">순번*</th> 
								<td colspan="5">
									<span class="inpbox" style="width:70%;"><input type="text" class="txt" name="txt_disp_seq" id="disp_seq" value="<?=$rs_disp_seq?>" onblur="javascript:validation();" style="text-align:right" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"/></span>
								</td>
							</tr>
							<tr> 
								<th scope="row">사용여부</th>
								<td colspan="5">
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('Y');" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('N');" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 사용 안함
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									<input type="hidden" name="del_tf" value="<?= $rs_del_tf ?>"> 
								</td>
							</tr>
							<!--
							<tr>
								<th scope="row">본문*</th> 								
								<td colspan="5">
									<div style="float:left;display:inline;width:70%;">
										<textarea style="width:100%; height:205px" name="p_contents" id="p_contents" ><?=$rs_p_contents?></textarea>
									</div>									
								</td>
							</tr>
							-->
							<tr>
								<th scope="row">흑백 로고<br>(160*56)</th>
								<td colspan="5">
								<?
									if (strlen($rs_down_img) > 3) {
								?>
									<img src="/upload_data/partner/<?=$rs_down_img?>" width="310" >
									<input type="file" size="40%" name="down_img">
									<input type="hidden" name="old_down_img" value="<?=$rs_down_img?>">
								<?
									} else {	
								?>
									<input type="file" size="40%" name="down_img">
									<input type="hidden" name="old_down_img" value="">
								<?
									}
								?>
								</td>
							</tr>							 
							<tr>
								<th scope="row">컬러 로고<br>(160*56)</th>
								<td colspan="5">
									<?
									if (strlen($rs_up_img) > 3) {
								?>
									<img src="/upload_data/partner/<?=$rs_up_img?>" width="310" >
									<input type="file" size="40%" name="up_img">
									<input type="hidden" name="old_up_img" value="<?=$rs_up_img?>">
								<?
									} else {	
								?>
									<input type="file" size="40%" name="up_img">
									<input type="hidden" name="old_up_img" value="">
								<?
									}
								?>
								</td>
							</tr>
							<tr>
								<th scope="row">대표 포트폴리오</th>
								<td colspan="5">
									<span class="inpbox" style="width:70%;"><input type="text" class="txt" name="txt_portfolio_nm" id="portfolio_nm" value="<?=$rs_portfolio_nm?>" maxlength="50" /></span>
								</td>
							</tr> 
						</tbody>
					</table>
				</div>
				<div class="btnright">				 
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
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
