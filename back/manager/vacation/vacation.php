<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : vacation.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-10-22
# Modify Date  : 
#	Copyright : Copyright @ucom Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "VA003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/vacation/vacation.php";
	require "../../_classes/biz/holiday/holiday.php";
	require "../../_classes/biz/admin/admin.php";

	$year				= $_POST['year']!=''?$_POST['year']:$_GET['year'];
	$month			= $_POST['month']!=''?$_POST['month']:$_GET['month'];

	function Get_TotalDays($year,$month) { 
		$day = 1; 
		while(checkdate($month,$day,$year)) { 
			$day ++ ; 
		} 
		$day--; 
		return $day; 
	}

	if(!$year) {
		$year = date("Y",time());
	} 

	if(!$month) {
		$month = date("m",time());
	}

	$sDate = $year."-".$month."-01";

	# Get_TotalDays 함수로 이번달의 총 일자를 구한다
	# 몇일 까지 있는지를 검사한후 쿼리해서 결과 값을 가져온다

	$total_days = Get_TotalDays($year,$month) ;
	$this_date = date("Ymd",strtotime("0 day"));

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "연차 관리 조회", "List");

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript" >

function re_load() {
	document.frm.submit();
}


function js_modify(seq_no) {
	document.location = "vacation_write.php?mode=S&seq_no="+seq_no;
}

function js_write() {
	document.location = "vacation_write.php";
}

	function js_layer_show(seq_no) {
		document.getElementById(seq_no).style.display = "block";
	}

	function js_layer_hide(seq_no) {
		document.getElementById(seq_no).style.display = "none";
	}

	$(document).on("change", "#con_dept_code", function() { 
		var frm = document.frm;
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.method = "post";
		frm.target = "";
		frm.submit();
	});

	$(document).on("change", "#con_va_user", function() { 
		var frm = document.frm;
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.method = "post";
		frm.target = "";
		frm.submit();
	});

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
					<button type="button" class="btn-navy" onClick="js_write();">등록하기</button>
				</h3>

<form name="frm" method="post" action="vacation.php">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="year" value="<?=$year?>">
<input type="hidden" name="month" value="<?=$month?>">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">


				<div class="boardlist search">


					<table>
						<colgroup>
							<col style="width:130px" />
							<col style="width:23%" />
							<col style="width:130px" />
							<col style="width:auto" />
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">부서명</th>
								<td>
									<span class="optionbox">
										<?= makeSelectBox($conn,"DEPT","con_dept_code","125px","전체","",$con_dept_code)?>
									</span>
								</td>
								<th scope="row">직원명</th>
								<td>
									<span class="optionbox">
										<?=makeEmpSelectBox($conn, "con_va_user" , "200px" , " 전체 ", "", $con_va_user)?>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<p class="btnleft">
					<span class="btnset">
						<button type="button" class="btn-border-white on" onClick="document.location='vacation.php'">달력</button>
						<button type="button" class="btn-border-white" onClick="document.location='vacation_list.php'">리스트</button><!-- 활성화시 on클래스 -->
					</span>
				</p>
				<div class="boardlist calendar">

					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:16%" />
							<col style="width:16%" />
							<col style="width:16%" />
							<col style="width:16%" />
							<col style="width:16%" />
							<col style="width:10%" />
						</colgroup>
						<thead>
							<tr>
								<th colspan="8">
									<?=$year?> 년 <?=$month?> 월
								</th>
							</tr>
							<tr>
								<th scope="col">일요일</th>
								<th scope="col">월요일</th>
								<th scope="col">화요일</th>
								<th scope="col">수요일</th>
								<th scope="col">목요일</th>
								<th scope="col">금요일</th>
								<th scope="col">토요일</th>
							</tr>
						</thead>
						<tbody>
<?
		$we = date("w", strtotime($sDate));

		//echo $we;
		//echo $sDate;

		if ($we == 0) {
			$start_day = 0;
			$end_day = 6;
		} else if ($we == 1) {
			$start_day = -1;
			$end_day = 5;
		} else if ($we == 2) {
			$start_day = -2;
			$end_day = 4;
		} else if ($we == 3) {
			$start_day = -3;
			$end_day = 3;
		} else if ($we == 4) {
			$start_day = -4;
			$end_day = 2;
		} else if ($we == 5) {
			$start_day = -5;
			$end_day = 2;
		} else if ($we == 6) {
			$start_day = -6;
			$end_day = 1;
		}
		
		# 이전달 마지막 날짜 구하기

		if ($month != "01") {
			$pre_month = $month - 1;
			$pre_year = $year;
		} else {
			$pre_month = "12";	
			$pre_year = $year - 1;
		}

		$pre_total_days = Get_TotalDays($pre_year,$pre_month);
		
		$pre_day = $pre_total_days - ($we - 1) ;

		//echo $pre_total_days."<br>";
		//echo $pre_day;

		# 다음달 첫날 구하기

		if ($month != "12") {
			$next_month = $month + 1;
			$next_year = $year;
		} else {
			
			$next_month = "01";	
			$next_year = $year + 1;
		}

		$next_sDate = $next_year."-".$next_month."-01";
		$next_we = date("w", strtotime($next_sDate));

		//echo $next_sDate."<br>";
		//echo $next_we;

		$firstDay = 0;
		$first_week = 1;
		$week_total = 0;
		$month_total = 0;
		$sun_total = 0;
		$mon_total = 0;
		$tus_total = 0;
		$wed_total = 0;
		$thu_total = 0;
		$fri_total = 0;
		$sat_total = 0;

