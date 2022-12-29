<div class="section module-a style-a type-a ustory" id="ustory" data-bui-animation="type-1">
	<div class="section-wrap">
		<div class="section-head">
			<h3 class="section-subject"><span class="section-name">U:Story</span></h3>
			<p class="section-summary"><span class="wbr">유컴패니온의 <span class="em">새로운 소식</span></span></p>
		</div>
		<div class="section-body">
			<!-- posts-display -->
			<div class="posts-display module-a style-a">
				<input type="text" name="cnt" value="1">
				<ul class="posts-list">
				<? 										
					if (sizeof($arr_rs_ustory) > 0) {
							for ($j = 0 ; $j < sizeof($arr_rs_ustory); $j++) { 
								$P_STORY_NO				= trim($arr_rs_ustory[$j]["STORY_NO"]);
								$P_STORY_IMG			= trim($arr_rs_ustory[$j]["STORY_IMG"]);
								$P_STORY_REAL_IMG		= trim($arr_rs_ustory[$j]["STORY_REAL_IMG"]);
								$P_STORY_NM				= trim($arr_rs_ustory[$j]["STORY_NM"]);
								$P_REG_DATE				= trim($arr_rs_ustory[$j]["REGDATE"]);
								$P_FILE_NM				= trim($arr_rs_ustory[$j]["FILE_NM"]);							
				?>			
					<li class="posts-item">
						<div class="posts-wrap">
							<div class="posts-figure">
								<a class="posts-thumbnail" href="#">
									<a class="data-name" href="USR.005_1?story_no=<?=$P_STORY_NO?>&story_gubun=v"><img class="image" src="../../upload_data/story/<?=$P_STORY_IMG?>" onclick="js_view('<?=$P_STORY_NO?>','UStory')"; alt="" /></a>
								</a>
							</div>
							<div class="posts-inform">
								<div class="posts-head">
									<p class="posts-subject"><span class="posts-name"><?=$P_STORY_NM?></span></p>
								</div>
								<div class="posts-data">
									<p class="data-list">
										<span class="data-item date">
											<span class="head">등록일</span>
											<span class="body"><?=$P_REG_DATE?></span>
										</span>
									</p>
								</div>
							</div>
						</div>
					</li>
				<?
						} 
					} 
				?>
				</ul>
				<div class="button-display module-a style-a type-c">
					<span class="button-area">
						<button class="btn module-b style-c type-fill normal-00 large-2x symbol-rtl-chevron-down" type="button" onclick="js_story();"><span class="btn-text">VIEW</span></button>
					</span>
				</div>
			</div>
			<!-- //posts-display -->
		</div>
	</div>
</div>