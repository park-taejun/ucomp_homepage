<?session_start();?>
<?
	extract($_POST);
	extract($_GET);
# =============================================================================
# File Name    : event_mem_popup.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2014.07.11
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "EV002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";
	
#====================================================================
# common_header
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/event/event.php";

#====================================================================
# Request Parameter
#====================================================================
	$mode 			= trim($mode);
	$seq_no		= trim($seq_no);
	
	$result = false;
#====================================================================
# DML Process
#====================================================================
	
	//echo $mode."<br>";
	//echo $sel_type."<br>";

	if (($mode == "Q") || ($mode == "QS")){
		
		if ($sel_type == "db") {

			$query_str_all = "";
			$query_str_age = "";
			$query_str_level = "";
				
			$query_str_all = "sel_type|".$sel_type."#chk_all_age|".$chk_all_age;

			$row_cnt = count($age);

			for ($i = 0 ; $i < $row_cnt; $i++) {
				if ($query_str_age) {
					$query_str_age = $query_str_age."%".$age[$i];
				} else {
					$query_str_age = $age[$i];
				}
				//echo "age ".$age[$i]."<br>";
			}

			$query_str_all = $query_str_all."#age|".$query_str_age;
			$query_str_all = $query_str_all."#gender|".$gender;

			$row_cnt = count($level);
			for ($i = 0 ; $i < $row_cnt; $i++) {
				if ($query_str_level) {
					$query_str_level = $query_str_level."%".$level[$i];
				} else {
					$query_str_level = $level[$i];
				}
				//echo "age ".$level[$i]."<br>";
			}

			$query_str_all = $query_str_all."#chk_all_level|".$chk_all_level;
			$query_str_all = $query_str_all."#level|".$query_str_level;
			$query_str_all = $query_str_all."#search_field|".$search_field;
			$query_str_all = $query_str_all."#search_str|".$search_str;


			//echo "query_str_all ".$query_str_all."<br>";

			if ($mode == "QS") {
				$result_qs = updateEventCondition($conn, $query_str_all, $seq_no);
			}

		} else {

#====================================================================
	$savedir1 = $g_physical_path."upload_data/event";
#====================================================================

			//echo "ssssssssss";

			//echo $temp_file_nm;
			
			//echo "FILE".$savedir1;
			if ($temp_file_nm) {
				//$result_insert_temp = insertEventTempToReal($conn, $seq_no, $temp_file_nm);
				$file_nm = $temp_file_nm;
			} else {

				$file_nm	= upload($_FILES[file_nm], $savedir1, 10000 , array('txt','TXT'));

				//echo $file_nm;

				$file_dir = $g_physical_path."upload_data/event/".$file_nm;
			
				//echo "file_dir ".$file_dir;

				$fo = fopen($file_dir, "r");
				$number_id = 0;

				while($str = fgets($fo, 3000)){

					//$str = iconv("EUC-KR","UTF-8",$str);

					$a_str = explode("	",$str);

					$mem_nm					= trim($a_str[0]);
					$mem_hp					= trim($a_str[1]);
				
					//echo $mem_id."<br>";
					//echo $mem_nm."<br>";

					if ($number_id != 0) {
						$result_file = insertEventTempFile($conn, $file_nm, $mem_nm, $mem_hp);
					}
					$number_id++;
				}
			}
		}
		
		$arr_rs_mem = getEventMember($conn, $seq_no, $sel_type, $query_str_all, $file_nm, $mode);

	}

	if ($mode == "S") {

		$arr_rs = selectEvent($conn, $seq_no);

		$rs_condition_str	= trim($arr_rs[0]["CONDITION_STR"]); 
		$rs_use_tf				= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf				= trim($arr_rs[0]["DEL_TF"]); 
		
		//echo $rs_condition_str;

		$query_str_all = $rs_condition_str;

		// 조건식으로 화면 구성하기 위한 부분입니다.
		$arr_query_str = explode("#",$query_str_all);

		// 대상등록 방법 
		// sel_type
		$arr_sel_type = explode("|",$arr_query_str[0]);
		$sel_type = $arr_sel_type[1];

		$arr_chk_all_age = explode("|",$arr_query_str[1]);
		$chk_all_age = $arr_chk_all_age[1];

		$arr_age = explode("|",$arr_query_str[2]);
		$query_str_age = $arr_age[1];
		
		$arr_gender = explode("|",$arr_query_str[3]);
		$gender = $arr_gender[1];

		$arr_chk_all_level = explode("|",$arr_query_str[4]);
		$chk_all_level = $arr_chk_all_level[1];

		$arr_level = explode("|",$arr_query_str[5]);
		$query_str_level = $arr_level[1];

		$arr_search_field = explode("|",$arr_query_str[6]);
		$search_field = $arr_search_field[1];

		$arr_search_str = explode("|",$arr_query_str[7]);
		$search_str = $arr_search_str[1];
		
		$arr_rs_mem = listEventMem($conn, $seq_no, $sel_type);

		if ($sel_type <> "db") $sel_type = "file";
	}


	if ($result) {
?>	
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		opener.js_search();
		self.close();
</script>
<?
		exit;
	}	
