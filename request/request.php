<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "3";

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/ImgUtil.php";
	require "../_classes/com/util/ImgUtilResize.php";
	require "../_classes/com/etc/etc.php";

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<meta name="msvalidate.01" content="D3D1C99AF64E85DB61B385661327885B" />
<meta name="robots" content="index, follow">
<title>유컴패니온 : U:COMPANION REQUEST</title>
<meta content="유컴패니온" name="keywords" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="description" content="유컴패니온 문의 REQUEST ">
<meta content="유컴패니온" name="keywords" />
<meta property="og:type" content="website"> 
<meta property="og:title" content="유컴패니온 문의 REQUEST">
<meta property="og:description" content="유컴패니온 문의 REQUEST">
<meta property="og:url" content="http://admin.ucomp.co.kr/request">
<link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/reset.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery_ui.js"></script>
<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="../js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script type="text/javascript" src="../js/slick.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render=6Ld87m0aAAAAAK7Nd3O9MIMZ-SKHSjMIOnE7TcaS"></script>
</head>

<body>

<div id="wrap">

<?
	require "../_common/front.header.php";
?>

	<!-- S: midarea -->
	<div class="midarea">
		<div class="leftarea"></div>
		<div class="contentsarea" id="contents">
			<div class="project-request">
				<h3>PROJECT <br class="tm-only" />REQUEST</h3>
				<div class="boardwrite">  
					<form id="frm" name="frm" method="post" action="ajax_requet.php" enctype="multipart/form-data">
						<input type="hidden" name="mode" id="mode" value="I">
						<ul class="requestfom-box">
							<li>
								<strong>프로젝트 유형*</strong>
								
								<div>
									<!-- S:20.04.17 마크업 수정 -->
									<div>
										<!--
										<span class="iradio">
											<input type="radio" id="cate_01" name="reason" value="01" checked="checked"/>
											<label for="cate_01">구축 문의</label>
										</span>
										<span class="iradio">
											<input type="radio" id="cate_02" name="reason" value="02"/>
											<label for="cate_02">운영 문의</label>
										</span>
										<span class="iradio">
											<input type="radio" id="cate_03" name="reason" value="03"/>
											<label for="cate_03">영업 / 제휴문의</label>
										</span>
										<span class="iradio">
											<input type="radio" id="cate_05" name="reason" value="99"/>
											<label for="cate_05">기타</label>
										</span>
										-->
										<select class="select-request" name="">
											<option value="01">사업 제휴</option>
											<option value="02">신규 구축</option>
											<option value="03">리뉴얼</option>
											<option value="04">유지 운영</option>
											<option value="99">기타</option>
										</select>
									</div>
									<!-- //E:20.04.17 마크업 수정 -->
									<!-- <ul class="pc-only"
										<li class="iradiobox on"><span class="iradio"><input type="radio" id="cate_01" name="reason" value="01"/></span><label for="cate_01">구축 문의</label></li>
										<li class="iradiobox"><span class="iradio"><input type="radio" id="cate_02" name="reason" value="02"/></span><label for="cate_02">운영 문의</label></li>
										<li class="iradiobox"><span class="iradio"><input type="radio" id="cate_03" name="reason" value="03"/></span><label for="cate_03">영업 / 제휴문의</label></li>
										<li class="iradiobox"><span class="iradio"><input type="radio" id="cate_04" name="reason" value="04"/></span><label for="cate_04">파견 문의</label></li>
										<li class="iradiobox"><span class="iradio"><input type="radio" id="cate_05" name="reason" value="99"/></span><label for="cate_05">기타</label></li>
									</ul> -->
									<div class="tm-only">
										<!--
										<select class="select-request" name="">
											<option value="01">구축 문의</option>
											<option value="02">운영 문의</option>
											<option value="03">영업 / 제휴문의</option>
											<option value="04">파견 문의</option>
											<option value="99">기타</option>
										</select>
										-->
										<select class="select-request" name="">
											<option value="01">사업 제휴</option>
											<option value="02">신규 구축</option>
											<option value="03">리뉴얼</option>
											<option value="04">유지 운영</option>
											<option value="99">기타</option>
										</select>
									</div>
									<input type="hidden" name="request_cate" id="request_cate" value="01">
								</div>
							</li>
							<li class="medium">
								<strong>예산</strong>
								<div class="email">
									<span class="inpbox"><input type="text" class="txt onlynum" name="req_budget" id="req_budget"  title="예산 입력" placeholder="예산을 입력해주세요." /></span><b>만원</b>
								</div>
							</li>
							<li class="medium">
								<strong>기간</strong>
								<div class="email">
									<span class="inpbox"><input type="text" class="txt onlynum" name="req_month" id="req_month" title="기간 입력" placeholder="기간을 입력해주세요." /></span><b>개월</b>
								</div>
							</li> 
							<li>
								<strong>문의 제목*</strong>
								<div><span class="inpbox"><input type="text" class="txt" title="제목 입력" name="req_title" id="req_title" placeholder="제목을 입력해주세요." /></span></div>
							</li>
							<li class="demand-txt">
								<strong>상세 문의 내용*</strong>
								<div>
									<span class="textareabox">
										<textarea name="req_contents" id="req_contents" placeholder="문의내용을 상세히 기재해 주시면 더욱 빠른 피드백이 가능합니다."></textarea>
									</span>
								</div>
							</li>
							<li>
								<strong>회사명</strong>
								<div class="email">
									<span class="inpbox"><input type="text" class="txt" name="req_company" id="req_company" title="회사명 입력" placeholder="회사명을 입력해주세요." /></span>
								</div>
							</li>														
							<li>
								<strong>직책</strong>
								<div class="email">
									<span class="inpbox"><input type="text" class="txt" name="req_position" id="req_position" title="직책 입력" placeholder="직책을 입력해주세요." /></span>
								</div>
							</li>
							<li>
								<strong>담당자명*</strong>
								<div class="name-txt"><span class="inpbox"><input type="text" class="txt" name="req_name" id="req_name" title="이름 입력" placeholder="이름 입력" /></span></div>
							</li>
							<li>
								<strong>이메일*</strong>
								<div class="email">
									<span class="inpbox"><input type="text" class="txt" name="req_email01" id="req_email01" title="이메일 입력" placeholder="이메일주소 입력" /></span><b>@</b>
									<span class="inpbox"><input type="text" class="txt" name="req_email02" id="req_email02" title="이메일 입력" placeholder="" /></span>
								</div>
							</li>
							<li>
								<strong>연락처</strong>
								<div class="tel">
									<span class="inpbox"><input type="text" class="txt onlynum" name="req_tel01" id="req_tel01" maxlength="3" title="연락처 앞자리 입력" placeholder="010" /></span><b>-</b>
									<span class="inpbox"><input type="text" class="txt onlynum" name="req_tel02" id="req_tel02" maxlength="4" title="연락처 중간자리 입력" placeholder="0000" /></span><b>-</b>
									<span class="inpbox last"><input type="text" class="txt onlynum" name="req_tel03" id="req_tel03" maxlength="4" title="연락처 뒷자리 입력" placeholder="0000" /></span>
								</div>
							</li>
							
							<!--
							<li class="file-list">
								<strong>파일첨부</strong>
								<div>
									<p class="filebox">
										<span class="filetxt">
											<strong data-rol="file_01"></strong>
											<button type="button" class="btn-del" onclick="fileDel('file_01')">삭제</button>
										</span>
										<span class="ipfile">
											<label for="file_01">파일첨부</label>
											<input type="file" class="file" name="file_01" id="file_01" onchange="fileAdd('file_01')" />
										</span>
									</p>
									<p class="comment"><b>※ 파일첨부 (용량제한 3M)</b> 파일의 개수가 많을 경우 압축 파일로 등록해 주시기 바랍니다.<!--파일의 개수가 많을 경우 압축을 하거나 담당자에게 메일로 전달해 주시기 바랍니다.--></p>
								<!--</div>
							</li>
							-->
            </ul>
				  <input type="hidden" id="g-recaptcha" name="g-recaptcha">

						<!-- <p class="privacy_btn">
							<span class="ickbox"><input type="checkbox" id="privacy_01" name="privacy" value="02"/></span>
							<label for="privacy_01">개인 정보 수집·이용에 동의합니다.</label> 
							<button onclick="modalView('pop-privacy')">전문보기</button>
						</p> -->

						<!-- S:2020.04.17 마크업 수정 -->
						<p class="privacy_btn">
							<span class="ickbox">
								<input type="checkbox" id="privacy_01" name="privacy" value="02"/>
								<label for="privacy_01">개인 정보 수집·이용에 동의합니다.</label>
							</span>
							<button onclick="modalView('pop-privacy')">전문보기</button>
						</p>
						<!-- //E:2020.04.17 마크업 수정 -->					
				</div>
				
				<p class="btncenter">
					<button type="button" class="btn-red" id="btn_submit">확인</button>
					<button type="button" class="btn-black" id="btn_reset">취소</button>
				</p>
			</div>
		</div>
	</div>
	<!-- //E: midarea -->

