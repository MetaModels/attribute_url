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
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @author     Oliver Hoff <oliver@hofff.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\Attribute\Url;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ManipulateWidgetEvent;
use MetaModels\Attribute\BaseSimple;
use MetaModels\DcGeneral\Events\UrlWizardHandler;

/**
 * This is the MetaModelAttribute class for handling urls.
 */
class Url extends BaseSimple
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDataType()
    {
        return 'blob NULL';
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeSettingNames()
    {
        return \array_merge(
            parent::getAttributeSettingNames(),
            [
                'no_external_link',
                'mandatory',
                'trim_title'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function valueToWidget($varValue)
    {
        if ($this->get('trim_title') && is_array($varValue)) {
            $varValue = $varValue[1];
        }

        if ($varValue === null) {
            $varValue = $this->get('trim_title') ? null : [0 => '', 1 => ''];
        }

        return parent::valueToWidget($varValue);
    }

    /**
     * {@inheritdoc}
     */
    public function widgetToValue($varValue, $intId)
    {
        if ($this->get('trim_title') && !is_array($varValue)) {
            $varValue = [0 => '', 1 => $varValue];
        }

        if (($this->get('trim_title') && empty($varValue[1])) ||
            (!$this->get('trim_title') && empty($varValue[0]) && empty($varValue[1]))
        ) {
            $varValue = null;
        }

        return parent::widgetToValue($varValue, $intId);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldDefinition($arrOverrides = [])
    {
        $arrFieldDef = parent::getFieldDefinition($arrOverrides);

        $arrFieldDef['inputType'] = 'text';
        if (!isset($arrFieldDef['eval']['tl_class'])) {
            $arrFieldDef['eval']['tl_class'] = '';
        }
        $arrFieldDef['eval']['tl_class'] .= ' wizard inline';

        if (!$this->get('trim_title')) {
            $arrFieldDef['eval']['size']      = 2;
            $arrFieldDef['eval']['multiple']  = true;
            $arrFieldDef['eval']['tl_class'] .= ' metamodelsattribute_url';
        }

        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
        $dispatcher = $this->getMetaModel()->getServiceContainer()->getEventDispatcher();
        $dispatcher->addListener(
            ManipulateWidgetEvent::NAME,
            [new UrlWizardHandler($this->getMetaModel(), $this->getColName()), 'getWizard']
        );

        return $arrFieldDef;
    }

    /**
     * Unserialize the value from the database if possible, return the value as is otherwise.
     *
     * @param mixed $value The array of data from the database.
     *
     * @return array
     */
    public function unserializeData($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (substr($value, 0, 2) == 'a:') {
            return unserialize($value);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function serializeData($value)
    {
        return is_array($value) ? serialize($value) : $value;
    }
}
