<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : story_modify.php
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
	$menu_right = "CO003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";	
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/story/story.php";
	
	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================
	 
	if ( $mode == "S" ) {

		$arr_rs = selectStory($conn, $story_no);
		$arr_rs_file	= listStoryFile($conn, $story_no);
		 
		$rs_story_type				= trim($arr_rs[0]["STORY_TYPE"]); 
		$rs_story_nm				= trim($arr_rs[0]["STORY_NM"]);
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);
		$rs_up_date					= trim($arr_rs[0]["UP_DATE"]);
		$rs_click_cnt				= trim($arr_rs[0]["CLICK_CNT"]);
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_main_tf					= trim($arr_rs[0]["MAIN_TF"]); 
		$rs_contents				= trim($arr_rs[0]["CONTENTS"]); 
		$rs_story_img				= trim($arr_rs[0]["STORY_IMG"]); 
		$rs_story_real_img			= trim($arr_rs[0]["STORY_REAL_IMG"]); 		
		
		$rs_story_no				= trim($arr_rs[0]["STORY_NO"]); 
		$rs_disp_seq				= trim($arr_rs[0]["DISP_SEQ"]); 
		
		if ($rs_use_tf == "Y") {
			$STR_USE_TF = "<font color='navy'>사용</font>";
		} else {
			$STR_USE_TF = "<font color='red'>사용안함</font>";
		}
		
		if ($rs_main_tf == "Y") {
			$STR_MAIN_TF = "<font color='navy'>사용</font>";
		} else {
			$STR_MAIN_TF = "<font color='red'>사용안함</font>";
		}
	}
	
#====================================================================
	$savedir1 = $g_physical_path."upload_data/story";
