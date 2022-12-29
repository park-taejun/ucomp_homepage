<?session_start();?>
<?
# ===================================================================
# File Name    : branch_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2016-12-07
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

	$menu_right = "BA001"; // 메뉴마다 셋팅 해 주어야 합니다
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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/branch/branch.php";

#====================================================================
	$savedir1 = $g_physical_path."upload_data/branch";
#====================================================================

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$branch_lang				= $_POST['branch_lang']!=''?$_POST['branch_lang']:$_GET['branch_lang'];
	$branch_type				= $_POST['branch_type']!=''?$_POST['branch_type']:$_GET['branch_type'];
	$group_tf						= $_POST['group_tf']!=''?$_POST['group_tf']:$_GET['group_tf'];
	$logo_img01					= $_POST['logo_img01']!=''?$_POST['logo_img01']:$_GET['logo_img01'];
	$logo_img02					= $_POST['logo_img02']!=''?$_POST['logo_img02']:$_GET['logo_img02'];
	$logo_img03					= $_POST['logo_img03']!=''?$_POST['logo_img03']:$_GET['logo_img03'];
	$logo_img04					= $_POST['logo_img04']!=''?$_POST['logo_img04']:$_GET['logo_img04'];
	$facebook_id				= $_POST['facebook_id']!=''?$_POST['facebook_id']:$_GET['facebook_id'];
	$main_board_id			= $_POST['main_board_id']!=''?$_POST['main_board_id']:$_GET['main_board_id'];
	$notice_board_id		= $_POST['notice_board_id']!=''?$_POST['notice_board_id']:$_GET['notice_board_id'];
	$intro_path					= $_POST['intro_path']!=''?$_POST['intro_path']:$_GET['intro_path'];
	$post_no						= $_POST['post_no']!=''?$_POST['post_no']:$_GET['post_no'];
	$addr								= $_POST['addr']!=''?$_POST['addr']:$_GET['addr'];
	$doroaddr						= $_POST['doroaddr']!=''?$_POST['doroaddr']:$_GET['doroaddr'];
	$map_url						= $_POST['map_url']!=''?$_POST['map_url']:$_GET['map_url'];
	$phone							= $_POST['phone']!=''?$_POST['phone']:$_GET['phone'];
	$fax								= $_POST['fax']!=''?$_POST['fax']:$_GET['fax'];
	$email							= $_POST['email']!=''?$_POST['email']:$_GET['email'];

	$branch_lang			= SetStringToDB($branch_lang);
	$branch_type			= SetStringToDB($branch_type);
	$group_tf					= SetStringToDB($group_tf);
	$logo_img01				= SetStringToDB($logo_img01);
	$logo_img02				= SetStringToDB($logo_img02);
	$logo_img03				= SetStringToDB($logo_img03);
	$logo_img04				= SetStringToDB($logo_img04);
	$facebook_id			= SetStringToDB($facebook_id);
	$main_board_id		= SetStringToDB($main_board_id);
	$notice_board_id	= SetStringToDB($notice_board_id);
	$intro_path				= SetStringToDB($intro_path);
	$post_no					= SetStringToDB($post_no);
	$addr							= SetStringToDB($addr);
	$doroaddr					= SetStringToDB($doroaddr);
	$map_url					= SetStringToDB($map_url);
	$phone						= SetStringToDB($phone);
	$fax							= SetStringToDB($fax);
	$email						= SetStringToDB($email);

