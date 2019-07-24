# xiropht-php

## Description
This package provides a wrapper around the Xiropht RPC to manage Xiropht wallets.

## Installation

Add this to your ```composer.json``` file : 
```json
{
  "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/anto2oo/xiropht-rpc-php"
        }
    ]
}
```

Then just run :
```bash
composer require ateros/xiropht-rpc-php
```

## Usage

Do not forget to include the library via the composer autoloader at the beginning of your file:
```php
require __DIR__ . '/vendor/autoloader.php';
```

Then, the first thing to do is to create a ```Connection``` object that will handle requests to your RPC server :
```php
$rpc = new Connection('1.1.1.1', '8000');
```

With your ```Connection``` object, you can now start using wallets. 
To create a new wallet, pass the ```Connection``` object as the first parameter of the ```Wallet``` constructor :
```php
$wallet = new Wallet($rpc);
```

You can then start sending operations to the wallet. See available methods in the documentation.