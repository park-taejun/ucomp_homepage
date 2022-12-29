<?session_start();?>
<?
# =============================================================================
# File Name    : board_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2011.06.01
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Request Parameter
#==============================================================================

	$bb_code = trim($bb_code);

	if ($bb_code == "")
		$bb_code = "CU002";

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = $bb_code; // 메뉴마다 셋팅 해 주어야 합니다

	//echo $menu_right;

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
	require "../../_classes/biz/customer/customer.php";
	require "../../_classes/biz/admin/admin.php";

	$config_no = "";

	$arr_bb_code = explode("_", $bb_code);
	
	for ($i = 0; $i < sizeof($arr_bb_code) ; $i++) {
		$config_no = $arr_bb_code[$i];
	}


	if ($mode == "D") {

		$row_cnt = count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
			$tmp_bb_no = $chk[$k];
			$result= deleteCustomer($conn, $s_adm_no, $bb_code, $tmp_bb_no);
		}
	}

#====================================================================
# Request Parameter
#====================================================================
	if ($nPage == 0) $nPage = "1";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$con_cate_01 = trim($con_cate_01);
	$con_cate_02 = trim($con_cate_02);
	$con_cate_03 = trim($con_cate_03);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$del_tf = "N";
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
		$nPageSize = 25;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntCustomer($conn, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $category, $con_use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listCustomer($conn, $bb_code, $con_cate_01, $con_cate_02, $con_cate_03, $con_cate_04, $category, $con_use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

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

    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="../bootstrap/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <link href="../bootstrap/css/animate.css" rel="stylesheet">
    <link href="../bootstrap/css/style.css" rel="stylesheet">

<script language="javascript">

	function js_write() {
		var frm = document.frm;
		frm.target = "";
		frm.action = "customer_write.php";
		frm.submit();
	}

	function js_modify(){

		bDelOK = confirm('노출순서를 수정 하시겠습니까?');
		
		if (bDelOK==true) {
			frm.mode.value = "U";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_delete() {
		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");
	
		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
	
		if (chk_cnt == 0) {
			alert("선택 하신 자료가 없습니다.");
		} else {

			bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');
		
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
			}
		}
	}

	function js_view(rn, bb_code, bb_no) {

		var frm = document.frm;
		
		frm.bb_code.value = bb_code;
		frm.bb_no.value = bb_no;
		frm.mode.value = "S";
		frm.target = "";
		frm.method = "post";
		frm.action = "customer_write.php";
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


	function js_search2() {
		var frm = document.frm_search;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}

function js_toggle(bb_code, bb_no, use_tf) {
	var frm = document.frm;

	bDelOK = confirm('공개 여부를 변경 하시겠습니까?');
		
	if (bDelOK==true) {

		if (use_tf == "Y") {
			use_tf = "N";
		} else {
			use_tf = "Y";
		}

		frm.bb_code.value = bb_code;
		frm.bb_no.value = bb_no;
		frm.use_tf.value = use_tf;
		frm.mode.value = "T";
		frm.target = "";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}
}

function js_con_cate_01 () {
	frm.nPage.value = "1";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}

function js_con_cate_02 () {
	frm.nPage.value = "1";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}

function js_con_cate_03 () {
	frm.nPage.value = "1";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
}

function js_view2(n,tot){
	
	if(document.getElementById("sub_info"+n).style.display==""){
		document.getElementById("sub_info"+n).style.display="none";
	}else{
		for(i=0;i<tot;i++){
		document.getElementById("sub_info"+i).style.display="none";
		}
		document.getElementById("sub_info"+n).style.display="";
	}

	
}

function js_category(){
		frm.nPage.value = "1";
	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();
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
<input type="hidden" name="bb_no" value="">
<input type="hidden" name="bb_code" value="<?=$bb_code?>">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
<input type="hidden" name="con_cate_01" value="<?=$con_cate_01?>">

			<!--select name="" style="width:100px;position:absolute;right:30px;top:145px;">
				<option value=""></option>
				<option value=""></option>
			</select-->
<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-4">
                    <div class="ibox">
                        <div class="ibox-title">
                            <span class="label label-primary pull-right">NEW</span>
                            <h5>IT-01 - Design Team</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="team-members">
                                <a href="#"><img alt="member" class="img-circle" src="img/a1.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a2.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a3.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a5.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a6.jpg"></a>
                            </div>
                            <h4>Info about Design Team</h4>
                            <p>
                                It is a long established fact that a reader will be distracted by the readable content
                                of a page when looking at its layout. The point of using Lorem Ipsum is that it has.
                            </p>
                            <div>
                                <span>Status of current project:</span>
                                <div class="stat-percent">48%</div>
                                <div class="progress progress-mini">
                                    <div style="width: 48%;" class="progress-bar"></div>
                                </div>
                            </div>
                            <div class="row  m-t-sm">
                                <div class="col-sm-4">
                                    <div class="font-bold">PROJECTS</div>
                                    12
                                </div>
                                <div class="col-sm-4">
                                    <div class="font-bold">RANKING</div>
                                    4th
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="font-bold">BUDGET</div>
                                    $200,913 <i class="fa fa-level-up text-navy"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>IT-04 - Marketing Team</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="team-members">
                                <a href="#"><img alt="member" class="img-circle" src="img/a4.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a5.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a6.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a8.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a7.jpg"></a>
                            </div>
                            <h4>Info about Design Team</h4>
                            <p>
                                It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker.
                            </p>
                            <div>
                                <span>Status of current project:</span>
                                <div class="stat-percent">32%</div>
                                <div class="progress progress-mini">
                                    <div style="width: 32%;" class="progress-bar"></div>
                                </div>
                            </div>
                            <div class="row  m-t-sm">
                                <div class="col-sm-4">
                                    <div class="font-bold">PROJECTS</div>
                                    24
                                </div>
                                <div class="col-sm-4">
                                    <div class="font-bold">RANKING</div>
                                    3th
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="font-bold">BUDGET</div>
                                    $190,325 <i class="fa fa-level-up text-navy"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>IT-07 - Finance Team</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="team-members">
                                <a href="#"><img alt="member" class="img-circle" src="img/a4.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a8.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a7.jpg"></a>
                            </div>
                            <h4>Info about Design Team</h4>
                            <p>
                                Uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                            </p>
                            <div>
                                <span>Status of current project:</span>
                                <div class="stat-percent">73%</div>
                                <div class="progress progress-mini">
                                    <div style="width: 73%;" class="progress-bar"></div>
                                </div>
                            </div>
                            <div class="row  m-t-sm">
                                <div class="col-sm-4">
                                    <div class="font-bold">PROJECTS</div>
                                    11
                                </div>
                                <div class="col-sm-4">
                                    <div class="font-bold">RANKING</div>
                                    6th
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="font-bold">BUDGET</div>
                                    $560,105 <i class="fa fa-level-up text-navy"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>IT-02 - Developers Team</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="team-members">
                                <a href="#"><img alt="member" class="img-circle" src="img/a8.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a4.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a1.jpg"></a>
                            </div>
                            <h4>Info about Design Team</h4>
                            <p>
                                Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
                            </p>
                            <div>
                                <span>Status of current project:</span>
                                <div class="stat-percent">61%</div>
                                <div class="progress progress-mini">
                                    <div style="width: 61%;" class="progress-bar"></div>
                                </div>
                            </div>
                            <div class="row  m-t-sm">
                                <div class="col-sm-4">
                                    <div class="font-bold">PROJECTS</div>
                                    43
                                </div>
                                <div class="col-sm-4">
                                    <div class="font-bold">RANKING</div>
                                    1th
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="font-bold">BUDGET</div>
                                    $705,913 <i class="fa fa-level-up text-navy"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="ibox">
                        <div class="ibox-title">
                            <span class="label label-warning pull-right">DEADLINE</span>
                            <h5>IT-05 - Administration Team</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="team-members">
                                <a href="#"><img alt="member" class="img-circle" src="img/a1.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a2.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a6.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a3.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a4.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a7.jpg"></a>
                            </div>
                            <h4>Info about Design Team</h4>
                            <p>
                                Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin.
                            </p>
                            <div>
                                <span>Status of current project:</span>
                                <div class="stat-percent">14%</div>
                                <div class="progress progress-mini">
                                    <div style="width: 14%;" class="progress-bar"></div>
                                </div>
                            </div>
                            <div class="row  m-t-sm">
                                <div class="col-sm-4">
                                    <div class="font-bold">PROJECTS</div>
                                    8
                                </div>
                                <div class="col-sm-4">
                                    <div class="font-bold">RANKING</div>
                                    7th
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="font-bold">BUDGET</div>
                                    $40,200 <i class="fa fa-level-up text-navy"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>IT-08 - Lorem ipsum Team</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="team-members">
                                <a href="#"><img alt="member" class="img-circle" src="img/a1.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a8.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a3.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a7.jpg"></a>
                            </div>
                            <h4>Info about Design Team</h4>
                            <p>
                                Many desktop publishing packages and web page editors now use Lorem Ipsum as their. ometimes by accident, sometimes on purpose (injected humour and the like).
                            </p>
                            <div>
                                <span>Status of current project:</span>
                                <div class="stat-percent">25%</div>
                                <div class="progress progress-mini">
                                    <div style="width: 25%;" class="progress-bar"></div>
                                </div>
                            </div>
                            <div class="row  m-t-sm">
                                <div class="col-sm-4">
                                    <div class="font-bold">PROJECTS</div>
                                    25
                                </div>
                                <div class="col-sm-4">
                                    <div class="font-bold">RANKING</div>
                                    4th
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="font-bold">BUDGET</div>
                                    $140,105 <i class="fa fa-level-up text-navy"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox">
                        <div class="ibox-title">

                            <h5>IT-02 - Graphic Team</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="team-members">
                                <a href="#"><img alt="member" class="img-circle" src="img/a3.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a4.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a7.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a2.jpg"></a>
                            </div>
                            <h4>Info about Design Team</h4>
                            <p>
                                Very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                            </p>
                            <div>
                                <span>Status of current project:</span>
                                <div class="stat-percent">82%</div>
                                <div class="progress progress-mini">
                                    <div style="width: 82%;" class="progress-bar"></div>
                                </div>
                            </div>
                            <div class="row  m-t-sm">
                                <div class="col-sm-4">
                                    <div class="font-bold">PROJECTS</div>
                                    68
                                </div>
                                <div class="col-sm-4">
                                    <div class="font-bold">RANKING</div>
                                    2th
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="font-bold">BUDGET</div>
                                    $701,400 <i class="fa fa-level-up text-navy"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>IT-06 - Standard  Team</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="team-members">
                                <a href="#"><img alt="member" class="img-circle" src="img/a1.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a2.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a4.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a7.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a8.jpg"></a>
                            </div>
                            <h4>Info about Design Team</h4>
                            <p>
                                Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                            </p>
                            <div>
                                <span>Status of current project:</span>
                                <div class="stat-percent">26%</div>
                                <div class="progress progress-mini">
                                    <div style="width: 26%;" class="progress-bar"></div>
                                </div>
                            </div>
                            <div class="row  m-t-sm">
                                <div class="col-sm-4">
                                    <div class="font-bold">PROJECTS</div>
                                    16
                                </div>
                                <div class="col-sm-4">
                                    <div class="font-bold">RANKING</div>
                                    8th
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="font-bold">BUDGET</div>
                                    $160,100 <i class="fa fa-level-up text-navy"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>IT-09 - Modern Team</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="team-members">
                                <a href="#"><img alt="member" class="img-circle" src="img/a2.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a3.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a8.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a6.jpg"></a>
                                <a href="#"><img alt="member" class="img-circle" src="img/a7.jpg"></a>
                            </div>
                            <h4>Info about Design Team</h4>
                            <p>
                                Words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in.
                            </p>
                            <div>
                                <span>Status of current project:</span>
                                <div class="stat-percent">18%</div>
                                <div class="progress progress-mini">
                                    <div style="width: 18%;" class="progress-bar"></div>
                                </div>
                            </div>
                            <div class="row  m-t-sm">
                                <div class="col-sm-4">
                                    <div class="font-bold">PROJECTS</div>
                                    53
                                </div>
                                <div class="col-sm-4">
                                    <div class="font-bold">RANKING</div>
                                    9th
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="font-bold">BUDGET</div>
                                    $60,140 <i class="fa fa-level-up text-navy"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>
		</form>

			<!--페이지 표시 영역-->
			<div class="btnArea">
				<ul class="fRight">
					<?	if ($sPageRight_I == "Y") { ?>
					<li><a href="javascript:js_write();"><img src="../images/btn/btn_upload.gif" alt="등록" /></a></li>
					<?}?>

					<?	if ($sPageRight_D == "Y") { ?>
						<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<?  } ?>
				</ul>
			</div>
			<div id="bbspgno">
				<!-- --------------------- 페이지 처리 화면 START -------------------------->
				<?
				
					# ==========================================================================
					#  페이징 처리
					# ==========================================================================
					if (sizeof($arr_rs) > 0) {
						#$search_field		= trim($search_field);
						#$search_str			= trim($search_str);
						$strParam = $strParam."&nPageSize=".$nPageSize."&bb_code=".$bb_code."&search_field=".$search_field."&search_str=".$search_str."&con_cate_01=".$con_cate_01."&category=".$category;
						
				//		echo $nPage."<BR>";
				//		echo $nTotalPage."<BR>";
					//	echo $nPageBlock."<BR>";
				//		echo $strParam."<BR>";
				?>
				<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
				<?
					}
				?>
				<!-- --------------------- 페이지 처리 화면 END -------------------------->
			</div>

			<form id="searchBar" name="frm_search" action="javascript:js_search2();" method="post">
			<input type="hidden" name="rn" value="">
			<input type="hidden" name="bb_no" value="">
			<input type="hidden" name="bb_code" value="<?=$bb_code?>">
			<input type="hidden" name="use_tf" value="">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="nPage" value="<?=$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

				<fieldset>
					<legend>검색창</legend>
					<? if ($b_board_cate) { ?>
					<?=makeBoardSelectBox("con_cate_01", "전체", "", $b_board_cate, "style='width:100px'", $con_cate_01); ?>
					<? } ?>
					<select name="search_field" style="width:84px;">
						<option value="COMPANY_NM" <? if ($search_field == "COMPANY_NM") echo "selected"; ?> >업체명</option>
						<option value="HOMEPAGE" <? if ($search_field == "HOMEPAGE") echo "selected"; ?> >사이트</option>
					</select>
					<input type="text" value="<?=$search_str?>" name="search_str" class="txt" />
					<a href="javascript:js_search2();"><img src="../images/btn/btn_search.gif" class="sch" alt="Search" /></a>
				</fieldset>
			</form>
		</section>
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