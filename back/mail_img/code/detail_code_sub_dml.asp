<!-- #include virtual="/include/shared/login_check.asp" -->
<%
' =============================================================================
' File Name    : detail_code_sub_dml.asp
' Modlue       : overseas > t2k manager > code > detail code sub DataBase Module
' Writer       : Park Chan Ho 
' Create Date  : 2004.11.17
' Modify Date  : 
' =============================================================================

'====================================================================
' 권한 체크 및 번역
'====================================================================

Dim sPageRight_ 
Dim sPageRight_R 
Dim sPageRight_I 
Dim sPageRight_U 
Dim sPageRight_D 

sPageRight_ = "M901"

'g_ImagePath 다국어 이미지 경로 모든 이미지경로는 이부분을 추가 해야 합니다.
'sPageRight_R 해당 메뉴 읽기 권한
'sPageRight_I 해당 메뉴 입력 권한
'sPageRight_U 해당 메뉴 수정 권한
'sPageRight_D 해당 메뉴 삭제 권한
'sPageRight_  해당 메뉴 코드 메뉴 관리 에서 지정된 코드 값을 사용한다.

%>
<!-- #include virtual="/include/shared/common_right.asp" -->
<%
'====================================================================
' Language variables
'====================================================================
%>
<!-- #include virtual="/include/shared/common_trans.asp" -->
<%
'====================================================================
' 권한 체크 및 번역
'====================================================================
%>


<!-- #include virtual="/include/dbcon/overseas_db_open.asp" -->
<%
'====================================================================
' Declare variables
'====================================================================
' System Parameter
Dim sPcode, sDcode, sLang			
Dim mode			'Process Mode
					'insert value = "add"
					'update value = "mod"
					'delete value = "del"
Dim Result			'DML return value

'User input values
Dim sDcode_name			

'' Search Condition
Dim sSearchField	' Search Field
Dim sSearchString	' Search String

'====================================================================
' Request Parameter
'====================================================================
' System Parameter
sMode = trim(Request("mode"))
sPcode = trim(Request("pcode"))
sDcode = trim(Request("dcode"))
sLang = trim(Request("lang"))

'' Search Condition
sSearchField = trim((Request("SearchField")))	' Search Field
sSearchString = trim((Request("SearchString")))	' Search String

'User Input Parameter
sDcode_name = trim(Request("dcode_name"))

'Chack Data  Find "'" and replace "''"	
sDcode_name = Replace(sDcode_name, "'", "''")

'=====================================================================
' DB Connection
'=====================================================================
objDbCon.Open strDbConnect

'=====================================================================
' DML Process
'=====================================================================

If (sMode = "mod") Then

'=====================================================================
' Update as stored procedure
'=====================================================================

	With objCmd

		.ActiveConnection = objDbCon
		.CommandType = adCmdStoredProc
		.CommandText = "AUpd_Detail_Sub_Code"

		.Parameters.Append .CreateParameter("RETURN_VALUE"			,adInteger	,adParamReturnValue)
		.Parameters.Append .CreateParameter("@strPcode"				,adVarChar	,adParamInput	,6	, sPcode)
		.Parameters.Append .CreateParameter("@strDcode"				,adVarChar	,adParamInput	,6	, sDcode)
		.Parameters.Append .CreateParameter("@strDcode_name"		,adVarWChar	,adParamInput	,50	, sDcode_name)
		.Parameters.Append .CreateParameter("@strLang"				,adVarChar	,adParamInput	,2	, sLang)
		.Execute ,,adExecuteNoRecords

		Result  = .Parameters("RETURN_VALUE")

	End With

'=====================================================================
' DB Close
'=====================================================================
objDbCon.Close

'=====================================================================
' RETURN_VALUE
' 0 : Success
' 1 : DB error
'===================================================================== 

'=====================================================================
' DB Error
'===================================================================== 
	IF Result = 1 Then

%>
<HTML>
<HEAD>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Tour2Korea.com</title>
<script language="javascript">
<!--
	function init() {
		alert("<%=getLabel("L0104", g_arrList)%>"); //DB 오류 입니다.
		history.back();
	}
//-->
</script>
</HEAD>
<body onLoad="init();">
</body>
</HTML>
<%
		'Response.End


	End If
'=====================================================================
' DB Done
'===================================================================== 
%>
<HTML>
<HEAD>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Tour2Korea.com</title>
<script language="javascript">
<!--
	function init() {
		alert("<%=getLabel("L0102", g_arrList)%>"); //저장 되었습니다.
		document.frm.submit();
	}
//-->
</script>
</HEAD>
<body onLoad="init();">
<form name="frm" action="detail_code_sub_list.asp" method="post">
<input type="hidden" name="SearchField" value="<%=sSearchField%>">
<input type="hidden" name="SearchString" value="<%=sSearchString%>">
<input type="hidden" name="Lang" value="<%=sLang%>">
<input type="hidden" name="pcode" value="<%=sPcode%>">
</form>
</body>
</html>
<%
'	Response.Write "<meta http-equiv=Content-Type content='text/html; charset=UTF-8'><script>alert('Update complete.'); document.location.href='parent_code_list.asp';</script>
	Response.end
	
End If

%>
