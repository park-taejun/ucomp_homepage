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

	$menu_right = "MB004"; // 메뉴마다 셋팅 해 주어야 합니다

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
	
	if ($mode == "U") {
		if (($seq_no <> "") && ($m_no <> "")) {
			$result = updatePayInfo($conn, $seq_no, $m_no);
			
			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "당비 납부 정보 수정", "Update");

		}
	}

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";


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
	$nListCnt =totalCntMemberPayHistory($conn, $sel_pay_type, $sel_area_cd, $sel_party, $is_agree, $Ngroup_cd, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listMemberPayHistory($conn, $sel_pay_type, $sel_area_cd, $sel_party, $is_agree, $Ngroup_cd, $search_field, $search_str, $nPage, $nPageSize);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "당비 납부 정보 수정 조회", "List");

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

<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script language="javascript">

	$(document).ready(function() {
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

	function js_modify(seq_no, m_no) {
		
		bDelOK = confirm('수정 승인 처리 하시겠습니까?');
		
		if (bDelOK==true) {
			var frm = document.frm;
			frm.m_no.value = m_no;
			frm.seq_no.value = seq_no;
			frm.mode.value = "U";
			frm.target = "";
			frm.method = "get";
			frm.action = "update_req_list.php";
			frm.submit();
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
<input type="hidden" name="seq_no" value="">
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

							<tr class="last">
								<th>납부방법</th>
								<td>
									<select name="sel_pay_type" style="width:150px">
										<option value="">납부방법 선택</option>
										<option value="cms" <? if ($sel_pay_type == "cms") echo "selected"; ?>>CMS</option>
										<option value="card" <? if ($sel_pay_type == "card") echo "selected"; ?>>신용카드</option>
										<option value="mobile" <? if ($sel_pay_type == "mobile") echo "selected"; ?>>휴대전화</option>
									</select>
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
									<select name="search_field" style="width:84px;">
										<option value="M_NAME" <? if ($search_field == "M_NAME") echo "selected"; ?> >성명</option>
										<!--
										<option value="M_NICK" <? if ($search_field == "M_NICK") echo "selected"; ?> >닉네임</option>
										-->
										<option value="M_ID" <? if ($search_field == "M_ID") echo "selected"; ?> >아이디</option>
										<option value="M_EMAIL" <? if ($search_field == "M_EMAIL") echo "selected"; ?> >이메일</option>
										<option value="M_3" <? if ($search_field == "M_3") echo "selected"; ?> >소속당</option>
									</select>&nbsp;

									<input type="text" value="<?=$search_str?>" name="search_str" id="search_str" size="15"class="txt" />
									<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="검색" /></a>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="expArea">
						<ul class="fLeft">
							<li class="total">총 <?=$nListCnt?>건</li>
							<li><font color="red">&nbsp;&nbsp;CMS 카드 출금일 (<b>25일</b>) 휴대전화 (<b>15일</b>)</font></li>
						</ul>
						<p class="fRight">
							<font color="red"><b>* 출금일이 영업일 기준 최소 4일 전일 경우만 수정 승인 처리 하셔야 합니다.</b></font>
						</p>
					</div>
					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
						<colgroup>
							<col width="6%" /><!-- 아이디 -->
							<col width="6%" /><!-- 이름 -->
							<col width="10%" /><!-- 광역시/도 -->
							<col width="10%" /><!-- 납부여부 -->
							<col width="14%" /><!-- 이전내용 -->
							<col width="14%" /><!-- 변경내용 -->
							<col width="10%" /><!-- 연락처 -->
							<col width="10%" /><!-- 소속당 -->
							<col width="10%" /><!-- 수정일시 -->
							<col width="10%" /><!-- 처리여부 -->
						</colgroup>

						<thead>
							<tr>
								<th>아이디</th>
								<th>이름</th>
								<th>광역시/도</th>
								<th>납부여부</th>
								<th>이전내용</th>
								<th>변경내용</th>
								<th>연락처</th>
								<th>소속당</th>
								<th>수정일시</th>
								<th>처리여부</th>
							</tr>
						</thead>
						<tbody>

						<?
							if (sizeof($arr_rs) > 0) {
								
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									
									$pre_str = "";
									$change_str = "";
									//A.MEM_NO, A.MEM_TYPE AS M_MEM_TYPE, A.MEM_NM, A.MEM_NICK, 
									//A.MEM_ID, A.BIRTH_DATE, A.HPHONE, A.USE_TF, B.SEQ_NO, B.COMM_NO, B.MEM_TYPE

									$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
									$M_NO							= trim($arr_rs[$j]["M_NO"]);
									$M_ID							= trim($arr_rs[$j]["M_ID"]);
									$M_NAME						= trim($arr_rs[$j]["M_NAME"]);
									$SIDO							= trim($arr_rs[$j]["SIDO"]);
									$IS_PAY						= trim($arr_rs[$j]["IS_PAY"]);
									$PAY_TYPE					= trim($arr_rs[$j]["PAY_TYPE"]);
									$M_HP							= trim($arr_rs[$j]["M_HP"]);
									$M_3							= trim($arr_rs[$j]["M_3"]);					// 소속당
									$M_5							= trim($arr_rs[$j]["M_5"]);					// 납부여부
									$M_6							= trim($arr_rs[$j]["M_6"]);					// 납부방법
									$M_SIGNATURE			= trim($arr_rs[$j]["M_SIGNATURE"]);
									$CMS_FLAG					= trim($arr_rs[$j]["CMS_FLAG"]);
									$SEND_FLAG				= trim($arr_rs[$j]["SEND_FLAG"]);
									$UP_DATE					= trim($arr_rs[$j]["up_date"]);
									$chk_flag					= trim($arr_rs[$j]["chk_flag"]);

									$P_M_7					= trim($arr_rs[$j]["P_M_7"]);
									$P_M_8					= trim($arr_rs[$j]["P_M_8"]);
									$P_M_9					= trim($arr_rs[$j]["P_M_9"]);
									$P_M_10					= trim($arr_rs[$j]["P_M_10"]);

									$M_7						= trim($arr_rs[$j]["M_7"]);
									$M_8						= trim($arr_rs[$j]["M_8"]);
									$M_9						= trim($arr_rs[$j]["M_9"]);
									$M_10						= trim($arr_rs[$j]["M_10"]);

									$P_M_7		= decrypt($key, $iv, $P_M_7);
									$P_M_8		= decrypt($key, $iv, $P_M_8);
									$P_M_9		= decrypt($key, $iv, $P_M_9);
									$P_M_10		= decrypt($key, $iv, $P_M_10);

									$M_7		= decrypt($key, $iv, $M_7);
									$M_8		= decrypt($key, $iv, $M_8);
									$M_9		= decrypt($key, $iv, $M_9);
									$M_10		= decrypt($key, $iv, $M_10);

									
									if ($M_5 == "Y") {
										$pre_str = "약정 : ";
										
										if ($M_6 == "mobile") {
											$pre_str = $pre_str."휴대폰<br>";
											$pre_str = $pre_str.getDcodeName($conn,"MOBILE_COM",$P_M_8)."<br>";
											$pre_str = $pre_str.$P_M_7."<br>";
										}

										if ($M_6 == "cms") {
											$pre_str = $pre_str."CMS<br>";
											$pre_str = $pre_str.getDcodeName($conn,"BANK_CODE",$P_M_7)."<br>";
											$pre_str = $pre_str."계좌번호 : ".$P_M_8."<br>";
											$pre_str = $pre_str."예금주 : ".$P_M_9."<br>";
										}

										if ($M_6 == "card") {
											$pre_str = $pre_str."신용카드<br>";
											$pre_str = $pre_str.getDcodeName($conn,"CARD_CODE",$P_M_7)."<br>";
											$pre_str = $pre_str."카드번호 : ".$P_M_8."<br>";
											$pre_str = $pre_str."유효기간 : ".$P_M_9." 년 ".$P_M_10." 월<br>";
										}
									} else {
										$pre_str = "미악정";
									}

									if ($IS_PAY == "Y") {
										$change_str = "약정 : ";
										
										if ($PAY_TYPE == "mobile") {
											$change_str = $change_str."휴대폰<br>";
											$change_str = $change_str.getDcodeName($conn,"MOBILE_COM",$M_8)."<br>";
											$change_str = $change_str.$M_7."<br>";
										}

										if ($PAY_TYPE == "cms") {
											$change_str = $change_str."CMS<br>";
											$change_str = $change_str.getDcodeName($conn,"BANK_CODE",$M_7)."<br>";
											$change_str = $change_str."계좌번호 : ".$M_8."<br>";
											$change_str = $change_str."예금주 : ".$M_9."<br>";
										}

										if ($PAY_TYPE == "card") {
											$change_str = $change_str."신용카드<br>";
											$change_str = $change_str.getDcodeName($conn,"CARD_CODE",$M_7)."<br>";
											$change_str = $change_str."카드번호 : ".$M_8."<br>";
											$change_str = $change_str."유효기간 : ".$M_9." 년 ".$M_10." 월<br>";
										}
									} else {
										$change_str = "미악정";
									}

									$str_m_tel = decrypt($key, $iv, $M_HP);

					?>

							<tr <? if ($j == (sizeof($arr_rs)-1)) {?> class="last" <? } ?> >
								<td><?=$M_ID?></td>
								<td><?=$M_NAME?></td>
								<td><?=$SIDO?></td>
								<td>
								<? if ($IS_PAY == "Y") { echo "약정"; } else { echo "미약정"; } ?>
								</td>
								<td style="text-align:left;padding-left:20px"><?=$pre_str?></td>
								<td style="text-align:left;padding-left:20px"><?=$change_str?>
								</td>
								<td><?=$str_m_tel?></td>
								<td><?=$M_3?></td>
								<td><?=$UP_DATE?></td>
								<td>
									<a href="javascript:js_modify('<?=$SEQ_NO?>','<?=$M_NO?>');"><b>수정승인처리</b></a>
								</td>
							</tr>
						<?			
								}
							} else { 
						?>
							<tr>
								<td align="center" height="50" colspan="11">데이터가 없습니다. </td>
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
					<!--<input type="button" name="aa" value=" 엑셀 리스트 " class="btntxt"  style="cursor:hand" onclick="js_excel_list();">--> 
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
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree."&Ngroup_cd=".$Ngroup_cd;

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
