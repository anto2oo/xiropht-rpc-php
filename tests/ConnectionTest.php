<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Xiropth\Connection;

$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

final class ConnectionTest extends TestCase
{
    public function testNeedsValidUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        new Connection('not_an_ip', 'not_a_port');
    }

    public function testCanBeInstantiated(): void
    {
        $this->assertInstanceOf(
            Connection::class,
            new Connection(getenv('RPC_ADDRESS'), getenv('RPC_PORT'))
        );
    }

    public function testCanMakeRequests()
    {
        $rpc = new Connection(getenv('RPC_ADDRESS'), getenv('RPC_PORT'));
        $this->assertIsArray($rpc->request('get_total_wallet_index'));
    }

    public function testCanListWallets()
    {
        $rpc = new Connection(getenv('RPC_ADDRESS'), getenv('RPC_PORT'));
        $this->assertIsArray($rpc->wallets());
    }
}
