<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\FilesystemType;
use Xuedi\PhpSysMon\HardDrive;
use Xuedi\PhpSysMon\HardDriveType;
use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\Storage;

/**
 * @covers \Xuedi\PhpSysMon\Storage
 * @uses   \Xuedi\PhpSysMon\FilesystemType
 * @uses   \Xuedi\PhpSysMon\HardDrive
 * @uses   \Xuedi\PhpSysMon\HardDriveType
 * @uses   \Xuedi\PhpSysMon\LinuxPath
 */
final class StorageTest extends TestCase
{
    public function testCanBeCreatedFromValidStrings(): void
    {
        $expectedName = 'name';
        $expectedMount = LinuxPath::fromString('/tmp');
        $expectedPartition = '/dev/sdb3'; // TODO: use path
        $expectedFilesystemType = FilesystemType::fromString('ext4');
        $expectedHardDrives = [HardDrive::fromParameters(
            LinuxPath::fromString("/dev/sdb"),
            HardDriveType::fromString("ssd")
        )];


        $subject = Storage::fromParameters(
            $expectedName,
            $expectedMount,
            $expectedPartition,
            $expectedFilesystemType,
            ["/dev/sdb" => "ssd"],
        );

        $this->assertEquals($expectedName, $subject->getName());
        $this->assertEquals($expectedMount, $subject->getMount());
        $this->assertEquals($expectedPartition, $subject->getPartition());
        $this->assertEquals($expectedFilesystemType, $subject->getFsType());
        $this->assertEquals($expectedHardDrives, $subject->getHardDrives());
    }
}
