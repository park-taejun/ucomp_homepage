<%
	'-------------------------------------------------------------------------------
	'  작  성 : 박찬호 (2003.11.20)
	'  수  정 : 
	'  용  도 : 관리자 권한 체크
	'  Copyright 1999-2000, Drimone Communications Co., Ltd. All rights are reserved
	'-------------------------------------------------------------------------------

   	Response.Expires = 0
   
   	Dim s_admin_id, s_flag

   	s_admin_id = Request.Cookies("s_admin_id")
   	s_flag = Request.Cookies("s_flag")

	if s_admin_id = "" Then
%>
	<script language="javascript">
		alert("권한이 없습니다. 로그인 하셔야 합니다.");
		parent.location="index.asp";	
	</script>
<%
		Response.End
	End if
%>