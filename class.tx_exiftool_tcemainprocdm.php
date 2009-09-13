<?php
/***************************************************************
*  Copyright notice
*
*  (c)  2008 Martin Holtz
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
*/


class tx_exiftool_tcemainprocdm
{

	/**
	 * this function should remove or escape all characters,
	 * which could lead to an misbehavior in the exec-call
	 *
	 * atm. it will strip some chars
	 */
	function sanitize($input) {
		$sanitized = str_replace(array('\'','´','`'), array('','',''), $input);
		return $sanitized;
	}
	
	function processDatamap_postProcessFieldArray ($status, $table, $id, &$fieldArray, &$reference)
	{
		global $FILEMOUNTS, $BE_USER, $TYPO3_CONF_VARS;
		$this->extKey = 'exiftool';
// TODO: take care of UTF-8/latin1 conversion if needed - exiftool param: -L
		if ($table != 'tx_dam') {
			return 0;
		}
		if ($status != 'update') {
			return 0;
		}
		$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['exiftool']);
		if (1 != $config['writeMetaData']) {
			if (1 == $config['debug'])  { t3lib_div::devLog('Write MetaData is not enabled - check extension configuration: "writeMetaData"',$this->extKey,1,$config); }
			return 0;
		}
		if ((int)$config['mediapid'] <= 0) {
			// TODO: Debug-Message
			t3lib_div::devLog('PID of Media Record is not configured - check extension configuration: "mediapid"',$this->extKey,3,$config); 
			return 0;
		}

		// TODO: check if this assumtion is right - especially for fe-editing!
		$absolutePath = dirname(dirname($GLOBALS['_SERVER']['SCRIPT_FILENAME']));
		if (1 == $config['debug'])  { t3lib_div::devLog('assumption: absolutpath is: '.$absolutePath,$this->extKey,-1,array()); }
		$file = $absolutePath.'/'.$reference->checkValue_currentRecord['file_path'].$reference->checkValue_currentRecord['file_name'];

		$this->info['exec'] = 'perl';

		$page_id = $config['mediapid'];
		$this->service_conf = t3lib_BEfunc::getModTSconfig($page_id,'tx_exiftool_sv1');
		if (1 == $config['debug'])  { t3lib_div::devLog('service configuration',$this->extKey,-1,$this->service_conf);}
		$this->info['params'] = ' '.t3lib_extMgm::extPath('exiftool').'exiftool/exiftool ';
		$match = $this->service_conf['properties']['match.'];

		$params = array(); // Array for all params

		if (!is_array($match)) {
			t3lib_div::devLog('configuration is missing. Please check page-ts config of media folder',$this->extKey,3,$this->service_conf);
			return 0;
		}
		// Example:
		// keywords.tag.1 = -keywords
        // keywords.tag.1.splitToken = ,
		foreach ($match as $field => $tagConfiguration) {
			if (!isset($tagConfiguration['tag.'])) {
				continue;
			}
			t3lib_div::devLog('Check '.$field,$this->extKey,3,$tagConfiguration);
			$fieldName = mb_substr($field,0,-1); // strip of the last dot 'keywords.'
			if (!isset($fieldArray[$fieldName]) && !isset($tagConfiguration['tag.']['ignoreUnchangedValue'])) {
				// we get only the changed values, so we should only write the changed values
				continue;
			}
			$i = 1;
			while (isset($tagConfiguration['tag.'][(string)$i])) {
				$tmpparams = ' '.$tagConfiguration['tag.'][(string)$i].'=\''.$this->sanitize($fieldArray[$fieldName]).'\' ';
				// splitToken is used
				if (isset($tagConfiguration['tag.'][(string)$i.'.']['splitToken'])) {
					// special handling
					$list = t3lib_div::trimExplode($tagConfiguration['tag.'][(string)$i.'.']['splitToken'], $fieldArray[$fieldName]);
					if (!is_array($list)) {
						// empty list? should be written too
						// we can use the default configuration for that
						$params[] = $tmpparams;
						$i++;
						continue;
					}
					$tmpparams = '';
					foreach ($list as $listitem) {
						$tmpparams .= ' '.$tagConfiguration['tag.'][(string)$i].'=\''.$this->sanitize($listitem).'\' ';
					}
					$params[] = $tmpparams;
					$i++;
					t3lib_div::devLog('Check (keywords) '.$field,$this->extKey,3,$tmpparams);
					continue;
				}
				// lookUpCategory  is used
				if (isset($tagConfiguration['tag.'][(string)$i.'.']['lookUpCategory'])) {

					$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query('tx_dam_cat.title',
							'tx_dam',
							'tx_dam_mm_cat',
							'tx_dam_cat',
							' AND tx_dam_mm_cat.uid_local = '.(int)$id,
							'',
							'',
							''
						);
					$tmpparams = '';
					while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
						$tmpparams .= ' '.$tagConfiguration['tag.'][(string)$i].'=\''.$this->sanitize($row['title']).'\' ';
					}
				}

				$i++;
				$params[] = $tmpparams;
			}
		}



		$t3lib_cs = t3lib_div::makeInstance("t3lib_cs");
		$this->service_conf['properties']['fileCharset'] = $t3lib_cs->parse_charset($this->service_conf['properties']['fileCharset']);
		// check if charset is known by TYPO3
		if (false === array_search($this->service_conf['properties']['fileCharset'], $t3lib_cs->synonyms)) {
			// TODO: error handling
			t3lib_div::devLog('Wrong character set! '.$this->service_conf['properties']['fileCharset'].' is unknown. Forcing to utf-8.',$this->extKey,2,$this->service_conf);
			$this->service_conf['properties']['fileCharset'] = 'utf-8';
			
		}
		t3lib_div::devLog('convert from '.$this->service_conf['properties']['dbCharset'].' to '.$this->service_conf['properties']['fileCharset'], $this->extKey,2,$this->service_conf['properties']);
		t3lib_div::devLog('params before conversion '.count($params),$this->extKey,2,$params);
		$t3lib_cs->convArray($params, $this->service_conf['properties']['dbCharset'], $this->service_conf['properties']['fileCharset'], true);
		t3lib_div::devLog('params after conversion '.count($params),$this->extKey,2,$params);
		$this->info['params'] .= ' '.implode(' ',$params).' ';

		$cmd = t3lib_exec::getCommand($this->info['exec']).$this->info['params'].' "'.$file.'" ';
		$output = array();
		$ret = -1;
		exec($cmd, $output, $ret);
		if (1 == $config['debug'])  { t3lib_div::devLog('exec output. cmd was: '.$cmd,$this->extKey,-1,array('output' => $output, 'ret' => $ret));}
	}

}

?>