<?session_start();?>
<?
# =============================================================================
# File Name    : comm_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.05.21
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
	require "../../../_classes/community/ccommunity/community.php";
	

	if ($mode == "") $mode			= "S";
#====================================================================
# Request Parameter
#====================================================================

$c_level = Trim($c_level);
$c_seq01 = Trim($c_seq01);
$c_seq02 = Trim($c_seq02);

//echo $c_level."<br>";
//echo $c_seq01."<br>";
//echo $c_seq02."<br>";

#====================================================================
# Declare variables
#====================================================================

#====================================================================
# Request Parameter
#====================================================================

$comm_no					= trim($comm_no);
$c_level					= trim($c_level);
$c_seq01					= trim($c_seq01);
$c_seq02					= trim($c_seq02);
$comm_cd					= trim($comm_cd);
$comm_name				= trim($comm_name);
$comm_domain			= trim($comm_domain);
$comm_type				= trim($comm_type);
$comm_addr				= trim($comm_addr);
$comm_phone				= trim($comm_phone);
$comm_fax					= trim($comm_fax);
$comm_email				= trim($comm_email);
$comm_flag				= trim($comm_flag);
$comm_title_img		= trim($comm_title_img);
$comm_footer_img	= trim($comm_footer_img);
$comm_img					= trim($comm_img);
$comm_img_over		= trim($comm_img_over);

//echo $m_level;

$result = false;
#====================================================================
# DML Process
#====================================================================
	
#====================================================================
	$savedir1 = $g_physical_path."upload_data/community";
