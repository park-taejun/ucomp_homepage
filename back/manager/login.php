<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : login.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-12-10
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
		$next_url = "/manager/main/main.php";
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
	require "../_common/common_script.php";
?>
</head>

<body id="login"> <!-- login-->

	<div class="loginwrap">
		<h1><img src="images/logo.png" alt="U: 유컴패니온" /></h1>
		<h2><img src="images/txt_loginpr.png" alt="더운 여름처럼 열정있게 화이팅 합시다" /></h2>
		<fieldset>
		<form id="logIn" name="frm" method="post">
			<legend>로그인 정보입력</legend>
			<span class="inp-id"><input type="text" id="systemId" name="adm_id" class="txt" placeholder="ID" /></span>
			<span class="inp-pw"><input type="password" id="systemPw" name="adm_pw" class="txt" placeholder="PW" /></span>
			<p class="msg-error" id="msg-error"><!--해당 아이디가 없습니다.--></p>
			<button type="button" id="btn_login">Login</button>
		</form>
		</fieldset>
	</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
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
			return;
		}

		if (adm_pw == "") {
			alert("비밀번호를 입력하세요.");
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
				document.location = "/manager/main/main.php";
			} else {
				$("#msg-error").html(data.msg);
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