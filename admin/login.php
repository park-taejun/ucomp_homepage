<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : login.php
# Modlue       : 
# Writer       : Park Chan Ho /JeGal Jeong
# Create Date  : 2018-12-10
# Modify Date  : 2021-09-03
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# common_header Check Session
#====================================================================
		
#====================================================================
# Request Parameter
#====================================================================
	if ($_SESSION['s_adm_no'] <> "") {
		if ($eas == "smt") {
			$next_url = "/manager/commute/commute_list.php";
		} elseif ($eas == "birthday") {
			$next_url = "/manager/anniversary/birthday_calendar.php";
		} elseif ($eas == "hiredate") {
			$next_url = "/manager/anniversary/hiredate_calendar.php";
		} elseif ($eas == "approval") {
			$next_url = "/manager/approval/approval_list.php";
		} elseif ($eas == "vacation") {
			$next_url = "/manager/approval/vacation_approval_list.php";
		}else {
			$next_url = "/admin/admingroup/admingroup_list.php";
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
	require "../_common/common_script.php";
?>
</head>

<body id="login"> <!-- login-->

	<div class="loginwrap">
		
		<h1><img src="images/logo.png" alt="U: 유컴패니온" /></h1>
		<!--
		<h2>
			코로나<br />
			<strong>그 다음 세상을</strong><br /> 준비합시다.<br />
			<strong>담대하게...</strong>
		</h2>
		-->
		<h2>
			<!--유컴패니온은<br />--><br />
			<strong>LOGIN</strong><br /> <br />
			<!--<strong>담대하게...</strong>-->
		</h2>

		<fieldset>
		<form id="logIn" name="frm" method="post">
			<legend>로그인 정보입력</legend>
			<span class="inp-id"><input type="text" id="systemId" name="adm_id" class="txt" placeholder="ID" /></span>
			<span class="inp-pw"><input type="password" id="systemPw" name="adm_pw" class="txt" placeholder="PASSWORD" /></span>
			<p class="msg-error" id="msg-error"><!--해당 아이디가 없습니다.--></p>
			<button type="button" id="btn_login">LOGIN</button><br />
			<!--<button type="button" id="btn_reset">RESET PASSWORD</button>-->
		</form>
		</fieldset>
	</div>

<script type="text/javascript" src="/admin/js/common_ui.js"></script>
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
		  
		// document.location = "/admin/login/ajax_login_dml.php?mode=S&adm_id="+adm_id+"&adm_pw="+adm_pw;

		var request = $.ajax({
			url:"/admin/login/ajax_login_dml.php",			
			type:"POST",
			data:{mode:mode, adm_id:adm_id, adm_pw:adm_pw},
			dataType:"json"
		})

		request.done(function(data) {
			if (data.result == "0") {				
				<? if ($eas <> "") { ?>
					document.location = "<?=$next_url?>";								
				<? } else { ?>
					document.location = "/admin/admingroup/admingroup_list.php";					
				<? } ?>
			} else {				
				$("#msg-error").html(data.msg);
			}
			// alert(data.result);
			// alert(data.msg);
		});
/*
		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});
*/
		
	});
	
	
	$(document).on("click", "#btn_reset", function() {
		document.location = "/admin/reset.php";				
	});
	
 
 
</script>
</body>
</html>