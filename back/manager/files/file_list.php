<?session_start();?>
<?
# =============================================================================
# File Name    : file_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.05.29
# Modify Date  : 
#	Copyright : Copyright @jinhak Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$menu_right = "CS003"; // 메뉴마다 셋팅 해 주어야 합니다

#==============================================================================
# Confirm right
#==============================================================================

	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$file_no						= $_POST['file_no']!=''?$_POST['file_no']:$_GET['file_no'];
	$file_nm						= $_POST['file_nm']!=''?$_POST['file_nm']:$_GET['file_nm'];
	
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

	#List Parameter
	$file_no		= trim($file_no);
	$file_nm		= trim($file_nm);
	
	//echo $banner_type;

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
	require "../../_classes/biz/files/files.php";
	require "../../_classes/biz/admin/admin.php";

	//$arr_rs = getSiteInfo($conn, $site_no);

#====================================================================
# Request Parameter
#====================================================================

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$pdf_file_nm				= $_POST['pdf_file_nm']!=''?$_POST['pdf_file_nm']:$_GET['pdf_file_nm'];
	$pdf_file_rnm				= $_POST['pdf_file_rnm']!=''?$_POST['pdf_file_rnm']:$_GET['pdf_file_rnm'];

	$hwp_file_nm				= $_POST['hwp_file_nm']!=''?$_POST['hwp_file_nm']:$_GET['hwp_file_nm'];
	$hwp_file_rnm				= $_POST['hwp_file_rnm']!=''?$_POST['hwp_file_rnm']:$_GET['hwp_file_rnm'];

	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];

	$old_pdf_file_nm		= $_POST['old_pdf_file_nm']!=''?$_POST['old_pdf_file_nm']:$_GET['old_pdf_file_nm'];
	$old_pdf_file_rnm		= $_POST['old_pdf_file_rnm']!=''?$_POST['old_pdf_file_rnm']:$_GET['old_pdf_file_rnm'];

	$old_hwp_file_nm		= $_POST['old_hwp_file_nm']!=''?$_POST['old_hwp_file_nm']:$_GET['old_hwp_file_nm'];
	$old_hwp_file_rnm		= $_POST['old_hwp_file_rnm']!=''?$_POST['old_hwp_file_rnm']:$_GET['old_hwp_file_rnm'];


	$flag01							= $_POST['flag01']!=''?$_POST['flag01']:$_GET['flag01'];
	$flag02							= $_POST['flag02']!=''?$_POST['flag02']:$_GET['flag02'];

	$mode					= trim($mode);

	$nPage				= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field	= trim($search_field);
	$search_str		= trim($search_str);

