<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 Benoit Norrin <info@dlcube.com>
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

	function processDatamap_postProcessFieldArray ($status, $table, $id, &$fieldArray, &$reference)
	{
		global $FILEMOUNTS, $BE_USER, $TYPO3_CONF_VARS;
// TODO: take care of UTF-8/latin1 conversion if needed - exiftool param: -L
		if ($table == 'tx_dam') {
			if ($status == 'update') {
				$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['exiftool']);
t3lib_div::debug($config);
				if (1 != $config['writeMetaData']) {
					// TODO: Debug-Message
					return 0;
				}
				if ((int)$config['mediapid'] <= 0) {
					// TODO: Debug-Message
					return 0;
				}

				// t3lib_div::debug(dirname(dirname($GLOBALS['_SERVER']['SCRIPT_FILENAME'])));
				// TODO: check if this assumtion is right - especially for fe-editing!
				$absolutePath = dirname(dirname($GLOBALS['_SERVER']['SCRIPT_FILENAME']));

				// t3lib_div::debug($id);
				// Änderungen: t3lib_div::debug($fieldArray);
				$file = $absolutePath.'/'.$reference->checkValue_currentRecord['file_path'].$reference->checkValue_currentRecord['file_name'];
// t3lib_div::debug($file);
				// checkValue_currentRecord
				// t3lib_div::debug($reference);
				// [datamap][tx_dam][$id]
				$this->info['exec'] = 'perl';

				// TODO: read from configuration
				$page_id = $config['mediapid'];
				$this->service_conf = t3lib_BEfunc::getModTSconfig($page_id,'tx_exiftool_sv1');
// t3lib_div::debug($this->service_conf);
				$this->info['params'] = ' '.t3lib_extMgm::extPath('exiftool').'exiftool/exiftool ';
				$match = $this->service_conf['properties']['match.'];

				$params = array(); // Array for all params
				// Example:
				// keywords.tag.1 = -keywords
        		// keywords.tag.1.splitToken = ,
				foreach ($match as $field => $tagConfiguration) {
					if (!isset($tagConfiguration['tag.'])) {
						continue;
					}
					$fieldName = mb_substr($field,0,-1); // strip of the last dot 'keywords.'
					if (!isset($fieldArray[$fieldName])) {
						// we get only the changed values, so we should only write the changed values
						continue;
					}
					$i = 1;
					while (isset($tagConfiguration['tag.'][(string)$i])) {
						$tmpparams = ' '.$tagConfiguration['tag.'][(string)$i].'=\''.$fieldArray[$fieldName].'\' ';
						if (isset($tagConfiguration['tag.'][(string)$i.'.']['splitToken'])) {
							// special handling
							$list = t3lib_div::trimExplode($tagConfiguration['tag.'][(string)$i.'.']['splitToken'], $fieldArray[$fieldName]);
// t3lib_div::debug($list);
							if (!is_array($list)) {
								// empty list? should be written too
								// we can use the default configuration for that
								$params[] = $tmpparams;
								continue;
							}
							$tmpparams = '';
							foreach ($list as $listitem) {
								$tmpparams .= ' '.$tagConfiguration['tag.'][(string)$i].'=\''.$listitem.'\' ';
							}
						}
						$i++;
						$params[] = $tmpparams;
					}
				}

				$this->info['params'] .= ' '.implode(' ',$params).' ';
				// $this->info['params'] .= ' -keywords=\''.addslashes($fieldArray['keywords']).'\'';
				$cmd = t3lib_exec::getCommand($this->info['exec']).$this->info['params'].' '.$file.'';
				$output = array();
				$ret = -1;
				exec($cmd, $output, $ret);
				// t3lib_div::debug($cmd);

			}
		}
		/*

  [historyRecords] => Array
        (
            [tx_dam:3] => Array
                (
                    [oldRecord] => Array
                        (
                            [keywords] => test93
                        )

                    [newRecord] => Array
                        (
                            [keywords] => test94
                        )

                )

        )


		 */


	}

}

?>