function CookieInfo(settings) {
		
	settings					= settings 								|| {};
	var cookieName				= settings['cookieName'] 				|| 'CookieInfoReadAndAgreed';
	var cookieBarId				= settings['cookieBarId'] 				|| 'cookie-info-bar';
	var messageId				= settings['messageId'] 				|| 'cookie-info-bar-message';
	var messageText				= settings['messageText'] 				|| 'Cookies helfen uns bei der Bereitstellung unserer Dienste. Durch die Nutzung unserer Dienste erklären Sie sich damit einverstanden, dass wir Cookies setzen.';
	var okButtonId				= settings['okButtonId']				|| 'cookie-info-bar-buttons-accept';
	var okButtonText			= settings['okButtonText']				|| 'OK';
	var moreInfoButtonIsActive	= settings['moreInfoButtonIsActive']	|| true;
	var moreInfoButtonId		= settings['moreInfoButtonId']			|| 'cookie-info-bar-buttons-moreinfo';
	var moreInfoButtonText		= settings['moreInfoButtonText']		|| 'Mehr erfahren';
	var delay					= settings['delay']						|| 800;
	var daysToExpire			= settings['daysToExpire'] 				|| 365;
	
	var Cookie = {
		set: function(name, value, days) {
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
	
	var CookieInfoHandler = {
		acceptCookiePolicyAndClose: function() {
			Cookie.set(cookieName, 'yes', daysToExpire);
			jQuery('#' + cookieBarId).slideUp(delay);
			return false;
		},
		displayInfoBar: function() {
			jQuery('#' + cookieBarId).slideDown(delay);
		},
		hideInfoBar: function() {
			jQuery('#' + cookieBarId).slideUp(delay);
		}
	};
	
	jQuery('#' + messageId).html(messageText);
	jQuery('#' + okButtonId).html(okButtonText);
	if (moreInfoButtonIsActive == true) {
		jQuery('#' + moreInfoButtonId).html(moreInfoButtonText);
	}
	
	if (!Cookie.exists(cookieName)) {
		CookieInfoHandler.displayInfoBar();
	}
	
	jQuery('#' + okButtonId).click(function(e) {
		e.preventDefault();
		CookieInfoHandler.acceptCookiePolicyAndClose();
	});
	
	
};