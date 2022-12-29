<?session_start();?>
<?
# ===================================================================
# File Name    : office_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.04.27
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# ===================================================================

# ===================================================================
	$s_adm_no = $_SESSION['s_adm_no'];
# ===================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#====================================================================
# common_header Check Session
#====================================================================
	$menu_right = "PE004"; // 메뉴마다 셋팅 해 주어야 합니다
	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/office/office.php";

#====================================================================
# DML Process
#====================================================================
	//$type = "contact";

	if ($mode == "I") {

		$result =  insertOffice($conn, $name, $type, $tel01, $tel02, $fax01, $fax02, $email, $post, $address, $str_lat, $str_lng, $ex_info01, $ex_info02, $ex_info03, $use_tf, $s_adm_no);

	}


	if ($mode == "U") {

		$result = updateOffice($conn, $name, $type, $tel01, $tel02, $fax01, $fax02, $email, $post, $address, $str_lat, $str_lng, $ex_info01, $ex_info02, $ex_info03, $use_tf, $s_adm_no, $seq_no);

	}


	if ($mode == "D") {
		$result = deleteOffice($conn, $s_adm_no, $seq_no);
	}

	if ($mode == "S") {

		$arr_rs = selectOffice($conn, $seq_no);

		$rs_seq_no					= trim($arr_rs[0]["SEQ_NO"]); 
		$rs_name						= trim($arr_rs[0]["NAME"]); 
		$rs_type						= trim($arr_rs[0]["TYPE"]); 
		$rs_tel01						= trim($arr_rs[0]["TEL01"]); 
		$rs_tel02						= trim($arr_rs[0]["TEL02"]); 
		$rs_fax01						= trim($arr_rs[0]["FAX01"]); 
		$rs_fax02						= trim($arr_rs[0]["FAX02"]); 
		$rs_email						= trim($arr_rs[0]["EMAIL"]); 
		$rs_post						= trim($arr_rs[0]["POST"]); 
		$rs_address					= trim($arr_rs[0]["ADDRESS"]); 
		$rs_str_lat					= trim($arr_rs[0]["STR_LAT"]); 
		$rs_str_lng					= trim($arr_rs[0]["STR_LNG"]); 
		$rs_ex_info01				= trim($arr_rs[0]["EX_INFO01"]); 
		$rs_ex_info02				= trim($arr_rs[0]["EX_INFO02"]); 
		$rs_ex_info03				= trim($arr_rs[0]["EX_INFO03"]); 
		
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm					= trim($arr_rs[0]["REG_ADM"]); 
	}

	$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

	if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "contact_list.php<?=$strParam?>";
</script>
</head>
</html>
<?
		exit;
	}	
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />

<!--[if IE]>
<script>
document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("section");
document.createElement("figure");
document.createElement("figcaption");
document.createElement("legend");
document.createElement("time");
</script>
<![endif]-->
<!--[if IE]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/goods_common.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>

<script language="javascript" type="text/javascript">
<!--

function js_area_cd_01() {
	
	//alert("s");
	var frm = document.frm;

	var area_01 = frm.area_cd_01.value;
	var area_02 = frm.area_cd_02.value;

	//alert(area_02);
	
	$.get("get_next_area.php",
		{ area_01:area_01, area_02:area_02}, 
		function(data){ 
			//alert(data);
			$("#next_area").html(data); 
		}
	);
}


function js_list() {
	document.location = "contact_list.php<?=$strParam?>";
}

function js_save() {

	var frm = document.frm;
	var seq_no = "<?=$seq_no ?>";
	
	if (frm.type.value =="") {
		alert("지역을 선택 하세요");
		frm.type.focus();
		return;
	}

	if (frm.name.value =="") {
		alert("지부명을 입력 하세요");
		frm.name.focus();
		return;
	}

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (isNull(seq_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.seq_no.value = frm.seq_no.value;
	}


	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_view(rn, seq) {

	var frm = document.frm;
		
	frm.seq_no.value = seq;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "get";
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

//	}
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

	var contentString = document.getElementById("name").value + "<br />" + document.getElementById("address").value;

	var infowindow = new google.maps.InfoWindow({
		content: contentString,size: new google.maps.Size(100,100) 
	});

	var marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		title: document.getElementById("name").value
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});


}

//-->
</script>

</head>

<?
	if (($rs_str_lat != "") && ($rs_str_lng != "")) {
?>
<body onLoad="initialize();js_area_cd_01();">
<?
	} else {
?>
<body>
<?
	}
?>

