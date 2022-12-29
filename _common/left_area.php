 	<div class="header">
		<div class="innerbox">
			<? if ( $_SESSION['s_adm_group_no'] == '11' ) { ?>
				<h1><a href="/admin/request/request_list.php"><img src="../images/logo.png" alt="U: 유컴패니온" /></a></h1>
			<? } else { ?>
				<h1><a href="/admin/ir/ir_list.php"><img src="../images/logo.png" alt="U: 유컴패니온" /></a></h1>
			<? } ?>

			<!--<h1><a href="/admin/admingroup/admingroup_list.php"><img src="../images/logo.png" alt="U: 유컴패니온" /></a></h1>-->
			<p class="welcome"><strong><?=$_SESSION['s_adm_nm']?></strong>
				<!--
				<span class="pic">
					<?=getProfileImages($conn, $_SESSION['s_adm_id'])?>
				</span>
				-->
			</p>
			<ul>
			<?
				$sel_sub_menu = "";
				
				$rs_leader_yn			= $_SESSION['s_adm_leader_yn'];//trim($arr_rs[0]["LEADER_YN"]);
				$rs_level				= $_SESSION['s_adm_level'];//trim($arr_rs[0]["LEVEL"]);
 
				$arr_rs_menu = getListAdminGroupMenu($conn, $_SESSION['s_adm_group_no']);
 
				if (sizeof($arr_rs_menu) > 0) {
					for ($m = 0 ; $m < sizeof($arr_rs_menu); $m++) {
						 
						$M_MENU_CD		= trim($arr_rs_menu[$m]["MENU_CD"]);
						$M_MENU_NAME	= trim($arr_rs_menu[$m]["MENU_NAME"]);
						$M_MENU_URL		= trim($arr_rs_menu[$m]["MENU_URL"]);						 
						
						if (strlen($M_MENU_CD) == "2") {

							if ($m <> 0) {
			?>
					</ul>
				</li>
			<?
					}

					if ($M_MENU_CD == substr($sPageMenu_CD,0,2)) {
						$str_display_ = "class='on'";
						$sel_sub_menu = $m;
					} else {
						$str_display_ = "";
					}
			?>
				<li <?=$str_display_?> id="sub_<?=$m?>"><a href="javascript:js_sub_tree('<?=$m?>');"><?=$M_MENU_NAME?></a>
					<ul>
			<?
				}  

						if (strlen($M_MENU_CD) == "4") {							
							if (strpos($M_MENU_URL, "?") > 0) {
								$str_menu_url = $M_MENU_URL."&menu_cd=".$M_MENU_CD;
							} else {
								$str_menu_url = $M_MENU_URL."?menu_cd=".$M_MENU_CD;
							}

							if ($M_MENU_CD == substr($sPageMenu_CD,0,4)) {
								$str_display_ = "class='on'";
							} else {
								$str_display_ = "";
							}	  
			 
							if ( $rs_leader_yn == 'N' && $rs_level == '5' ) { 
								if ( $M_MENU_CD != '1803' ) {
			?>
									<li <?=$str_display_?>><a href="<?=$str_menu_url?>"><?=$M_MENU_NAME?></a></li>
			<?
								} 
							} else {
			?>				 
								<li <?=$str_display_?>><a href="<?=$str_menu_url?>"><?=$M_MENU_NAME?></a></li>
			<?
							}
						}
					}
				}
			?>
					</ul>
				</li>
			</ul>
		</div>
	</div>

<script>

	function js_sub_tree(cntFlag) {
		
		for (i = 0; i < <?=sizeof($arr_rs_menu)?> ; i++) {

			if (cntFlag != i ) {
				if($("#sub_"+i) != null) {
					$("#sub_"+i).removeClass("on");
					$("#sub_"+i).find("ul").slideUp();
				}
			}
		}

		$("#sub_"+cntFlag).addClass("on");
		$("#sub_"+cntFlag).find("ul").slideDown();

	}

	js_sub_tree("<?=$sel_sub_menu?>");
</script>