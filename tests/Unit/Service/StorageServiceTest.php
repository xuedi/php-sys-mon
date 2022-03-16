<?php

namespace Xuedi\PhpSysMon\Tests\Unit\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\Configuration\Configuration;
use Xuedi\PhpSysMon\FilesystemType;
use Xuedi\PhpSysMon\HardDrive;
use Xuedi\PhpSysMon\HardDriveType;
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
    private StorageService $subject;
    private MockObject|StorageService $config;

    public function setUp(): void
    {
        $this->config = $this->createMock(Configuration::class);
        $this->config
            ->expects($this->once())
            ->method('loadStorage')
            ->willReturn($this->getStorageCollection());

        $tempService = $this->createMock(TemperatureService::class);

        $this->subject = new StorageService(
            $this->config,
            $tempService
        );
    }

    public function testCanGetHeaders(): void
    {
        $expected = ['Name', 'Mount', 'FsType', 'Size', 'Used', 'Temp', 'Devices'];

        $this->assertEquals($expected, $this->subject->getHeaders());
    }

    public function testCanGetRows(): void
    {
        $expected = [
            [
                'name',
                '/dev/sda',
                'ext4',
                '0',
                '0',
                '0°',
                'sda: °',
            ]
        ];

        $this->assertEquals($expected, $this->subject->getRows());
    }

    private function getStorageCollection(): StorageCollection
    {
        return new StorageCollection(
            Storage::fromParameters(
                'name',
                LinuxPath::fromString('/dev/sda'),
                '/home/',
                FilesystemType::fromString('ext4'),
                [
                    '/dev/sda' => 'hdd',
                ]
            )
        );
    }
}
