<?
ini_set('memory_limit',-1);
session_start();
?>
<?
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : upload.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright    : Copyright @C&C Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "MB003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";


	function insertTempMember2($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				if ($key == "M_PASSWORD") {
					$set_value .= ",PASSWORD('".$value."')"; 
				} else {
					$set_value .= ",'".$value."'"; 
				}
			}
		}

		$query = "INSERT INTO TBL_MEMBER_TEMP2 (".$set_field.") 
					values (".$set_value."); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function listMemberTemp($db, $temp_no) {

		$query = "SELECT * FROM TBL_MEMBER_TEMP2 WHERE TEMP_NO = '$temp_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function dupCheckMember($db, $type, $check_value) {
		
		$return_str = "T";

		if ($type == "ID") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_ID = '$check_value' AND M_LEAVE_DATE = '' ";
		}

		if ($type == "NICK") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_NICK = '$check_value' AND M_LEAVE_DATE = '' ";
		}

		if ($type == "EMAIL") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_EMAIL = '$check_value' AND M_LEAVE_DATE = '' ";
		}

		if ($type == "JUMIN") {
			$query = "SELECT COUNT(*) AS CNT FROM TBL_MEMBER WHERE M_JUMIN = '$check_value' AND M_LEAVE_DATE = '' ";
		}
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		if ($rows[0] == 0) {
			$return_str = "T";
		} else {
			$return_str = "F";
		}

		if ($type == "ID" && $return_str =="T") {
			
			$query = "SELECT COUNT(*) AS CNT FROM TBL_ADMIN_INFO WHERE ADM_ID = '$check_value' ";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			if ($rows[0] == 0) {
				$return_str = "T";
			} else {
				$return_str = "F";
			}
		}

		return $return_str;
	}

	function searchGroupCd($db, $party, $sido, $str) {
		
		$record = "";

		$query = "SELECT GROUP_CD FROM TBL_GROUP WHERE GROUP_CD = '$str' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		
		if ($record == "") {

			$temp_str = "str".$sido;

			if (strpos($temp_str, "경상북도") > 0) {
				$sido = "경북";
			} else if (strpos($temp_str, "경상남도") > 0) {
				$sido = "경남";
			} else if (strpos($temp_str, "전라북도") > 0) {
				$sido = "전북";
			} else if (strpos($temp_str, "전라남도") > 0) {
				$sido = "전남";
			} else if (strpos($temp_str, "충청북도") > 0) {
				$sido = "충북";
			} else if (strpos($temp_str, "충청남도") > 0) {
				$sido = "충남";
			} else if (strpos($temp_str, "세종") > 0) {
				$sido = "세종특별자치시";
			} else if (strpos($temp_str, "제주") > 0) {
				$sido = "제주특별자치도";
			} else if (strpos($temp_str, "광주") > 0) {
				$sido = "광주";
			} else if (strpos($temp_str, "인천") > 0) {
				$sido = "인천";
			} else if (strpos($temp_str, "부산") > 0) {
				$sido = "부산";
			} else if (strpos($temp_str, "대전") > 0) {
				$sido = "대전";
			} else if (strpos($temp_str, "대구") > 0) {
				$sido = "대구";
			} else if (strpos($temp_str, "울산") > 0) {
				$sido = "울산";
			} else if (strpos($temp_str, "강원") > 0) {
				$sido = "강원";
			} else if (strpos($temp_str, "경기") > 0) {
				$sido = "경기";
			} else if (strpos($temp_str, "서울") > 0) {
				$sido = "서울";
			}

			$query = "SELECT GROUP_CD FROM TBL_GROUP WHERE GROUP_KIND = '$party' AND GROUP_SIDO = '$sido' AND GROUP_NAME like '$str%' limit 1 ";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$record  = $rows[0];
		} 
		
		return $record;

	}

	function updateOrganization($db, $GROUP_CD, $SEQ_NO) {
		
		$query = "UPDATE TBL_MEMBER_TEMP2 SET M_ORGANIZATION = '$GROUP_CD' WHERE SEQ_NO = '$SEQ_NO'  ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

	}

#====================================================================
# Request Parameter
#====================================================================
	$mode	= trim($mode);
	
