//ÆË¾÷Ã¢

function topWindow(url){
popup = window.open(url,"print","height=450,width=600,scrollbars=yes");
}

function leanWindow1(url){
popup = window.open(url,"lean_des","height=400,width=560,scrollbars=yes");
}
function movWindow(url){
popup = window.open(url,"movie","height=310,width=490,scrollbars=no");
}

//ÆË¾÷
function NewWindow(mypage, myname, w, h, scroll) {
var winl = (screen.width - w) / 2;
var wint = (screen.height - h) / 2;
winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
win = window.open(mypage, myname, winprops)
if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

//ÆË¾÷
function NewWindow_with_param(mypage, myname, w, h, scroll, param) {
var winl = (screen.width - w) / 2;
var wint = (screen.height - h) / 2;
winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
win = window.open(mypage+"?id="+param, myname, winprops)
if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function isValidEmail(input) {
//    var format = /^(\S+)@(\S+)\.([A-Za-z]+)$/;
    var format = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
    return isValidFormat(input,format);
}
	
function isValidFormat(input,format) {
    if (input.value.search(format) != -1) {
        return true; //¿Ã¹Ù¸¥ Æ÷¸Ë Çü½Ä
    }
    return false;
}

function containsCharsOnly(input,chars) {
    for (var inx = 0; inx < input.value.length; inx++) {
       	if (chars.indexOf(input.value.charAt(inx)) == -1)
          	return false;
    }
    return true;	
}

function isNumber(input) {
    var chars = "0123456789";
    return containsCharsOnly(input,chars);
}

function isPhoneNumber(input) {
    var chars = "0123456789-";
    return containsCharsOnly(input,chars);
}

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
