/**
 * buiToggle
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

// buiToggle
(function (root, factory) {
	if ( typeof define === 'function' && define.amd ) {
		define([], function () {
			return factory(root);
		});
	} else if ( typeof exports === 'object' ) {
		module.exports = factory(root);
	} else {
		root.buiToggle = factory(root);
	}
})(typeof global !== 'undefined' ? global : typeof window !== 'undefined' ? window : this, function (window) {

	'use strict';

	//
	// Variables
	//
	var defaults = {
		// general
		mode: 'normal',
		event: 'click',

		activeClass: 'active',
		
		// active: true,
		// inactive: false,
		// inactiveClass: 'inactive',

		// initialTarget: null,

		// disabled: false,
		// disabledClass: null,

		focusin: true,
		focusout: true,

		clickout: false,
		clickoutTarget: null,
		
		// target
		targetClass: 'bui-toggle-target',
		targetActiveClass: 'active',
		targetAttribute: 'data-toggle-target',

		// inactiveButton
		inactiveButton: false,
		inactiveButtonElement: 'button',
		inactiveButtonClass: 'close',
		inactiveButtonText: 'close',
		inactiveButtonArea: null,

		// react target
		reactTarget: null,
		reactTargetClass: null,
		reactTargetActiveClass: 'active',

		// react Parent
		reactParent: null,
		reactParentClass: null,
		reactParentActiveClass: 'active',

		/* callback */
		onloadCallBack: function() {return false;},
		
		eventBeforeCallBack: function() {return false;},
		eventAfterCallBack: function() {return false;},

		activeBeforeCallBack: function() {return false;},
		activeAfterCallBack: function() {return false;},

		inactiveBeforeCallBack: function() {return false;},
		inactiveAfterCallBack: function() {return false;}
	};

	/**
	 * Merge two or more objects together.
	 * @param   {Object} objects	The objects to merge together
	 * @returns {Object}			Merged values of defaults and options
	 */
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

	/**
	 * Create the Constructor object
	 */
	var Constructor = function(selector, options) {
		
		// Merge user options with defaults
		settings = extend(defaults, options || {});
		
		var publicAPIs = {};
		var settings;
		var lastEventTarget = null;

		publicAPIs.settings = settings;
		publicAPIs.myToggle = {};

		// active
		publicAPIs.active = function(name) {
			active(settings, publicAPIs.myToggle[name]);
		}

		// inactive
		publicAPIs.inactive = function(name) {
			inactive(settings, publicAPIs.myToggle[name]);
		}

		// toggle
		publicAPIs.toggle = function(name) {
			for (let [key, value] of Object.entries(publicAPIs.myToggle)) {
				if (key === name) {
					publicAPIs.myToggle[name].toggleTarget.classList.contains(settings.activeClass) ? publicAPIs.inactive(key) : publicAPIs.active(key);
				} else {
					publicAPIs.inactive(key);
				}
			}
		}

		// tab
		publicAPIs.tab = function(name) {
			for (let [key, value] of Object.entries(publicAPIs.myToggle)) {
				if (key === name) {
					publicAPIs.active(key);
				} else {
					publicAPIs.inactive(key);
				}
			}
		}

		// update
		publicAPIs.update = function() {			
			let toggleTargets = document.querySelectorAll(selector);
			if (!toggleTargets) return;
			
			toggleTargets.forEach(function(value, index, array) {
				publicAPIs.myToggle[value.id] = {
					toggleTarget: value,
					toggleButton: document.querySelectorAll('[data-bui-toggle-button="' + value.id + '"]'),
					reactTarget: document.querySelector(settings.reactTarget),
				}

				if (settings.inactiveButton) inactiveButton(settings, publicAPIs.myToggle[value.id]);
				if (settings.focusin) focusin(settings, publicAPIs.myToggle[value.id]);

				settings.onloadCallBack.call(this, publicAPIs.myToggle[value.id].toggleTarget, publicAPIs.myToggle[value.id].reactTarget);
			});
		};
		
		/**
		 * Actions Active
		 */
		 function active(settings, toggleThis) {
			settings.eventBeforeCallBack.call(this, toggleThis.toggleTarget, toggleThis.reactTarget);
			settings.activeBeforeCallBack.call(this, toggleThis.toggleTarget, toggleThis.reactTarget);

			toggleThis.toggleTarget.classList.add(settings.activeClass);
			toggleThis.toggleButton.forEach((value) => value.classList.add(settings.activeClass));
			if (settings.reactTarget != null) toggleThis.reactTarget.classList.add(settings.reactTargetActiveClass);
			if (settings.reactParent != null) toggleThis.toggleTarget.closest(settings.reactParent).classList.add(settings.reactParentActiveClass);	

			if (settings.focusin) toggleThis.toggleTarget.focus();
			event.preventDefault();
			lastEventTarget = event.taeget;

			if (settings.focusout) focusout(settings, toggleThis);
			if (settings.clickout) clickout(settings, toggleThis);

			settings.eventAfterCallBack.call(this, toggleThis.toggleTarget, toggleThis.reactTarget);
			settings.activeAfterCallBack.call(this, toggleThis.toggleTarget, toggleThis.reactTarget);
		};
		
		/**
		 * Actions Inactive
		 */
		function inactive(settings, toggleThis) {		
			settings.eventBeforeCallBack.call(this, toggleThis.toggleTarget, toggleThis.reactTarget);
			settings.inactiveBeforeCallBack.call(this, toggleThis.toggleTarget, toggleThis.reactTarget);

			toggleThis.toggleTarget.classList.remove(settings.activeClass);
			toggleThis.toggleButton.forEach((value) => value.classList.remove(settings.activeClass));
			if (settings.reactTarget != null) toggleThis.reactTarget.classList.remove(settings.reactTargetActiveClass);
			if (settings.reactParent != null) toggleThis.toggleTarget.closest(settings.reactParent).classList.remove(settings.reactParentActiveClass);

			settings.eventAfterCallBack.call(this, toggleThis.toggleTarget, toggleThis.reactTarget);
			settings.inactiveAfterCallBack.call(this, toggleThis.toggleTarget, toggleThis.reactTarget);
		};	

		/**
		 * Inactive Button
		 */
		 function inactiveButton(settings, toggleThis) {
			var inactiveButton = toggleThis.toggleTarget.querySelector('.' + settings.inactiveButtonClass);

			if (inactiveButton === null) {
				inactiveButton = document.createElement('button');
				inactiveButton.setAttribute('type', 'button');
				inactiveButton.className = settings.inactiveButtonClass;
				inactiveButton.innerHTML = settings.inactiveButtonText;
				
				// Append Inactive Button
				settings.inactiveButtonArea === null ? toggleThis.toggleTarget.appendChild(inactiveButton) : toggleThis.toggleTarget.querySelector(settings.inactiveButtonArea).appendChild(inactiveButton);
			}

			// Inactive event
			inactiveButton.addEventListener('click', function() {
				inactive(settings, toggleThis);
			}, false);
		};

		/**
		 * reactTarget
		 */
		 function reactTarget(settings, toggleThis) {		
			toggleThis.reactTarget.classList.add(settings.activeClass);
		};

		/**
		 * Inactive by Clickout
		 */
		function clickout(settings, toggleThis) {
			document.addEventListener('mouseup', function(event) {
				if (toggleThis.toggleTarget.classList.contains(settings.activeClass) && !toggleThis.toggleTarget.contains(event.target)) {
					inactive(settings, toggleThis);
				}
			});
		};

		/**
		 * Inactive by focusout
		 */
		function focusout(settings, toggleThis) {
			toggleThis.toggleTarget.addEventListener('keydown', function(event) {
				if (event.keyCode === 9) {
					setInterval(function() {
						if (!toggleThis.toggleTarget.contains(document.activeElement)) inactive(settings, toggleThis);
					}, 1);
				}
			});
		};

		/**
		 * focus to Active target
		 */
		function focusin(settings, toggleThis) {
			toggleThis.toggleTarget.setAttribute('tabindex', '0');
		};


		/**
		 * Initialize the instance
		 */
		var init = function () {
			// Setup the DOM
			publicAPIs.update();
		};

		// Initialize and return the Public APIs
		init();
		return publicAPIs;
	};

	// Return the Constructor
	return Constructor;
});


