<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : partner_modify.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-11-14
# Modify Date  : 
#	Copyright    : Copyright @Ucom Corp. All Rights Reserved.
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
	include "../../_common/common_header.php"; 

	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";	
	require "../../_classes/biz/partner/partner.php";

	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================
	 
	if ( $mode == "S" ) {

		$arr_rs = selectPartner($conn, $partner_no);		 
		
		$rs_partner_nm				= trim($arr_rs[0]["PARTNER_NM"]);
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);
		$rs_up_date					= trim($arr_rs[0]["UP_DATE"]);		
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 		
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 		
		$rs_contents				= trim($arr_rs[0]["CONTENTS"]); 
		$rs_down_img				= trim($arr_rs[0]["DOWN_IMG"]); 
		$rs_down_real_img			= trim($arr_rs[0]["DOWN_REAL_IMG"]); 		
		$rs_up_img					= trim($arr_rs[0]["UP_IMG"]); 
		$rs_up_real_img				= trim($arr_rs[0]["UP_REAL_IMG"]); 		
		$rs_partner_no				= trim($arr_rs[0]["PARTNER_NO"]); 
		$rs_disp_seq				= trim($arr_rs[0]["DISP_SEQ"]); 
		$rs_portfolio_nm			= trim($arr_rs[0]["PORTFOLIO_NM"]);
		
		if ($rs_use_tf == "Y") {
			$STR_USE_TF = "<font color='navy'>사용</font>";
		} else {
			$STR_USE_TF = "<font color='red'>사용 안함</font>";
		}		
	}
	
#====================================================================
	$savedir1 = $g_physical_path."upload_data/partner";
