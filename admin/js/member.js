/*
//회원관련 자바스크립트
//2012-04-21
//chingong
*/

function containsCharsOnly(input,chars) {
	
	return containsCharsOnly2(input,chars,chars,true);	
}
function containsCharsOnly2(input,chars,al,flag) {
	
	for (var inx = 0; inx < input.value.length; inx++) {

		if (chars.indexOf(input.value.charAt(inx)) == -1) {
			if(flag)alert(al + "만 입력 가능합니다..");

			input.value = input.value.replace(input.value.charAt(inx), '');
			//alert(input.value.charAt(inx));
			
			//input.value = input.value.substring(0,input.value.length -1);
			
			input.focus();
			return false;
		}
	}
	return true;	
}

// 전화번호 검사 
//
function isPhoneNumber(input) {
	var chars = "1234567890-~() ";
	return containsCharsOnly(input,chars);
}

function isNumber(input) {
	var chars = "1234567890";
	return containsCharsOnly(input,chars);
}
function isNumberReal(input) {
	var chars = "1234567890.-+";
	return containsCharsOnly(input,chars);
}

function isScaleNumber(input) {
	var chars = "1234567890X";
	return containsCharsOnly(input,chars);
}

function isAlphabetNumber(input) {
	var chars = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	return containsCharsOnly2(input,chars,"",false);
}

// 주민 등록 번호 유효 검사
function CheckJuminForm(J1, J2) {
	
	var SUM=0;

	for(i=0;i<J1.length;i++)  {

		if (J1.charAt(i) >= 0 || J1.charAt(i) <= 9) { 
			if(i == 0) {
				SUM = (i+2) * J1.charAt(i);
			} else { 
				SUM = SUM + (i+2) * J1.charAt(i);
			}
		} else {
			return false;
		}
	}

	for(i=0;i<2;i++) {
		
		if (J2.charAt(i) >= 0 || J2.charAt(i) <= 9) {
			SUM = SUM + (i+8) * J2.charAt(i);
		} else { 
			return false;
		}
	}

	for(i=2;i<6;i++) {

		if (J2.charAt(i) >= 0 || J2.charAt(i) <= 9) {
			SUM = SUM + (i) * J2.charAt(i);
		} else {
			return false;
		}
	}

	var checkSUM = SUM % 11;

	if(checkSUM == 0) {
		var checkCODE = 10;
	} else if (checkSUM ==1) {
		var checkCODE = 11;
	} else {
		var checkCODE = checkSUM;
	}

	var check1 = 11 - checkCODE; 

	if (J2.charAt(6) >= 0 || J2.charAt(6) <= 9) {
		var check2 = parseInt(J2.charAt(6))
	} else {
		return false;
	}

	if(check1 != check2) {
		return false;
	} else {
		return true; 
	}
} 


//maxlength 만큼 옮기면 다음으로 이동하기....
function nextFocus(sFormName,sNow,sNext)
{
	var sForm = 'document.'+ sFormName +'.'
	var oNow = eval(sForm + sNow);

	if (typeof oNow == 'object')
	{
		if ( oNow.value.length == oNow.maxLength)
		{
			var oNext = eval(sForm + sNext);

			if ((typeof oNext) == 'object')
				oNext.focus();
		}
	}
}


// 실명인증 확인
function checkRealName( pForm ) {
	if( $.trim(document.gform.user_nm.value) =="" ) {
		alert('이름을 입력해 주세요.');
		return false;
	}
    else if(  $.trim(document.gform.user_ssn1.value) =="") {
		alert('주민등록번호를 입력해 주세요.');
		return false;
	}
    else if(  $.trim(document.gform.user_ssn2.value) =="") {
		alert('주민등록번호를 입력해 주세요.');
		return false;
	}
    /*else if( !document.gform.agreement.checked ) {
		alert('본인확인에 동의하셔야 가입하실 수 있습니다.');
		return false;
	}*/
    else {
		return true;
	}
}
// 실명인증 성공일때 호출
function checkRealNameNextGo( ) {
	var frm = document.gform;

	frm.target="";
	frm.checkrealname.value="Y";
	frm.action = frm.actionhref.value;
	frm.submit();
}


