<?php
/**
 * This file is part of the browscap package.
 *
 * Copyright (c) 1998-2017, Browser Capabilities Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace BrowscapTest\Data\Factory;

use Browscap\Data\Device;
use Browscap\Data\Factory\DeviceFactory;

/**
 * Class DeviceFactoryTest
 *
 * @category   BrowscapTest
 *
 * @author     James Titcumb <james@asgrim.com>
 */
class DeviceFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Browscap\Data\Factory\DeviceFactory
     */
    private $object;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp() : void
    {
        $this->object = new DeviceFactory();
    }

    /**
     * tests the creating of an engine factory
     *
     * @group data
     * @group sourcetest
     */
    public function testBuildWithoutStandardProperty() : void
    {
        $this->expectException('\UnexpectedValueException');
        $this->expectExceptionMessage('the value for "standard" key is missing for device "Test"');

        $deviceData = ['abc' => 'def'];
        $json       = [];
        $deviceName = 'Test';

        $this->object->build($deviceData, $json, $deviceName);
    }

    /**
     * tests the creating of an engine factory
     *
     * @group data
     * @group sourcetest
     */
    public function testBuildWithMissingParent() : void
    {
        $this->expectException('\UnexpectedValueException');
        $this->expectExceptionMessage('parent Device "abc" is missing for device "Test"');

        $deviceData = ['abc' => 'def', 'standard' => false, 'inherits' => 'abc'];
        $json       = [];
        $deviceName = 'Test';

        $this->object->build($deviceData, $json, $deviceName);
    }

    /**
     * tests the creating of an engine factory
     *
     * @group data
     * @group sourcetest
     */
    public function testBuildWithRepeatingProperties() : void
    {
        $this->expectException('\UnexpectedValueException');
        $this->expectExceptionMessage('the value for property "abc" has the same value in the keys "Test" and its parent "abc"');

        $deviceData = ['properties' => ['abc' => 'def'], 'standard' => true, 'inherits' => 'abc'];
        $json       = [
            'devices' => [
                'abc' => [
                    'properties' => ['abc' => 'def'],
                    'standard' => false,
                ],
            ],
        ];
        $deviceName = 'Test';

        $this->object->build($deviceData, $json, $deviceName);
    }

    /**
     * tests the creating of an engine factory
     *
     * @group data
     * @group sourcetest
     */
    public function testBuild() : void
    {
        $deviceData = ['abc' => 'def', 'standard' => false];
        $json       = [];
        $deviceName = 'Test';

        self::assertInstanceOf(Device::class, $this->object->build($deviceData, $json, $deviceName));
    }

    /**
     * tests the creating of an engine factory
     *
     * @group data
     * @group sourcetest
     */
    public function testBuildOk() : void
    {
        $deviceData = ['properties' => ['abc' => 'xyz'], 'standard' => true, 'inherits' => 'abc'];
        $json       = [
            'devices' => [
                'abc' => [
                    'properties' => ['abc' => 'def'],
                    'standard' => false,
                ],
            ],
        ];
        $deviceName = 'Test';

        $device = $this->object->build($deviceData, $json, $deviceName);
        self::assertInstanceOf(Device::class, $device);
        self::assertFalse($device->isStandard());
    }
}
