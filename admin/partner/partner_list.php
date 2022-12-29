<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : partner_list.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-11-14
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

	$menu_right = "PT001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/partner/partner.php";  

#==============================================================================
# Request Parameter
#==============================================================================
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$nPage						= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$mode						= SetStringToDB($mode);
	$nPage						= SetStringToDB($nPage);
	$nPageSize					= SetStringToDB($nPageSize);
	$nPage						= trim($nPage);
	$nPageSize					= trim($nPageSize);
	$search_field				= SetStringToDB($search_field);
	$search_str					= SetStringToDB($search_str);
	$search_field				= trim($search_field);
	$search_str					= trim($search_str);
	 
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
				$partner_no = (int)$chk[$k];			
				$result= deletePartner($conn, $s_adm_no, $partner_no);
			}
		}		 
	}

#====================================================================
# Request Parameter
#====================================================================
	if ($nPage == 0) $nPage = "1";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

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

	$nListCnt = totalCntPartner($conn, $use_tf, $del_tf, $search_field, $search_str);
							  
	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
 
	$arr_rs = listPartner($conn, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

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
		frm.action = "partner_write.php";
		frm.submit();
	}
	/*
	function js_toggle(banner_no, use_tf) {
		var frm = document.frm;

		bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
			
		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}

			frm.seq_no.value = banner_no;
			frm.use_tf.value = use_tf;
			frm.mode.value = "T";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function file_change(file) { 
		document.getElementById("file_name").value = file; 
	}
	*/
	/*
	var preid = -1;

	function js_up(n) {
		
		preid = parseInt(n);

		if (preid > 1) {
			

			temp1 = document.getElementById("t").rows[preid].innerHTML;
			temp2 = document.getElementById("t").rows[preid-1].innerHTML;

			var cells1 = document.getElementById("t").rows[preid].cells;
			var cells2 = document.getElementById("t").rows[preid-1].cells;

			for(var j=0 ; j < cells1.length; j++) {
				
				if (j != 1) {
					var temp = cells2[j].innerHTML;

					cells2[j].innerHTML =cells1[j].innerHTML;
					cells1[j].innerHTML = temp;

					var tempCode = document.frm.seq_banner_no[preid-2].value;
				
					document.frm.seq_banner_no[preid-2].value = document.frm.seq_banner_no[preid-1].value;
					document.frm.seq_banner_no[preid-1].value = tempCode;
				}
			}
			
			//preid = preid - 1;
			js_change_order();

		} else {
			alert("가장 상위에 있습니다. ");
		}
	}
	

	function js_down(n) {

		preid = parseInt(n);

		if ((preid < document.getElementById("t").rows.length-1) && (document.frm.seq_banner_no[preid].value != null)) {

		//alert(preid);
		//return;

			temp1 = document.getElementById("t").rows[preid].innerHTML;
			temp2 = document.getElementById("t").rows[preid+1].innerHTML;
			
			var cells1 = document.getElementById("t").rows[preid].cells;
			var cells2 = document.getElementById("t").rows[preid+1].cells;
			
			for(var j=0 ; j < cells1.length; j++) {

				if (j != 1) {
					var temp = cells2[j].innerHTML;

				
					cells2[j].innerHTML =cells1[j].innerHTML;
					cells1[j].innerHTML = temp;
		
					var tempCode = document.frm.seq_banner_no[preid-1].value;
					document.frm.seq_banner_no[preid-1].value = document.frm.seq_banner_no[preid].value;
					document.frm.seq_banner_no[preid].value = tempCode;
				}
			}
			
			//preid = preid + 1;
			js_change_order();
		} else{
			alert("가장 하위에 있습니다. ");
		}
	}

	function js_change_order() {
		
		if(document.getElementById("t").rows.length < 2) {
			alert("순서를 저장할 메뉴가 없습니다");//순서를 저장할 메뉴가 없습니다");
			return;
		}

		document.frm.mode.value = "O";
		document.frm.target = "ifr_hidden";
		document.frm.action = "banner_order_dml.php";
		document.frm.submit();

	}
	*/
	
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
				frm.action = "partner_list.php";
				frm.submit();
			}
		}
	}
	
	function js_view(partner_no) {
		 
		var frm = document.frm;
		
		frm.partner_no.value = partner_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "partner_read.php";
		frm.submit();
		
	}
	
	/*
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

	function js_sel_banner_lang() {
		var frm = document.frm;
		frm.action = "story_list.php";
		frm.submit();
	}
	*/
	
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
		//frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.action = "partner_list.php";
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
						<button type="button" class="btn-gray button-p" onClick="js_delete()" style="width:100px" >삭제</button>
					<? } ?>
				</h3>
				<div class="boardlist search">
