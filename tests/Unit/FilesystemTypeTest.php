<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Xuedi\PhpSysMon\FilesystemType;

/**
 * @covers FilesystemType
 */
final class FilesystemTypeTest extends TestCase
{
    /** @dataProvider getTypeProvider */
    public function testCanBeCreatedFromValidStrings(string $expected): void
    {
        $subject = FilesystemType::fromString($expected);

        $this->assertEquals(
            $expected,
            $subject->asString()
        );
    }

    public function testCanCreateWithUnknownType(): void
    {
        $type = 'invalidType';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Unknown FilesystemType: [$type]");

        FilesystemType::fromString($type);
    }

    public function getTypeProvider(): array
    {
        return [
            ['btrfs'],
            ['ext4'],
        ];
    }
}
