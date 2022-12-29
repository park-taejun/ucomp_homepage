<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : team_list.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-12-19
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================

	$menu_right = "CO004"; // 메뉴마다 셋팅 해 주어야 합니다

	//echo $menu_right;

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
	require "../../_classes/biz/team/team.php";  

#==============================================================================
# Request Parameter
#==============================================================================
	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	
	$nPage				= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize			= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field		= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str			= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	
	$mode				= SetStringToDB($mode);
	
	$nPage				= SetStringToDB($nPage);
	$nPageSize			= SetStringToDB($nPageSize);
	
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);
	
	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	 
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
			
				$result= deleteTeam($conn, $s_adm_no, $tmp_file_no);

			}
		}
		 
	}

#====================================================================
# Request Parameter
#====================================================================
	if ($nPage == 0) $nPage = "1";

	#List Parameter
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);
	 
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";
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
	if ( $mode == "L" ) {
		$use_tf = "";
	} 
	
	$nListCnt = totalCntTeam($conn, $use_tf, $del_tf, $search_field, $search_str);
							  
	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;
  
	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	 
	$arr_rs = listTeam($conn, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

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
		var frm = document.frm;
		frm.target = "";
		frm.action = "team_write.php";
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

			bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');
			 
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "team_list.php";
				frm.submit();
			}
		}
	}
	
	function js_view(team_no) {
		
		var frm = document.frm;
		
		frm.team_no.value = team_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "team_read.php";
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
 
	function checkAll() {
	
		var obj = document.frm;		 
		
		for(var i=0; i<obj.ch1.length; i++) {		
			obj.ch1[i].checked = obj.ch2.checked;
		}
	}
	
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";		
		frm.submit();
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
					<?	if ($sPageRight_I == "Y") { ?>
						<button type="button" class="btn-navy" onclick="js_write()">등록하기</button>
					<? } ?>
					
					<? if ($sPageRight_D == "Y") {?>
						<button type="button" class="btn-gray" onClick="js_delete()" style="width:100px" >삭제</button>
					<? } ?>
				</h3>
				<div class="boardlist search">
<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="team_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>"> 				
					<!--
					<table>
						<colgroup>
							<col style="width:15%" />
							<col style="width:85%" />
						</colgroup>
						<tbody>
							<tr>
								<th>검색조건</th>
								<td>
									<div class="searchbox">
										<span class="inpbox">
											팀 이름
										</span>
										<span class="inpbox"><input type="text" value="<?=$search_str?>" name="search_str" size="15" class="txt" /></span>
										<button type="button" class="btn-border-white" id="btn_search" onClick="js_search();">검색</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					-->
					<table>
						<colgroup>
							<col style="width:15%" />
							<col style="width:85%" />
						</colgroup>
						<tbody>
							<tr>
								<th class="long">검색조건</th>
								<td>
									<input type="text" class="txt" name="text_mode" style="display:inline-block; width:75px" style="border:0px; soild #FFF" value="팀 이름" readonly="">
									<!--<span class="inpbox" style="display:inline-block; width:40%"><input type="text" class="txt"  name="group_name" required/></span>-->
									<span class="inpbox" style="display:inline-block; width:70%"><input type="text" value="<?=$search_str?>" name="search_str" size="30" class="txt" /></span>
									<button type="button" class="btn-border-white" id="btn_search" onClick="js_search();">검색</button>
									
								</td>
							</tr>
						</tbody>
					</table>
</form>					
				</div>
				<div style="margin: -30px 0 10px 0;"></div>
				<span class="fn_gray">총 <?=$nListCnt?> 건</span>
				<div class="sp5"></div>
				
				<div class="boardlist"> 
					<table>
						<colgroup>
							<col style="width:3%" />
							<col style="width:3%" /><!-- No. -->
							<col style="width:3%" /><!-- 순번 -->
							<col style="width:20%" /><!-- 제목. -->							
							<col style="width:10%" /><!-- 등록일 -->
							<col style="width:10%" /><!-- 등록자 -->
							<col style="width:10%" /><!-- 수정일 -->
							<col style="width:10%" /><!-- 수정자 -->							
							<col style="width:10%" /><!-- 사용여부 -->
						</colgroup>
						<tbody>
						<tr>
							<th scope="col"><input type="checkbox" name="ch2" onClick="javascript:checkAll();" /></th>
							<th scope="col">No.</th>							
							<th scope="col">순번</th>
							<th scope="col">팀이름</th>
							<th scope="col">등록일</th>
							<th scope="col">등록자</th>							
							<th scope="col">수정일</th>
							<th scope="col">수정자</th>
							<th scope="col">사용여부</th>
						</tr>
<?
		if (sizeof($arr_rs) > 0) {			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				
				// $rn						= trim($arr_rs[$j]["rn"]);				
				$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;
				$TEAM_NO				= trim($arr_rs[$j]["TEAM_NO"]);	
				$TEAM_NM				= trim($arr_rs[$j]["TEAM_NM"]);	
				$DISP_SEQ				= trim($arr_rs[$j]["DISP_SEQ"]);
				$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
				$TEAM_IMG				= SetStringFromDB($arr_rs[$j]["TEAM_IMG"]); 
				$TEAM_REAL_IMG			= SetStringFromDB($arr_rs[$j]["TEAM_REAL_IMG"]); 				
				$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);	
				$REG_NAME				= trim($arr_rs[$j]["REG_NAME"]);	
				$UP_DATE				= trim($arr_rs[$j]["UP_DATE"]);	
				$UP_NAME				= trim($arr_rs[$j]["UP_NAME"]);		

				if ($USE_TF == "Y") {
					$STR_USE_TF = "<font color='navy'>사용</font>";
				} else {
					$STR_USE_TF = "<font color='red'>사용 안함</font>";
				}
	?>
				<tr> 
					<td><input type="checkbox" name="chk[]" id="ch1" value="<?=$TEAM_NO?>"></td>										
					<td><?=$rn?></td>
					<td><?=$DISP_SEQ?></td>
					<td><a href="javascript:js_view('<?=$TEAM_NO?>');"><?=$TEAM_NM?></a></td>					
					<td><?=$REG_DATE?></td>					
					<td><?=$REG_NAME?></td>
					<td><?=$UP_DATE?></td>
					<td><?=$UP_NAME?></td>
					<td><?=$STR_USE_TF?></td>
					<!--<td><a href="javascript:js_view('<?= $BANNER_NO ?>');"><?=$BANNER_TYPE?></a></td>-->
				</tr> 
	<?			
			}
		} else { 
	?> 
			<tr>
				<td height="50" align="center" colspan="9">데이터가 없습니다. </td>
			</tr>
	<? 
		}
	?>

						</tbody>
					</table>
				</div> 
			<div class="sp30"></div>

				<p class="paging">
				<!-- --------------------- 페이지 처리 화면 START -------------------------->
				<?
					# ==========================================================================
					#  페이징 처리
					# ==========================================================================
					if (sizeof($arr_rs) > 0) {
						#$search_field		= trim($search_field);
						#$search_str			= trim($search_str);
						$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

				?>
				<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
				<?
					}
				?>
				<!-- --------------------- 페이지 처리 화면 END -------------------------->
				</p>

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
<script>

</script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>