#====================================================================
# DML Process
#====================================================================

	if ($mode == "I") {

#====================================================================
	$savedir1 = $g_physical_path."upload_data/files";
#====================================================================

		$pdf_file_nm	= SetStringToDB($pdf_file_nm);
		$pdf_file_rnm = SetStringToDB($pdf_file_rnm);

		$hwp_file_nm	= SetStringToDB($hwp_file_nm);
		$hwp_file_rnm = SetStringToDB($hwp_file_rnm);
		

		$pdf_file_nm			= upload($_FILES[pdf_file_nm], $savedir1, 1000 , array('pdf', 'PDF'));
		$pdf_file_rnm			= $_FILES[pdf_file_nm][name];

		$hwp_file_nm			= upload($_FILES[hwp_file_nm], $savedir1, 1000 , array('hwp', 'HWP'));
		$hwp_file_rnm			= $_FILES[hwp_file_nm][name];

		//$file_ext = end(explode('.', $_FILES[banner_img]['name']));
		//$banner_real_img = str_replace(".".$file_ext,"",$_FILES[banner_img]['name']).".".$file_ext;

		$use_tf = "Y";

		$result =  insertFiles($conn, $file_nm, $pdf_file_nm, $pdf_file_rnm, $hwp_file_nm, $hwp_file_rnm, $use_tf, $_SESSION["s_adm_no"]);

		$result_log = insertUserLog($conn, "admin", $_SESSION["s_adm_id"], $_SERVER['REMOTE_ADDR'], " [".$file_nm."] 모집요강 등록", "Insert");

		if ($result) {
?>	
<script language="javascript">
		//location.href =  '<?=$_SERVER[PHP_SELF]?>?banner_type=<?=$banner_type?>';
</script>
<?
			//exit;
		}
	}

	if ($mode == "U") {

#====================================================================
		$savedir1 = $g_physical_path."upload_data/files";
#====================================================================
		# file업로드
		switch ($flag01) {
			case "insert" :

				$pdf_file_nm			= upload($_FILES[pdf_file_nm], $savedir1, 1000 , array('pdf', 'PDF'));
				$pdf_file_rnm			= $_FILES[pdf_file_nm][name];

			break;
			case "keep" :

				$pdf_file_nm			= $old_pdf_file_nm;
				$pdf_file_rnm			= $old_pdf_file_rnm;

			break;
			case "delete" :

				$pdf_file_nm	= "";
				$pdf_file_rnm = "";

			break;
			case "update" :

				$pdf_file_nm			= upload($_FILES[pdf_file_nm], $savedir1, 1000 , array('pdf', 'PDF'));
				$pdf_file_rnm			= $_FILES[pdf_file_nm][name];

			break;
		}

		switch ($flag02) {
			case "insert" :

				$hwp_file_nm			= upload($_FILES[hwp_file_nm], $savedir1, 1000 , array('hwp', 'HWP'));
				$hwp_file_rnm			= $_FILES[hwp_file_nm][name];

			break;
			case "keep" :

				$hwp_file_nm			= $old_hwp_file_nm;
				$hwp_file_rnm			= $old_hwp_file_rnm;

			break;
			case "delete" :

				$hwp_file_nm	= "";
				$hwp_file_rnm = "";

			break;
			case "update" :

				$hwp_file_nm			= upload($_FILES[hwp_file_nm], $savedir1, 1000 , array('hwp', 'HWP'));
				$hwp_file_rnm			= $_FILES[hwp_file_nm][name];

			break;
		}
	
		$use_tf = "Y";

		$result = updateFiles($conn, $file_nm, $pdf_file_nm, $pdf_file_rnm, $hwp_file_nm, $hwp_file_rnm, $use_tf, $_SESSION["s_adm_no"], $file_no);

		$result_log = insertUserLog($conn, "admin", $_SESSION["s_adm_id"], $_SERVER['REMOTE_ADDR'], " [".$file_nm."] 모집요강 수정", "Update");

		if ($result) {
?>	
<script language="javascript">
	location.href =  '<?=$_SERVER[PHP_SELF]?>?menu_cd=<?=$menu_cd?>';
</script>
<?
			exit;
		}

	}

	if ($mode == "S") {

		$arr_rs = selectFiles($conn, $file_no);
		
		$rs_file_no					= trim($arr_rs[0]["FILE_NO"]); 
		$rs_file_nm					= trim($arr_rs[0]["FILE_NM"]); 
		$rs_pdf_file_nm			= SetStringFromDB($arr_rs[0]["PDF_FILE_NM"]); 
		$rs_pdf_file_rnm		= SetStringFromDB($arr_rs[0]["PDF_FILE_RNM"]); 
		$rs_hwp_file_nm			= SetStringFromDB($arr_rs[0]["HWP_FILE_NM"]); 
		$rs_hwp_file_rnm		= SetStringFromDB($arr_rs[0]["HWP_FILE_RNM"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 

	}

	$del_tf = "N";
	$use_tf = "";
#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 100;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntFiles($conn, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = ($nListCnt - 1) / $nPageSize + 1 ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	$nPage		 = 1;
	$nPageSize = 100;

	$arr_rs = listFiles($conn, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	$result_log = insertUserLog($conn, "admin", $_SESSION["s_adm_id"], $_SERVER['REMOTE_ADDR'], "모집요강 리스트 조회", "List");

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

<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript">

function js_save() {

	var frm = document.frm;
	var file_no = "<?= $file_no ?>";
	
	if (file_no == "") {
		alert('수정하고자 하는 모집요강의 제목을 먼저 선택 하세요.');
		return ;		
	}

	frm.file_nm.value = frm.file_nm.value.trim();
	
	if (isNull(frm.file_nm.value)) {
		alert('제목을 입력해주세요.');
		frm.file_nm.focus();
		return ;		
	}

	if (isNull(file_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.file_no.value = frm.file_no.value;
	}

	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_view(file_no) {

	var frm = document.frm;
		
	frm.file_no.value = file_no;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "get";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
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

<form id="bbsList" name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="file_no" value="<?=$file_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 모집요강을 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?></caption>
					<tbody>
						<tr>
							<th class="long"><label for="bannerTit">제목</label></th>
							<td>
								<?=$rs_file_nm?>
								<input type="hidden" name="file_nm" value="<?=$rs_file_nm?>" class="wfull" />
							</td>
						</tr>
						<tr>
							<th class="long"><label for="pdf_file_nm">PDF파일</label></th>
							<td>
							<?
								if (strlen($rs_pdf_file_nm) > 3) {
							?>
								<a href="/_common/new_download_file.php?menu=files&file_no=<?= $rs_file_no ?>&field=pdf_file_nm"><?= $rs_pdf_file_rnm ?></a>
								<select name="flag01" style="width:70px;" onchange="javascript:js_fileView(this,'01')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
							
								<input type="hidden" name="old_pdf_file_nm" value="<?= $rs_pdf_file_nm?>">
								<input type="hidden" name="old_pdf_file_rnm" value="<?= $rs_pdf_file_rnm?>">

								<div id="file_change01" style="display:none;">
										<input type="file" id="pdf_file_nm" class="w50per" name="pdf_file_nm" />&nbsp;<?=$str_img_size?>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="pdf_file_nm" class="w50per" name="pdf_file_nm" /><span class="explain"><?=$str_img_size?></span>
								<input type="hidden" name="old_pdf_file_nm" value="">
								<input type="hidden" name="old_pdf_file_rnm" value="">
								<input TYPE="hidden" name="flag01" value="insert">
							<?
								}	
							?>
							</td>
						</tr>

						<tr>
							<th class="long"><label for="hwp_file_nm">HWP파일</label></th>
							<td>
							<?
								if (strlen($rs_hwp_file_nm) > 3) {
							?>
								<a href="/_common/new_download_file.php?menu=files&file_no=<?= $rs_file_no ?>&field=hwp_file_nm"><?= $rs_hwp_file_rnm ?></a>
								<select name="flag02" style="width:70px;" onchange="javascript:js_fileView(this,'02')">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
							
								<input type="hidden" name="old_hwp_file_nm" value="<?= $rs_hwp_file_nm?>">
								<input type="hidden" name="old_hwp_file_rnm" value="<?= $rs_hwp_file_rnm?>">

								<div id="file_change02" style="display:none;">
										<input type="file" id="hwp_file_nm" class="w50per" name="hwp_file_nm" />&nbsp;<?=$str_img_size?>
								</div>
							<?
								} else {	
							?>
								<input type="file" id="hwp_file_nm" class="w50per" name="hwp_file_nm" /><span class="explain"><?=$str_img_size?></span>
								<input type="hidden" name="old_hwp_file_nm" value="">
								<input type="hidden" name="old_hwp_file_rnm" value="">
								<input TYPE="hidden" name="flag02" value="insert">
							<?
								}	
							?>
							</td>
						</tr>

						</tbody>
					</table>

					<div class="btnArea">
						<ul class="fRight">
							<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
							<li><a href="/manager/files/file_list.php?menu_cd=0303"><img src="../images/btn/btn_cancel.gif" alt="취소" /></a></li>
						</ul>
					</div>

					<table summary="이곳에서 모집요강를 관리하실 수 있습니다" class="secBtm" id='t'>
						<thead>
							<tr>
								<th class="num">순서</th>
								<th class="tit">제목</th>
								<th class="visual">PDF 파일</th>
								<th class="visual">HWP 파일</th>
								<th class="visual">수정일</th>
							</tr>
						</thead>
						<tbody>
						<?
								
							if (sizeof($arr_rs) > 0) {
											
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$FILE_NO					= trim($arr_rs[$j]["FILE_NO"]);
									$FILE_NM					= trim($arr_rs[$j]["FILE_NM"]);
									$PDF_FILE_NM			= trim($arr_rs[$j]["PDF_FILE_NM"]);
									$PDF_FILE_RNM			= trim($arr_rs[$j]["PDF_FILE_RNM"]);
									$HWP_FILE_NM			= trim($arr_rs[$j]["HWP_FILE_NM"]);
									$HWP_FILE_RNM			= trim($arr_rs[$j]["HWP_FILE_RNM"]);
									$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
									$UP_DATE					= trim($arr_rs[$j]["UP_DATE"]);

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

									$offset = $nPageSize * ($nPage-1);
									$logical_num = ($nListCnt - $offset);
									$rn = $logical_num - $j;
						?>
							<tr <? if ($j == (sizeof($arr_rs) -1)) echo "class='last'"; ?> >
								<td class="num"><?=$rn?></td>
								<td class="tit">
									<a href="javascript:js_view('<?=$FILE_NO?>');"><?=$FILE_NM?></a>
								</td>
								<td>
									<?=$PDF_FILE_RNM?>
								</td>
								<td>
									<?=$HWP_FILE_RNM?>
								</td>
								<td>
									<?=$UP_DATE?>
								</td>
							</tr>
						<?
								}
							} else { 
						?>
							<tr>
								<td height="50" align="center" colspan="7">데이터가 없습니다. </td>
							</tr>
				<? 
					}
				?>
						</tbody>
				</table>
			</fieldset>
			</form>
		</section>
	</section>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	sqlsrv_close($conn);
?>
