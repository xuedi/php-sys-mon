<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\Configuration\Configuration;
use Xuedi\PhpSysMon\Sensor;
use Xuedi\PhpSysMon\Service\LmSensorsService;
use Xuedi\PhpSysMon\Service\SensorService;
use Xuedi\PhpSysMon\ShellWrapper;

/**
 * @covers \Xuedi\PhpSysMon\Service\LmSensorsService
 * @uses   \Xuedi\PhpSysMon\Sensor
 */
final class LmSensorsServiceTest extends TestCase
{
    /**
     * @dataProvider sensorListProvider
     */
    public function testCanGetRows(Sensor $sensor, string $expected): void
    {
        $testFixture = file_get_contents(__DIR__ . '/fixtures/lmSensorsOutput.txt');

        $wrapperMock = $this->createMock(ShellWrapper::class);
        $wrapperMock->expects($this->once())->method('shell_exec')->willReturn($testFixture);

        $subject = new LmSensorsService(
            $wrapperMock
        );

        $this->assertEquals($expected, $subject->read($sensor));
    }

    public function sensorListProvider(): array
    {
        return [
            [Sensor::fromParameters('unknown', 'unknown'), 'X'],
            [Sensor::fromParameters('asuswmisensors-isa-0000', 'CPU Core Voltage'), '1.41 V'], // last one
            [Sensor::fromParameters('asuswmisensors-isa-0000', 'CPU Fan'), '828 RPM'],
            [Sensor::fromParameters('asuswmisensors-isa-0000', 'CPU Socket Temperature'), '+37.0°C'],
            [Sensor::fromParameters('nvme-pci-0b00', 'Composite'), '+32.9°C'],
            [Sensor::fromParameters('nvme-pci-0b00', 'Sensor 2'), '+36.9°C'],
            [Sensor::fromParameters('k10temp-pci-00cb', 'Tctl'), '+63.9°C'],
        ];
    }
}
