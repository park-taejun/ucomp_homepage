/* 전역 변수 */
var ua = navigator.userAgent;
var windowWidth = window.innerWidth;
var windowHeight = window.innerHeight;
var isMobile;

/* useagent check */
function userAgentChk(){
	/* device check */
	if(ua.match(/iPhone|iPod|iPad|Android|LG|SAMSUNG|Samsung|GallaxyTab/i) != null){
		$("body").addClass("device");		
	}else if(ua.indexOf("Mac") > -1){
		$("body").addClass("mac");
	}else{
		$("body").addClass("pc");
	}
		
	/* browser check */
	if(ua.indexOf('MSIE') > -1){
		if(ua.indexOf("MSIE 7.0") > -1){
			$("body").addClass("ie7");
		}else if(ua.indexOf("MSIE 8.0") > -1){
			$("body").addClass("ie8");
		}else if(ua.indexOf("MSIE 9.0") > -1){
			$("body").addClass("ie9");
		}else if(ua.indexOf("MSIE 10.0") > -1){
			$("body").addClass("ie10");
		}
	}else if(ua.indexOf("rv:11.0") > -1){
		$("body").addClass("ie11");
	}else if(ua.indexOf("Edge") > -1){
		$("body").addClass("edge");
	}else if (ua.indexOf("Chrome") > -1 || ua.indexOf("CriOS") > -1){
		$("body").addClass("chrome");
	}else if (ua.indexOf("Firefox") > -1){
		$("body").addClass("firefox");
	}else if (ua.indexOf("OPT") > -1){
		$("body").addClass("opera");
	}else if (ua.indexOf("NAVER") > -1){
		$("body").addClass("naver");
	}else if (ua.indexOf("KAKAOTALK") > -1){
		$("body").addClass("kakao");
	}else if (ua.indexOf("SamsungBrowser") > -1){
		$("body").addClass("samsungbrowser");
	}else if (ua.indexOf("Safari") > -1){
		$("body").addClass("safari");
	}
}

/* 해상도 check */
function bodyClassChange(){
	/* display check */
	if (windowWidth > 1440){
		isMobile = false;
		$("body").removeClass("smallbrowser").removeClass("tablet").removeClass("mobile").addClass("normal");
	}else if (windowWidth <= 1440 && windowWidth > 1200){
		isMobile = false;
		$("body").removeClass("normal").removeClass("tablet").removeClass("mobile").addClass("smallbrowser");
	}else if (windowWidth <= 1200 && windowWidth > 750){
		isMobile = true;
		$("body").removeClass("normal").removeClass("smallbrowser").removeClass("mobile").addClass("tablet");
	}else if (windowWidth <= 750){
		isMobile = true;
		$("body").removeClass("normal").removeClass("smallbrowser").removeClass("tablet").addClass("mobile");
	}
	
	/* orientation check */
	switch(window.orientation){ 
		case -90:
		$("body").removeClass("portrait").addClass("landscape");
		break;
		case 90:
		$("body").removeClass("portrait").addClass("landscape");
		break;
		case 0:
		$("body").removeClass("landscape").addClass("portrait");
		break;
		case 180:
		$("body").removeClass("landscape").addClass("portrait");
		break;
	}
	if(!$("body").is(".device")){
		if((windowWidth/windowHeight) > 1) { //해상도 가로세로 비율이 1보다 클 경우 가로모드
			$("body").removeClass("portrait").addClass("landscape");
		}else{
			$("body").removeClass("landscape").addClass("portrait");
		}
	}
}

function firstLoad(){
	setTimeout(function(){
		$("#wrap").animate({opacity:1}, 500); 
	}, 200);
}

function windowLeftScoll(){
	var winSc = $(window).scrollLeft();
	$('.header').css('left',-winSc);
}

/* 모달팝업 보이기 */ 
function modalView(modalName, parentName){
	var modalWidth;
	var modalHeight;
	
	if (!parentName) {
		$(".transparents-layer").remove();	
		$(".popupwrap").removeClass("active").css("left", "-99999rem").css("top", "-99999rem").css("opacity", "0");
		$(".modalpop").css({"top": 0, "left":0, "opacity":1});
	}
	
	if ($("." + modalName).outerHeight()  > windowHeight*0.8) {
		$("." + modalName + " .popcontents").css({ height: windowHeight * 0.85 - 60 , overflowY: 'auto' });
	}

	modalWidth = $(".popupwrap."+modalName).innerWidth()/2;
	modalHeight = $(".popupwrap." + modalName).innerHeight()/2;	
	
	if(isMobile) {
		modalWidth = (window.innerWidth*0.95)/2;
	}

	if (parentName){
		$(".popupwrap." + parentName).removeClass("active");
		$(".popupwrap." + parentName).append("<div class='transparents-layer' style='position:absolute; z-index:111; opacity:0.5'></div>");			
		if(isMobile){ 
			$(".popupwrap." + modalName).css({top:"10%", left: "50%", marginTop:0, marginLeft: -modalWidth});
		}else{
			$(".popupwrap." + modalName).css({ top: "50%", left: "50%", marginTop: -modalHeight, marginLeft: -modalWidth, zIndex:"110" }).animate({ opacity: 1 }, 500);
		}
	}else{
		$("body").append("<div class='transparents-layer'></div>");

		if(isMobile){ 
			$(".popupwrap." + modalName).css({top:"10%", left: "50%", marginTop:0, marginLeft: -modalWidth});
		}else{
			$(".popupwrap." + modalName).css({ top: "50%", left: "50%", marginTop: -modalHeight, marginLeft: -modalWidth});
		}
		$(".transparents-layer").attr("onclick", "modalHide('"+modalName+"')");			
	}
	$(".transparents-layer").on('scroll touchmove mousewheel', function(e) { //배경 스크롤 방지
		e.preventDefault();
	});
	$(".popupwrap."+modalName).animate({ opacity: 1 }, 500).addClass("active");	
	$("body").css({overflow:"hidden"});
}

