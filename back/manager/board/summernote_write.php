<?session_start();?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>썸머노트 에디터</title>
<!-- include libraries(jQuery, bootstrap) -->
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> 
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 

<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>
<script>

	$(document).ready(function() {
		//$('#summernote').summernote();

		$('#summernote').summernote({
			height: 300,                 // set editor height
			width: 800,                 // set editor width
			minHeight: null,             // set minimum height of editor
			maxHeight: null,             // set maximum height of editor
			focus: true                  // set focus to editable area after initializing summernote
		});


	});

	function js_send() {

		var frm = document.frm;

		$("#contents").val($('#summernote').summernote('code'));
		//alert($("#contents").val());
		frm.submit();

	}


</script>
<body>
<form name="frm" method="post" action="/summernote_read.php" accept-charset="utf-8" target="_blank">
<div id="summernote"></div>
<input type="hidden" id="contents" name="contents" value="">
<a href="javascript:js_send();">본문에 적용</a>
</form>
</body>
</html>