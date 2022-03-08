<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Xuedi\PhpSysMon\FilesystemType;
use Xuedi\PhpSysMon\HardDrive;
use Xuedi\PhpSysMon\HardDriveType;
use Xuedi\PhpSysMon\LinuxPath;

/**
 * @covers \Xuedi\PhpSysMon\HardDrive
 * @uses   \Xuedi\PhpSysMon\LinuxPath
 * @uses   \Xuedi\PhpSysMon\HardDriveType
 */
final class HardDriveTest extends TestCase
{
    public function testCanBeCreatedFromValidStrings(): void
    {
        $expectedLinuxDevice = LinuxPath::fromString('/tmp');
        $expectedHardDriveType = HardDriveType::fromString('ssd');

        $subject = HardDrive::fromParameters(
            $expectedLinuxDevice,
            $expectedHardDriveType
        );

        $this->assertEquals($expectedLinuxDevice, $subject->getDevice());
        $this->assertEquals($expectedHardDriveType, $subject->getType());
    }
}
