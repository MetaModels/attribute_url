<?php

/**
 * This file is part of MetaModels/attribute_url.
 *
 * (c) 2012-2016 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage AttributeUrl
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @copyright  2012-2016 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0
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
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_rendersetting']['no_external_link'],
    'inputType' => 'checkbox',
);
