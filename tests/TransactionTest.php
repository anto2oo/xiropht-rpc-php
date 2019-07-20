<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Xiropth\Connection;
use Xiropth\Transaction;
use Xiropth\Wallet;

$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

final class TransactionTest extends TestCase
{
    /** @var Connection $rpc */
    protected $rpc;

    /** @var Wallet $wallet */
    protected $wallet;

    public function setUp(): void
    {
        $this->rpc = new Connection(getenv('RPC_ADDRESS'), getenv('RPC_PORT'));
        $this->wallet = new Wallet($this->rpc);
    }

    public function testNeedsAConnection()
    {
        $this->expectException(TypeError::class);
        new Transaction('not_a_wallet', 'random');
    }

    public function testNeedsAValidHash()
    {
        $this->expectException(Exception::class);
        new Transaction($this->wallet, 'not_a_valid_hash');
    }
}
