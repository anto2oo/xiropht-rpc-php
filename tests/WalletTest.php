<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Xiropth\Connection;
use Xiropth\Wallet;

$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

final class WalletTest extends TestCase
{
    /** @var Connection $rpc */
    protected $rpc;

    public function setUp(): void
    {
        $this->rpc = new Connection(getenv('RPC_ADDRESS'), getenv('RPC_PORT'));
    }

    public function testNeedsAConnection()
    {
        $this->expectException(TypeError::class);
        new Wallet('not_a_connection', 'random');
    }

    public function testNeedsAValidAddress()
    {
        $this->expectException(Exception::class);
        new Wallet($this->rpc, 'not_a_valid_address');
    }

    public function testCanBeCreated()
    {
        $old_wallets_count = $this->rpc->request('get_total_wallet_index')['result'];
        new Wallet($this->rpc);
        $new_wallets_count = $this->rpc->request('get_total_wallet_index')['result'];

        $this->assertGreaterThan($old_wallets_count, $new_wallets_count);
    }

    public function testHasCorrectProperties()
    {
        $wallet = new Wallet($this->rpc);
        $this->assertInstanceOf(Connection::class, $wallet->rpc);
        $this->assertIsString($wallet->address);
        $this->assertEquals(0, $wallet->balance);
        $this->assertEquals(0, $wallet->pending_balance);
    }
}
