<?php

namespace Xuedi\PhpSysMon\Tests\Unit\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\Configuration\Configuration;
use Xuedi\PhpSysMon\FilesystemType;
use Xuedi\PhpSysMon\HardDrive;
use Xuedi\PhpSysMon\HardDriveType;
use Xuedi\PhpSysMon\Helpers\FilesystemWrapper;
use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\Service\StorageService;
use Xuedi\PhpSysMon\Service\TemperatureService;
use Xuedi\PhpSysMon\Storage;
use Xuedi\PhpSysMon\StorageCollection;

/**
 * @covers \Xuedi\PhpSysMon\Service\StorageService
 * @uses   \Xuedi\PhpSysMon\FilesystemType
 * @uses   \Xuedi\PhpSysMon\HardDrive
 * @uses   \Xuedi\PhpSysMon\HardDriveType
 * @uses   \Xuedi\PhpSysMon\LinuxPath
 * @uses   \Xuedi\PhpSysMon\Storage
 * @uses   \Xuedi\PhpSysMon\StorageCollection
 */
final class StorageServiceTest extends TestCase
{
    public function testCanGetHeaders(): void
    {
        $expected = ['Name', 'Mount', 'FsType', 'Size', 'Used', 'Temp', 'Devices'];

        $configMock = $this->createMock(Configuration::class);
        $fsWrapperMock = $this->createMock(FilesystemWrapper::class);
        $tempServiceMock = $this->createMock(TemperatureService::class);

        $subject = new StorageService(
            $configMock,
            $tempServiceMock,
            $fsWrapperMock
        );

        $this->assertEquals($expected, $subject->getHeaders());
    }

    public function testCanGetRows(): void
    {
        $name = 'name';
        $diskSpace = 12233445635234.0;
        $partition = '/dev/nvme1n1';
        $expectedMount = '/home';
        $expectedRows = [
            [
                'name',
                $expectedMount,
                'ext4',
                ' 12.23 TB',
                '58% -   7.14 TB',
                '0°',
                'nvme1: °',
            ]
        ];

        $storageCollection = new StorageCollection(
            Storage::fromParameters(
                $name,
                LinuxPath::fromString($expectedMount),
                $partition,
                FilesystemType::fromString('ext4'),
                [
                    $partition => 'nvme',
                ]
            )
        );

        $configMock = $this->createMock(Configuration::class);
        $configMock
            ->expects($this->once())
            ->method('loadStorage')
            ->willReturn($storageCollection);

        $fsWrapperMock = $this->createMock(FilesystemWrapper::class);
        $fsWrapperMock
            ->expects($this->once())
            ->method('is_dir')
            ->with($expectedMount)
            ->willReturn(true);
        $fsWrapperMock
            ->expects($this->once())
            ->method('disk_total_space')
            ->with($expectedMount)
            ->willReturn($diskSpace);
        $fsWrapperMock
            ->expects($this->once())
            ->method('disk_free_space')
            ->with($expectedMount)
            ->willReturn($diskSpace / 2.4);

        $tempServiceMock = $this->createMock(TemperatureService::class);



        $subject = new StorageService(
            $configMock,
            $tempServiceMock,
            $fsWrapperMock
        );

        $this->assertEquals($expectedRows, $subject->getRows());
    }

}