// 아이디중복검색
function checkDupUserId() {
	var id = document.gform.user_id.value;
	
	$("#error_user_id").html("확인중입니다.");
	document.gform.user_check_dupid.value="";
	var pattern = /^[a-zA-Z0-9_-]{5,20}$/;
	if( id == '' ) {
		alert('아이디를 입력해 주십시요.');
		document.gform.user_id.focus();
		$("#error_user_id").html("");
		return ;
	}
	if( !pattern.test(id) ) {
		alert('아이디는 5자이상 영문 또는 영문/숫자 조합이어야 합니다.');
		$("#error_user_id").html("");
		document.gform.user_id.value="";
		document.gform.user_id.focus();
		return ;
	}
	document.gform.user_check_dupid.value="";
	$.get("/kor/member/data.json.user_check.php", 
		{mode:'DUPID', chk_user_id:id, r: Math.random()}, 
		function(data){      
			//isprocessing_comment=false;
			try{
				var jobj = $.parseJSON($.trim(data));
				if($.trim(jobj.CHECKDUPID)=="DUPNO"){							
					$("#error_user_id").html("(O) 사용가능한 아이디입니다.");
					document.gform.user_check_dupid.value=id;
				}else if($.trim(jobj.CHECKDUPID)=="DUPYES"){							
					$("#error_user_id").html("(X) 이미 사용중인 아이디입니다.");
				}else{							
					$("#error_user_id").html("다시 확인해 주세요.");
				}
			}catch(e){
				//alert("e="+e)
				gbc_page=1;
			}
		}
	);
}





