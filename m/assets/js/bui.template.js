/**
 * @module 
 */
function findElement(selector, target, height) {
	var elem = document.querySelector(selector);
	if (!elem) return;

	document.querySelector(target).style.setProperty('padding-bottom', height);
}
// findElement('.content-util', '#page', '56rem');


/**
 * @module form buiFormCheckValue
 */
function buiFormCheckValue(formElem) {
	formElem.value.length > 0 ? formElem.classList.add('typed') : formElem.classList.remove('typed');
}

/**
 * @module form buiFormCancel
 */
function buiFormCancel(formElem) {
	if (formElem.readOnly) return;

	// check value
	buiFormCheckValue(formElem);
	formElem.addEventListener('input', function(e) {
		buiFormCheckValue(formElem);
	});

	// form util
	var formUtil = formElem.parentElement.querySelector('.form-func');
	if(!formUtil) {
		formUtil = document.createElement('span');
		formUtil.classList.add('form-func');
		formElem.parentElement.appendChild(formUtil);
	}

	// form cancel
	var formCancel = formElem.parentElement.querySelector('.form-cancel');
	if(!formCancel) {
		formCancel = document.createElement('span');
		formCancel.classList.add('form-cancel');
		formUtil.prepend(formCancel);
	}

	formElem.style.paddingRight = formUtil.offsetWidth + 'rem';

	var xStart = formUtil.offsetLeft + formCancel.offsetLeft;
	var yStart = formUtil.offsetTop + formCancel.offsetTop;
	var xEnd = xStart + formCancel.offsetWidth;
	var yEnd = yStart + formCancel.offsetHeight;

	formElem.addEventListener('mousemove', function(e) {
		if (formElem.classList.contains('typed')) {
			if (e.offsetX >= xStart && e.offsetX <= xEnd && e.offsetY >= yStart && e.offsetY <= yEnd) {
				formElem.style.setProperty('cursor', 'default');
			} else {
				formElem.style.removeProperty('cursor');
			}
		}
	});

	formElem.addEventListener('click', function(e) {
		if (formElem.classList.contains('typed')) {
			if (e.offsetX >= xStart && e.offsetX <= xEnd && e.offsetY >= yStart && e.offsetY <= yEnd) {
				formElem.value = '';
				formElem.classList.remove('typed');
				formElem.style.removeProperty('cursor');
			}
		}
	});
}








/**
 * @module form buiFormAfter
 */
function buiFormAfter() {
	var formElems = document.querySelectorAll('.form.module-a.checkbox, .form.module-a.radio');
	Array.prototype.forEach.call(formElems, function(formElem) {
		var formAfter = formElem.querySelector('.form-after');
		if(!formAfter) {
			var formAfter = document.createElement('span');
			formAfter.className = 'form-after';
			formElem.appendChild(formAfter);
		}
	});
}

if ((navigator.appName == 'Netscape' && navigator.userAgent.toLowerCase().indexOf('trident') != -1) || (navigator.userAgent.toLowerCase().indexOf("msie") != -1)) {
	buiFormAfter();
}

/**
 * @module form buiFormResize
 */
function buiFormResize(elem) {
	elem.parentNode.setAttribute('data-bui-form-value', elem.value);
	elem.value.length > 0 ? elem.parentNode.classList.add('typed') : elem.parentNode.classList.remove('typed');
};



/**
 * @module form buiFormResize
 */
