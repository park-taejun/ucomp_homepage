<?session_start();?>
<? 
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "2";

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/ImgUtil.php";
	require "../_classes/com/util/ImgUtilResize.php";
	require "../_classes/com/etc/etc.php";	
	require "../_classes/biz/team/team.php";	

	$team_no					= $_POST['team_no']!=''?$_POST['team_no']:$_GET['team_no'];


	$arr_rs = selectTeam($conn, $team_no);

	$arr_rs_team 		= listTeamAll($conn); 

	$P_TEAM_NO_TOP		= trim($arr_rs[0]["TEAM_NO"]);
	$P_TEAM_NM_TOP		= trim($arr_rs[0]["TEAM_NM"]);
	$P_TEAM_IMG_TOP		= trim($arr_rs[0]["TEAM_IMG"]);
	$P_TEAM_CONTENTS_TOP		= trim($arr_rs[0]["TEAM_CONTENTS"]);	 

?>

<div class="section-wrap" id="id_team_img" style="--background-image: url(../../upload_data/team/<?=$P_TEAM_IMG_TOP?>);">

	<div class="section-head">
		<h3 class="section-subject"><span class="section-name">Our Team</span></h3>
		<p class="section-summary">유컴패니온의 <strong class="em normal-01">전문가들</strong></p>
	</div>
	<div class="section-body">
		<!-- dropdown -->
		<div class="dropdown module-a style-a">
			<div class="dropdown-wrap">
				<div class="dropdown-head">
					
					<p id="dropdown-subject" class="dropdown-subject" tabindex="0" data-bui-tab-selected="<?=$P_TEAM_NM_TOP?>" onclick="js_team();"><span class="dropdown-name"><?=$P_TEAM_NM_TOP?></span></p>
					
				</div>
				<div class="dropdown-body" style="display:none">
					<ul class="navi-list" id="navi_list">

						<? 										
							if (sizeof($arr_rs_team) > 0) {
								for ($j = 0 ; $j < sizeof($arr_rs_team); $j++) { 
									$P_TEAM_NO				= trim($arr_rs_team[$j]["TEAM_NO"]);
									$P_TEAM_NM				= trim($arr_rs_team[$j]["TEAM_NM"]);
									$P_TEAM_IMG				= trim($arr_rs_team[$j]["TEAM_IMG"]);
									$P_TEAM_CONTENTS		= trim($arr_rs_team[$j]["TEAM_CONTENTS"]);
									$P_TEAM_CONTENTS_IMG = $P_TEAM_IMG."|".$P_TEAM_CONTENTS;
						?>
						<li class="navi-item" value="<?=$P_TEAM_NO?>"><a class="navi-text"  href="javascript:void(0)" onClick="js_select_team('<?=$P_TEAM_NO?>');"><?=$P_TEAM_NM?></a></li>
						<?
								}
							}
						?>
					</ul>
				</div>
			</div>
		</div>
		<!-- //dropdown -->
		<p class="para"><?=$P_TEAM_CONTENTS_TOP?></p>
	</div>
</div>

<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>