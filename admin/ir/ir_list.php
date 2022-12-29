<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : ir_list.php
# Modlue       : 
# Writer       : Park Tae Jun 
# Create Date  : 2022-11-30
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================	
	$menu_right = "IR001"; // 메뉴마다 셋팅 해 주어야 합니다
	
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
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/brochure/brochure.php";
	
	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];	
	$mode				= SetStringToDB($mode);
	
	if ($mode == "D") { 		
		// 삭제 권한 관련 입니다.
		$del_ok = "N";
		  
		if ($_SESSION['s_adm_no'] && $arr_page_nm[1] == "admin") {
			if ($sPageRight_D == "Y") {
				$del_ok = "Y";
			}
		}
		 
		if ($del_ok == "Y") {
			$row_cnt = count($chk);
			
			for ($k = 0; $k < $row_cnt; $k++) {				
				$tmp_file_no = (int)$chk[$k];								
				$result= deleteBrochure($conn, $s_adm_no, $tmp_file_no);
			}		
		}
	}
	
#====================================================================
# Request Parameter
#====================================================================
	
	$nPage					= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize				= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	
	#List Parameter
	$nPage					= trim($nPage);
	$nPageSize				= trim($nPageSize);

	$search_field			= trim($search_field);
	$search_str				= trim($search_str);
	
	$del_tf = "N";
	 
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
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt = totalCntBrochure($conn);

	$nTotalPage = (int)(($nListCnt - 1) / (int)$nPageSize + 1) ;
	 
	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listBrochure($conn, $nPage, $nPageSize, $nListCnt);
	
	    

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script language="javascript">

	function js_write() {
		var frm = document.frm;
		frm.target = "";
		frm.action = "ir_write.php";
		frm.submit();
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

			bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까????');
			  
			if (bDelOK==true) {
				frm.mode.value = "D";
				 
				frm.target = "";
				// frm.action = "<?=$_SERVER[PHP_SELF]?>";				
				frm.action = "ir_list.php";
				frm.submit();
			}
		}
	}

	function js_view(file_no) {
		
		var frm = document.frm;
		
		frm.file_no.value = file_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "ir_write.php";
		frm.submit();
		
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";			
		frm.submit();
	}
	
	function checkAll() {	
		var obj = document.frm;
		 
		for(var i=0; i<obj.ch1.length; i++) {		
			obj.ch1[i].checked = obj.ch2.checked;
		}
	}

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
					<? if ($sPageRight_I == "Y") { ?>
						<button type="button" class="btn-navy" id="btn_write" onclick="js_write()">등록하기</button>
					<? } ?>
					<? if ($sPageRight_D == "Y") {?>
						<button type="button" class="btn-gray" onClick="js_delete()" style="width:100px" >삭제</button>
					<? } ?>
				</h3>

<form name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="file_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<!--<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">-->				
				<span class="fn_gray">총 <?=$nListCnt?> 건</span>			
				<div class="boardlist">
					<table>
						<colgroup>
							<col style="width:3%" />
							<col style="width:5%" /><!-- No. -->
							<col style="width:20%" /><!-- 파일명 -->
							<col style="width:20%" /><!-- 등록일 -->
							<col style="width:12%" /><!-- 등록자 -->
							<col style="width:20%" /><!-- 수정일 -->
							<col style="width:12%" /><!-- 수정자 -->
							<col style="width:20%" /><!-- 조회수 -->
							<col style="width:12%" /><!-- 사용여부 -->
						</colgroup>
						<thead>
							<tr>
								<th scope="col"><input type="checkbox" name="ch2" onClick="javascript:checkAll();" /></th></th>
								<th scope="col">No.</th>
								<th scope="col">파일명</th>
								<th scope="col">등록일</th>
								<th scope="col">등록자</th>
								<th scope="col">수정일</th>
								<th scope="col">수정자</th>
								<th scope="col">조회수</th>
								<th scope="col">사용여부</th>
							</tr>
						</thead>
						<tbody>
						<?
							
							if (sizeof($arr_rs) > 0) {							
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								
									$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;
									$FILE_NO				= trim($arr_rs[$j]["FILE_NO"]);
									$FILE_NM				= trim($arr_rs[$j]["FILE_NM"]);
									$FILE_RNM				= trim($arr_rs[$j]["FILE_RNM"]);
									$HIT_CNT				= trim($arr_rs[$j]["HIT_CNT"]);
									$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
									$MAIN_TF				= trim($arr_rs[$j]["MAIN_TF"]);
									$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
									$REG_NAME				= trim($arr_rs[$j]["REG_NAME"]);
									$UP_DATE				= trim($arr_rs[$j]["UP_DATE"]);
									$UP_NAME				= trim($arr_rs[$j]["UP_NAME"]);				
									$REG_DATE				= date("Y-m-d H:i",strtotime($REG_DATE));
									$UP_DATE				= date("Y-m-d H:i",strtotime($UP_DATE));
									
									if ($DEL_TF == "N") {
										$STR_USE_TF = "<font color='navy'>사용</font>";
									} else {
										$STR_USE_TF = "<font color='red'>사용안함</font>";
									}
					
							?>  
							<tr> 
								<td><input type="checkbox" name="chk[]" id="ch1" value="<?=$FILE_NO?>"></td>
						
								<td><a href="javascript:js_view('<?=$FILE_NO?>');"><?=$rn?></a></td>								
								<td><a href="javascript:js_view('<?=$FILE_NO?>');"><?=$FILE_RNM ?></a></td>
								<td><?=$REG_DATE?></td>
								<td><?=$REG_NAME?></td>
								<td><?=$UP_DATE?></td>
								<td><?=$UP_NAME?></td>
								<td><?=number_format($HIT_CNT)?></td>
								<td><?=$STR_USE_TF?></td>
								
							</tr>
							<?	
								}
							} else { 
							?> 
								<tr>
									<td align="center" height="50"  colspan="10">데이터가 없습니다. </td>
								</tr>
							<? 
							}
							?>
						</tbody>
					</table>
				</div>
				</form>	 
				<p class="paging">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리  
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
				</p>
			<!-- // E: mwidthwrap -->


			</div>
		</div>
	</div>
	<!-- //E: container -->

	<!-- S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>
<script type="text/javascript" src="/admin/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>

