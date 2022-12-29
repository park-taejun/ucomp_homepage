<?
#게시판 title

if ( $code == "G1") {
?>
	<td height="30" style="padding-left:20px"><a href="#">BBS 관리</a> > <a href="#">BBS</a> > <a href="#"><?echo $title_mode?></a></td>
<?
} else if ( $code == "G2") {
?>
	<td height="30" style="padding-left:20px"><a href="#">게시판 관리</a> > <a href="#">Q & A</a> > <a href="#"><?echo $title_mode?></a></td>
<?
} 
?>