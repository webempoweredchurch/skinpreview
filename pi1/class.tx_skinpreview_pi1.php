<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Jeff Segars <jeff@webempoweredchurch.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('templavoila_framework') . 'class.tx_templavoilaframework_lib.php');

/**
 * Plugin 'Fronted Skin Preview' for the 'skinpreview' extension.
 *
 * @author	Jeff Segars <jeff@webempoweredchurch.org>
 * @package	TYPO3
 * @subpackage	tx_skinpreview
 */
class tx_skinpreview_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_skinpreview_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_skinpreview_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'skinpreview';	// The extension key.
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf)	{
			// Avoid double inclusions
		if (!$GLOBALS['skinSelectorProcessed']) {
			$GLOBALS['skinSelectorProcessed'] = TRUE;
			return $this->drawSkinSelector();
		}
	}

	protected function drawSkinSelector() {
		$GLOBALS['TSFE']->additionalHeaderData[] = '<link href="typo3conf/ext/skinpreview/pi1/res/styles.css" rel="stylesheet" type="text/css" />';
		$GLOBALS['TSFE']->additionalHeaderData[] = '<script src="typo3conf/ext/skinpreview/pi1/res/skinpreview.js"></script>';

		$currentSkinKey = $this->getCurrentSkinKey();

		$content = array();
		$content[] = '<div class="skinSelectorWrapper">';
		$content[] = 	'<div class="skinSelector">';

		$skinKeys = tx_templavoilaframework_lib::getSkinKeys();
		natsort($skinKeys);
		$content[] = 		'<form id="skinSelectorForm" action="' . t3lib_div::getIndpEnv('TYPO3_REQUEST_URL') . '" method="POST">';
		$content[] = 			'<label>Select a Skin</label>';
		$content[] = 			'<select name="skinSelector" id="skinSelectorDropdown">';
		foreach ($skinKeys as $key) {
			$selected = '';
			$skinInfo = tx_templavoilaframework_lib::getSkinInfo($key);
			if ($key == $currentSkinKey) {
				$selected = 'selected="selected"';
			}
			$content[] = 			'<option value="' . $key . '" ' . $selected . '>' . $skinInfo['title'] . '</option>';
		}
		$content[] = 			'</select>';
		$content[] =		'</form>';
		$content[] =		'<div class="wecLogo">';
		$content[] =			'<a href="http://webempoweredchurch.org"><img src="typo3conf/ext/skinpreview/pi1/res/wec-logo.png" /></a>';
		$content[] =		'</div>';
		$content[] = 	'</div>';
		$content[] = '</div>';
		
		return implode(chr(10), $content);
	}

	protected function getCurrentSkinKey() {
		$currentSkinKey = $this->retrieveFromSession();
		if (!$currentSkinKey) {
			$rootPid = $this->findRootPid($GLOBALS['TSFE']->id);
			$currentSkinKey = tx_templavoilaframework_lib::getCurrentSkin($rootPid);
		}

		return $currentSkinKey;
	}

	protected function storeInSession($skinKey) {
		$GLOBALS["TSFE"]->fe_user->setKey("ses","tx_skinpreview_pi1", $skinKey);
		$GLOBALS["TSFE"]->fe_user->sesData_change = true;
		$GLOBALS["TSFE"]->fe_user->storeSessionData();		
	}
	
	/*
	 * Retrieves session data.
	 * @return		array		The array of session data for the extension.
	 */
	protected function retrieveFromSession() {
		return $GLOBALS["TSFE"]->fe_user->getKey("ses","tx_skinpreview_pi1");
	}

	/**
	 * Starts at the specified page ID and walks up the tree to find the nearest root page id.
	 *
	 * @param	integer		$pageId
	 * @return	integer
	 */
	protected function findRootPid($pageID) {
		$tsTemplate = t3lib_div::makeInstance("t3lib_tsparser_ext");
		$tsTemplate->tt_track = 0;
		$tsTemplate->init();

		// Gets the rootLine
		$sys_page = t3lib_div::makeInstance("t3lib_pageSelect");
		$rootLine = $sys_page->getRootLine($pageID);
		$tsTemplate->runThroughTemplates($rootLine);
		$rootPid = $tsTemplate->rootId;

		return $rootPid;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/skinpreview/pi1/class.tx_skinpreview_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/skinpreview/pi1/class.tx_skinpreview_pi1.php']);
}

?>
