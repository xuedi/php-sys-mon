<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Xuedi\PhpSysMon\HardDriveType;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Hdd;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Nvme;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Ssd;

/**
 * @covers HardDriveType
 */
final class HardDriveTypeTest extends TestCase
{
    /** @dataProvider getTypeProvider */
    public function testCanBeCreatedFromValidStrings(
        string $expected,
        bool   $isHdd,
        bool   $isSsd,
        bool   $isNvme,
        string $tempProvider
    ): void {
        $subject = HardDriveType::fromString($expected);

        $this->assertEquals($expected, $subject->asString());
        $this->assertEquals($isHdd, $subject->isHdd());
        $this->assertEquals($isSsd, $subject->isSsd());
        $this->assertEquals($isNvme, $subject->isNvme());
        $this->assertEquals($tempProvider, $subject->getTemperatureProvider());
    }

    public function testCanCreateWithUnknownType(): void
    {
        $type = 'invalidType';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Unknown HardDriveType: [$type]");

        HardDriveType::fromString($type);
    }

    public function getTypeProvider(): array
    {
        return [ // type, hdd, ssd, nvme, tempProvider
            ['hdd', true, false, false, Hdd::class],
            ['ssd', false, true, false, Ssd::class],
            ['nvme', false, false, true, Nvme::class],
        ];
    }
}
