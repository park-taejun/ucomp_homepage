<!-- #include virtual="/manager/shared/login_check.asp" -->
<%
' =============================================================================
' File Name    : parent_code_sub_list.asp
' Modlue       : ihico > manager > code > parent code sub list
' Writer       : Park Chan Ho 
' Create Date  : 2004.11.04
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
<!-- #include virtual="/manager/shared/common_right.asp" -->
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
Dim arrList			'array for record set

'' Search Condition
Dim sSearchField	' Search Field
Dim sSearchString	' Search String

'' Lang Condition
Dim sLang			' Select Language for site
Dim sLanguage		' Language Name

Dim sParameter		' SP Parameter variables 

'====================================================================
' Request Parameter
'====================================================================
'' Search Condition
sSearchField = trim((Request("SearchField")))	' Search Field
sSearchString = trim((Request("SearchString")))	' Search String

'' Lang Condition
sLang = trim((Request("Lang")))	' Select Language for site
'====================================================================
' sLang 
' 1 : English
' 2 : Japanese
' 3 : Chinese1(GB)
' 4 : Chinese2(BIG5)
' 5 : French
' 6 : German
' 7 : Spanish
' 8 : Russian
'====================================================================
If sLang = "" Then
	sLang = "1"
End If

Select Case sLang
	Case "1"		sLanguage = getLabel("L0234", g_arrList) '"영어"
	Case "2"		sLanguage = getLabel("L0235", g_arrList) '"일어"
	Case "3"		sLanguage = getLabel("L0236", g_arrList) '"중어간체"
	Case "4"		sLanguage = getLabel("L0237", g_arrList) '"중어번체"
	Case "5"		sLanguage = getLabel("L0238", g_arrList) '"불어"
	Case "6"		sLanguage = getLabel("L0239", g_arrList) '"독어"
	Case "7"		sLanguage = getLabel("L0240", g_arrList) '"서반아어"
	Case "8"		sLanguage = getLabel("L0241", g_arrList) '"러시아어"
	Case Else		sLanguage = getLabel("L0234", g_arrList) '"영어"
End Select



sParameter = ""

If sSearchString = "" Then
	sParameter = " null," 
	sParameter = sParameter & " null, " 
	sParameter = sParameter & "'" & sLang & "'"
Else
	sParameter = "'" & sSearchField & "',"
	sParameter = sParameter & "'" & sSearchString & "',"
	sParameter = sParameter & "'" & sLang & "'"
End If

'============================================================
' Setting select box
'============================================================
'' Search Field
Dim arrSelect1(1)

Select Case sSearchField '(pcode_name , pcode)
	Case "pcode_name"	arrSelect1(0) = "selected"
	Case "pcode"		arrSelect1(1) = "selected"
	Case Else	        arrSelect1(0) = "selected"
End Select

'=====================================================================
' DB Connection
'=====================================================================
objDbCon.Open strDbConnect

'=================================================================
' Get Result set from stored procedure
'=================================================================
objRS.Open "ASearch_Parent_Code_Sub_List "& sParameter, objDbCon
	If NOT objRS.EOF Then
		arrList = objRS.GetRows()
	End If
objRS.Close
'=====================================================================
' DB Close
'=====================================================================
objDbCon.Close

response.clear()
response.flush()

%>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
<title>IHICO Manager System</title>
<LINK REL="StyleSheet" HREF="/include/css/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<script LANGUAGE="JavaScript">
<!--

function goEdit() {
	var frm_search = document.frm_search; 
	var frm = document.frm; 

	document.frm_search.pcode.value = document.frm.pcode.value;
	document.frm_search.pcode_name.value = document.frm.pcode_name.value;
	
	frm_search.action = "parent_code_sub_dml.asp";
	frm_search.submit();
}

function Edit_Mode(pcode, pcode_name)
{
	modifyfrm.style.display = "";

	document.frm.pcode.value = pcode;
	document.frm.pcode_name.value = pcode_name;
	document.frm.pcode_name.focus();
}

//-->
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
				<tr>
					<td height="30" style="padding-left:20px"><a href="#"><%=getLabel("L0015", g_arrList)%><!--시스템 관리--></a> > <a href="#"><%=getLabel("L0016", g_arrList)%><!--코드관리--></a> > <a href="#"><%=getLabel("L0349", g_arrList)%><!--대분류 코드--></a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" >
				<tr>
					<td width="30"><img src="/images/admin/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Select Language Section -->
						<table cellpadding="0" cellspacing="0" border="0" width="95%" bgcolor="#ffffff"> 
							<tr>
								<td height="50" style="padding-left:20px" align="center">
									<a href="parent_code_list.asp"><% if sLang=0 then %><b>Total</b><% else %>Total<% end if%></a> | 
									<a href="parent_code_sub_list.asp?Lang=1"><% if sLang=1 then %><b>English</b><% else %>English<% end if%></a> | 
									<a href="parent_code_sub_list.asp?Lang=2"><% if sLang=2 then %><b>Japanese</b><% else %>Japanese<% end if%></a> | 
									<a href="parent_code_sub_list.asp?Lang=3"><% if sLang=3 then %><b>Chinese1(GB)</b><% else %>Chinese1(GB)<% end if%></a> | 
									<a href="parent_code_sub_list.asp?Lang=4"><% if sLang=4 then %><b>Chinese2(BIG5)</b><% else %>Chinese2(BIG5)<% end if%></a> | 
									<a href="parent_code_sub_list.asp?Lang=5"><% if sLang=5 then %><b>French</b><% else %>French<% end if%></a> | 
									<a href="parent_code_sub_list.asp?Lang=6"><% if sLang=6 then %><b>German</b><% else %>German<% end if%></a> | 
									<a href="parent_code_sub_list.asp?Lang=7"><% if sLang=7 then %><b>Spanish</b><% else %>Spanish<% end if%></a> | 
									<a href="parent_code_sub_list.asp?Lang=8"><% if sLang=8 then %><b>Russian</b><% else %>Russian<% end if%></a> 
								</td>
							</tr>
						</table>
