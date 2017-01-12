<?php
namespace sebastianroming\CookieInfo;

class Core {

	const PLUGIN_VERSION 				= '0.1.1';

	public $pluginSettings				= Array(
		'pluginSettingsSlug'	=> 'cookie-info-settings',
		'pluginFile'					=> '',
		'pluginBasename'			=> '',
		'pluginDir'						=> '',
		'pluginUrl'						=> '',
		'scriptUrl'						=> '',
		'styleUrl'						=> '',
	);

	private $__defaultSettings			= Array(
		'cookie-info-settings-is-active' 						=> true,
		'cookie-info-settings-ok-button-text' 			=> 'OK',
		'cookie-info-settings-moreinfo-is-active' 	=> true,
		'cookie-info-settings-moreinfo-button-text'	=> 'Mehr erfahren',
		'cookie-info-settings-message' 							=> 'Cookies helfen uns bei der Bereitstellung unserer Dienste. Durch die Nutzung unserer Dienste erklären Sie sich damit einverstanden, dass wir Cookies setzen.',
		'cookie-info-settings-style'								=> 'bottom-full',
    );


	// ---------------------------------------------
	public function __construct() {

		$this->pluginSettings['pluginFile'] 		= __FILE__;
		$this->pluginSettings['pluginBasename'] = plugin_basename($this->pluginSettings['pluginFile']);
		$this->pluginSettings['pluginDir'] 			= plugin_dir_path($this->pluginSettings['pluginFile']);
		$this->pluginSettings['pluginUrl']			= plugin_dir_url($this->pluginSettings['pluginFile']);
		$this->pluginSettings['scriptUrl']			= $this->pluginSettings['pluginUrl'] . 'js/';
		$this->pluginSettings['styleUrl']				= $this->pluginSettings['pluginUrl'] . 'css/';

		$this->addHooks();

	}


	/******************************************
	 *
	 * WORDPRESS
	 *
	 ******************************************/

	// ---------------------------------------------
	protected function addHooks() {

		add_action( 'init', 									array($this, '_initPlugin') );
		add_action( 'plugins_loaded', 				array($this, '_pluginsLoaded') );

		register_activation_hook( __FILE__, 	array($this, '_activatePlugin') );
		register_activation_hook( __FILE__, 	array($this, '_deactivatePlugin') );

		add_action( 'admin_init', 						array($this, 'registerSettings') );
		add_action( 'admin_menu', 						array($this, 'addToSettingsMenu') );
		add_action( 'wp_enqueue_scripts', 		array($this, 'loadFrontendScripts') );
		add_action( 'admin_enqueue_scripts', 	array($this, 'loadBackendScripts') );
		add_action( 'wp_footer', 							array($this, 'showCookieInfoBar') );

	}

	// ---------------------------------------------
	public function _initPlugin() {
		//
	}

	// ---------------------------------------------
	public function _pluginsLoaded() {
		load_plugin_textdomain('cookie-info');
	}

	// ---------------------------------------------
	public function _activatePlugin() {
	}

	// ---------------------------------------------
	public function _deactivatePlugin() {
		//
	}

	// ---------------------------------------------
	public function _uninstallPlugin() {
	}

	// ---------------------------------------------
	public function loadFrontendScripts() {

		wp_register_style( 'cookie-info-style', $this->pluginSettings['styleUrl'] . 'cookie-info.css', null, self::PLUGIN_VERSION );
		wp_enqueue_style( 'cookie-info-style' );

		wp_enqueue_script( 'cookie-info-script', $this->pluginSettings['scriptUrl'] . 'cookie-info.js', array( 'jquery' ), self::PLUGIN_VERSION );

	}

	// ---------------------------------------------
	public function loadBackendScripts() {

		wp_register_style( 'cookie-info-admin-style', $this->pluginSettings['styleUrl'] . 'cookie-info-admin.css', null, self::PLUGIN_VERSION );
		wp_enqueue_style( 'cookie-info-admin-style' );

	}



	/******************************************
	 *
	 * COOKIE-INFO
	 *
	 ******************************************/

