<!-- #include virtual="/manager/shared/login_pop_check.asp" -->
<%
' =============================================================================
' File Name    : pop_dcode_dup.asp
' Modlue       : ihico > manager > code > dcode duplicate check
' Writer       : Park Chan Ho 
' Create Date  : 2005.05.11
' Modify Date  : 
' =============================================================================

'====================================================================
' ���� üũ �� ����
'====================================================================

Dim sPageRight_ 
Dim sPageRight_R 
Dim sPageRight_I 
Dim sPageRight_U 
Dim sPageRight_D 

sPageRight_ = "M901"

'g_ImagePath �̹��� ��� ��� �̹�����δ� �̺κ��� �߰� �ؾ� �մϴ�.
'sPageRight_R �ش� �޴� �б� ����
'sPageRight_I �ش� �޴� �Է� ����
'sPageRight_U �ش� �޴� ���� ����
'sPageRight_D �ش� �޴� ���� ����
'sPageRight_  �ش� �޴� �ڵ� �޴� ���� ���� ������ �ڵ� ���� ����Ѵ�.

%>
<!-- #include virtual="/manager/shared/common_right.asp" -->
<%
'====================================================================
' image variables
'====================================================================
g_ImagePath = "../images/"
%>
<%
'====================================================================
' ���� üũ �� ����
'====================================================================
%>

<%
'====================================================================
' Declare variables
'====================================================================
'' Search Condition
Dim sSearchFlag		' Search Flag
'' system variable
Dim sPcode

'' User input variable
Dim sDcode

Dim sParameter	' SP Parameter variables 

Dim sExist		' Dup Flag

'====================================================================
' Request Parameter
'====================================================================
'' Search Condition
sSearchFlag = trim(Request("SearchFlag"))	' Search flag

'' System Parameter
sPcode = trim(Request("pcode")) 
'' User input Parameter
sDcode = trim(Request("dcode")) 

sParameter = "'" & sPcode & "',"
sParameter = sParameter & "'" & sDcode & "'"

sExist = 0

If sSearchFlag = "Y" Then

%>
<!-- #include virtual="/manager/dbcon/ihico_db_open.asp" -->
<%	
'=====================================================================
' DB Connection
'=====================================================================
	objDbCon.Open strDbConnect
' ==========================================================================
' Get Data
' ==========================================================================
	objRS.Open "AGet_Detail_Code_Dup "& sParameter, objDbCon
		sExist = Trim(objRS("CNT"))		'' CNT
	objRS.Close
	
'=====================================================================
' DB Close
'=====================================================================
	objDbCon.Close

End If

response.clear()
response.flush()

%>
<html>
<head>
<title>IHICO Manager System</title>
<meta http-equiv=Content-Type content="text/html; charset=euc-kr">
<LINK REL="StyleSheet" HREF="/manager/css/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<script language="javascript" src="/manager/js/common.js"></script>
<script language="javascript">
<!--	
	function Sendit() {
		with(document.frm) {
			if (dcode.value == "") {
				alert("�ڵ尪�� �Է��� �ֽʽÿ�."); //�ڵ尪�� �Է��� �ֽʽÿ�.
				dcode.focus();
				return;
			}
			submit();
		}
	}

	function send_close() {
		
		if (document.frm.dcode.value == "") {
			bChgOK = confirm("�ڵ尪�� �������� �����̽��ϴ�. â�� �����ðڽ��ϱ�?"); //�ڵ尪�� �������� �����̽��ϴ�. â�� �����ðڽ��ϱ�?
			if (bChgOK == true) {
				self.close();	
			} else {
				return;
			}

		}
		
		opener.document.frm.dcode.value = document.frm.dcode.value;
		self.close();	

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
					<td height="30" style="padding-left:20px"><a href="#">�ý��� ����<!--�ý��� ����--></a> > <a href="#">�ڵ����<!--�ڵ����--></a>  > <a href="#">�ڵ尪 �ߺ� üũ<!--�ڵ尪 �ߺ� üũ--></a> </td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
<form name="frm" action="pop_dcode_dup.asp" method="post">
<input type="hidden" name="SearchFlag" value="Y">
<input type="hidden" name="pcode" value="<%=sPcode%>">
				<tr>
					<td height="20"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
						<table cellpadding="2" cellspacing="1" border="0" width="90%"> 
							<tr>
								<td>* ����Ͻð� ���� �ڵ尪�� �Է��� �ֽʽÿ�.<!--����Ͻð� ���� �ڵ尪�� �Է��� �ֽʽÿ�.--><br>
								</td>
							</tr>
						</table>
						<table cellpadding="2" cellspacing="1" border="0" width="90%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="5"></td>
							</tr>
							<tr>
								<td width="25%" bgcolor="#E9E9E9" align="center" height="25">�ڵ�<!--�ڵ�--></td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="dcode" size="20" value="<%=sDcode%>" maxlength="6"> 
								</td>
								<td>
									<a href="javascript:Sendit();"><img src="<%=g_ImagePath%>btn_search.gif" border="0"></a>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="5"></td>
							</tr>
						</table>
<%
	If (sSearchFlag = "Y") Then
		If (sExist = 0) Then
%>						
						<br>
						<table cellpadding="2" cellspacing="1" border="0" width="90%"> 
							<tr>
								<td>
									* <b><%=sDcode%></b> �� ��� �Ͻ� �� �ִ� ���Դϴ�.<!--�� ��� �Ͻ� �� �ִ� ���Դϴ�.--><br>
								</td>
							</tr>
						</table>
<%
		Else 
%>
						<br>
						<table cellpadding="2" cellspacing="1" border="0" width="90%"> 
							<tr>
								<td>
									* <b><%=sDcode%></b> �� �̹� ��� �ϰ� ��ô� ���Դϴ�. <!--�� �̹� ��� �ϰ� ��ô� ���Դϴ�.--><br>
									<script language="javascript">document.frm.dcode.value="";</script>
								</td>
							</tr>
						</table>
<%
		End If
	End If
%>
						<br>
						<table width="90%" cellpadding="0" cellspacing="1" border="0">
							<tr>
								<td align="center">
									<a href="javascript:send_close();"><img src="<%=g_ImagePath%>btn_close.gif" border="0"></a>
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

