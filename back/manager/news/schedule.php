<?session_start();?>
<?
# =============================================================================
# File Name    : schedule.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2011.06.01
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../../_common/config.php";
	require "../../../_classes/community/util/util.php";
	require "../../../_classes/community/etc/etc.php";
	require "../../../_classes/community/cschedule/schedule.php";

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

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_comm_name?> 관리자 로그인</title>
<link href="../css/common.css" rel="stylesheet" />
<style>

.calendar {text-align:center; margin-top:10px}

table.schedule { width: 95%; border-top: 1px solid #86a4b3; }
table.schedule th { padding: 9px 0 8px 0; font-weight: normal; color: #86a4b2; border-bottom: 1px solid #d2dfe5; background: #ebf3f6 }
*html table.schedule th { padding: 9px 0 6px 0; }
*+html table.schedule th { padding: 9px 0 6px 0; }
table.schedule th.end { background: #ebf3f6; }

table.schedule tr.bdr_bot {border-bottom:1px solid #ddd;}
table.schedule td.left {border:0; padding-bottom:5px}
table.schedule td.bdrn {border-left:0}
table.schedule td.bdrn_R {border-right:0}
table.schedule td {color: #555555; text-align: left; vertical-align: middle; padding-left:5px; border:1px solid #ddd}
table.schedule td.week {text-align:center; padding-left:0}
table.schedule td.align_R {text-align:right; border:0; padding:5px 10px 20px 0}
table.schedule td.pb_40 {padding-bottom:5px}

</style>
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
<script language="javascript">

function js_add_schedule(str) {

	var url = "pop_schedule.php?menu_cd=<?=$menu_cd?>&sch_date="+str;
	NewWindow(url,'pop_schedule_add','560','720','NO');
}

function js_mod_schedule(str) {

	var url = "pop_schedule.php?menu_cd=<?=$menu_cd?>&seq_no="+str;
	NewWindow(url,'pop_schedule_mod','560','700','NO');
}

function re_load() {
	document.frm.submit();
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

<form name="frm" method="post" action="schedule.php">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
<input type="hidden" name="year" value="<?=$year?>">
<input type="hidden" name="month" value="<?=$month?>">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 관리하실 수 있습니다" cellpadding="0" cellspacing="0" class="schedule">
					<caption><?=$p_menu_name?></caption>
					<colgroup>
						<col width="9%" />
						<col width="13%" />
						<col width="13%" />
						<col width="13%" />
						<col width="13%" />
						<col width="13%" />
						<col width="13%" />
					</colgroup>
					<thead>
					<tr>
						<th colspan="8" class="end">
							<?=$year?> 년 <?=$month?> 월
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>요 일</th>
						<th>일요일</th>
						<th>월요일</th>
						<th>화요일</th>
						<th>수요일</th>
						<th>목요일</th>
						<th>금요일</th>
						<th class="end">토요일</th>
					</tr>
<?
		$we = date("w", strtotime($sDate));	

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
						<td bgcolor="#F6F6F6" width="60" height="60" class="week bdrn">
							<?echo $first_week++?> 주
						</td>

<?
		for ($i = 0 ; $i <= $total_days+($we-1) ; $i++) {
			if (($i == 7) || ($i == 14) || ($i == 21) || ($i == 28) || ($i == 35) || ($i == 42)) {
?>
					</tr>
					<tr align="right">
						<td bgcolor="#F6F6F6" width="60" height="60" class="week bdrn">
							<?echo $first_week++?> 주
						</td>
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
							<table width="100%">
								<tr>
									<td class="align_R">
										<a href="javascript:js_add_schedule('<?=$year?>-<?=$month?>-<?=$day_?>');"><font color="<?=$str_color?>"><?echo $firstDay?></font></a>
									</td>
								</tr>
								<tr>
									<td class="left">
										&nbsp;<?=getCommScheduleDateData($conn, $comm_no, $sch_date, $con_use_tf);?>
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

				$sch_date = $year."-".$pre_month_."-".($pre_day-1);

?>
								<tr>
									<td class="left">
										<?=getCommScheduleDateData($conn, $comm_no, $sch_date, $con_use_tf);?>
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

				$sch_date = $year."-".$next_month_."-".$j_;

?>
								<tr>
									<td class="left">
										<?=getCommScheduleDateData($conn, $comm_no, $sch_date, $con_use_tf);?>
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
				<div class="sp5"></div>
				<table width="100%" border="0" class="calendar">
					<tr bgcolor='#FFFFFF'>
						<td class="center" colspan="2">
							<a href="<?=$PHP_SELF?>?menu_cd=<?=$menu_cd?>&site_code=<?=$site_code?>&year=<?=($year-1)?>&month=12">[<?=($year-1)?> 년 12 월]</a>&nbsp;&nbsp;&nbsp;
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
			<a href='$PHP_SELF?menu_cd=$menu_cd&site_code=$site_code&year=$year&month=$i'>$i 월</a>&nbsp;
		";		
			
	}
?>
						&nbsp;&nbsp;&nbsp;<a href="<?=$PHP_SELF?>?menu_cd=<?=$menu_cd?>&site_code=<?=$site_code?>&year=<?=($year+1)?>&month=01">[<?=($year+1)?> 년 01 월]</a>&nbsp;
						</td>
					</tr>
				</table>
				</fieldset>

			</form>
		</section>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>