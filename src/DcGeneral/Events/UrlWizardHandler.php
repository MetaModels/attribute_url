<?php

/**
 * This file is part of MetaModels/attribute_url.
 *
 * (c) 2012-2018 The MetaModels team.
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
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2018 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\AttributeUrlBundle\DcGeneral\Events;

use Contao\StringUtil;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ManipulateWidgetEvent;
use MetaModels\AttributeUrlBundle\Attribute\Url;

/**
 * This class adds the file picker wizard to the file picker widgets if necessary.
 *
 * @package MetaModels\DcGeneral\Events
 */
class UrlWizardHandler
{
    /**
     * The name of the attribute of the MetaModel this handler should react on.
     *
     * @var string[][]
     */
    private $propertyNames = [];

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
        dump($attribute);

        $model      = $event->getModel();
        $inputId    = $propName . (!$attribute->get('trim_title') ? '_1' : '');
        $translator = $event->getEnvironment()->getTranslator();

        $this->addStylesheet('metamodelsattribute_url', 'bundles/metamodelsattributeurl/style.css');

        $currentField = \deserialize($model->getProperty($propName), true);

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
                                      '&amp;table=' . $tableName . '&amp;field=' . $inputId .
                                      '&amp;value=' . str_replace(['{{link_url::', '}}'], '', $currentField[1])
                                      . '" title="' .
                                      StringUtil::specialchars($translator->translate('pagepicker', 'MSC')) .
                                      '" onclick="Backend.getScrollOffset();'.
                                      'Backend.openModalSelector({\'width\':765,\'title\':\'' .
                                      StringUtil::specialchars(
                                          str_replace("'", "\\'", $translator->translate('page.0', 'MOD'))
                                      ) .
                                      '\',\'url\':this.href,\'id\':\'' . $inputId . '\',\'tag\':\'ctrl_' . $inputId
                                      . '\',\'self\':this});' .
                                      'return false">' . $imageEvent->getHtml() . '</a>';
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