// 닉네임중복검색
function checkDupUserNick() {
	
	$("#error_user_nick").html("확인중입니다.");
	document.gform.user_check_dupnick.value="";

	var nick = document.gform.user_nick.value;
	//var pattern = /^[a-zA-Z0-9 _-]{5,20}$/;
	var pattern = /^[a-zA-Z0-9 _-]{3,10}$/;
	var pattern = /^[a-zA-Z0-9가-힣@#_-]{2,10}$/;
	if( nick == '' ) {
		alert('닉네임을 입력해 주십시요.');
		$("#error_user_nick").html("");
		document.gform.user_nick.focus();
		return ;
	}
	if( !pattern.test(nick) ) {
		alert('아이디는 3자이상 한글, 영문 또는 한글/영문/숫자 조합이어야 합니다.');
		//document.gform.user_nick.value="";
		//document.gform.user_nick.focus();
		$("#error_user_nick").html("");
		document.gform.user_nick.focus();
		return ;
	}
	
	
	document.gform.user_check_dupnick.value="";
	$.get("/kor/member/data.json.user_check.php", 
		{mode:'DUPNICK', chk_user_nick:nick, r: Math.random()}, 
		function(data){      
			//isprocessing_comment=false;
			try{
				var jobj = $.parseJSON($.trim(data));
				if($.trim(jobj.CHECKDUPNICK)=="DUPNO"){							
					$("#error_user_nick").html("(O) 사용가능한 닉네임입니다.");
					document.gform.user_check_dupnick.value=nick;
				}else if($.trim(jobj.CHECKDUPNICK)=="DUPYES"){							
					$("#error_user_nick").html("(X) 이미 사용중인 닉네임입니다.");
				}else{							
					$("#error_user_nick").html("다시 확인해 주세요.");
				}
			}catch(e){
				//alert("e="+e)
				gbc_page=1;
			}
		}
	);
}



function userSearchZip(){

}

$('#dong').keyup(function(event) {   
  if (event.which == 13) {    js_searchZip('document.gform');  }  
 });
function OpenZipLayer(value, value2){
	document.getElementById("ZipLayer").style.display = "block";
	if(document.gform.mode.value =="U")document.getElementById("ZipLayer").style.top = "400px";
	eval(value).dong.focus();
	document.gform.addr_gubun.value = value2;
}

function js_searchZip(val) {
	
	var dong;
	dong = eval(val).dong.value;

	if(dong==""){
		alert("주소(읍/면/동)을 입력하셔야 합니다.");
		eval(val).dong.focus();
		return;
	}
	eval(val).target = "ifr_zip";
	eval(val).action = "/_common/ifrm_zipcode_search.php";
	eval(val).submit();

}

function js_hide(d){

	document.getElementById(d).style.display = "none";
	document.gform.dong.value = "xxxx";
	document.gform.target = "ifr_zip";
	document.gform.action = "/_common/ifrm_zipcode_search.php";
	document.gform.submit();
	document.gform.target = "";
	document.gform.dong.value = "";
}



// 기존아이디 입력
function inMyId(n) {
	var frm = document.gform;
	frm.user_id.value=n;
}
// 기존닉네임 입력
function inMyNick(n) {
	var frm = document.gform;
	frm.user_nick.value=n;
}


//회원 가입페이지에서 취소버턴
function UserRegCancel(){
	var conf = confirm("회원가입을 취소하시겠습니까?");
	if(conf){
		//document.gform.reset();
		location.href="/manager/member/member_list.php";
	}

}

//이메일 입력 방법
function js_email_input_type(){

	var frm = document.gform;
	if(frm.email_input_type.value!="00" && frm.email_input_type.value!="직접입력"){
		frm.email2.value = frm.email_input_type.value;
	}
	
	if(frm.email_input_type.value=="00" || frm.email_input_type.value=="직접입력"){
		frm.email2.value = "";
		frm.email2.focus();
	}

}

//회원 가입페이지에서 가입버턴
function regMember() {
	
	var frm = document.gform;
	
	frm.target="";
	frm.action="member_write.php";
	var mode = frm.mode.value;


	if(mode=="I"){
		if( !frm.agreement.checked ) {
			alert('이용약관에 동의하셔야 가입하실 수 있습니다.');
			return false;
		}
		
	
		if($('input[name=calendar]:checked').val()=="undefined" || typeof($('input[name=calendar]:checked').val())=="undefined"){
			alert('생년월일(음력/양력)을 선택해 주세요');
			return false;
		}

		if ( frm.user_id.value == "" ){
			alert( "아이디를 입력해 주세요" );
			frm.user_id.focus();
			return false;
		}
		if (isNull(frm.user_check_dupid.value)) {
			alert('아이디 중복체크해주세요.');
			return false;		
		}
		if (frm.user_check_dupid.value!=frm.user_id.value) {
			alert('아이디 중복체크해주세요.');
			return false;		
		}

		if (isNull(frm.user_nick.value)) {
			alert('닉네임를 입력해주세요.');
			frm.user_nick.focus();
			return false;		
		}
		if (isNull(frm.user_check_dupnick.value)) {
			alert('닉네임 중복체크해주세요.');
			return false;		
		}
		if (frm.user_check_dupnick.value!=frm.user_nick.value) {
			alert('닉네임 중복체크해주세요.');
			return false;		
		}
	
		if ( isNull(frm.user_pw.value) ){
			alert( "비밀번호를 입력해 주세요" );
			frm.user_pw.focus();
			return false;
		}
		if ( !isAlphabetNumber(frm.user_pw) ){
			alert( "비밀번호는 영문+숫자 6자리 이상을 입력해 주세요" );
			frm.user_pw.focus();
			return false;
		}
		if ( frm.user_pw.value.trim().length<6 ){
			alert( "비밀번호 6자리 이상을 입력해 주세요" );
			frm.user_pw.focus();
			return false;
		}
	}

	if(mode=="U" && !isNull(frm.user_pw.value)){
		if ( !isAlphabetNumber(frm.user_pw) ){
			alert( "비밀번호는 영문+숫자 6자리 이상을 입력해 주세요" );
			frm.user_pw.focus();
			return false;
		}
		if ( frm.user_pw.value.trim().length<6 ){
			alert( "비밀번호 6자리 이상을 입력해 주세요" );
			frm.user_pw.focus();
			return false;
		}
	}


	if ( frm.user_pw.value != frm.user_pw2.value ){
		alert( "비밀번호가 다릅니다." );
		frm.user_pw.value = frm.user_pw2.value = "";
		frm.user_pw.focus();
		return false;
	}


	

	if ( isNull(frm.user_pw_req.value) ){
		alert( "비밀번호 찾기 질문을 선택해 주세요" );
		frm.user_pw_req.focus();
		return false;
	}

	if ( isNull(frm.user_pw_ans.value) ){
		alert( "비밀번호 찾기 답변을 입력해 주세요" );
		frm.user_pw_ans.focus();
		return false;
	}


	if (isNull(frm.hphone1.value)) {
		alert('휴대전화번호를 입력해주세요.');
		frm.hphone1.focus();
		return false;		
	}

	if (isNull(frm.hphone2.value)) {
		alert('휴대전화번호를 입력해주세요.');
		frm.hphone2.focus();
		return false;		
	}

	if (isNull(frm.hphone3.value)) {
		alert('휴대전화번호를 입력해주세요.');
		frm.hphone3.focus();
		return false;		
	}
	
	if (!isNumber(frm.hphone1)) {
		//alert('휴대전화번호는 숫자만 입력해주세요.');
		frm.hphone1.focus();
		return false;		
	}

	if (!isNumber(frm.hphone2)) {
		//alert('휴대전화번호는 숫자만 입력해주세요.');
		frm.hphone2.focus();
		return false;		
	}

	if (!isNumber(frm.hphone3)) {
		///alert('휴대전화번호는 숫자만 입력해주세요.');
		frm.hphone3.focus();
		return false;		
	}
	
	if($('input[name=sms_tf]:checked').val()=="undefined" || typeof($('input[name=sms_tf]:checked').val())=="undefined"){
		alert('휴대전화(SMS) 수신여부를 선택해 주세요');
		return false;
	}

	if (!isNull(frm.phone1.value)) {
		if (!isNumber(frm.phone1)) {
			frm.phone1.focus();
			return false;		
		}
	}

	if (!isNull(frm.phone2.value)) {
		if (!isNumber(frm.phone2)) {
			frm.phone2.focus();
			return false;		
		}
	}

	if (!isNull(frm.phone3.value)) {
		if (!isNumber(frm.phone3)) {
			frm.phone3.focus();
			return false;		
		}
	}


	if (isNull(frm.email1.value)) {
		alert('이메일를 입력해주세요.');
		frm.email1.focus();
		return false;		
	}

	if (isNull(frm.email2.value)) {
		alert('이메일를 입력해주세요.');
		frm.email2.focus();
		return false;		
	}

	if($('input[name=email_tf]:checked').val()=="undefined" || typeof($('input[name=email_tf]:checked').val())=="undefined"){alert('이메일 수신여부를 선택해 주세요');return false;}

	if (isNull(frm.zipcode01.value)) {
		alert('자택주소(우편번호앞)를 입력해주세요.');
		frm.zipcode01.focus();
		return false;		
	}
	if (isNull(frm.zipcode02.value)) {
		alert('자택주소(우편번호뒤)를 입력해주세요.');
		frm.zipcode02.focus();
		return false;		
	}


	if (!isNumber(frm.zipcode01)) {
		//alert('주소를 입력해주세요.');
		frm.zipcode01.focus();
		return false;		
	}
	if (!isNumber(frm.zipcode02)) {
		//alert('주소를 입력해주세요.');
		frm.zipcode02.focus();
		return false;		
	}
	


	if (isNull(frm.addr01.value)) {
		alert('자택주소를 입력해주세요.');
		frm.addr01.focus();
		return false;		
	}

	if (isNull(frm.addr02.value)) {
		alert('자택주소(상세)를 입력해주세요.');
		frm.addr02.focus();
		return false;		
	}

	return true;
}






// 당원가입 본인확인 확인
function checkRealPrivacy( g ) {
	
	var frm = document.gform;
	if( g == "card" ) {
		//location.href="join02_4.php";
		frm.auth_obj_gubun.value="C";
	}
    else if(  g == "phone" ) {
		//location.href="join02_4.php";
		frm.auth_obj_gubun.value="M";
	}
    else if(  g == "gongin" ) {
		//location.href="join02_4.php";
		frm.auth_obj_gubun.value="X";
	}

	//frm.target="hiddeniframe";
	//2012-04-24
	frm.submit();
}


// 본인확인 성공일때 호출
function checkRealPrivacyNextGo( ) {
	var frm = document.gform;

	frm.target="";
	frm.checkprivacy.value="Y";
	frm.action = frm.actionhref.value;
	frm.submit();
}

//당원가입 결제 관련
function payMemberCheck(){
	var p1 = $('#payCheckBox1:checked').val();
	var p2 = $('#payCheckBox2:checked').val();
	var p3 = $('#payCheckBox3:checked').val();
	
	$("#pay_cms_h").css("display","none");
	$("#pay_cms_div").css("display","none");
	$("#pay_card_h").css("display","none");
	$("#pay_card_div").css("display","none");
	$("#pay_phone_h").css("display","none");
	$("#pay_phone_div").css("display","none");


	if(typeof(p1) != "undefined"){
		$("#pay_cms_h").css("display","block");
		$("#pay_cms_div").css("display","block");
	}
	if(typeof(p2) != "undefined"){
		$("#pay_card_h").css("display","block");
		$("#pay_card_div").css("display","block");
	}
	if(typeof(p3) != "undefined"){
		$("#pay_phone_h").css("display","block");
		$("#pay_phone_div").css("display","block");
	}


}







//당원 가입페이지에서 취소버턴
function UserRegPartyCancel(n){
	var conf = confirm("회원가입을 취소하시겠습니까?");
	if(conf){
		//document.gform.reset();
		location.href="/";
	}
}

//당원 가입페이지에서 가입버턴
function regMemberParty() {
	
	var frm = document.gform;
	if(!regMember())return false;
	frm.target="";
	frm.action="member_write.php";

	
	var p1 = $('#payCheckBox1:checked').val();
	var p2 = $('#payCheckBox2:checked').val();
	var p3 = $('#payCheckBox3:checked').val();
	var pay_radio = $('input[name=partydue_type]:checked').val();

	
	if(frm.already_partydue_money_always.value!="Y" && pay_radio == "1"){//cms선택시
		if (isNull(frm.cms_partydue_name.value)) {
			alert('[결제정보]은행을 선택해주세요.');
			return false;		
		}
		if (isNull(frm.cms_partydue_number.value)) {
			alert('[결제정보]계좌번호를 입력해주세요.');
			return false;		
		}
		if (!isNumber(frm.cms_partydue_number)) {
			return false;		
		}
		if($('input[name=cms_partydue_day]:checked').val()=="undefined" || typeof($('input[name=cms_partydue_day]:checked').val())=="undefined"){alert('[결제정보]출금일을 선택해 주세요');return false;}
		
		if (isNull(frm.cms_partydue_money.value)) {
			alert('약정금액을 입력해주세요.');
			return false;		
		}
		
		if (!isNumber(frm.cms_partydue_money)) {
			return false;		
		}
		if($('input[name=cms_partydue_print]:checked').val()=="undefined" || typeof($('input[name=cms_partydue_print]:checked').val())=="undefined"){alert('[결제정보]당비영수증발급처를 선택해 주세요');return false;}
		frm.partydue_name.value = frm.cms_partydue_name.value;
		frm.partydue_money.value = frm.cms_partydue_money.value;
		frm.partydue_number.value = frm.cms_partydue_number.value;
		frm.partydue_day.value = $('input[name=cms_partydue_day]:checked').val();//frm.cms_partydue_day.value;
		frm.partydue_valid_date.value = "";//카드
		frm.partydue_print.value = $('input[name=cms_partydue_print]:checked').val() ;//frm.cms_partydue_print.value;
		frm.card_partydue_valid_date_y.value="";
		frm.card_partydue_valid_date_m.value="";
		
	}
	if(frm.already_partydue_money_always.value!="Y" && pay_radio == "3"){//카드선택시
		if (isNull(frm.card_partydue_name.value)) {
			alert('[결제정보]카드사를 선택해주세요.');
			return false;		
		}
		if (isNull(frm.card_partydue_number.value)) {
			alert('[결제정보]카드번호를 입력해주세요.');
			frm.card_partydue_number.focus();
			return false;		
		}
		if (!isNumber(frm.card_partydue_number)) {
			return false;		
		}
		if($('input[name=card_partydue_day]:checked').val()=="undefined" || typeof($('input[name=card_partydue_day]:checked').val())=="undefined"){alert('[결제정보]출금일을 선택해 주세요');return false;}
		
		if (isNull(frm.card_partydue_money.value)) {
			alert('약정금액을 입력해주세요.');
			return false;		
		}
		
		if (!isNumber(frm.card_partydue_money)) {
			return false;		
		}
		if($('input[name=card_partydue_print]:checked').val()=="undefined" || typeof($('input[name=card_partydue_print]:checked').val())=="undefined"){alert('[결제정보]당비영수증발급처를 선택해 주세요');return false;}

		//if(isNull($('input[name=card_partydue_day]:checked').val())){alert('출금일을 선택해 주세요');return false;}
		frm.partydue_name.value = frm.card_partydue_name.value;
		frm.partydue_money.value = frm.card_partydue_money.value;
		frm.partydue_number.value = frm.card_partydue_number.value;
		frm.partydue_day.value = $('input[name=card_partydue_day]:checked').val();//frm.card_partydue_day.value;
		frm.partydue_valid_date.value = frm.card_partydue_valid_date_y.value+""+frm.card_partydue_valid_date_m.value;//카드
		frm.partydue_print.value = $('input[name=card_partydue_print]:checked').val() ;//frm.card_partydue_print.value;
		//if(isNull($('input[name=card_partydue_day]:checked').val()){alert('출금일을 선택해 주세요');return false;}		

	}

	if(frm.already_partydue_money_always.value!="Y" && pay_radio == "2"){//폰선택시
		if($('input[name=phone_partydue_name]:checked').val()=="undefined" || typeof($('input[name=phone_partydue_name]:checked').val())=="undefined"){alert('[결제정보]통신사를 선택해 주세요');return false;}
		if (isNull(frm.phone_partydue_number1.value)) {
			alert('[결제정보]전화번호를 입력해주세요.');
			frm.phone_partydue_number1.focus();
			return false;		
		}
		
		if (isNull(frm.phone_partydue_number2.value)) {
			alert('[결제정보]전화번호를 입력해주세요.');
			frm.phone_partydue_number2.focus();
			return false;		
		}
		
		if (isNull(frm.phone_partydue_number3.value)) {
			alert('[결제정보]전화번호를 입력해주세요.');
			frm.phone_partydue_number3.focus();
			return false;		
		}
		if (!isNumber(frm.phone_partydue_number1)) {
			return false;		
		}
		if (!isNumber(frm.phone_partydue_number2)) {
			return false;		
		}
		if (!isNumber(frm.phone_partydue_number2)) {
			return false;		
		}
		if($('input[name=phone_partydue_day]:checked').val()=="undefined" || typeof($('input[name=phone_partydue_day]:checked').val())=="undefined"){alert('[결제정보]출금일을 선택해 주세요');return false;}
		
		if (isNull(frm.phone_partydue_money.value)) {
			alert('[결제정보]약정금액을 입력해주세요.');
			return false;		
		}
		
		if (!isNumber(frm.phone_partydue_money)) {
			return false;		
		}
		if($('input[name=phone_partydue_print]:checked').val()=="undefined" || typeof($('input[name=phone_partydue_print]:checked').val())=="undefined"){alert('[결제정보]당비영수증발급처를 선택해 주세요');return false;}
		
		frm.partydue_name.value = $('input[name=phone_partydue_name]:checked').val();//frm.phone_partydue_name.value;
		frm.partydue_money.value = frm.phone_partydue_money.value;
		//frm.partydue_number.value = frm.phone_partydue_number1.value;
		frm.partydue_day.value = $('input[name=phone_partydue_day]:checked').val();//frm.partydue_day.value;
		frm.partydue_valid_date.value = "";//카드
		frm.partydue_print.value = $('input[name=phone_partydue_print]:checked').val() ;//frm.partydue_print.value;
		frm.card_partydue_valid_date_y.value="";
		frm.card_partydue_valid_date_m.value="";
	}

	if(frm.already_partydue_money_always.value!="Y" && parseInt(pay_radio)!=0){//평생당원이 아니고 직접납부도 아니면 결제금액 체크
		if (isNull(frm.partydue_money.value)) {
			alert('[결제정보]약정금액을 입력해주세요.');
			return false;		
		}
		
		if (!isNumber(frm.partydue_money)) {
			return false;		
		}
	
		if(frm.already_partydue_money_5000.value=="Y"){
			if (parseInt(frm.partydue_money.value)<5000) {
				alert('약정금액은 5000원 이상을 입력해 주세요.');
				return ;		
			}
		}else{
			if (parseInt(frm.partydue_money.value)<10000) {
				alert('[결제정보]약정금액은 10000원 이상을 입력해 주세요.');
				return false;		
			}
		}
	}




	
	var rdo_committee_flag = $('input[name=committee_flag]:checked').val();
	var rdo_delivery_gubun = $('input[name=delivery_gubun]:checked').val();
	var rdo_magazine_div = $('input[name=magazine_div]:checked').val();
	var rdo_magazine_tf = $('input[name=magazine_tf]:checked').val();
	if(rdo_committee_flag=="2" || rdo_delivery_gubun=="2" || (rdo_magazine_div=="2" && rdo_magazine_tf=="Y")){	

		if (isNull(frm.ozipcode01.value)) {
			alert('[당원정보]직장주소(우편번호앞)를 입력해주세요.');
			//frm.ozipcode1.focus();
			return false;		
		}
		if (isNull(frm.ozipcode02.value)) {
			alert('[당원정보]직장주소(우편번호뒤)를 입력해주세요.');
			//frm.ozipcode2.focus();
			return false;		
		}

		if (!isNumber(frm.ozipcode01)) {
			alert('[당원정보]직장주소(우편번호앞)를 (숫자)입력해주세요.');
			//frm.ozipcode1.focus();
			return false;		
		}
		if (!isNumber(frm.ozipcode02)) {
			alert('[당원정보]직장주소(우편번호뒤)를 (숫자)입력해주세요.');
			//frm.ozipcode2.focus();
			return false;		
		}

		if (isNull(frm.oaddr01.value)) {
			alert('[당원정보]직장주소를 입력해주세요.');
			//frm.oaddr1.focus();
			return false;		
		}

		if (isNull(frm.oaddr02.value)) {
			alert('[당원정보]직장주소(상세)를 입력해주세요.');
			//frm.oaddr2.focus();
			return false;		
		}

	}

	
	if($('input[name=magazine_tf]:checked').val()=="undefined" || typeof($('input[name=magazine_tf]:checked').val())=="undefined"){alert('[부가정보]기관지 구독여부를 선택해 주세요');return false;}
	
	if(rdo_magazine_tf=="Y"){	
	if($('input[name=magazine_div]:checked').val()=="undefined" || typeof($('input[name=magazine_div]:checked').val())=="undefined"){alert('[부가정보]기관지 구독주소를 선택해 주세요');return false;}
	}



	
	var act_check=document.getElementsByName("activitycate[]");
	var chk_cnt=0;
	for (i=0;i<act_check.length;i++) {
		if(act_check.item(i).checked==true) {
			chk_cnt++;
		}
	}

	if (chk_cnt == 0) {
		alert("정당활동 참여희망분야를 선택해 주세요");
		return false;		
	} 

/*
	if ( frm.user_id.value == "" ){
		alert( "아이디를 입력해 주세요" );
		frm.user_id.focus();
		return false;
	}
	if (isNull(frm.user_check_dupid.value)) {
		alert('아이디 중복체크해주세요.');
		return false;		
	}

	if (isNull(frm.user_nick.value)) {
		alert('닉네임를 입력해주세요.');
		frm.user_nick.focus();
		return false;		
	}
	if (isNull(frm.user_check_dupnick.value)) {
		alert('닉네임 중복체크해주세요.');
		return false;		
	}



	if ( frm.user_pw.value == "" ){
		alert( "비밀번호를 입력해 주세요" );
		frm.user_pw.focus();
		return false;
	}

	if ( frm.user_pw.value != frm.user_pw2.value ){
		alert( "비밀번호가 다릅니다." );
		frm.user_pw.value = frm.user_pw2.value = "";
		frm.user_pw.focus();
		return false;
	}

	

	if (isNull(frm.birth_y.value)) {
		alert('생년를 입력해주세요.');
		frm.birth_y.focus();
		return false;		
	}

	if (isNull(frm.birth_m.value)) {
		alert('생년를 입력해주세요.');
		frm.birth_m.focus();
		return false;		
	}

	if (isNull(frm.birth_d.value)) {
		alert('생년를 입력해주세요.');
		frm.birth_d.focus();
		return false;		
	}


	if (isNull(frm.email1.value)) {
		alert('이메일를 입력해주세요.');
		frm.email1.focus();
		return false;		
	}

	if (isNull(frm.email2.value)) {
		alert('이메일를 입력해주세요.');
		frm.email2.focus();
		return false;		
	}

	if (isNull(frm.zipcode01.value)) {
		alert('주소를 입력해주세요.');
		frm.zipcode01.focus();
		return false;		
	}
	if (isNull(frm.zipcode02.value)) {
		alert('주소를 입력해주세요.');
		frm.zipcode02.focus();
		return false;		
	}

	if (isNull(frm.addr1.value)) {
		alert('주소를 입력해주세요.');
		frm.addr1.focus();
		return false;		
	}

	if (isNull(frm.addr2.value)) {
		alert('주소를 입력해주세요.');
		frm.addr2.focus();
		return false;		
	}

	if (isNull(frm.phone1.value)) {
		alert('전화번호를 입력해주세요.');
		frm.phone1.focus();
		return false;		
	}

	if (isNull(frm.phone2.value)) {
		alert('전화번호를 입력해주세요.');
		frm.phone2.focus();
		return false;		
	}

	if (isNull(frm.phone3.value)) {
		alert('전화번호를 입력해주세요.');
		frm.phone3.focus();
		return false;		
	}
*/

	frm.target="";
	frm.action="member_write.php";
	return true;
}


//당원 정보수정페이지에서 버턴
function modMemberPartyNext(n){
	
		$("#modify_sub01").css("display","none");
		$("#modify_sub02").css("display","none");
		$("#modify_sub03").css("display","none");
		$("#modify_sub04").css("display","none");
		
		$("#modify_sub0"+n).css("display","block");
	
}



//당원 가입페이지에서 가입버턴
function modMember() {
	
	var frm = document.gform;
	
	if (frm.hidden_user_nick.value.trim()!=frm.user_nick.value.trim()){
		if(isNull(frm.user_check_dupnick.value)) {
			alert('닉네임 중복 확인해주세요.');
			return false;		
		}
	}
	if(!regMember())return false;

	frm.target="";
	frm.action="member_modify.php";

	
	alert('회원정보 수정합니다.');
	
	return true;
}



//당원 수정페이지에서 가입버턴
function modMemberParty() {
	
	var frm = document.gform;
	
	if(!regMemberParty())return false;
	if (frm.hidden_user_nick.value.trim()!=frm.user_nick.value.trim()){
		if(isNull(frm.user_check_dupnick.value)) {
			alert('닉네임 중복 확인해주세요.');
			return false;		
		}
	}

	frm.target="";
	frm.action="member_modify.php";


	alert('당원정보 수정합니다.');
	
	return true;
}