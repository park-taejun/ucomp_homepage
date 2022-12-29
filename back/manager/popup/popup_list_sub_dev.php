		<colgroup>
			<col width="3%" />
			<col width="5%" />
			<col width="7%" />
			<col width="28%" />
			<col width="9%" />
			<col width="19%" />
			<col width="12%" />
			<col width="12%" />
			<col width="5%" />
		</colgroup>
		<thead>
		<tr>
			<th scope="col">&nbsp;</th>
			<th scope="col">No.</th>
			<th scope="col">팝업종류</th>
			<th scope="col">제목</th>
			<th scope="col">게시일<br />사용여부</th>
			<th scope="col">게시일</th>
			<th scope="col">등록일</th>
			<th scope="col">게시여부</th>
			<th scope="col">&nbsp;</th>

		</tr>
		</thead>
		<tbody>
	<?
		$nCnt = 0;

		if (sizeof($arr_rs) > 0) {

			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

				$rn							= trim($arr_rs[$j]["rn"]);
				$POP_NO					= trim($arr_rs[$j]["POP_NO"]);
				$CATE_01					= trim($arr_rs[$j]["CATE_01"]);
				$CATE_02					= trim($arr_rs[$j]["CATE_02"]);
				$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
				$SIZE_W					= trim($arr_rs[$j]["SIZE_W"]);
				$SIZE_H					= trim($arr_rs[$j]["SIZE_H"]);
				$TOP					= trim($arr_rs[$j]["TOP"]);
				$LEFT_					= trim($arr_rs[$j]["LEFT_"]);

				$S_DATE					= trim($arr_rs[$j]["S_DATE"]);
				$S_TIME					= trim($arr_rs[$j]["S_TIME"]);
				$E_DATE					= trim($arr_rs[$j]["E_DATE"]);
				$E_TIME					= trim($arr_rs[$j]["E_TIME"]);
				$SCROLLBARS				= trim($arr_rs[$j]["SCROLLBARS"]);
				$CONTENTS				= SetStringFromDB($arr_rs[$j]["CONTENTS"]);
				$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
				$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
				$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

				preg_match_all('/<img .*?src=["|\']([^"|\']+)/is', $CONTENTS, $IMG_SRC);

				$IMGSIZE_ARR = getimagesize($IMG_SRC[1][0]);

				if(($IMGSIZE_ARR[0] != 0)&&($IMGSIZE_ARR[0] != "")){
					$SIZE_W = $IMGSIZE_ARR[0];
					$SIZE_H = $IMGSIZE_ARR[1];
					$SIZE_H = $SIZE_H+36;
				}

				if($TOP == ""){
					$TOP = 0;
				}

				if($LEFT_ == ""){
					$LEFT_ = 0;
				}

				if ($SCROLLBARS == "Y") {
					$SCROLLBARS = "yes";
				}else{
					$SCROLLBARS = "no";
				}

				if ($USE_TF == "Y") {
					$STR_USE_TF = "<font color='blue'>게시함</font>";
				} else {
					$STR_USE_TF = "<font color='red'>게시안함</font>";
				}

				if ($CATE_01 == "Y") {
					$STR_CATE_01_TF = "<font color='blue'>사용함</font>";
				} else {
					$STR_CATE_01_TF = "<font color='red'>사용안함</font>";
				}

				if ($CATE_02 == "Y") {
					$STR_CATE_02_TF = "팝업";
				} else {
					$STR_CATE_02_TF = "레이어팝업";
				}

	?>
		<tr>
			<td><input type="checkbox" name="chk[]" value="<?=$POP_NO?>"></td>
			<td><a href="javascript:js_view('<?=$rn?>','<?=$POP_NO?>');"><?= $rn ?></a></td>
			<td><?=$STR_CATE_02_TF?>
			<td class="tit"><?=$space?>
			<a href="javascript:js_view('<?=$rn?>','<?=$POP_NO?>');"><?=$TITLE?></a></td>
			<td class="filedown">
				<?=$STR_CATE_01_TF?>
			</td>
			<td><?= $S_DATE ?> <?=$S_TIME?> ~ <?= $E_DATE ?> <?=$E_TIME?></td>
			<td><?= $REG_DATE ?></td>
			<td class="filedown">
				<?=$STR_USE_TF?>
			</td>
			<td class="filedown">
				<!--<a href="javascript:view('<?=$POP_NO?>','<?=$SIZE_W?>','<?=$SIZE_H?>','<?=$TOP?>','<?=$LEFT_?>','<?=$SCROLLBARS?>');">미리보기</a>-->

			<? if(($CATE_02 == "N")||($CATE_02 == "")){ ?>
				<a href="javascript:view_div('<?=$POP_NO?>','0','0');">미리보기</a>
			<?}else{?>
				
				<a href="javascript:view_pop('<?=$POP_NO?>','<?=$SIZE_W?>','<?=$SIZE_H?>','<?=$TOP?>','<?=$LEFT_?>','<?=$SCROLLBARS?>');">미리보기</a>
			<?}?>

			</td>
		</tr>
	<?
			}//for
		} else {
	?>
		<tr>
			<td height="50" align="center" colspan="11">데이터가 없습니다. </td>
		</tr>
	<?
		}
	?>
		</tbody>