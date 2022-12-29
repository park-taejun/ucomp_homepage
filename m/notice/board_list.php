<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : board_list.php
# Modlue       : 
# Writer       : JeGal Jeong 
# Create Date  : 2022-05-10
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$b_code			= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_code			= "B_1_1"; 

	$menu_right = $b_code; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_common/common_header_mobile.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/board/board.php";
	require "../../_classes/biz/board/board_comment.php";
	require "../../_classes/biz/member/member.php";
	require "../../_classes/biz/admin/admin.php";
	
#==============================================================================
# Request Parameter
#==============================================================================
	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$config_no					= $_POST['config_no']!=''?$_POST['config_no']:$_GET['config_no'];
	$b_code							= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_no								= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];

	$con_cate_01				= $_POST['con_cate_01']!=''?$_POST['con_cate_01']:$_GET['con_cate_01'];
	$con_cate_02				= $_POST['con_cate_02']!=''?$_POST['con_cate_02']:$_GET['con_cate_02'];
	$con_cate_03				= $_POST['con_cate_03']!=''?$_POST['con_cate_03']:$_GET['con_cate_03'];
	$con_cate_04				= $_POST['con_cate_04']!=''?$_POST['con_cate_04']:$_GET['con_cate_04'];

	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];

	$mode			= SetStringToDB($mode);
	if ($mode == "") $mode = "L";

	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf				= SetStringToDB($use_tf);
	$b_code				= SetStringToDB($b_code);
	$b_no					= SetStringToDB($b_no);

	$bb_code = trim($bb_code);

	if ($b_code == "")
		$b_code = "B_1_1";

#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/config_info.php";
#====================================================================
# Board Config End
#====================================================================

	if ($mode == "T") {
		updateBoardUseTF($conn, $use_tf, $s_adm_no, $bb_code, $bb_no);
	}

	if ($mode == "D") {

		// 삭제 권한 관련 입니다.
		$del_ok = "N";
		if ($_SESSION['s_adm_no'] && $arr_page_nm[1] == "manager") {
			if ($sPageRight_D == "Y") {
				$del_ok = "Y";
			}
		}
		
		if ($del_ok == "Y") {
			$row_cnt = count($chk);
			for ($k = 0; $k < $row_cnt; $k++) {
				$tmp_b_no = (int)$chk[$k];

				$result= deleteBoard($conn, $s_adm_no, $b_code, $tmp_b_no);

			}
		}

	}

