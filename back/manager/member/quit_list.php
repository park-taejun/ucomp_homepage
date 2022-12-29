<?session_start();?>
<?
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");


#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다
	//$menu_cd="0501";

	$menu_right = "MB006"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/member/member.php";
#====================================================================
# Request Parameter
#====================================================================
	
	// 로그인한 관리자 권한에 따라 보여주는 내용이 다릅니다. -- 여기 부터

	if (($_SESSION['s_adm_position_code'] == "") || ($_SESSION['s_adm_position_code'] == "중앙당")) {
		$sel_area_cd = trim($sel_area_cd);
	} else {
		$sel_area_cd = $_SESSION['s_adm_position_code'];
	}

	if ((($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) && ($_SESSION['s_adm_organization'] == "")) {
		$sel_party = trim($sel_party);
	} else {
		$sel_party = $_SESSION['s_adm_dept_code'];
	}

	if ($_SESSION['s_adm_organization'] == "") {
		$Ngroup_cd = trim($Ngroup_cd);
	} else {
		if (strlen(trim($Ngroup_cd)) > strlen($_SESSION['s_adm_organization'])) {
			$Ngroup_cd = trim($Ngroup_cd);
		} else {
			$Ngroup_cd = $_SESSION['s_adm_organization'];
		}
	}

	// --------------------------------------------------------- 여기 까지

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	
	if ($search_field == "M_HP") {
		$search_str	 = encrypt($key, $iv, $search_str); 
	}
	


	$con_del_tf = "N";
	
	if ($order_field == "")
		$order_field = "M_LEAVE_DATE";
	
	if ($order_str=="")
		$order_str = "DESC";

#============================================================
# Page process
#============================================================

	if (($nPage <> "") && ($nPage <> 0)) {
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
	$nListCnt =totalCntQuitMember($conn, $start_date, $end_date, $sel_pay_type, $sel_area_cd, $sel_party, $is_agree, $Ngroup_cd, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listQuitMember($conn, $start_date, $end_date, $sel_pay_type, $sel_area_cd, $sel_party, $is_agree, $Ngroup_cd, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize);


	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "탈당 당원 관리 리스트 조회", "List");

	if ($search_field == "M_HP") {
		$search_str	 = decrypt($key, $iv, $search_str); 
	}

	if ($Ngroup_cd) {

		if(strlen($Ngroup_cd) == 3){
			$group_cd_01=$Ngroup_cd;
		}
		if(strlen($Ngroup_cd) == 6){
			$group_cd_01=substr($Ngroup_cd,0,3);
			$group_cd_02=substr($Ngroup_cd,0,6);
		}
		if(strlen($Ngroup_cd) == 9){
			$group_cd_01=substr($Ngroup_cd,0,3);
			$group_cd_02=substr($Ngroup_cd,0,6);
			$group_cd_03=substr($Ngroup_cd,0,9);
		}

		if(strlen($Ngroup_cd) == 12){
			$group_cd_01=substr($Ngroup_cd,0,3);
			$group_cd_02=substr($Ngroup_cd,0,6);
			$group_cd_03=substr($Ngroup_cd,0,9);
			$group_cd_04=substr($Ngroup_cd,0,12);
		}

		if(strlen($Ngroup_cd) == 15){
			$group_cd_01=substr($Ngroup_cd,0,3);
			$group_cd_02=substr($Ngroup_cd,0,6);
			$group_cd_03=substr($Ngroup_cd,0,9);
			$group_cd_04=substr($Ngroup_cd,0,12);
			$group_cd_05=substr($Ngroup_cd,0,15);
		}
	}

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_comm_name?> 관리자 로그인</title>
<link href="../css/common.css" rel="stylesheet" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />

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

<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>

<script language="javascript">

	$(document).ready(function() {

		$(".date").datepicker({
			dateFormat: "yy-mm-dd"
		});

		$( "#search_str" ).keypress(function( event ) {
			if ( event.which == 13 ) {
				js_search();
			}
		});

		$(document).on("change","#group_cd_01", function(){
			js_sel_party($("#group_cd_01").val(), '');
		});

		$(document).on("change","#group_cd_02", function(){
			js_sel_party($("#group_cd_01").val(), $("#group_cd_02").val());
		});

		$(document).on("change","#group_cd_03", function(){
			js_sel_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val());
		});

		$(document).on("change","#group_cd_04", function(){
			js_sel_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val(), $("#group_cd_04").val());
		});

		$(document).on("change","#group_cd_05", function(){
			document.frm.Ngroup_cd.value=$("#group_cd_05").val();
		});

	});


	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_read(seq) {

		var frm = document.frm;
		frm.m_no.value = seq;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "quit_read.php";
		frm.submit();
		
	}

	function js_excel_list() {

		var frm = document.frm;
		frm.target = "";
		frm.action = "<?=str_replace("list","excel_list",$_SERVER[PHP_SELF])?>";
		frm.submit();

	}

	function js_search_field() {
		
		var frm = document.frm;
		if (frm.search_field.value == "M_HP") {
			alert("휴대폰 검색 시 '-' 포함하여 검색해 주세요.");
			return;
		}

	}

	function js_sel_party(group_cd_01, group_cd_02, group_cd_03, group_cd_04) {

		var frm = document.frm;
		var party_val = "";

		party_val = frm.sel_party.value;

		if (party_val == "") {
			$("#add_group").hide();
		} else {
		
			var request = $.ajax({
				url:"/_common/get_next_group.php",
				type:"POST",
				data:{party_val:party_val, group_cd_01:group_cd_01, group_cd_02:group_cd_02, group_cd_03:group_cd_03, group_cd_04:group_cd_04},
				dataType:"html"
			});

			request.done(function(msg) {
				//alert(msg);
				$("#group_div").html(msg);
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});

			$("#add_group").show();
		}

		$("#Ngroup_cd").val("");
		if ((group_cd_01 != "") && (group_cd_01 != null)) $("#Ngroup_cd").val(group_cd_01);
		if ((group_cd_02 != "") && (group_cd_02 != null)) $("#Ngroup_cd").val(group_cd_02);
		if ((group_cd_03 != "") && (group_cd_03 != null)) $("#Ngroup_cd").val(group_cd_03);
		if ((group_cd_04 != "") && (group_cd_04 != null)) $("#Ngroup_cd").val(group_cd_04);
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

<form id="bbsList" name="frm" method="post" action="javascript:js_serch();">
<input type="hidden" name="m_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
<input type="hidden" name="Ngroup_cd" id="Ngroup_cd" value="<?=$Ngroup_cd?>"/>

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							<tr>
								<th>탈당일</th>
								<td colspan="3">
									<input type="text" name="start_date" id="start_date" class="date" value="<?=$start_date?>" style="width:100px;border:1px solid #dfdfdf;" readonly="1"> ~
									<input type="text" name="end_date" id="end_date" class="date" value="<?=$end_date?>" style="width:100px;border:1px solid #dfdfdf;" readonly="1"> 
								</td>
							</tr>
							<tr>
								<th>소속지역</th>
								<td>
									<?
										if (($_SESSION['s_adm_position_code'] == "") || ($_SESSION['s_adm_position_code'] == "중앙당")) {
									?>
									<?= makeSelectBox($conn,"AREA_CD","sel_area_cd", "250","소속지역 선택", "",$sel_area_cd);?>
									<?
										} else {
									?>
									<?=getDcodeName($conn, "AREA_CD", $_SESSION['s_adm_position_code'])?>
									<input type="hidden" name="sel_area_cd" value="<?=$_SESSION['s_adm_position_code']?>">
									<?
										}
									?>
								</td>
								<th>소속당</th>
								<td>
									<?
										if ((($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) && ($_SESSION['s_adm_organization'] == "")) {
									?>
									<?= makeSelectBoxOnChange($conn,"PARTY","sel_party", "250","소속당 선택", "",$sel_party);?>
									<?
										} else {
									?>
									<?=getDcodeName($conn, "PARTY", $_SESSION['s_adm_dept_code'])?>
									<input type="hidden" name="sel_party" value="<?=$_SESSION['s_adm_dept_code']?>">
									<?
										}
									?>
								</td>
							</tr>

							<tr id="add_group" style="display:none">
								<th>소속조직</th>
								<td colspan="3">
								<div class="sp5"></div>
								<div id="group_div"><select name="group_cd_01" id="group_cd_01" style="width:200px;"></select></div>
								
								<div class="sp5"></div>
								</td>
							</tr>

							<tr>
								<th>납부방법</th>
								<td>
									<select name="sel_pay_type" style="width:150px">
										<option value="">납부방법 선택</option>
										<option value="cms" <? if ($sel_pay_type == "cms") echo "selected"; ?>>CMS</option>
										<option value="card" <? if ($sel_pay_type == "card") echo "selected"; ?>>신용카드</option>
										<option value="mobile" <? if ($sel_pay_type == "mobile") echo "selected"; ?>>휴대전화</option>
										<option value="cash" <? if ($sel_pay_type == "cash") echo "selected"; ?>>직접납부</option>
									</select>
								</td>
								<th>동의서</th>
								<td>
									<select name="is_agree" style="width:150px">
										<option value="">동의서여부 선택</option>
										<option value="Y" <? if ($is_agree == "Y") echo "selected"; ?>>첨부</option>
										<option value="N" <? if ($is_agree == "N") echo "selected"; ?>>미첨부</option>
									</select>
								</td>
							</tr>

							<tr class="last">
								<th>정렬</th>
								<td>
									<select name="order_field" style="width:84px;">
										<option value="M_LEAVE_DATE" <? if ($order_field == "M_LEAVE_DATE") echo "selected"; ?> >탈당일</option>
										<option value="M_DATETIME" <? if ($order_field == "M_DATETIME") echo "selected"; ?> >가입일</option>
										<option value="M_NAME" <? if ($order_field == "M_NAME") echo "selected"; ?> >성명</option>
										<option value="M_BIRTH" <? if ($order_field == "M_BIRTH") echo "selected"; ?> >생년월일</option>
										<!--
										<option value="M_NICK" <? if ($order_field == "M_NICK") echo "selected"; ?> >닉네임</option>
										-->
										<option value="M_ID" <? if ($order_field == "M_ID") echo "selected"; ?> >아이디</option>
									</select>&nbsp;&nbsp;
									<input type='radio' class="radio" name='order_str' value='DESC' <? if (($order_str == "DESC") || ($order_str == "")) echo " checked"; ?> > 오름차순 &nbsp;
									<input type='radio' class="radio" name='order_str' value='ASC' <? if ($order_str == "ASC") echo " checked"; ?>> 내림차순
								</td>
								<th>검색조건</th>
								<td>
									<select name="nPageSize" style="width:84px;">
										<option value="20" <? if ($nPageSize == "20") echo "selected"; ?> >20개씩</option>
										<option value="50" <? if ($nPageSize == "50") echo "selected"; ?> >50개씩</option>
										<option value="100" <? if ($nPageSize == "100") echo "selected"; ?> >100개씩</option>
										<option value="200" <? if ($nPageSize == "200") echo "selected"; ?> >200개씩</option>
										<option value="300" <? if ($nPageSize == "300") echo "selected"; ?> >300개씩</option>
										<option value="400" <? if ($nPageSize == "400") echo "selected"; ?> >400개씩</option>
										<option value="500" <? if ($nPageSize == "500") echo "selected"; ?> >500개씩</option>
									</select>&nbsp;
									<select name="search_field" style="width:84px;" onChange="js_search_field();">
										<option value="M_NAME" <? if ($search_field == "M_NAME") echo "selected"; ?> >성명</option>
										<!--
										<option value="M_NICK" <? if ($search_field == "M_NICK") echo "selected"; ?> >닉네임</option>
										-->
										<option value="M_ID" <? if ($search_field == "M_ID") echo "selected"; ?> >아이디</option>
										<option value="M_ADDR1" <? if ($search_field == "M_ADDR1") echo "selected"; ?> >주소</option>
										<option value="M_EMAIL" <? if ($search_field == "M_EMAIL") echo "selected"; ?> >이메일</option>
										<option value="M_BIRTH" <? if ($search_field == "M_BIRTH") echo "selected"; ?> >생년월일</option>
										<option value="M_3" <? if ($search_field == "M_3") echo "selected"; ?> >소속당</option>
										<option value="M_HP" <? if ($search_field == "M_HP") echo "selected"; ?> >휴대폰</option>
									</select>&nbsp;

									<input type="text" value="<?=$search_str?>" name="search_str" id="search_str" size="15"class="txt" />
									<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="검색" /></a>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="expArea">
						<ul class="fLeft">
							<li class="total">총 <?=number_format($nListCnt)?>건</li>
							<!--<li class="file">파일 저장하기&nbsp;<a href="#"><img src="../images/bu/ic_file.gif" alt="엑셀파일 아이콘" /></a></li>
							<?if($_SERVER['REMOTE_ADDR']=="203.229.173.93"){?>
							<?}?>
							<a href="javascript:js_emailsend4check();"><img src="../images/mail/mailing_btn_01.gif" alt="메일보내기[선택된 사람에게]" /></a>&nbsp;&nbsp;
							<a href="javascript:js_emailsend4search();"><img src="../images/mail/mailing_btn_02.gif" alt="메일보내기[검색된 사람에게]" /></a>&nbsp;&nbsp;
							<a href="javascript:js_emailsend4all();"><img src="../images/mail/mailing_btn_03.gif" alt="메일보내기[전체]" /></a>-->
						</ul>
						<p class="fRight">
							&nbsp;
						</p>
					</div>
					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
				
						<colgroup>
							<col width="5%" /><!-- 번호 -->
							<col width="6%" /><!-- 아이디 -->
							<col width="6%" /><!-- 이름 -->
							<col width="10%" /><!-- 광역시/도 -->
							<col width="5%" /><!-- 성별 -->
							<col width="8%" /><!-- 생년월일 -->
							<col width="9%" /><!-- 연락처 -->
							<col width="7%" /><!-- 소속당 -->
							<col width="7%" /><!-- 당비여부 -->
							<col width="5%" /><!-- 납부방법 -->
							<col width="7%" /><!-- 가입일 -->
							<col width="7%" /><!-- 탈당일 -->
							<col width="18%" /><!-- 메모 -->
						</colgroup>

						<thead>
							<tr>
								<th>번호</th>
								<th>아이디</th>
								<th>이름</th>
								<th>광역시/도</th>
								<th>성별</th>
								<th>생년월일</th>
								<th>연락처</th>
								<th>소속당</th>
								<th>당비여부</th>
								<th>납부방법</th>
								<th>가입일</th>
								<th>탈당일</th>
								<th>관리자메모</th>
							</tr>
						</thead>
						<tbody>

						<?
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									//A.MEM_NO, A.MEM_TYPE AS M_MEM_TYPE, A.MEM_NM, A.MEM_NICK, 
									//A.MEM_ID, A.BIRTH_DATE, A.HPHONE, A.USE_TF, B.SEQ_NO, B.COMM_NO, B.MEM_TYPE

									$rn								= trim($arr_rs[$j]["rn"]);
									$M_NO							= trim($arr_rs[$j]["M_NO"]);
									$M_ID							= trim($arr_rs[$j]["M_ID"]);
									$M_NAME						= trim($arr_rs[$j]["M_NAME"]);
									$SIDO							= trim($arr_rs[$j]["SIDO"]);
									$M_SEX						= trim($arr_rs[$j]["M_SEX"]);
									$M_HP							= trim($arr_rs[$j]["M_HP"]);
									$M_3							= trim($arr_rs[$j]["M_3"]);
									$M_5							= trim($arr_rs[$j]["M_5"]);
									$M_6							= trim($arr_rs[$j]["M_6"]);
									$M_DATETIME 			= trim($arr_rs[$j]["M_DATETIME"]);
									$M_BIRTH					= trim($arr_rs[$j]["M_BIRTH"]);
									$M_LEVEL					= trim($arr_rs[$j]["M_LEVEL"]);
									$M_SIGNATURE			= trim($arr_rs[$j]["M_SIGNATURE"]);
									$CMS_FLAG					= trim($arr_rs[$j]["CMS_FLAG"]);
									$SEND_FLAG				= trim($arr_rs[$j]["SEND_FLAG"]);
									$M_LEAVE_DATE			= trim($arr_rs[$j]["M_LEAVE_DATE"]);
									$M_MEMO						= trim($arr_rs[$j]["M_MEMO"]);

									$str_m_tel = decrypt($key, $iv, $M_HP);

					?>

							<tr <? if ($j == (sizeof($arr_rs)-1)) {?> class="last" <? } ?> >
								<td><!--<input type="checkbox" name="chk[]" value="<?=$M_NO?>">--><?=$rn?></td>
								<td><a href="javascript:js_read('<?=$M_NO?>');"><?=$M_ID?></a></td>
								<td><a href="javascript:js_read('<?=$M_NO?>');"><?=$M_NAME?></a></td>
								<td><?=$SIDO?></td>
								<td>
								<?
									if($M_SEX=="1"){
										echo "남자";
									}else{
										echo "여자";
									}
								?>
								</td>
								<td><?=$M_BIRTH?></td>
								<td><?=$str_m_tel?></td>
								<td><?=$M_3?></td>
								<td>
									<? if ($M_5 == "Y") { echo "약정"; } else { echo "미약정"; } ?>
								</td>
								<td>
									<? if ($M_6 == "mobile") { echo "휴대전화"; } else if ($M_6 == "cms") { echo "CMS"; } else if ($M_6 == "card") { echo "신용카드"; } else { echo "&nbsp;";} ?>
								</td>
								<td><?=substr($M_DATETIME ,0,10)?></td>
								<td><?=$M_LEAVE_DATE?></td>
								<td><?=nl2br($M_MEMO)?></td>
							</tr>
						<?			
								}
							} else { 
						?>
							<tr>
								<td align="center" height="50" colspan="15">데이터가 없습니다. </td>
							</tr>
						<? 
							}
						?>

						</tbody>
					</table>
				</fieldset>
			</form>
			<div class="btnArea">
				<ul class="fRight">
					
					<input type="button" name="aa" value=" 엑셀 리스트 " class="btntxt"  style="cursor:pointer;height:25px;" onclick="js_excel_list();"> 
					
				</ul>
			</div>
			<div id="bbspgno">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree."&start_date=".$start_date."&end_date=".$end_date."&Ngroup_cd=".$Ngroup_cd;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>

					<!-- --------------------- 페이지 처리 화면 END -------------------------->
		</div>
		</section>
		<iframe src="about:blank" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?
	if($Ngroup_cd || $sel_party) {
?>
	<script type="text/javascript">
	<!--
		js_sel_party('<?=$group_cd_01?>', '<?=$group_cd_02?>', '<?=$group_cd_03?>', '<?=$group_cd_04?>', '<?=$group_cd_05?>');
	//-->
	</script>
<?
	}

#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>