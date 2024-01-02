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
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeUrlBundle\Test\DependencyInjection;

use MetaModels\AttributeUrlBundle\Attribute\AttributeTypeFactory;
use MetaModels\AttributeUrlBundle\DcGeneral\Events\UrlWizardHandler;
use MetaModels\AttributeUrlBundle\DependencyInjection\MetaModelsAttributeUrlExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * This test case test the extension.
 *
 * @covers \MetaModels\AttributeUrlBundle\DependencyInjection\MetaModelsAttributeUrlExtension
 */
class MetaModelsAttributeUrlExtensionTest extends TestCase
{
    public function testInstantiation(): void
    {
        $extension = new MetaModelsAttributeUrlExtension();

        self::assertInstanceOf(MetaModelsAttributeUrlExtension::class, $extension);
        self::assertInstanceOf(ExtensionInterface::class, $extension);
    }

    public function testFactoryIsRegistered(): void
    {
        $container = new ContainerBuilder();

        $extension = new MetaModelsAttributeUrlExtension();
        $extension->load([], $container);

        self::assertTrue($container->hasDefinition('metamodels.attribute_url.factory'));
        $definition = $container->getDefinition('metamodels.attribute_url.factory');
        self::assertCount(1, $definition->getTag('metamodels.attribute_factory'));

        self::assertTrue($container->hasDefinition('metamodels.attribute_url.factory.container'));
        $definition = $container->getDefinition('metamodels.attribute_url.factory.container');
        self::assertCount(1, $definition->getTag('container.service_locator'));

        self::assertTrue($container->hasDefinition(UrlWizardHandler::class));
        $definition = $container->getDefinition(UrlWizardHandler::class);
        self::assertCount(1, $definition->getTag('kernel.event_listener'));
    }
}