#====================================================================
# DML Process
#====================================================================
	if ($mode == "FR") {
	
	#====================================================================
		$savedir1 = $g_physical_path."upload_data/temp_member";
	#====================================================================

		$file_nm = upload($_FILES[file_nm], $savedir1, 10000 , array('xls'));

		require_once "../../_PHPExcel/Classes/PHPExcel.php";
		$objPHPExcel = new PHPExcel();
		require_once "../../_PHPExcel/Classes/PHPExcel/IOFactory.php";
		$filename = '../../upload_data/temp_member/'.$file_nm; 

		//error_reporting(E_ALL ^ E_NOTICE);
		error_reporting(E_ALL ^ E_NOTICE);

		$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
		$objReader->setReadDataOnly(true);
		$objExcel = $objReader->load($filename);
		$objExcel->setActiveSheetIndex(0);
		$objWorksheet = $objExcel->getActiveSheet();

		$rowIterator = $objWorksheet->getRowIterator();

		foreach ($rowIterator as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
		}

		$maxRow = $objWorksheet->getHighestRow();

		//echo $filename;
		//echo $maxRow;
		//exit;

		$chk_str = trim($objWorksheet->getCell('AA' . 1)->getValue());

		if ($chk_str != "지역위원회") {
?>
<script language="javascript">
	alert("등록 파일 형식이 최종 파일 형식이 아닙니다. \n예제 파일을 다시 받아 작성해 주세요.");
	location.href =  'upload.php';
</script>
<?
			mysql_close($conn);
			exit;
		}

		for ($i = 2 ; $i <= $maxRow ; $i++) {

			$m_name					= trim($objWorksheet->getCell('A' . $i)->getValue());
			$m_sex					= trim($objWorksheet->getCell('B' . $i)->getValue());
			$m_age					= trim($objWorksheet->getCell('C' . $i)->getValue());
			$m_birth				= trim($objWorksheet->getCell('D' . $i)->getValue());
			$m_jumin				= trim($objWorksheet->getCell('E' . $i)->getValue());
			$m_mtel					= trim($objWorksheet->getCell('F' . $i)->getValue());
			$m_sms					= trim($objWorksheet->getCell('G' . $i)->getValue());
			$m_tel					= trim($objWorksheet->getCell('H' . $i)->getValue());
			$m_email				= trim($objWorksheet->getCell('I' . $i)->getValue());
			$m_mailling			= trim($objWorksheet->getCell('J' . $i)->getValue());
			$m_postcode			= trim($objWorksheet->getCell('K' . $i)->getValue());
			$m_sido					= trim($objWorksheet->getCell('L' . $i)->getValue());
			$m_sigungu			= trim($objWorksheet->getCell('M' . $i)->getValue());
			$m_address2			= trim($objWorksheet->getCell('N' . $i)->getValue());
			$party					= trim($objWorksheet->getCell('O' . $i)->getValue());
			$m_organization	= trim($objWorksheet->getCell('P' . $i)->getValue());
			$job						= trim($objWorksheet->getCell('Q' . $i)->getValue());
			$com_name				= trim($objWorksheet->getCell('R' . $i)->getValue());
			$o_postcode			= trim($objWorksheet->getCell('S' . $i)->getValue());
			$o_sigungu			= trim($objWorksheet->getCell('T' . $i)->getValue());
			$o_address2			= trim($objWorksheet->getCell('U' . $i)->getValue());
			$group					= trim($objWorksheet->getCell('V' . $i)->getValue());
			$pay_type				= trim($objWorksheet->getCell('W' . $i)->getValue());
			$cms_info01			= trim($objWorksheet->getCell('X' . $i)->getValue());
			$cms_info02			= trim($objWorksheet->getCell('Y' . $i)->getValue());
			$cms_amount			= trim($objWorksheet->getCell('Z' . $i)->getValue());
			$area						= trim($objWorksheet->getCell('AA' . $i)->getValue());
			$m_organization_cd	= trim($objWorksheet->getCell('AB' . $i)->getValue());

			//echo $m_name."<br>";

			if ($party == "없음") {
				$party = "지역";
			}

			if ($party == "") {
				$party = "지역";
			}

			$m_postcode			= str_replace("-","",$m_postcode);
			
			$cms_amount			= str_replace(",","",$cms_amount);
			$cms_amount			= str_replace("원","",$cms_amount);
			$cms_amount			= str_replace(" ","",$cms_amount);

			$m_jumin				= str_replace("-","",$m_jumin);
			$m_jumin				= str_replace(" ","",$m_jumin);
			
			$m_jumin01			= left($m_jumin,6);
			$m_jumin02			= right($m_jumin,7);

			$m_jumin				= $m_jumin01."-".$m_jumin02;

			$en_m_jumin			=  encrypt($key, $iv, $m_jumin);
			$en_m_mtel			=  encrypt($key, $iv, $m_mtel);
			$en_m_tel				=  encrypt($key, $iv, $m_tel);

			$m_online_flag="off";

			if ($cms_info01 <> "") {
				$en_cms_info01	=  encrypt($key, $iv, $cms_info01);
			} else {
				$en_cms_info01 = "";
			}
			
			if ($cms_info02 <> "") {
				$en_cms_info02	=  encrypt($key, $iv, $cms_info02);
			} else {
				$en_cms_info02 = "";
			}

			if (($m_sms != "1") && ($m_sms != "0")) {
				$m_sms = "1";
			}

			if (($m_mailling != "1") && ($m_mailling != "0")) {
				$m_mailling = "1";
			}

			$arr_data = array("TEMP_NO"=>$file_nm,
												"M_NAME"=>$m_name,
												"M_SEX"=>$m_sex,
												"M_AGE"=>$m_age,
												"M_BIRTH"=>$m_birth,
												"M_JUMIN"=>$en_m_jumin,
												"M_TEL"=>$en_m_tel,
												"M_HP"=>$en_m_mtel,
												"M_SMS"=>$m_sms,
												"M_EMAIL"=>$m_email,
												"M_MAILLING"=>$m_mailling,
												"M_ZIP1"=>$m_postcode,
												"M_ADDR1"=>$m_sigungu,
												"M_ADDR2"=>$m_address2,
												"O_ZIP1"=>$o_postcode,
												"O_ADDR1"=>$o_sigungu,
												"O_ADDR2"=>$o_address2,
												"M_ONLINE_FLAG"=>$m_online_flag,
												"SIDO"=>$m_sido,
												"PARTY"=>$party,
												"M_ORGANIZATION"=>$m_organization_cd,
												"JOB"=>$job,
												"COM_NAME"=>$com_name,
												"M_GROUP"=>$group,
												"PAY_TYPE"=>$pay_type,
												"BANK_NO"=>$en_cms_info01,
												"BANK_CODE"=>$en_cms_info02,
												"CMS_AMOUNT"=>$cms_amount,
												"AREA"=>$area);

			$result =  insertTempMember2($conn, $arr_data);

		}

		$temp_file = $savedir1."/".$file_nm;
		$exist = file_exists($temp_file);

		if($exist){
			$delrst=unlink($temp_file);
			if(!$delrst) {
				echo "삭제실패";
			}
		}



?>	
<script language="javascript">
	location.href =  'upload_temp2.php?mode=L&temp_no=<?=$file_nm?>';
</script>
<?
		mysql_close($conn);
		exit;
	}
	

	if ($mode == "I") {

		$row_cnt = count($ok);
		
		$str_seq_no = "";

		for ($k = 0; $k < $row_cnt; $k++) {
			$str_seq_no .= "'".$ok[$k]."',";
		}

		$str_seq_no = substr($str_seq_no, 0, (strlen($str_seq_no) -1));
		//echo $str_cp_no;

		$insert_result = insertTempToRealMember($conn, $temp_no, $str_seq_no, $key, $iv);

		if ($insert_result) {
			$delete_result = deleteTempToRealMember($conn, $temp_no, $str_seq_no);
		}

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "당원 대량 등록", "Insert");

		$mode = "L";

	}

	if ($mode == "D") {

		$row_cnt = count($chk);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_seq_no = $chk[$k];

			$temp_result = deleteMemberTemp($conn, $temp_no, $tmp_seq_no);
		}
		
		$mode = "L";
	}

	if ($mode == "L") {
		$arr_rs = listMemberTemp($conn, $temp_no);
	}
	
	if ($result) {
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		location.href =  'member_list.php';
</script>
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
<script src="http://code.jquery.com/jquery-1.6.min.js"></script>
<script src="../../js/jcarousellite_1.0.1.min.js"></script>
<script src="../js/common.js"></script>
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

<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#ex_scroll { z-index: 1; background-color:#f7f7f7; overflow: auto; width: 100%; height:155px; border:1px solid #d1d1d1;}
#temp_scroll { clear:both;float:left;margin:0 0 20px 3%;z-index: 1; background-color:#f7f7f7; overflow: auto; padding-left:20px; width: 93%; height:100%; border:1px solid #d1d1d1;border-sizing:border-box;}
.btnright { clear:both;margin-left:3%; }
-->
</style>

<script language="javascript">
	
	// 조회 버튼 클릭 시 
	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "member_list.php";
		frm.submit();
	}

	// 저장 버튼 클릭 시 
	function js_save() {
		
		var file_rname = "<?= $file_rname ?>";
		var frm = document.frm;

		if (isNull(frm.file_nm.value)) {
			alert('파일을 선택해 주세요.');
			frm.file_nm.focus();
			return ;		
		}
		
		if (AllowAttach(frm.file_nm)) {

			if (isNull(file_rname)) {
				frm.mode.value = "FR";
			} else {
				frm.mode.value = "I";
			}

			frm.method = "post";
			frm.action = "upload_temp2.php";
			frm.submit();
		}
	}

	/**
	* 파일 첨부에 대한 선택에 따른 파일첨부 입력란 visibility 설정
	*/
	function js_fileView(obj,idx) {
		
		var frm = document.frm;
		
		if (idx == 01) {
			if (obj.selectedIndex == 2) {
				frm.contracts_nm.style.visibility = "visible";
			} else {
				frm.contracts_nm.style.visibility = "hidden";
			}
		}

	}

	function LimitAttach(obj) {
		var file = obj.value;
		extArray = new Array(".jsp", ".cgi", ".php", ".asp", ".aspx", ".exe", ".com", ".php3", ".inc", ".pl", ".asa", ".bak");
		allowSubmit = false;
		
		if (!file){
			//form1.submit();
		}

		while (file.indexOf("\\") != -1){
			file = file.slice(file.indexOf("\\") + 1);
			ext = file.slice(file.indexOf(".")).toLowerCase();

			for (var i = 0; i < extArray.length; i++){
				if (extArray[i] == ext){ 
					allowSubmit = true; 
					break; 
				}
			}
		}

		if (!allowSubmit){
			//
		}else{
			alert("입력하신 파일은 업로드 될 수 없습니다!\nxlsx 파일인 경우 Excel 97 - 2003 통합문서 (*.xls)로 저장 후 등록해 주십시오.");
			return;
		}
	}


	function AllowAttach(obj) {

		var file = obj.value;
		extArray = new Array(".xls");
		allowSubmit = false;
		
		if (!file){
			//form1.submit();
		}
		
		var arr_file_info = file.split(".");
		ext = "."+arr_file_info[arr_file_info.length - 1];

		for (var i = 0; i < extArray.length; i++){
			if (extArray[i] == ext){ 
				allowSubmit = true; 
				break; 
			}
		}

		if (allowSubmit){
			return true;
		}else{
			alert("입력하신 파일은 업로드 될 수 없습니다!\nxlsx 파일인 경우 Excel 97 - 2003 통합문서 (*.xls)로 저장 후 \n등록해 주십시오.");
			return false;
		}
	}

	function js_view(file_nm, order_no) {
		
		var url = "order_modify.php?mode=S&temp_no="+file_nm+"&order_no="+order_no;
		NewWindow(url, '주문대량입력', '860', '513', 'YES');
		
	}

	function js_reload() {
		location.href =  'order_write_file.php?mode=L&temp_no=<?=$temp_no?>';
	}

	function js_delete() {

		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");
		
		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
		
		if (chk_cnt == 0) {
			alert("선택 하신 자료가 없습니다.");
		} else {

			bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');
			
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
			}
		}
	}

	function js_register() {
		var frm = document.frm;
		bDelOK = confirm('정상 데이타는 모두 등록 하시겠습니까?');

		if (bDelOK==true) {
			frm.mode.value = "I";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
		
	}

	function js_excel() {
		
		var frm = document.frm;

		frm.target = "";
		frm.action = "upload_file_excel.php";
		frm.submit();

		//alert("자료 출력");
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
<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="rn" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="temp_no" value="<?=$temp_no?>">
<input type="hidden" name="cp_no" value="">

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
		
		<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
			<caption>게시판 생성</caption>
			<colgroup>
				<col width="20%" />
				<col width="80%" />
			</colgroup>
			<tbody>
				<tr>
					<th>예제파일</td>
					<td>
						<a href="sample.xls"><b>내려 받기</b></a>
					</td>
				</tr>
				<tr>
					<th>파일</th>
					<td><input type="file" name="file_nm" style="width:60%;border:none"></td>
				</tr>
			</tbody>
		</table>
		<div class="btnArea">
			<ul class="fRight">
				<? if ($file_nm <> "" ) {?>
					<? if ($sPageRight_U == "Y") {?>
					<li><a href="javascript:js_save();"><img src="../images/admin/btn_confirm.gif" alt="확인" /></a></li>
					<? } ?>
				<? } else {?>
					<? if ($sPageRight_I == "Y") {?>
					<li><a href="javascript:js_save();"><img src="../images/admin/btn_confirm.gif" alt="확인" /></a></li>
					<? } ?>
				<? }?>
			</ul>
		</div>
		
		<div class="expArea">
			<ul class="fLeft">
				<li class="total">
					* 총 <?=sizeof($arr_rs)?> 건 &nbsp;&nbsp;
					<? if ($insert_result) {?>
					* 등록건 <?=$row_cnt?> 건
					<? }?>
				</li>
			</ul>
		</div>

		<div id="temp_scroll">
		
			<table cellpadding="0" cellspacing="0" border="0" style="width:3265px">
					<colgroup>
						<col width="35">
						<col width="100">	<!-- 비고 -->
						<col width="70">	<!-- 이름 -->
						<col width="50">	<!-- 성별 -->
						<col width="50">	<!-- 나이 -->
						<col width="90">	<!-- 생년월일 -->
						<col width="120">	<!-- 주민번호 -->
						<col width="100">	<!-- 휴대전화 -->
						<col width="80">	<!-- 수신동의 -->
						<col width="100">	<!-- 자택전화 -->
						<col width="120">	<!-- 이메일 -->
						<col width="80">	<!-- 수신동의 -->
						<col width="80">	<!-- 우편번호 -->
						<col width="100">	<!-- 광역시도당 -->
						<col width="200">	<!-- 주소(시,군,구) -->
						<col width="300">	<!-- 상세주소 -->
						<col width="100">	<!-- 소속당 -->
						<col width="100">	<!-- 조직명 -->
						<col width="100">	<!-- 직업 -->
						<col width="80">	<!-- 직장명 -->
						<col width="80">	<!-- 직장 우편번호 -->
						<col width="200">	<!-- 직장 주소(시,군,구) -->
						<col width="300">	<!--  직장상세주소 -->
						<col width="150">	<!-- 소속단체 -->
						<col width="80">	<!-- 결제방식 -->
						<col width="120">	<!-- 계좌번호 -->
						<col width="100">	<!-- 은행명 -->
						<col width="80">	<!-- 당비약정액 -->
						<col width="100">	<!-- 지역위원회 -->
					</colgroup>
					<thead>
						<tr style="height:25px">
							<th>&nbsp;</th>
							<th>비고</th>
							<th>이름</th>
							<th>성별</th>
							<th>나이</th>
							<th>생년월일</th>
							<th>주민번호</th>
							<th>휴대전화</th>
							<th>수신여부</th>
							<th>자택전화</th>
							<th>이메일</th>
							<th>수신여부</th>
							<th>우편번호</th>
							<th>광역시도당</th>
							<th>주소(시,군,구)</th>
							<th>상세주소</th>
							<th>소속당</th>
							<th>조직(지역)</th>
							<th>직업</th>
							<th>직장명</th>
							<th>직장우편번호</th>
							<th>직장주소(시,군,구)</th>
							<th>직장상세주소</th>
							<th>소속단체</th>
							<th>결제방식</th>
							<th>계좌번호</th>
							<th>은행명</th>
							<th>당비약정액</th>
							<th class="end">지역위원회</th>
						</tr>
					</thead>
					<tbody>
					<?
						$nCnt = 0;
						
						if (sizeof($arr_rs) > 0) {
							
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								
								$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
								$TEMP_NO				= trim($arr_rs[$j]["TEMP_NO"]);
								$M_NAME					= trim($arr_rs[$j]["M_NAME"]);
								$M_SEX					= trim($arr_rs[$j]["M_SEX"]);
								$M_AGE					= trim($arr_rs[$j]["M_AGE"]);
								$M_BIRTH				= trim($arr_rs[$j]["M_BIRTH"]);
								$M_JUMIN				= trim($arr_rs[$j]["M_JUMIN"]);
								$M_TEL					= trim($arr_rs[$j]["M_TEL"]);
								$M_HP						= trim($arr_rs[$j]["M_HP"]);
								$M_SMS					= trim($arr_rs[$j]["M_SMS"]);
								$M_EMAIL				= trim($arr_rs[$j]["M_EMAIL"]);
								$M_MAILLING			= trim($arr_rs[$j]["M_MAILLING"]);
								$M_ZIP1					= trim($arr_rs[$j]["M_ZIP1"]);
								$M_ADDR1				= trim($arr_rs[$j]["M_ADDR1"]);
								$M_ADDR2				= trim($arr_rs[$j]["M_ADDR2"]);
								$O_ZIP1					= trim($arr_rs[$j]["O_ZIP1"]);
								$O_ADDR1				= trim($arr_rs[$j]["O_ADDR1"]);
								$O_ADDR2				= trim($arr_rs[$j]["O_ADDR2"]);
								$M_ONLINE_FLAG	= trim($arr_rs[$j]["M_ONLINE_FLAG"]);
								$SIDO						= trim($arr_rs[$j]["SIDO"]);
								$PARTY					= trim($arr_rs[$j]["PARTY"]);
								$M_ORGANIZATION	= trim($arr_rs[$j]["M_ORGANIZATION"]);
								$JOB						= trim($arr_rs[$j]["JOB"]);
								$COM_NAME				= trim($arr_rs[$j]["COM_NAME"]);
								$M_GROUP				= trim($arr_rs[$j]["M_GROUP"]);
								$PAY_TYPE				= trim($arr_rs[$j]["PAY_TYPE"]);
								$BANK_NO				= trim($arr_rs[$j]["BANK_NO"]);
								$BANK_CODE			= trim($arr_rs[$j]["BANK_CODE"]);
								$CMS_AMOUNT			= trim($arr_rs[$j]["CMS_AMOUNT"]);
								$AREA						= trim($arr_rs[$j]["AREA"]);

								$dec_m_jumin		= decrypt($key, $iv, $M_JUMIN);
								$dec_m_tel			= decrypt($key, $iv, $M_TEL);
								$dec_m_mtel			= decrypt($key, $iv, $M_HP);
								$dec_bank_no		= decrypt($key, $iv, $BANK_NO);
								$dec_bank_code	= decrypt($key, $iv, $BANK_CODE);

								// 데이터 유효성 검사
								$err_str = "정상";

								if ($M_NAME == "") {
									$err_str .=  "이름 누락,";
								}
								
								// 주민등록번호
								if ($M_JUMIN == "") {
									$err_str .=  "주민번호 누락,";
								} else {

									if (strlen($dec_m_jumin) <> 14) {
										$err_str .=  "주민번호 오류,";
									}

									$result_jumin = dupCheckMember($conn, "JUMIN", $M_JUMIN);
									if ($result_jumin == "F") {
										$err_str .=  "주민번호 증복,";
									}
								}

								if ($M_HP == "") {
									$err_str .=  "휴대번호 누락,";
								}
								
								if ($SIDO == "") {
									$err_str .=  "주소 누락,";
								} 

								if ($PAY_TYPE <> "") {
									$str_code = "";
									$str_pay_type = "";
									
									if ($PAY_TYPE == "휴대전화") {
										$str_pay_type = "mobile";

										$str_code = getDcodeCode($conn, "MOBILE_COM", $dec_bank_code);
										if ($str_code == "") {
											$err_str .=  "통신사 이름 오류 (SKT KT LGU+ 로 입력) ,";
										}

										if ($dec_bank_no == "") {
											$err_str .=  "전화번호 누락 ,";
										}

									}

									if ($PAY_TYPE == "CMS") {
										$str_pay_type = "cms";
										$str_code = getDcodeCode($conn, "BANK_CODE", $dec_bank_code);
										if ($str_code == "") {
											$err_str .=  "은행 이름 오류 ,";
										}
										if ($dec_bank_no == "") {
											$err_str .=  "계좌번호 누락 ,";
										}
									}
									
									if ($str_pay_type == "") {
										$err_str .=  "결제 방법 입력 오류 (휴대전화 CMS로 입력),";
									}
								}

								// 조직정보 가지고 오기
								//$M_ORGANIZATION = searchGroupCd($conn, $PARTY, $SIDO, $AREA);

								//if ($M_ORGANIZATION <> "") {
								//	$result = updateOrganization($conn, $M_ORGANIZATION, $SEQ_NO);
								//}

								if ($err_str <> "정상") {
									$err_str = str_replace("정상","",$err_str);
									$err_str = substr($err_str, 0, (strlen($err_str) -1));
									$err_str = str_replace(",","<div class='sp5'></div>",$err_str);
									$err_str = "<font color='red'>".$err_str."</font>";
								}


					?>
						<tr>
							<td class="filedown" style="text-align:center">
								<input type="checkbox" name="chk[]" value="<?=$SEQ_NO?>">
							</td>
							<td style="padding:8px 0 3px 3px">
								<?=$err_str?>
								<? if ($err_str == "정상") {?>
								<input type="hidden" name="ok[]" value="<?=$SEQ_NO?>">
								<? } ?>
							</td>

							<td style="text-align:center"><?=$M_NAME?></td>
							<td style="text-align:center"><?=$M_SEX?></td>
							<td style="text-align:center"><?=$M_AGE?></td>
							<td style="text-align:center"><?=$M_BIRTH?></td>
							<td style="text-align:center"><?=$dec_m_jumin?></td>
							<td style="text-align:center"><?=$dec_m_mtel?></td>
							<td style="text-align:center"><?=$M_SMS?></td>
							<td style="text-align:center"><?=$dec_m_tel?></td>
							<td style="text-align:center"><?=$M_EMAIL?></td>
							<td style="text-align:center"><?=$M_MAILLING?></td>
							<td style="text-align:center"><?=$M_ZIP1?></td>
							<td style="text-align:center"><?=$SIDO?></td>
							<td style="padding-left:10px"><?=$M_ADDR1?></td>
							<td style="padding-left:10px"><?=$M_ADDR2?></td>
							<td style="text-align:center"><?=$PARTY?></td>
							<td style="text-align:center"><?=$M_ORGANIZATION?></td>
							<td style="text-align:center"><?=$JOB?></td>
							<td style="text-align:center"><?=$COM_NAME?></td>
							<td style="text-align:center"><?=$O_ZIP1?></td>
							<td style="padding-left:10px"><?=$O_ADDR1?></td>
							<td style="padding-left:10px"><?=$O_ADDR2?></td>
							<td style="text-align:center"><?=$M_GROUP?></td>
							<td style="text-align:center"><?=$PAY_TYPE?></td>
							<td style="text-align:center"><?=$dec_bank_no?></td>
							<td style="text-align:center"><?=$dec_bank_code?></td>
							<td style="text-align:center"><?=$CMS_AMOUNT?></td>
							<td style="text-align:center"><?=$AREA?></td>
						</tr>
					<?			
										$err_str = "";
									}
								} else { 
							?> 
								<tr>
									<td align="center" height="50"  colspan="29">데이터가 없습니다. </td>
								</tr>
							<? 
								}
							?>
				</tbody>
			</table>
		</div>

		<div class="btnArea">
			<ul class="fRight">
				<input type="button" name="aa" value=" 미등록자료 엑셀받기 " style="cursor:pointer;height:25px;" onclick="js_excel();">&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="bb" value=" 정상자료 등록 " style="cursor:pointer;height:25px;" onclick="js_register();">&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="cc" value=" 선택자료 삭제 " style="cursor:pointer;height:25px;" onclick="js_delete();">
			</ul>
		</div>




				</section>

<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>