?>
							<tr>
								<!-- <th class="week bdrn">
									<?echo $first_week++?> 주
								</th> -->

<?
		//echo $total_days+($we-1);

		for ($i = 0 ; $i <= $total_days+($we-1) ; $i++) {
			if (($i == 7) || ($i == 14) || ($i == 21) || ($i == 28) || ($i == 35) || ($i == 42)) {
?>
							</tr>
							<tr align="right">
								<!-- <th class="week bdrn">
									<?echo $first_week++?> 주
								</th> -->
<?
				$week_total = 0;
			}
?>
								<td valign="top">
<?	
			if ($i == $we) {
				$firstDay = 1;
			}
			
			if ($firstDay > 0) {
				$str_color = "blue";
				if ($i%7 == 0) {
					$str_color = "red";
				}
				if ($i%7 == 6) {
					$str_color = "orange";
				}
				if (strlen($firstDay) == 1) {
					$day_ = "0".$firstDay;
				} else {
					$day_ = $firstDay;
				}

				$sch_date = $year."-".$month."-".$day_;
?>
									<table width="100%" border="0">
										<tr>
											<td class="pname align_R">
												<font color="<?=$str_color?>"><?echo $firstDay?></font>
											</td>
										</tr>
										<tr>
											<td class="left">
												<?=getHolidayDate($conn, $sch_date);?>
												<?=getVacationDateWithCondition($conn, $sch_date, $sPageRight_I, $s_adm_no, $con_dept_code, $con_va_user);?>&nbsp;
											</td>
										</tr>
									</table>
<?						 
				$firstDay++;
			} else {

?>
									<table width="100%">
										<tr>
											<td class="align_R pb_40">
												<font color="silver"><?echo $pre_day++?></font>
											</td>
										</tr>
<?
				if (strlen($pre_month) == 1) {
					$pre_month_ = "0".$pre_month;
				} else {
					$pre_month_ = $pre_month;
				}

				if ($pre_month_ == "12") {
					$pre_year_ = $year - 1;
				} else {
					$pre_year_ = $year;
				}
				
				$sch_date = $pre_year_."-".$pre_month_."-".($pre_day-1);

?>
										<tr>
											<td class="left">
												<?=getHolidayDate($conn, $sch_date);?>
												<?=getVacationDateWithCondition($conn, $sch_date, $sPageRight_I, $s_adm_no, $con_dept_code, $con_va_user);?>&nbsp;
											</td>
										</tr>
									</table>
<?											
			}

			if ($firstDay-1 == $total_days) {
				if($next_we	!= 0) {
					for ($j = 1; $j < (8 - $next_we); $j++) {
?>
								</td>
								<td valign="top">
									<table width="100%">
										<tr>
											<td class="align_R pb_40">
												<font color="silver"><?echo $j?></font>
											</td>
										</tr>
<?

						if (strlen($j) == 1) {
							$j_ = "0".$j;
						} else {
							$j_ = $j;
						}

						if (strlen($next_month) == 1) {
							$next_month_ = "0".$next_month;
						} else {
							$next_month_ = $next_month;
						}

						if ($next_month_ == "01") {
							$next_year_ = $year + 1;
						} else {
							$next_year_ = $year;
						}

						$sch_date = $next_year_."-".$next_month_."-".$j_;

?>
										<tr>
											<td class="left">
												<?=getHolidayDate($conn, $sch_date);?>
												<?=getVacationDateWithCondition($conn, $sch_date, $sPageRight_I, $s_adm_no, $con_dept_code, $con_va_user);?>&nbsp;
											</td>
										</tr>
									</table>
<?
					}
				}		
			}
?>
								</td>
<?
		}
?>
							</tbody>
						</table>
				</div>

				<div class="sp15"></div>

				<table width="100%" border="0" class="calendar-paging">
					<tr bgcolor='#FFFFFF'>
						<td align="center" colspan="2">
							<a href="<?=$PHP_SELF?>?site_code=<?=$site_code?>&con_dept_code=<?=$con_dept_code?>&con_va_user=<?=$con_va_user?>&year=<?=($year-1)?>&month=12">[<?=($year-1)?> 년 12 월]</a>&nbsp;&nbsp;&nbsp;
<?
	# 월별 링크를 출력한다
	
	for($i=01;$i<=12;$i++) {
		# $i 의 길이를 측정해서 1 이라면 앞자리에 0 을 붙여 준다
		
		$i_len = strlen($i);
		$zero = 0;
		if($i_len == 1) {
			$i = $zero.$i;
		}
		# $i의 달을 쿼리해서 카운터가 있으면 링크를 출력하고 없다면 링크 출력을 하지 않눈다

		echo "
			<a href='$PHP_SELF?site_code=$site_code&con_dept_code=$con_dept_code&con_va_user=$con_va_user&year=$year&month=$i'>$i 월</a>&nbsp;
		";		
			
	}
?>
						&nbsp;&nbsp;&nbsp;<a href="<?=$PHP_SELF?>?site_code=<?=$site_code?>&con_dept_code=<?=$con_dept_code?>&con_va_user=<?=$con_va_user?>&year=<?=($year+1)?>&month=01">[<?=($year+1)?> 년 01 월]</a>&nbsp;
						</td>
					</tr>
				</table>

			</div>
		</div>
	</div>
</form>

	<!-- //S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>