<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="file_no" value="">
<input type="hidden" name="partner_no" >
<input type="hidden" name="con_use_tf" value="">
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
											파트너명
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
									<input type="text" class="txt" name="text_mode" style="display:inline-block; width:75px" style="border:0px; soild #FFF" value="파트너명" readonly="">									
									<span class="inpbox" style="display:inline-block; width:70%"><input type="text" value="<?=$search_str?>" name="search_str" size="15" class="txt" /></span>
									<button type="button" class="btn-border-white" id="btn_search" onClick="js_search();">검색</button>									
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div style="margin: -30px 0 10px 0;"></div>
				<span class="fn_gray">총 <?=$nListCnt?> 건</span>
				<div class="sp5"></div>				
				<div class="boardlist">
					<table>

						<colgroup>
							<col style="width:3%" />
							<col style="width:5%" /><!-- No. -->							
							<col style="width:5%" /><!-- 순번 -->							
							<col style="width:15%" /><!-- 파트너명 -->
							<col style="width:10%" /><!-- 등록일 -->
							<col style="width:10%" /><!-- 등록자 -->							
							<col style="width:10%" /><!-- 수정일 -->
							<col style="width:10%" /><!-- 수정자 -->														
							<col style="width:5%" /><!-- 사용여부 -->							
						</colgroup>
						<tbody>
						<tr>
							<th scope="col"><input type="checkbox" name="ch2" onClick="javascript:checkAll();" /></th>
							<th scope="col">No.</th>							
							<th scope="col">순번</th>
							<th scope="col">파트너명</th>
							<th scope="col">등록일</th>
							<th scope="col">등록자</th>
							<th scope="col">수정일</th>							
							<th scope="col">수정자</th>
							<th scope="col">사용여부</th>							 
						</tr>
	<?
		$nCnt = 0;
		
		if (sizeof($arr_rs) > 0) {			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {				
				// $rn					= trim($arr_rs[$j]["rn"]);
				$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;
				$DISP_SEQ			= trim($arr_rs[$j]["DISP_SEQ"]);
				$PARTNER_NM			= trim($arr_rs[$j]["PARTNER_NM"]);
				$REG_DATE 			= trim($arr_rs[$j]["REG_DATE"]);
				$REG_NAME 			= trim($arr_rs[$j]["REG_NAME"]);
				$UP_DATE 			= trim($arr_rs[$j]["UP_DATE"]);				
				$UP_NAME 			= trim($arr_rs[$j]["UP_NAME"]);	
				$USE_TF				= trim($arr_rs[$j]["USE_TF"]);
				$MAIN_TF			= trim($arr_rs[$j]["MAIN_TF"]);				
				$DOWN_IMG			= SetStringFromDB($arr_rs[$j]["DOWN_IMG"]); 
				$DOWN_REAL_IMG		= SetStringFromDB($arr_rs[$j]["DOWN_REAL_IMG"]); 
				$UP_IMG		= SetStringFromDB($arr_rs[$j]["OVERVIEW_IMG"]); 
				$UP_REAL_IMG	= SetStringFromDB($arr_rs[$j]["UP_REAL_IMG"]); 
				$PARTNER_NO			= trim($arr_rs[$j]["PARTNER_NO"]);
				
				if ($USE_TF == "Y") {
					$STR_USE_TF = "<font color='navy'>사용</font>";
				} else {
					$STR_USE_TF = "<font color='red'>사용안함</font>";
				}
				
				if ($MAIN_TF == "Y") {
					$STR_MAIN_TF = "<font color='navy'>노출</font>";
				} else {
					$STR_MAIN_TF = "<font color='red'>미 노출</font>";
				}

	?>
				<tr> 
					<td><input type="checkbox" name="chk[]" id="ch1" value="<?=$PARTNER_NO?>"></td>
					<td><?=$rn?></td>		
					<td><?=$DISP_SEQ?></td>					
					<td><a href="javascript:js_view('<?=$PARTNER_NO?>');"><?=$PARTNER_NM?></a></td>
					<td><?=$REG_DATE?></td>
					<td><?=$REG_NAME?></td>
					<td><?=$UP_DATE?></td>	
					<td><?=$UP_NAME?></td>					
					<td><?=$STR_USE_TF?></td>	
					
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
			</form>
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

