<!doctype html>
<html lang="en">
 <head>
<meta charset="utf-8" />
<title>제이쿼리 스크롤 이벤트 문서 최하단 감지하기</title>
	<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.2.0/dist/js/datepicker-full.min.js"></script>
	<script type="text/javascript" src="/m/assets/js/bui.js"></script>
	<script type="text/javascript" src="/m/assets/js/bui.template.js" defer></script>
	<script type="text/javascript" src="/manager/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="/manager/js/jquery_ui.js"></script>
	<script type="text/javascript" src="/manager/js/jquery.easing.1.3.js"></script>
	<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.3/clipboard.min.js"></script> 
<script type="text/javascript">
$(function(){
 
    var docHeight = $(document).height();
    var winHeight = $(window).height();
 
    $(window).scroll(function() {
			if($(window).scrollTop() + winHeight >= docHeight) {
				// alert('Here is Bottom of This Page');
			}
    });

		$('#txt').keypress(function(event){
		});
});
</script>
</head>
<body>
<input type="text" name="txt" id="txt">
    <div class="content">
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
        <p>hello world</p>
    </div>
 </body>
</html>

<?

function utf8_strlen($str) { return mb_strlen($str, 'UTF-8'); }
function utf8_charAt($str, $num) { return mb_substr($str, $num, 1, 'UTF-8'); }
function utf8_ord($c) {
	$len = strlen($c);
	if($len <= 0) return false;
	$h = ord($c[0]);
	if ($h <= 0x7F) return $h;
	if ($h < 0xC2) return false;
	if ($h <= 0xDF && $len>1) return ($h & 0x1F) <<  6 | (ord($c[1]) & 0x3F);
	if ($h <= 0xEF && $len>2) return ($h & 0x0F) << 12 | (ord($c[1]) & 0x3F) <<  6 | (ord($c[2]) & 0x3F);		  
	if ($h <= 0xF4 && $len>3) return ($h & 0x0F) << 18 | (ord($c[1]) & 0x3F) << 12 | (ord($c[2]) & 0x3F) << 6 | (ord($c[3]) & 0x3F);
	return false;
}
/*
function cho_hangul($str, $accept_nonhangul=false) {
	$cho = Array('ㄱ','ㄲ','ㄴ','ㄷ','ㄸ','ㄹ','ㅁ','ㅂ','ㅃ','ㅅ','ㅆ','ㅇ','ㅈ','ㅉ','ㅊ','ㅋ','ㅌ','ㅍ','ㅎ');
	$result = '';
	for ($i=0; $i<utf8_strlen($str); $i++) {
		$c = utf8_charAt($str, $i);
		$code = utf8_ord($c) - 44032;
		if ($code > -1 && $code < 11172) {
			$cho_idx = $code / 588;	  
			$result .= $cho[$cho_idx];
			continue;
		}
		if( $accept_nonhangul || in_array($c, $cho) ) {
			$result .= $c;
			continue;
		}
	}
	return $result;
}

var_dump( cho_hangul("안녕하세요") );  # string(15) "ㅇㄴㅎㅅㅇ"
var_dump( cho_hangul("안1녕하세요") ); # string(15) "ㅇㄴㅎㅅㅇ"
var_dump( cho_hangul("ㅇㄴㅎㅅㅇ") );  # string(15) "ㅇㄴㅎㅅㅇ"
var_dump( cho_hangul("ㅇㄶㅅㅇ") );    # string(9) "ㅇㅅㅇ"

var_dump( cho_hangul("안녕하세요", true) );  # string(15) "ㅇㄴㅎㅅㅇ"
var_dump( cho_hangul("안1녕하세요", true) ); # string(16) "ㅇ1ㄴㅎㅅㅇ"
var_dump( cho_hangul("ㅇㄴㅎㅅㅇ", true) );  # string(15) "ㅇㄴㅎㅅㅇ"
var_dump( cho_hangul("ㅇㄶㅅㅇ", true) );    # string(12) "ㅇㄶㅅㅇ"
*/

function cho_hangul($str, $accept_nonhangul=false) {
	
	$cho = Array('ㄱ','ㄲ','ㄴ','ㄷ','ㄸ','ㄹ','ㅁ','ㅂ','ㅃ','ㅅ','ㅆ','ㅇ','ㅈ','ㅉ','ㅊ','ㅋ','ㅌ','ㅍ','ㅎ');
	$result = '';
	for ($i=0; $i<utf8_strlen($str); $i++) {
		$c = utf8_charAt($str, $i);
		$code = utf8_ord($c) - 44032;
		if ($code > -1 && $code < 11172) {
			$cho_idx = $code / 588;	  
			$result .= $cho[$cho_idx];
			continue;
		}
		if( $accept_nonhangul || in_array($c, $cho) ) {
			$result .= $c;
			continue;
		}
	}
	return $result;
}
?>