function buiFormAddFile(inputEl) {
	var curFiles = inputEl.files;

	if (curFiles.length === 0) {
		inputEl.parentElement.classList.remove('typed');
		inputEl.parentElement.removeAttribute('data-bui-file-type');
		inputEl.parentElement.setAttribute('data-bui-file-name', inputEl.getAttribute('title'));
		inputEl.parentElement.removeAttribute('style');
		inputEl.focus();
	} else {
		inputEl.parentElement.classList.add('typed');
		inputEl.parentElement.setAttribute('data-bui-file-type', curFiles[0].type.split('/')[0]);
		inputEl.parentElement.setAttribute('data-bui-file-name', inputEl.files[0].name);
		inputEl.focus();


		if (curFiles[0].type.split('/')[0] === 'image') {
			inputEl.parentElement.setAttribute('style', 'background-image: url(' + URL.createObjectURL(curFiles[0]) + ')');
		} else {
			inputEl.parentElement.removeAttribute('style');
			inputEl.parentElement.removeAttribute('title');
		}
	}

	console.log();

	// inputEl.parentElement.querySelector('.form-clear').addEventListener('click', function () {
	// 	inputEl.value = null;
	// 	inputEl.parentElement.classList.remove('typed');
	// 	inputEl.parentElement.setAttribute('data-bui-placeholder', inputEl.getAttribute('title'));
	// 	inputEl.parentElement.removeAttribute('style');
	// 	inputEl.parentElement.removeAttribute('data-bui-file-type');
	// 	inputEl.focus();
	// });
};







/**
 * @module checkAspectRatio
 */
function checkAspectRatio(viewWidth, viewHeight) {
	let ratio = null;
	if (window.innerWidth / viewWidth > window.innerHeight / viewHeight) {
		ratio = 'landscape';
	} else if (window.innerWidth / viewWidth < window.innerHeight / viewHeight) {
		ratio = 'portrait';
	} else {
		ratio = 'portrait';
	}
	return ratio;
}

/**
 * @layout checkDevice
 */
function checkDevice() {
	if(navigator.userAgent.match(/Mobile/)) {
		document.querySelector('html').classList.remove('laptop');
		document.querySelector('html').classList.add('mobile');
	
	} else {
		document.querySelector('html').classList.remove('mobile');
		document.querySelector('html').classList.add('laptop');
	}
}
checkDevice();
// window.addEventListener('resize', function() {
// 	clearTimeout(timer);
// 	timer = setTimeout(function() {
// 		checkDevice();
// 	}, 400);
// });


/**
 * @layout checkScrollStart
 */


 function checkScrollStart() {
	var elem = document.querySelector('#header');
	if (!elem) return;

	window.scrollY > 0 ? elem.classList.add('scroll-start') : elem.classList.remove('scroll-start');
	window.addEventListener('scroll', function() {
		window.scrollY > 0 ? elem.classList.add('scroll-start') : elem.classList.remove('scroll-start');
	});
 }

/**
 * @layout Page Navigations
 */
const pageNavigations = new buiToggle('[data-bui-toggle="pageNavigations"]', {
	close: true,
	closeButtonClass: 'btn close',
	closeButtonText: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px" height="24px"><title>닫기</title><path d="M10.5382 12L3 4.46179L4.46179 3L12 10.5382L19.5382 3L21 4.46179L13.4618 12L21 19.5382L19.5382 21L12 13.4618L4.46179 21L3 19.5382L10.5382 12Z"/></svg>',
	closeButtonArea: '.section-util .button-area',
	reactTarget: 'html',
	reactTargetActiveClass: 'active-page-navi',
	focusin: true,
	onloadCallBack: function() {
		var thisToggle = this;
		var timer;
		window.addEventListener('resize', function() {
			clearTimeout(timer);
			timer = setTimeout(function() {
				if (window.screen.width > 1024 && thisToggle.active) {
					pageNavigations.inactive('pageNavigations');
					console.log(thisToggle);
				}
			}, 400);
		});
	},
	activeBeforeCallBack: function() {
		var toggleTarget = this.target;
		toggleTarget.classList.add('enabled');
	},	
	inactiveAfterCallBack: function() {
		var toggleTarget = this.target;
		setTimeout(function() {
			toggleTarget.classList.remove('enabled');
		}, 250);
	}
});

