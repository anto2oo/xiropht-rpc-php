<?php


namespace Xiropth;

use Exception;

class Wallet
{
    public $rpc;
    public $address;
    public $balance;
    public $pending_balance;

    public function __construct(Connection $rpc, $address = null)
    {
        $this->rpc = $rpc;

        if (is_null($address)) {
            $wallet = $this->rpc->request('create_wallet');
            $this->address = $wallet['result'];
            return $this->get();
        } else {
            $this->address = $address;
            return $this->get();
        }
    }

    private function get()
    {
        $wallet = $this->rpc->request('get_wallet_balance_by_wallet_address', [$this->address]);

        if (isset($wallet['result']) and $wallet['result'] === 'wallet_not_exist') {
            throw new Exception('Invalid wallet address');
        }

        $this->balance = $wallet['wallet_balance'];
        $this->pending_balance = $wallet['wallet_pending_balance'];

        return $this;
    }

    public function update()
    {
        $wallet = $this->rpc->request('update_wallet_by_address', [$this->address]);
        return $this->get();
    }

    public function transactions()
    {
        $transactions = [];

        foreach (range(1, $this->transactionCount()) as $index) {
            $hash = $this->rpc->request('get_wallet_transaction', [$this->address, $index])['hash'];
            $transactions[] = new Transaction($this, $hash);
        }

        return $transactions;
    }

    private function transactionCount()
    {
        return $this->rpc->request('get_wallet_total_transaction_by_wallet_address', [
            $this->address
        ])['wallet_total_transaction'];
    }

    public function send($to, $amount, $fee, $anonymous = false)
    {
        $anonymous = $anonymous ? 1 : 0;

        return $this->rpc->request('send_transaction_by_wallet_address', [
            $this->address,
            $amount,
            $fee,
            $anonymous,
            $to
        ]);
    }
}