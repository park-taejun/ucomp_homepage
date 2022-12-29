<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
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
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/board/board.php";
	require "../../_classes/biz/board/board_comment.php";
	require "../../_classes/biz/member/member.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";

	$arr_page_nm = explode("/", $_SERVER['PHP_SELF']);

	$mode					= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$b						= $_POST['b']!=''?$_POST['b']:$_GET['b'];
	$bn						= $_POST['bn']!=''?$_POST['bn']:$_GET['bn'];
	$cno					= $_POST['cno']!=''?$_POST['cno']:$_GET['cno'];
	$cnt					= $_POST['cnt']!=''?$_POST['cnt']:$_GET['cnt'];  //댓글 갯수
	$writer_nm		= $_POST['writer_nm'];
	$writer_pw		= $_POST['writer_pw'];
	$secret_tf		= $_POST['secret_tf'];
	$contents			= $_POST['contents'];
	$cPage				= $_POST['cPage']!=''?$_POST['cPage']:$_GET['cPage'];
	$encrypt_str	= $_POST['encrypt_str'];
	
	/*
	$writer_nm = iconv("UTF-8", "CP949", rawurldecode($writer_nm));
	$writer_pw = iconv("UTF-8", "CP949", rawurldecode($writer_pw));
	$contents	 = iconv("UTF-8", "CP949", rawurldecode($contents));
	*/

