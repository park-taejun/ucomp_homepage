<!---------- top table 시작  ------------------------------------>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link type="text/css" rel="stylesheet" href="http://www.hillstate.co.kr/Common/Css2/access.css"  />

<!--[if IE 7]><link type="text/css" rel="stylesheet" href="../Common/css2/ie7.css" /><![endif]-->

<script type="text/javascript" src="http://www.hillstate.co.kr/Common/js/jquery/jquery-1.8.3.js"></script>

</head>
<body>

<div class="house_contents">
	<div class="pdfArea">
		<div class="pdfBox">
			<div id="pfdViwer"></div>
		</div>
	<div class="btn_pdfArea">
		<a href="http://file.hillstate.co.kr/uploads/1/20210204_023243.pdf" target="_blank" title="새창 이동" class="btn_pdf_zoom">100% 크게 보기</a>
		<a href="javascript:void(0);" class="btn_pdf_download" onclick="downPdf()" title="PDF문서 다운로드" >PDF문서 다운로드</a>
		<a href="https://get2.adobe.com/kr/reader/" target="_blank" title="새창 이동" class="btn_pdf_download">PDF Viwer 다운로드</a>
		<a id="DownPdf" href="javascript:__doPostBack(&#39;DownPdf&#39;,&#39;&#39;)"></a>
	</div>
</div>
<script type="text/javascript" src="http://dev.ucomp.co.kr/park/Common/js/pdf-js/pdfobject.js"></script>
<script type="text/javascript">

	$(document).ready(function() {

		var myPDF;

		var options = {
			pdfOpenParams: { 
				navpanes: 0,
				toolbar: 0,
				statusbar: 0, 
				view: "FitV",
				pagemode: "none",
				page: 1
			},
			forcePDFJS: true,
			PDFJS_URL: "http://dev.ucomp.co.kr/park/Common/js/pdf-js/web/viewer.html"
		};

		//myPDF = PDFObject.embed('http://file.hillstate.co.kr/uploads/1/20210128_032956.pdf', '#pfdViwer', options);
		myPDF = PDFObject.embed('http://dev.ucomp.co.kr/park/2021_00000.pdf', '#pfdViwer', options);

	});

</script>
</body>
</html>
