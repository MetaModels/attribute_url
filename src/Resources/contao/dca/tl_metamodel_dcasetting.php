<?php

/**
 * This file is part of MetaModels/attribute_url.
 *
 * (c) 2012-2023 The MetaModels team.
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
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2023 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

use Contao\System;
use MetaModels\ContaoFrontendEditingBundle\MetaModelsContaoFrontendEditingBundle;

$GLOBALS['TL_DCA']['tl_metamodel_dcasetting']['metasubselectpalettes']['attr_id']['url'] = [
    'presentation' => [
        'tl_class',
        'be_template',
    ],
    'functions'  => [
        'mandatory',
    ]
];

// Load configuration for the frontend editing.
if (\in_array(
    MetaModelsContaoFrontendEditingBundle::class,
    System::getContainer()->getParameter('kernel.bundles'),
    true
)) {
    $GLOBALS['TL_DCA']['tl_metamodel_dcasetting']['metasubselectpalettes']['attr_id']['url']['presentation'][] =
        'fe_template';
}
