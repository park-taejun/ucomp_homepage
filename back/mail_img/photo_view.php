<?
	include "admin_session_check.inc";
	include "../dbconn.inc";
	include "./inc/global_init.inc";
	
	$query = "select * from tb_photo where photo_id = '$id'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$photo_title = $list[photo_title];
	$thumbnail = $list[thumbnail];
	$image_zoom = $list[image_zoom];
	$photo_id = $list[photo_id];

?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="photo_list.php?page=<?echo $page?>";
		document.frm.submit();
	}

	function goIn() {
				
//		if (document.frm.thumbnail.value == "") {
//			alert("작은 이미지를 선택해 주십시오.");
//			return;
//		}

//		if (document.frm.image_zoom.value == "") {
//			alert("큰 이미지를 선택해 주십시오.");
//			return;
//		}
		
		document.frm.target = "frhidden";
		document.frm.action = "photo_db.php";
		document.frm.submit();
		
	}
		
//-->
</SCRIPT>
</HEAD>
<BODY>
<form name='frm' method='post' action='photo_db.php' enctype='multipart/form-data'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>홍보사진</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="수정" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table class="IN">
		<tr>
			<th>
				사진 제목 :
			</th>
			<td>
				<input type="text" name="photo_title" size="50" value="<?echo $photo_title?>">
			</td>
		</tr>
		<tr>
			<th>
				작은 이미지 :
			</th>
			<td>
				<input type="file" name="thumbnail" size="40"><br>
				<img src="/usr_img/<?echo $thumbnail?>" width="100">
			</td>
		</tr>
		<tr>
			<th>
				큰 이미지 :
			</th>
			<td>
			<input type="file" name="image_zoom" size="40"><br>
			<img src="/usr_img/<?echo $image_zoom?>" width="200">
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<input type="hidden" name="photo_id" value="<?echo $photo_id?>">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="mode" value="mod">
</FORM>
</body>
</html>
<?
mysql_close($connect);
?>