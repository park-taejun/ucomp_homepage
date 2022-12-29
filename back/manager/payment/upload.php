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
	$menu_right = "PM003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/payment/payment.php";

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

		for ($i = 2 ; $i <= $maxRow ; $i++) {

			$m_name			= trim($objWorksheet->getCell('A' . $i)->getValue());
			$m_birth		= trim($objWorksheet->getCell('B' . $i)->getValue());
			$m_mtel			= trim($objWorksheet->getCell('C' . $i)->getValue());
			$amount			= trim($objWorksheet->getCell('D' . $i)->getValue());

			//$m_birth	= $m_birth;
			$en_m_tel				=  encrypt($key, $iv, $m_mtel);

			$arr_data = array("M_NAME"=>$m_name,
												"P_SEQ_NO"=>$p_no,
												"TEMP_NO"=>$file_nm,
												"M_BIRTH"=>$m_birth,
												"M_HP"=>$en_m_tel,
												"AMOUNT"=>$amount);

			$result =  insertTempSpeMember($conn, $arr_data);

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
	location.href =  'upload.php?mode=L&p_no=<?=$p_no?>&temp_no=<?=$file_nm?>';
</script>
<?
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

		$insert_result = insertTempToRealSpeMember($conn, $temp_no, $str_seq_no, $key, $iv);

		if ($insert_result) {
			$delete_result = deleteTempToRealSpeMember($conn, $temp_no, $str_seq_no);
		}

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "특별당비대상 대량 등록", "Insert");

		$mode = "L";

	}

	if ($mode == "D") {

		$row_cnt = count($chk);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_seq_no = $chk[$k];

			$temp_result = deleteSpeMemberTemp($conn, $temp_no, $tmp_seq_no);
		}
		
		$mode = "L";
	}

	if ($mode == "L") {

		$arr_rs = listSpeMemberTemp($conn, $p_no, $temp_no);
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
			frm.action = "upload.php";
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
		frm.action = "special_upload_file_excel.php";
		frm.submit();

		//alert("자료 출력");
	}

	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.action = "specialparty_list.php";
		frm.submit();
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
<input type="hidden" name="p_no" value="<?=$p_no?>">
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
				<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
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
		
			<table cellpadding="0" cellspacing="0" border="0" style="width:1000px">
					<colgroup>
						<col width="10">
						<col width="100">	<!-- 비고 -->
						<col width="70">	<!-- 이름 -->
						<col width="90">	<!-- 생년월일 -->
						<col width="100">	<!-- 휴대전화 -->
						<col width="80">	<!-- 특별당비금액 -->
					</colgroup>
					<thead>
						<tr style="height:25px">
							<th>&nbsp;</th>
							<th>비고</th>
							<th>이름</th>
							<th>생년월일</th>
							<th>휴대전화</th>
							<th class="end">특별당비금액</th>
						</tr>
					</thead>
					<tbody>
					<?
						$nCnt = 0;
						
						if (sizeof($arr_rs) > 0) {
							
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								
								$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
								$P_SEQ_NO				= trim($arr_rs[$j]["P_SEQ_NO"]);
								$M_NAME					= trim($arr_rs[$j]["M_NAME"]);
								$M_BIRTH				= trim($arr_rs[$j]["M_BIRTH"]);
								$M_HP						= trim($arr_rs[$j]["M_HP"]);
								$AMOUNT					= trim($arr_rs[$j]["AMOUNT"]);

								$dec_m_mtel			= decrypt($key, $iv, $M_HP);
								

								// 데이터 유효성 검사
								$err_str = "정상";

								if ($M_NAME == "") {
									$err_str .=  "이름 누락,";
								}

								if ($M_BIRTH == "") {
									$err_str .=  "생년월일 누락,";
								}

								if ($M_HP == "") {
									$err_str .=  "휴대번호 누락,";
								}

								if ($AMOUNT == "") {
									$err_str .=  "특별당비 누락,";
								}
								
								if (($M_NAME != "")||($M_BIRTH != "")||($M_HP != "")) {
									
									$result_jumin = SpedupCheckMember($conn, $M_NAME, $M_BIRTH, $M_HP, $p_no);
									if ($result_jumin == "F") {
										$err_str .=  "중복사용자,";
									}

									$result_jumin = SpedupCheckMembertable($conn, $M_NAME, $M_BIRTH, $M_HP);
									if ($result_jumin == "F") {
										$err_str .=  "당원데이터 불일치,";
									}

									$result_jumin = SpedupCheckMemberRegCms($conn, $M_NAME, $M_BIRTH, $M_HP);
									if ($result_jumin == "F") {
										$err_str .=  "CMS 미등록 당원,";
									}

								}

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
							<td style="text-align:center"><?=$M_BIRTH?></td>
							<td style="text-align:center"><?=$dec_m_mtel?></td>
							<td style="text-align:right"><?=number_format($AMOUNT)?> 원</td>

						</tr>
					<?			
										$err_str = "";
									}
								} else { 
							?> 
								<tr>
									<td align="center" height="50"  colspan="25">데이터가 없습니다. </td>
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