<?php

/**
 * This file is part of MetaModels/attribute_url.
 *
 * (c) 2012-2021 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_url
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2021 The MetaModels team.
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
 * @covers \MetaModels\AttributeUrlBundle\DependencyInjection\MetaModelsAttributeRatingExtension
 */
class MetaModelsAttributeUrlExtensionTest extends TestCase
{
    /**
     * Test that extension can be instantiated.
     *
     * @return void
     */
    public function testInstantiation()
    {
        $extension = new MetaModelsAttributeUrlExtension();

        $this->assertInstanceOf(MetaModelsAttributeUrlExtension::class, $extension);
        $this->assertInstanceOf(ExtensionInterface::class, $extension);
    }

    /**
     * Test that the services are loaded.
     *
     * @return void
     */
    public function testFactoryIsRegistered()
    {
        $container = $this->getMockBuilder(ContainerBuilder::class)->getMock();

        $container
            ->expects($this->exactly(3))
            ->method('setDefinition')
            ->withConsecutive(
                [
                    'metamodels.attribute_url.factory',
                    $this->callback(
                        function ($value) {
                            /** @var Definition $value */
                            $this->assertInstanceOf(Definition::class, $value);
                            $this->assertEquals(AttributeTypeFactory::class, $value->getClass());
                            $this->assertCount(1, $value->getTag('metamodels.attribute_factory'));

                            return true;
                        }
                    )
                ],
                [
                    'metamodels.attribute_url.factory.container',
                    $this->callback(
                        function ($value) {
                            /** @var Definition $value */
                            $this->assertInstanceOf(Definition::class, $value);
                            $this->assertEquals(ServiceLocator::class, $value->getClass());
                            $this->assertCount(1, $value->getTag('container.service_locator'));

                            return true;
                        }
                    )
                ],
                [
                    UrlWizardHandler::class,
                    $this->callback(
                        function ($value) {
                            /** @var Definition $value */
                            $this->assertInstanceOf(Definition::class, $value);
                            $this->assertCount(1, $value->getTag('kernel.event_listener'));

                            return true;
                        }
                    )
                ]
            );

        $extension = new MetaModelsAttributeUrlExtension();
        $extension->load([], $container);
    }
}
