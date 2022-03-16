<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Xuedi\PhpSysMon\Configuration\Configuration;
use Xuedi\PhpSysMon\HardDrive;
use Xuedi\PhpSysMon\HardDriveType;
use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\Sensor;
use Xuedi\PhpSysMon\Service\LmSensorsService;
use Xuedi\PhpSysMon\Service\SensorService;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Hdd;
use Xuedi\PhpSysMon\Service\TemperatureService;

/**
 * @covers \Xuedi\PhpSysMon\Service\TemperatureService
 * @uses   \Xuedi\PhpSysMon\HardDriveType
 * @uses   \Xuedi\PhpSysMon\HardDrive
 * @uses   \Xuedi\PhpSysMon\LinuxPath
 */
final class TemperatureServiceTest extends TestCase
{
    public function testCanGetRows(): void
    {
        $expectedTemp = 5;
        $expectedPath = LinuxPath::fromString('/tmp');

        $containerMock = $this->createMock(Container::class);
        $hddProviderMock = $this->createMock(Hdd::class);

        $hardDrive = HardDrive::fromParameters(
            $expectedPath,
            HardDriveType::fromString(HardDriveType::HDD)
        );

        $containerMock
            ->expects($this->once())
            ->method('get')
            ->with(Hdd::class)
            ->willReturn($hddProviderMock);

        $hddProviderMock
            ->expects($this->once())
            ->method('getTemperature')
            ->with($expectedPath)
            ->willReturn($expectedTemp);

        $subject = new TemperatureService(
            $containerMock
        );

        $this->assertEquals($expectedTemp, $subject->measure($hardDrive));

        // call a second time to test the cache
        $subject->measure($hardDrive);
    }
}