</div>
</form>
	<!-- S: modalpop -->
	<div class="modalpop">
		<div class="popupwrap pop-privacy">
			<button type="button" class="btn-popclose" onclick="modalHide('pop-privacy')" title="닫기">닫기</button>
			<h1>본인 확인</h1>
			<div class="popcontents">
				<dl>
					<dt>(1) 수집하는 개인정보의 항목 및 수집방법</dt>
					<dd>
							<ul>
								<li><span>A. 수집하는 개인정보의 항목</span>
									<ul>
										<li>유컴패니온은 원활한 프로젝트 상담을 위해 아래와 같은 개인정보를	수집하고 있습니다.</li>
										<li>성명, E-mail, 회사정보, 전화번호</li>
									</ul>
								</li>
								<li><span>B.개인정보 수집방법</span>
									<ul>
										<li>유컴패니온은 이용자가 자발적으로, 구체적으로 기입할 때만 개인정보를 수집하고 있습니다.</li>
									</ul>
								</li>
							</ul>
					</dd>
				</dl>
				<dl>
					<dt>(2) 개인정보 수집 및 이용목적</dt>
					<dd>
						<ul>
							<li><span>A. 유컴패니온은 수집한 개인정보를 다음의 목적을 위해 활용합니다.</span>
								<ul>
									<li>프로젝트문의</li>
								</ul>
							</li>
						</ul>
					</dd>
				</dl>
				<dl>
					<dt>(3) 개인정보의 보유 및 이용기간</dt>
					<dd>유컴패니온의 개인정보 보유 및 이용기간은 작성 시점부터 3년이내로 합니다.</dd>
				</dl>
			</div>
		</div>
	</div>
	<!-- //E: modalpop -->

