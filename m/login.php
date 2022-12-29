<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : login.php
# Modlue       : 
# Writer       : JeGal Jeong
# Create Date  : 2022-04-26
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================

#====================================================================
# Request Parameter
#====================================================================
	if ($_SESSION['s_adm_no'] <> "") {
		if ($eas == "approval") {
			$next_url = "/m/approval/approval_list.php";
		} else {
			$next_url = "/m/main/main.php";
		}
?>
<meta http-equiv='Refresh' content='0; URL=<?=$next_url?>'>
<?
		exit;
	}

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../_common/m_common_script.php";
?>

	<link rel="stylesheet" type="text/css" href="/m/assets/css/page.login.css">
</head>
<body>
<div id="wrap">
	<!-- page -->
	<div id="page">
				<!-- page-head -->
		<div class="page-head" id="header">
			<h1 class="page-title" id="pageTitle"><a class="page-name" href="#">유컴패니온 인트라넷</a></h1>
		</div>
		<!-- //page-head -->
		<hr />
		<!-- page-body -->
		<div class="page-body page-login">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-title" id="localTitle"><span class="local-name">로그인</span></h2>
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-body -->
						<form id="logIn" name="frm" method="post" class="content-body">
							<!-- info-board -->
							<div class="info-board">
								<div class="board-wrap">
									<div class="board-head">
										<p class="board-summary">
											<span class="wbr">유컴패니온은</span>
											<span class="wbr"><strong class="em accent-01">지식경제시대를</strong></span>
											<span class="wbr">준비하고자 고민합니다.</span>
										</p>
									</div>
								</div>
							</div>
							<!-- //info-board -->
							<!-- submit-form -->
							<form id="logIn" name="frm" method="post">
							<fieldset class="submit-form">
								<legend>로그인 정보 입력 서식</legend>
								<div class="form-list">
									<div class="form-item">
										<div class="form-wrap">
											<div class="form-head"><label class="form-name" for="form_id">아이디</label></div>
											<div class="form-body">
												<div class="form-area">
													<span class="form textfield module-b style-b type-line normal-04 medium flex"><input type="text" id="systemId" name="adm_id" class="form-elem" placeholder="아이디를 입력해주세요." value="" /></span>
												</div>
												<div class="form-noti" id="msg-error">
													<p class="para">해당 아이디가 없습니다.</p>
												</div>
											</div>
										</div>
									</div>
									<div class="form-item">
										<div class="form-wrap">
											<div class="form-head"><label class="form-name" for="form_id">비밀번호</label></div>
											<div class="form-body">
												<div class="form-area">
													<span class="form textfield module-b style-b type-line normal-04 medium flex"><input type="password" id="systemPw" name="adm_pw" class="form-elem" placeholder="비밀번호를 입력해주세요." value="" /></span>
												</div>
												<div class="form-noti">
													<p class="para">회원 정보가 일치 하지 않습니다.</p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-util">
									<!-- button-display -->
									<div class="button-display module-a style-a type-a">
										<span class="button-area">
											<button id="btn_login" class="btn module-b style-b type-fill accent-01 x-large flex" type="button"><span class="btn-text">로그인</span></button>
										</span>
									</div>
									<!-- //button-display -->
								</div>
							</fieldset>
							<!-- //submit-form -->
						</form>
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
</div>

<script>

	$("#systemPw").keypress(function(e) {
		if (e.keyCode == 13) { 
			$('#btn_login').trigger('click');
		}
	});
	
	$(document).on("click", "#btn_login", function() { 

		var mode = "S";
		var adm_id = $("#systemId").val().trim();
		var adm_pw = $("#systemPw").val().trim();
		
		if (adm_id == "") {
			alert("아이디를 입력하세요.");
			$("#systemId").focus();
			return;
		}

		if (adm_pw == "") {
			alert("비밀번호를 입력하세요.");
			$("#systemPw").focus();
			return;
		}

		//alert(adm_id);
		//alert(adm_pw);

		var request = $.ajax({
			url:"/manager/login/ajax_login_dml.php",
			type:"POST",
			data:{mode:mode, adm_id:adm_id, adm_pw:adm_pw},
			dataType:"json"
		})

		request.done(function(data) {

			if (data.result == "0") {
				<? if ($eas <> "") { ?>
					document.location = "<?=$next_url?>";
				<? } else { ?>
					document.location = "/m/main/main.php";
				<? } ?>
			} else {
				
				//$(".form-noti").css("display","block");
				//$("#msg-error").html(data.msg);
				if ((data.result == "1") || (data.result == "2"))	{
					alert(data.msg);
					$("#systemId").focus();
					return false;
				}
			}
			//alert(data.result);
			//alert(data.msg);
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

	});

</script>
</body>
</html>