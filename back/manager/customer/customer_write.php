<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : customer_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-07-16
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
	$writer_id = $s_adm_id;//작성자 아이디:로그인한 사용자 아이디
	$bb_code = trim($bb_code);

	//echo $bb_code;

	if ($bb_code == "")$bb_code = "CU002";

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	$menu_right = $bb_code; // 메뉴마다 셋팅 해 주어야 합니다
	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/category/category.php";
	require "../../_classes/biz/customer/customer.php";

	$config_no = "";

	$arr_bb_code = explode("_", $bb_code);
	
	for ($i = 0; $i < sizeof($arr_bb_code) ; $i++) {
		$config_no = $arr_bb_code[$i];
	}
	

	$sPageRight_M ="N";
	$ref_ip = $_SERVER['REMOTE_ADDR'];
	
	$thumb_width = "212";
	$thumb_height = "131";


#====================================================================
# DML Process
#====================================================================

	if ($mode == "I") {

		$contents = SetStringToDB($contents);
		
		$new_bb_no =  insertCustomer($conn, $bb_code, $cate_01, $cate_02, $cate_03, $cate_04, $category, $company_nm, $homepage, $hosting, $ftp_addr, $ftp_port, $ftp_id, $ftp_pw, $db_addr, $db_nm, $db_id, $db_pw, $admin_addr, $admin_id, $admin_pw, $contents, $use_tf);


		if ($new_bb_no) {
			$result = true;
		}

	}


	if ($mode == "U") {
		$contents = SetStringToDB($contents);
		$result = updateCustomer($conn, $cate_01, $cate_02, $cate_03, $cate_04, $category, $company_nm, $homepage, $hosting, $ftp_addr, $ftp_port, $ftp_id, $ftp_pw, $db_addr, $db_nm, $db_id, $db_pw, $admin_addr, $admin_id, $admin_pw, $contents, $use_tf, $s_adm_no, $bb_code, $bb_no);
	}


	if ($mode == "T") {

		updateBannerUseTF($conn, $use_tf, $s_adm_no, $bb_code, $bb_no);

	}

	if ($mode == "D") {
		
		
	//	$row_cnt = count($chk);
		
	//	for ($k = 0; $k < $row_cnt; $k++) {
		
	//		$tmp_banner_no = $chk[$k];

			$result = deleteCustomer($conn, $s_adm_no, $bb_code, $bb_no);
		
//		}
	}

	if ($mode == "S") {

		$arr_rs = selectCustomer($conn, $bb_code, $bb_no);
		
		$rs_bb_no					= trim($arr_rs[0]["BB_NO"]); 
		$rs_bb_code					= trim($arr_rs[0]["BB_CODE"]); 
		$rs_company_nm			= trim($arr_rs[0]["COMPANY_NM"]); 
		$rs_homepage				= trim($arr_rs[0]["HOMEPAGE"]); 
		$rs_contents					= SetStringFromDB($arr_rs[0]["CONTENTS"]);
		$rs_cate_02					= trim($arr_rs[0]["CATE_02"]); 
		$rs_hosting					= trim($arr_rs[0]["HOSTING"]); 
		$rs_ftp_addr				= trim($arr_rs[0]["FTP_ADDR"]); 
		$rs_ftp_port				= trim($arr_rs[0]["FTP_PORT"]); 
		$rs_ftp_id					= trim($arr_rs[0]["FTP_ID"]); 
		$rs_ftp_pw					= trim($arr_rs[0]["FTP_PW"]); 
		$rs_db_addr					= trim($arr_rs[0]["DB_ADDR"]); 
		$rs_db_nm					= trim($arr_rs[0]["DB_NM"]); 
		$rs_db_id						= trim($arr_rs[0]["DB_ID"]); 
		$rs_db_pw					= trim($arr_rs[0]["DB_PW"]); 
		$rs_admin_addr			= trim($arr_rs[0]["ADMIN_ADDR"]); 
		$rs_admin_id				= trim($arr_rs[0]["ADMIN_ID"]); 
		$rs_admin_pw				= trim($arr_rs[0]["ADMIN_PW"]); 
		$rs_category				= trim($arr_rs[0]["CATEGORY"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]); 

		$content  = $rs_contents;

	} else {
		$rs_writer_nm = $s_adm_nm;
		$rs_email			= $s_adm_email;
		$rs_writer_pw = $s_adm_no;
	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&bb_code=".$bb_code."&con_cate_01=".$con_cate_01."&con_cate_02=".$con_cate_02."&con_cate_03=".$con_cate_03."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "customer_list.php<?=$strParam?>";
</script>
</head>
</html>
<?
		exit;
	}	


	$arr_girin_mem_rs = listGirinMember($conn);

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript" src="../../../_common/SE2.1.1.8141/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script language="javascript" type="text/javascript">
<!--

