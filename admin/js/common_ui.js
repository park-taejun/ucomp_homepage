/* 전역 변수 */
var ua = navigator.userAgent;
var windowWidth = $(window).width();
var windowHeight = $(window).height();
var isMobile;



/* useagent check */
function userAgentChk(){
	if(ua.match(/iPhone|iPod|LG|Android|SAMSUNG|Samsung/i) != null){
		if (windowWidth > 720){
			$("body").addClass("device").addClass("tablet");
			switch(window.orientation){ 
				case -90:
				$("body").addClass("tablet_landscape");
				$("body").addClass("pc").removeClass("tablet");
				break;
				case 90:
				$("body").addClass("tablet_landscape");
				$("body").addClass("pc").removeClass("tablet");
				break;
				case 0:
				$("body").addClass("tablet_portrait");
				$("body").removeClass("pc").removeClass("normal").addClass("tablet");
				break;
				case 180:
				$("body").addClass("tablet_portrait");
				$("body").removeClass("pc").removeClass("normal").addClass("tablet");
				break;
			 }
		}else{
			$("body").addClass("mobile").addClass("device");
			switch(window.orientation){  
				case -90:
				$("body").addClass("mobile_landscape")
				break;
				case 90:
				$("body").addClass("mobile_landscape");
				break;
				case 0:
				$("body").addClass("mobile_portrait");
				break;
				case 180:
				$("body").addClass("mobile_portrait");
				break;
			 }
		}
		isMobile = true;
	}else if (ua.match(/iPad|GallaxyTab/i) != null){
		$("body").addClass("device").addClass("tablet");		
		switch(window.orientation){ 
			case -90:
			$("body").addClass("tablet_landscape");
			$("body").addClass("pc").removeClass("tablet");
			break;
			case 90:
			$("body").addClass("tablet_landscape");
			$("body").addClass("pc").removeClass("tablet");
			break;
			case 0:
			$("body").addClass("tablet_portrait");
			$("body").removeClass("pc").removeClass("normal").addClass("tablet");
			break;
			case 180:
			$("body").addClass("tablet_portrait");
			$("body").removeClass("pc").removeClass("normal").addClass("tablet");
			break;
		 }
		isMobile = true;
	}else{
		bodyClassChange();

		$(window).resize(function(){
			windowWidth = $(window).width();
			windowHeight = $(window).height();
			bodyClassChange();
		}).resize();

		if(ua.indexOf("MSIE 8.0") > -1 || ua.indexOf("Trident/4.0") > -1){ //IE8 이하일 경우
			$("body").addClass("pc").addClass("pc_ie8");
			if(ua.indexOf("Windows NT 6.2") > -1){
			}else if (ua.indexOf("Windows NT 6.1") > -1){			
				$("body").addClass("pc").addClass("pc_ie8").addClass("w7"); //window7, IE8
			}else if (ua.indexOf("Windows NT 5.1") > -1){
				$("body").addClass("pc").addClass("pc_ie8").addClass("xp"); //windowXP, IE8
			}
		}else if(ua.indexOf("MSIE 7.0") > -1 || ua.indexOf("MSIE 6.0") > -1){
			$("body").addClass("pc").addClass("pc_ie8");
		}else if(ua.indexOf("Trident") > -1){
			$("body").addClass("pc").addClass("ie");
		}else{ //IE9 PC 
			if (ua.indexOf("Chrome") > -1){
				$("body").addClass("pc").addClass("chrome");
			}else if(ua.indexOf("Mac") > -1){
				$("body").addClass("mac");
			}else{
				$("body").addClass("pc");
			}
		}
	}
	isMobile = false;
}
userAgentChk();