<script type="text/javascript" src="../js/common_ui.js"></script>

<script type="text/javascript">
grecaptcha.ready(function() {
  grecaptcha.execute('6Ld87m0aAAAAAK7Nd3O9MIMZ-SKHSjMIOnE7TcaS', {action: 'homepage'}).then(function(token) {
    // 토큰을 받아다가 g-recaptcha 에다가 값을 넣어줍니다.
    document.getElementById('g-recaptcha').value = token;
  });
});
</script>

<script>

$(document).ready(function(){

/*
		// 등록
		$("#frm").ajaxForm({
			beforeSubmit: function(data, form, option) {
				return true;
			},
			success: function(response,status) {
				alert("문의가 등록 되었습니다. 담당자 확인 후 연락 드리겠습니다.");
				document.frm.reset();
				fileDel('file_01');

			},
			error: function() {
			}
	});
*/

	$("#file_01").change(function() {

		if ($("#file_01").val() != "") {

			var arr_image_nm = $("#file_01").val().split("\\");
			var arr_path = arr_image_nm[arr_image_nm.length-1];

			if (!chk_file_size(3, document.frm.file_01)) {
				alert("업로드 용량을 초과 하셨습니다.");
				fileDel('file_01');
				return;
			}

			var arr_file_info = arr_path.split(".");
			var file_ext = arr_file_info[arr_file_info.length-1].toUpperCase();

			var allow_ext = ["JPG","GIF","PNG","JPEG","XLS","XLSX","ZIP","DOC","DOCX","HWP","PDF","PPT","PPTX"];
			var allow_flag = false;

			for (var i = 0; i < allow_ext.length ; i++) {
				if (file_ext == allow_ext[i]) {
					allow_flag = true;
				}
			}

			if (allow_flag == false) {
				alert("등록할 수 없는 파일 형식 입니다.");
				fileDel('file_01');
				return;
			}
		}
	});
});

