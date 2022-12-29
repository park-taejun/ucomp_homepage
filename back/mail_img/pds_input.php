<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
?>		
<HTML>
<HEAD>
<TITLE><?echo $g_site_title?></TITLE>
<link rel="stylesheet" href="inc/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<SCRIPT language="javascript">
<!--

function goList() {
	document.frm.action = "pds_list.php";
	document.frm.submit();
	
} 


function check_form() {

   if (document.frm.title.value == "") {
      alert ('제목을 입력하세요');
      document.frm.title.focus();
      return;
   }

   if (document.frm.name.value == "") {
      alert ('글쓴이을 입력하세요');
      document.frm.name.focus();
      return ;
   }

	 if( checkIt(document.frm) ){
			document.frm.action = "pds_db.php";
		  document.frm.submit()
	 } else {
		  return;
	 }

//	document.frm.submit();

}

function checkIt(form) {      
   getEditCheck(form);
   form.data.value = form.BB_CONTENT.value;
   if(!form.data.value) {
      alert('내용을 입력하세요!');
      return false;
   }
   return true;
}
//-->
</script>
</HEAD>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
				<tr>
					<? 
						$title_mode = "Write";
						include "./inc/pds_title.inc"; 
					?>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<form name="frm" method='post' action='pds_db.php' enctype='multipart/form-data'>
				<input type="hidden" name="mode" value="addnew">
				<input type='hidden' name='page' value="<?echo $page?>">
				<input type="hidden" name="idxfield" value="<?echo $idxfield?>">
				<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
				<input type='hidden' name='passwd' value="###############">
				<input type='hidden' name='code' value="<?echo $code?>">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">제 목</td>
								<td style="padding-left:10px" colspan="3">
									<input type=text name="title" size=45>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">글쓴이</td>
								<td style="padding-left:10px" colspan="3">
									<input type=text name="name" value="<?echo $s_adm_name?>" size=25>	
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">내 용</td>
								<td style="padding-left:10px" colspan="3" bgcolor="#FFFFFF" align=center height="500">
									<!--<textarea name="data" style="width:690px; height:350px"></textarea>-->
									<input type="hidden" name="data" value="">
									<? $image_path = "PDS_".$code;?>
									<? include("./htmledit/init.php");	?>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<!--
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">파일첨부</td>
								<td style="padding-left:10px" colspan="3">
									<input type="file" name="pds" size="50">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							-->
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<a href="javascript:check_form();"><img src="images/button_save.gif" border="0"></a>&nbsp;
									<a href="javascript:goList();"><img src="images/button_list_01.gif" border="0"></a><?echo $image_path?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
<!-- Main Section End -->
		</td>
	</tr>
</table>
</form>
</body>
</html>