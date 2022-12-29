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
			<strong>CHANGE PASSWORD</strong>			
		</h2>

		<fieldset>
		<form id="logIn" name="frm" method="post">
			
			<legend>로그인 정보입력</legend>
			<span class="inp-id"><input type="hidden" id="systemId" name="adm_id" class="txt"  value="cadt" /></span>	
			<span class="inp-id"><input type="text" id="expasswd" name="ex_passwd" class="txt" placeholder="EX PASSWORD" /></span>	
			<span class="inp-pw"><input type="text" id="newpasswd" name="new_passwd" class="txt" placeholder="NEW PASSWORD" /></span>
			<span class="inp-pw"><input type="text" id="confirmpasswd" name="con_passwd" class="txt" placeholder="NEW PASSWORD CONFIRM" /></span>
			<p class="msg-error" id="msg-error"><!--해당 아이디가 없습니다.--></p>
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
	
	$(document).on("click", "#btn_changepasswd", function() { 
		
		var mode = "S";
		var adm_id = $("#systemId").val().trim();
		var ex_passwd = $("#expasswd").val().trim();		
		var new_passwd = $("#newpasswd").val().trim();
		var con_passwd = $("#confirmpasswd").val().trim();
		
		if (ex_passwd == "") {
			alert("현재 비밀번호를 입력 해주세요.");
			return;
		} else {			
			var request = $.ajax({
				url:"/admin/login/ajax_passwd_dml.php",
				type:"POST",			
				data:{mode:mode, adm_id:adm_id, ex_passwd:ex_passwd, new_passwd:new_passwd},
				dataType:"json"
			})

			request.done(function(data) {				
				if (data.result != "0") {
					$("#msg-error").html(data.msg);					
				}
			});
		}

		if (new_passwd == "") {
			alert("비밀번호를 입력하세요.");
			return;		
		}

		if (new_passwd != "") {
			fn_passwd(new_passwd);
			return;
		}		
		 
		if (con_passwd == "") {
			alert("비밀번호 확인을 입력하세요.");
			return;		
		}
		
		if (con_passwd != "") {
			fn_passwd(con_passwd);
			return;		
		}
		
		if (new_passwd != con_passwd) {
			alert("새 비밀번호와 일치하지 않습니다.");
			return;
		} else {
			var request = $.ajax({
				url:"/admin/login/ajax_passwd_dml.php",
				type:"POST",			
				data:{mode:mode, adm_id:adm_id, ex_passwd:ex_passwd, new_passwd:new_passwd},	
				dataType:"json"
			})

			request.done(function(data) {				
				if (data.result != "0") {
					$("#msg-error").html(data.msg);					
				}
			});
		}
 
	});
	
	function fn_passwd(passwd) {
		var regExp = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,10}$/;
					
		if(!regExp.test(passwd)){
			alert("비밀번호는 영문 대문자, 영문 소문자, 숫자, 특수기호를 포함하여 8~10자로 입력 해주세요."); 
			// return false;
			// var aa = "a";			
			// return aa;
		} else {
			//var aa = "b";
			//return aa;
		}
		return true; 
	} 
	
</script>
</body>
</html>