$(document).ready(function() {
	$('#example-getting-started').multiselect();
});

function codeAddress() {
	var geocoder = new google.maps.Geocoder();
	var address = document.getElementById("address").value;
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			//alert(results[0].geometry.location.lat());
			//alert(results[0].geometry.location.lng());
			document.getElementById("lat").value = results[0].geometry.location.lat();
			document.getElementById("lng").value = results[0].geometry.location.lng();
			initialize();
		} else {
			alert("정확한 주소를 입력해 주세요.");
		}
	});
}

function initialize() {

	var lat_val = document.getElementById("lat").value;
	var lng_val = document.getElementById("lng").value;

	var myLatlng = new google.maps.LatLng(lat_val,lng_val);
	var myOptions = {
		zoom: 16,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	
	var icon_img="/manager/images/bu/icon_01.png";
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	var contentString = document.getElementById("title").value + "<br />" + document.getElementById("address").value;

	var infowindow = new google.maps.InfoWindow({
		content: contentString,size: new google.maps.Size(100,100) 
	});

	var marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		icon:icon_img,
		title: document.getElementById("title").value
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}

function js_list() {
	document.location = "customer_list.php<?=$strParam?>";
}

function unitlist(unit){
		var text = $('#example-getting-started').val();
		var unittext="";
		for(var i=0; i<text.length; i++){
			if(unittext!=""){
				unittext=unittext+"||";
			}
			unittext=unittext+text[i];
		}
		document.getElementById("unitss").value=unittext;
	
	}

function js_save() {

	var frm = document.frm;
	var bb_no = "<?= $bb_no ?>";
	
	if(document.frm.company_nm.value==""){
		alert('업체명을 입력해주세요.');
		document.frm.company_nm.focus();
		return;
	}

	if (isNull(bb_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.bb_no.value = frm.bb_no.value;
	}

<? if ($mode == "R") {?>
		frm.mode.value = "IR";
<? }?>

	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);   // 에디터의 내용이 textarea에 적용된다.

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
function js_exfileView(idx,cnt) {

	if(cnt < 2){
		var obj = document.frm["file_flag[]"];
	}else{
		var obj = document.frm["file_flag[]"][idx];
	}
	
	if(cnt > 1){

		if (obj.selectedIndex == 2) {
			document.frm["file_name[]"][idx].style.visibility = "visible"; 
		} else { 
			document.frm["file_name[]"][idx].style.visibility = "hidden"; 
		}	

	}else{
		if (obj.selectedIndex == 2) {
			document.frm["file_name[]"].style.visibility = "visible"; 
		} else { 
			document.frm["file_name[]"].style.visibility = "hidden"; 
		}	
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


function getNextValue(dep) {
	
	var cate_01 = "";

	if (document.frm.cate_01 != null) cate_01 = document.frm.cate_01.value;

	$.get("../../../_common/ajax_next_cate.php", 
		{ cate_01:cate_01 }, 
		function(data){

			var arr_str = data.split("");
			var arr_str_sub = "";

			for (i=0 ; i < (arr_str.length -1) ; i++) {
				arr_str_sub = arr_str[i].split("");
				add_cate_select(arr_str_sub[0], arr_str_sub[1], arr_str_sub[2], (i+1));
			}
		}
	);
}


function add_cate_select(depth, value, text, index){
	
	var obj = "";

	obj = eval("document.frm.cate_02");

	if (obj != null) {
		obj.options[index] = new Option(text, value);
	}
}

function js_cate_01() {
	//var frm = document.frm;

	var obj = "cate_02";
	obj = eval("document.frm."+obj);
	

	if (obj != null) {
		clear_select(obj);
	}
	
	if (frm.cate_01.value != "") {
		getNextValue();
	}
}

<?if (sizeof($arr_rs_link) > 0) {?>
var _growCnt = <?=sizeof($arr_rs_link)?>;
<?}else{?>
var _growCnt = 1;
<?}?>

function isIE() {
	var name = navigator.appName;
	if (name == "Microsoft Internet Explorer")
		return true;
	else
		return false;
}

function addRow(tbodyId) {

	var goption = new Array();
	var toggle = true;
	var _tbody = document.getElementById(tbodyId);
	var l = 0;
	var dsel = "";

	
	var rowId = _growCnt++;
	
	_row = document.createElement("TR");
	var rowId_1 = "row_" + rowId;

	if (isIE()) {
		_row.id = rowId_1;
	} else {
		_row.setAttribute("id", rowId_1);
	}

	_tbody.appendChild(_row); 
	_cell = document.createElement("TD");
	_cell.innerHTML = "&nbsp;";
	_row.appendChild(_cell);

	_cell = document.createElement("TD");
	_cell.colSpan=3;
	_cell.innerHTML = "<select name=\"l_type[]\"><option value=\"I\">ICF</option><option value=\"P\">파노라믹뷰</option></select>&nbsp;<input type=\"text\" name=\"subject[]\" style=\"width:200px;\" />&nbsp;<input type=\"text\" name=\"link[]\" style=\"width:300px;\" />";
	_row.appendChild(_cell);

	document.frm.link_cnt.value=rowId;

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

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="bb_no" value="<?=$bb_no?>" />
<input type="hidden" name="bb_code" value="<?=$bb_code?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
<input type="hidden" name="bb_po" value="<?=$rs_bb_po?>">
<input type="hidden" name="bb_de" value="<?=$rs_bb_de?>">
<input type="hidden" name="bb_re" value="<?=$rs_bb_re?>">
<input type="hidden" name="use_tf" value="Y"> 
<input type="hidden" name="cate_02" value="<?=$rs_cate_02?>" id="unitss"> 

<input type="hidden" name="search_field" value="<?=$_REQUEST['search_field']?>">
<input type="hidden" name="search_str" value="<?=$_REQUEST['search_str']?>">

				<div class="boardwrite">

					<table>

						<colgroup>
							<col style="width:12%">
							<col style="width:38%">
							<col style="width:12%">
							<col style="width:38%">
						</colgroup>

						<tr>
							<th>카테고리</th>
							<td colspan="3">
								<span class="optionbox" style="width:125px">
									<?= makeSelectBoxWithCondition($conn, "CUSTOMER_CATE","category","","선택","",$rs_category, $condition);?>
								</span>
							</td>
						</tr>
						<tr>
							<th>업체명</th>
							<td colspan="3">
								<span class="inpbox"><input type="text" class="txt" name="company_nm" value="<?=$rs_company_nm?>" /></span>
							</td>
						</tr>
						<tr>
							<th>홈페이지 주소</th>
							<td colspan="3">
								<span class="inpbox" style="width:60%"><input type="text" class="txt" name="homepage" value="<?=$rs_homepage?>" id="homepage"/></span>
								<b>* HTTP:// 제외하고 입력해주세요.</b>
							</td>
						</tr>
						<tr>
							<th>호스팅</th>
							<td colspan="3">
								<span class="inpbox"><input type="text" class="txt" name="hosting" value="<?=$rs_hosting?>" /></span>
							</td>
						</tr>
						<tr>
							<th>FTP 주소</th>
							<td width="300">
								<span class="inpbox"><input type="text" class="txt" name="ftp_addr" value="<?=$rs_ftp_addr?>" /></span>
							</td>
							<th>FTP 포트</th>
							<td>
								<span class="inpbox"><input type="text" class="txt" name="ftp_port" value="<?=$rs_ftp_port?>" /></span>
							</td>
						</tr>
						<tr>
							<th>FTP ID</th>
							<td width="300">
								<span class="inpbox"><input type="text" class="txt" name="ftp_id" value="<?=$rs_ftp_id?>" /></span>
							</td>
							<th>FTP 비밀번호</th>
							<td>
								<span class="inpbox"><input type="text" class="txt" name="ftp_pw" value="<?=$rs_ftp_pw?>" /></span>
							</td>
						</tr>
						<tr>
							<th>DB 주소</th>
							<td width="300">
								<span class="inpbox"><input type="text" class="txt" name="db_addr" value="<?=$rs_db_addr?>" /></span>
							</td>
							<th>DB 명</th>
							<td>
								<span class="inpbox"><input type="text" class="txt" name="db_nm" value="<?=$rs_db_nm?>" /></span>
							</td>
						</tr>
						<tr>
							<th>DB ID</th>
							<td width="300">
								<span class="inpbox"><input type="text" class="txt" name="db_id" value="<?=$rs_db_id?>" /></span>
							</td>
							<th>DB 비밀번호</th>
							<td>
								<span class="inpbox"><input type="text" class="txt" name="db_pw" value="<?=$rs_db_pw?>" /></span>
							</td>
						</tr>
						<tr>
							<th>관리자주소</th>
							<td colspan="3">
								<span class="inpbox"><input type="text" class="txt" name="admin_addr" value="<?=$rs_admin_addr?>" /></span>
							</td>
						</tr>
						<tr>
							<th>관리자 ID</th>
							<td width="300">
								<span class="inpbox"><input type="text" class="txt" name="admin_id" value="<?=$rs_admin_id?>" /></span>
							</td>
							<th>관리자 비밀번호</th>
							<td>
								<span class="inpbox"><input type="text" class="txt" name="admin_pw" value="<?=$rs_admin_pw?>" /></span>
							</td>
						</tr>
						<tr> 
							<th>내용</th>
							<td colspan="3" style="padding: 10px 10px 10px 15px">
							<?
								// ================================================================== 수정 부분								
							?>
								 <span class="fl" style="padding-left:0px;width:90%;height:300px;"><textarea name="contents" id="contents"  style="padding-left:0px;width:100%;height:200px;"><?=$rs_contents?></textarea></span>
							<?
								// ================================================================== 수정 부분
							?>
							</td>
						</tr>
						
					</table>
				</div>

				<div class="btnright">
				<? if ($adm_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<? } ?>
				<? } else {?>
					<? if ($sPageRight_I == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_save();" style="width:100px">확인</button>
					<? } ?>
				<? }?>

				<? if ($s_adm_cp_type == "운영") { ?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				<? } ?>
				
				<? if ($s_adm_cp_type == "운영") { ?>
				<? if ($adm_no <> "") {?>
					<? if ($sPageRight_D == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_delete();" style="width:100px">삭제</button>
					<? } ?>
				<? } ?>
				<? } ?>
				</div>

			</div>
		</div>
	</div>

<script type="text/javascript" src="../js/wrest.js"></script>
</form>
<SCRIPT LANGUAGE="JavaScript">
<!--

var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "contents",
	sSkinURI: "../../_common/SE2.1.1.8141/SmartEditor2Skin.html",
	htParams : {bUseToolbar : true, 
	fOnBeforeUnload : function(){ 
		// alert('야') 
	}
	}, 
	fCreator: "createSEditor2"
});

//-->
</SCRIPT>
	<!-- S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>
<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
