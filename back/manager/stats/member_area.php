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

	$menu_right = " ST003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/stats/stats.php";
#====================================================================
# Request Parameter
#====================================================================
	
	// 로그인한 관리자 권한에 따라 보여주는 내용이 다릅니다. -- 여기 부터

	if (($_SESSION['s_adm_position_code'] == "") || ($_SESSION['s_adm_position_code'] == "중앙당")) {
		$sel_area_cd = trim($sel_area_cd);
	} else {
		$sel_area_cd = $_SESSION['s_adm_position_code'];
	}

	if (($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) {
		$sel_party = trim($sel_party);
	} else {
		$sel_party = $_SESSION['s_adm_dept_code'];
	}
	
	// --------------------------------------------------------- 여기 까지

	$arr_rs = memberStatsAsArea($conn, $start_date, $end_date, $sel_pay_type, $sel_area_cd, $sel_party, $is_agree, $m_online_flag, $group_cd);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "지역별 당원 현황  조회", "Stats");

	if($group_cd){
			if(strlen($group_cd) == 3){
				$group_cd_01=$group_cd;
			}
			if(strlen($group_cd) == 6){
				$group_cd_01=substr($group_cd,0,3);
				$group_cd_02=substr($group_cd,0,6);
			}
			if(strlen($group_cd) == 9){
				$group_cd_01=substr($group_cd,0,3);
				$group_cd_02=substr($group_cd,0,6);
				$group_cd_03=substr($group_cd,0,9);
			}

			if(strlen($group_cd) == 12){
				$group_cd_01=substr($group_cd,0,3);
				$group_cd_02=substr($group_cd,0,6);
				$group_cd_03=substr($group_cd,0,9);
				$group_cd_04=substr($group_cd,0,12);
			}

			if(strlen($group_cd) == 15){
				$group_cd_01=substr($group_cd,0,3);
				$group_cd_02=substr($group_cd,0,6);
				$group_cd_03=substr($group_cd,0,9);
				$group_cd_04=substr($group_cd,0,12);
				$group_cd_05=substr($group_cd,0,15);
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
		
		frm.method = "post";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

	function js_reset() {
		var frm = document.frm;
		frm.reset();
		$(".date").val("");
	}

	function js_sel_party(group_cd_01, group_cd_02, group_cd_03, group_cd_04) {

		var frm = document.frm;
		var party_val = "";

		party_val = frm.sel_party.value;

		if (party_val=="") {
			$("#add_group").hide();
		}else{
		
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

		$("#group_cd").val("");
		if ((group_cd_01 != "") && (group_cd_01 != null)) $("#group_cd").val(group_cd_01);
		if ((group_cd_02 != "") && (group_cd_02 != null)) $("#group_cd").val(group_cd_02);
		if ((group_cd_03 != "") && (group_cd_03 != null)) $("#group_cd").val(group_cd_03);
		if ((group_cd_04 != "") && (group_cd_04 != null)) $("#group_cd").val(group_cd_04);

	}

	function js_excel_list() {

		var frm = document.frm;
		frm.target = "";
		frm.action = "<?=str_replace("area","excel_area",$_SERVER[PHP_SELF])?>";
		frm.submit();

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
<input type="hidden" name="mode" value="">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
<input type="hidden" name="group_cd" id="group_cd"/>
				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							<tr>
								<th>가입일</th>
								<td >
									<input type="text" name="start_date" id="start_date" class="date" value="<?=$start_date?>" style="width:100px;border:1px solid #dfdfdf;" readonly="1"> ~
									<input type="text" name="end_date" id="end_date" class="date" value="<?=$end_date?>" style="width:100px;border:1px solid #dfdfdf;" readonly="1">&nbsp;&nbsp;&nbsp;
									<input type="button" name="aa" value="가입일 초기화" class="btntxt"  style="cursor:pointer;height:25px;width:85px;" onclick="js_reset();"> 
								</td>
								<th>가입경로</th>
								<td>
									<select name="online_flag" style="width:150px">
										<option value="">가입경로 선택</option>
										<option value="on" <?if($online_flag=="on"){?>selected<?}?>>온라인</option>
										<option value="off" <?if($online_flag=="off"){?>selected<?}?>>관리자</option>
									</select>
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
										if (($_SESSION['s_adm_dept_code'] == "") || ($_SESSION['s_adm_dept_code'] == "지역")) {
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
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="검색" /></a>
								</td>
							</tr>
						</tbody>
					</table>

						<?
							$max_cnt = 0;
							$min_cnt = 0;
							$tot_cnt = 0;

							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								
								$IN_COUNT					= trim($arr_rs[$j]["IN_COUNT"]);
								$OUT_COUNT				= trim($arr_rs[$j]["OUT_COUNT"]);
								
								$tot_cnt = $tot_cnt + $IN_COUNT;

								if ($IN_COUNT > $max_cnt) {
									$max_cnt = $IN_COUNT;
								}

								if ($IN_COUNT < $min_cnt) {
									$min_cnt = $IN_COUNT;
								}
							}
						?>

					<div class="expArea">
						<ul class="fLeft">
							<li class="total">총 가입자 수 <?=number_format($tot_cnt)?> 명</li>
						</ul>
						<p class="fRight">
							<? if ($sPageRight_F == "Y") { ?>
							<input type="button" name="aa" value=" 엑셀 리스트 " style="cursor:pointer;height:25px;" onclick="js_excel_list();"> 
							<? } ?>
						</p>
					</div>
					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm">
				
						<colgroup>
							<col width="10%" /><!-- 일자 (요일) -->
							<col width="90%" /><!-- 가입 당원수 -->
						</colgroup>

						<thead>
							<tr>
								<th>지역</th>
								<th>가입당원수</th>
							</tr>
						</thead>
						<tbody>
						<?
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

								$S_SIDO						= trim($arr_rs[$j]["S_SIDO"]);
								$DW								= trim($arr_rs[$j]["DW"]);
								$IN_COUNT					= trim($arr_rs[$j]["IN_COUNT"]);
								$OUT_COUNT				= trim($arr_rs[$j]["OUT_COUNT"]);

								$max_width = 800;
								
								if ($max_cnt == 0) $max_cnt = 1;

								$img_width=( $IN_COUNT / $max_cnt) * $max_width;
								$out_img_width=( $OUT_COUNT / $max_cnt) * $max_width;

					?>
							<tr <? if ($j == (sizeof($arr_rs)-1)) {?> class="last" <? } ?> >

								<td><?=$S_SIDO?></td>
								<td style="text-align:left">
									입당 : <img src='../images/etc/red.gif' width='<?=$img_width?>' height='10'> (<?=number_format($IN_COUNT)?> 명)
									<div class="sp5"></div>
									탈당 : <img src='../images/etc/sur.gif' width='<?=$out_img_width?>' height='10'> (<?=number_format($OUT_COUNT)?> 명)
								</td>
							</tr>
					<?
							}
					?>

						</tbody>

						</tbody>
					</table>
				</fieldset>
			</form>
		</section>
		<iframe src="about:blank" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>

<?
	if($group_cd || $sel_party) {
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
