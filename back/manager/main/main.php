<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : main.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-12-10
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================

	if ($_SESSION['s_adm_no'] == "") {

		$next_url = "../login.php";

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

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/vacation/vacation.php";
	require "../../_classes/biz/board/board.php";
	

	// 내 남은 연차 조회
	$con_year = date("Y",time());

	$arr_rs = listUserVacationYear($conn, $con_year, "", $_SESSION['s_adm_no']);
	
	$j = 0;

	$VA_CNT						= trim($arr_rs[$j]["VA_CNT"]);
	
	$M_1M							= trim($arr_rs[$j]["1M"]);
	$M_2M							= trim($arr_rs[$j]["2M"]);
	$M_3M							= trim($arr_rs[$j]["3M"]);
	$M_4M							= trim($arr_rs[$j]["4M"]);
	$M_5M							= trim($arr_rs[$j]["5M"]);
	$M_6M							= trim($arr_rs[$j]["6M"]);
	$M_7M							= trim($arr_rs[$j]["7M"]);
	$M_8M							= trim($arr_rs[$j]["8M"]);
	$M_9M							= trim($arr_rs[$j]["9M"]);
	$M_10M						= trim($arr_rs[$j]["10M"]);
	$M_11M						= trim($arr_rs[$j]["11M"]);
	$M_12M						= trim($arr_rs[$j]["12M"]);
	$M_13M						= trim($arr_rs[$j]["13M"]);

	$M_1S							= trim($arr_rs[$j]["1S"]);
	$M_2S							= trim($arr_rs[$j]["2S"]);
	$M_3S							= trim($arr_rs[$j]["3S"]);
	$M_4S							= trim($arr_rs[$j]["4S"]);
	$M_5S							= trim($arr_rs[$j]["5S"]);
	$M_6S							= trim($arr_rs[$j]["6S"]);
	$M_7S							= trim($arr_rs[$j]["7S"]);
	$M_8S							= trim($arr_rs[$j]["8S"]);
	$M_9S							= trim($arr_rs[$j]["9S"]);
	$M_10S						= trim($arr_rs[$j]["10S"]);
	$M_11S						= trim($arr_rs[$j]["11S"]);
	$M_12S						= trim($arr_rs[$j]["12S"]);
	$M_13S						= trim($arr_rs[$j]["13S"]);
	
	$use_tot = $M_1M + $M_2M + $M_3M + $M_4M + $M_5M + $M_6M + $M_7M + $M_8M + $M_9M + $M_10M + $M_11M + $M_12M + $M_13M;  
	$use_sd_tot = $M_1S + $M_2S + $M_3S + $M_4S + $M_5S + $M_6S + $M_7S + $M_8S + $M_9S + $M_10S + $M_11S + $M_12S + $M_13S; 


	$arr_rs_typeA = listMainVacation($conn, "1", $con_year, $_SESSION['s_adm_no']);
	$arr_rs_typeB = listMainVacation($conn, "5", $con_year, $_SESSION['s_adm_no']);
	$arr_rs_typeC = listMainVacation($conn, "6", $con_year, $_SESSION['s_adm_no']);

	$arr_rs_bbs = listBoardMain($conn, "B_1_1", "", "Y", "N", 30);
	
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_header.php";
	require "../../_common/common_script.php";
?>
</head>

<body id="main"> <!-- main-->

<div id="wrap">
	<!-- S: header -->
<?
	require "../../_common/left_area.php";
?>

	<!-- //E: header -->

	<!-- S: container -->
	<div class="container">
		<div class="maincontents">
			<div class="holiday-column">
				<p class="btnleft">
					<button type="button" class="btn-navy" style="width:110px" onClick="document.location='/manager/vacation/vacation_write.php'">연차 등록</button>
					<button type="button" class="btn-navy" style="width:110px" onClick="document.location='/manager/vacation/vacation_list.php'">월별 연차보기</button>
					<button type="button" class="btn-navy" style="width:110px" onClick="document.location='/manager/supplies/supplies_write.php'">장비구매요청</button>
				</p>
				<dl class="holiday-remain">
					<dt>
						<strong>내 남은 연차</strong>
						<p><strong><?=($VA_CNT - $use_tot)?></strong><sub>day</sub></p>
					</dt>
					<dd>
						<p>사용연차 <em><?=$use_tot?>일</em></p>
						<p>개인연차 문의사항은 경영지원팀 김현정 차장님께 문의해주세요.</p>
					</dd>
				</dl>
				<div class="holiday-date">
					<ul class="tabbox">
						<li class="years on"><a href="javascript:commonTab('holiday-date','years')">연차</a></li>
						<li class="smartday"><a href="javascript:commonTab('holiday-date', 'smartday')">스마트데이</a></li>
						<li class="refresh"><a href="javascript:commonTab('holiday-date', 'refresh')">리플레쉬</a></li>
					</ul>
					<div class="tabcontents">
						<div class="tab-hiddencontents years on">
							<ul>
								<? 
									if (sizeof($arr_rs_typeA) > 0) {
										for ($j = 0 ; $j < sizeof($arr_rs_typeA); $j++) {
											$VA_TYPE	= trim($arr_rs_typeA[$j]["VA_TYPE"]);
											$VA_SDATE	= trim($arr_rs_typeA[$j]["VA_SDATE"]);
											$VA_EDATE	= trim($arr_rs_typeA[$j]["VA_EDATE"]);

											if ($VA_SDATE == $VA_EDATE) {
												$date_str = $VA_SDATE;
											} else {
												$date_str = $VA_SDATE."~".$VA_EDATE;
											}

								?>
								<li><label><?=getDcodeName($conn,"VA_TYPE",$VA_TYPE)?></label><p><?=$date_str?></p></li>
								<?
										}
									}
								?>
							</ul>
						</div>
						<div class="tab-hiddencontents smartday">
							<ul>
								<? 
									if (sizeof($arr_rs_typeB) > 0) {
										for ($j = 0 ; $j < sizeof($arr_rs_typeB); $j++) {
											$VA_TYPE	= trim($arr_rs_typeB[$j]["VA_TYPE"]);
											$VA_SDATE	= trim($arr_rs_typeB[$j]["VA_SDATE"]);
											$VA_EDATE	= trim($arr_rs_typeB[$j]["VA_EDATE"]);

											if ($VA_SDATE == $VA_EDATE) {
												$date_str = $VA_SDATE;
											} else {
												$date_str = $VA_SDATE."~".$VA_EDATE;
											}

								?>
								<li><label><?=getDcodeName($conn,"VA_TYPE",$VA_TYPE)?></label><p><?=$date_str?></p></li>
								<?
										}
									}
								?>
							</ul>
						</div>
						<div class="tab-hiddencontents refresh">
							<ul>
								<? 
									if (sizeof($arr_rs_typeC) > 0) {
										for ($j = 0 ; $j < sizeof($arr_rs_typeC); $j++) {
											$VA_TYPE	= trim($arr_rs_typeC[$j]["VA_TYPE"]);
											$VA_SDATE	= trim($arr_rs_typeC[$j]["VA_SDATE"]);
											$VA_EDATE	= trim($arr_rs_typeC[$j]["VA_EDATE"]);

											if ($VA_SDATE == $VA_EDATE) {
												$date_str = $VA_SDATE;
											} else {
												$date_str = $VA_SDATE."~".$VA_EDATE;
											}

								?>
								<li><label><?=getDcodeName($conn,"VA_TYPE",$VA_TYPE)?></label><p><?=$date_str?></p></li>
								<?
										}
									}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="notice-column">
				<div class="noticelist-column">
					<p class="btncenter"><button type="button" class="btn-white" onClick="document.location='/manager/board/board_write.php'">공지등록</button></p>
					<ul>

						<? 
							if (sizeof($arr_rs_bbs) > 0) {
								for ($j = 0 ; $j < sizeof($arr_rs_bbs); $j++) {
									$B_NO				= SetStringFromDB($arr_rs_bbs[$j]["B_NO"]);
									$B_CODE			= SetStringFromDB($arr_rs_bbs[$j]["B_CODE"]);
									$TITLE			= SetStringFromDB($arr_rs_bbs[$j]["TITLE"]);
									$REG_DATE		= trim($arr_rs_bbs[$j]["REG_DATE"]);

									if ($j == 0) {
										$str_class = "class='on'";
										$first_b_no		= $B_NO;
										$first_b_code = $B_CODE;
									} else {

										if (!ChkBoardAsAdmin($conn, $B_CODE, $B_NO, $_SESSION['s_adm_no'])) {
											$str_class = "class='new'";
										} else {
											$str_class = "";
										}
									}



						?>
						<li <?=$str_class?> id="<?=$B_CODE?>_<?=$B_NO?>"><a href="javascript:void(0)" onClick="js_view('<?=$B_CODE?>', '<?=$B_NO?>');"><strong><?=$TITLE?></strong><span><?=$REG_DATE?></span></a></li>
						<?
								}
							}
						?>
						<!--
						<li><a href="#"><strong>신한은행 급여통장 발급완료 공지사항</strong><span>2018.12.03</span></a></li>
						<li><a href="#"><strong>법정 의무교육 수강안내</strong><span>2018.12.03</span></a></li>
						<li class="new"><a href="#"><strong>법인카드 사용 관련</strong><span>2018.12.03</span></a></li>
						<li><a href="#"><strong>조직도 변경</strong><span>2018.12.03</span></a></li>
						<li><a href="#"><strong>6월 생일파티 안내</strong><span>2018.12.03</span></a></li>
						<li><a href="#"><strong>5월 생일파티 안내</strong><span>2018.12.03</span></a></li>
						<li><a href="#"><strong>4월 생일파티 안내</strong><span>2018.12.03</span></a></li>
						-->

					</ul>
				</div>
				<div class="noticeview-column" id="read_bbs">
				<!-- 공지사항 읽기 영억 -->
				</div>
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
<script>
var windowWidth = $(window).width();
var windowHeight = $(window).height();
$(".holiday-column .tab-hiddencontents").height(windowHeight-$(".holiday-column .btnleft").innerHeight()-$(".holiday-column .holiday-remain").innerHeight()-$(".holiday-column ul.tabbox").innerHeight() - 190);
$(".noticelist-column > ul").height(windowHeight-$(".noticelist-column .btncenter").innerHeight());
$(".replylist").height(windowHeight-$(".noticeview-column dl").innerHeight()-$(".noticeview-column .btnright").innerHeight() - 20);
$(".maincontents > div").css("height", windowHeight+"px");

	$(document).ready(function() {
		js_view('<?=$first_b_code?>','<?=$first_b_no?>');
	});
	
	function js_view(b_code, b_no) {

		$(".noticelist-column").find("ul>li").removeClass("on");
		$("#"+b_code+"_"+b_no).addClass("on");


		// ajax 로 공지사항 상세 가지고 오기
		var request = $.ajax({
			url:"/_common/board/ajax_board_read.php",
			type:"POST",
			data:{b_code:b_code, b_no:b_no},
			dataType:"html"
		});

		request.done(function(msg) {
			$("#read_bbs").html(msg);
			
			$("#"+b_code+"_"+b_no).removeClass("new");
			js_getList(b_code, b_no);

		});

		request.fail(function(jqXHR, textStatus) {
		});


	}


	function js_getList(b_code, b_no) {
		
		var frm		= document.frm;
		var mode	= "L";
		var b			= b_code
		var bn		= b_no;

		$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
			{ mode:mode, b:b, bn:bn}, 
			function(data){
				//data = decodeURIComponent(data);
				$("#div_recomm_list").html(data); 
				
				$(".replylist").height(windowHeight-$(".noticeview-column dl").innerHeight()-$(".noticeview-column .btnright").innerHeight() - 20);

			}
		);
	}

	function js_comment_save() {

		var frm		= document.frm_comment;
		var mode	= "I";
		var b			= frm.b_code.value;
		var bn		= frm.b_no.value;
		var secret_tf = "";
		if ($("#secret_tf").is(":checked") == true) {
			secret_tf = "Y";
		}

		var contents		= $("#contents").val();

		if (bn == "") {
			return;
		}
		
		var writer_nm = "";
		var writer_pw = "";

		if (contents.trim() == "") {
			alert("내용를 입력해 주십시오.");
			$("#contents").focus();
			return;
		}

		//writer_nm = encodeURIComponent(writer_nm);
		//writer_pw = encodeURIComponent(writer_pw);
		//contents	= encodeURIComponent(contents);
		
		var request = $.ajax({
			url: "<?=$g_base_dir?>/_common/board/ajax.comment.php",
			type: "POST",
			data: {mode: mode, b:b, bn:bn, writer_nm:writer_nm, writer_pw:writer_pw, secret_tf:secret_tf, contents:contents},
			dataType: "html"
		});

		request.done(function(msg) {
			//msg = decodeURIComponent(msg);
			if (msg != "") {
				alert(msg);
			} else {
				$("#writer_nm").val("");
				$("#writer_pw").val("");
				document.frm_comment.secret_tf.checked = false;
				$("#contents").val("");
				js_getList(b, bn);
			}

			return false;
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed:" + textStatus);
			return false;
		});

	}


	function js_comment_delete(cno) {

		var frm = document.frm_comment;

		bDelOK = confirm('댓글을 삭제 하시겠습니까?');

		if (bDelOK == true) {
	
			var mode	= "D";
			var b			= frm.b_code.value;
			var bn		= frm.b_no.value;
			var cno		= cno;

			$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
				{ mode:mode, b:b, bn:bn, cno:cno}, 
				function(msg){
					if (msg != "") {
						alert(msg);
					} else {
						js_getList(b, bn);
					}
				}
			);
		}
	}

	var active_obj = "";
	var active_act = "";

	function js_comment_modify(cno) {
		var obj = "#reply_"+cno;
		var con_obj		= "#contents_"+cno;
		var secret_obj	= "#secret_tf_"+cno;
		var mode	= "S";

		$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
			{ mode:mode, cno:cno}, 
			function(msg){
				if (msg == "작성자만 수정 가능합니다.") {
					alert(msg);
				} else {
					$(obj).html(msg);
				}
			}
		);
		
		if ($(obj).css("display") == "none") {

			if (active_obj) {
				$(active_obj).hide();
			}
			$(obj).show();
			active_obj = obj;
			active_act = mode;
			$("#write_comment").hide();

		} else {
			if (active_act == mode) {
				$(obj).hide();
				$("#write_comment").show();
			} else {
				active_act = mode;
			}
		}
	}

	function js_comment_reply_save(cno) {
		
		var frm = document.frm_comment;
		var frm_omment = eval("document.frm_comment_"+cno);
		
		if (frm_omment.mode.value == "reply") {
			var mode	= "IR";
		} else {
			var mode	= "U";
		}
		var b			= frm.b_code.value;
		var bn		= frm.b_no.value;
		var secret_tf = "";
		
		if (frm_omment.secret_tf.checked == true) {
			secret_tf = "Y";
		}

		var contents		= frm_omment.contents.value;

		if (bn == "") {
			return;
		}
		
		var writer_nm = "";
		var writer_pw = "";

		if (contents.trim() == "") {
			alert("내용를 입력해 주십시오.");
			frm_omment.contents.focus();
			return;
		}

		//writer_nm = encodeURIComponent(writer_nm);
		//writer_pw = encodeURIComponent(writer_pw);
		//contents	= encodeURIComponent(contents);

		var request = $.ajax({
			url: "<?=$g_base_dir?>/_common/board/ajax.comment.php",
			type: "POST",
			data: {mode: mode, b:b, bn:bn, writer_nm:writer_nm, writer_pw:writer_pw, secret_tf:secret_tf, contents:contents, cno:cno},
			dataType: "html"
		});
		

		request.done(function(msg) {
			//msg = decodeURIComponent(msg);
			if (msg != "") {
				alert(msg);
			} else {
				frm_omment.secret_tf.checked = false;
				frm_omment.contents.value = "";
				js_getList(b, bn);
				$("#write_comment").show();
			}
			return false;
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed:" + textStatus);
			return false;
		});

	}



</script>
</body>
</html>
<?
	mysql_close($conn);
?>