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
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @copyright  2012-2016 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\DcGeneral\Events;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ManipulateWidgetEvent;
use MetaModels\IMetaModel;

/**
 * This class adds the file picker wizard to the file picker widgets if necessary.
 *
 * @package MetaModels\DcGeneral\Events
 */
class UrlWizardHandler
{
    /**
     * The MetaModel instance this handler should react on.
     *
     * @var IMetaModel
     */
    protected $metaModel;

    /**
     * The name of the attribute of the MetaModel this handler should react on.
     *
     * @var string
     */
    protected $propertyName;

    /**
     * Create a new instance.
     *
     * @param IMetaModel $metaModel    The MetaModel instance.
     * @param string     $propertyName The name of the property.
     */
    public function __construct($metaModel, $propertyName)
    {
        $this->metaModel    = $metaModel;
        $this->propertyName = $propertyName;
    }

    /**
     * Build the wizard string.
     *
     * @param ManipulateWidgetEvent $event The event.
     *
     * @return void
     */
    public function getWizard(ManipulateWidgetEvent $event)
    {
        if ($event->getModel()->getProviderName() !== $this->metaModel->getTableName()
            || $event->getProperty()->getName() !== $this->propertyName
        ) {
            return;
        }

        $propName   = $event->getProperty()->getName();
        $model      = $event->getModel();
        $inputId    = $propName . (!$this->metaModel->getAttribute($this->propertyName)->get('trim_title') ? '_1' : '');
        $translator = $event->getEnvironment()->getTranslator();

        $this->addStylesheet('metamodelsattribute_url', 'system/modules/metamodelsattribute_url/html/style.css');

        if (version_compare(VERSION, '3.1', '>=')) {
            $currentField = deserialize($model->getProperty($propName), true);

            /** @var GenerateHtmlEvent $imageEvent */
            $imageEvent = $event->getEnvironment()->getEventDispatcher()->dispatch(
                ContaoEvents::IMAGE_GET_HTML,
                new GenerateHtmlEvent(
                    'pickpage.gif',
                    $translator->translate('pagepicker', 'MSC'),
                    'style="vertical-align:top;cursor:pointer"'
                )
            );

            $event->getWidget()->wizard = ' <a href="contao/page.php?do=' . \Input::get('do') .
                '&amp;table=' . $this->metaModel->getTableName() . '&amp;field=' . $inputId .
                '&amp;value=' . str_replace(array('{{link_url::', '}}'), '', $currentField[1]) . '" title="' .
                specialchars($translator->translate('pagepicker', 'MSC')) .
                '" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'' .
                specialchars(str_replace("'", "\\'", $translator->translate('page.0', 'MOD'))) .
                '\',\'url\':this.href,\'id\':\'' . $inputId . '\',\'tag\':\'ctrl_' . $inputId . '\',\'self\':this});' .
                'return false">' . $imageEvent->getHtml() . '</a>';

            return;
        }

        /** @var GenerateHtmlEvent $imageEvent */
        $imageEvent = $event->getEnvironment()->getEventDispatcher()->dispatch(
            ContaoEvents::IMAGE_GET_HTML,
            new GenerateHtmlEvent(
                'pickpage.gif',
                $translator->translate('pagepicker', 'MSC'),
                'style="vertical-align:top;cursor:pointer" onclick="Backend.pickPage(\'ctrl_' . $inputId . '\')"'
            )
        );

        $event->getWidget()->wizard = ' ' . $imageEvent->getHtml();
    }

    /**
     * Add the stylesheet to the backend.
     *
     * @param string $name Name The name-key of the file.
     * @param string $file File The filepath on the filesystem.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     *
     * @return void
     */
    protected function addStylesheet($name, $file)
    {
        $GLOBALS['TL_CSS'][$name] = $file;
    }
}