function bodyClassChange(){
	if (windowWidth > 1201){
		isMobile = false;
		$("body").removeClass("mobile_portrait").removeClass("mobile").removeClass("tablet").removeClass("smallbrowser").addClass("normal");
		$(".contentsarea").css("min-height", (windowHeight-$(".toparea").innerHeight())+"px");
	}else if (windowWidth <= 1200 && windowWidth > 1025){
		isMobile = false;
		$("body").removeClass("mobile_portrait").removeClass("normal").removeClass("mobile").removeClass("tablet").addClass("smallbrowser");
		$(".contentsarea").css("min-height", (windowHeight-$(".toparea").innerHeight()-$(".bottomarea").innerHeight())+"px");
	}else if (windowWidth <= 1024 && windowWidth > 769){
		isMobile = true;
		$("body").removeClass("mobile_portrait").removeClass("normal").removeClass("mobile").removeClass("smallbrowser").addClass("tablet");
		$(".contentsarea").css("min-height", (windowHeight-$(".toparea").innerHeight()-$(".bottomarea").innerHeight())+"px");

	}else if (windowWidth <= 768){
		isMobile = true;
		$("body").removeClass("mobile_portrait").removeClass("normal").removeClass("tablet").removeClass("smallbrowser").addClass("mobile");
		if (windowWidth < 481) {
			$("body").addClass("mobile_portrait");
		}
	}
}

function firstLoad(){
	setTimeout(function(){
		$("#wrap").animate({opacity:1}, 500); 
	}, 200);
}
firstLoad();

$(window).resize(function(){	
	windowWidth = $(window).width();
	windowHeight = $(window).height();	
}).resize();

if ($("body").hasClass("mobile") == true || $("body").hasClass("tablet") == true || $("body").hasClass("device") == true){
	isMobile = true;
}else{
	isMobile = false;
}

$(window).scroll(function() { 
	scrollFixed();
});

function scrollFixed(){
	if ($("body").hasClass("smallbrowser") || $("body").hasClass("pc")){
		if ($(window).scrollTop() > 120){
			$(".pagenavi").addClass("topFixed");
		}else{
			$(".pagenavi").removeClass("topFixed");
		}
	}
}

function modalView(modalName){
	var modalWidth = $(".modalpop .popupwrap."+modalName).innerWidth()/2; 
	var modalHeight = $(".modalpop .popupwrap."+modalName).innerHeight()/2;
	$(".transparents-layer").remove();
	$(".popupwrap").removeClass("active").css("left", "-99999px").css("top", "-99999px").css("opacity", "0");
	$(".modalpop").show().css({"top" : 0, "left": 0});
	$("body").append("<div class='transparents-layer' style='background:#000; opacity:0.7'></div>");
	$(".popupwrap."+modalName).addClass("active").css("top", "40%").css("left","50%").css("margin-top", -($(".modalpop .popupwrap."+modalName).innerHeight()/2.7)+"px").css("margin-left", -modalWidth+"px").animate({opacity:1}, 500);
	$(".transparents-layer").attr("onclick", "modalHide('"+modalName+"')");
	$(".popupwrap."+modalName).addClass("active");
}

function modalHide(modalName){
	$(".popupwrap."+modalName).animate({opacity:0}, 400, function(){
		$(".popupwrap."+modalName).css("top", "-99999px").css("left","-99999px");
		$(".modalpop").css({"top" : "-99999px", "left": "-99999px"});
		$(".transparents-layer").animate({opacity:0}, 400, function(){
			$(this).remove();
		});
		$(".popupwrap."+modalName).removeClass("active");
	});
}

function commonTab(tabParent, tabName){
	$("."+tabParent+" ul.tabbox li").removeClass("on");
	$("."+tabParent+" ul.tabbox li."+tabName).addClass("on");
	$("."+tabParent+" .tab-hiddencontents").removeClass("on");
	$("."+tabParent+" .tab-hiddencontents."+tabName).addClass("on");
}

function lnbToggle(Idx) {
	if ($(".header .innerbox > ul > li:eq("+Idx+")").hasClass("on") == false){
		$(".header .innerbox > ul > li:eq("+Idx+")").addClass("on");	
		$(".header .innerbox > ul > li:eq("+Idx+") > ul").slideDown();	
	}else{
		$(".header .innerbox > ul > li:eq("+Idx+")").removeClass("on");	
		$(".header .innerbox > ul > li:eq("+Idx+") > ul").slideUp();
	}
}
