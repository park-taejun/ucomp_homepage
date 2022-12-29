<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

# =============================================================================
# File Name    : customer_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-07-13
# Modify Date  : 
#	Copyright    : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	if ($_SERVER["REMOTE_ADDR"] <> "182.208.250.10") {
?>
<meta http-equiv='Refresh' content='0; URL=/'>
<?
		exit;
	}



	$conn = db_connection("w");

#==============================================================================
# Request Parameter
#==============================================================================

	$bb_code = trim($bb_code);

	if ($bb_code == "")
		$bb_code = "CU002";

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = $bb_code; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/customer/customer.php";
	require "../../_classes/biz/admin/admin.php";

	$config_no = "";

	$arr_bb_code = explode("_", $bb_code);
	
	for ($i = 0; $i < sizeof($arr_bb_code) ; $i++) {
		$config_no = $arr_bb_code[$i];
	}


	if ($mode == "D") {

		$row_cnt = count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
			$tmp_bb_no = $chk[$k];
			$result= deleteCustomer($conn, $s_adm_no, $bb_code, $tmp_bb_no);
		}
	}

#====================================================================
# Request Parameter
#====================================================================
	if ($nPage == 0) $nPage = "1";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$con_cate_01 = trim($con_cate_01);
	$con_cate_02 = trim($con_cate_02);
	$con_cate_03 = trim($con_cate_03);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
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
		$nPageSize = 25;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntCustomer($conn, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_category, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listCustomer($conn, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_category, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

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
		frm.action = "customer_write.php";
		frm.submit();
	}

	function js_modify(){

		bDelOK = confirm('노출순서를 수정 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.mode.value = "U";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
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

	function js_view(rn, bb_code, bb_no) {

		var frm = document.frm;
		
		frm.bb_code.value = bb_code;
		frm.bb_no.value = bb_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "post";
		frm.action = "customer_write.php";
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


	function js_search2() {
		var frm = document.frm_search;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

function js_toggle(bb_code, bb_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
		
	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.bb_code.value = bb_code;
		frm.bb_no.value = bb_no;
		frm.use_tf.value = use_tf;
		frm.mode.value = "T";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}
}

function js_con_cate_01 () {
	frm.nPage.value = "1";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}

function js_con_cate_02 () {
	frm.nPage.value = "1";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}

function js_con_cate_03 () {
	frm.nPage.value = "1";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}

function js_view2(n,tot){
	
	if(document.getElementById("sub_info"+n).style.display==""){
		document.getElementById("sub_info"+n).style.display="none";
	}else{
		for(i=0;i<tot;i++){
		document.getElementById("sub_info"+i).style.display="none";
		}
		document.getElementById("sub_info"+n).style.display="";
	}

	
}

function js_con_category(){
	frm.nPage.value = "1";
	frm.target = "";
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
					<? if ($sPageRight_I == "Y") { ?><button type="button" class="btn-navy" onclick="js_write()">등록하기</button><? } ?>
				</h3>


<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="bb_no" value="">
<input type="hidden" name="bb_code" value="<?=$bb_code?>">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
<input type="hidden" name="con_cate_01" value="<?=$con_cate_01?>">
<? if($search_str != ""){ ?>
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">
<? } ?>


				<div class="boardlist search">
					<table>
						<tbody>
							<tr>
								<th>업체구분</th>
								<td>
									<span class="optionbox">
										<?=makeSelectBoxOnChange_customer($conn,"CUSTOMER_CATE","con_category","125","선택","",$con_category)?>
									</span>
								</td>
								<th>검색조건</th>
								<td>
									<div class="searchbox">
										<span class="optionbox">
											<select name="search_field" style="width:84px;">
												<option value="COMPANY_NM" <? if ($search_field == "COMPANY_NM") echo "selected"; ?> >업체명</option>
												<option value="HOMEPAGE" <? if ($search_field == "HOMEPAGE") echo "selected"; ?> >사이트</option>
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
							<col style="width:5%" />
							<col style="width:5%" />
							<col style="width:25%" />
							<col style="width:25%" />
							<col style="width:15%" />
							<col style="width:15%" />
							<col style="width:10%" />
						</colgroup>
						<tbody>
							<tr>
								<th scope="col">&nbsp;</th>
								<th scope="col">NO.</th>
								<th scope="col">[업체번호] 업체명</th>
								<th scope="col">사이트</th>
								<th scope="col">관리자 ID</th>
								<th scope="col">관리자비번</th>
								<th scope="col">수정</th>
							</tr>

					
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
										$rn							= trim($arr_rs[$j]["rn"]);
										$BB_NO					= trim($arr_rs[$j]["BB_NO"]);
										$BB_RE					= trim($arr_rs[$j]["BB_RE"]);
										$BB_DE					= trim($arr_rs[$j]["BB_DE"]);
										$BB_PO					= trim($arr_rs[$j]["BB_PO"]);
										$BB_CODE				= trim($arr_rs[$j]["BB_CODE"]);

										$CATE_02				= trim($arr_rs[$j]["CATE_02"]);

										$COMPANY_NM		= trim($arr_rs[$j]["COMPANY_NM"]);
										$CONTENTS			= SetStringFromDB($arr_rs[$j]["CONTENTS"]);
										$HOMEPAGE			= trim($arr_rs[$j]["HOMEPAGE"]);
										$FTP_ADDR				= trim($arr_rs[$j]["FTP_ADDR"]);
										$FTP_PORT				= trim($arr_rs[$j]["FTP_PORT"]);
										$FTP_ID					= trim($arr_rs[$j]["FTP_ID"]);
										$FTP_PW					= trim($arr_rs[$j]["FTP_PW"]);
										$DB_ADDR				= trim($arr_rs[$j]["DB_ADDR"]);
										$DB_NM					= trim($arr_rs[$j]["DB_NM"]);
										$DB_ID					= trim($arr_rs[$j]["DB_ID"]);
										$DB_PW					= trim($arr_rs[$j]["DB_PW"]);
										$ADMIN_ADDR		= trim($arr_rs[$j]["ADMIN_ADDR"]);
										$ADMIN_ID			= trim($arr_rs[$j]["ADMIN_ID"]);
										$ADMIN_PW			= trim($arr_rs[$j]["ADMIN_PW"]);

										$CATE_02 = str_replace("||",",",$CATE_02);
							?>
							<tr> 
								<td><input type="checkbox" name="chk[]" value="<?=$BB_NO?>"></td>
								<td><a href="javascript:js_view2('<?=$j?>','<?=sizeof($arr_rs)?>');"><?= $rn ?></a></td>
								<td class="tit"><?=$space?><a href="javascript:js_view2('<?=$j?>','<?=sizeof($arr_rs)?>');"><?=$COMPANY_NM?></a> <?=$reply_cnt?></td>
								<td><a href="http://<?=$HOMEPAGE?>" target="_blank"><?= $HOMEPAGE ?></td>
								<td><?= $ADMIN_ID ?></td>
								<td class="filedown">
									<?=$ADMIN_PW?>
								</td>
								<td class="filedown">
									<button type="button" class="btn-gray" onClick="js_view('<?=$rn?>','<?=$BB_CODE?>','<?=$BB_NO?>');" style="width:80px">수정</button>
								</td>
							</tr>
							<tr id="sub_info<?=$j?>" style="display:none">
								<td colspan="7" style="padding:0px">
									<table width="100%" class="tb_con" style="width:100%">
										<colgroup>
											<col style="width:90px;;" />
											<col style="width:540px;" />
											<col style="width:;" />
											<col style="width:;" />
											<col style="width:;" />
										</colgroup>
										<tr>
											<td style="padding-right:20px;text-align:right;border-top:none">FTP : </td>
											<td style="padding-left:20px;text-align:left;border-top:none"><?=$FTP_ADDR?></td>
											<td><?=$FTP_ID?></td>
											<td><?=$FTP_PW?></td>
											<td><?=$FTP_PORT?></td>
										</tr>
										<tr>
											<td style="padding-right:20px;text-align:right">DB : </td>
											<td style="padding-left:20px;text-align:left"><?=$DB_ADDR?></td>
											<td><?=$DB_ID?></td>
											<td><?=$DB_PW?></td>
											<td><?=$DB_NM?></td>
										</tr>
										<tr>
											<td style="padding-right:20px;text-align:right">ADMIN : </td>
											<td style="padding-left:20px;text-align:left"><?=$ADMIN_ADDR?></td>
											<td><?=$ADMIN_ID?></td>
											<td><?=$ADMIN_PW?></td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style="padding-right:20px;text-align:right">담당자 : </td>
											<td style="padding-left:20px;text-align:left" colspan="4"><?=$CATE_02?></td>
	
										</tr>
										<tr>
											<td style="padding-right:20px;text-align:right;border-bottom:none">추가내용 : </td>
											<td colspan="4" style="padding-left:20px;text-align:left;border-bottom:none"><?=$CONTENTS?></td>

										</tr>
									</table>
								</td>
							</tr>
							<?			
									}
								} else { 
							?> 
							<tr>
								<td height="50" align="center" colspan="10">데이터가 없습니다. </td>
							</tr>
							<? 
								}
							?>
						</tbody>
					</table>
				</div>
				<p class="paging">

					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&con_category=".$con_category;

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
</form>
	<!-- S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>
<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
