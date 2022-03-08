<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\Sensor;

/**
 * @covers \Xuedi\PhpSysMon\Sensor
 */
final class SensorTest extends TestCase
{
    public function testCanBeCreatedFromValidStrings(): void
    {
        $expectedProvider = 'nvme-pci-0a00';
        $expectedIdent = 'Sensor 1';

        $subject = Sensor::fromParameters(
            $expectedProvider,
            $expectedIdent
        );

        $this->assertEquals($expectedProvider, $subject->getProvider());
        $this->assertEquals($expectedIdent, $subject->getIdent());
    }
}
