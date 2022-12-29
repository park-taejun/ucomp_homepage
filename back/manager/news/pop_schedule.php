<?session_start();?>
<?
# =============================================================================
# File Name    : pop_schedule.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.02.20
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	
#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../../_common/config.php";
	require "../../../_classes/community/util/util.php";
	require "../../../_classes/community/etc/etc.php";
	require "../../../_classes/community/cschedule/schedule.php";

	$mode				= trim($mode);
	$sch_date		= trim($sch_date);

	if ($mode == "I") {

		$hit_cnt = 0;
		
		$result = insertCommSchedule($conn, $comm_no, $sch_cate, $sch_date, $title, $place, $from_hh, $from_mm, $to_hh, $to_mm, $contents, $addr, $lat, $lng, $temp01, $temp02, $temp03, $hit_cnt, $use_tf, $use_tf, $s_com_adm_no);

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

		$result = updateCommSchedule($conn, $comm_no, $sch_cate, $sch_date, $title, $place, $from_hh, $from_mm, $to_hh, $to_mm, $contents, $addr, $lat, $lng, $temp01, $temp02, $temp03, $use_tf, $s_com_adm_no, $seq_no);

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

		$result = deleteCommSchedule($conn, $s_com_adm_no, $seq_no);

?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script type="text/javascript">
	alert("삭제 되었습니다.");
	opener.re_load();
	self.close();
</script>

<?
	}

	if ($seq_no <> "") {
		$arr_rs = selectCommSchedule($conn, $seq_no);

		$rs_seq_no		= trim($arr_rs[0]["SEQ_NO"]); 
		$rs_sch_cate	= trim($arr_rs[0]["SCH_CATE"]); 
		$sch_date			= trim($arr_rs[0]["SCH_DATE"]); 
		$rs_title			= trim($arr_rs[0]["TITLE"]); 
		$rs_place			= trim($arr_rs[0]["PLACE"]); 
		$rs_from_hh		= trim($arr_rs[0]["FROM_HH"]); 
		$rs_from_mm		= trim($arr_rs[0]["FROM_MM"]); 
		$rs_to_hh			= trim($arr_rs[0]["TO_HH"]); 
		$rs_to_mm			= trim($arr_rs[0]["TO_MM"]); 
		$rs_contents	= trim($arr_rs[0]["CONTENTS"]); 
		$rs_addr			= trim($arr_rs[0]["ADDR"]); 
		$rs_str_lat		= trim($arr_rs[0]["STR_LAT"]); 
		$rs_str_lng		= trim($arr_rs[0]["STR_LNG"]); 
		$rs_temp01		= trim($arr_rs[0]["TEMP01"]); 
		$rs_temp02		= trim($arr_rs[0]["TEMP02"]); 
		$rs_temp03		= trim($arr_rs[0]["TEMP03"]); 
		$rs_hit_cnt		= trim($arr_rs[0]["HIT_CNT"]); 
		$rs_use_tf		= trim($arr_rs[0]["USE_TF"]); 

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
	var seq_no = "<?= $seq_no ?>";

	frm.title.value		= frm.title.value.trim();
	frm.place.value		= frm.place.value.trim();
	frm.from_hh.value = frm.from_hh.value.trim();
	frm.from_mm.value = frm.from_mm.value.trim();
	frm.to_hh.value		= frm.to_hh.value.trim();
	frm.to_mm.value		= frm.to_mm.value.trim();
	frm.addr.value		= frm.addr.value.trim();
	frm.lat.value			= frm.lat.value.trim();
	frm.lng.value			= frm.lng.value.trim();

	if (!chk_value(frm.title, '제목을 입력해주세요.')) return;
	if (!chk_value(frm.place, '장소를 입력해주세요.')) return;
	if (!chk_value(frm.from_mm, '분을 입력해 주세요. 정시는 00을 입력해주세요.')) return;
	if (!chk_value(frm.to_mm, '분을 입력해 주세요. 정시는 00을 입력해주세요.')) return;

	if (isNull(seq_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}
	/*
	if (frm.rd_sch_cate[0].checked == true) {
		frm.sch_cate.value = "0";
	}else if(frm.rd_sch_cate[1].checked == true){
		frm.sch_cate.value = "1";
	} else {
		frm.sch_cate.value = "2";
	}
	*/

	if (frm.rd_use_tf[0].checked == true) {
		frm.use_tf.value = "Y";
	} else {
		frm.use_tf.value = "N";
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
<input type="hidden" name="menu_cd" value="<?= $menu_cd ?>">
<input type="hidden" name="seq_no" value="<?= $seq_no ?>">
<input type="hidden" name="sch_date" value="<?= $sch_date ?>">

<div id="popupwrap_code">
	<h1>주요일정 등록</h1>
	<div id="postsch">
		<h2>* 주요일정을 등록하는 화면 입니다.</h2>
		<div class="addr_inp">
		<table cellpadding="0" cellspacing="0" width="95%">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
						<colgroup>
							<col width="20%">
							<col width="80%">
						</colgroup>
							<!--
							<tr>
								<td class="lpd20 left">일정구분</td>
								<td class="lpd20 right">
									<input type="radio" name="rd_sch_cate" value="0" <? if ($rs_sch_cate =="0") echo "checked"; ?>> 중앙당 일정<span style="width:20px;"></span>
									<input type="radio" name="rd_sch_cate" value="1" <? if ($rs_sch_cate =="1") echo "checked"; ?>> 당대표 일정<span style="width:20px;"></span>
									<input type="radio" name="rd_sch_cate" value="2" <? if ($rs_sch_cate =="2") echo "checked"; ?>> 공통 일정
									<input type="hidden" name="sch_cate" value="<?= $rs_sch_cate?>"> 
								</td>
							<tr>
							-->
							<input type="hidden" name="sch_cate" value="0"> 
							<tr>
								<td class="lpd20 left bu03">제목</td>
								<td colspan="3" class="lpd20 rpd20 right">
									<input type="text" name="title" id="title" value="<?= $rs_title ?>" style="width:95%;" required class="txt">
								</td>
							</tr>
							<tr>
								<td class="lpd20 left bu03">장소</td>
								<td colspan="3" class="lpd20 rpd20 right">
									<input type="Text" name="place" id="place" value="<?= $rs_place ?>" style="width:95%;" required class="txt">
								</td>
							</tr>

							<tr>
								<td class="lpd20 left">시간</td>
								<td colspan="3" class="lpd20 rpd20 right">
									<?= makeSelectBox($conn,"STR_HOUR","from_hh","50","","",$rs_from_hh);?> 시
									<input type="text" name="from_mm" value="<?= $rs_from_mm ?>" style="width:30px;" maxlength="2"> 분
									-
									<?= makeSelectBox($conn,"STR_HOUR","to_hh","50","","",$rs_to_hh);?> 시
									<input type="text" name="to_mm" value="<?= $rs_to_mm ?>" style="width:30px;" maxlength="2"> 분
								</td>
							</tr>

							<tr>
								<td class="lpd20 left">내용</td>
								<td colspan="3" class="lpd20 rpd20 right">
									<textarea name="contents" id="memo" class="box01" style="height:125px; width:95%;"><?=$rs_contents?></textarea>
								</td>
							</tr>

							<tr>
								<td class="lpd20 left bu03">주소</td>
								<td colspan="3" class="lpd20 rpd20 right">
									<input type="hidden" name="zip" value="">
									<input type="text" name="addr" id="address" value="<?= $rs_addr ?>" style="width:61%;" class="txt">
									<input type="hidden" name="lat" id="lat" value="" style="width:20px">
									<input type="hidden" name="lng" id="lng" value="" style="width:20px">
									<a href="javascript:js_post('zip','addr');"><img src="/manager/images/admin/btn_filesch.gif" alt="찾기" align="absmiddle" /></a>
									<a href="javascript:codeAddress();"><img src="/manager/images/admin/btn_map.gif" alt="지도보기" align="absmiddle" /></a>
								</td>
							</tr>

							<tr>
								<td class="lpd20 left bu03">지도</td>
								<td colspan="3" class="lpd20 rpd20 right">
									<div id="map_canvas" style="width: 95%; height: 250px; border:solid 1px #DEDEDE;"></div>
								</td>
							</tr>

							<tr>
								<td class="lpd20 left">사용여부</td>
								<td class="lpd20 right">
									<input type="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 사용<span style="width:20px;"></span>
									<input type="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?>> 미사용
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
								</td>
							<tr>
					</table>
					</div>
				</td>
			</tr>
		</table>
		</div>

		<div class="btn">
			<a href="javascript:js_save();"><img src="../images/admin/btn_regist_02.gif" alt="등록" /></a>
			<a href="javascript:js_delete();"><img src="../images/admin/btn_delete.gif" alt="삭제" /></a>
		</div>
		<div class="sp30"></div>

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