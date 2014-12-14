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

/**
 * Table tl_metamodel_rendersetting
 */

$GLOBALS['TL_DCA']['tl_metamodel_rendersetting']['metapalettes']['url extends default'] = array
(
	'+advanced' => array('no_external_link'),
);

$GLOBALS['TL_DCA']['tl_metamodel_rendersetting']['fields']['no_external_link'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_metamodel_rendersetting']['no_external_link'],
	'inputType'               => 'checkbox',
);
