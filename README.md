# Account Kit SDK for PHP

## Install

```
composer require anhskohbo/accountkit
```

## Usage

Follow the instruction from: https://developers.facebook.com/docs/accountkit/webjs

Example "server.php"

```php
<?php

require 'vendor/autoload.php';

use Anhskohbo\AccountKit\Config;
use Anhskohbo\AccountKit\Client;

$facebookAppID = "<facebook_app_id>";
$accountKitAppSecret = "<account_kit_api_version>";

$client = new Client(new Config($facebookAppID, $accountKitAppSecret));

$token = $client->getAccessToken($_POST['code']);
$user = $client->getUser($token);

var_dump($user);
var_dump($user->getPhoneNumber());
```

## Contribute

https://github.com/anhskohbo/accountkit/pulls
