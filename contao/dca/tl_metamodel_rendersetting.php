<?php

/**
 * This file is part of MetaModels/attribute_url.
 *
 * (c) 2012-2019 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_url
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

/**
 * Table tl_metamodel_rendersetting
 */

$GLOBALS['TL_DCA']['tl_metamodel_rendersetting']['metapalettes']['url extends default'] = [
    '+advanced' => ['no_external_link'],
];

$GLOBALS['TL_DCA']['tl_metamodel_rendersetting']['fields']['no_external_link'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_rendersetting']['no_external_link'],
    'inputType' => 'checkbox',
    'sql'       => 'char(1) NOT NULL default \'\'',
];
