/**
 * buiFullPage
 * 
 * @ProjectDescription
 * @author codenamic@gmail.com
 * @version 1.1
 * 
 * Released on 2022-02-01
 * Copyright (c) 2018,
 *
 * Licensed under the MIT license.
 * http://opensource.org/licenses/MIT
 * 
 */


function optimizeAnimation(callback) {
	let ticking = false;

	return () => {
		if (!ticking) {
			ticking = true;
			requestAnimationFrame(() => {
				callback();
				ticking = false;
			});
		}
	};
}



(function (root, factory) {
	if ( typeof define === 'function' && define.amd ) {
		define([], function () {
			return factory(root);
		});
	} else if ( typeof exports === 'object' ) {
		module.exports = factory(root);
	} else {
		root.buiFullPage = factory(root);
	}
})(typeof global !== 'undefined' ? global : typeof window !== 'undefined' ? window : this, function (window) {

	'use strict';

	var defaults = {
		scroll: true,

		// general
		container: 'body',
		activeClass: 'active',
		currentClass: 'current',

		initial: 0,

		pagination: true,
		paginationListClassName: 'bui-full-page-pagination-list',
		paginationItemClassName: 'bui-full-page-pagination-item',
		paginationTextClassName: 'bui-full-page-pagination-text',

		/* callback */
		onloadCallBack: function() {return false;},
		eventBeforeCallBack: function() {return false;},
		eventAfterCallBack: function() {return false;},
		activeBeforeCallBack: function() {return false;},
		activeAfterCallBack: function() {return false;},
		inactiveBeforeCallBack: function() {return false;},
		inactiveAfterCallBack: function() {return false;}
	};

	//  Merge two or more objects together.
	var extend = function () {
		var merged = {};
		Array.prototype.forEach.call(arguments, function (obj) {
			for (var key in obj) {
				if (!obj.hasOwnProperty(key)) return;
				merged[key] = obj[key];
			}
		});
		return merged;
	};

	// Create the Constructor object
	let Constructor = function(selector, options) {
		
		// Merge user options with defaults
		settings = extend(defaults, options || {});
		
		var publicAPIs = {};
		var settings;

		publicAPIs.settings = settings;
		publicAPIs.pageIndex = {};

		publicAPIs.currentIndex = 0;
		publicAPIs.lastScrollY = window.scrollY;
		publicAPIs.isScrolling;
		publicAPIs.scrollEnd = true;


		// goToPageIndex
		publicAPIs.goToPageIndex = function(index) {
			if (index < 0) {
				publicAPIs.currentIndex = 0;
			} else if (index > (Object.entries(publicAPIs.pageIndex).length - 1)) {
				publicAPIs.currentIndex = Object.entries(publicAPIs.pageIndex).length - 1;
			} else {
				publicAPIs.currentIndex = index;
			}

			window.scroll({
				top: publicAPIs.pageIndex[publicAPIs.currentIndex].offsetY,
				behavior: "smooth"
			});

			publicAPIs.paginationActions(publicAPIs.currentIndex);

			if (publicAPIs.currentIndex == (Object.entries(publicAPIs.pageIndex).length - 1)) {
				document.querySelector("html").classList.remove("disabled-scroll");
			} else {
				document.querySelector("html").classList.add("disabled-scroll");
			}
		}

		// goToPageAnchor
		publicAPIs.goToPageAnchor = function(name) {
		}

		// goToPageName
		publicAPIs.goToPageName = function(name) {
		}

		// paginationActions
		publicAPIs.paginationActions = function(currentItem) {
			Array.prototype.forEach.call(document.querySelectorAll('.' + settings.paginationListClassName + ' .' + settings.paginationItemClassName), function(siblings, i) {
				currentItem == i ? siblings.classList.add(settings.currentClass) : siblings.classList.remove(settings.currentClass);
			});
		};

		// update
		publicAPIs.update = function() {
			let checkElement = document.querySelector(selector);
			if (!checkElement) return;

			let toggleTargets = document.querySelectorAll(selector);
			toggleTargets.forEach(function(elem, index, array) {
				publicAPIs.pageIndex[index] = {
					index: index,
					id: elem.id,
					name: elem.dataset.buiFullPage,
					element: elem,
					width: elem.offsetWidth,
					height: elem.offsetHeight,
					offsetX: elem.offsetLeft,
					offsetY: elem.offsetTop,
				}
			});

			// if (settings.scroll === true) scrollActions(settings, publicAPIs.pageIndex);
			if (settings.pagination === true) paginationSetup(settings, publicAPIs.pageIndex);

			setTimeout(function() {
				publicAPIs.goToPageIndex(publicAPIs.currentIndex);
			}, 100);

			window.addEventListener("wheel", function (event) {

				publicAPIs.scrollEnd = false;
	
				// Clear our timeout throughout the scroll
				window.clearTimeout(publicAPIs.isScrolling);
				
				// Set a timeout to run after scrolling ends
				publicAPIs.isScrolling = setTimeout(function() {
					eventHandleWheel(event);
					publicAPIs.scrollEnd = true;
					publicAPIs.lastScrollY = window.scrollY
				}, 600);
			}, false);
		
	
			document.addEventListener("keydown", function(event) {
				const key = event.key;
	
				switch (key) {
					case "PageDown":
						event.preventDefault();
						publicAPIs.goToPageIndex(publicAPIs.currentIndex + 1);
					break;
	
					case "PageUp":
						event.preventDefault();
						publicAPIs.goToPageIndex(publicAPIs.currentIndex - 1);
	
					break;
	
					case "Home":
						event.preventDefault();
						publicAPIs.goToPageIndex(0);
					break;
				}
			});
		};

		// Listen for scroll events
		// window.addEventListener('scroll', function (event) {
		// 	if (publicAPIs.scrollEnd) {				
		// 		eventHandlerScroll();
		// 		console.log(publicAPIs.scrollEnd +  ", " + publicAPIs.currentIndex);
		// 	}

		// 	publicAPIs.scrollEnd = false;

		// 	// Clear our timeout throughout the scroll
		// 	window.clearTimeout(publicAPIs.isScrolling);

		// 	// Set a timeout to run after scrolling ends
		// 	publicAPIs.isScrolling = setTimeout(function() {
		// 		console.log(publicAPIs.scrollEnd);
		// 		publicAPIs.scrollEnd = true;
		// 		publicAPIs.lastScrollY = window.scrollY
		// 	}, 600);
		// }, false);

		// function eventHandlerScroll() {
		// 	publicAPIs.scrollEnd = false;
			
		// 	if (publicAPIs.lastScrollY > window.scrollY) {
		// 		console.log("up");
		// 		scrollUpActions(settings);
		// 	} else {
		// 		console.log("down");
		// 		scrollDownActions(settings);
		// 	}
		// }

		function eventHandleWheel(event) {
			publicAPIs.scrollEnd = false;
			
			if (event.deltaY < 0) {
				console.log("up");
				scrollUpActions(settings);
			} else {
				console.log("down");
				scrollDownActions(settings);
			}
		}

		function scrollUpActions(settings) {
			if (publicAPIs.currentIndex > 0) {
				publicAPIs.goToPageIndex(publicAPIs.currentIndex - 1);
			}
		};

		function scrollDownActions(settings) {
			if (publicAPIs.currentIndex < (Object.entries(publicAPIs.pageIndex).length - 1)) {
				publicAPIs.goToPageIndex(publicAPIs.currentIndex + 1);
			}
		};

		// paginationSetup
		function paginationSetup(settings, toggleThis) {
			let paginationContainer = document.querySelector('.' + settings.paginationListClassName);
			if (!paginationContainer) {
				paginationContainer = document.createElement('div');
				paginationContainer.className = settings.paginationListClassName;
	
				Array.prototype.forEach.call(Object.entries(toggleThis), function(item, i) {
					let paginationItems;
					let paginationItemsText;
	
					paginationItems = document.createElement('a');
					paginationItems.className = settings.paginationItemClassName;
					paginationItems.setAttribute("href", "#" + publicAPIs.pageIndex[i].id);
	
					paginationItemsText = document.createElement('span');
					paginationItemsText.className = settings.paginationTextClassName;
					paginationItemsText.innerHTML = i;
	
					paginationItems.appendChild(paginationItemsText);
					paginationContainer.appendChild(paginationItems);
	
					if (i == settings.initial) paginationItems.classList.add(settings.currentClass);
	
					paginationItems.addEventListener("click", function(e) {
						e.preventDefault();
						buiFullPages.goToPageIndex(i);
					});
				});
				
				document.querySelector(settings.container).appendChild(paginationContainer);
			};
		};

		// Initialize the instance
		var init = function () {
			// Setup the DOM
			publicAPIs.update();
			window.addEventListener('resize', publicAPIs.update, false);
		};

		// Initialize and return the Public APIs
		init();
		return publicAPIs;
	};

	// Return the Constructor
	return Constructor;
});