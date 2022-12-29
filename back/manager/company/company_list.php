<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : company_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2010.05.21
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

if ($s_adm_cp_type <> "운영") {
	$next_url = "company_write.php?mode=S&cp_no=$s_adm_com_code";
?>
<meta http-equiv='Refresh' content='0; URL=<?=$next_url?>'>
<?
	exit;
}


#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "CP002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/company/company.php";

#====================================================================
# Request Parameter
#====================================================================
	#user_paramenter
	$con_cp_type = trim($con_cp_type);
	
	
	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$date_start			= trim($date_start);
	$date_end				= trim($date_end);
	
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
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntCompany($conn, $con_cp_type, $con_ad_type, $date_start, $date_end, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / (int)$nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	$arr_rs = listCompany($conn, $con_cp_type, $con_ad_type, $date_start, $date_end, $use_tf, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize);

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


	function js_write(){
		location.href = "company_write.php" ;
	}

	function js_view(rn, seq) {

		var frm = document.frm;
		frm.rn.value = rn;
		frm.cp_no.value = seq;
		frm.mode.value = "S";
		frm.method = "get";
		frm.action = "company_write.php";
		frm.submit();
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_excel() {

		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.target = "";
		frm.action = "<?=str_replace("list","excel_list",$_SERVER[PHP_SELF])?>";
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
					
					<? if ($sPageRight_I == "Y") { ?><button type="button" class="btn-navy" id="btn_write" onclick="js_write()">등록하기</button><? } ?>

				</h3>

<form name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="cp_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<!--<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">-->

				<div class="boardlist search">
					
					<table>

						<colgroup>
							<col style="width:10%" />
							<col style="width:32%" />
							<col style="width:10%" />
							<col style="width:42%" />
							<col style="width:6%" />
						</colgroup>
						<tbody>
							<tr>
								<th>업체구분</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"CP_TYPE","con_cp_type","125","전체","",$con_cp_type)?>
									</span>
								</td>
								<th>정렬</th>
								<td>
									<div class="searchbox">
										<span class="optionbox">
											<select name="order_field">
												<option value="CP_NO" <? if ($order_field == "CP_NO") echo "selected"; ?> >업체번호</option>
												<option value="CP_NM" <? if ($order_field == "CP_NM") echo "selected"; ?> >업체명</option>
												<option value="CEO_NM" <? if ($order_field == "CEO_NM") echo "selected"; ?> >대표자명</option>
												<option value="MANAGER_NM" <? if ($order_field == "MANAGER_NM") echo "selected"; ?> >담당자명</option>
												<option value="REG_DATE" <? if ($order_field == "REG_DATE") echo "selected"; ?> >등록일</option>
											</select>
										</span>
										<div class="iradiobox" style="margin-left:20px">
											<span class="iradio"><input type='radio' class="" name='order_str' value='DESC' <? if (($order_str == "DESC") || ($order_str == "")) echo " checked"; ?> ><label>오름차순</label></span>
											<span class="iradio"><input type='radio' name='order_str' value='ASC' <? if ($order_str == "ASC") echo " checked"; ?>><label>내림차순</label></span>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th>검색조건</th>
								<td colspan="3">
									<div class="searchbox">
										<span class="optionbox">
											<select name="nPageSize">
												<option value="20" <? if ($nPageSize == "20") echo "selected"; ?> >20개씩</option>
												<option value="50" <? if ($nPageSize == "50") echo "selected"; ?> >50개씩</option>
												<option value="100" <? if ($nPageSize == "100") echo "selected"; ?> >100개씩</option>
											</select>
										</span>
										<span class="optionbox" style="width:150px">
											<select name="search_field">
												<option value="CP_NM" <? if ($search_field == "CP_NM") echo "selected"; ?> >업체명</option>
												<option value="CEO_NM" <? if ($search_field == "CEO_NM") echo "selected"; ?> >대표자명</option>
												<option value="MANAGER_NM" <? if ($search_field == "MANAGER_NM") echo "selected"; ?> >담당자명</option>
												<option value="BIZ_NO" <? if ($search_field == "BIZ_NO") echo "selected"; ?> >사업자등록번호</option>
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
				<span class="fn_gray">총 <?=$nListCnt?> 건</span>
				
				<div class="boardlist">
					<table>
						<colgroup>
							<col style="width:5%">
							<col style="width:33%">
							<col style="width:10%">
							<col style="width:12%">
							<col style="width:10%">
							<col style="width:10%">
							<col style="width:10%">
							<col style="width:10%">
						</colgroup>
						<thead>
							<tr>
								<th scope="col">NO.</th>
								<th scope="col">[업체번호] 업체명</th>
								<th scope="col">대표자명</th>
								<th scope="col">연락처</th>
								<th scope="col">팩스</th>
								<th scope="col">업체구분</th>
								<th scope="col">결제구분</th>
								<th scope="col">등록일</th>
							</tr>
						</thead>
						<tbody>
						<?
							$nCnt = 0;
						
							if (sizeof($arr_rs) > 0) {
							
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								
									//rn, CP_NO, CP_NM, CP_PHONE, CP_HPHONE, CP_FAX, CP_ZIP, CP_ADDR, 
									//BIZ_NO, CEO_NM, UPJONG, UPTEA, DC_RATE, MANAGER_NM, PHONE, HPHONE, FPHONE, CONTRACT_START, CONTRACT_END, 
									//USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								
									$rn							= trim($arr_rs[$j]["rn"]);
									$CP_NO					= trim($arr_rs[$j]["CP_NO"]);
									$CP_NM					= SetStringFromDB($arr_rs[$j]["CP_NM"]);
									$CEO_NM					= SetStringFromDB($arr_rs[$j]["CEO_NM"]);
									$CP_TYPE				= SetStringFromDB($arr_rs[$j]["CP_TYPE"]);
									$AD_TYPE				= SetStringFromDB($arr_rs[$j]["AD_TYPE"]);
									$MANAGER_NM			= SetStringFromDB($arr_rs[$j]["MANAGER_NM"]);
									$CP_PHONE				= SetStringFromDB($arr_rs[$j]["CP_PHONE"]);
									$CP_FAX					= SetStringFromDB($arr_rs[$j]["CP_FAX"]);
									$PHONE					= SetStringFromDB($arr_rs[$j]["PHONE"]);
									$CONTRACT_START	= trim($arr_rs[$j]["CONTRACT_START"]);
									$CONTRACT_END		= trim($arr_rs[$j]["CONTRACT_END"]);
									$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

									$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
									$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
								
									$CONTRACT_START = date("Y-m-d",strtotime($CONTRACT_START));
									$CONTRACT_END		= date("Y-m-d",strtotime($CONTRACT_END));
									$REG_DATE				= date("Y-m-d",strtotime($REG_DATE));
					
							?>
							<tr>
								<td ><?= $rn ?></td>
								<td><a href="javascript:js_view('<?= $rn ?>','<?= $CP_NO ?>');">[<?=$CP_NO?>] <?= $CP_NM ?></a></td>
								<td><?= $CEO_NM ?></td>
								<td><?= $CP_PHONE ?></td>
								<td><?= $CP_FAX ?></td>
								<td><?= getDcodeName($conn, "CP_TYPE", $CP_TYPE);?></td>
								<td><?= getDcodeName($conn, "AD_TYPE", $AD_TYPE);?></td>
								<td class="filedown"><?= $REG_DATE ?></td>
							</tr>
							<?			
									}
								} else { 
							?> 
							<tr>
								<td align="center" height="50"  colspan="8">데이터가 없습니다. </td>
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
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
				</p>
			<!-- // E: mwidthwrap -->

</form>
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
<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
