<?session_start();?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>다음 에디터</title>

<link rel="stylesheet" href="../../_common/daumeditor/css/editor.css" type="text/css" charset="utf-8"/>
<script src="../../_common/daumeditor/js/editor_loader.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript">

</script>

</head>
<body>

<form name="frm" id="frm" method="post" action="/daum_read.php" accept-charset="utf-8" target="_blank"> 
	<!-- 에디터프레임호출 영역 -->
	<div id="editor_frame" style="width:766px; height:412px;"></div>
	<!-- 실제 값이 담겨져서 넘어갈 textarea 태그 -->
	<textarea name="daumeditor" id="daumeditor" rows="10" cols="100" style="width:766px; height:412px;display: none;">></textarea>

</form>
<br>
<br>
<br>
<br>
<!--<input type="button" id="save_button" value="내용전송"/>-->
<a href="javascript:js_send();">본문에 적용</a>

<SCRIPT LANGUAGE="JavaScript">

$(function(){
	$.ajax({
		url : "../../_common/daumeditor/editor_template.html",
		success : function(data){
			$("#editor_frame").html(data);
			// 에디터UI load
			var config = {
				/* 런타임 시 리소스들을 로딩할 때 필요한 부분으로, 경로가 변경되면 이 부분 수정이 필요. ex) http://xxx.xxx.com */
				txHost: '',
				/* 런타임 시 리소스들을 로딩할 때 필요한 부분으로, 경로가 변경되면 이 부분 수정이 필요. ex) /xxx/xxx/ */
				txPath: '',
				/* 수정필요없음. */
				txService: 'sample',
				/* 수정필요없음. 프로젝트가 여러개일 경우만 수정한다. */
				txProject: 'sample',
				/* 대부분의 경우에 빈문자열 */
				initializedId: "",
				/* 에디터를 둘러싸고 있는 레이어 이름(에디터 컨테이너) */
				wrapper: "tx_trex_container",
				/* 등록하기 위한 Form 이름 */
				form: "frm",
				/*에디터에 사용되는 이미지 디렉터리, 필요에 따라 수정한다. */
				txIconPath: "../../_common/daumeditor/images/icon/editor/",
				/*본문에 사용되는 이미지 디렉터리, 서비스에서 사용할 때는 완성된 컨텐츠로 배포되기 위해 절대경로로 수정한다. */
				txDecoPath: "../../_common/daumeditor/images/deco/contents/",
				canvas: {
					styles: {
						/* 기본 글자색 */
						color: "#123456",
						/* 기본 글자체 */
						fontFamily: "굴림",
						/* 기본 글자크기 */
						fontSize: "10pt",
						/*기본 배경색 */
						backgroundColor: "#fff",
						/*기본 줄간격 */
						lineHeight: "1.5",
						/* 위지윅 영역의 여백 */
						padding: "8px"
					},
					showGuideArea: false
				},
				events: {
					preventUnload: false
				},
				sidebar: {
					attachbox: {
						show: true,
						confirmForDeleteAll: true
					}
				},
				size: {
					/* 지정된 본문영역의 넓이가 있을 경우에 설정 */
					contentWidth: 700
				}
			};
			//에디터내에 환경설정 적용하기
				new Editor(config);
			}
		});

	//form submit 버튼 클릭
	$("#save_button").click(function(){
		//다음에디터가 포함된 form submit
		Editor.save();
	})
})

//Editor.save() 호출 한 다음에 validation 검증을 위한 함수
//validation 체크해줄 입력폼들을 이 함수에 추가 지정해줍니다.
function validForm(editor) {
	var validator = new Trex.Validator();
	var content = editor.getContent();
	if (!validator.exists(content)) {
		alert('내용을 입력하세요');
		return false;
	}
	return true;
}

//validForm 함수까지 true값을 받으면 이어서 form submit을 시켜주는  setForm함수
function setForm(editor) {
	var content = editor.getContent();
	$("#daumeditor").val(content)

	//alert($("#daumeditor").val());
	return true;
}

function js_send() {

	Editor.save();

}

</SCRIPT>
</body>
</html>
