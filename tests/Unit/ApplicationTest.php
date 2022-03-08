<?php declare(strict_types=1);

namespace Xuedi\PhpSysMon\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Xuedi\PhpSysMon\Application;
use Xuedi\PhpSysMon\Command\DashboardCommand;
use Xuedi\PhpSysMon\Service\SensorService;
use Xuedi\PhpSysMon\Service\StorageService;

/**
 * @covers Application
 */
final class ApplicationTest extends TestCase
{
    public function testCanBuildApplication(): void
    {
        $expectedName = Application::APP_NAME;
        $expectedVersion = '1.0';
        $expectedCommands = [
            new DashboardCommand(
                $this->createMock(StorageService::class),
                $this->createMock(SensorService::class),
            )
        ];

        $subject = new Application($expectedCommands, $expectedVersion);

        $this->assertEquals($expectedName, $subject->getName());
        $this->assertEquals($expectedVersion, $subject->getVersion());
        //$this->assertEquals($expectedVersion, $subject->get(DashboardCommand::class));
    }
}
