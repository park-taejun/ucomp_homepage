<?
	session_start();
	if(!session_is_registered("s_adm_id")){
?>
	<script language="javascript">
		alert("세션이 종료 되어 다시 로그인 하셔야 합니다.");
		parent.location="/manager/";	
	</script>
<?
	} else {
		$s_adm_id = $s_adm_id;
	}
?>
