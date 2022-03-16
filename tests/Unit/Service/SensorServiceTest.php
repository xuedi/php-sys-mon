<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\Configuration\Configuration;
use Xuedi\PhpSysMon\Sensor;
use Xuedi\PhpSysMon\Service\LmSensorsService;
use Xuedi\PhpSysMon\Service\SensorService;

/**
 * @covers \Xuedi\PhpSysMon\Service\SensorService
 * @uses   \Xuedi\PhpSysMon\Sensor
 */
final class SensorServiceTest extends TestCase
{
    public function testCanGetRows(): void
    {
        $sensor1 = Sensor::fromParameters('5', '6');
        $sensor2 = Sensor::fromParameters('8', '9');
        $sensor3 = Sensor::fromParameters('a', 'b');

        $configMock = $this->createMock(Configuration::class);
        $lmSensorsMock = $this->createMock(LmSensorsService::class);

        $configMock
            ->expects($this->once())
            ->method('loadSensors')
            ->willReturn([
                [
                    'name' => 'test1234',
                    'value' => [$sensor1, $sensor2],
                    'extra' => [$sensor3],
                ]
            ]);

        $lmSensorsMock
            ->expects($this->exactly(3))
            ->method('read')
            ->withConsecutive([$sensor1], [$sensor2], [$sensor3])
            ->willReturnOnConsecutiveCalls('sensor1', 'sensor2', 'sensor3');

        $subject = new SensorService(
            $configMock,
            $lmSensorsMock
        );

        $this->assertEquals([['test1234', 'sensor1, sensor2', 'sensor3']], $subject->getRows());
    }
}
