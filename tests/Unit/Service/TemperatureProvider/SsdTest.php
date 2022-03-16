<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit\Service\TemperatureProvider;

use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\Helpers\ShellWrapper;
use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Ssd;

/**
 * @covers \Xuedi\PhpSysMon\Service\TemperatureProvider\Ssd
 * @uses   \Xuedi\PhpSysMon\LinuxPath
 */
final class SsdTest extends TestCase
{
    public function testTemperatureReading(): void
    {
        $expectedTemperature = 27;

        $deviceString = "/dev/sda";
        $devicePath = LinuxPath::fromString($deviceString);

        $wrapperMock = $this->createMock(ShellWrapper::class);
        $wrapperMock
            ->expects($this->once())
            ->method('shell_exec')
            ->with("sudo smartctl -A $deviceString -j")
            ->willReturn($this->getSmartCtlOutput());

        $subject = new Ssd($wrapperMock);
        $actual = $subject->getTemperature($devicePath);

        $this->assertEquals($expectedTemperature, $actual);
    }

    public function testFailedReadingOnStructure(): void
    {
        $expectedTemperature = 0;

        $wrapperMock = $this->createMock(ShellWrapper::class);
        $wrapperMock->expects($this->once())->method('shell_exec')->willReturn(json_encode([]));

        $subject = new Ssd($wrapperMock);
        $actual = $subject->getTemperature(LinuxPath::fromString("/dev/sda"));

        $this->assertEquals($expectedTemperature, $actual);
    }

    public function testFailedReadingOnParsing(): void
    {
        $expectedTemperature = 0;

        $wrapperMock = $this->createMock(ShellWrapper::class);
        $wrapperMock->expects($this->once())->method('shell_exec')->willReturn(json_encode(
            ['ata_smart_attributes' => ['table' => []]]
        ));

        $subject = new Ssd($wrapperMock);
        $actual = $subject->getTemperature(LinuxPath::fromString("/dev/sda"));

        $this->assertEquals($expectedTemperature, $actual);
    }

    private function getSmartCtlOutput(): string
    {
        $output = [
            "id" => 190,
            "name" => "Airflow_Temperature_Cel",
            "value" => 73,
            "worst" => 56,
            "thresh" => 0,
            "when_failed" => "",
            "flags" => [
                "value" => 50,
                "string" => "-O--CK ",
                "prefailure" => false,
                "updated_online" => true,
                "performance" => false,
                "error_rate" => false,
                "event_count" => true,
                "auto_keep" => true,
            ],
            "raw" => [
                "value" => 27,
                "string" => "27"
            ],
        ];

        // add wrapping
        $output['ata_smart_attributes']['table'][0] = $output;

        return json_encode($output);
    }
}