?>

<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<!--<script type="text/javascript" src="../js/httpRequest.js"></script>--> <!-- Ajax js -->

<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  height: 220; background-color:#f7f7f7; overflow: auto; height: 325px; border:1px solid #d1d1d1;}
-->
</style>
<script language="javascript">

$(document).ready(function() {
	$(".date").datepicker({
		dateFormat: "yy-mm-dd"
		,minDate: new Date(2000, 4-1, 15)	//(연, 월-1, 일)
	//,maxDate: new Date(2012, 9-1, 14)	//(연, 월-1, 일)
	});
});

	// 저장 버튼 클릭 시 
	function js_save() {
		var frm = document.frm;
		
		frm.mode.value = "QS";

		frm.method = "post";
		frm.action = "event_mem_popup.php";
		frm.submit();
	}

	function js_delete() {
		
		bDelOK = confirm('정말 삭제 하시겠습니까?');//정말 삭제 하시겠습니까?
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.method = "post";
			frm.action = "pcode_write_popup.php";
			frm.submit();
		} else {
			return;
		}
	}


	function js_sel_type() {

		if ($("#sel_type").val() == "db") {
			$("#in_db").show();
			$("#in_file").hide();
		} else if ($("#sel_type").val() == "file") {
			$("#in_db").hide();
			$("#in_file").show();
		} else {
			$("#in_db").hide();
			$("#in_file").hide();
		}
	}

	function js_sel_all(obj) {
		
		var frm = document.frm;

		if (obj == "age") {
			var chk_obj = frm['age[]'];
			var chk_all_obj = frm.age_all;
			var chk_all = frm.chk_all_age;
		}

		if (obj == "level") {
			var chk_obj = frm['level[]'];
			var chk_all_obj = frm.level_all;
			var chk_all = frm.chk_all_level;
		}

		if (chk_all_obj.checked == true) {
			for (i=0; i < chk_obj.length; i++) {
				chk_obj[i].checked = true;
			}
			chk_all.value = "all";
		} else {
			for (i=0; i < chk_obj.length; i++) {
				chk_obj[i].checked = false;
			}
			chk_all.value = "";
		}

	}

	function js_search() {

		var frm = document.frm;
		frm.action = "event_mem_popup.php";
		frm.mode.value = "Q";
		frm.submit();

	}

</script>

</head>
<body id="popup_stock">
<form name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" >
<input type="hidden" name="seq_no" value="<?= $seq_no ?>">
<input type="hidden" name="condition_str" value="<?= $query_str_all?>">

