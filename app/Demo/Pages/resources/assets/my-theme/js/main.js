;(function($, window, document) {
	'use strict';

	var el;

	var app = {
		name: 'minimal',
		lang: null,
		registry: [],
		isMobile: false,
		isTouch: Modernizr.touchevents,
		isIos: /(iPad|iPhone|iPod)/g.test(navigator.userAgent),
		isAndroid: /(Android)/gi.test(navigator.userAgent),

		// app init
		init: function() {
			el = {
				html: $('html')
			};

			FastClick.attach(document.body);
			this.lang = el.html.attr('lang');
			this.isIos && el.html.addClass('is-ios');
			this.isAndroid && el.html.addClass('is-android');

			this.runFunctions();
		},

		// register init function
		register: function(fn) {
			return this.registry.push(fn) - 1;
		},

		// unregister function from registry
		unregister: function(i) {
			/^f/.test(typeof i) ?
				(i = this.registry.indexOf(i)) > -1 ?
					this.registry.splice(i, 1) :
					void(0) :
				this.registry.splice(i, 1);
		},

		// run functions from registry
		runFunctions: function() {
			var fn = this.registry;
			for(var i in fn) {
				fn[i]();
			}
		}

	};

	// attach app to window object
	window.app = app;

	// DOM ready
	$(app.init.bind(app));

})(jQuery, window, document);