#====================================================================
# Request Parameter
#====================================================================
	if ($nPage == 0) $nPage = "1";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$con_cate_01	= SetStringToDB($con_cate_01);
	$con_cate_02	= SetStringToDB($con_cate_02);
	$con_cate_03	= SetStringToDB($con_cate_03);
	$keyword			= SetStringToDB($keyword);

	//$search_field		= trim($search_field);
	$search_field		= "ALL"; //모바일은 무조건 모두
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
		$nPageSize = 200;
	}

	$nPageBlock	= 200;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt = totalCntBoard($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listBoard($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	//$arr_rs_top = listBoardTop($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

	//$arr_rs_main = listBoardMainDisp($conn, $b_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $reply_state, $con_use_tf, $del_tf, $search_field, $search_str);

?>

<!DOCTYPE html>
<html lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/m_common_script.php";
?>
	<link rel="stylesheet" type="text/css" href="/m/assets/css/layout.front.css">
	<script language="javascript" type="text/javascript">

/*
	function js_getList() {
		
		var frm		= document.frm;
		var mode	= "L";
		var b			= frm.b.value;
		var bn		= frm.bn.value;

		$.get("<?=$g_base_dir?>/_common/board/ajax.comment.php", 
			{ mode:mode, b:b, bn:bn}, 
			function(data){
				//data = decodeURIComponent(data);
				$("#div_recomm_list").html(data); 
			}
		);
	}
*/

		// 조회 버튼 클릭 시 
		function js_search() {
			var frm = document.frm;
			
			//frm.nPage.value = "1";
			frm.method = "post";
			frm.target = "";
			//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
			frm.action = "board_list.php";
			frm.submit();
		}

		// 조회 버튼 클릭 시 
		function js_search_must_read(f) {
			var frm = f;
			frm.method = "post";
			frm.target = "";

			//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
			frm.action = "board_list.php";
			frm.submit();
		}

		function js_comment_save(f, bno, cnt) {

			var frm		= f;
			var mode	= "I";
			var b			= frm.b.value;
			var bn		= frm.bn.value;
			frm.mode.value = mode;
			//var secret_tf = "";
			//if (frm.secret_tf.value.is(":checked") == true) {
			//	secret_tf = "Y";
			//}

			var contents		= frm.contents.value;

			if (bn == "") {
				return false;
			}
			
			var writer_nm = "";
			var writer_pw = "";

			if (contents.trim() == "") {
				alert("내용를 입력해 주십시오.");
				frm.contents.focus();
				return false;
			}

			//writer_nm = encodeURIComponent(writer_nm);
			//writer_pw = encodeURIComponent(writer_pw);
			//contents	= encodeURIComponent(contents);

			$.ajax({
				url: "/_common/board/ajax.comment.mobile.php",
				type: "POST",
				data: {mode: mode, b:b, bn:bn, contents:contents},
				dataType: "html",
				success: function(result) {
					$("#comment-display_"+bno).html(result);
				},
				error: function(request, status, error){
					alert("code"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
			
			return false;

		}

	function js_comment_modify(cno, i, t) {

		var obj		= "#contents_modify_"+i+"_"+t;
		var mode	= "S";
/*
		$.get("<?=$g_base_dir?>/_common/board/ajax.comment.mobile.php", 
			{ mode:mode, cno:cno}, 
			function(msg){
				if (msg == "작성자만 수정 가능합니다.") {
					alert(msg);
				} else {
					$(obj).html(msg);
				}
			}
		);
*/

		if ($(obj).css("display") == "none") {
			$(obj).show();
		} else {
			$(obj).hide();
		}

	}

	function js_comment_modify_save(f, cno, bno) {  //수정 저장~~

			var frm		= f;
			var mode	= "U";
			//var secret_tf = "";
			//if (frm.secret_tf.value.is(":checked") == true) {
			//	secret_tf = "Y";
			//}
			var contents		= frm.contents.value;

			//var writer_nm = "";
			//var writer_pw = "";

			if (contents.trim() == "") {
				alert("내용를 입력해 주십시오.");
				frm.contents.focus();
				return false;
			}

			//writer_nm = encodeURIComponent(writer_nm);
			//writer_pw = encodeURIComponent(writer_pw);
			//contents	= encodeURIComponent(contents);

			$.ajax({
				url: "/_common/board/ajax.comment.mobile.php",
				type: "POST",
				data: {mode: mode, cno:cno, contents:contents},
				dataType: "html",
				success: function(result) {
					$("#comment-display_"+bno).html(result);
				},
				error: function(request, status, error){
					alert("code"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});

			return false;

	}

	function js_comment_delete(cno, bno, cnt) {

		bDelOK	= confirm('댓글을 삭제 하시겠습니까?');

		if (bDelOK == true) {
	
			var mode	= "D";

			$.ajax({
				url: "/_common/board/ajax.comment.mobile.php",
				type: "POST",
				data: {mode: mode, cno:cno, cnt:cnt},
				dataType: "html",
				success: function(result) {
					$("#comment-display_"+bno).html(result);
				},
				error: function(request, status, error){
					alert("code"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});

			return false;

		}

	}

	</script>
	<style>
		.local-body {scroll-behavior: smooth;}
	</style>
</head>
<body>
<div id="wrap">
	<!-- page -->
	<div id="page">
				<div id="skip" class="page-skip"><a class="skip-item" href="#content">본문 바로가기</a></div>
		<!-- page-head -->
		<div class="page-head" id="header">
			<h1 class="page-title" id="pageTitle"><a class="page-name" href="#">유컴패니온 인트라넷</a></h1>
		</div>
		<!-- //page-head -->
		<hr />
		<!-- page-navi -->
		<div class="page-navi">
			<ul class="navi-list">
				<li class="navi-item"><a class="navi-name" href="/m/main/main.php">메인</a></li>
				<li class="navi-item current" title="선택 됨"><span class="navi-name"">공지사항</span></li>
				<li class="navi-item"><a class="navi-name" href="/m/vacation/vacation.php">근무현황</a></li>
				<li class="navi-item"><a class="navi-name" href="/m/member/admin_list.php">임직원 조회</a></li>
				<li class="navi-item"><a class="navi-name" href="/m/approval/vacation_approval_list.php">결재관리</a></li>
			</ul>
		</div>
		<!-- //page-navi -->
		<hr />
		<!-- page-body -->
		<div class="page-body page-intro page-notice">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-title" id="localTitle"><span class="local-name">공지사항</span></h2>
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-head -->
						<div class="content-head">
							<h3 class="content-title"><span class="content-name">공지사항</span></h3>
							<!-- data-finder --> 
							<form class="data-finder" id="bbsList" name="frm">
							<input type="hidden" name="b_no" value="">
							<input type="hidden" name="b_code" value="B_1_1">
							<input type="hidden" name="use_tf" value="">
							<input type="hidden" name="mode" value="">
								<!-- submit-form -->
								<fieldset class="submit-form module-a">
									<legend>게시물 검색 서식</legend>
									<div class="form-list">
										<div class="form-item">
											<div class="form-head"><label class="form-name" for="searchKeyword">검색어</label></div>
											<div class="form-body">
												<div class="form-area">
													<span class="form textfield module-b style-b type-line normal-04 medium flex">
														<input type="search" class="form-elem" name="search_str" value="<?=$search_str?>" id="searchKeyword" placeholder="검색어를 입력해 주세요." onfocus="buiFormCancel(this);" onmousemove="buiFormCancel(this);" />
														<span class="form-func">
															<button type="submit" class="btn" onClick="return js_search()"><svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>검색</title><path d="M19.63,17.87l-2-2a7.5,7.5,0,1,0-1.76,1.76l2,2a1.24,1.24,0,0,0,1.76-1.76ZM11.5,16.5a5,5,0,1,1,5-5A5,5,0,0,1,11.5,16.5Z"></path></svg></button></span>
														</span>
													</span>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
								<!-- //submit-form -->
							</form>
							<!-- //data-finder -->
						</div>
						<!-- //content-head -->
						<!-- content-body -->
						<div class="content-body">
							<!-- post-display -->
							<div class="post-display module-a style-a type-a">
								<!-- post-info -->
								<div class="post-info">
									<div class="info-list">
										<div class="info-item">
											<!-- data-list -->
											<p class="data-list">
												<span class="data-item">
													<span class="head">총</span>
													<span class="body"><?=$nListCnt?>건</span>
												</span>
											</p>
											<!-- //data-list -->
										</div>
										<form class="info-item" name="frm_must_read">
											<!-- submit-form -->
											<fieldset class="submit-form module-a">
												<legend>게시물 정렬 서식</legend>
												<div class="form-list">
													<div class="form-item">
														<div class="form-head"><span class="form-name">조건 선택</span></div>
														<div class="form-body">
															<div class="form-area">
																<label class="form module-a style-a switch"><span class="form-text">필독만 보기</span> <input type="checkbox" class="form-elem" name="search_str" value="필독" onClick="if(this.checked){js_search_must_read(this.form);} else {this.value='';js_search_must_read(this.form);}" <?if($search_str == "필독") {?>checked <?}?> /></label>
															</div>
														</div>
													</div>
												</div>
											</fieldset>
											<!-- //submit-form -->
										</form>
									</div>
								</div>
								<!-- //post-info -->
								<!-- post-list -->

<div class="post-list">

<?

		$nCnt = 0;
		
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				
				$B_NO						= trim($arr_rs[$j]["B_NO"]);
				$B_CODE					= trim($arr_rs[$j]["B_CODE"]);
				$B_PO						= trim($arr_rs[$j]["B_PO"]);
				$CATE_01				= trim($arr_rs[$j]["CATE_01"]);
				$CATE_02				= trim($arr_rs[$j]["CATE_02"]);
				$CATE_03				= trim($arr_rs[$j]["CATE_03"]);
				$CATE_04				= trim($arr_rs[$j]["CATE_04"]);
				$CATE_05				= trim($arr_rs[$j]["CATE_05"]);
				$WRITER_NM			= trim($arr_rs[$j]["WRITER_NM"]);
				$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
				$CONTENTS				= SetStringFromDB($arr_rs[$j]["CONTENTS"]);
				$REG_ADM				= trim($arr_rs[$j]["REG_ADM"]);
				$HIT_CNT				= trim($arr_rs[$j]["HIT_CNT"]);
				$REF_IP					= trim($arr_rs[$j]["REF_IP"]);
				$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
				$COMMENT_TF			= trim($arr_rs[$j]["COMMENT_TF"]);
				$COMMENT_CNT		= trim($arr_rs[$j]["COMMENT_CNT"]);
				$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
				$SECRET_TF			= trim($arr_rs[$j]["SECRET_TF"]);
				$F_CNT					= trim($arr_rs[$j]["F_CNT"]);

				$REPLY_DATE			= trim($arr_rs[$j]["REPLY_DATE"]);
				$REPLY_STATE		= trim($arr_rs[$j]["REPLY_STATE"]);

				$rs_admin				= selectAdmin($conn, $REG_ADM);
				$rs_adm_name		= SetStringFromDB($rs_admin[0]["ADM_NAME"]);
				$rs_adm_profile	= selectAdminProfile($conn, $REG_ADM);

				$CATE_01 = str_replace("^"," & ",$CATE_01);

				$REG_DATE = date("Y-m-d H:i",strtotime($REG_DATE));
		
				if ($USE_TF == "Y") {
					$STR_USE_TF = "<font color='navy'>보이기</font>";
				} else {
					$STR_USE_TF = "<font color='red'>보이지않기</font>";
				}

				if ($COMMENT_TF == "Y") {
					$STR_COMMENT_TF = "<font color='navy'>사용</font>";
				} else {
					$STR_COMMENT_TF = "<font color='red'>사용안함</font>";
				}

				$STR_REPLY_STATE = "";

				if ($REPLY_STATE == "Y") {
					$STR_REPLY_STATE = "<font color='navy'>답변완료</font>";
				} else {
					$STR_REPLY_STATE = "<font color='red'>대기중</font>";
				}

				$R_CNT = getReplyCount($conn, $B_CODE, $B_NO);

				if ($R_CNT > 0) {
					$reply_cnt = "<span class=\"orange f11\">(".$R_CNT.")</span>";
				} else {
					$reply_cnt = "";
				}

				if ($SECRET_TF == "Y") 
					$str_lock = "<img src='../images/bu/ic_lock.jpg' alt='' />";
				else 
					$str_lock = "";

				if ($F_CNT > 0) {
					$arr_rs_files = listBoardFile($conn, $B_CODE, $B_NO);
				} else {
					$str_file = "";
				}

				$is_new = "";
				if ($REG_DATE >= date("Y-m-d H:i:s", (strtotime("0 day") - ($b_new_hour * 3600)))) {
					if ($MAIN_TF <> "N") {
						$is_new = "N";
					}
				}
				
				$is_notice = "공지"; //[필독], [전체공지] 등
				$idx_n = strpos($TITLE, "]");

				if ( $idx_n > 0) {
					$is_notice = substr($TITLE, 1, $idx_n-1);
					$TITLE = substr($TITLE, $idx_n+1);
				}

	?>

	<div class="post-item" data-bui-expand="postItem" id="n_<?=$B_NO?>">
		<div class="post-wrap">
			<div class="post-inform">
				<div class="post-type">
					<p class="data-list">
					<? if ($is_new == "N") { ?>
						<span class="data-item"><span class="mark module-b style-c type-fill accent-01 small"><span class="mark-text">N</span></span></span>
					<? } ?>
					<? if ($is_notice <> "공지") { ?>
						<span class="data-item"><span class="mark module-b style-c type-line normal-01 small"><span class="mark-text"><?=$is_notice?></span></span></span>
					<? } else { ?>
						<span class="data-item"><span class="mark module-b style-c type-line normal-03 small"><span class="mark-text">공지</span></span></span>
					<? } ?>
					</p>
				</div>
				<div class="post-head">
					<p class="post-title"><span class="post-name"><?=$TITLE?> <?=$reply_cnt?> <?=$str_lock?> <?=$str_file?></span></p>
				</div>
				<div class="post-data">
					<ul class="data-list">
						<li class="data-item writer" style="--default-picture: url(/upload_data/profile/<?=$rs_adm_profile?>);">
							<span class="head">글쓴이</span>
							<span class="body">
								<span class="name"><?=$rs_adm_name?></span>
							</span>
						</li>
						<li class="data-item posted">
							<span class="head">등록일</span>
							<span class="body">
								<span class="date"><?=$REG_DATE?></span>
							</span>
						</li>
						<? if ($F_CNT > 0) { ?>
						<li class="data-item attachments">
							<div class="head" tabindex="0">첨부파일</div>
							<div class="body">
								<!-- tooltip -->
								<div class="tooltip module-a style-a type-b">
									<div class="tooltip-wrap">
										<div class="tooltip-body">
											<ul class="data-list">
											<!--
												<li class="data-item"><a href="#"><span class="file-name">수강안내문(학습자배포용)</span><span class="extension">.pdf</span></a></li>
												<li class="data-item"><a href="#"><span class="file-name">영업비밀보호의 첫걸음 전사원을 교육하라_요약집</span><span class="extension">.zip</span></a></li>
											-->
											<?
												if (sizeof($arr_rs_files) > 0) {
													for ($i = 0 ; $i < sizeof($arr_rs_files); $i++) {
														$RS_FILE_NO			= trim($arr_rs_files[$i]["FILE_NO"]);
														$RS_FILE_NM			= trim($arr_rs_files[$i]["FILE_NM"]);
														$RS_FILE_RNM		= trim($arr_rs_files[$i]["FILE_RNM"]);

														if ($RS_FILE_NM <> "") {
											?>
												<li class="data-item"><a href="../../_common/new_download_file.php?menu=boardfile&file_no=<?= $RS_FILE_NO ?>"><span class="file-name"><?=$RS_FILE_RNM?></span></a></li>
											<?
														}
													}
												}
											?>
											</ul>
										</div>
									</div>
								</div>
								<!-- //tooltip -->
							</div>
						</li>
						<? }  //첨부파일여부 ?>
					</ul>
				</div>
				<div class="post-body"><?=$CONTENTS?></div>
			</div>
		</div>
		<div class="post-util">
			<div class="button-display module-a style-b type-d">
				<span class="button-area">
				</span>
			</div>
		</div>
		<div class="post-side">	
			<!-- comment-display --> 
			<div class="comment-display module-a style-a type-a" id="comment-display_<?=$B_NO?>">
				<!-- comment-info -->
				<div class="comment-info">
					<div class="info-list">
						<div class="info-item" id="info-item_<?=$B_NO?>">
							<!-- data-list -->
							<? if($COMMENT_CNT > 0) { ?>
							<p class="data-list">
								<span class="data-item">
									<span class="head">댓글</span>
									<span class="body" id="info-item_cnt_<?=$B_NO?>"><?=$COMMENT_CNT?>개</span>
								</span>
							</p>
							<? } ?>
							<!-- //data-list -->
						</div>
					</div>
				</div>
				<!-- //comment-info -->
				<!-- comment-write -->
				<form class="comment-write" name="frm_comment_<?=$B_NO?>" method="post">
				<input type="hidden" name="mode" id="mode" />
				<input type="hidden" name="secret_tf" id="secret_tf" value="N"/>
				<input type="hidden" name="encrypt_str" id="encrypt_str" value="<?=$temp_str?>">
				<input type="hidden" name="b" id="b" value="B_1_1">
				<input type="hidden" name="bn" id="bn" value="<?=$B_NO?>">
					<!-- submit-form -->
					<fieldset class="submit-form module-a">
						<legend>댓글 등록 서식</legend>
						<div class="form-list">
							<div class="form-item">
								<div class="form-head"><span class="form-name">댓글 작성</span></div>
								<div class="form-body">
									<div class="form-area">
										<span class="form textarea module-b style-b type-line normal-04 medium flex" data-bui-form-value>
											<textarea class="form-elem" placeholder="내용을 입력해주세요." title="댓글 작성" oninput="buiFormResize(this);" name="contents" id="contents"></textarea>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="form-util">
							<div class="button-display module-a style-a type-b">
								<span class="button-area">
									<button type="button" class="btn module-c style-b type-line accent-02 small" onClick="this.form.contents.value=''"><span class="btn-text">취소</span></button>
									<button type="submit" class="btn module-b style-b type-fill accent-02 small" onClick="return js_comment_save(this.form, <?=$B_NO?>, <?=$COMMENT_CNT?>);"><span class="btn-text">등록</span></button>
								</span>
							</div>
						</div>
					</fieldset>
					<!-- //submit-form -->
				</form>

				<!-- //comment-write -->
				<!-- comment-list -->
				<div class="comment-list" id="comment-list_<?=$B_NO?>">
				<?
				if ($mode == "L") {
					if ($COMMENT_TF == "Y") {
						
						// 커맨트 리스트 출력 입니다.
						if ($cPage == 0) $cPage = "1";

						if ($cPage <> "") {
							$cPage = (int)($cPage);
						} else {
							$cPage = 1;
						}

						if ($cPageSize <> "") {
							$cPageSize = (int)($cPageSize);
						} else {
							$cPageSize = 10000;
						}
						$cPageBlock = 10;
						
						$con_use_tf = "Y";
						$con_del_tf = "N";

						$cListCnt =totalCntBoardComment($conn, $B_NO, $con_writer_id, $con_use_tf, $con_del_tf, $f, $s);

						$cTotalPage = (int)(($cListCnt - 1) / $cPageSize + 1) ;

						if ((int)($cTotalPage) < (int)($cPage)) {
							$cPage = $cTotalPage;
						}

						$arr_rs_comment = listBoardComment($conn, $B_NO, $con_writer_id, $con_use_tf, $con_del_tf, $f, $s, $cPage, $cPageSize,$cListCnt);

						if (sizeof($arr_rs_comment) > 0) {
							for ($t = 0 ; $t < sizeof($arr_rs_comment); $t++) {

								$rn_comm						= trim($arr_rs_comment[$t]["rn"]);
								$C_NO_COMM					= trim($arr_rs_comment[$t]["C_NO"]);
								$B_RE_COMM					= trim($arr_rs_comment[$t]["B_RE"]);
								$B_PO_COMM					= trim($arr_rs_comment[$t]["B_PO"]);
								$SECRET_TF_COMM			= trim($arr_rs_comment[$t]["SECRET_TF"]);
								$WRITER_ID_COMM			= trim($arr_rs_comment[$t]["WRITER_ID"]);
								$WRITER_NM_COMM			= SetStringFromDB($arr_rs_comment[$t]["WRITER_NM"]);
								$WRITER_NICK_COMM		= SetStringFromDB($arr_rs_comment[$t]["WRITER_NICK"]);
								$TITLE_COMM					= SetStringFromDB($arr_rs_comment[$t]["TITLE"]);
								$TITLE_COMM					= check_html($TITLE_COMM);
								$CONTENTS_COMM			= SetStringFromDB($arr_rs_comment[$t]["CONTENTS"]);
								$REG_DATE_COMM			= trim($arr_rs_comment[$t]["REG_DATE"]);
								$REF_IP_COMM				= trim($arr_rs_comment[$t]["REF_IP"]);

								$profile_comm				=selectAdminIdProfile($conn, 	$WRITER_ID_COMM);
								
								if ($_SESSION['s_adm_no'] == "" && $SECRET_TF_COMM == "Y") {
									$CONTENTS_COMM = "<font color='orange'> * 비밀글 입니다.</font>";
								}

								$cc_i_arr = array("<form",	"</form",	"<input",	"<textarea",	"</textarea",	"girin_comment" ,	"javascript:gbc_" );
								$cc_o_arr = array("<orm",	"</orm",	"<nput",	"<extarea",		"</extarea",	"glgln_comment" ,	"javascript:gbcc_");
								$str_contents		= nl2br(replace_tag_parts($CONTENTS_COMM, $cc_i_arr, $cc_o_arr));
								
								// 글쓴이.
								if ($b_realname_tf == "Y") {
									$str_writer_name = $WRITER_NM_COMM;
								} else {
									$str_writer_name = $WRITER_NICK_COMM;
								}

								$str_reg_date = date("Y-m-d H:i:s",strtotime($REG_DATE_COMM));
								
								$DEPTH_COMM = strlen($B_PO_COMM);
								$str_depth_class = "";
								if ($DEPTH_COMM) {
									if ($DEPTH_COMM > 9) {
										$str_depth_class = "class='depth".$DEPTH_COMM."'";
									} else {
										$str_depth_class = "class='depth0".$DEPTH_COMM."'";
									}
								}

								$del_btn_flag = false;

								if ($_SESSION['s_adm_id']) {
									if (trim($_SESSION['s_adm_id']) == trim($WRITER_ID_COMM)) $del_btn_flag = true;
								} else {
									//if ($WRITER_ID_COMM == "")
										$del_btn_flag = false;
								}

								if (($_SESSION['s_adm_group_no'] <= 3) and ($_SESSION['s_adm_id'] <> "")) {
									$del_btn_flag = true;
									$is_guest = false;
								}
				?>

					<div class="comment-item">
						<div class="comment-wrap">
							<div class="comment-inform">
								<div class="comment-data">
									<ul class="data-list">
										<li class="data-item writer" style="--default-picture:  url(/upload_data/profile/<?=$profile_comm?>);">
											<span class="head">글쓴이</span>
											<span class="body">
												<span class="name"><?=$WRITER_NM_COMM?></span>
											</span>
										</li>
										<li class="data-item posted">
											<span class="head">등록일</span>
											<span class="body">
												<span class="date"><?=$REG_DATE_COMM?></span>
											</span>
										</li>
									</ul>
								</div>
								<div class="comment-body">
									<?=$str_contents?>
								</div>
								<? if ($del_btn_flag) {?>
								<div class="comment-util">
									<div class="button-display">
										<span class="button-area">
											<button class="btn module-a normal-03 small" onClick="js_comment_modify('<?=$C_NO_COMM?>',<?=$B_NO?>,<?=$t?>);"><span class="btn-text">수정</span></button>
											<button class="btn module-a normal-03 small" onClick="js_comment_delete('<?=$C_NO_COMM?>',<?=$B_NO?>,<?=$COMMENT_CNT?>);"><span class="btn-text">삭제</span></button>
										</span>
									</div>
								</div>
								<? } ?>
							</div>
						</div>
					</div>

					<div class="comment-item" id="contents_modify_<?=$B_NO?>_<?=$t?>" style="display:none;">
						<div class="comment-side">
							<!-- reply-display -->
							<div class="reply-display">
								<!-- comment-write -->
								<form class="reply-write">
									<!-- submit-form -->
									<fieldset class="submit-form module-a">
										<legend>답글 등록 서식</legend>
										<div class="form-list">
											<div class="form-item">
												<div class="form-head"><span class="form-name">답글 작성</span></div>
												<div class="form-body">
													<div class="form-area">
														<span class="form textarea module-b style-b type-line normal-04 medium flex" data-bui-form-value="수정할 경우 화면입니다! 프로필, 내용 등이 날라가고 텍스트필드로 변경됩니다 ㅎㅎ 버튼도 수정으로 나오게..">
															<textarea class="form-elem" placeholder="내용을 입력해주세요." name="contents" title="댓글 작성" oninput="buiFormResize(this);"><?=$CONTENTS_COMM?></textarea>
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="form-util">
											<div class="button-display module-a style-a type-b">
												<span class="button-area">
													<button type="button" class="btn module-c style-b type-line accent-02 small" name="js_modify_cancle" onClick="$('#contents_modify_<?=$B_NO?>_<?=$t?>').css('display','none');"><span class="btn-text">취소</span></button>
													<button type="submit" class="btn module-b style-b type-fill accent-02 small" onClick="return js_comment_modify_save(this.form, '<?=$C_NO_COMM?>', '<?=$B_NO?>')"><span class="btn-text">수정</span></button>
												</span>
											</div>
										</div>
									</fieldset>
									<!-- //submit-form -->
								</form>
								<!-- //reply-write -->
							</div>
							<!-- //reply-display -->
						</div>
					</div>

				<?
							}
						}
					}
				}
				?>
				</div>
				<!-- //comment-list -->
			</div>
			<!-- //comment-display -->
		</div>
	</div>

	<?			
			}
		}
	?>
</div>

<!-- //post-list -->
							</div>
							<!-- //post-display -->
						</div>
						<!-- //content-body -->
					</div>
					<!-- //content -->
				</div>
				<!-- //local-body -->
			</div>
			<!-- //local -->
		</div>
		<!-- //page-body -->
				<hr />
		<!-- page-foot -->
		<div class="page-foot" id="footer">
			<p class="copyright">U:COMPANION. ALL RIGHT RESERVED.</p>
		</div>
		<!-- //page-foot -->
	</div>
	<!-- //page -->
	<hr />

</div>
</body>
</html>