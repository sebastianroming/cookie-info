function CookieInfo(settings) {

	var that												= this;
	var _debug											= false;

	var settings										= settings 														|| {};
	var cookieName									= settings['cookieName'] 							|| 'CookieInfoReadAndAgreed';
	var cookieBarId									= settings['cookieBarId'] 						|| 'cookie-info-bar';
	var cookieBarIdBottomStyle			= settings['cookieBarId'] 						|| 'cookie-info-bar-style-bottom';
	var cookieBarIdBottomFullStyle	= settings['cookieBarId'] 						|| 'cookie-info-bar-style-bottom-full';
	var messageId										= settings['messageId'] 							|| 'cookie-info-bar-message';
	var messageText									= settings['messageText'] 						|| 'Cookies helfen uns bei der Bereitstellung unserer Dienste. Durch die Nutzung unserer Dienste erklären Sie sich damit einverstanden, dass wir Cookies setzen.';
	var okButtonId									= settings['okButtonId']							|| 'cookie-info-bar-buttons-accept';
	var okButtonText								= settings['okButtonText']						|| 'OK';
	var moreInfoButtonIsActive			= settings['moreInfoButtonIsActive']	|| true;
	var moreInfoButtonId						= settings['moreInfoButtonId']				|| 'cookie-info-bar-buttons-moreinfo';
	var moreInfoButtonText					= settings['moreInfoButtonText']			|| 'Mehr erfahren';
	var moreInfoButtonPage					= settings['moreInfoButtonPage']			|| false;
	var delay												= settings['delay']										|| 800;
	var daysToExpire								= settings['daysToExpire'] 						|| 365;
	var cookieInfoBarStyle					= settings['cookieInfoBarStyle'] 			|| 'top';

	var CookieInfoHandler = {
		acceptCookiePolicyAndClose: function() {

			this.debug('Accepted the Cookie Policy Notice');

			Cookie.set(cookieName, 'yes', daysToExpire);

			if (cookieInfoBarStyle == 'top') {
				jQuery('#' + cookieBarId).slideUp(delay);
			} else if (cookieInfoBarStyle == 'bottom') {
				jQuery('#' + cookieBarIdBottomStyle).slideUp(delay);
			} else if (cookieInfoBarStyle == 'bottom-full') {
				jQuery('#' + cookieBarIdBottomFullStyle).slideUp(delay);
			}

			return false;
		},
		displayInfoBar: function() {

			if (cookieInfoBarStyle == 'top') {
				jQuery('#' + cookieBarId).slideDown(delay);
			} else if (cookieInfoBarStyle == 'bottom') {
				jQuery('#' + cookieBarIdBottomStyle).slideDown(delay);
			} else if (cookieInfoBarStyle == 'bottom-full') {
				jQuery('#' + cookieBarIdBottomFullStyle).slideDown(delay);
			}

		},
		hideInfoBar: function() {

			if (cookieInfoBarStyle == 'top') {
				jQuery('#' + cookieBarId).slideUp(delay);
			} else if (cookieInfoBarStyle == 'bottom') {
				jQuery('#' + cookieBarIdBottomStyle).slideUp(delay);
			} else if (cookieInfoBarStyle == 'bottom-full') {
				jQuery('#' + cookieBarIdBottomFullStyle).slideUp(delay);
			}

		},
		forwardToMoreInfoPage: function() {
			this.debug('Forwarding User to page: ' + moreInfoButtonPage);
			document.location.href = moreInfoButtonPage;
			return;
		},
		debug: function(message) {
			if (_debug == true) {
				console.log('cookie-info', message);
			}
		}
	};

	var Cookie = {
		set: function(name, value, days) {

			CookieInfoHandler.debug('Setting the Cookie');
			CookieInfoHandler.debug('Cookie Information: {name: ' + name + ', value: ' + value + ', days: ' + days + '}');

			if (days) {
				var date = new Date();
				date.setTime(date.getTime() + (days*24*60*60*1000));
				var expires = "; expires=" + date.toGMTString();
			}
			else var expires = "";
			document.cookie = name + "=" + value + expires + "; path=" + window.location.pathname;
		},
		read: function(name) {
			var nameEQ = name + "=";
			var ca = document.cookie.split(';');
			for(var i=0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') {
					c = c.substring(1,c.length);
				}
				if (c.indexOf(nameEQ) === 0) {
					return c.substring(nameEQ.length,c.length);
				}
			}
			return null;
		},
		erase: function(name) {
			this.set(name,"",-1);
		},
		exists: function(name) {
			return (this.read(name) !== null);
		}
	};

	jQuery('#' + messageId).html(messageText);
	jQuery('#' + okButtonId).html(okButtonText);
	if (moreInfoButtonIsActive == true && moreInfoButtonPage != false) {
		jQuery('#' + moreInfoButtonId).html(moreInfoButtonText);
	}

	if (!Cookie.exists(cookieName)) {
		CookieInfoHandler.debug('Required Cookie "' + cookieName + '" does not exist.');
		CookieInfoHandler.displayInfoBar();
	} else {
		CookieInfoHandler.debug('Required Cookie "' + cookieName + '" does exist.');
	}

	jQuery('#' + okButtonId).click(function(e) {
		e.preventDefault();
		CookieInfoHandler.acceptCookiePolicyAndClose();
	});

	jQuery('#' + moreInfoButtonId).click(function(e) {
		e.preventDefault();
		CookieInfoHandler.forwardToMoreInfoPage();
	});


};
