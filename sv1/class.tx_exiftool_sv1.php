<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Martin Holtz <holtz@elemente.ms>
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

require_once(PATH_t3lib.'class.t3lib_svbase.php');


/**
 * Service "exiftool - iptc" for the "exiftool" extension.
 *
 * @author	Martin Holtz <holtz@elemente.ms>
 * @package	TYPO3
 * @subpackage	tx_exiftool
 */
class tx_exiftool_sv1 extends t3lib_svbase {
	var $prefixId = 'tx_exiftool_sv1';		// Same as class name
	var $scriptRelPath = 'sv1/class.tx_exiftool_sv1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'exiftool';	// The extension key.

	/**
	 * [Put your description here]
	 *
	 * @return	[type]		...
	 */
	function init()	{
		$available = parent::init();

		// Here you can initialize your class.

		// The class have to do a strict check if the service is available.
		// The needed external programs are already checked in the parent class.

		// If there's no reason for initialization you can remove this function.

		return $available;
	}

	/**
	 * write to devlog
	 *
	 * @param string $title Title / short description
	 * @param int $severity Severity: 0 is info, 1 is notice, 2 is warning, 3 is
	 * fatal error, -1 is "OK" message
	 * @param mixed $somearray array with debug-content
	 */
	function devlog($title, $severity = 1, $somearray = array()) {
		if (!TYPO3_DLOG) return 0;
		t3lib_div::devLog($title, $this->extKey, -1, $somearray);
	}

	/**
	 * performs the service processing
	 *
	 * @param	string		Content which should be processed.
	 * @param	string		Content type
	 * @param	array		Configuration array
	 * @return	boolean
	 */
	function process($content='', $type='', $conf=array())	{
		$this->conf = $conf;

		$this->out = array();
		$this->out['fields'] = array();
		$this->meta = array();
		$this->iptc = array();
		$this->info['exec'] = 'perl';

		// TS PAGE-Config
		$page_id = $this->conf['meta']['fields']['pid'];
		$this->service_conf = t3lib_BEfunc::getModTSconfig($page_id,'tx_exiftool_sv1');

		$this->service_conf['properties']['exiftoolparams'] = mb_strlen($this->service_conf['properties']['exiftoolparams']) > 0?$this->service_conf['properties']['exiftoolparams']:'  -S -iptc:all ';
		$this->info['params'] = ' '.t3lib_extMgm::extPath('exiftool').'exiftool/exiftool '.$this->service_conf['properties']['exifparams'].' ';

		$match = $this->service_conf['properties']['match.'];
// t3lib_div::debug('exiftool'.$page_id);
// t3lib_div::debug($this->service_conf);
		if($inputFile = $this->getInputFile()) {
			$cmd = t3lib_exec::getCommand($this->info['exec']).$this->info['params'].' "'.$inputFile.'"';
			$output = array();
			$ret = -1;
			exec($cmd, $output, $ret);
			if (1 == $this->service_conf['properties']['debug']) {
				$this->devlog('Unparsed Meta-Data', 0, $output);
			}

			if ($ret != 0) {
				// TODO: do some magick error handling - however?
				$this->devlog('exec return error', 3, $ret);
			}
			$t3lib_cs = t3lib_div::makeInstance("t3lib_cs");
			$this->service_conf['properties']['fileCharset'] = $t3lib_cs->parse_charset($this->service_conf['properties']['fileCharset']);
			// check if charset is known by TYPO3
			if (false === array_search($this->service_conf['properties']['fileCharset'], $t3lib_cs->synonyms)) {
				// TODO: error handling
				$this->devlog('unknown charset', 2, $this->service_conf['properties']['fileCharset']);
				$this->service_conf['properties']['fileCharset'] = 'utf-8';
			}

// TODO: Character Conversion!
			// character conversion, before postProcess,
			// so we could compare fields in database with fields
			// from our metadata
			// $t3lib_cs->convArray($this->iptc, $service_conf['properties']['fromCharset'], $this->conf['wantedCharset'], true);
			// $t3lib_cs->convArray($this->out['fields'], $service_conf['properties']['fromCharset'], $this->conf['wantedCharset'], true);
			$t3lib_cs->convArray($output, $this->service_conf['properties']['fileCharset'], $this->conf['wantedCharset'], true);

			$outputArray = array();
			foreach ($output as $str) {
				// exiftool gives us "editstatus     : value of editstatus"
				// so we have to split at the first ":" and trim than
				$key = mb_strtolower(trim(mb_substr($str, 0, mb_strpos($str,':'))));
				$value = trim(mb_substr($str,mb_strpos($str,':')+1));
				$outputArray[$key] = $value;
			}
			// TODO: check internal Vars - they are not used correctly
			if (!is_array($match)) { return false; }
			foreach ($match as $field => $exiftag) {
				if ('.' == mb_substr($field,-1,1)) {
					// we can do things like:
					// abstract = description
					// abstract.1 = abstract
					// abstract.2 = something else
					//
					// abstract.1 should only be processed after "abstract"
					// if "abstract" exists, we should skip all "abstract.x" because they
					// are processed right after "abstract"
					if (isset($match[mb_substr($field,0,-1)])) {
						continue;
					}
					// if "abstract" does not exists, we should create it,
					// so "abstract.x" will be processed too
					$field = mb_substr($field,0,-1);
					$exiftag = '';
				}

				// via PAGE-TS setting we can define in which field
				// the value should be stored
				$this->iptc[$field] = (!isset($outputArray[mb_strtolower($exiftag)]))?'':''.$outputArray[mb_strtolower($exiftag)];
				// If there is no default Config, or empty content, test for other
				// fields. f.e. no IPTC data, but EXIF or XMP
				if (0 == mb_strlen($this->iptc[$field]) && isset($match[$field.'.'])) {
					$i = 1;
					while (0 == mb_strlen($this->iptc[$field]) && isset($match[$field.'.'][$i])) {
						// f.e.
						// abstract = description
						// abstract.1 = abstract
						// abstract.2 = something else
						$this->iptc[$field] = (!isset($outputArray[mb_strtolower($match[$field.'.'][$i])]))?'':''.$outputArray[mb_strtolower($match[$field.'.'][$i])];
						$i++;
					}
				}

				$this->out['fields'][$field] = $this->iptc[$field];
			}
			// TODO: conversion of Informations, f.e. Date, Keywords etc.
			// TODO: build hook

			$this->postProcess();
			$this->out['fields']['meta']['iptc'] = $this->iptc;
// t3lib_div::debug($this->out['fields']);
			if (1 == $this->service_conf['properties']['debug']) {
				$this->devlog('Parsed Meta-Data', 0, $this->out['fields']);
			}
		} else {
			$this->errorPush(T3_ERR_SV_NO_INPUT, 'No or empty input.');
		}
		return $this->getLastError();
	}

