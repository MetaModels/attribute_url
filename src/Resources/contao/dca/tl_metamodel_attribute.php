<?php

/**
 * This file is part of MetaModels/attribute_url.
 *
 * (c) 2012-2024 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_url
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

/**
 * Table tl_metamodel_attribute
 */

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['metapalettes']['url extends _simpleattribute_'] = [
    '+display' => ['trim_title']
];

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['trim_title'] = [
    'label'       => 'trim_title.label',
    'description' => 'trim_title.description',
    'exclude'     => true,
    'inputType'   => 'checkbox',
    'sql'         => 'char(1) NOT NULL default \'\'',
    'eval'        => ['tl_class' => 'clr']
];
