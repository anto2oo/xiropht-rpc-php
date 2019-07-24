<?php

namespace Xiropth;

use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;

class Connection
{
    private $ip;
    private $port;

    /**
     * Connection constructor.
     * @param $ip
     * @param $port
     * @throws Exception
     */
    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;

        try {
            $this->request('get_total_wallet_index');
        } catch (Exception $e) {
            throw new InvalidArgumentException('Unable to contact the RPC server.');
        }
    }

    private function requestRaw($method, array $params = [])
    {
        $client = new Client();

        return $client->request('GET', $this->buildUrl($method) . $this->buildParams($params), ['connect_timeout' => 5]);

    }

    public function request($method, array $params = [])
    {
        $request = $this->requestRaw($method, $params);
        return json_decode(
            $request
                ->getBody()
                ->getContents(), true);
    }

    private function buildUrl($method)
    {
        return 'http://' . $this->ip . ':' . $this->port . '/' . $method;
    }

    private function buildParams(array $params)
    {
        if (empty($params)) {
            return '';
        }

        return '|' . implode('|', $params);
    }

    public function wallets()
    {
        $wallets = [];

        foreach (range(1, $this->walletCount()) as $index) {
            $address = $this->request('get_wallet_address_by_index', [$index])['result'];
            $wallets[] = new Wallet($this, $address);
        }

        return $wallets;
    }

    private function walletCount()
    {
        return $this->request('get_total_wallet_index')['result'];
    }

}