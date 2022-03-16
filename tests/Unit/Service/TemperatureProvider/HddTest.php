<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit\Service\TemperatureProvider;

use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\Helpers\ShellWrapper;
use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Hdd;

/**
 * @covers \Xuedi\PhpSysMon\Service\TemperatureProvider\Hdd
 * @uses   \Xuedi\PhpSysMon\LinuxPath
 */
final class HddTest extends TestCase
{
    public function testTemperatureReading(): void
    {
        $deviceString = "/dev/sda";
        $devicePath = LinuxPath::fromString($deviceString);

        $wrapperMock = $this->createMock(ShellWrapper::class);
        $wrapperMock
            ->expects($this->once())
            ->method('shell_exec')
            ->with("sudo hddtemp --unit=C --wake-up --numeric $deviceString")
            ->willReturn('35C');

        $subject = new Hdd($wrapperMock);
        $subject->getTemperature($devicePath);
    }
}
