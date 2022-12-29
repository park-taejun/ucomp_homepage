<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : team_write.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-12-19
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

	$menu_right = "CO004"; // 메뉴마다 셋팅 해 주어야 합니다
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
	require "../../_classes/biz/team/team.php";

	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];	
	// $team_nm					= $_POST['txt_team_nm']!=''?$_POST['txt_team_nm']:$_GET['txt_team_nm'];	
	$disp_seq					= $_POST['txt_disp_seq']!=''?$_POST['txt_disp_seq']:$_GET['txt_disp_seq'];
	$use_tf						= $_POST['rd_use_tf']!=''?$_POST['rd_use_tf']:$_GET['rd_use_tf'];
	$del_tf						= $_POST['rd_del_tf']!=''?$_POST['rd_del_tf']:$_GET['rd_del_tf'];	
	$team_img					= $_POST['team_img']!=''?$_POST['team_img']:$_GET['team_img'];	
	$team_contents				= $_POST['txt_team_contents']!=''?$_POST['txt_team_contents']:$_GET['txt_team_contents'];
	$nPage						= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];	
	$team_img					= SetStringToDB($team_img);
	$dept_code					= $_POST['dept_code']!=''?$_POST['dept_code']:$_GET['dept_code'];
	
	#====================================================================
	$savedir1 = $g_physical_path."upload_data/team";
	#====================================================================

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($_SESSION['s_adm_no']) {
		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");
	} else {
		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "USER");
	}

	$max_allow_file_size = $allow_file_size * 1024 * 1024;

	$ref_ip = $_SERVER['REMOTE_ADDR'];
	
	if ( $del_tf == "" ) {
		$del_tf = 'N';
	}
	 
	if ($mode == "I") {	
		$team_img = "";
		
		if (($_FILES["team_img"]["name"] != "") || ($old_team_img != "")) {
			$team_img	= upload($_FILES["team_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$team_rnm	= $_FILES[team_img][name];
		}
		
		$arr_data = array("TEAM_NM"=>$dept_code,											
											"TEAM_IMG"=>$team_img,
											"TEAM_REAL_IMG"=>$team_rnm,
											"DISP_SEQ"=>$disp_seq,
											"TEAM_CONTENTS"=>$team_contents,
											"USE_TF"=>$use_tf,
											"DEL_TF"=>$del_tf,
											"REG_ADM"=>$_SESSION['s_adm_no']
										);
		
		$new_eq_no = insertTeam($conn, $arr_data);
		  
	}
 
	if ($mode == "S") {
		
		$arr_rs = selectPortfolio($conn, $p_no);

		$rs_p_no			= trim($arr_rs[0]["P_NO"]); 
		
	} 
	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_p_yyyy=".$con_p_yyyy."&con_p_mm=".$con_p_mm."&con_p_category=".$con_p_category."&search_field=".$search_field."&search_str=".$search_str;

	if ($new_eq_no)  {

?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "team_list.php<?=$strParam?>";
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
<link type="text/css" rel="stylesheet" href="/admin/css/jquery.minicolors.css" />
<script src="/admin/js/jquery.minicolors.min.js"></script>

<script language="javascript" type="text/javascript">
<!--

	function js_list() {		
		var frm = document.frm;
		
		if ( frm.txt_disp_seq.value != "" || frm.team_img.value != "" || frm.txt_team_contents.value != "" ) {
			
			list_url = confirm('저장하지 않고 목록으로 돌아가시겠습니까?');
			
			if ( list_url == true) {			
				frm.target = "";
				frm.action = "team_list.php";
				frm.submit();  
			}
		} else {
			frm.target = "";
			frm.action = "team_list.php";
			frm.submit();  
		}	
	}

	function js_save() {

		var frm = document.frm;
		var team_no = "<?= $team_no ?>";
		/*
		if(document.frm.txt_team_nm.value == ""){
			alert('팀 이름을 입력해주세요.');
			document.frm.txt_team_nm.focus();
			return;
		}
		*/

		if(document.frm.txt_disp_seq.value == ""){
			alert('순번을 입력해주세요.');
			document.frm.txt_disp_seq.focus();
			return;
		}
		
		if(document.frm.team_img.value == ""){
			alert('파일을 선택해주세요.');
			document.frm.team_img.focus();
			return;
		}
		
		if(document.frm.txt_team_contents.value == ""){
			alert('팀 소개를 입력해주세요.');
			document.frm.txt_team_contents.focus();
			return;
		}
	 
		
		if (isNull(team_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
			frm.team_no.value = frm.team_no.value;
		}
		
		insertOK = confirm('등록 하시겠습니까?');
		
		if ( insertOK == true) {
			frm.method = "post";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
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
<input type="hidden" name="team_no" value="<?=$p_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
<input type="hidden" name="con_dept_code" value="<?=$con_dept_code?>">
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
								<th scope="row">팀 이름*</th>
								<td colspan="5">									
									<span class="optionbox">									
									    <?= makeSelectBox($conn,"DEPT_2022","dept_code","125px","선택","",$rs_dept_code)?>
									</span>
								</td>
							</tr>
							<tr>
								<th scope="row">순번*</th> 
								<td colspan="5">
									<span class="inpbox" style="width:70%;"><input type="text" class="txt" name="txt_disp_seq" id="disp_seq" value="<?=$rs_p_name02?>" onblur="javascript:validation();" style="text-align:right" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"/></span>
								</td>
							</tr>
							<tr> 
								<th scope="row">사용여부</th>
								<td colspan="3">
									<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 미 사용
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
									<input type="hidden" name="del_tf" value="<?= $rs_del_tf ?>"> 
								</td>
							</tr>
							<tr>
								<th scope="row">팀 이미지*<br>(1030*520)</th>
								<td colspan="5">
								<?
									if (strlen($rs_banner_img) > 3) {
								?>
									<img src="/upload_data/team/<?=$rs_banner_img?>" width="310" >
									<input type="file" size="40%" name="team_img">
									<input type="hidden" name="old_team_img" value="<?=$rs_banner_img?>">
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
										<textarea style="width:100%; height:85px" name="txt_team_contents" id="team_contents" maxlength="20" ><?=$rs_p_name01?></textarea>
									</div>
								</td>
							</tr> 
						</tbody>
					</table>
				</div>
				<div class="btnright">				 
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
					<!--
						<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
					-->
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
