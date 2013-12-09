<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package    MetaModels
 * @subpackage AttributeUrl
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\Helper\Url;

use DcGeneral\DataDefinition\ContainerInterface;
use MetaModels\Helper\ContaoController;

/**
 * This is the MetaModelAttribute class for handling urls.
 *
 * @package    MetaModels
 * @subpackage AttributeUrl
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 */
class Url
{
	protected static $objInstance = null;

	protected function __construct()
	{
	}

	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new self();
		}

		return self::$objInstance;
	}

	/**
	 * Return the page picker wizard
	 *
	 * ToDo: We should add the right interface here.
	 * @param \DcGeneral\DataDefinition\ContainerInterface $dc
	 *
	 * @return string
	 */
	public function singlePagePicker($dc)
	{
        	$strField = 'ctrl_' . $dc->inputName;
        	if (version_compare(VERSION, '3.0', '<')){
            		return ' ' . ContaoController::getInstance()->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer" onclick="Backend.pickPage(\'' . $strField . '\')"');
        	} else {
            		return '<a href="contao/page.php?table='.$dc->table.'&field='.$dc->field.'&value='.str_replace(array('{{link_url::', '}}'), '', $dc->getEnvironment()->getCurrentModel()->getProperty($dc->field)).'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\''.specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']).'\',\'url\':this.href,\'id\':\''.$dc->field.'\',\'tag\':\''.$strField.'\',\'self\':this});return false"> '.\Image::getHtml('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"').'</a>';
        	}
	}

	/**
	 * Return the page picker wizard
	 *
	 * ToDo: We should add the right interface here.
	 * @param \DcGeneral\DataDefinition\ContainerInterface $dc
	 *
	 * @return string
	 */
	public function multiPagePicker($dc)
	{
		$strField = 'ctrl_' . $dc->inputName . '_1';
		return ' ' . ContaoController::getInstance()->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer" onclick="Backend.pickPage(\'' . $strField . '\')"');
	}

}
