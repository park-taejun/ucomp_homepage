<?session_start();?>
<?
# =============================================================================
# File Name    : pop_holiday.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2016-04-01
# Modify Date  : 
#	Copyright : Copyright @jinhak Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	
	$cp_no=$s_adm_com_code;
#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "SY003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/holiday/holiday.php";
	require "../../_classes/biz/admin/admin.php";

	$mode					= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$sch_date			= $_POST['sch_date']!=''?$_POST['sch_date']:$_GET['sch_date'];
	$is_holiday		= $_POST['is_holiday']!=''?$_POST['is_holiday']:$_GET['is_holiday'];
	$title				= $_POST['title']!=''?$_POST['title']:$_GET['title'];

	$mode				= trim($mode);
	$sch_date		= trim($sch_date);
	$is_holiday	= trim($is_holiday);

	$title			= SetStringToDB($title);

	if ($mode == "I") {

		$hit_cnt = 0;

		$result = insertHoliday($conn, $sch_date, $is_holiday, $title, $_SESSION["s_adm_no"]);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "휴일 관리 등록", "Insert");

?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script type="text/javascript">
	alert("저장 되었습니다.");
	opener.re_load();
	self.close();
</script>

<?
	}

	if ($mode == "U") {

		$result = updateSchedule($conn, $sch_cate, $sch_date, $title, $place, $from_hh, $from_mm, $to_hh, $to_mm, $contents, $addr, $lat, $lng, $temp01, $temp02, $temp03, $use_tf, $_SESSION["s_adm_no"], $seq_no);

		$result_log = insertUserLog($conn, "admin", $_SESSION["s_adm_id"], $_SERVER['REMOTE_ADDR'], " [".$title."]휴일 관리 수정", "Update");

?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script type="text/javascript">
	alert("수정 되었습니다.");
	opener.re_load();
	self.close();
</script>

<?
	}

	if ($mode == "D") {

		$result = deleteHoliday($conn, $sch_date);

		$result_log = insertUserLog($conn, "admin", $_SESSION["s_adm_id"], $_SERVER['REMOTE_ADDR'], " [".$sch_date."]일 휴일 관리 삭제", "Delete");

?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script type="text/javascript">
	alert("삭제 되었습니다.");
	opener.re_load();
	self.close();
</script>

<?
	}

	if ($sch_date <> "") {

		$arr_rs = selectHoliday($conn, $sch_date);

		$rs_h_date			= trim($arr_rs[0]["H_DATE"]); 
		$rs_is_holiday	= trim($arr_rs[0]["IS_HOLIDAY"]); 
		$rs_title				= trim($arr_rs[0]["TITLE"]); 
		$rs_reg_adm			= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date		= trim($arr_rs[0]["REG_DATE"]); 

	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<script type="text/javascript" src="../js/common.js"></script>
<style type="text/css">
<!--

	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }

select { font-size: 14px; font-family: Dotum; color: #555555; }

/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  overflow: auto; height: 160px; border-bottom:1px solid #efefef;}
-->
</style>
<script type="text/javascript" rc="../../kor/js/sample.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script language="javascript">

function isNull_pop(str) {
	str = str.replace(/\s/g, "");
	return ((str == null || str == "" || str == "<undefined>" || str == "undefined") ? true:false);
}

function js_save() {

	var frm = document.frm;

	frm.title.value		= frm.title.value.trim();


	if (!chk_value(frm.title, '내용을 입력해주세요.')) return;

	frm.mode.value = "I";

	if (frm.rd_is_holiday[0].checked == true) {
		frm.is_holiday.value = "Y";
	} else if(frm.rd_is_holiday[1].checked == true){
		frm.is_holiday.value = "N";
	} 

	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_delete() {

	var frm = document.frm;

	bDelOK = confirm('일정을 삭제 하시겠습니까?');
	
	if (bDelOK==true) {
		frm.mode.value = "D";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}
}

//우편번호 찾기
function js_post(zip, addr) {
	var url = "/_common/common_post.php?zip="+zip+"&addr="+addr;
	NewWindow(url, '우편번호찾기', '390', '370', 'NO');
}

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

	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	var contentString = document.getElementById("title").value + "<br />" + document.getElementById("place").value;

	var infowindow = new google.maps.InfoWindow({
		content: contentString,size: new google.maps.Size(100,100) 
	});

	var marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		title: document.getElementById("title").value
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}

</script>

</head>
<?
	if ($rs_addr <> "") {
?>
<body id="popup_code" onload="codeAddress();">
<?
	} else {
?>
<body id="popup_code">
<?
	}
?>
<form name="frm" method="post">
<input type="hidden" name="mode" value="" >
<input type="hidden" name="sch_date" value="<?= $sch_date ?>">

<div id="popupwrap_code">
	<h1>휴일 등록</h1>
	<div id="postsch">
		<h2>* 휴일을 등록하는 화면 입니다.</h2>
		<div class="addr_inp">
		<table cellpadding="0" cellspacing="0" width="95%">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
						<colgroup>
							<col width="20%">
							<col width="80%">
						</colgroup>
							<tr>
								<td class="lpd20 left">휴일구분</td>
								<td class="lpd20 right">
									<input type="radio" name="rd_is_holiday" value="Y" <? if (($rs_is_holiday == "") || ($rs_is_holiday == "Y")) echo "checked"; ?>> 휴일<span style="width:20px;"></span>
									<input type="radio" name="rd_is_holiday" value="N" <? if ($rs_is_holiday =="N") echo "checked"; ?>> 휴일아님<span style="width:20px;"></span>
									<input type="hidden" name="is_holiday" value="<?= $rs_is_holiday?>"> 
								</td>
							<tr>
							<tr>
								<td class="lpd20 left bu03">내용</td>
								<td colspan="3" class="lpd20 rpd20 right">
									<input type="text" name="title" id="title" value="<?= $rs_title ?>" style="width:95%;" required class="txt" style="ime-mode:active">
								</td>
							</tr>
					</table>
					</div>
				</td>
			</tr>
		</table>
		</div>
		<div class="sp30"></div>
		<div class="btn">
			<a href="javascript:js_save();"><img src="../images/admin/btn_regist_02.gif" alt="등록" /></a>
			<a href="javascript:js_delete();"><img src="../images/admin/btn_delete.gif" alt="삭제" /></a>
		</div>
		<div class="sp20"></div>

	</div>
	<div class="bot_close"><a href="javascript: window.close();"><img src="../images/admin/icon_pclose.gif" alt="닫기" /></a></div>
</div>
<script type="text/javascript" src="../js/wrest.js"></script>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>