#====================================================================
	 
	if ($mode == "U") {
		
		$rd_del_tf						= $_POST['del_tf']!=''?$_POST['del_tf']:$_GET['del_tf'];	
		
		if (($_FILES["down_img"]["name"] != "") || ($old_down_img != "")) {
			$down_img	= upload($_FILES["down_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$down_rnm	= $_FILES[down_img][name];
		}
		
	    if (($_FILES["up_img"]["name"] != "") || ($old_up_img != "")) {
			$up_img	= upload($_FILES["up_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$up_rnm	= $_FILES[up_img][name];
		}
		
		if ( $down_img == "" && $up_img == "" ) {
			$arr_data = array("PARTNER_NM"=>$txt_partner_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"DEL_TF"=>$rd_del_tf,
											"CONTENTS"=>$p_contents,											
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"PORTFOLIO_NM"=>$txt_portfolio_nm 
										);
		}
		
		if ( $down_img != "" && $up_img != "" ) {
			$arr_data = array("PARTNER_NM"=>$txt_partner_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"DEL_TF"=>$rd_del_tf,											
											"CONTENTS"=>$p_contents,	
											"DOWN_IMG"=>$down_img,
											"DOWN_REAL_IMG"=>$down_rnm,
											"UP_IMG"=>$up_img,
											"UP_REAL_IMG"=>$up_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"PORTFOLIO_NM"=>$txt_portfolio_nm
										);
		}
		
		if ( $down_img == "" && $up_img != "" ) {
			$arr_data = array("PARTNER_NM"=>$txt_partner_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,											
											"DEL_TF"=>$rd_del_tf,
											"CONTENTS"=>$p_contents,												
											"UP_IMG"=>$up_img,
											"UP_REAL_IMG"=>$up_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"PORTFOLIO_NM"=>$txt_portfolio_nm
										);
		}
		
		if ( $down_img != "" && $up_img == "" ) {
			$arr_data = array("PARTNER_NM"=>$txt_partner_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,											
											"DEL_TF"=>$rd_del_tf,
											"CONTENTS"=>$p_contents,	
											"DOWN_IMG"=>$down_img,
											"DOWN_REAL_IMG"=>$down_rnm,
											"UP_IMG"=>$up_img,
											"UP_REAL_IMG"=>$up_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"PORTFOLIO_NM"=>$txt_portfolio_nm
										);
		}
		
		if ( $down_img == "" && $old_down_img != "" && $up_img != "" ) {
			$arr_data = array("PARTNER_NM"=>$txt_partner_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"DEL_TF"=>$rd_del_tf,											
											"CONTENTS"=>$p_contents,
											"UP_IMG"=>$up_img,
											"UP_REAL_IMG"=>$up_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"PORTFOLIO_NM"=>$txt_portfolio_nm
										);
		}
		
		if ( $down_img != "" && $old_up_img != "" && $up_img == "" ) {
			$arr_data = array("PARTNER_NM"=>$txt_partner_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"DEL_TF"=>$rd_del_tf,
											"CONTENTS"=>$p_contents,
											"DOWN_IMG"=>$down_img,
											"DOWN_REAL_IMG"=>$down_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"PORTFOLIO_NM"=>$txt_portfolio_nm
										);
		}
		
		if ( $down_img != "" && $up_img != "" ) {
			$arr_data = array("PARTNER_NM"=>$txt_partner_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"DEL_TF"=>$rd_del_tf,
											"CONTENTS"=>$p_contents,	
											"DOWN_IMG"=>$down_img,
											"DOWN_REAL_IMG"=>$down_rnm,
											"UP_IMG"=>$up_img,
											"UP_REAL_IMG"=>$up_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"PORTFOLIO_NM"=>$txt_portfolio_nm
										);
		}
	        
		/*
		if ( ( $story_img == "" && $old_story_img != "" ) && ( $story_img == "" && $old_story_img != "" ) ) {
			$arr_data = array("STORY_TYPE"=>$story_type,
											"STORY_NM"=>$txt_story_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"MAIN_TF"=>$rd_main_tf,
											"CONTENTS"=>$p_contents,											
											"REG_ADM"=>$_SESSION['s_adm_no']
										);
		}
		
		if ( $story_img == "" && $old_story_img != "" ) {
			$arr_data = array("STORY_TYPE"=>$story_type,
											"STORY_NM"=>$txt_story_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"MAIN_TF"=>$rd_main_tf,
											"CONTENTS"=>$p_contents,
											"STORY_IMG"=>$story_img,
											"STORY_REAL_IMG"=>$story_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no']
										);
		}
		
		*/ 
		$result = updatePartner($conn, $arr_data, $partner_no);
	}	
	 
	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_eq_type=".$con_eq_type."&con_eq_user=".$con_eq_user."&con_eq_user_name=".$con_eq_user_name."&con_eq_state=".$con_eq_state."&search_field=".$search_field."&search_str=".$search_str;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "partner_list.php<?=$strParam?>";
</script>
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
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->

<script type="text/javascript">
	 
	function js_list() {
		var frm = document.frm;
			
		frm.method = "get";
		frm.action = "partner_list.php";
		frm.submit();
	}

	function js_save() {

		var frm = document.frm;
		var partner_no = "<?=$partner_no?>";
		 
		if(document.frm.txt_partner_nm.value==""){
			alert('제목을 입력해주세요.');
			frm.txt_partner_nm.focus();
			return;
		}
		 
		if(frm.txt_disp_seq.value==""){
			alert('순번을 입력해주세요.');
			frm.txt_disp_seq.focus();
			return;
		}

		if (isNull(partner_no)) {		
			frm.mode.value = "I";
		} else {		
			frm.mode.value = "U";
			frm.partner_no.value = frm.partner_no.value;
		}
		
		frm.target = "";
		frm.action = "partner_modify.php";
		frm.submit();	
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
<input type="hidden" name="partner_no" value="<?=$partner_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

 				<div class="boardlist search">
				
					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:24%" />
						</colgroup>
						<tbody> 														
							<tr>
								<th>제목</th>								
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_partner_nm" value="<?=$rs_partner_nm?>" /></span></td>
							</tr> 
							<tr>
								<th>순번</th>
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_disp_seq" value="<?=$rs_disp_seq?>" /></span></td>
							</tr>
							<tr>
								<th>사용여부</th>
								<td colspan="5">
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('Y');" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('N');" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 사용 안함
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									<input type="hidden" name="del_tf" value="<?= $rs_del_tf ?>"> 
								</td>
							</tr> 
							<!--
							<tr>
								<th>본문</th>
								<td colspan="5">
									<div style="float:left;width:70%;">
										<textarea style="width:100%; height:205px" name="p_contents" id="p_contents" ><?=$rs_contents?></textarea>
									</div>
								</td>
							</tr>
							-->
							<tr>
								<th>흑백 로고<br>(160*56)</th>
								<td colspan="5">
									<!--
									<input type="file" size="40%" name="down_img">
									<input type="hidden" name="old_down_img" value="<?=$rs_down_real_img?>">									 
									-->
									<!--<?=$rs_down_real_img?><br />-->
									<?
									if (strlen($rs_down_real_img) > 3) { 
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
								<th>컬러 로고<br>(160*56)</th>
								<td colspan="5">
									<!--
										<input type="file" size="40%" name="up_img">
										<input type="hidden" name="old_up_img" value="<?=$rs_up_real_img?>">
									-->
									<!--<?=$rs_up_real_img?><br />-->
									<?
									if (strlen($rs_up_real_img) > 3) { 
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
								<th>대표 포트폴리오</th>								
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_portfolio_nm" value="<?=$rs_portfolio_nm?>" /></span></td>
							</tr> 
						</tbody>
						 
					</table>
				</div>
 
				<div class="btnright">
				<? if ($partner_no <> "" ) {?>
					<? //if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<? //} ?>
				<? }?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>
      <!-- // E: mwidthwrap -->

			</div>
		</div>
	</div>

	<!-- //S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/admin/js/common_ui.js"></script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>