<div class="wrapper">
<section id="container">	

<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";

?>

	<section class="conRight">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../../_common/top_area.php";
?>
		<div class="conTit">
			<h2><?=$p_parent_menu_name?></h2>
		</div>
		<section class="conBox">


<form name="frm" method="post">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="area_cd_02" value="<?=$rs_area?>" />

			<h3 class="conTitle"><?=$p_menu_name?></h3>
			<div class="sp10"></div>

			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">

				<colgroup>
					<col width="120" />
					<col width="*" />
					<col width="120" />
					<col width="*" />
				</colgroup>
				<tr>
					<th>지역</th>
					<td colspan="3" class="line">
						<?
							//$rs_area
						?>
						<?=makeSelectBox($conn,"BOARD_GROUP","type","128","지역을 선택하세요.","", $rs_type)?>&nbsp;
						<span id="next_area">
						</span>
					</td>
				</tr>
				<tr>
					<th>지부명</th>
					<td colspan="3" class="line">
						<input type="text" class="txt" id="name" name="name" value="<?=$rs_name?>" style="width: 20%;" />
					</td>
				</tr>
				<tr>
					<th>연락처</th>
					<td colspan="3" class="line">
						<input type="text" class="txt" name="tel01" value="<?=$rs_tel01?>" style="width: 15%;" /> 예) 02-0000-1234
					</td>
				</tr>
				<tr>
					<th>FAX</th>
					<td colspan="3" class="line">
						<input type="text" class="txt" name="fax01" value="<?=$rs_fax01?>" style="width: 15%;" /> 예) 02-0000-1234
					</td>
				</tr>
				<!--
				<tr>
					<th>타입</th>
					<td colspan="3" class="line">
						<input type="text" class="txt" name="ex_info01" value="<?=$rs_ex_info01?>" style="width: 15%;" />
					</td>
				</tr>
				<tr>
					<th>대표자</th>
					<td colspan="3" class="line">
						<input type="text" class="txt" name="ex_info02" value="<?=$rs_ex_info02?>" style="width: 15%;" />
					</td>
				</tr>
				-->
				
				<tr>
					<th>당담자</th>
					<td colspan="3" class="line">
						<input type="text" class="txt" name="ex_info03" value="<?=$rs_ex_info03?>" style="width: 15%;" />
					</td>
				</tr>
				<tr>
					<th>우편번호</th>
					<td colspan="3" class="line">
						<input type="text" class="txt" name="post" value="<?=$rs_post?>" style="width: 15%;" />
					</td>
				</tr>
				<tr>
					<th>주소</th>
					<td colspan="3" class="line">
						<input type="text" name="address" id="address" value="<?= $rs_address ?>" style="width:61%;" class="txt">
						<!--<a href="javascript:js_post('post','address');"><img src="/manager/images/admin/btn_filesch.gif" alt="찾기" align="absmiddle" /></a>-->
						<a href="javascript:codeAddress();"><img src="../images/admin/btn_map.gif" alt="지도보기" align="absmiddle" /></a>
					</td>
				</tr>
				
				<tr>
					<th>위도</th>
					<td colspan="3" class="line">
						<input type="text" name="str_lat" id="lat" value="<?= $rs_str_lat ?>" style="width:120px" class="txt">
					</td>
				</tr>
				<tr>
					<th>경도</th>
					<td colspan="3" class="line">
						<input type="text" name="str_lng" id="lng" value="<?= $rs_str_lng ?>" style="width:120px" class="txt">
					</td>
				</tr>

				<tr>
					<th>지도</th>
					<td colspan="3" class="line">
						<div id="map_canvas" style="width: 95%; height: 250px; border:solid 1px #DEDEDE;"></div>
					</td>
				</tr>

				<tr class="end"> 
					<th>노출여부</th>
					<td colspan="3" class="choices">
						<input type="radio" class="radio" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?>> 보이기<span style="width:20px;"></span>
						<input type="radio" class="radio" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N")echo "checked"; ?>> 보이지않기 
						<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>"> 
					</td>
				</tr>
			</table>


			<div class="btnArea">
				<ul class="fRight">
				<? if ((int)$adm_no <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<? } ?>
				<? } else {?>
					<? if ($sPageRight_I == "Y") {?>
					<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
					<? } ?>
				<? }?>

					<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>

				<? if ((int)$adm_no <> "") {?>
					<? if ($sPageRight_D == "Y") {?>
					<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<? } ?>
				<? } ?>
				</ul>
			</div>
		</section>
		<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>