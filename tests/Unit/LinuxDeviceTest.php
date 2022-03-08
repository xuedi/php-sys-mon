<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Xuedi\PhpSysMon\FilesystemType;
use Xuedi\PhpSysMon\HardDrive;
use Xuedi\PhpSysMon\HardDriveType;
use Xuedi\PhpSysMon\LinuxDevice;

final class LinuxDeviceTest extends TestCase
{
    public function testCanBeCreatedFromValidStrings(): void
    {
        $expectedDevice = '/tmp';
        $expectedId = md5($expectedDevice);

        $subject = LinuxDevice::fromString($expectedDevice);

        $this->assertEquals($expectedId, $subject->getId());
        $this->assertEquals($expectedDevice, $subject->asString());
    }

    public function testCanCreateEmpty(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("LinuxDevice cant be empty");

        LinuxDevice::fromString('');
    }

    public function testCanCreateWithoutStartingSlash(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("LinuxDevice must start with a slash");

        LinuxDevice::fromString('test');
    }

    public function testCanCreateWithTailingSlash(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("LinuxDevice cant have a tailing slash");

        LinuxDevice::fromString('/tmp/');
    }
}
