<? session_start();?>
	
<?
	
	$rs_adm_no		= $_SESSION['s_adm_no'];
	$str_resetpw = trim(uniqid());	
	$str_resetpasswd = encrypt($key, $iv, $str_resetpw);
	$str_update = updatePassword($conn, $rs_adm_no, $str_resetpasswd);
	                       
	$passwd = $str_resetpw;
?>

<? 
/////////////////////////////http://ucomp.co.kr/manager/approval/approval_list.php  ////////////////////check
  $mail_string = '
<body>
	<div>
		<table cellspacing="0" cellpadding="0" style="width:600px;" border="1">
			<tr>
				<td style="text-align:center">
					<div style="padding:40px;">
						<img src="http://ucomdev.ucomp.co.kr/manager/images/bell.png" style="width:40px;" alt="유컴패니온" />
					</div>
					<div style="padding-bottom:40px;font-size:14px;font-weight:600; color:#111; font-family:나눔고딕, NanumGothic, 돋움, Dotum, Sans-serif">
						임시 비밀번호가 발급 되었습니다.<br/>
						임시 비밀번호로 로그인한 후 비밀번호를 변경해 주세요.
					</div>
					<div style="padding-bottom:40px;vertical-align:middle;">
						임시 비밀번호
						<div style="background:#2e323f;width:320px;line-height:40px;border-radius:50px;display: inline-block;font-weight:600">
							'.$passwd.'
						</div>
					
					</div>
				</td>
			</tr>
			<tr>
				<td style="padding:30px 0; background:#f7f7f7; font-weight:600">
					<p style="margin:10px 0 0 0; padding:0; text-align:center">
						<a href="http://ucomdev.ucomp.co.kr"  target="_blank" title="홈페이지 바로가기" style="color:#666; font-size:14px; font-family:나눔고딕, NanumGothic, 돋움, Dotum, Sans-serif; text-decoration:none">시스템 관리자 : www.ucomp.co.kr</a>
					</p>
				</td>
			</tr>
		</table>
	</div>
</body>';
?>
<!--
<html>
<head>
<title>유컴패니온</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
</head>
<body>
	<div>
		<table cellspacing="0" cellpadding="0" style="width:600px;" border="1">
			<tr>
				<td style="text-align:center">
					<div style="padding:40px;">
						<img src="http://www.ucomp.co.kr/manager/images/bell.png" style="width:80px;" alt="유컴패니온" />
					</div>
					<div style="padding-bottom:40px;font-size:14px;font-weight:600; color:#111; font-family:나눔고딕, NanumGothic, 돋움, Dotum, Sans-serif">
						지출결의 승인을 기다리는 내역이 있습니다!
					</div>
					<div style="padding-bottom:40px;vertical-align:middle;">
						<a href="http://ucomp.co.kr/manager/login.php?eas=approval" target="_blank" style="text-decoration:none;color:#ed1a3c">
						<div style="background:#2e323f;width:320px;line-height:40px;border-radius:50px;display: inline-block;font-weight:600">
							승인 하러 가기
						</div>
						</a>
					</div>
				</td>
			</tr>
			<tr>
				<td style="padding:30px 0; background:#f7f7f7; font-weight:600">
					<p style="margin:10px 0 0 0; padding:0; text-align:center">
						<a href="http://www.ucomp.co.kr"  target="_blank" title="홈페이지 바로가기" style="color:#666; font-size:14px; font-family:나눔고딕, NanumGothic, 돋움, Dotum, Sans-serif; text-decoration:none">www.ucomp.co.kr</a>
					</p>
				</td>
			</tr>
		</table>
	</div>
</body>
-->