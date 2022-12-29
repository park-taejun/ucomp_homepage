<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : main.php
# Modlue       : 
# Writer       : Park Chan Ho / JeGal Jeong
# Create Date  : 2018-12-10
# Modify Date  : 2021-08-19
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
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/vacation/vacation.php";
	require "../../_classes/biz/board/board.php";
	// require "../../_classes/biz/menu/menu_ptj.php";
	   
	$arr_rs_bbs = listBoardMain($conn, "B_1_1", "", "Y", "N", 30);
#====================================================================
# Request Parameter
#====================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	$order_field				= $_POST['order_field']!=''?$_POST['order_field']:$_GET['order_field'];
	$order_str					= $_POST['order_str']!=''?$_POST['order_str']:$_GET['order_str'];
	
	#List Parameter

	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$order_field		= SetStringToDB($order_field);
	$order_str			= SetStringToDB($order_str);

	$use_tf			= SetStringToDB($use_tf);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

#===============================================================
# Get Search list count
#===============================================================

	// $arr_rs = listAdminMenu($conn, $use_tf, $del_tf, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 메뉴 조회", "List");

	#echo sizeof($arr_rs);
	
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	// require "../../_common/common_header.php";
	require "../../_common/common_script.php";
?>

<style>
/* 형광펜 효과를 위해 2022-04-15 */
.highlight{
  display: inline;
  box-shadow: inset 0 -10px 0 #fff9c7; 
  /*-10px은 highlight의 두께*/
}

.highlight_word:after{
  content:"";
  width: 0;
  height: 10px;
  display: inline-block;
  background: #fff9c7;
}
</style>
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
			 
			<div class="notice-column">
				<div class="noticelist-column">
					<p class="btncenter"><button type="button" class="btn-white" onClick="document.location='/manager/board/board_write.php'">공지등록</button></p>
					<ul>

						<? 
							if (sizeof($arr_rs_bbs) > 0) {
								for ($j = 0 ; $j < sizeof($arr_rs_bbs); $j++) {
									$B_NO			= SetStringFromDB($arr_rs_bbs[$j]["B_NO"]);
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
					</ul>
				</div>
				<div class="noticeview-column" id="read_bbs">
				<!-- 공지사항 읽기 영억 -->
				</div>
			</div>
		</div>
	</div>
	<!-- //E: container -->

	<!-- S: modalpop -->
	<div class="modalpop">

		<form name="frm" id="frm" method="post">
		<input type="hidden" name="birthday" value="">
		<input type="hidden" name="hiredate" value="">
			<div class="popupwrap pop-planner" id="planner_popup" >
			</div>
		</form>
	</div>

	<!-- //E: modalpop -->

	<!-- S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>

<script type="text/javascript" src="/admin/js/common_ui.js"></script>
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

/*
	function js_open_modal(h, b) {

		var mode = "OK";

		var request = $.ajax({
			url:"/manager/main/main_popup_dml.php",
			type:"POST",
			data:{mode:mode, hiredate:h, birthday:b},
			dataType:"html"
		});

		request.done(function(data) {
			$("#planner_popup").html(data);
				modalView('pop-planner');
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

	}
*/
</script>

<?
//기념일 팝업을 위해 추가 2021.08.19

	$s_adm_no=$_SESSION['s_adm_no'];

	if (($s_adm_no =="25") || ($s_adm_no =="14") || ($s_adm_no =="1") || ($s_adm_no == "178") || ($s_adm_no =="4")) {

		$arr_rs_hiredate = listAdminHireDateTest($conn, $con_group_no, $con_headquarters_code, $con_dept_code, $con_position_code, "Y", "N");
		$name_list = array();
		$year_list = array();
		$event_cnt = 0;
		$birthday_name_list = array();
		$birthday_cnt = 0;

		if (sizeof($arr_rs_hiredate) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs_hiredate); $j++) {
				$ADM_NO					= trim($arr_rs_hiredate[$j]["ADM_NO"]);
				$ADM_NAME				= SetStringFromDB($arr_rs_hiredate[$j]["ADM_NAME"]);
				$ENTER_DATE				= trim($arr_rs_hiredate[$j]["ENTER_DATE"]);
				$ADM_BIRTHDAY			= trim($arr_rs_hiredate[$j]["ADM_BIRTHDAY"]);
				$today_y = date("Y");
				$today_md = date("m-d");
				$event_year = 0;
				$event_hire = "";

				//입사일 추출
				$enter_y = date("Y",strtotime($ENTER_DATE));
				$enter_md = date("m-d",strtotime($ENTER_DATE));

				if ($today_y - $enter_y >= 1) {
					$event_year =$today_y - $enter_y;
				}

				if ($enter_md == $today_md) {
					$event_hire = $event_year ."주년 입사기념일!";
				}

				if ($event_hire <> "" and $event_year > 0) {  //기념일인 직원만 출력
					$name_list[$event_cnt] = $ADM_NAME;
					$year_list[$event_cnt] = $event_year;
					$event_cnt = $event_cnt + 1;
				}
				//입사일 end

				//생일 추출
				$birthday_md = date("m-d",strtotime($ADM_BIRTHDAY));

				$timestamp = strtotime("+1 week");  //일주일 뒤
				$week_md = date("m-d", $timestamp);
				$event_birthday = "";

				if ($birthday_md == $week_md) {
					$event_birthday = "생일을 축하합니다!";
				}

				if ($event_birthday <> "") { //생일인 직원만 출력
					$birthday_name_list[$birthday_cnt] = $ADM_NAME;
					$birthday_cnt = $birthday_cnt + 1;
				}
				//생일 end			

			}

			if (($event_cnt > 0) || ($birthday_cnt > 0)) {
				$str_list = "";
				for ($i = 0 ; $i < sizeof($name_list) ; $i++){
					$str_list .= $name_list[$i]."(". $year_list[$i]."주년), ";
				}
				if ($str_list <> ""){
					$str_list = substr($str_list, 0, -2); 
				}

				$str_birthday_list = "";
				for ($i = 0 ; $i < sizeof($birthday_name_list) ; $i++){
					$str_birthday_list .= $birthday_name_list[$i].", ";
				}
				if ($str_birthday_list <> ""){
					$str_birthday_list = substr($str_birthday_list, 0, -2); 
				}
	
	?>
					<!--
					<script>
						js_open_modal("<?=$str_list?>","<?=$str_birthday_list?>"); 
					</script>
					-->
	<?	
					break;
			}
		}
	}
?>

</body>
</html>
<?
	mysql_close($conn);
?>