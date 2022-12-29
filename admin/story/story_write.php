<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : portfolio_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-03-17
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

	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";	
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/story/story.php";

	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];	
	$story_nm						= $_POST['txt_story_nm']!=''?$_POST['txt_story_nm']:$_GET['txt_story_nm'];
	$disp_seq						= $_POST['txt_disp_seq']!=''?$_POST['txt_disp_seq']:$_GET['txt_disp_seq'];
	$use_tf							= $_POST['rd_use_tf']!=''?$_POST['rd_use_tf']:$_GET['rd_use_tf'];
	$main_tf						= $_POST['rd_main_tf']!=''?$_POST['rd_main_tf']:$_GET['rd_main_tf'];
	/*
	$contents						= $_POST['contents']!=''?$_POST['contents']:$_GET['contents'];
	$contents						= SetStringToDB($contents);
	*/
	$story_img						= $_POST['story_img']!=''?$_POST['story_img']:$_GET['story_img'];
	// $overview_img					= $_POST['overview_img']!=''?$_POST['overview_img']:$_GET['overview_img'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize						= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field					= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str						= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	
	$story_img						= SetStringToDB($story_img);
	// $overview_img					= SetStringToDB($overview_img);
	
	$p_contents						= $_POST['p_contents']!=''?$_POST['p_contents']:$_GET['p_contents'];
  
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
	
	if ($mode == "I") {	
		$story_img = "";
		// $overview_img = "";
		
		if (($_FILES["story_img"]["name"] != "") || ($old_story_img != "")) {
			$story_img	= upload($_FILES["story_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$story_rnm	= $_FILES[story_img][name];
		}
		
		/*
		if (($_FILES["overview_img"]["name"] != "") || ($old_overview_img != "")) {
			$overview_img	= upload($_FILES["overview_img"], $savedir1, 1000 , array('gif', 'jpeg', 'jpg','png'));
			$overview_rnm	= $_FILES[overview_img][name];
		}
		*/
		$arr_data = array("STORY_TYPE"=>$story_type,
											"STORY_NM"=>$story_nm,
											"DISP_SEQ"=>$disp_seq,
											"USE_TF"=>$use_tf,
											"MAIN_TF"=>$main_tf,
											"CONTENTS"=>$p_contents,
											"STORY_IMG"=>$story_img,
											"STORY_REAL_IMG"=>$story_rnm,
										//	"OVERVIEW_IMG"=>$overview_img,
									//		"OVERVIEW_REAL_IMG"=>$overview_rnm,
											"REG_ADM"=>$_SESSION['s_adm_no']
										);		
		
		$new_story_no = insertStory($conn, $arr_data); 
		
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

				$arr_data = array("STORY_NO"=>$new_story_no,
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
  
	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&con_p_yyyy=".$con_p_yyyy."&con_p_mm=".$con_p_mm."&con_p_category=".$con_p_category."&search_field=".$search_field."&search_str=".$search_str;

	if ($new_story_no)  {

?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "story_list.php<?=$strParam?>";
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
	document.location = "story_list.php<?=$strParam?>";
}

function js_save() {
	
	var frm = document.frm;
	var story_no = "<?= $story_no ?>";
	
	if(document.frm.txt_story_nm.value==""){
		alert('제목을 입력해주세요.');
		document.frm.txt_story_nm.focus();
		return;
	}
	
	if ( frm.disp_val.value == "Y" ) {
		if(document.frm.txt_disp_seq.value==""){
			alert('순번을 입력해주세요.');
			document.frm.txt_disp_seq.focus();
			return;
		}
	}
		
	if (isNull(story_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.story_no.value = frm.story_no.value;
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
			add_tr = add_tr+"<th scope='row'>첨부파일 <img src='../images/btn_plus.gif' alt='파일추가'  style='cursor:pointer' class='btn_add_file' /> <img src='../images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_file' /></th>";
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


function js_disp_seq(gubun){
	var frm = document.frm;
	var disp_value = "";
	
	if ( frm.disp_val.value == "" ) {
		frm.disp_val.value = "Y";
	} else {
		frm.disp_val.value = gubun;
	}
	
	if ( gubun == "Y" ) {
		$("#seq").show();	     
	} else {
		$("#seq").hide();	     
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
<input type="hidden" name="story_no" value="<?=$story_no?>" />
<input type="hidden" name="disp_val" value="Y" />
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
								<th scope="row">스토리 타입</th>
								<td colspan="5">
									<span class="optionbox">
										<?= makeSelectBoxOnChange($conn, "STORY_TYPE" , "story_type", "", "", "", $rs_story_type); ?>
									</span>
								</td>  
							</tr>
							  
							<tr>
								<th scope="row">제목*</th>
								<td colspan="5">
									<span class="inpbox" style="width:70%;"><input type="text" class="txt" name="txt_story_nm" id="story_nm" value="<?=$rs_p_name02?>" /></span>
								</td>
							</tr>
							<tr> 
								<th scope="row">사용여부</th>
								<td colspan="5">
									<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 사용 안함
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
								</td>
							</tr>
							<tr> 
								<th scope="row">메인여부</th>
								<td colspan="5">
									<input type="radio" class="radio" name="rd_main_tf" value="Y" onclick="js_disp_seq('Y');" <? if (($rd_main_tf =="Y") || ($rd_main_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" class="radio" name="rd_main_tf" value="N" onclick="js_disp_seq('N');" <? if ($rd_main_tf =="N")echo "checked"; ?>> 사용 안함
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
								</td>
							</tr>
							<tr id="seq">
								<th scope="row">순번*</th> 
								<td colspan="5">
									<span class="inpbox" style="width:70%;"><input type="text" class="txt" name="txt_disp_seq" id="disp_seq" value="<?=$rs_p_name02?>" onblur="javascript:validation();" style="text-align:right" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"/></span>
								</td>
							</tr>							
							<tr>
								<th scope="row">본문*</th> 								
								<td colspan="5">
									<div style="float:left;display:inline;width:70%;">
										<textarea style="width:100%; height:205px" name="p_contents" id="p_contents" ><?=$rs_p_contents?></textarea>
									</div>
									<!--
									<div style="float:left;display:inline;width:70%;padding-left:10px">
										<b>줄 넘김는 'enter'를 사용 합니다.<br>
											예) SHINHAN<br>OPEN API<br>PLATFORM
										</b>
									</div>
									-->
								</td>
							</tr>
							<tr>
								<th scope="row">썸네일<br>(586*450)</th>
								<td colspan="5">
								<?
									if (strlen($rs_story_img) > 3) {
								?>
									<img src="/upload_data/story/<?=$rs_story_img?>" width="310" >
									<input type="file" size="40%" name="story_img">
									<input type="hidden" name="old_story_img" value="<?=$rs_story_img?>">
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
											<td colspan="3">
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
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
					<!--<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>-->
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
