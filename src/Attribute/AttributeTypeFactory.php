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
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeUrlBundle\Attribute;

use Doctrine\DBAL\Connection;
use MetaModels\Attribute\IAttributeTypeFactory;
use MetaModels\AttributeUrlBundle\DcGeneral\Events\UrlWizardHandler;
use MetaModels\Helper\TableManipulator;
use Psr\Container\ContainerInterface;

/**
 * Attribute type factory for translated url attributes.
 */
class AttributeTypeFactory implements IAttributeTypeFactory
{
    /**
     * The database connection.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeName()
    {
        return 'url';
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeIcon()
    {
        return 'bundles/metamodelsattributeurl/url.png';
    }

    /**
     * {@inheritDoc}
     */
    public function createInstance($information, $metaModel)
    {
        $attribute = new Url(
            $metaModel,
            $information,
            $this->container->get(Connection::class),
            $this->container->get(TableManipulator::class)
        );

        $this->container->get(UrlWizardHandler::class)->watch($attribute);

        return $attribute;
    }

    /**
     * {@inheritDoc}
     */
    public function isTranslatedType()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isSimpleType()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isComplexType()
    {
        return false;
    }
}