/**
 * buiExpand
 */
(function (root, factory) {
	if ( typeof define === 'function' && define.amd ) {
		define([], function () {
			return factory(root);
		});
	} else if ( typeof exports === 'object' ) {
		module.exports = factory(root);
	} else {
		root.buiExpand = factory(root);
	}
})(typeof global !== 'undefined' ? global : typeof window !== 'undefined' ? window : this, function (window) {

	'use strict';

	// Variables
	var defaults = {
		// general
		active: false,
		activeClass: 'active',
		initial: 0,

		ellipsis: false,
		ellipsisLimit: 2,
		ellipsisField: '',
		ellipsisActiveClass: 'limit',

		// target
		target: null,
		targetClass: 'bui-fold-target',
		targetActiveClass: 'active',

		// close
		button: 'button',
		buttonClass: 'expand',
		buttonActiveClass: 'active',
		buttonText: '펼치기',
		buttonActiveText: null,
		buttonAppendTo: null,

		// accordion
		// accordion: true,

		/* callback */
		onloadCallBack: function() {return false;},
		eventCallBack: function() {return false;},
		activeAfterCallBack: function() {return false;},
		inactiveAfterCallBack: function() {return false;}
	};

	/**
	 * Merge two or more objects together.
	 * @param	{Object}	objects		The objects to merge together
	 * @returns	{Object}				Merged values of defaults and options
	 */
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

	var actionActive = function(settings, toggleTarget, toggleButton) {
		toggleTarget.classList.add(settings.activeClass);
		toggleButton.classList.add(settings.buttonActiveClass);
		settings.buttonActiveText != null ?  toggleButton.innerHTML = settings.buttonActiveText : null;

		settings.activeAfterCallBack.call(this, toggleTarget, toggleButton);
	};

	var actionInactive = function(settings, toggleTarget, toggleButton) {
		toggleTarget.classList.remove(settings.activeClass);
		toggleButton.classList.remove(settings.buttonActiveClass);
		settings.buttonActiveText != null ? toggleButton.innerHTML = settings.buttonText : null;

		settings.inactiveAfterCallBack.call(this, toggleTarget, toggleButton);
	};

	var ellipsis = function(settings, toggleTarget) {
		var field = toggleTarget.querySelector(settings.ellipsisField); 
		var containerHeight = field.offsetHeight 
		var lineHeight = parseInt(window.getComputedStyle(field).getPropertyValue('line-height')); 
		var lines = containerHeight / lineHeight;

		if (lines > settings.ellipsisLimit) {
			toggleTarget.classList.add(settings.ellipsisActiveClass);
		} else {
			toggleTarget.classList.remove(settings.ellipsisActiveClass);
		}
	};

	var actionSetup = function(settings, toggleTarget) {

		toggleTarget.classList.add(settings.targetClass);
		var toggleButton = document.createElement('button');
		toggleButton.setAttribute('type', 'button');
		toggleButton.className = settings.buttonClass;
		toggleButton.innerHTML = settings.buttonText;

		if(settings.buttonAppendTo != null) {
			if(toggleTarget.querySelector(settings.buttonAppendTo)) {
				toggleTarget.querySelector(settings.buttonAppendTo).appendChild(toggleButton);
			}
		} else {
			toggleTarget.appendChild(toggleButton);
		};

		toggleButton.addEventListener('click', function(e) {
			if (settings.accordion === true) {
				if(toggleTarget.classList.contains(settings.activeClass)) {
					actionInactive(settings, toggleTarget, toggleButton);
				} else {
					actionActive(settings, toggleTarget, toggleButton);
					var siblings = getSiblings(toggleTarget);
					Array.prototype.forEach.call(siblings, function(siblingItem) {
						var siblingItemButton = siblingItem.getElementsByClassName(settings.buttonClass)[0];
						actionInactive(settings, siblingItem, siblingItemButton);
					});
				}
			} else {
				toggleTarget.classList.contains(settings.activeClass) ? actionInactive(settings, toggleTarget, toggleButton) : actionActive(settings, toggleTarget, toggleButton);
			}
			settings.eventCallBack.call(toggleTarget, toggleButton);
		});

		if(toggleTarget.classList.contains(settings.activeClass)) {
			actionActive(settings, toggleTarget, toggleButton);
		}
	}

	/**
	 * Create the Constructor object
	 */
	var Constructor = function (selector, options) {

		// Merge user options with defaults
		settings = extend(defaults, options || {});

		// Variables
		var publicAPIs = {};
		var settings;

		/**
		 * Setup the DOM with the proper attributes
		 */
		publicAPIs.setup = function() {

			// Variables
			var selectItems = document.querySelectorAll(selector);
			if (!selectItems) return;

			Array.prototype.forEach.call(selectItems, function(toggleTarget) {

				// Setup Toggle Button
				actionSetup(settings, toggleTarget);

				if(settings.ellipsis === true) {
					ellipsis(settings, toggleTarget);
					window.addEventListener('resize', function() {
						ellipsis(settings, toggleTarget);
					}, false);
				}
			});
		};
		/**
		 * Initialize the instance
		 */
		var init = function () {
			// Setup the DOM
			publicAPIs.setup();
		};

		// Initialize and return the Public APIs
		init();
		return publicAPIs;
	};
	
	// Return the Constructor
	return Constructor;
});