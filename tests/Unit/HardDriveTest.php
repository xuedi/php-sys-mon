<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Xuedi\PhpSysMon\FilesystemType;
use Xuedi\PhpSysMon\HardDrive;
use Xuedi\PhpSysMon\HardDriveType;
use Xuedi\PhpSysMon\LinuxDevice;

final class HardDriveTest extends TestCase
{
    public function testCanBeCreatedFromValidStrings(): void
    {
        $expectedLinuxDevice = LinuxDevice::fromString('/tmp');
        $expectedHardDriveType = HardDriveType::fromString('ssd');

        $subject = HardDrive::fromParameters(
            $expectedLinuxDevice,
            $expectedHardDriveType
        );

        $this->assertEquals($expectedLinuxDevice, $subject->getDevice());
        $this->assertEquals($expectedHardDriveType, $subject->getType());
    }
}
