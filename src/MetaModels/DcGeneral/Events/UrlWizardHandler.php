<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package    MetaModels
 * @subpackage Core
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The MetaModels team.
 * @license    LGPL-3+.
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
	 *
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
		)
		{
			return;
		}

		$propName   = $event->getProperty()->getName();
		$model      = $event->getModel();
		$inputId    = $propName . (!$this->metaModel->getAttribute($this->propertyName)->get('trim_title') ? '_1' : '');
		$translator = $event->getEnvironment()->getTranslator();

		$GLOBALS['TL_CSS']['metamodelsattribute_url'] = 'system/modules/metamodelsattribute_url/html/style.css';
		if (version_compare(VERSION, '3.1', '>='))
		{
			$currentField = deserialize($model->getProperty($propName), true);

			/** @var GenerateHtmlEvent $imageEvent */
			$imageEvent = $event->getEnvironment()->getEventPropagator()->propagate(
				ContaoEvents::IMAGE_GET_HTML,
				new GenerateHtmlEvent(
					'pickpage.gif',
					$translator->translate('pagepicker', 'MSC'),
					'style="vertical-align:top;cursor:pointer"'
				)
			);

			$event->getWidget()->wizard =  ' <a href="contao/page.php?do=' . \Input::get('do') .
				'&amp;table=' . $this->metaModel->getTableName() . '&amp;field=' . $inputId .
				'&amp;value=' . str_replace(array('{{link_url::', '}}'), '', $currentField[1]) . '" title="' .
				specialchars($translator->translate('pagepicker', 'MSC')) .
				'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'' .
				specialchars(str_replace("'", "\\'", $translator->translate('page.0', 'MOD'))) .
				'\',\'url\':this.href,\'id\':\'' . $inputId . '\',\'tag\':\'ctrl_' .$inputId . '\',\'self\':this});return false">' .
				$imageEvent->getHtml() . '</a>';

			return;
		}

		/** @var GenerateHtmlEvent $imageEvent */
		$imageEvent = $event->getEnvironment()->getEventPropagator()->propagate(
			ContaoEvents::IMAGE_GET_HTML,
			new GenerateHtmlEvent(
				'pickpage.gif',
				$translator->translate('pagepicker', 'MSC'),
				'style="vertical-align:top;cursor:pointer" onclick="Backend.pickPage(\'ctrl_' . $inputId . '\')"'
			)
		);

		$event->getWidget()->wizard =  ' ' . $imageEvent->getHtml();
	}
}