/* 모달팝업 숨기기 */ 
function modalHide(modalName, parentName){
	$(".popupwrap."+modalName).animate({opacity:0}, 400, function(){
		if (!parentName) {
			$(".popupwrap."+modalName).css("top", "-99999rem").css("left","-99999rem");
			$(".modalpop").css({"top" : "-99999rem", "left": "-99999rem", "opacity":0});
			$(".transparents-layer").animate({opacity:0}, 400, function(){
				$(this).remove();
			});
			if($("body").attr("id") == "main"){
				$("body").css("overflow", "hidden");
			}else{
				$("body").css({overflow:"auto"});
			}
		}else{
			$(".popupwrap."+parentName+ " .transparents-layer").remove();
			$(".popupwrap."+modalName).css("top", "-99999rem").css("left","-99999rem");
			$(".popupwrap."+modalName).removeClass("active");
		}
	});
	
}

function modalResize(){
	if ($(".popupwrap.active").length > 0){
		var	popHeight = $(".popupwrap.active").innerHeight();

		if (popHeight >= windowHeight*0.8){	
			$(".popupwrap.active .popcontents").css({ height: windowHeight * 0.8 - 60 , overflowY: 'auto' });
			
		}else{
			$(".popupwrap.active .popcontents").css({height:"auto"});
			popHeight = $(".popupwrap.active").innerHeight();
			if (popHeight >= windowHeight*0.8){	
				$(".popupwrap.active .popcontents").css({height:windowHeight * 0.8, paddingRight:20});
			}				
		}

		var modalWidth = $(".modalpop .popupwrap.active").innerWidth()/2; 
		var modalHeight = $(".modalpop .popupwrap.active").innerHeight()/2;
		
		if(isMobile){ 
			$(".popupwrap.active").css({top:"10%", left: "50%", marginTop:0, marginLeft: -modalWidth});
		}else{
			$(".popupwrap.active").css({ top: "50%", left: "50%", marginTop: -modalHeight, marginLeft: -modalWidth});			
			if ($(".pop-main")){
				$(".popupwrap.active").css({top:"30rem", left:"20rem", marginTop:0, marginLeft:0});
			}
		}
	}
}	

function commonTab(tabParent, tabName){
	$("."+tabParent+" ul.tabbox li").removeClass("on");
	$("."+tabParent+" ul.tabbox li."+tabName).addClass("on");
	$("."+tabParent+" .tabcontents").removeClass("on");
	$("."+tabParent+" .tabcontents."+tabName).addClass("on");
	$('.slick-slider').slick('setPosition');
}

/* 전체메뉴 */
function allmenuToggle(){
	if($(".header").is(".expend")){
		//$("body").css({overflowY:"auto"});
		$(".header").removeClass("expend");
	}else{
		$(".header").addClass("expend")
		//$("body").css({overflowY:"hidden"});
	}
}

function goTop() {
	var topBtn = $('.btn-top');
	$(window).scroll(function() {
		topBtn.stop().animate({opacity : '0'}, 100);
		clearTimeout($.data(this, 'scrollTimer'));
		$.data(this, 'scrollTimer', setTimeout(function() {
			topBtn.stop().animate({opacity : '1'});
		}, 300));
	});
	topBtn.on('click', function () {
		$(window).scrollTop(0);
	});
}

$(document).ready(function(){
	userAgentChk();
	bodyClassChange();
    firstLoad();
    goTop();
});

$(window).resize(function() {
	if (window.innerWidth != windowWidth || window.innerHeight != windowHeight) {

	windowWidth = window.innerWidth;
	windowHeight = window.innerHeight;

	bodyClassChange();
	modalResize();

	if(this.resizeTO){
		clearTimeout(this.resizeTO);
	}

	this.resizeTO = setTimeout(function(){
			$(this).trigger('resizeEnd');
		},100);
	}	
});

$(window).on("resizeEnd", function(){
});

$(window).scroll(function() { 
	windowLeftScoll();
});