<table WIDTH=100%; style="BORDER-WIDTH: 0px; FONT-SIZE:12px;" >
<tr align="left">
<td>
<b><input type=radio name="rbox" value="N" <?if($goods_temp1 == "N") echo "checked";?> onclick="setIn('N');"> 봉합사</b>
<b><input type=radio name="rbox" value="M" <?if($goods_temp1 == "M") echo "checked";?> onclick="setIn('M');"> 의료용구/소모품</b>
<b><input type=radio name="rbox" value="D" <?if($goods_temp1 == "D") echo "checked";?> onclick="setIn('D');"> 기구/장비</b>
<b><input type=radio name="rbox" value="E" <?if($goods_temp1 == "E") echo "checked";?> onclick="setIn('E');"> 약품</b>
</td>
</tr>
</table>
<input type="hidden" name="goods_temp1" value="">