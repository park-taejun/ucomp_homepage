<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : equipment_modify.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-12-20
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
	$menu_right = "CO004"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/team/team.php";
 
	$mm_subtree	 = "4";

#====================================================================
# DML Process
#====================================================================
	 
	if ( $mode == "S" ) {

		$arr_rs = selectTeam($conn, $team_no);
		 
		$rs_team_no					= trim($arr_rs[0]["TEAM_NO"]); 
		$rs_disp_seq				= trim($arr_rs[0]["DISP_SEQ"]); 
		$rs_team_img				= trim($arr_rs[0]["TEAM_IMG"]); 
		$rs_team_real_img			= trim($arr_rs[0]["TEAM_REAL_IMG"]); 
		$rs_team_nm					= trim($arr_rs[0]["TEAM_NM"]); 		
		$rs_team_contents			= trim($arr_rs[0]["TEAM_CONTENTS"]); 		
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		
		if ($rs_use_tf == "Y") {
			$STR_USE_TF = "<font color='navy'>사용</font>";
		} else {
			$STR_USE_TF = "<font color='red'>사용안함</font>";
		}		
	}
#====================================================================
	$savedir1 = $g_physical_path."upload_data/team";
#====================================================================
		
	if ($mode == "U") {  
		
		$rd_del_tf					= $_POST['del_tf']!=''?$_POST['del_tf']:$_GET['del_tf'];	
		$dept_code					= $_POST['dept_code']!=''?$_POST['dept_code']:$_GET['dept_code'];
		$team_contents				= $_POST['txt_team_contents']!=''?$_POST['txt_team_contents']:$_GET['txt_team_contents'];
		
		
		if (($_FILES["team_img"]["name"] != "") || ($old_team_img != "")) {
			$team_img	= upload($_FILES["team_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$banner_rnm	= $_FILES[team_img][name];
		}
		 
		if ( $team_img == "" && $old_team_img != "" ) {
			$arr_data = array(	"TEAM_NM"=>$dept_code,
								"DISP_SEQ"=>$txt_disp_seq,
								"USE_TF"=>$rd_use_tf,
								"DEL_TF"=>$rd_del_tf,
								"TEAM_CONTENTS"=>$team_contents,
								"UP_ADM"=>$_SESSION['s_adm_no'],
								"UP_DATE"=>date("Y-m-d",strtotime("0 day"))											
							);
		} else {
			$arr_data = array(	"TEAM_NM"=>$dept_code,
								"DISP_SEQ"=>$txt_disp_seq,
								"USE_TF"=>$rd_use_tf,
								"DEL_TF"=>$rd_del_tf,
								"TEAM_IMG"=>$team_img,
								"TEAM_REAL_IMG"=>$team_rnm,
								"TEAM_CONTENTS"=>$team_contents,
								"UP_ADM"=>$_SESSION['s_adm_no'],
								"UP_DATE"=>date("Y-m-d",strtotime("0 day"))											
							);							
		}
		
		$result = updateTeamAll($conn, $arr_data, $team_no);		
	}	
	 
	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_eq_type=".$con_eq_type."&con_eq_user=".$con_eq_user."&con_eq_user_name=".$con_eq_user_name."&con_eq_state=".$con_eq_state."&search_field=".$search_field."&search_str=".$search_str;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');		
		document.location.href = "team_list.php";
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
		frm.action = "team_list.php";
		frm.submit();
	}

	function js_save() {

		var frm = document.frm;
		var team_no = "<?= $team_no ?>";
	 
		if(frm.txt_disp_seq.value==""){
			alert('순번을 입력해주세요.');
			frm.txt_disp_seq.focus();
			return;
		}

		if (isNull(team_no)) {		
			frm.mode.value = "I";
		} else {		
			frm.mode.value = "U";
			frm.team_no.value = frm.team_no.value;
		}
		
		frm.target = "";
		frm.action = "team_modify.php";
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
<input type="hidden" name="team_no" value="<?=$team_no?>" />
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
								<th scope="row">팀 이름*</th>
								<td colspan="5">
									<span class="optionbox">
									    <?= makeSelectBox($conn,"DEPT_2022","dept_code","125px","선택","",$rs_team_nm)?>
									</span>
								</td>
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
								<th>이미지<br>(1030*520)</th>
								<td colspan="5">									
									<?
									if (strlen($rs_team_real_img) > 3) { 
									?>
										<img src="/upload_data/team/<?=$rs_team_img?>" width="310" >
										<input type="file" size="40%" name="team_img">
										<input type="hidden" name="old_team_img" value="<?=$rs_team_img?>">
									<?
										} else {	
									?>	
										
										<input type="file" size="40%" name="team_img">
										<input type="hidden" name="old_team_img" value="">
										
									<?
										}
									?>
								</td>
							</tr>
							<tr>
								<th scope="row">팀 소개</th>
								<td colspan="5">
									<div style="float:left;display:inline;width:30%;">
										<textarea style="width:100%; height:85px" name="txt_team_contents" id="team_contents" maxlength="20" ><?=$rs_team_contents?></textarea>
									</div>
								</td>
							</tr>							 
						</tbody>						 
					</table>
				</div>
 
				<div class="btnright">
				<? if ($team_no <> "" ) {?>
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