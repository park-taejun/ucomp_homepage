<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : portfolio_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-03-16
# Modify Date  : 2022-11-30
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

	$menu_right = "CO002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/portfolio/portfolio.php";

#==============================================================================
# Request Parameter
#==============================================================================
	$mode					= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$con_p_yyyy				= $_POST['con_p_yyyy']!=''?$_POST['con_p_yyyy']:$_GET['con_p_yyyy'];
	$con_p_mm				= $_POST['con_p_mm']!=''?$_POST['con_p_mm']:$_GET['con_p_mm'];
	$con_p_category			= $_POST['con_p_category']!=''?$_POST['con_p_category']:$_GET['con_p_category'];
	$con_p_type				= $_POST['con_p_type']!=''?$_POST['con_p_type']:$_GET['con_p_type'];
	$nPage					= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize				= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field			= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str				= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$mode					= SetStringToDB($mode);
	$nPage					= SetStringToDB($nPage);
	$nPageSize				= SetStringToDB($nPageSize);
	$nPage					= trim($nPage);
	$nPageSize				= trim($nPageSize);
	$search_field			= SetStringToDB($search_field);
	$search_str				= SetStringToDB($search_str);
	$search_field			= trim($search_field);
	$search_str				= trim($search_str);
	
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
				$tmp_p_no = (int)$chk[$k];

				$result= deletePortfolio($conn, $s_adm_no, $tmp_p_no);

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

	$nListCnt = totalCntPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

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
		frm.action = "portfolio_write.php";
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
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
			}
		}
	}

	function js_view(p_no) {

		var frm = document.frm;
		
		frm.p_no.value = p_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "portfolio_write.php";
		frm.submit();
		
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "portfolio_list.php";
		frm.submit();
	}

	function js_toggle(p_no, use_tf) {
		
		var frm = document.frm;

		bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
		
		if (bDelOK==true) {

			if (use_tf == "Y") {
				con_use_tf = "N";
			} else {
				con_use_tf = "Y";
			}

			frm.p_no.value = p_no;
			frm.con_use_tf.value = con_use_tf;
			frm.mode.value = "T";
			frm.target = "";
			frm.action = "portfolio_list.php";
			frm.submit();
		}
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
					<?	if ($sPageRight_I == "Y") { ?><button type="button" class="btn-navy" onclick="js_write()">등록하기</button><? } ?>
				</h3>

<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="p_no" value="">
<input type="hidden" name="con_use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<div class="boardlist search">

					<table>
						<tbody>
							<tr>
								<th>검색조건</th>
								<td>
									<div class="searchbox">

										<span class="optionbox" style="width:114px;">
										<?= makeSelectBox($conn,"PCATEGORY","con_p_category","","구분전체","",$con_p_category) ?>
										</span>
										<span class="optionbox">
										<?= makeSelectBox($conn,"YYYY","con_p_yyyy","","년도전체","",$con_p_yyyy) ?>
										</span>
										<span class="optionbox">
										<?= makeSelectBox($conn,"MONTH","con_p_mm","","월전체","",$con_p_yyyy) ?>
										</span>

										<span class="optionbox" style="width:114px;">
											<select name="search_field" >
												<option value="P_NAME01" <? if ($search_field == "P_NAME01") echo "selected"; ?> >프로젝트명</option>
												<option value="P_CLIENT" <? if ($search_field == "P_CLIENT") echo "selected"; ?> >고객사</option>
											</select>
										</span>
										<span class="inpbox"><input type="text" value="<?=$search_str?>" name="search_str" size="15" class="txt" /></span>
										<button type="button" class="btn-border-white" id="btn_search" onClick="js_search();">검색</button>
									</div>

								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="btnright">
					<? if ($sPageRight_D == "Y") {?>
						<button type="button" class="btn-gray" onClick="js_delete()" style="width:100px" >삭제</button>
					<? } ?>
				</div>
				<div style="margin: -30px 0 10px 0;"></div>
				<span class="fn_gray">총 <?=$nListCnt?> 건</span>
				<div class="sp5"></div>
				
				<div class="boardlist">

					<table>

						<colgroup>
							<col style="width:3%" />
							<col style="width:5%" /><!-- No. -->
							<col style="width:20%" /><!-- 프로젝트명 -->
							<col style="width:10%" /><!-- 수행년월 -->
							<col style="width:12%" /><!-- 구분 -->
							<col style="width:10%" /><!-- 고객명 -->
							<col style="width:10%" /><!-- 등록일 -->
							<col style="width:3%" /><!-- 등록자 -->
							<col style="width:10%" /><!-- 수정일 -->
							<col style="width:5%" /><!-- 수정자 -->
							<col style="width:5%" /><!-- 조회수 -->
							<col style="width:5%" /><!-- 사용여부 -->
							
							
						</colgroup>
						<tbody>
						<tr>
							<th scope="col"><input type="checkbox" name="ch2" onClick="javascript:checkAll();" /></th>
							<th scope="col">No.</th>
							<th scope="col">프로젝트명</th>
							<th scope="col">수행년월일</th>
							<th scope="col">구분</th>
							<th scope="col">고객명</th>
							<th scope="col">등록일</th>
							<th scope="col">등록자</th>
							<th scope="col">수정일</th>
							<th scope="col">수정자</th>
							<th scope="col">조회수</th>
							<th scope="col">사용여부</th>
							
							
						</tr>
	<?
		$nCnt = 0;
		
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				
				// $rn						= trim($arr_rs[$j]["rn"]);
				$rn = $nListCnt - (($nPage-1) * $nPageSize) - $j;
				$P_NO					= trim($arr_rs[$j]["P_NO"]);
				$P_NAME01				= trim($arr_rs[$j]["P_NAME01"]);
				$P_NAME02				= SetStringFromDB($arr_rs[$j]["P_NAME02"]);
				$P_YYYY					= trim($arr_rs[$j]["P_YYYY"]);
				$P_MM					= trim($arr_rs[$j]["P_MM"]);
				$P_CATEGORY				= trim($arr_rs[$j]["P_CATEGORY"]);
				$P_CLIENT				= SetStringFromDB($arr_rs[$j]["P_CLIENT"]);
				$P_IMG01				= trim($arr_rs[$j]["P_IMG01"]);
				$P_IMG02				= trim($arr_rs[$j]["P_IMG02"]);
				$P_IMG03				= trim($arr_rs[$j]["P_IMG03"]);
				$P_IMG04				= trim($arr_rs[$j]["P_IMG04"]);
				$HIT_CNT				= trim($arr_rs[$j]["HIT_CNT"]);
				$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
				$MAIN_TF				= trim($arr_rs[$j]["MAIN_TF"]);
				$TOP_TF					= trim($arr_rs[$j]["TOP_TF"]);
				$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
				$REG_NAME				= trim($arr_rs[$j]["REG_NAME"]);
				$UP_DATE				= trim($arr_rs[$j]["UP_DATE"]);				
				$UP_NAME				= trim($arr_rs[$j]["UP_NAME"]);				
				$REG_DATE				= date("Y-m-d H:i",strtotime($REG_DATE));
				$UP_DATE				= date("Y-m-d H:i",strtotime($UP_DATE));

				if ($USE_TF == "Y") {
					$STR_USE_TF = "<font color='navy'>사용</font>";
				} else {
					$STR_USE_TF = "<font color='red'>사용 안함</font>";  
				}

				if ($MAIN_TF == "Y") {
					$STR_MAIN_TF = "<font color='navy'>메인</font>";
				} else {
					$STR_MAIN_TF = "<font color='red'>&nbsp;</font>";
				}

				if ($TOP_TF == "Y") {
					$STR_TOP_TF = "<font color='navy'>썸네일</font>";
				} else {
					$STR_TOP_TF = "<font color='red'>&nbsp;</font>";
				}

				$arr_p_category = explode("||", $P_CATEGORY);
				$str_p_category = "";
		
				for ($h = 0 ; $h < sizeof($arr_p_category) ; $h++) {
					if ($str_p_category == "") { 
						$str_p_category = getDcodeName($conn, "PCATEGORY", str_replace("|","", $arr_p_category[$h]));
					} else {
						$str_p_category = $str_p_category .",".getDcodeName($conn, "PCATEGORY", str_replace("|","", $arr_p_category[$h]));
					}
				}

	?>
		<tr> 
			<td><input type="checkbox" name="chk[]" id="ch1" value="<?=$P_NO?>"></td>
			<td><a href="javascript:js_view('<?=$P_NO?>');"><?= $rn ?></a></td>
			<td style="text-align:left"><a href="javascript:js_view('<?=$P_NO?>');"><?= $P_NAME02 ?></a></td>
			<td><?=$P_YYYY?>.<?=$P_MM?></td>
			<td><?=$str_p_category?></td>
			<td><?=$P_CLIENT?></td>
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
			<td height="50" align="center" colspan="12">데이터가 없습니다. </td>
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
						$strParam = $strParam."&nPageSize=".$nPageSize."&con_cate_01=".$con_cate_01."&b_code=".$b_code."&search_field=".$search_field."&search_str=".$search_str;

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