#====================================================================

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	if ($_SESSION['s_adm_no']) {
		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "ADMIN");
	} else {
		$allow_file_size = getDcodeName($conn, "FILE_SIZE", "USER");
	}

	$max_allow_file_size = $allow_file_size * 1024 * 1024;

	$ref_ip = $_SERVER['REMOTE_ADDR']; 
	 
	if ($mode == "U") {
		
		$rd_del_tf						= $_POST['del_tf']!=''?$_POST['del_tf']:$_GET['del_tf'];	
		 
		if (($_FILES["story_img"]["name"] != "") || ($old_story_img != "")) {
			$story_img	= upload($_FILES["story_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$story_rnm	= $_FILES[story_img][name];
		}
		
		if ( $story_img != "" || $old_story_img != "" || $story_rnm != "" ) {
			$arr_data = array("STORY_TYPE"=>$story_type,
											"STORY_NM"=>$txt_story_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"DEL_TF"=>$rd_del_tf,
											"MAIN_TF"=>$rd_main_tf,
											"CONTENTS"=>$p_contents,											
											"REG_ADM"=>$_SESSION['s_adm_no']
										);
		} else {
			$arr_data = array("STORY_TYPE"=>$story_type,
											"STORY_NM"=>$txt_story_nm,
											"DISP_SEQ"=>$txt_disp_seq,
											"USE_TF"=>$rd_use_tf,
											"DEL_TF"=>$rd_del_tf,
											"MAIN_TF"=>$rd_main_tf,
											"CONTENTS"=>$p_contents,
											"STORY_IMG"=>$story_img,
											"STORY_REAL_IMG"=>$story_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no'],
											"UP_ADM"=>$_SESSION['s_adm_no']
										);
		}
		
			 
		$result = updateStory($conn, $arr_data, $story_no);
		
		$file_cnt = count($file_name);
		
		$result = deleteStoryFile($conn, $story_no);

		for($i=0; $i <= $file_cnt; $i++) {

			$file_rname				= $_FILES["file_name"]["name"][$i];
			$old_file_name		= $_POST["old_file_name"][$i];

			if (($file_rname != "") || ($old_file_name != "")) {
				
				if ($file_rname != "") {
					$file_name	= multiupload($_FILES["file_name"], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','txt','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','TXT'));
				} else {
					$file_name	= $old_file_name;
				}

				$arr_data = array("STORY_NO"=>$story_no,
													"FILE_NM"=>$file_name,
													"FILE_RNM"=>$file_name,
													"FILE_SIZE"=>0,
													"FILE_EXT"=>"",
													"REG_ADM"=>$_SESSION['s_adm_no'],
													"REG_DATE"=>$REG_INSERT_DATE
												);

				$result = insertStoryFile($conn, $arr_data); 
			}
		} 
	}	
	 
	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_eq_type=".$con_eq_type."&con_eq_user=".$con_eq_user."&con_eq_user_name=".$con_eq_user_name."&con_eq_state=".$con_eq_state."&search_field=".$search_field."&search_str=".$search_str;
?>	  
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "story_list.php<?=$strParam?>";
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
			
		frm.method = "post";
		frm.action = "story_list.php";
		frm.submit();
	}

	function js_save() {

		var frm = document.frm;
		var story_no = "<?= $story_no ?>";
		 
		if(document.frm.txt_story_nm.value==""){
			alert('제목을 입력해주세요.');
			frm.txt_story_nm.focus();
			return;
		}
		 
		if(frm.txt_disp_seq.value==""){
			alert('순번을 입력해주세요.');
			frm.txt_disp_seq.focus();
			return;
		}

		if (isNull(story_no)) {		
			frm.mode.value = "I";
		} else {		
			frm.mode.value = "U";
			frm.story_no.value = frm.story_no.value;
		}
		
		frm.target = "";
		frm.action = "story_modify.php";
		frm.submit();	
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
<input type="hidden" name="story_no" value="<?=$story_no?>" />
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
								<th>스토리 타입</th>
								<td colspan="5">									
									<!--<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>">-->
									<?= makeBannerSelectBoxOnChange($conn, "STORY_TYPE" , "story_type", "", "스토리 타입 선택", "", $rs_story_type); ?>
								</td>								 
							</tr>
							
							<tr>
								<th>제목</th>								
								<td colspan="5"><span class="inpbox"><input type="text" class="txt" style="width:65%" name="txt_story_nm" value="<?=$rs_story_nm?>" /></span></td>
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
									<input type="hidden" name="use_tf" value="<?=$rs_use_tf?>"> 
									<input type="hidden" name="del_tf" value="<?=$rs_del_tf?>"> 
								</td>
							</tr>
							<tr>
								<th>메인여부</th>
								<td colspan="5">
									<input type="radio" class="radio" name="rd_main_tf" value="Y" <? if (($rs_main_tf =="Y") || ($rs_main_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_main_tf" value="N" <? if ($rs_main_tf =="N")echo "checked"; ?>> 사용 안함
									<input type="hidden" name="main_use_tf" value=""> 
									<input type="hidden" name="main_del_tf" value=""> 
								</td>
							</tr>
							<tr>
								<th>본문</th>
								<td colspan="5">
									<div style="float:left;width:70%;">
										<textarea style="width:100%; height:205px" name="p_contents" id="p_contents" ><?=$rs_contents?></textarea>
									</div>
								</td>
							</tr>
							<tr>
								<th>썸네일<br>(586*450)</th>
								<td colspan="5">
									<!--
									<input type="file" size="40%" name="story_img">
									<input type="hidden" name="old_story_img" value="<?=$rs_story_real_img?>">									 
									-->
									<!--<?=$rs_story_real_img?><br />-->
									<?
									if (strlen($rs_story_real_img) > 3) {
									?>
									
										<img src="/upload_data/story/<?=$rs_story_img?>" width="200" height="200">
										<input type="hidden" name="old_story_img" value="<?=$rs_story_img?>">
										<!--<img src='/admin/images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_file' onclick="js_imgDel('<?=$story_no?>','1');" />파일삭제-->
										<input type="file" size="40%" name="story_img">
									<?
										} else {	
									?>	
										
										<input type="file" size="40%" name="story_img">
										<input type="hidden" name="old_story_img" value="">
										
									<?
										}
									?>
								</td>
							</tr>
							<?
								// $arr_rs_file	= listStoryFile($conn, $story_no);
							?>
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
											<td colspan="5">
												<img src="/upload_data/story/<?=$RS_FILE_NM?>" width="310" >									
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
											<td colspan="5">
												<input type="file" size="40%" name="file_name[]">
												<input type="hidden" name="old_file_name[]" value="">
												<input type="hidden" name="old_file_rname[]" value="">
											</td>
										</tr>
							<?
								}
							?>	
						</tbody>
						 
					</table>
				</div>
 
				<div class="btnright">
				<? if ($story_no <> "" ) {?>
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