<!-- Select Language Section End -->
						<table cellpadding="0" cellspacing="1" border="0" width="95%">
						<form name="frm_search" action="parent_code_list.asp" method="post">
							<input type="hidden" name="pcode" value="">	
							<input type="hidden" name="Lang" value="<%=sLang%>">
							<input type="hidden" name="pcode_name" value="">
							<input type="hidden" name="mode" value="mod">
							<!--
							<tr>
								<td>
									<select name="SearchField">
										<option value="pcode_name" <%=arrSelect1(0)%>>코드명</option>
										<option value="pcode" <%=arrSelect1(1)%>>코드</option>
									</select>
									<input type="Text" name="SearchString" value="<%=sSearchString%>">
									<input type="image" src="/images/admin/btn_search.gif" border="0" align="absmiddle" hspace="5" style="cursor:hand">
								</td>
							</tr>
							-->
							<tr>
								<td height="10"></td>
							</tr>
						</form>
						</table>
						* <%=getLabel("L0343", g_arrList)%><!--관리자가 대분류 코드의 해당 코드명을 수정하실 수 있습니다.--><br>
						* <%=getLabel("L0344", g_arrList)%><!--각 언어별 코드명은 관리자의 편의 사항 입니다. 반드시 입력 하실 필요는 없습니다.-->
						<table cellpadding="0" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="2" bgcolor="#000000" colspan="4"></td>
							</tr>
							<tr align="center" bgcolor="#E9E9E9" height="25">
								<td><%=getLabel("L0322", g_arrList)%><!--코드--></td>
								<td><%=getLabel("L0334", g_arrList)%><!--코드명(한국어)--></td>
								<td><%=getLabel("L0323", g_arrList)%><!--코드명(<%=sLanguage%>)--></td>
								<td><%=getLabel("L0345", g_arrList)%><!--메뉴--></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="4"></td>
							</tr>
<%
'===================================================================
' Print List
'===================================================================
	Dim nCnt , nMAX
		nCnt = 0

	If IsArray(arrList) Then

		nMAX = UBound(arrList,2)

       	For nCnt = 0 to nMAX
%>

							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td><%=arrList(0,nCnt)%></td>
								<td><%=arrList(2,nCnt)%></td>
								<td><%=arrList(3,nCnt)%></td>
								<td>
									[<a href="javascript:Edit_Mode('<%=arrList(0,nCnt)%>','<%=arrList(3,nCnt)%>');"><%=getLabel("L0051", g_arrList)%><!--수정--></a>] 
									[<a href="detail_code_sub_list.asp?pcode=<%=trim(arrList(0,nCnt))%>&Lang=<%=sLang%>"><%=getLabel("L0319", g_arrList)%><!--세부코드--></a>]
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
<%
		Next
	Else
%>
							<tr align="center">
								<td height="25" colspan="4"><%=getLabel("L0352", g_arrList)%><!--한국어 등록 목록이 없습니다.--></td>
							</tr>
<%   
	End IF  
%>
							<tr>
								<td height="10" colspan="4"></td>
							</tr>
							<!--
							<tr align="right">
								<td height="25" colspan="4"><a href="list_excel.asp" target="_blank"><img src="/images/admin/btn_excel.gif" border="0" align="absmiddle"></a>&nbsp;
								<a href="parent_code_write.asp"><img src="/images/admin/btn_regist_new.gif" border="0" align="absmiddle"></a></td>
							</tr>
							-->
							<tr align="center">
								<td align="center" colspan="4">
								</td>
							</tr>
						</table>
						<br>
<!-- Edit Form -->
						<div id=modifyfrm style="display:none;">
						<FORM name="frm" method="POST" style="margin:0;">
						<input type="hidden" name="pcode" value="">
						<table cellpadding="0" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#000000" colspan="4"></td>
							</tr>
							<tr align="center">
								<td width="20%" height="27" bgcolor="#E9E9E9"><b><%=sLanguage%></b></td>
								<td width="60%" align="left">&nbsp;
									<input type="text" name="pcode_name" size="50" maxlength="100">
								</td>
								<td width="20%" bgcolor="#E9E9E9" align="center">
									<a href="javascript:goEdit();"><img src="<%=g_ImagePath%>btn_edit.gif" border="0" align="absmiddle"></a>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#000000" colspan="4"></td>
							</tr>
						</table>
						</FORM>
						</div>	  
<!-- Edit Form End -->

					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>