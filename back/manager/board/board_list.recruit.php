		<colgroup>
			<col width="5%" />
			<col width="5%" />
			<col width="10%" />
			<col width="30%" />
			<col width="10%" />
			<col width="20%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
		</colgroup>
		<thead>
		<tr>
			<th scope="col">&nbsp;</th>
			<th scope="col">No.</th>
			<th scope="col">법인</th>
			<th scope="col">모집분야</th>
			<th scope="col">대상</th>
			<th scope="col">모집기간</th>
			<th scope="col">노출여부</th>
			<th scope="col">조회수</th>
			<th scope="col">상태</th>
		</tr>
		</thead>
		<tbody>
	<?
		$nCnt = 0;

		$this_day = date("Y-m-d",strtotime("0 month"));
		
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				
				$rn							= trim($arr_rs[$j]["rn"]);
				$BB_NO					= trim($arr_rs[$j]["BB_NO"]);
				$BB_RE					= trim($arr_rs[$j]["BB_RE"]);
				$BB_DE					= trim($arr_rs[$j]["BB_DE"]);
				$BB_PO					= trim($arr_rs[$j]["BB_PO"]);
				$BB_CODE				= trim($arr_rs[$j]["BB_CODE"]);
				$CATE_01				= trim($arr_rs[$j]["CATE_01"]);
				$CATE_02				= trim($arr_rs[$j]["CATE_02"]);
				$CATE_03				= trim($arr_rs[$j]["CATE_03"]);
				$CATE_04				= trim($arr_rs[$j]["CATE_04"]);
				$WRITER_NM			= trim($arr_rs[$j]["WRITER_NM"]);
				$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
				$REG_ADM				= trim($arr_rs[$j]["REG_ADM"]);
				$HIT_CNT				= trim($arr_rs[$j]["HIT_CNT"]);
				$REF_IP					= trim($arr_rs[$j]["REF_IP"]);
				$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
				$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);

				$REPLY_DATE			= trim($arr_rs[$j]["REPLY_DATE"]);
				$REPLY_STATE		= trim($arr_rs[$j]["REPLY_STATE"]);

				$rs_admin			= selectAdmin($conn, $REG_ADM);
				$rs_adm_name	= SetStringFromDB($rs_admin[0]["ADM_NAME"]);
				
				$REG_DATE = date("Y-m-d H:i",strtotime($REG_DATE));
	
				if ($USE_TF == "Y") {
					$STR_USE_TF = "<font color='navy'>보이기</font>";
				} else {
					$STR_USE_TF = "<font color='red'>보이지않기</font>";
				}
				
				if ($BB_DE == 1) {
					if ($REPLY_STATE == "Y") {
						$STR_REPLY_STATE = "<font color='navy'>답변완료</font>";
					} else {
						$STR_REPLY_STATE = "<font color='red'>대기중</font>";
					}
				} else {
					$STR_REPLY_STATE = "";
				}

				$R_CNT = getReplyCount($conn, $BB_CODE, $BB_NO);

				if ($R_CNT > 0) {
					$reply_cnt = "<span class=\"orange f11\">(".$R_CNT.")</span>";
				} else {
					$reply_cnt = "";
				}

				$space = "";

				for ($l = 1; $l < $BB_DE; $l++) {
					if ($l != 1)
						$space .= "&nbsp;";
					else
						$space .= "&nbsp;";
	
					if ($l == ($BB_DE - 1))
						$space .= "&nbsp;┗";
	
					$space .= "&nbsp;";
				}
				

				if ($CATE_04 >= $this_day) {
					$str = "/images/btn/btn_ing.gif";
				} else {
					$str = "/images/btn/btn_finish.gif";
				}
	?>
		<tr> 
			<td><input type="checkbox" name="chk[]" value="<?=$BB_NO?>"></td>
			<td><a href="javascript:js_view('<?=$rn?>','<?=$BB_CODE?>','<?=$BB_NO?>');"><?= $rn ?></a></td>
			<td><?= getDcodeName($conn, "COM_TYPE", $CATE_01) ?></td>
			<td class="tit">
				<a href="javascript:js_view('<?=$rn?>','<?=$BB_CODE?>','<?=$BB_NO?>');"><?=$TITLE?></a> <?=$reply_cnt?></td>
			<td><?= getDcodeName($conn, "REC_TYPE", $CATE_02) ?></td>
			<td><?= $CATE_03 ?> ~ <?= $CATE_04 ?></td>
			<td class="filedown">
				<?=$STR_USE_TF?>
			</td>
			<td class="filedown">
				<?=$HIT_CNT?>
			</td>
			<td class="filedown">
				<img src="<?=$str?>">
			</td>
		</tr>
	<?			
			}
		} else { 
	?> 
		<tr>
			<td height="50" align="center" colspan="10">데이터가 없습니다. </td>
		</tr>
	<? 
		}
	?>
		</tbody>