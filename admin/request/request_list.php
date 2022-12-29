<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : request_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-03-29
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
	// $menu_right = "RQ002"; // 메뉴마다 셋팅 해 주어야 합니다
	$menu_right = "QR001"; // 메뉴마다 셋팅 해 주어야 합니다
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
	require "../../_classes/biz/request/request.php";

#====================================================================
# Request Parameter
#====================================================================
	
	$nPage					= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize				= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field			= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str				= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$con_request_cate		= $_POST['con_request_cate']!=''?$_POST['con_request_cate']:$_GET['con_request_cate'];
	$con_reply_state		= $_POST['con_reply_state']!=''?$_POST['con_reply_state']:$_GET['con_reply_state'];

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

	$nListCnt =totalCntRequest($conn, $start_date, $end_date, $con_request_cate, $con_reply_state, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / (int)$nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listRequest($conn, $start_date, $end_date, $con_request_cate, $con_reply_state, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

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
		location.href = "request_write.php" ;
	}

	function js_view(request_no) {

		var frm = document.frm;
		frm.request_no.value = request_no;
		frm.mode.value = "S";
		frm.method = "get";
		frm.action = "request_write.php";
		frm.submit();
	}
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		//frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.action = "/admin/request/request_list.php";
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
					
					<? if ($sPageRight_I == "Y") { ?><!--<button type="button" class="btn-navy" id="btn_write" onclick="js_write()">등록하기</button>--><? } ?>

				</h3>

<form name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="request_no" value="">
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
								<th>프로젝트 유형</th>  
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"REQUEST_CATE","con_request_cate","125pc","전체","",$con_request_cate)?>
									</span>
								</td>
								<th>답변상태</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"REPLY_STATE","con_reply_state","125pc","전체","",$con_reply_state)?>
									</span>
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
												<option value="REQUEST_NAME" <? if ($search_field == "REQUEST_NAME") echo "selected"; ?> >이름</option>
												<option value="REQUEST_TEL" <? if ($search_field == "REQUEST_TEL") echo "selected"; ?> >연락처</option>
												<option value="REQUEST_EMAIL" <? if ($search_field == "REQUEST_EMAIL") echo "selected"; ?> >이메일</option>
												<option value="REQUEST_TITLE" <? if ($search_field == "REQUEST_TITLE") echo "selected"; ?> >제목</option>
												<option value="REQUEST_CONTENTS" <? if ($search_field == "REQUEST_CONTENTS") echo "selected"; ?> >요청내용</option>
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
							<col style="width:5%">	<!-- NO -->
							<col style="width:10%">	<!-- 문의구분 -->
							<col style="width:25%">	<!-- 제목 -->
							<col style="width:8%">	<!-- 이름 -->
							<col style="width:10%">	<!-- 연락처 -->
							<col style="width:10%">	<!-- 이메일 -->
							<col style="width:8%">	<!-- 답변상태 -->
							<col style="width:8%">	<!-- 답변자 -->
							<col style="width:8%">	<!-- 등록일 -->
							<col style="width:8%">	<!-- 처리일 -->
						</colgroup>
						<thead>
							<tr>
								<th scope="col">NO.</th>
								<th scope="col">프로젝트 유형</th>
								<th scope="col">제목</th>
								<th scope="col">담당자명</th>
								<th scope="col">연락처</th>
								<th scope="col">이메일</th>
								<th scope="col">답변상태</th>
								<th scope="col">답변자</th>
								<th scope="col">등록일</th>
								<th scope="col">처리일</th>
							</tr>
						</thead>
						<tbody>
						<?
							$nCnt = 0;
						
							if (sizeof($arr_rs) > 0) {
							
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								
									$rn							= trim($arr_rs[$j]["rn"]);
									$REQUEST_NO					= trim($arr_rs[$j]["REQUEST_NO"]);
									$REQUEST_CATE				= trim($arr_rs[$j]["REQUEST_CATE"]);
									$REQUEST_NAME				= SetStringFromDB($arr_rs[$j]["REQUEST_NAME"]);
									$REQUEST_TEL				= trim($arr_rs[$j]["REQUEST_TEL"]);
									$REQUEST_EMAIL				= trim($arr_rs[$j]["REQUEST_EMAIL"]);
									$REQUEST_TITLE				= SetStringFromDB($arr_rs[$j]["REQUEST_TITLE"]);
									$REQUEST_IP					= trim($arr_rs[$j]["REQUEST_IP"]);
									$REQUEST_CONTENTS			= SetStringFromDB($arr_rs[$j]["REQUEST_CONTENTS"]);
									$REQUEST_REPLY				= SetStringFromDB($arr_rs[$j]["REQUEST_REPLY"]);
									$REQUEST_REPLY_ADM			= trim($arr_rs[$j]["REQUEST_REPLY_ADM"]);
									$REPLY_DATE					= trim($arr_rs[$j]["REPLY_DATE"]);
									$REPLY_STATE				= trim($arr_rs[$j]["REPLY_STATE"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
  
									if ($REPLY_DATE) {
										$REPLY_DATE				= date("Y-m-d",strtotime($REPLY_DATE));
									}

									$REG_DATE					= date("Y-m-d",strtotime($REG_DATE));
					
							?>
							<tr>
								<td ><?= $rn ?></td>
								<td><?= getDcodeName($conn, "REQUEST_CATE", $REQUEST_CATE);?></td>
								<td style="text-align:left;padding-left:10px"><a href="javascript:js_view('<?= $REQUEST_NO ?>');"><?= $REQUEST_TITLE ?></a></td>
								<td><?= $REQUEST_NAME ?></td>
								<td><?= $REQUEST_TEL ?></td>
								<td><?= $REQUEST_EMAIL ?></td>
								<td><?= getDcodeName($conn, "REPLY_STATE", $REPLY_STATE);?></td>
								<td><?= getAdminName($conn, $REQUEST_REPLY_ADM) ?></td>
								<td class="filedown"><?= $REG_DATE ?></td>
								<td class="filedown"><?= $REPLY_DATE ?></td>
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
				<p class="paging">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&con_request_cate=".$con_request_cate."&con_reply_state=".$con_reply_state;

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
<script type="text/javascript" src="/admin/js/common_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>

