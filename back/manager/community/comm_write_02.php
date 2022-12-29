					<tr scope="row">
						<th>Title 이미지 (185*25)</th>
						<td colspan="3">
						<?
							if (strlen($rs_comm_title_img) > 3) {
						?>
							<img src="/upload_data/community/<?= $rs_comm_title_img ?>" width="185" height="25"></a>
							&nbsp;&nbsp;
							<select name="flag01" style="width:70px;" onchange="javascript:js_fileView(this,'01')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_comm_title_img" value="<?= $rs_comm_title_img?>">

							<div id="file_change01" style="display:none;">
								<input type="file" name="comm_title_img" size="40%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="comm_title_img">
							<input type="hidden" name="old_comm_title_img" value="">
							<input TYPE="hidden" name="flag01" value="insert">
						<?
							}	
						?>
						</td>
					</tr>
					<!--
					<tr>
						<th scope="row">좌측 이미지 (48*17)</th>
						<td colspan="3">
						<?
							if (strlen($rs_comm_img) > 3) {
						?>
							<img src="/upload_data/community/<?= $rs_comm_img ?>" width="48" height="17"></a>
							&nbsp;&nbsp;
							<select name="flag03" style="width:70px;" onchange="javascript:js_fileView(this,'03')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_comm_img" value="<?= $rs_comm_img?>">

							<div id="file_change03" style="display:none;">
								<input type="file" name="comm_img" size="40%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="comm_img">
							<input type="hidden" name="old_comm_img" value="">
							<input TYPE="hidden" name="flag03" value="insert">
						<?
							}	
						?>
						</td>
					</tr>

					<tr>
						<th scope="row">좌측 Over 이미지 (48*17)</th>
						<td colspan="3">
						<?
							if (strlen($rs_comm_img_over) > 3) {
						?>
							<img src="/upload_data/community/<?= $rs_comm_img_over ?>" width="48" height="17"></a>
							&nbsp;&nbsp;
							<select name="flag04" style="width:70px;" onchange="javascript:js_fileView(this,'04')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_comm_img_over" value="<?= $rs_comm_img_over?>">

							<div id="file_change04" style="display:none;">
								<input type="file" name="comm_img_over" size="40%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="comm_img_over">
							<input type="hidden" name="old_comm_img_over" value="">
							<input TYPE="hidden" name="flag04" value="insert">
						<?
							}	
						?>
						</td>
					</tr>
					-->