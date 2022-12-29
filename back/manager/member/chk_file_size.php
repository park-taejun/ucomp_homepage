<?session_start();?>
<?
	header("Content-Type: text/html; charset=UTF-8"); 

	//echo $_FILES[file_nm][size];

	if ($_FILES[file_nm][size] > 1912390) {
?>
<script>
	alert("동의서 파일은 2MB 이하로 등록해 주세요.");
	parent.document.frm.file_nm.value = "";
	parent.document.frm.target = "";
</script>
<?
	}
?>