#====================================================================
	
	if ($mode == "I") {
		
		$comm_title_img		= upload($_FILES[comm_title_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$comm_footer_img	= upload($_FILES[comm_footer_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$comm_img					= upload($_FILES[comm_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		$comm_img_over		= upload($_FILES[comm_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		
		//$file_rnm					= $_FILES[menu_img][name];

		$new_comm_no = insertCcommunity($conn, $c_level, $c_seq01, $c_seq02, $comm_name, $comm_domain, $comm_type, $comm_addr, $comm_phone, $comm_fax, $comm_email, $comm_flag, $comm_title_img, $comm_footer_img, $comm_img, $comm_img_over, $use_tf, $s_comm_adm_no);

		$result = make_branch_file($new_comm_no, $comm_domain, $comm_name);

	}


	if ($mode == "S") {

		$arr_rs = selectCcommunity($conn, $comm_no);

		$rs_comm_no					= trim($arr_rs[0]["COMM_NO"]); 
		$rs_comm_cd					= trim($arr_rs[0]["COMM_CD"]); 
		$rs_comm_domain			= trim($arr_rs[0]["COMM_DOMAIN"]); 
		$rs_comm_type				= trim($arr_rs[0]["COMM_TYPE"]); 
		$rs_comm_name				= trim($arr_rs[0]["COMM_NAME"]); 
		$rs_comm_addr				= trim($arr_rs[0]["COMM_ADDR"]); 
		$rs_comm_phone			= trim($arr_rs[0]["COMM_PHONE"]); 
		$rs_comm_fax				= trim($arr_rs[0]["COMM_FAX"]); 
		$rs_comm_email			= trim($arr_rs[0]["COMM_EMAIL"]); 
		$rs_comm_seq01			= trim($arr_rs[0]["COMM_SEQ01"]); 
		$rs_comm_seq02			= trim($arr_rs[0]["COMM_SEQ02"]); 
		$rs_comm_seq03			= trim($arr_rs[0]["COMM_SEQ03"]); 
		$rs_comm_flag				= trim($arr_rs[0]["COMM_FLAG"]); 
		$rs_comm_title_img	= trim($arr_rs[0]["COMM_TITLE_IMG"]); 
		$rs_comm_footer_img	= trim($arr_rs[0]["COMM_FOOTER_IMG"]); 
		$rs_comm_img				= trim($arr_rs[0]["COMM_IMG"]); 
		$rs_comm_img_over		= trim($arr_rs[0]["COMM_IMG_OVER"]); 
		$rs_intro_tf				= trim($arr_rs[0]["INTRO_TF"]);
		$rs_str_lat					= trim($arr_rs[0]["STR_LAT"]);
		$rs_str_lng					= trim($arr_rs[0]["STR_LNG"]);
		$rs_tweeter					= trim($arr_rs[0]["TWEETER"]);
		$rs_facebook				= trim($arr_rs[0]["FACEBOOK"]);
		$rs_meto						= trim($arr_rs[0]["METO"]);
		$rs_yozm						= trim($arr_rs[0]["YOZM"]);
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 
		$rs_area_name				= trim($arr_rs[0]["AREA_NAME"]); 
		$rs_area_code				= trim($arr_rs[0]["AREA_CODE"]); 

	}

	if ($mode == "U") {

		switch ($flag01) {
			case "insert" :
				$comm_title_img	= upload($_FILES[comm_title_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$comm_title_img	= $old_comm_title_img;
			break;
			case "delete" :
				$comm_title_img	= "";
			break;
			case "update" :
				$comm_title_img	= upload($_FILES[comm_title_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag02) {
			case "insert" :
				$comm_footer_img	= upload($_FILES[comm_footer_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$comm_footer_img	= $old_comm_footer_img;
			break;
			case "delete" :
				$comm_footer_img	= "";
			break;
			case "update" :
				$comm_footer_img	= upload($_FILES[comm_footer_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag03) {
			case "insert" :
				$comm_img	= upload($_FILES[comm_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$comm_img	= $old_comm_img;
			break;
			case "delete" :
				$comm_img	= "";
			break;
			case "update" :
				$comm_img	= upload($_FILES[comm_img], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		switch ($flag04) {
			case "insert" :
				$comm_img_over	= upload($_FILES[comm_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
			case "keep" :
				$comm_img_over	= $old_comm_img_over;
			break;
			case "delete" :
				$comm_img_over	= "";
			break;
			case "update" :
				$comm_img_over	= upload($_FILES[comm_img_over], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			break;
		}

		$result = updateCcommunity($conn, $comm_domain, $comm_type, $comm_name, $comm_addr, $comm_phone, $comm_fax, $comm_email, $comm_flag, $comm_title_img, $comm_footer_img, $comm_img, $comm_img_over, $area_name, $area_code, $use_tf, $s_comm_adm_no, $comm_no);
		
		$result = updateCcommunityAddInfo($conn, $intro_tf, $str_lat, $str_lng, $tweeter, $facebook, $meto, $yozm, $comm_no);

		$result = make_branch_file($comm_no, $comm_domain, $comm_name);

	}

	if ($mode == "D") {
		$result = deleteCcommunity($conn, $s_comm_adm_no, $comm_no);
	}


#=================================================================
# Get Result set from stored procedure
#=================================================================
	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&menu_cd=".$menu_cd;
?>	
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
	alert('정상 처리 되었습니다.');
	document.location.href = "comm_write.php<?=$strParam?>";
</script>
<?
		exit;
	}	
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_comm_name?> 관리자 로그인</title>
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

<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script language="javascript">
	function js_save() {
		
		var comm_no = "<?= $comm_no ?>";
		var frm = document.frm;

		if (isNull(comm_no)) {
			frm.mode.value = "I";
		} else {
			frm.mode.value = "U";
		}

		if (document.frm.rd_intro_tf == null) {
			//alert(document.frm.rd_use_tf);
		} else {
			if (frm.rd_intro_tf[0].checked == true) {
				frm.intro_tf.value = "Y";
			} else {
				frm.intro_tf.value = "N";
			}
		}
		//alert(frm.mode.value);

		frm.submit();
	}

	function js_fileView(obj,idx) {
	
		var frm = document.frm;
	
		if (idx == 01) {
			if (obj.selectedIndex == 2) {
				document.getElementById("file_change01").style.display = "inline";
			} else {
				document.getElementById("file_change01").style.display = "none";
			}
		}

		if (idx == 02) {
			if (obj.selectedIndex == 2) {
				document.getElementById("file_change02").style.display = "inline";
			} else {
				document.getElementById("file_change02").style.display = "none";
			}
		}

		if (idx == 03) {
			if (obj.selectedIndex == 2) {
				document.getElementById("file_change03").style.display = "inline";
			} else {
				document.getElementById("file_change03").style.display = "none";
			}
		}

		if (idx == 04) {
			if (obj.selectedIndex == 2) {
				document.getElementById("file_change04").style.display = "inline";
			} else {
				document.getElementById("file_change04").style.display = "none";
			}
		}
	}
	

	function js_delete() {
		var frm = document.frm;

		bDelOK = confirm('해당 메뉴를 삭제 하시겠습니까?\n\n해당 메뉴에 하위 메뉴도 모두 삭제 됩니다.');
		
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
				document.getElementById("str_lat").value = results[0].geometry.location.lat();
				document.getElementById("str_lng").value = results[0].geometry.location.lng();
				initialize();
			} else {
				alert("정확한 주소를 입력해 주세요.");
			}
		});
	}

	function initialize() {

		var lat_val = document.getElementById("str_lat").value;
		var lng_val = document.getElementById("str_lng").value;

		var myLatlng = new google.maps.LatLng(lat_val,lng_val);
		var myOptions = {
			zoom: 16,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}

		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

		var contentString = document.getElementById("comm_name").value + "<br />" + document.getElementById("comm_addr").value;

		var infowindow = new google.maps.InfoWindow({
			content: contentString,size: new google.maps.Size(100,100) 
		});

		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title: document.getElementById("comm_name").value
		});
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});
	}


</script>
</head>

<? if ($rs_str_lat) { ?>
<body onLoad="initialize()">
<? } else { ?>
<body>
<? } ?>

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

			<h3 class="conTitle"><?=$p_menu_name?></h3>

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" id="mode" value="">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>" />
<input type="hidden" name="comm_no" value="<?=$comm_no?>" />
<input type="hidden" name="c_level" value="<?=$c_level?>" />
<input type="hidden" name="c_seq01" value="<?=$c_seq01?>" />
<input type="hidden" name="c_seq02" value="<?=$c_seq02?>" />
<input type="hidden" name="area_name" value="<?=$rs_area_name?>" />
<input type="hidden" name="area_code" value="<?=$rs_area_code?>" />
<input type="hidden" name="use_tf" value="Y">

			<table summary="이곳에서 내용을 입력하실 수 있습니다" class="bbsWrite">
				<caption>내용 입력란</caption>
					<thead>
						<tr>
							<th scope="row">커뮤니티명</th>
							<td colspan="3">
								<?=$rs_comm_name?>
								<input type="hidden" name="comm_name" id="comm_name" value="<?= $rs_comm_name ?>" />
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">커뮤니티구분</th>
							<td colspan="3">
								<?= getDcodeName($conn, 'COMM_TYPE',$rs_comm_type);?>
								<input type="hidden" name="comm_type" value="<?= $rs_comm_type ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">커뮤니티도메인</th>
							<td colspan="3">
								<?= $rs_comm_domain ?>.goupp.org
								<input type="hidden" name="comm_domain" id="comm_domain" value="<?= $rs_comm_domain ?>" >
								<input type="hidden" name="old_comm_domain" id="old_comm_domain" value="<?= $rs_comm_domain ?>">
							</td>
						</tr>
						<tr>	
							<th>메인비주얼<br/>사용여부</th>
							<td colspan="3">
								<input type="radio" class="radio" name="rd_intro_tf" value="Y" <? if (($rs_intro_tf =="Y") || ($rs_intro_tf =="")) echo "checked"; ?>> 보이기<span style="width:20px;"></span>
								<input type="radio" class="radio" name="rd_intro_tf" value="N" <? if ($rs_intro_tf =="N")echo "checked"; ?>> 보이지않기 
								<input type="hidden" name="intro_tf" value="<?= $rs_intro_tf ?>"> 
							</td>
						</tr>
						<tr>
							<th scope="row">주소</th>
							<td colspan="3">
								<input type="text" name="comm_addr" id="comm_addr" value="<?= $rs_comm_addr ?>" style="width:450px;" /> 하단에 표시될 주소를 입력해 주세요.
							</td>
						</tr>
						<tr>
							<th scope="row">전화번호</th>
							<td colspan="3">
								<input type="text" name="comm_phone" value="<?= $rs_comm_phone ?>" style="width:250px;" /> 하단에 표시될 전화번호를 입력해 주세요.
							</td>
						</tr>
						<tr>
							<th scope="row">팩스번호</th>
							<td colspan="3">
								<input type="text" name="comm_fax" value="<?= $rs_comm_fax ?>" style="width:250px;" /> 하단에 표시될 팩스를 입력해 주세요.
							</td>
						</tr>
						<tr>
							<th scope="row">이메일</th>
							<td colspan="3">
								<input type="text" name="comm_email" value="<?= $rs_comm_email ?>" style="width:200px;" /> 하단에 표시될 이메일을 입력해 주세요.
							</td>
						</tr>
						<?
							#====================================================================
							# 커뮤니티 구분별 파일
							#====================================================================
							if (strlen($rs_comm_cd) == 2) {
								require "./comm_write_01.php";
							}

							if (strlen($rs_comm_cd) == 4) {
								require "./comm_write_02.php";
							}
						?>
						<tr>
							<th scope="row">트위터계졍</th>
							<td colspan="3">
								<input type="text" name="tweeter" value="<?=$rs_tweeter?>" style="width: 150px;" /> 아이디(ID) 만 입력해주세요
							</td>
						</tr>
						<tr>
							<th scope="row">페이스북계정</th>
							<td colspan="3">
								<input type="text" name="facebook" value="<?=$rs_facebook?>" style="width: 150px;" /> 아이디(ID) 만 입력해주세요
							</td>
						</tr>
						<tr>
							<th scope="row">미투데이계정</th>
							<td colspan="3">
								<input type="text" name="meto" value="<?=$rs_meto?>" style="width: 150px;" /> 아이디(ID) 만 입력해주세요
							</td>
						</tr>
						<tr>
							<th scope="row">주소검색 (번지)</th>
							<td colspan="3">
								<input type="hidden" name="zip" value="">
								<input type="text" name="addr" id= "address" style="width:450px;" />
								<a href="javascript:js_post('zip','addr');"><img src="/manager/images/admin/btn_filesch.gif" alt="찾기" /></a>
								<a href="javascript:codeAddress();"><img src="/manager/images/admin/btn_map.gif" alt="지도보기" /></a>
								&nbsp;&nbsp;* 번지 까지 입력해 주세요.
							</td>
						</tr>
						<tr>
							<th scope="row">지도</th>
							<td colspan="3">
								<input type="hidden" name="str_lat" id="str_lat" value="<?=$rs_str_lat?>">
								<input type="hidden" name="str_lng" id="str_lng" value="<?=$rs_str_lng?>">
								<div id="map_canvas" style="width: 95%; height: 300px; border:solid 1px #DEDEDE;"></div>
							</td>
						</tr>
					</tbody>
				</table>
			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fRight">
					<li><a href="javascript:js_save();"><img src="../images/btn/btn_rewrite.gif" alt="수정" /></a></li>
				</ul>
			</div>
		</section>
	</section>
</section>
</div><!--wrapper-->
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>