/**
 * @layout widgetGotoTop 
 */
 function widgetGotoTop(progressRange) {
	let gotoTop = document.querySelector('#gotoTop');
	if (!gotoTop) return;
	let value = gotoTop.querySelector('.value');
	let RADIUS = 32;
	let CIRCUMFERENCE = 2 * Math.PI * RADIUS;

	function progress(per) {
	  let progress = per / 100;
	  let dashoffset = CIRCUMFERENCE * (1 - progress);
	  value.style.strokeDashoffset = dashoffset;
	}

	gotoTop.addEventListener('click', function(e) {
		e.preventDefault();
		window.scrollTo({
			top: 0,
			left: 0,
			behavior: 'smooth'
		});
	});

	document.documentElement.scrollTop > 0 ? gotoTop.classList.add('active') : gotoTop.classList.remove('active');
	document.addEventListener('scroll', function() {
		progress((document.documentElement.scrollTop + document.body.scrollTop) / (document.documentElement.scrollHeight - document.documentElement.clientHeight) * 100);
		document.documentElement.scrollTop > 0 ? gotoTop.classList.add('active') : gotoTop.classList.remove('active');
	});	
	
	value.style.strokeDasharray = CIRCUMFERENCE;
	progress(progressRange);

	// footer와 만나면 toggle
	let localUtil = document.querySelector('#localUtil');
	if (!localUtil) return;
	setTimeout(function() {
		ScrollTrigger.create({
			trigger: '.page-body',
			start: 'bottom bottom',
			// markers: true,
		
			onEnter: function() {
				localUtil.classList.add('active');
			},
			onLeaveBack: function() {
				localUtil.classList.remove('active');
			},
		});
	}, 400);
}
widgetGotoTop(0);


/**
 * @module buiToggle contentsPopup
 */
 const contentsPopup = new buiToggle('[data-bui-toggle="contentsPopup"]', {
	inactiveButton: true,
	inactiveButtonClass: 'btn popup-close',
	inactiveButtonText: '닫기',
	inactiveButtonArea: '.popup-local',
	reactTarget: 'html',
	reactTargetActiveClass: 'active-content-popup',
	focusin: true,
	activeBeforeCallBack: function(toggleTarget) {
		toggleTarget.classList.add('enabled');
	},	
	inactiveAfterCallBack: function(toggleTarget) {
		setTimeout(function() {
			toggleTarget.classList.remove('enabled');
		}, 250);
	}
});

/**
 * @module buiToggle toastPopup
 */
 const toastPopup = new buiToggle('[data-bui-toggle="toastPopup"]', {
	inactiveButton: true,
	inactiveButtonClass: 'btn popup-close',
	inactiveButtonText: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px" height="24px"><title>닫기</title><path d="M6.37,8.48a1.24,1.24,0,0,1,1.76,0L12,12.35l3.87-3.87a1.25,1.25,0,1,1,1.76,1.77L12,15.88,6.37,10.25A1.25,1.25,0,0,1,6.37,8.48Z"/></svg>',
	inactiveButtonArea: '.popup-local',
	reactTarget: 'html',
	reactTargetActiveClass: 'active-content-popup',
	focusin: true,
	activeBeforeCallBack: function(toggleTarget) {
		toggleTarget.classList.add('enabled');
	},	
	inactiveAfterCallBack: function(toggleTarget) {
		setTimeout(function() {
			toggleTarget.classList.remove('enabled');
		}, 750);
	}
});
/**
 * @module buiToggle imageEnlarge
 */
 const imageEnlarge = new buiToggle('[data-bui-toggle="imageEnlarge"]', {
	inactiveButton: true,
	inactiveButtonClass: 'btn popup-close',
	inactiveButtonText: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32px" height="32px"><title>닫기</title><path d="M19,6.77A1.25,1.25,0,1,0,17.23,5L12,10.23,6.77,5A1.25,1.25,0,0,0,5,6.77L10.23,12,5,17.23A1.25,1.25,0,1,0,6.77,19L12,13.77,17.23,19A1.25,1.25,0,1,0,19,17.23L13.77,12Z"/></svg>',
	inactiveButtonArea: '.popup-local',
	reactTarget: 'html',
	reactTargetActiveClass: 'active-image-popup',
	focusin: true,
});