	/**
	 * Started with TS-Config:
	 * category.lookUpCategory = 1
	 * category.lookUpCategory.insertNew = 1
	 * category.lookUpCategory.splitToken = ,
	 *
	 * To get inserted categorys we need: $meta ['category'] = '12,15';
	 *
	 * @return string komma separated list of category uids
	 */
	function lookUpCategory($value, $splitToken = ',', $insertNew = false) {
		if (0 == mb_strlen($splitToken)) { $splitToken = ','; }

		if (!is_array($value)) {
			$value = t3lib_div::trimExplode($splitToken, $value);
		}
		if (0 == count($value)) return '';
		$categoryList = array();
		foreach ($value as $category) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'tx_dam_cat', 'title LIKE '.$GLOBALS['TYPO3_DB']->fullQuoteStr($category, 'tx_dam_cat'));
			if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
				$categoryList[] = $row['uid'];
			}
		}
		return implode(',',$categoryList);
	}

	/**
	 * processing of values
	 */
	function postProcess () {

		if (is_array($this->iptc['keywords'])) {
			$this->out['fields']['keywords'] = $this->iptc['keywords'] = implode(',', $this->iptc['keywords']);
		}

		$this->out['fields']['category'] = $this->lookUpCategory($this->iptc['category'], $this->service_conf['properties']['category.']['lookUpCategory.']['splitToken'], (1 == $this->service_conf['properties']['category.']['lookUpCategory.']['insertNew']));
		$this->out['fields']['file_orig_loc_desc']  = 'exiftool '.$this->iptc['category'];

			// detect country code
		if ($this->out['fields']['loc_country']=='' AND $this->iptc['country']) {
			$country_en = $this->iptc['country'];
			if($country_en) {
				$likeStr = $GLOBALS['TYPO3_DB']->escapeStrForLike($country_en, 'static_countries');
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cn_iso_3', 'static_countries', 'cn_short_en LIKE '.$GLOBALS['TYPO3_DB']->fullQuoteStr($likeStr, 'static_countries').t3lib_BEfunc::deleteClause('static_countries'));
				if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
					$this->out['fields']['loc_country'] = $row['cn_iso_3'];
					$this->iptc['country_code'] = $row['cn_iso_3'];
				}
			}
		}
	}

	/**
	 * convert date string into tstamp
	 * ISO 8601 (JJJJMMTT)
	 */
	function parseDate($date)	{
		if (is_string($date) AND strlen($date)==8) {
			$date = mktime(0, 0, 0, substr($date,4,2), substr($date,6,2), substr($date,0,4));
		} else {
			$date = 0;
		}
		return $date;
	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/exiftool/sv1/class.tx_exiftool_sv1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/exiftool/sv1/class.tx_exiftool_sv1.php']);
}

?>