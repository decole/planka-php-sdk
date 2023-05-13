# PHP PLANKA REST API

See /src/[PlankaClient.php](src/PlankaClient.php)

```php
<?php

use Planka\Bridge\PlankaClient;use Planka\Bridge\TransportClients\Client;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    user: 'login',
    password: 'Z#M**********"j',
    baseUri: 'http://192.168.1.101', // https://your.domain.com
    port: 3000                       // 443
);
$planka = new PlankaClient($config);
$planka->authenticate();

$result = $planka->board->get(745435921242915851);
var_dump($result);
```
## RTFM

- Planka - https://github.com/plankanban/planka
- HTTP client lib: https://symfony.com/doc/current/http_client.html#authentication

## For develop

`./vendor/bin/psalm --no-cache --no-file-cache`