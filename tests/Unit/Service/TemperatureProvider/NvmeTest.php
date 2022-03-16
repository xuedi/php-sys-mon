<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit\Service\TemperatureProvider;

use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\Helpers\ShellWrapper;
use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Nvme;

/**
 * @covers \Xuedi\PhpSysMon\Service\TemperatureProvider\Nvme
 * @uses   \Xuedi\PhpSysMon\LinuxPath
 */
final class NvmeTest extends TestCase
{
    public function testTemperatureReading(): void
    {
        $expectedTemperature = 37;

        $deviceString = "/dev/sda";
        $devicePath = LinuxPath::fromString($deviceString);

        $wrapperMock = $this->createMock(ShellWrapper::class);
        $wrapperMock
            ->expects($this->once())
            ->method('shell_exec')
            ->with("sudo nvme smart-log  -o json $deviceString")
            ->willReturn(json_encode(['temperature' => $expectedTemperature+273.15]));

        $subject = new Nvme($wrapperMock);
        $actual = $subject->getTemperature($devicePath);

        $this->assertEquals($expectedTemperature, $actual);
    }
}
