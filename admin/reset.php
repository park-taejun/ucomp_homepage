<? session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 

echo "_SESSION['s_adm_no'] : ".$_SESSION['s_adm_no']."<br />";
echo "eas : ".$eas."<br />";
	
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
		<!--
		<h1><img src="images/logo.png" alt="U: 유컴패니온" /></h1>		
		<h2>
			코로나<br />
			<strong>그 다음 세상을</strong><br /> 준비합시다.<br />
			<strong>담대하게...</strong>
		</h2>
		-->
		<h2>			
			<strong>RESET PASSWORD</strong>			
		</h2>

		<fieldset>
		<form id="logIn" name="frm" method="post">
			
			<legend>로그인 정보입력</legend>
			<span class="inp-id"><input type="text" id="systemId" name="adm_id" class="txt" placeholder="ID" /></span>			
			<span class="inp-pw"><input type="text" id="systemMail" name="adm_email" class="txt" placeholder="COMPANY MAIL" /></span>
			<p class="msg-error" id="msg-error"><!--해당 아이디가 없습니다.--></p>
			<!--<button type="button" id="btn_mail" onClick="js_mail();">SUBMIT</button><br />-->
			<button type="button" id="btn_mail" >SUBMIT</button><br />			
			<button type="button" id="btn_changepasswd" >CHANGE PASSWORD</button><br />			
		</form>
		</fieldset>
	</div>

<script type="text/javascript" src="/manager/js/common_ui.js"></script>
<script>

	$("#systemMail").keypress(function(e) {
		if (e.keyCode == 13) { 
			$('#btn_mail').trigger('click');
		}
	});
	
	$(document).on("click", "#btn_mail", function() { 
		
		var mode = "S";
		var adm_id = $("#systemId").val().trim();		
		var adm_email = $("#systemMail").val().trim();
		
		if (adm_id == "") {
			alert("아이디를 입력하세요.");
			return;
		}

		if (adm_email == "") {
			alert("이메일을 입력하세요.");
			return;
		} else {
			fn_email(adm_email);
		}
		 
		var request = $.ajax({
			url:"/admin/login/ajax_reset_dml.php",
			type:"POST",			
			data:{mode:mode, adm_id:adm_id, adm_email:adm_email},			
			dataType:"json"
		})

		request.done(function(data) {
			
			if (data.result == "0") {
				
				document.location = "/expense/expense_mail.php";
				
			} else {
				$("#msg-error").html(data.msg);
			}
		 
		});
		 
		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

	});
	
	$(document).on("click", "#btn_changepasswd", function() { 
		document.location = "/admin/changepasswd.php";				
	});
	
	function fn_email(adm_email) {
		var regExp = /^([0-9a-zA-Z_\.-]+)@([0-9a-zA-Z_-]+)(\.[0-9a-zA-Z_-]+){1,2}$/;
					
		if(!regExp.test(adm_email)){
			alert("이메일 형식이 올바르지 않습니다.");
			return false;
		}
		return true;
	}
	
</script>
</body>
</html>