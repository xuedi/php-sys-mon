<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Xuedi\PhpSysMon\FilesystemType;
use Xuedi\PhpSysMon\HardDrive;
use Xuedi\PhpSysMon\HardDriveType;
use Xuedi\PhpSysMon\LinuxPath;

/**
 * @covers \Xuedi\PhpSysMon\LinuxPath
 */
final class LinuxPathTest extends TestCase
{
    public function testCanBeCreatedFromValidStrings(): void
    {
        $expectedDevice = '/tmp';
        $expectedId = md5($expectedDevice);

        $subject = LinuxPath::fromString($expectedDevice);

        $this->assertEquals($expectedId, $subject->getId());
        $this->assertEquals($expectedDevice, $subject->asString());
    }

    public function testCanCreateEmpty(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("LinuxDevice cant be empty");

        LinuxPath::fromString('');
    }

    public function testCanCreateWithoutStartingSlash(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("LinuxDevice must start with a slash");

        LinuxPath::fromString('test');
    }

    public function testCanCreateWithTailingSlash(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("LinuxDevice cant have a tailing slash");

        LinuxPath::fromString('/tmp/');
    }
}
