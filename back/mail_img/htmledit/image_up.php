<style>
  html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt; position: absolute; };
</style>
<title>이미지 첨부</title>
<script language="javascript">
<!--
	function upload_file()
	{
		if ( document.inputform.imgfile.value == "" ) return;
		document.inputform.action="image_save.php";
		
		document.inputform.submit();
	}	
</script>	
<BODY style="background: threedface; color: windowtext;" scroll=no  >
	<DIV id=divAltText style="left: 0.98em; top: 3em; width: 6.58em; height: 1.2168em; ">이미지첨부:</DIV>
	<form  enctype="multipart/form-data" name="inputform" method="post">
	<input type='hidden' name='image_path' value='<? echo $image_path; ?>'>
	<INPUT type='file' name="imgfile" style="left: 7.54em; top: 2.5em; width: 24.5em; height: 2.1294em; " >
	</form>
	<input type=button value="확인" onClick="upload_file();"  style="left: 5.54em; top: 6.5em; width: 9em; height: 2.1294em; ">
	<input type=button value="취소" onClick="self.close();" style="left: 16.54em; top: 6.5em; width: 9em; height: 2.1294em; ">
</BODY>