<div id="popupwrap_stock">
	<h1>설문 대상 선택</h1>
	<div id="postsch">
		<h2>* 설문 대상을 선택 합니다.</h2>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
			<colgroup>
				<col width="20%">
				<col width="80%">
			</colgroup>
			<tr>
				<th class="lpd20 left bu03">대상등록 방법</th>
				<td colspan="3" class="lpd20 rpd20 right">
					<select name="sel_type" id="sel_type" onChange="js_sel_type();">
						<option value="">대상등록 방법을 선택 하세요</option>
						<option value="db" <? if ($sel_type == "db") echo "selected"; ?> >회원 DB 사용</option>
						<option value="file" <? if ($sel_type == "file") echo "selected"; ?>>파일로 업로드</option>
					</select>
				</td>
			</tr>
		</table>
		<div class="sp20"></div>
		<div id="in_db" <? if ($sel_type != "db") { ?> style="display:none" <? } ?>>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
				<colgroup>
					<col width="10%">
					<col width="40%">
					<col width="10%">
					<col width="40%">
				</colgroup>
				<tr>
					<th class="lpd20 left bu03">연령</th>
					<td colspan="3" class="lpd20 rpd20 right">
						<input type="hidden" name="chk_all_age" value="<?=$chk_all_age?>">
						<input type="checkbox" name="age_all" value="all" onClick="js_sel_all('age')" <? if ($chk_all_age == "all") echo "checked" ?> > 전체연령&nbsp;&nbsp;
						<input type="checkbox" name="age[]" value="20" <? if (strpos($query_str_age, "20") !== false) echo "checked"?>> 20대&nbsp;&nbsp;
						<input type="checkbox" name="age[]" value="30" <? if (strpos($query_str_age, "30") !== false) echo "checked"?>> 30대&nbsp;&nbsp;
						<input type="checkbox" name="age[]" value="40" <? if (strpos($query_str_age, "40") !== false) echo "checked"?>> 40대&nbsp;&nbsp;
						<input type="checkbox" name="age[]" value="50" <? if (strpos($query_str_age, "50") !== false) echo "checked"?>> 50대&nbsp;&nbsp;
						<input type="checkbox" name="age[]" value="60" <? if (strpos($query_str_age, "60") !== false) echo "checked"?>> 60대&nbsp;&nbsp;
						<input type="checkbox" name="age[]" value="70" <? if (strpos($query_str_age, "70") !== false) echo "checked"?>> 70대 이상&nbsp;&nbsp;
					</td>
				</tr>
				<tr>
					<th class="lpd20 left bu03">성별</th>
					<td colspan="3" class="lpd20 rpd20 right">
						<input type="radio" name="gender" value="all" <? if (($gender == "all") || ($gender == ""))  echo "checked" ?> > 전체&nbsp;&nbsp;
						<input type="radio" name="gender" value="M" <? if ($gender == "M") echo "checked" ?>> 남&nbsp;&nbsp;
						<input type="radio" name="gender" value="F" <? if ($gender == "F") echo "checked" ?>> 여&nbsp;&nbsp;
					</td>
				</tr>
				<tr>
					<th class="lpd20 left">회원등급</th>
					<td colspan="3" class="lpd20 rpd20 right">
						<input type="hidden" name="chk_all_level" value="<?=$chk_all_level?>">
						<input type="checkbox" name="level_all" value="all" onClick="js_sel_all('level')" <? if ($chk_all_level == "all") echo "checked" ?> > 전체&nbsp;&nbsp;
						<input type="checkbox" name="level[]" value="1" <? if (strpos($query_str_level, "1") !== false) echo "checked"?>> 1&nbsp;&nbsp;
						<input type="checkbox" name="level[]" value="2" <? if (strpos($query_str_level, "2") !== false) echo "checked"?>> 2&nbsp;&nbsp;
						<input type="checkbox" name="level[]" value="3" <? if (strpos($query_str_level, "3") !== false) echo "checked"?>> 3&nbsp;&nbsp;
						<input type="checkbox" name="level[]" value="4" <? if (strpos($query_str_level, "4") !== false) echo "checked"?>> 4&nbsp;&nbsp;
						<input type="checkbox" name="level[]" value="5" <? if (strpos($query_str_level, "5") !== false) echo "checked"?>> 5&nbsp;&nbsp;
						<input type="checkbox" name="level[]" value="6" <? if (strpos($query_str_level, "6") !== false) echo "checked"?>> 6&nbsp;&nbsp;
						<input type="checkbox" name="level[]" value="7" <? if (strpos($query_str_level, "7") !== false) echo "checked"?>> 7&nbsp;&nbsp;
						<input type="checkbox" name="level[]" value="8" <? if (strpos($query_str_level, "8") !== false) echo "checked"?>> 8&nbsp;&nbsp;
						<input type="checkbox" name="level[]" value="9" <? if (strpos($query_str_level, "9") !== false) echo "checked"?>> 9&nbsp;&nbsp;
					</td>
				</tr>

				<tr>
					<th>검색조건</th>
					<td colspan="3" class="lpd20 rpd20 right">
						<select name="search_field" style="width:84px;">
							<option value="M_NAME" <? if ($search_field == "M_NAME") echo "selected"; ?> >이름</option>
							<option value="M_ID" <? if ($search_field == "M_ID") echo "selected"; ?> >아이디</option>
							<option value="M_NICK" <? if ($search_field == "M_NICK") echo "selected"; ?> >닉네임</option>
							<option value="M_HP" <? if ($search_field == "M_HP") echo "selected"; ?> >휴대전화번호</option>
							<!--<option value="" <? if ($search_field == "A.ADDR1") echo "selected"; ?> >주소</option>-->
						</select>&nbsp;
						<input type="text" value="<?=$search_str?>" name="search_str" size="25" />
						<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="go"/></a>
					</td>
				</tr>
			</table>
		</div>

		<div id="in_file"  <? if ($sel_type != "file") { ?> style="display:none" <? } ?>>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
				<colgroup>
					<col width="20%">
					<col width="80%">
				</colgroup>
					<tr>
						<th class="lpd20 left bu03">파일선택</th>
						<td colspan="3" class="lpd20 rpd20 right">
							<input type="file" name="file_nm" value="" class="txt" size="40%">
							<input type="hidden" name="temp_file_nm" value="<?=$file_nm?>">
							<div class="sp10"></div>
							탭으로 분리된 txt 로 저장 후 등록 <input type="button" name="btn" value=" 업로드 " class="btntxt" onclick="js_search();"><br>
							<div class="sp5"></div>
							<a href="example.xls"><b>등록용 엑셀 예제 파일 받기</b></a> &nbsp;&nbsp;
							<a href="<?=$g_base_dir?>/_common/path_download_file.php?str_path=upload_data/&file_name=example.txt"><b>등록용 TXT 예제 파일 받기</b></a> 
						</td>
					</tr>
			</table>
		</div>

		<div class="sp30"></div>

		<div class="addr_inp">	
		<table cellpadding="0" cellspacing="0" border="0" width="95%">
		<tr>
			<td width="100%" align="left">
				<div id="pop_table_list">
					<div id="pop_table_scroll">
						<table id='t' cellpadding="0" class="rowstable" cellspacing="0" border="0" width="100%">
							<colgroup>
								<col width="10%">
								<col width="25%">
								<col width="25%">
								<col width="20%">
								<col width="20%">
							</colgroup>

							<thead>
								<tr>
									<th scope="col">NO.</th>
									<th scope="col">이름</th>
									<th scope="col">휴대전화번호</th>
									<th scope="col">성별</th>
									<th class="end" scope="col">등록일</th>
								</tr>
							</thead>
							<tbody>
							<?
								$nCnt = 0;

								if ($mode == "QS") {
									$result_del = deleteEventMem($conn, $seq_no);
								}
								
								if (sizeof($arr_rs_mem) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs_mem); $j++) {
										
										#rn, DCODE_NO, PCODE, DCODE, DCODE_NM, DCODE_SEQ_NO, 
										#USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE

										$rn							= sizeof($arr_rs_mem) - $j;
										$MEM_ID					= trim($arr_rs_mem[$j]["M_ID"]);
										$MEM_NM					= trim($arr_rs_mem[$j]["M_NAME"]);
										$MEM_SEX				= trim($arr_rs_mem[$j]["M_SEX"]);
										$M_HP						= trim($arr_rs_mem[$j]["M_HP"]);
										$REG_DATE				= trim($arr_rs_mem[$j]["M_DATETIME"]);


										$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
										
										if ($UP_DATE != "") {
											$UP_DATE = date("Y-m-d",strtotime($UP_DATE));
										}
										
										if ($mode == "QS") {
											$result = insertEventMem($conn, $seq_no, $MEM_ID, $MEM_NM, $M_HP);
										}
							?>
								<tr>
									<td class="sort"><span><?=$rn?></span></td>
									<td><?=$MEM_NM?></td>
									<td><?=$M_HP?></td>
									<td><?=$MEM_SEX?></td>
									<td><?=$REG_DATE?></td>
								</tr>
							<?			
									}
								} else { 
							?> 
								<tr>
									<td height="50" align="center" colspan="5">데이터가 없습니다. </td>
								</tr>
							<? 
								}
							?>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</table>
		</div>


		<? if (sizeof($arr_rs_mem) > 0) { ?>
		<div class="btn">
			<a href="javascript:js_save();"><img src="../images/admin/btn_regist_02.gif" alt="등록" /></a>
		</div>
		<? } ?>

	</div>
	<!--
	<div class="bot_close"><a href="javascript: window.close();"><img src="../images/admin/icon_pclose.gif" alt="닫기" /></a></div>
	-->
</div>
<? if ($mode == "QS") { ?>
<script>
	opener.js_reload();
</script>
<? } ?>

</form>
</body>
</html>
<?
//echo "query_str_all ".$query_str_all."<br>";
//echo $query_str_sido."<br>";
//echo "cnt ".strlen($arr_sido[0]);
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>