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
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeUrlBundle\DcGeneral\Events;

use Contao\StringUtil;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ManipulateWidgetEvent;
use ContaoCommunityAlliance\Translator\TranslatorInterface;
use MetaModels\AttributeUrlBundle\Attribute\Url;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class adds the file picker wizard to the file picker widgets if necessary.
 */
class UrlWizardHandler
{
    /**
     * The name of the attribute of the MetaModel this handler should react on.
     *
     * @var array<string, array<string, Url>>
     */
    private array $propertyNames = [];

    /**
     * Register an attribute
     *
     * @param Url $attribute The attribute.
     *
     * @return void
     */
    public function watch(Url $attribute)
    {
        $this->propertyNames[$attribute->getMetaModel()->getTableName()][$attribute->getColName()] = $attribute;
    }

    /**
     * Build the wizard string.
     *
     * @param ManipulateWidgetEvent $event The event.
     *
     * @return void
     */
    public function __invoke(ManipulateWidgetEvent $event)
    {
        $tableName = $event->getModel()->getProviderName();
        $propName  = $event->getProperty()->getName();

        if (!isset($this->propertyNames[$tableName][$propName])) {
            return;
        }
        /** @var Url $attribute */
        $attribute = $this->propertyNames[$tableName][$propName];

        $model      = $event->getModel();
        $inputId    = $propName . (!$attribute->get('trim_title') ? '_1' : '');
        $translator = $event->getEnvironment()->getTranslator();
        assert($translator instanceof TranslatorInterface);

        $this->addStylesheet('metamodelsattribute_url', 'bundles/metamodelsattributeurl/style.css');

        $currentField = StringUtil::deserialize($model->getProperty($propName), true);

        $dispatcher = $event->getEnvironment()->getEventDispatcher();
        assert($dispatcher instanceof EventDispatcherInterface);
        /** @var GenerateHtmlEvent $imageEvent */
        $imageEvent = $dispatcher->dispatch(
            new GenerateHtmlEvent(
                'pickpage.svg',
                $translator->translate('pagepicker', 'MSC'),
                'style="vertical-align:text-bottom;cursor:pointer;width:20px;height:20px;"'
            ),
            ContaoEvents::IMAGE_GET_HTML
        );

        $event->getWidget()->wizard = ' <a href="contao/page.php?do=' . \Input::get('do') .
                                      '&amp;table=' . $tableName . '&amp;field=' . $inputId .
                                      '&amp;value=' . str_replace(['{{link_url::', '}}'], '', ($currentField[1] ?? ''))
                                      . '" title="' .
                                      StringUtil::specialchars($translator->translate('pagepicker', 'MSC')) .
                                      '" onclick="Backend.getScrollOffset();' .
                                      'Backend.openModalSelector({\'width\':765,\'title\':\'' .
                                      StringUtil::specialchars(
                                          str_replace("'", "\\'", $translator->translate('page.0', 'MOD'))
                                      ) .
                                      '\',\'url\':this.href,\'id\':\'' . $inputId . '\',\'tag\':\'ctrl_' . $inputId
                                      . '\',\'self\':this});' .
                                      'return false">' . ($imageEvent->getHtml() ?? '') . '</a>';
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
