<?php

namespace Xiropth;

use DateTime;
use Exception;

class Transaction
{
    public $wallet;
    public $hash;

    public function __construct(Wallet $wallet, $hash)
    {
        $this->wallet = $wallet;
        $this->hash = $hash;

        return $this->get();
    }

    private function get()
    {
        $transaction = $this->wallet->rpc->request('get_wallet_transaction_by_hash', [$this->wallet->address, $this->hash]);

        if (isset($transaction['result']) and $transaction['result'] === 'index_not_exist') {
            throw new Exception('Invalid transaction hash');
        }

        $this->type = $transaction['type'];
        $this->amount = $transaction['amount'];
        $this->fee = $transaction['fee'];
        $this->sent_at = new DateTime(date('c', $transaction['timestamp_send']));
        $this->received_at = new DateTime(date('c', $transaction['timestamp_recv']));

        return $this;
    }

}