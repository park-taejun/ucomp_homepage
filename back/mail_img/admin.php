<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin.php
	// 	Description : 관리자 로그인 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "./inc/global_init.inc";
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<style type='text/css'>
<!--
.border_top
{
   border-top-style:solid;
   border-top-width:1;
}

.border_bottom
{
   border-bottom-style:solid;
   border-bottom-width:1;
}

.border_left
{
   border-left-style:solid;
   border-left-width:1;
}

.border_right
{
   border-right-style:solid;
   border-right-width:1;
}

.input {
   font-size: 9pt;
   vertical-align: middle;
   background-color: #FFFFFF;
   color: #0000FF;
   border-style: solid;
   border-width: 1;
   border-color: #000000;
   padding-left: 10;
}

tr, th, td {
   font-family:"돋움";
   font-size:9pt;
   line-height:1.5em;
}

td a {
   text-decoration: none;
   color: #000000;
}

td a:visited {
   text-decoration: none;
   color: #000000;
}

td a:hover {
   text-decoration: underline;
   color: #FF0000;
}

.submit {
   font-size: 9pt;
   text-align: center;
   vertical-align: middle;
   background-color: #FFFFFF;
   color: #0000FF;
   border-style: solid;
   border-width: 1;
   border-color: #000000;
   padding-top: 1;
}

.text {
   font-size: 9pt;
}


//-->
</style>
<script language='javascript'>
<!--
function check_form(form)
{
	if (form.adminid.value == "")
	{
		alter ('관리자의 아이디를 입력해 주세요.');
		form.adminid.focus();
		return false;
	}
	if (form.adminpasswd.value == "")
	{
		alter ('관리자의 패스워드를 입력해 주세요.');
		form.adminid.focus();
		return false;
	}
}
//-->
</script>
</head>
<body>
<br><br><br>
<table border='0' width='600' align='center' cellpadding='9' cellspacing='0'>
	<tr>
		<td align='center' width='100%' class='text border_left border_top border_bottom border_right' style='border-color: #000000;'>
			<table border='0' width='98%' cellpadding='2' cellspacing='0'>
				<tr>
					<td align='center'><font face='소망B' size='3' color='#aaaaaa'><?echo $g_site_name?> 관리자 로그인</font></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<form method='post' name='form' action='admin_ok.php' onSubmit='return check_form(this);'>
<table border='0' width='600' align='center' cellpadding='9' cellspacing='0'>
	<tr>
		<td align='center' width='100%' class='text border_left border_top border_bottom border_right' style='border-color: #000000;'>
			<table border='0' width='98%' cellpadding='2' cellspacing='0'>
				<tr>
					<td align='center' class='text border_left border_top border_bottom border_right' style='border-color: #aaaaaa;' width='100'>관리자 ID</td>
					<td align='left' class='text border_top border_bottom border_right' style='border-color: #aaaaaa;'>&nbsp;<input type='text' name='adminid' size='20' maxlength='20' class='input'></td>
				</tr>
				<tr>
					<td align='center' class='text border_left border_bottom border_right' style='border-color: #aaaaaa;'>관리자 암호</td>
					<td align='left' class='text border_bottom border_right' style='border-color: #aaaaaa;'>&nbsp;<input type='password' name='adminpasswd' size='20' maxlength='20' class='input'></td>
				</tr>
			</table>
		</td>
	</tr>
</table><br>
<table border='0' width='600' align='center'>
				<tr>
					<td align='center' colspan='2'><input type='image' src='images/ok.jpg' border='0' title='확인'>&nbsp;&nbsp;<a href="javascript:cancel('cancel');"><img src='images/cancel00.jpg' border='0' title='취소'></a></td>
				</tr>
</table></form>
<script language='javascript'>
function cancel(strType)
{
	if (strType == "cancel")
	{
		document.location.href = "<?echo $g_site_url?>";
	}
}
</script>
</body>
</html>
