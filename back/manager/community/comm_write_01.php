					<tr>
						<th scope="row">Title 이미지 (81*33)</th>
						<td colspan="3">
						<?
							if (strlen($rs_comm_title_img) > 3) {
						?>
							<img src="/upload_data/community/<?= $rs_comm_title_img ?>" width="81" height="33"></a>
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

					<tr>
						<th scope="row">footer 이미지 (61*10)</th>
						<td colspan="3">
						<?
							if (strlen($rs_comm_footer_img) > 3) {
						?>
							<img src="/upload_data/community/<?= $rs_comm_footer_img ?>" width="61" height="10"></a>
							&nbsp;&nbsp;
							<select name="flag02" style="width:70px;" onchange="javascript:js_fileView(this,'02')">
								<option value="keep">유지</option>
								<option value="delete">삭제</option>
								<option value="update">수정</option>
							</select>
					
							<input type="hidden" name="old_comm_footer_img" value="<?= $rs_comm_footer_img?>">

							<div id="file_change02" style="display:none;">
								<input type="file" name="comm_footer_img" size="40%" />
							</div>

						<?
							} else {
						?>
							<input type="file" size="40%" name="comm_footer_img">
							<input type="hidden" name="old_comm_footer_img" value="">
							<input TYPE="hidden" name="flag02" value="insert">
						<?
							}	
						?>
						</td>
					</tr>