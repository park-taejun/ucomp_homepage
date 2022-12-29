<?session_start();?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>스마트 에디터</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
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
<script type="text/javascript" src="../../_common/SE2-2.8.2.3/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
<script language="javascript" type="text/javascript">

</script>

</head>
<body>
<form name="frm" method="post" enctype="multipart/form-data">
	<span class="fl" style="padding-left:0px;width:840px;height:500px;"><textarea name="contents" id="contents"  style="padding-left:0px;width:830px;height:450px;"><?=$rs_contents?></textarea></span>
	<br><br>
	<a href="javascript:js_send();">본문에 적용</a>
</form>
<SCRIPT LANGUAGE="JavaScript">

function js_send() {

	var frm = document.frm;

	oEditors[0].exec("UPDATE_CONTENTS_FIELD", []);

	frm.target = "_blank";
	frm.action = "/naver_read.php";
	frm.submit();

}

var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "contents",
	sSkinURI: "../../_common/SE2-2.8.2.3/SmartEditor2Skin.html",
	htParams : {
		bUseToolbar : true, 
		fOnBeforeUnload : function(){ 
			// alert('야') 
		},
		fOnAppLoad : function(){ 
		// 이 부분에서 FOCUS를 실행해주면 됩니다. 
		this.oApp.exec("EVENT_EDITING_AREA_KEYDOWN", []); 
		this.oApp.setIR(""); 
		//oEditors.getById["ir1"].exec("SET_IR", [""]); 
		}
	}, 
	fCreator: "createSEditor2"
});

</SCRIPT>
</body>
</html>
