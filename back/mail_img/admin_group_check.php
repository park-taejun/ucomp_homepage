<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: menu_view.php
	// 	Description : 메뉴 보기 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";

	$group_id = trim($group_id);


	//========================================================
	// 그룹 아이디로 메뉴 아이템 구하기
	//========================================================
	
	$query = "select group_item, group_name from tb_admin_group where group_id = '$group_id' ";

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$group_item = $list[group_item];
	$group_name = $list[group_name];
	
	//========================================================
	// 메뉴 아이템이 있으면 권한 있는 메뉴 가져오기
	//========================================================
	
	if (trim($group_item) <> "" ) {
	
		$query = "select menu_id from tb_admin_menu where menu_id in ($group_item) order by big_menu";
		$result = mysql_query($query);

		while($row = mysql_fetch_array($result)) {

			$menu_id1[] = $row[menu_id];
		
		}		
	}
			
	$query = "select m.menu_id, b.big_menu_name, m.small_menu from tb_admin_menu m, tb_big_menu b where b.big_menu = m.big_menu order by b.big_menu";
	$result = mysql_query($query);
	
   	//========================================================
   	// 메뉴 배열
   	//========================================================
	
	while($row = mysql_fetch_array($result)) {

		$menu_id2[] = $row[menu_id];
		$big_menu_name[] = $row[big_menu_name];
		$small_menu[] = $row[small_menu];
				
	}		
	
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
<script language=javascript>
	function LeftToRight()
	{
	   var objSel, objSel

		objLeft = document.form1.sel_left
		objRight = document.form1.sel_right
		
		for(var i = 0; i < objLeft.length; i++)
		  if(objLeft.options[i].selected)
			  objRight.options[objRight.length] = new Option(objLeft.options[i].text,  objLeft.options[i].value);
	   
		for(var i = 0; i < objRight.length; i++)
      {
			for(var j = 0; j < objLeft.length; j++)
			{
			   if(objLeft.options[j].selected)
				{
				   objLeft.options[j] = null;
				   break;
				}	
			}
		} 
	}
	
	function RightToLeft()
	{
	   var objSel, objSel
		objLeft = document.form1.sel_left
		objRight = document.form1.sel_right
		
		for(var i = 0; i < objRight.length; i++)
		  if(objRight.options[i].selected)
			  objLeft.options[objLeft.length] = new Option(objRight.options[i].text,  objRight.options[i].value);

		for(var i = 0; i < objLeft.length; i++)
      {
			for(var j = 0; j < objRight.length; j++)
			{
			   if(objRight.options[j].selected)
				{
					objRight.options[j] = null;
				   break;
				}	
			}
		} 
	}
	
	function goBack() {
		document.form1.target = "frmain";
		document.form1.action="admin_group_list.php";
		document.form1.submit();
	}

	function goIn() {
	
		var menu_items = ""; 
		
	    for(var i = 0; i < document.form1.sel_right.length; i++) {
		   	document.form1.sel_right.options[i].selected = true; 
			if (menu_items == "") {
				menu_items = document.form1.sel_right.options[i].value;	
			} else {
				menu_items = menu_items+","+document.form1.sel_right.options[i].value;	
			}
		}	
		
		document.form1.menu_item.value = menu_items;					
		document.form1.target = "frhidden";
		document.form1.action = "menu_group_db.php";
		document.form1.submit();
		
	}

</SCRIPT>
</HEAD>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
				<tr>
					<td height="30" style="padding-left:20px"><a href="#">관리자권한 관리</a> > <a href="#">메뉴 권한</a> > <a href="#">설정</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td>
									* 관리자 메뉴를 설정하는 화면 입니다.<br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4"></td>
							</tr>
							<tr>
								<td>
									<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' '#FFFFFF' bordercolor='#FFFFFF' bgcolor="#EEEEEE">
										<tr>
											<td align='center'>
												<TABLE border="0" cellspacing="1" cellpadding="2" width='80%'>
													<tr>
														<td align=center>
														<form name=form1 method=post>
															<table  border=0 cellpadding=0 cellspacing=0 width="90%">
																<tr>
		   															<td colspan=3  align=center><br>관리자 그룹 이름 : <font color="red"><b><?echo $group_name?></b></font><br><br>
																	</td>
																</tr>
																<tr>
		   															<td width=40% align=center><font color=>권한없음</font></td>
		   															<td width=20% align=center>&nbsp;</td>
																	<td width=40% align=center><font color=>권한있음</font></td>
																</tr>
																<tr>
         															<td width=45%  align=right>		      
																		<select name="sel_left" size="17" multiple style="width:270px;border:1 solid #cccccc;back-color:white;">
																	<?
	
	
   	//========================================================
   	// 초기 설정이 아닌 경우와 초기 설정인 경우 다름
   	//========================================================
	
																		if (trim($group_item) <> "") {
																			$isExist = 0;
	
																			for ($i = 0; $i < sizeof($menu_id2); $i++) {
																				for ($j = 0; $j < sizeof($menu_id1); $j++) {
																					if (($menu_id2[$i]) == ($menu_id1[$j])) {
				 																		$isExist = $isExist+1;
																					}
																				}
																				if ($isExist == 0) {
																	?>
																			<option value="<?echo $menu_id2[$i]?>"><?echo $big_menu_name[$i]?> : <?echo $small_menu[$i]?></option>
																	<?
																				}
																				$isExist = 0;
																			}

																		} else {
																			for ($i = 0; $i < sizeof($menu_id2); $i++) {
																	?>
																			<option value="<?echo $menu_id2[$i]?>"><?echo $big_menu_name[$i]?> : <?echo $small_menu[$i]?></option>
																	<?
																			}

																		}
																	?>
																		</select>
																	</td>
																	<td width=10%  align=center>
			 															<input type=button name=LR value=' -&gt; ' onClick="javascript:LeftToRight()"><p>
         	 															<input type=button name=RL value=' &lt;- '  onClick="javascript:RightToLeft()">
																	</td>
         															<td width=45% >
		   																<select name="sel_right" size="17" multiple style="width:270px;border:1 solid #cccccc;back-color:white;">
																	<?
   	//========================================================
   	// 초기 설정이 아닌 경우와 초기 설정인 경우 다름
   	//========================================================
	
																		if (trim($group_item) <> "") {
																			for ($i = 0; $i < sizeof($menu_id2); $i++) {
																				for ($j = 0; $j < sizeof($menu_id1); $j++) {
																					if (($menu_id2[$i]) == ($menu_id1[$j])) {
																	?>
																			<option value="<?echo $menu_id2[$i]?>"><?echo $big_menu_name[$i]?> : <?echo $small_menu[$i]?></option>
																	<?
																					}
																				}
																			}
																		}
																	?>
																		</select>  
																	</td>
																</tr>
															</table> <br><br>
														</td>	   
													</tr>
													<input type="hidden" name="group_id" value="<?echo $group_id?>">
													<input type="hidden" name="menu_item" value="">
													</form>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4"></td>
							</tr>
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td align="center">
									<a href="javascript:goIn();"><img src="images/button_save.gif" border="0"></a>&nbsp;
									<a href="javascript:goBack();"><img src="images/button_list_01.gif" border="0"></a>&nbsp;
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
</body>
</html>
<?
	mysql_close($connect);
?>