#====================================================================
# DML Process
#====================================================================

	$arr_rs = selectBranchInfo($conn, $branch_lang);

		// BRANCH_LANG, BRANCH_TYPE, GROUP_TF, LOGO_IMG01, LOGO_IMG02, LOGO_IMG03, LOGO_IMG04, LOGO_IMG05, 
		// FACEBOOK_ID, MAIN_BOARD_ID, NOTICE_BOARD_ID, POST_NO, ADDR, DOROADDR, MAP_URL, PHONE, FAX, EMAIL, REG_ADM, REG_DATE, UP_ADM, UP_DATE, INFO_01, INFO_02, INFO_03
	
	if (sizeof($arr_rs) > 0) {

		$rs_branch_lang			= trim($arr_rs[0]["BRANCH_LANG"]); 
		$rs_branch_type			= trim($arr_rs[0]["BRANCH_TYPE"]); 
		$rs_group_tf				= trim($arr_rs[0]["GROUP_TF"]); 
		$rs_logo_img01			= trim($arr_rs[0]["LOGO_IMG01"]); 
		$rs_logo_img02			= trim($arr_rs[0]["LOGO_IMG02"]); 
		$rs_logo_img03			= trim($arr_rs[0]["LOGO_IMG03"]); 
		$rs_logo_img04			= trim($arr_rs[0]["LOGO_IMG04"]); 
		$rs_logo_img05			= trim($arr_rs[0]["LOGO_IMG05"]); 
		$rs_facebook_id			= trim($arr_rs[0]["FACEBOOK_ID"]); 
		$rs_main_board_id		= trim($arr_rs[0]["MAIN_BOARD_ID"]); 
		$rs_notice_board_id	= trim($arr_rs[0]["NOTICE_BOARD_ID"]); 
		$rs_intro_path			= trim($arr_rs[0]["INTRO_PATH"]); 
		$rs_post_no					= trim($arr_rs[0]["POST_NO"]); 
		$rs_addr						= trim($arr_rs[0]["ADDR"]); 
		$rs_doroaddr				= trim($arr_rs[0]["DOROADDR"]); 
		$rs_map_url					= trim($arr_rs[0]["MAP_URL"]); 
		$rs_phone						= trim($arr_rs[0]["PHONE"]); 
		$rs_fax							= trim($arr_rs[0]["FAX"]); 
		$rs_email						= trim($arr_rs[0]["EMAIL"]); 
		
		$sub_mode = "U";
	} else {
		$sub_mode = "I";
	}

	if ($mode == "I") {
		
		if ($sub_mode == "I") {
		
			$logo_img01				= upload($_FILES[logo_img01], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			$logo_img02				= upload($_FILES[logo_img02], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			$logo_img03				= upload($_FILES[logo_img03], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
			$logo_img04				= upload($_FILES[logo_img04], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		
			$arr_data = array("BRANCH_LANG"=>$branch_lang,
												"BRANCH_TYPE"=>$branch_type,
												"GROUP_TF"=>$group_tf,
												"LOGO_IMG01"=>$logo_img01,
												"LOGO_IMG02"=>$logo_img02,
												"LOGO_IMG03"=>$logo_img03,
												"LOGO_IMG04"=>$logo_img04,
												"FACEBOOK_ID"=>$facebook_id,
												"MAIN_BOARD_ID"=>$main_board_id,
												"NOTICE_BOARD_ID"=>$notice_board_id,
												"INTRO_PATH"=>$intro_path,
												"POST_NO"=>$post_no,
												"ADDR"=>$addr,
												"DOROADDR"=>$doroaddr,
												"MAP_URL"=>$map_url,
												"PHONE"=>$phone,
												"FAX"=>$fax,
												"EMAIL"=>$email,
												"REG_ADM"=>$_SESSION['s_adm_no'],
												"REG_DATE"=>date("Y-m-d H:i:s",strtotime("0 day")));

			$result = insertBranchInfo($conn, $arr_data);
		}
		
		if ($sub_mode == "U") {

			switch ($flag01) {
				case "insert" :
					$logo_img01	= upload($_FILES[logo_img01], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
				break;
				case "keep" :
					$logo_img01	= $old_logo_img01;
				break;
				case "delete" :
					$logo_img01	= "";
				break;
				case "update" :
					$logo_img01	= upload($_FILES[logo_img01], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
				break;
			}

			switch ($flag02) {
				case "insert" :
					$logo_img02	= upload($_FILES[logo_img02], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
				break;
				case "keep" :
					$logo_img02	= $old_logo_img02;
				break;
				case "delete" :
					$logo_img02	= "";
				break;
				case "update" :
					$logo_img02	= upload($_FILES[logo_img02], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
				break;
			}

			switch ($flag03) {
				case "insert" :
					$logo_img03	= upload($_FILES[logo_img03], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
				break;
				case "keep" :
					$logo_img03	= $old_logo_img03;
				break;
				case "delete" :
					$logo_img03	= "";
				break;
				case "update" :
					$logo_img03	= upload($_FILES[logo_img03], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
				break;
			}

			$arr_data = array("BRANCH_TYPE"=>$branch_type,
												"GROUP_TF"=>$group_tf,
												"LOGO_IMG01"=>$logo_img01,
												"LOGO_IMG02"=>$logo_img02,
												"LOGO_IMG03"=>$logo_img03,
												"LOGO_IMG04"=>$logo_img04,
												"FACEBOOK_ID"=>$facebook_id,
												"MAIN_BOARD_ID"=>$main_board_id,
												"NOTICE_BOARD_ID"=>$notice_board_id,
												"INTRO_PATH"=>$intro_path,
												"POST_NO"=>$post_no,
												"ADDR"=>$addr,
												"DOROADDR"=>$doroaddr,
												"MAP_URL"=>$map_url,
												"PHONE"=>$phone,
												"FAX"=>$fax,
												"EMAIL"=>$email,
												"UP_ADM"=>$_SESSION['s_adm_no'],
												"UP_DATE"=>date("Y-m-d H:i:s",strtotime("0 day")));

			$result = updateBranchInfo($conn, $arr_data, $branch_lang);

		}

	}

	if ($result) {
?>	
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "branch_list.php";
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
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="javascript" type="text/javascript">

function js_list() {
	document.location = "branch_list.php";
}

function js_save() {

	var frm = document.frm;
	var branch_lang = "<?=$branch_lang?>";
	frm.mode.value = "I";

	if (document.frm.rd_group_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_group_tf[0].checked == true) {
			frm.group_tf.value = "Y";
		} else {
			frm.group_tf.value = "N";
		}
	}

	frm.method = "post";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

	function sample6_execDaumPostcode() {
		new daum.Postcode({
			oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수
			var fullAddr01 = ''; // 지번
			var fullAddr02 = ''; // 도로명

			fullAddr01 = data.jibunAddress;
			fullAddr02 = data.roadAddress;

			//법정동명이 있을 경우 추가한다.
			if(data.bname !== ''){
				extraAddr += data.bname;
			}
			// 건물명이 있을 경우 추가한다.
			if(data.buildingName !== ''){
				extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
			}
			// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
			fullAddr02 += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById('post_no').value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById('addr').value = fullAddr01;
			document.getElementById('doroaddr').value = fullAddr02;
			

		}
		}).open();
	}

</script>

</head>
<body>
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

<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="branch_lang" value="<?=$branch_lang?>" />

			<h3 class="conTitle"><?=$p_menu_name?></h3>
			<table summary="이곳에서 <?=$p_menu_name?>을 입력하실 수 있습니다" class="bbsWrite">
				<colgroup>
					<col width="180" />
					<col width="*" />
					<col width="120" />
					<col width="*" />
				</colgroup>
				<tr>
					<th>사이트 구분</th>
					<td colspan="3" class="line">
						<? if ($rs_branch_type == "") $rs_branch_type = "PARTY"; ?>
						<?=makeRadioBox($conn,"BRANCH_TYPE","branch_type",$rs_branch_type)?>
					</td>
				</tr>
				<tr> 
					<th>모임 사용여부</th>
					<td colspan="3" class="line">
						<input type="radio" class="radio" name="rd_group_tf" value="Y" <? if (($rs_group_tf =="Y") || ($rs_group_tf =="")) echo "checked"; ?>> 당원모임 사용&nbsp;&nbsp;<span style="width:30px;"></span>
						<input type="radio" class="radio" name="rd_group_tf" value="N" <? if ($rs_group_tf =="N") echo "checked"; ?>> 당원모임 사용안함 
						<input type="hidden" name="group_tf" value="<?= $rs_group_tf ?>"> 
					</td>
				</tr>

				<tr>
					<th>로고 이미지 (메인)</th>
					<td colspan="3">
						<?
							if (strlen($rs_logo_img01) > 3) {
						?>
							<img src="<?=$g_base_dir?>/upload_data/branch/<?=$rs_logo_img01?>" style="background-color:darkorange">
							&nbsp;&nbsp;
							<select name="flag01" style="width:70px;" onchange="javascript:js_fileView(this,'01')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_logo_img01" value="<?= $rs_logo_img01?>">
							<div id="file_change01" style="display:none;">
								<input type="file" name="logo_img01" size="30%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="logo_img01">
							<input type="hidden" name="old_logo_img01" value="">
							<input TYPE="hidden" name="flag01" value="insert">
						<?
							}	
						?>
					</td>
				</tr>
				<tr>
					<th>로고 이미지 (하단)</th>
					<td colspan="3" class="line">
						<?
							if (strlen($rs_logo_img02) > 3) {
						?>
							<img src="<?=$g_base_dir?>/upload_data/branch/<?=$rs_logo_img02?>">
							&nbsp;&nbsp;
							<select name="flag02" style="width:70px;" onchange="javascript:js_fileView(this,'02')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_logo_img02" value="<?= $rs_logo_img02?>">
							<div id="file_change02" style="display:none;">
								<input type="file" name="logo_img02" size="30%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="logo_img02">
							<input type="hidden" name="old_logo_img02" value="">
							<input TYPE="hidden" name="flag02" value="insert">
						<?
							}	
						?>
					</td>
				</tr>

				<tr>
					<th>로고 이미지 (모바일)</th>
					<td colspan="3" class="line">
						<?
							if (strlen($rs_logo_img03) > 3) {
						?>
							<img src="<?=$g_base_dir?>/upload_data/branch/<?=$rs_logo_img03?>">
							&nbsp;&nbsp;
							<select name="flag03" style="width:70px;" onchange="javascript:js_fileView(this,'03')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>

							<input type="hidden" name="old_logo_img03" value="<?= $rs_logo_img03?>">
							<div id="file_change03" style="display:none;">
								<input type="file" name="logo_img03" size="30%" />
							</div>
						<?
							} else {
						?>
							<input type="file" size="40%" name="logo_img03">
							<input type="hidden" name="old_logo_img03" value="">
							<input TYPE="hidden" name="flag03" value="insert">
						<?
							}	
						?>
					</td>
				</tr>
				<tr>
					<th colspan="3" class="line">페이스북</th>
					<td class="line">
						<input type="text" name="facebook_id" value="<?=$rs_facebook_id?>" style="width: 70%;"/>
					</td>
				</tr>

				<tr>
					<th colspan="3" class="line">메인 게시판 경로</th>
					<td class="line">
						<input type="text" name="main_board_id" value="<?=$rs_main_board_id?>" style="width: 15%;"/>
					</td>
				</tr>

				<tr>
					<th colspan="3" class="line">공지사항 게시판 경로</th>
					<td class="line">
						<input type="text" name="notice_board_id" value="<?=$rs_notice_board_id?>" style="width: 15%;"/>
					</td>
				</tr>

				<tr>
					<th colspan="3" class="line">소개 페이지 경로</th>
					<td class="line">
						<input type="text" name="intro_path" value="<?=$rs_intro_path?>" style="width: 15%;"/>
					</td>
				</tr>

				<tr>
					<th colspan="3" class="line">우편번호</th>
					<td class="line">
						<input type="text" id="post_no" name="post_no" value="<?=$rs_post_no?>" style="width: 60px;" readonly="1"/>
						<a href="javascript:void(0)" class="btn_type5 gray_post" onclick="sample6_execDaumPostcode();">주소검색</a> &nbsp; * 지번 주소를 클릭하여 입력해 주세요.
					</td>
				</tr>

				<tr>
					<th colspan="3" class="line">지번 주소</th>
					<td class="line">
						<input type="text" id="addr" name="addr" value="<?=$rs_addr?>" style="width: 70%;"/>
					</td>
				</tr>

				<tr>
					<th colspan="3" class="line">도로명 주소</th>
					<td class="line">
						<input type="text" id="doroaddr" name="doroaddr" value="<?=$rs_doroaddr?>" style="width: 70%;"/>
					</td>
				</tr>

				<tr>
					<th colspan="3" class="line">구글 지도 경로</th>
					<td class="line">
						<input type="text" name="map_url" value="<?=$rs_map_url?>" style="width: 70%;"/>
					</td>
				</tr>

				<tr>
					<th colspan="3" class="line">연락처</th>
					<td class="line">
						<input type="text" name="phone" value="<?=$rs_phone?>" style="width: 150px;"/>
					</td>
				</tr>

				<tr>
					<th colspan="3" class="line">팩스</th>
					<td class="line">
						<input type="text" name="fax" value="<?=$rs_fax?>" style="width: 150px;"/>
					</td>
				</tr>

				<tr class="end">
					<th colspan="3" class="line">이메일</th>
					<td class="line">
						<input type="text" name="email" value="<?=$rs_email?>" style="width: 70%;"/>
					</td>
				</tr>

			</table>

			<div class="btnArea">
				<ul class="fRight">
				<?	if ($sPageRight_I == "Y") {?>
				<li><a href="javascript:js_save();"><img src="../images/admin/btn_confirm.gif" alt="확인" /></a></li>
				<?	}?>
				<li><a href="javascript:js_list();"><img src="../images/admin/btn_list.gif" alt="목록" /></a></li>
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