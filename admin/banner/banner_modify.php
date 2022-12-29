<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : equipment_modify.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-10-16
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
	$menu_right = "CO001"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";
 
#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header_ptj.php"; 

	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util_ptj.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/biz/banner/banner.php";
	
	/*
	require "../../_common/config.php";	
	require "../../_classes/com/util/Util_ptj.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc_ptj.php";
	require "../../_classes/biz/banner/banner.php";
	*/
	

	$mm_subtree	 = "4";

#====================================================================
# DML Process
#====================================================================
	 
	if ( $mode == "S" ) {

		$arr_rs = selectBanner($conn, $banner_no);
		 
		$rs_banner_no				= trim($arr_rs[0]["BANNER_NO"]); 
		$rs_banner_type				= trim($arr_rs[0]["BANNER_TYPE"]); 
		$rs_banner_nm				= trim($arr_rs[0]["BANNER_NM"]); 		
		$rs_banner_url				= trim($arr_rs[0]["BANNER_URL"]); 
		$rs_title_nm				= trim($arr_rs[0]["TITLE_NM"]); 
		$rs_sub_title_nm			= trim($arr_rs[0]["SUB_TITLE_NM"]); 
		$rs_disp_seq				= trim($arr_rs[0]["DISP_SEQ"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_banner_img				= trim($arr_rs[0]["BANNER_IMG"]); 
		$rs_banner_real_img			= trim($arr_rs[0]["BANNER_REAL_IMG"]); 
		
		if ($rs_use_tf == "Y") {
			$STR_USE_TF = "<font color='navy'>사용</font>";
		} else {
			$STR_USE_TF = "<font color='red'>사용안함</font>";
		}		
	}
#====================================================================
	$savedir1 = $g_physical_path."upload_data/banner";
#====================================================================
		
	if ($mode == "U") {  
		
		$rd_del_tf						= $_POST['del_tf']!=''?$_POST['del_tf']:$_GET['del_tf'];	
		
		if (($_FILES["banner_img"]["name"] != "") || ($old_banner_img != "")) {
			$banner_img	= upload($_FILES["banner_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$banner_rnm	= $_FILES[banner_img][name];
		}
		 
		if ( $banner_img == "" && $old_banner_img != "" ) {
			$arr_data = array("BANNER_TYPE"=>$banner_type,
											"BANNER_NM"=>$txt_banner_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,			
											"DEL_TF"=>$rd_del_tf,			
											"TITLE_NM"=>$txt_title_nm,
											"SUB_TITLE_NM"=>$txt_sub_title_nm,
											"BANNER_URL"=>$txt_banner_url,											 
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"UP_DATE"=>date("Y-m-d",strtotime("0 day"))											
											);
		} else {
			$arr_data = array("BANNER_TYPE"=>$banner_type,
											"BANNER_NM"=>$txt_banner_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"DEL_TF"=>$rd_del_tf,			
											"BANNER_IMG"=>$banner_img,
											"BANNER_REAL_IMG"=>$banner_rnm,
											"TITLE_NM"=>$txt_title_nm,
											"SUB_TITLE_NM"=>$txt_sub_title_nm,
											"BANNER_URL"=>$txt_banner_url,											 
											"UP_ADM"=>$_SESSION['s_adm_no'],
											"UP_DATE"=>date("Y-m-d",strtotime("0 day"))											
											);
		}
		

		 
		$result = updateBannerAll($conn, $arr_data, $banner_no);
		
	}	
	 
	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_eq_type=".$con_eq_type."&con_eq_user=".$con_eq_user."&con_eq_user_name=".$con_eq_user_name."&con_eq_state=".$con_eq_state."&search_field=".$search_field."&search_str=".$search_str;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		//document.location.href = "banner_list.php<?=$strParam?>";
		document.location.href = "banner_list.php";
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
		frm.mode.value = "L";
		frm.action = "banner_list.php?mode=L";
		frm.submit();
	}

	function js_save() {

		var frm = document.frm;
		var banner_no = "<?= $banner_no ?>";
		 
		if(document.frm.txt_banner_nm.value==""){
			alert('제목을 입력해주세요.');
			frm.txt_banner_nm.focus();
			return;
		}
		 
		if(frm.txt_disp_seq.value==""){
			alert('순번을 입력해주세요.');
			frm.txt_disp_seq.focus();
			return;
		}

		if (isNull(banner_no)) {		
			frm.mode.value = "I";
		} else {		
			frm.mode.value = "U";
			frm.banner_no.value = frm.banner_no.value;
		}
		
		frm.target = "";
		frm.action = "banner_modify.php";
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

<!--<form name="frm" method="post">-->
<form name="frm" id="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="banner_no" value="<?=$banner_no?>" />
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
							<!--
							<tr>
								<th>배너타입</th>
								<td colspan="5">									
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									<?= makeBannerSelectBoxOnChange($conn, "BANNER" , "banner_type", "", "배너 타입 선택", "", $rs_banner_type); ?>									 
								</td>								 
							</tr>
							-->
							<tr>
								<th>제목</th>								
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_banner_nm" value="<?=$rs_banner_nm?>" /></span></td>
							</tr> 
							<tr>
								<th>순번</th>
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_disp_seq" value="<?=$rs_disp_seq?>" /></span></td>
							</tr>
							<tr>
								<th>사용여부</th>
								<td colspan="5">
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('Y');" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_use_tf" onclick="js_usetf('N');" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 미 사용
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									<input type="hidden" name="del_tf" value="<?= $rs_del_tf ?>"> 
								</td>
							</tr>
							<tr>
								<th scope="row">배너 이미지*<br>(1920*1081)</th>
								<td colspan="5">
									<!--
										<span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_img" value="<?=$rs_banner_real_img?>" /></span>
										<input type="file" size="40%" name="banner_img">
										<input type="hidden" name="old_banner_img" value="<?=$rs_banner_real_img?>">									 
									-->
									<!--<?=$rs_banner_real_img?><br />-->
									<?
									if (strlen($rs_banner_real_img) > 3) { 
									?>
										<img src="/upload_data/banner/<?=$rs_banner_img?>" width="310" >
										<input type="file" size="40%" name="banner_img">
										<input type="hidden" name="old_banner_img" value="<?=$rs_banner_img?>">
									<?
										} else {	
									?>	
										
										<input type="file" size="40%" name="banner_img">
										<input type="hidden" name="old_banner_img" value="">
										
									<?
										}
									?>
								</td>
							</tr>
							<tr>
								<th>타이틀</th>
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_title_nm" value="<?=$rs_title_nm?>" /></span></td>
							</tr>
							<tr>
								<th>서브 타이틀</th>
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_sub_title_nm" value="<?=$rs_sub_title_nm?>" /></span></td>
							</tr>
							<tr>
								<th>URL</th>
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_banner_url" value="<?=$rs_banner_url?>" /></span></td>
							</tr>  
						</tbody>
						 
					</table>
				</div>
 
				<div class="btnright">
				<? if ($banner_no <> "" ) {?>
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