#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/config_info.php";
#====================================================================
# Board Config End
#====================================================================

	$writer_nm			= SetStringToDB($writer_nm);
	$writer_pw			= SetStringToDB($writer_pw);
	$contents				= SetStringToDB($contents);

	$use_tf				= "Y";
	$top_tf				= "N";

	if ($mode == "I") {
		
		$err_mag = "";

		// 입력자 관련 정보 
		if ($_SESSION['s_adm_no']) {
			
			$writer_pw		 = $_SESSION['s_adm_pw'];
			$writer_id		 = $_SESSION['s_adm_id'];
			$writer_nick	 = $_SESSION['s_adm_nm'];
			$writer_nm		 = $_SESSION['s_adm_nm'];
			$writer_profile= selectAdminIdProfile($conn, $writer_id);

		} else {
			
			// 나중에 사용자 정보로 바꾼다.
			if ($_SESSION['s_m_id']) {
				$writer_id		= $_SESSION['s_m_id'];
				$writer_pw		= $m_m_password;
				$writer_nick	= $m_m_nick;
				$writer_nm		= $m_m_name;
			} else {
				$writer_id		= "";
				$writer_pw		= sql_password($conn, trim($writer_pw));
				$writer_nick	= trim($writer_nm);
				$writer_nm		= trim($writer_nm);
			}
		}

		if ($_SESSION['s_is_adm'] == "") { // 관리자 가 아닌경우 입력 값 체크

			if (substr_count($contents, "&#") > 50)
				$err_mag = "내용에 올바르지 않은 코드가 다수 포함되어 있습니다.";

			// 자동 등록방지 
			if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str']))
				$err_mag = "정상적인 접근 방식이 아닙니다.";

			// 권한을 체크 합니다.
			if (!$c_right)
				$err_mag = "댓글 쓰기 권한이 없습니다.";

			// 금지어 체크
			if ($b_board_badword) {
				$arr_board_badword = explode(",",$b_board_badword);

				//$err_mag = sizeof($arr_board_badword);

				for ($i =0; $i < sizeof($arr_board_badword); $i++) {
					if(@eregi($arr_board_badword[$i],$contents)) {
						$err_mag = "'".$arr_board_badword[$i]."' 금지어가 포함되어 있습니다.";
					}
				}
			}


			// 글쓴이에 금지어 체크
			if (preg_match("/[\,]?{$writer_nm}/i", $g_prohibit_id))
				$err_mag = "'$writer_nm' 은(는) 예약어로 사용하실 수 없는 이름입니다.";

			// 쓰기 시간 체크
			$times = mktime();
			
			if ($_SESSION["s_write_time"] >= ($times - $g_site_re_write)) {
				$err_mag = "너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.";
			} else {
				if ($err_mag == "") $_SESSION["s_write_time"] = $times;
			}

			if ($err_mag) {
				//$err_mag = rawurlencode(iconv("CP949", "UTF-8", $err_mag));
				echo $err_mag;
				mysql_close($conn);	
				exit;
			}
		}
		
		// 모든 예외 사항 처리 후 등록 합니다.
		$b_re = getBoardCommentNextRe($conn, $bn);
		$b_po = "";

		$arr_data = array("B_NO"=>$bn,
											"B_PO"=>$b_po,
											"B_RE"=>$b_re,
											"WRITER_ID"=>$writer_id,
											"WRITER_NM"=>$writer_nm,
											"WRITER_NICK"=>$writer_nick,
											"WRITER_PW"=>$writer_pw,
											"TITLE"=>$title,
											"REF_IP"=>$ref_ip,
											"CONTENTS"=>$contents,
											"SECRET_TF"=>$secret_tf,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$s_adm_no);

		$new_c_no = insertBoardComment($conn, $arr_data);
		$rs_b_no  = $bn;

	}

	if ($mode == "U") {

		$err_mag = "";

		$arr_rs = selectBoardComment($conn, $cno);

		$rs_c_no			= trim($arr_rs[0]["C_NO"]);
		$rs_b_no			= trim($arr_rs[0]["B_NO"]);
		$rs_writer_id	= trim($arr_rs[0]["WRITER_ID"]);

		if ($rs_c_no == "") $err_mag = "답글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동하였을 수 있습니다."; 

		if ($_SESSION['s_adm_no'] == "") {
			if ($rs_writer_id != $_SESSION['s_m_id']) $err_mag = "작성자만 수정 할 수 있습니다.";

			if (substr_count($contents, "&#") > 50)
				$err_mag = "내용에 올바르지 않은 코드가 다수 포함되어 있습니다.";

			// 자동 등록방지 
			if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str']))
				$err_mag = "정상적인 접근 방식이 아닙니다.";

			// 권한을 체크 합니다.
			if (!$c_right)
				$err_mag = "댓글 쓰기 권한이 없습니다.";

			// 금지어 체크
			if ($b_board_badword) {
				$arr_board_badword = explode(",",$b_board_badword);

				//$err_mag = sizeof($arr_board_badword);

				for ($i =0; $i < sizeof($arr_board_badword); $i++) {
					if(@eregi($arr_board_badword[$i],$contents)) {
						$err_mag = "'".$arr_board_badword[$i]."' 금지어가 포함되어 있습니다.";
					}
				}
			}

		}

		if ($err_mag) {
			//$err_mag = iconv("EUC-kr", "UTF-8", $err_mag);
			echo $err_mag;
			mysql_close($conn);	
			exit;
		}
			
		//$msg_title		= iconv("UTF-8","EUC-KR", $msg_title);
		//$contents	= iconv("EUC-KR", "UTF-8", $contents);

		$result = updateBoardComment($conn, $_SESSION['s_m_id'], $cno, $secret_tf, $contents);
	}

	if ($mode == "D") {

		$err_mag = "";

		$arr_rs = selectBoardComment($conn, $cno);

		$rs_c_no			= trim($arr_rs[0]["C_NO"]);
		$rs_b_no			= trim($arr_rs[0]["B_NO"]);
		$rs_writer_id	= trim($arr_rs[0]["WRITER_ID"]);


		if ($rs_c_no == "") $err_mag = "답글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동하였을 수 있습니다."; 
		// 외부에서 글을 등록할 수 있는 버그가 존재하므로 비밀글은 사용일 경우에만 가능해야 함
	
		// 자동 등록방지 
		//if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str']))
		//	$err_mag = "정상적인 접근 방식이 아닙니다.";
		
		if ($_SESSION['s_adm_no'] == "") {
			if ($rs_writer_id != $_SESSION['s_m_id']) $err_mag = "작성자만 삭제 할 수 있습니다.";
		}

		if ($err_mag) {
			//$err_mag = iconv("EUC-kr", "UTF-8", $err_mag);
			echo $err_mag;
			mysql_close($conn);	
			exit;
		}

		$msg_title		= "작성자 또는 관리자에 의해 삭제 되었습니다.";
		$msg_contents	= "답변글이 남아 있어 내용만 삭제 되었습니다.";
			
		//$msg_title		= iconv("UTF-8","EUC-KR", $msg_title);
		//$msg_contents	= iconv("UTF-8","EUC-KR", $msg_contents);

		$result = deleteBoardComment($conn, $_SESSION['s_m_id'], $cno, $msg_title, $msg_contents);

	}

						// 커맨트 리스트 출력 입니다.
						$cPage = "1";
						$cPageSize = 10000;
						$cPageBlock = 10;

						$b		= "B_1_1";
						$B_NO = $rs_b_no;
						$con_use_tf = "Y";
						$con_del_tf = "N";

						$arr_cnt = selectBoard($conn, $b, $B_NO);
						$COMMENT_CNT = $arr_cnt[0]["COMMENT_CNT"]; //댓글개수

						$cListCnt =totalCntBoardComment($conn, $B_NO, $con_writer_id, $con_use_tf, $con_del_tf, $f, $s);

						$cTotalPage = (int)(($cListCnt - 1) / $cPageSize + 1) ;

						if ((int)($cTotalPage) < (int)($cPage)) {
							$cPage = $cTotalPage;
						}

						$arr_rs_comment = listBoardComment($conn, $B_NO, $con_writer_id, $con_use_tf, $con_del_tf, $f, $s, $cPage, $cPageSize,$cListCnt);


?>
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
											<button class="btn module-a normal-03 small" onClick="js_comment_delete('<?=$C_NO_COMM?>',<?=$B_NO?>,<?=$cnt?>);"><span class="btn-text">삭제</span></button>
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

		mysql_close($conn);	

?>