$(document).on("click", ".iradio", function() {
	//$(".iradiobox").removeClass("on");
	//$(this).addClass("on");
	$("#request_cate").val($(this).find("input").val());
	//alert($("#req_reason").val());
});

$(document).on("click", "#btn_reset", function() {
	document.frm.reset();
	fileDel('file_01');
});


$(document).on("click", "#btn_submit", function() {

	var frm = document.frm;
	
	if((frm.req_title.value == "") || (frm.req_title.value == " ")){
		alert("문의제목을 입력하세요");
		frm.req_title.focus();
		return;
	}
	
	if(frm.req_contents.value == ""){
		alert("상세 문의 내용을 입력하세요");
		frm.req_contents.focus();
		return;
	}
	  
	if((frm.req_name.value == "") || (frm.req_name.value == " ")){
		alert("담당자명을 입력하세요");
		frm.req_name.focus();
		return;
	}
	
	if(frm.req_email01.value == ""){
		alert("이메일을 입력하세요");
		frm.req_email01.focus();
		return;
	}
  
	if(!frm.privacy_01.checked){  
		alert("개인 정보 수집·이용에 동의하셔야 합니다.");
		frm.privacy_01.focus();
		return;
	}

	$("#frm").submit();
	//alert("준비 중 입니다.");

});

function fileAdd(Idx){
	var file = document.getElementById(Idx);
	$("strong[data-rol='"+Idx+"'").text(file.value);
	delBtnShow(Idx);
}
function delBtnShow(Idx){
	if($("strong[data-rol='"+Idx+"'").next("button").css("display") == "none"){
		$("strong[data-rol='"+Idx+"'").next("button").css("display", "inline-block");
	}
}
function fileDel(Idx){
	$("strong[data-rol='"+Idx+"'").text('').next("button").css("display", "none");
}

/*$(document).on('click', '.iradio, .ickbox', function(){
	//console.log($(this))
	if($(this).is(".iradio")){
		if ($(this).hasClass("on") == false){
			$(this).parent().parent().find(".iradio").removeClass("on");
			$(this).parent().parent().find(".iradio input").attr("checked", false);
			$(this).addClass("on");
			$(this).find("input").attr("checked", true);
		}
	}else if($(this).is(".ickbox")){
		if ($(this).hasClass("on") == false){
			$(this).parent().find(".ickbox").removeClass("on");
			$(this).parent().find(".ickbox input").attr("checked", false);
			$(this).addClass("on");
			$(this).find("input").attr("checked", true);
		}else{
			$(this).removeClass("on");
			$(this).find("input").attr("checked", false);
			$(this).parents(".boardlist").find("th.check-all").children(".ickbox").removeClass("on").find("input.ick").attr("checked", false);
		}
	}
});*/



function chk_file_size(max, obj) {
	var maxSize = max * 1024 * 1024;
	var fileSize = 0;
	var b = navigator.appName;
	if (b == "Microsoft Internet Explorer") {
		var oas = new ActiveXObject("Scripting.FileSystemObject");
		fileSize = oas.getFile( obj.value ).size;
	} else {
		fileSize = obj.files[0].size;
	}
	if (maxSize >= fileSize) {
		return true;
	} else {
		return false;
	}
}

$(document).on("keyup", ".noblank", function() {
	$(this).val($(this).val().replace(/\s/g,''));
});

$(document).on("keyup", ".onlynum", function() {
	$(this).val($(this).val().replace(/[^0-9]/g,''));
});

$(document).on("keyup", ".onlyphone", function() {
	$(this).val($(this).val().replace(/[^0-9-]/g,''));
});

$(document).on("keyup", ".onlynumAlphabet", function() {
	$(this).val($(this).val().replace(/[^0-9a-zA-Z]/g,''));
});

$(document).on("keyup", ".onlynumAlphabetSpecial", function() {
	$(this).val($(this).val().replace(/[^0-9a-zA-Z~!@#$%^&*()_+|<>?:{}]/g,''));
});



</script>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28115000-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' :'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>