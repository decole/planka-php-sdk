# PHP PLANKA REST API

Wrapper over the rest api of the Planka (https://github.com/plankanban/planka)

Tested on Planka version: 
 - 1.10.3
 - 1.11

Implemented all entrypoints for the bar version **1.10.3** and later.


## Install

`composer require decole/planka-php-sdk`


## How to use

See /src/[PlankaClient.php](src/PlankaClient.php)

```php
<?php

use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Client;

require __DIR__ . '/vendor/autoload.php';

$config = new Config(
    user: 'login',
    password: '***************',
    baseUri: 'http://192.168.1.101', // https://your.domain.com
    port: 3000                       // 443
);

$planka = new PlankaClient($config);

$planka->authenticate();

$result = $client->project->list();

var_dump($result);
```

You can test this bundle for Rest API with a test script, in the folder `/tests/index.php`. 
There you will find the main examples of using the script.

Copy [config.example.php](tests/config.example.php) for `config.php` and customize to your
planka credentials.

In the test script, comments describe what is being done and the project, board and card are also created and carried 
out with them manipulations, at the end of the card, board and project are deleted.

All necessary entrypoints are conveniently divided into controllers. You can view the controllers 
in the `src/Controllers/` folder.

Result data output is strongly typed and returned in Dto objects


## Found problems:

Using Symfony\Component\HttpClient\NativeHttpClient - as an internal client, you can send passwords with special characters `()\|/"'`
but if you use Symfony\Component\HttpClient\CurlHttpClient - tricky passwords cannot be used. inside there is escaping for url encoding, which is why Planck
the password cannot be intact. Because of this, it is impossible to log in with an account.


## For develop

### RTFM

- Planka - https://github.com/plankanban/planka
- HTTP client lib: https://symfony.com/doc/current/http_client.html


### Static analyze code
Psalm analyze: `./vendor/bin/psalm --no-cache --no-file-cache`

Or if you use linux, use `make psalm`