/**
 * @module buiExpand postItem
 */
const postItem = new buiExpand('.post-item[data-bui-expand="postItem"]', {
	accordion: false,
	activeClass: 'active',
	buttonClass: 'btn module-b style-a type-none normal-04 large flex symbol-rtl-fill-chevron-up',
	buttonText: '<span class="btn-text">자세히보기</span>',
	buttonActiveText: '<span class="btn-text">닫기</span>',
	buttonAppendTo: '.post-util .button-display .button-area',
	// targetClass: 'bui-expand-target',
});






 // 혜택 목록
// const benefitList = new buiExpand('.data-item[data-bui-expand="benefitItem"]', {
// 	accordion: false,
// 	activeClass: 'active',
// 	buttonClass: 'btn expand',
// 	buttonText: '<span class="btn-text">자세히보기</span>',
// 	buttonAppendTo: '.data-head',
// 	targetClass: 'bui-expand-target',
// 	onloadCallBack: function() {
// 		var target = this;
// 		var toggleTargetContents = target.querySelector('.data-side>.info-board');
// 		setTimeout(function() {
// 			target.setAttribute('style', '--bui-expand-target-height : ' + toggleTargetContents.offsetHeight + 'px;');
// 		}, 400);
// 		window.addEventListener('resize', function() {
// 			target.setAttribute('style', '--bui-expand-target-height : ' + toggleTargetContents.offsetHeight + 'px;');
// 		});
// 	},
// });


// if ('virtualKeyboard' in navigator) {
// 	alert('dd');
// }



// navigator.virtualKeyboard.addEventListener('geometrychanged', (event) => {
// 	alert('ddd');
//  });












// datepicker options setup
// Datepicker.locales.en = {
// 	days: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
// 	daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
// 	daysMin: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
// 	months: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
// 	monthsShort: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
// 	today: "오늘",
// 	clear: "삭제",
// 	titleFormat: "yyyy.mm",
// 	weekStart: 0
// };

// const datepickers = document.querySelectorAll('.form.datepicker .form-elem:not(:read-only)');
// datepickers.forEach(function(datepickerSelector) {	
// 	const datepicker = new Datepicker(datepickerSelector, {
// 		format: "yyyy-mm-dd",
// 		prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" focusable="false"><title>전월</title><path d="M15.52,6.37a1.24,1.24,0,0,1,0,1.76L11.65,12l3.87,3.87a1.25,1.25,0,1,1-1.77,1.76L8.12,12l5.63-5.63A1.25,1.25,0,0,1,15.52,6.37Z"></path></svg>',
// 		nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" focusable="false"><title>차월</title><path d="M8.48,17.63a1.24,1.24,0,0,1,0-1.76L12.35,12,8.48,8.13a1.25,1.25,0,1,1,1.77-1.76L15.88,12l-5.63,5.63A1.25,1.25,0,0,1,8.48,17.63Z"></path></svg>',
// 		weekStart: 1,
// 		autohide: true,
// 		todayHighlight: true,
// 	}); 
// });



// const datepicker = new Datepicker(document.getElementById('datepickerInline'), {
// 	format: "yyyy-mm-dd",
// 	prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" focusable="false"><title>전월</title><path d="M15.52,6.37a1.24,1.24,0,0,1,0,1.76L11.65,12l3.87,3.87a1.25,1.25,0,1,1-1.77,1.76L8.12,12l5.63-5.63A1.25,1.25,0,0,1,15.52,6.37Z"></path></svg>',
// 	nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" focusable="false"><title>차월</title><path d="M8.48,17.63a1.24,1.24,0,0,1,0-1.76L12.35,12,8.48,8.13a1.25,1.25,0,1,1,1.77-1.76L15.88,12l-5.63,5.63A1.25,1.25,0,0,1,8.48,17.63Z"></path></svg>',
// 	weekStart: 1,
// 	autohide: true,
// 	todayHighlight: true,
// });