	// ---------------------------------------------
	public function showCookieInfoBar() {

		$bIsActivated = $this->__getBooleanOption($this->pluginSettings['pluginSettingsSlug'] . '-is-active');

		if ($bIsActivated === true) {

			$cookieInfoBarStyle	= $this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-style');	// bottom



			$sMoreInfoPage			= (!empty($this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-page'))) ? get_page_link($this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-page')) : false;

			$outputHtml		 = '';
			if ($cookieInfoBarStyle == 'top') {
				$outputHtml	.= '<div id="cookie-info-bar">';
			} elseif ($cookieInfoBarStyle == 'bottom') {
				$outputHtml	.= '<div id="cookie-info-bar-style-bottom">';
			} elseif ($cookieInfoBarStyle == 'bottom-full') {
				$outputHtml	.= '<div id="cookie-info-bar-style-bottom-full">';
			}
			$outputHtml		.= '<div id="cookie-info-bar-message"></div>';
			$outputHtml		.= '<div id="cookie-info-bar-buttons"><button id="cookie-info-bar-buttons-accept"></button>';

			if ( $this->__getBooleanOption($this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-is-active') == true) {
				$outputHtml	.= '<button id="cookie-info-bar-buttons-moreinfo"></button>';
			}

			$outputHtml		.= '</div>';
			$outputHtml		.= '<div id="cookie-info-bar-clearer"></div>';
			$outputHtml		.= '</div>';


			$outputHtml		.= '<script type="text/javascript">';
			$outputHtml		.= '	jQuery(document).ready(function() {';
			$outputHtml		.= '		var settings = {';
			$outputHtml		.= '			messageText: "' . $this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-message') . '",';
			$outputHtml		.= '			okButtonText: "' . $this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-ok-button-text') . '",';
			$outputHtml		.= '			moreInfoButtonText: "' . $this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-button-text') . '",';
			$outputHtml		.= '			moreInfoButtonIsActive: "' . $this->__getBooleanOption($this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-is-active') . '",';
			$outputHtml		.= '			moreInfoButtonPage: "' . $sMoreInfoPage . '",';
			$outputHtml		.= '			cookieInfoBarStyle: "' . $cookieInfoBarStyle . '"';
			$outputHtml		.= '		};';
			$outputHtml		.= '		CookieInfo(settings);';
			$outputHtml		.= '	});';
			$outputHtml		.= '</script>';

			echo $outputHtml;
		}

	}

	/******************************************
	 *
	 * HELPERS
	 *
	 ******************************************/
	// ---------------------------------------------
	public function getBaseUrl($aParams = null) {

		$queryString 			= $_SERVER['QUERY_STRING'];

		if ($aParams != null) {
			$explodedQueryString 	= explode('&', $queryString);

			$newQueryString			= Array();

			foreach ($explodedQueryString as $stringPart) {
				$explodedParts = explode('=', $stringPart);
				$newQuery[$explodedParts[0]] = $explodedParts[1];
			}
			foreach ($aParams as $key => $param) {
				$newQuery[$key] = $param;
			}
			$queryString = http_build_query($newQuery);
		}

		return strlen($queryString) ? basename($_SERVER['PHP_SELF'])."?".$queryString : basename($_SERVER['PHP_SELF']);
	}



	/******************************************
	 *
	 * SETTINGS
	 *
	 ******************************************/

	// ---------------------------------------------
	public function addToSettingsMenu() {
		add_options_page( 'Einstellungen', 'Cookie-Info', 'manage_options', $this->pluginSettings['pluginSettingsSlug'], array($this, 'showSettingsPage'));
	}

	// ---------------------------------------------
	public function showSettingsPage() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		echo '<div class="wrap">';
		echo '<h1>Einstellungen</h1>';

		echo '<div id="cookie-info-column-left">';
		echo '<form method="post" action="options.php" id="cookie-info-settings-form">';
		echo settings_fields( $this->pluginSettings['pluginSettingsSlug'] );
		echo do_settings_sections( $this->pluginSettings['pluginSettingsSlug'] );
		submit_button();
		echo '</form>';
		echo '</div>';

		echo '<div id="cookie-info-column-right">';
		$this->_showAboutSection();
		echo '</div>';

		echo '<div id="cookie-info-column-clearer"></div>';

		echo '</div>';

	}

	// ---------------------------------------------
	protected function _showAboutSection() {

		echo '<div id="cookie-info-about">';
		echo '<h2>Entwickler</h2>';
		echo '<p>Sebastian Roming<br /><a href="http://sebastianroming.github.io">sebastianroming.github.io</a></p><br />';
		echo '<h3>Infos zum Plugin</h3>';
		echo '<ul>';
		echo '<li><a href="http://github.com/sebastianroming/cookie-info" target="_blank">GitHub</a></li>';
		echo '<li><a href="https://github.com/sebastianroming/cookie-info/wiki" target="_blank">Anleitung</a></li>';
		echo '<li><a href="https://github.com/sebastianroming/cookie-info/issues" target="_blank">Fehler &amp; Wünsche melden</a></li>';
		echo '</ul><br />';
		echo '<h3>Spenden</h3>';
		echo '<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="8YYGJXLHATY2A"><input type="image" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal."><img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1"></form></p><br />';

		echo '<h3>Dir gefällt das Plugin?</h3>';
		echo '<p>Wenn das Plugin hilfreich ist, dann freue ich mich über eine Gute Bewertung auf <a href="http://wordpress.org/plugins/cookie-info" target="_blank">WordPress.org</a>.</p>';
		echo '</div>';

	}

	// ---------------------------------------------
	public function registerSettings() {

		$this->_addSettingsSections();
		$this->_addSettingsFields();

		register_setting($this->pluginSettings['pluginSettingsSlug'], $this->pluginSettings['pluginSettingsSlug'] . '-is-active');
		register_setting($this->pluginSettings['pluginSettingsSlug'], $this->pluginSettings['pluginSettingsSlug'] . '-ok-button-text');
		register_setting($this->pluginSettings['pluginSettingsSlug'], $this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-is-active');
		register_setting($this->pluginSettings['pluginSettingsSlug'], $this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-button-text');
		register_setting($this->pluginSettings['pluginSettingsSlug'], $this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-page');
		register_setting($this->pluginSettings['pluginSettingsSlug'], $this->pluginSettings['pluginSettingsSlug'] . '-message');
		register_setting($this->pluginSettings['pluginSettingsSlug'], $this->pluginSettings['pluginSettingsSlug'] . '-style');

	}

	// ---------------------------------------------
	protected function _addSettingsSections() {

		add_settings_section(
			$this->pluginSettings['pluginSettingsSlug'] . '-section-general',
			'Allgemeine Einstellungen',
			null,
			$this->pluginSettings['pluginSettingsSlug']
		);

	}

	// ---------------------------------------------
	protected function _addSettingsFields() {

		add_settings_field(
			$this->pluginSettings['pluginSettingsSlug'] . '-is-active',
			'Cookie-Info aktiv',
			array($this, 'fieldIsActive'),
			$this->pluginSettings['pluginSettingsSlug'],
			$this->pluginSettings['pluginSettingsSlug'] . '-section-general'
		);

		add_settings_field(
			$this->pluginSettings['pluginSettingsSlug'] . '-ok-button-text',
			'Beschriftung OK-Button',
			array($this, 'okButtonText'),
			$this->pluginSettings['pluginSettingsSlug'],
			$this->pluginSettings['pluginSettingsSlug'] . '-section-general'
		);

		add_settings_field(
			$this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-is-active',
			'Mehr Info aktiv',
			array($this, 'fieldMoreInfoIsActive'),
			$this->pluginSettings['pluginSettingsSlug'],
			$this->pluginSettings['pluginSettingsSlug'] . '-section-general'
		);

		add_settings_field(
			$this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-button-text',
			'Beschriftung Mehr Info-Button',
			array($this, 'moreinfoButtonText'),
			$this->pluginSettings['pluginSettingsSlug'],
			$this->pluginSettings['pluginSettingsSlug'] . '-section-general'
		);

		add_settings_field(
			$this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-page',
			'Seite für "Mehr Info"',
			array($this, 'moreinfoPageSelect'),
			$this->pluginSettings['pluginSettingsSlug'],
			$this->pluginSettings['pluginSettingsSlug'] . '-section-general'
		);

		add_settings_field(
			$this->pluginSettings['pluginSettingsSlug'] . '-message',
			'Hinweistext',
			array($this, 'message'),
			$this->pluginSettings['pluginSettingsSlug'],
			$this->pluginSettings['pluginSettingsSlug'] . '-section-general'
		);

		add_settings_field(
			$this->pluginSettings['pluginSettingsSlug'] . '-style',
			'Darstellung',
			array($this, 'cookieInfoBarStyle'),
			$this->pluginSettings['pluginSettingsSlug'],
			$this->pluginSettings['pluginSettingsSlug'] . '-section-general'
		);

	}

	// ---------------------------------------------
	public function fieldIsActive() {
		$this->__addSettingsCheckbox($this->pluginSettings['pluginSettingsSlug'] . '-is-active', 'Der Hinweis wird angezeigt');
	}

	// ---------------------------------------------
	public function fieldMoreInfoIsActive() {
		$this->__addSettingsCheckbox($this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-is-active', 'Der Button wird angezeigt');
	}

	// ---------------------------------------------
	public function okButtonText() {

		$this->__addSettingsInput(
			$this->pluginSettings['pluginSettingsSlug'] . '-ok-button-text',
			null,
			$this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-ok-button-text')
		);
	}

	// ---------------------------------------------
	public function moreinfoButtonText() {

		$this->__addSettingsInput(
			$this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-button-text',
			'Nur relevant, sofern der Button aktiv ist.',
			$this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-button-text')
		);

	}

	// ---------------------------------------------
	public function moreinfoPageSelect() {

		$this->__addSettingsSelectPage(
			$this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-page',
			'Nur relevant, sofern der Button aktiv ist.',
			$this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-moreinfo-page')
		);

	}

	// ---------------------------------------------
	public function message() {

		$this->__addSettingsInput(
			$this->pluginSettings['pluginSettingsSlug'] . '-message',
			null,
			$this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-message')
		);

	}

	// ---------------------------------------------
	public function cookieInfoBarStyle() {

		$this->__addSettingsSelect(
			$this->pluginSettings['pluginSettingsSlug'] . '-style',
			Array(
				'bottom' 			=> 'Bottom-Box',
				'bottom-full' => 'Bottom Full-Width'
			),
			$this->__getOption($this->pluginSettings['pluginSettingsSlug'] . '-style')
		);

	}

	// ---------------------------------------------
	public function checked($value) {
		return ($value === true || $value == 1 ? 'checked="checked" ' : '');
	}

	// ---------------------------------------------
	private function __addSettingsCheckbox($checkboxId, $text) {
		echo '<input type="checkbox" value="1" id="' . $checkboxId . '" name="' . $checkboxId . '" ';
		echo $this->checked($this->__getBooleanOption($checkboxId)) . '/><label for="' . $checkboxId . '">';
		echo $text . '</label>';
	}

	// ---------------------------------------------
	private function __addSettingsInput($name, $description, $value = '') {
		printf('<input type="text" value="%2$s" id="%1$s" name="%1$s" /><p class="description">%3$s</p>', $name, (empty($value) ? $this->__getOption($name) : $value), $description);
	}

	// ---------------------------------------------
	private function __addSettingsSelectPage($name, $description, $value = '') {

		$params = Array(
			'echo'				=> 0,
			'name'				=> $name,
			'show_option_none'	=> '--- Keine ---',
			'option_none_value'	=> 0,
		);

		if ($value != false && $value != 0 && !empty($value) && $value != null) {
			$params['selected'] = (int)$value;
		} else {
			$params['selected'] = $this->__getOption($name);
		}
		echo wp_dropdown_pages($params);
	}

	// ---------------------------------------------
	private function __addSettingsSelect($name, $options, $selectedValue) {
		echo '<select name="' . $name . '">';
		foreach ($options as $value => $label) {
			echo '<option value="' . $value . '"' . ($selectedValue == $value ? ' selected="selected"' : '') . '>';
			echo $label . '</option>';
		}
		echo '</select>';
	}

	// ---------------------------------------------
	private function __getOption($key) {

		if (array_key_exists($key, $this->__defaultSettings)) {
			return get_option($key, $this->__defaultSettings[$key]);
		}

		return get_option($key, false);
	}

	// ---------------------------------------------
	private function __getBooleanOption($key) {
		$option = $this->__getOption($key);
		return $this->__toBoolean($option);
	}

	// ---------------------------------------------
	private function __toBoolean($value) {
		return in_array($value, array(1, true, '1', 'yes', 'on'), true);
	}

}

new Core();
