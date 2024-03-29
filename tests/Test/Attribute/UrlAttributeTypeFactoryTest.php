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
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2021 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_url/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeUrlBundle\Test\Attribute;

use Doctrine\DBAL\Connection;
use MetaModels\Attribute\IAttributeTypeFactory;
use MetaModels\AttributeUrlBundle\Attribute\AttributeTypeFactory;
use MetaModels\AttributeUrlBundle\Attribute\Url;
use MetaModels\AttributeUrlBundle\DcGeneral\Events\UrlWizardHandler;
use MetaModels\Helper\TableManipulator;
use MetaModels\IMetaModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Test the attribute factory
 *
 * @covers \MetaModels\AttributeUrlBundle\Attribute\AttributeTypeFactory.
 */
class UrlAttributeTypeFactoryTest extends TestCase
{
    /**
     * Mock a MetaModel.
     *
     * @param string $tableName        The table name.
     *
     * @param string $language         The language.
     *
     * @param string $fallbackLanguage The fallback language.
     *
     * @return IMetaModel
     */
    protected function mockMetaModel($tableName, $language, $fallbackLanguage)
    {
        $metaModel = $this->getMockForAbstractClass(IMetaModel::class);

        $metaModel
            ->expects(self::any())
            ->method('getTableName')
            ->willReturn($tableName);

        $metaModel
            ->expects(self::any())
            ->method('getActiveLanguage')
            ->willReturn($language);

        $metaModel
            ->expects(self::any())
            ->method('getFallbackLanguage')
            ->willReturn($fallbackLanguage);

        return $metaModel;
    }

    /**
     * Mock the database connection.
     *
     * @return MockObject|Connection
     */
    private function mockConnection()
    {
        return $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Mock the table manipulator.
     *
     * @param Connection $connection The database connection mock.
     *
     * @return TableManipulator|MockObject
     */
    private function mockTableManipulator(Connection $connection)
    {
        return $this->getMockBuilder(TableManipulator::class)
            ->setConstructorArgs([$connection, []])
            ->getMock();
    }

    /**
     * Override the method to run the tests on the attribute factories to be tested.
     *
     * @return IAttributeTypeFactory[]
     */
    protected function getAttributeFactories()
    {
        $container   = new Container();
        $connection  = $this->mockConnection();
        $manipulator = $this->mockTableManipulator($connection);

        $container->set(Connection::class, $connection);
        $container->set(TableManipulator::class, $manipulator);
        $container->set(UrlWizardHandler::class, new UrlWizardHandler());

        return [new AttributeTypeFactory($container)];
    }

    /**
     * Test creation of an url attribute.
     *
     * @return void
     */
    public function testCreateUrl()
    {
        $container   = new Container();
        $connection  = $this->mockConnection();
        $manipulator = $this->mockTableManipulator($connection);

        $container->set(Connection::class, $connection);
        $container->set(TableManipulator::class, $manipulator);
        $container->set(UrlWizardHandler::class, new UrlWizardHandler());

        $factory   = new AttributeTypeFactory($container);
        $values    = ['colname' => 'test'];
        $attribute = $factory->createInstance(
            $values,
            $this->mockMetaModel('mm_test', 'de', 'en')
        );

        self::assertInstanceOf(Url::class, $attribute);

        foreach ($values as $key => $value) {
            self::assertEquals($value, $attribute->get($key), $key);
        }
    }
}
