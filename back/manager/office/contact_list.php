<?session_start();?>
<?

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "PE004"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/office/office.php";

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$con_type		= trim($con_type);
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$con_del_tf = "N";
	
	if ($order_field == "")
		$order_field = "DIS_SEQ";
	
	if ($order_str=="")
		$order_str = "DESC";

#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 50;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntOffice($conn, $con_type, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listOffice($conn, $con_type, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />

<!--[if IE]>
<script>
document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("section");
document.createElement("figure");
document.createElement("figcaption");
document.createElement("legend");
document.createElement("time");
</script>
<![endif]-->
<!--[if IE]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/goods_common.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.min.js"></script>


<script language="javascript">

	function js_write() {
		var frm = document.frm;
		frm.target = "";
		frm.action = "contact_write.php";
		frm.submit();
	}

	function js_view(rn, seq_no) {

		var frm = document.frm;
		
		frm.seq_no.value = seq_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "get";
		frm.action = "contact_write.php";
		frm.submit();
		
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}


	function js_excel() {
		
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.target = "";
		frm.action = "<?=str_replace("list","excel_list",$_SERVER[PHP_SELF])?>";
		frm.submit();

	}

	var preid = -1;

	function js_up(n) {
	
		preid = parseInt(n);

		office_no = document.frm.seq_office_no[preid-1].value;

		if (preid > 1) {
		
			temp1 = document.getElementById("t").rows[preid].innerHTML;
			temp2 = document.getElementById("t").rows[preid-1].innerHTML;

			var cells1 = document.getElementById("t").rows[preid].cells;
			var cells2 = document.getElementById("t").rows[preid-1].cells;

			for(var j=0 ; j < cells1.length; j++) {
			
				if (j != 0) {
					var temp = cells2[j].innerHTML;

					cells2[j].innerHTML =cells1[j].innerHTML;
					cells1[j].innerHTML = temp;

				}
			}
		
			preid = preid - 1;
			js_change_order('up',office_no);

		} else {

			js_change_order('up',office_no);
			frm.method = "get";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}


	function js_down(n) {

		preid = parseInt(n);

		office_no = document.frm.seq_office_no[preid-1].value;

		if (preid < document.getElementById("t").rows.length-1) {
		
			temp1 = document.getElementById("t").rows[preid].innerHTML;
			temp2 = document.getElementById("t").rows[preid+1].innerHTML;
		
			var cells1 = document.getElementById("t").rows[preid].cells;
			var cells2 = document.getElementById("t").rows[preid+1].cells;
		
			for(var j=0 ; j < cells1.length; j++) {

				if (j != 0) {
					var temp = cells2[j].innerHTML;

					cells2[j].innerHTML =cells1[j].innerHTML;
					cells1[j].innerHTML = temp;
	
				}
			}
		
			preid = preid + 1;
			js_change_order('down',office_no);
		} else{
			js_change_order('down',office_no);
			frm.method = "get";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_change_order(type,office_no) {
		
		var con_type = document.frm.con_type.value;

		$.get("/home2014/manager/office/office_order_ajax.php", 
			{ mode:"O", con_type:con_type, office_no:office_no, order_type:type }, 
			function(data){
				//alert(data);
			}
		);
	}

</script>
</head>

<body>

<div class="wrapper">
<section id="container">	

<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>

	<section class="conRight">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../../_common/top_area.php";
?>
		<div class="conTit">
			<h2><?=$p_parent_menu_name?></h2>
		</div>
		
		<section class="conBox">

<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					<table summary="이곳에서 <?=$p_menu_name?> 페이지를 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?> 관리</caption>
						<tbody>
							<tr class="last">
								<th>검색조건</th>
								<td>
									<!--
							<select name="search_field" style="width:84px;">
								<option value="ALL" <? if ($search_field == "ALL") echo "selected"; ?> >통합검색</option>
								<option value="NAME" <? if ($search_field == "NAME") echo "selected"; ?> >이름</option>
								<option value="ADDRES" <? if ($search_field == "ADDRESS") echo "selected"; ?> >주소</option>
							</select>&nbsp;

									<input type="text" value="<?=$search_str?>" name="search_str" size="15"class="txt" />
									-->
									<?=makeSelectBox($conn,"BOARD_GROUP","con_type","128","지역을 선택하세요.","", $con_type)?>&nbsp;

									<a href="javascript:js_search();"><img src="../images/btn/btn_search.gif" alt="검색" /></a>
								</td>
							</tr>
						</tbody>
					</table>

					<div class="expArea">
						<ul class="fLeft">
							<li class="total">총 <?=$nListCnt?>건</li>
							<!--<li class="file">파일 저장하기&nbsp;<a href="#"><img src="../images/bu/ic_file.gif" alt="엑셀파일 아이콘" /></a></li>-->
						</ul>

						<p class="fRight">
							<? if ($sPageRight_I == "Y") {?>
							<a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a>
							<? } ?>
						</p>
					</div>

					<table summary="이곳에서 <?=$p_menu_name?>를 관리하실 수 있습니다" class="secBtm" id='t'>
					
					<colgroup>
						<col width="7%" />
						<col width="11%" />
						<col width="17%" />
						<col width="10%" />
						<col width="35%"/>
						<col width="10%" />
						<col width="10%" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>지역</th>
							<th>지부명</th>
							<th>연락처</th>
							<th>주소</th>
							<th>등록일</th>
							<th class="end">사용여부</th>
						</tr>
					</thead>
					<tbody>
				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							
							$rn							= trim($arr_rs[$j]["rn"]);
							$SEQ_NO					= trim($arr_rs[$j]["SEQ_NO"]);
							$TYPE						= trim($arr_rs[$j]["TYPE"]);
							$NAME						= trim($arr_rs[$j]["NAME"]);
							$TEL01					= trim($arr_rs[$j]["TEL01"]);
							$TEL02					= trim($arr_rs[$j]["TEL02"]);
							$FAX01					= trim($arr_rs[$j]["FAX01"]);
							$FAX02					= trim($arr_rs[$j]["FAX02"]);
							$EMAIL					= trim($arr_rs[$j]["EMAIL"]);
							$POST						= trim($arr_rs[$j]["POST"]);
							$ADDRESS				= trim($arr_rs[$j]["ADDRESS"]);
							$STR_LAT				= trim($arr_rs[$j]["STR_LAT"]);
							$STR_LNG				= trim($arr_rs[$j]["STR_LNG"]);
							$EX_INFO01			= trim($arr_rs[$j]["EX_INFO01"]);
							$EX_INFO02			= trim($arr_rs[$j]["EX_INFO02"]);
							$EX_INFO03			= trim($arr_rs[$j]["EX_INFO03"]);
							$DIS_SEQ				= trim($arr_rs[$j]["DIS_SEQ"]);
							$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
							$REG_DATE				= date("Y-m-d",strtotime($REG_DATE));

							if($USE_TF=="Y"){
								$use_state="사용";
							}else{
								$use_state="미사용";
							}

				?>
					<tr>
						<td class="sort">
							<span><?=$rn?></span>
							<a href="javascript:js_up('<?=($j+1)?>');"><img src="../images/admin/icon_arr_top.gif" alt="" /></a> 
							<a href="javascript:js_down('<?=($j+1)?>');"><img src="../images/admin/icon_arr_bot.gif" alt="" /></a>
						</td>
						<td class="modeual_nm">
							<?=getDcodeName($conn,"BOARD_GROUP",$TYPE);?>&nbsp;
						</td>
						<td class="modeual_nm">
							<a href="javascript:js_view('<?=$rn?>','<?=$SEQ_NO?>');"><?=$NAME?></a>
							<input type="hidden" name="seq_office_no" value="<?=$SEQ_NO?>">
						</td>
						<td>
							<?=$TEL01?>
							<?
								if ($TEL02 <> "") echo ", ".$TEL02;
							?>
						</td>
						<td style="text-align:left;padding-left:10px"><? if ($POST) echo "(".$POST.")"?> <?=$ADDRESS?></td>
						<td><?=$REG_DATE?></td>
						<td class="filedown"><?=$use_state?></td>
					</tr>
				<?			
						}
					} else { 
				?>
					<tr>
						<td align="center" height="50" colspan="7">데이터가 없습니다. </td>
					</tr>
				<? 
					}
				?>
						</tbody>
					</table>
				</fieldset>
			</form>
			<br/><br/>

			<div id="bbspgno">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							
							$str_list_param = "";
							$str_list_param = $str_list_param."&nPageSize=".$nPageSize;
							$str_list_param = $str_list_param."&gd_cate_01=".$gd_cate_01."&gd_cate_02=".$gd_cate_02."&gd_cate_03=".$gd_cate_03;
							$str_list_param = $str_list_param."&start_date=".$start_date."&end_date=".$end_date;
							$str_list_param = $str_list_param."&order_field=".$order_field."&order_str=".$order_str."&search_field=".$search_field."&search_str=".$search_str;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$str_list_param) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
			</div>
		</section>
		<iframe src="about:blank" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>