# PHP PLANKA REST API

Wrapper over the rest api of the Planka (https://github.com/plankanban/planka)

Tested on Planka version: 
 - 1.10.3
 - 1.11
 - 1.24.3

Implemented all entrypoints for the bar version **1.10.3** and later.


## Install

`composer require decole/planka-php-sdk`


## How to use

Wrapper executes the requests that Planka makes over the web socket or hidden REST API.
Wrapper use hidden REST API. See endpoints in https://github.com/plankanban/planka/blob/master/server/config/routes.js 

To understand how to use the wrapper, you can go to the web socket and see how the web client of the web socket 
works with its server. Requests and responses are identical. I just standardized the answers in the DTO. 
The data inside the DTO is identical to the server responses.

It is also not important to understand that you are working under a specific user. Accordingly, if you do not see 
a project or some board, this means that this user is prohibited from having access by access rights.

You need to add a user. For which you come as a wrapper in the projects and boards you need.


Wrapper endpoint - /src/[PlankaClient.php](src/PlankaClient.php)


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

$result = $planka->project->list();

var_dump($result);
```


## Examples

- [Delete empty board](docs/DELETE_EMPTY_BOARD.md)
- [Create new card on board.md](docs/ADD_NEW_CARD_ON_BOARD.md)
- [Subscribe user to card.md](docs/SUBSCRIBE_MEMBERSHIP_TO_CARD.md)

You can test this bundle for Rest API with a test script, in the folder `/tests/index.php`. 
There you will find the main examples of using the script.

Copy [config.example.php](tests/config.example.php) for `config.php` and customize to your
Planka credentials.

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

## API Sources From Planka

### [Routes](https://github.com/plankanban/planka/blob/master/server/config/routes.js)
### [Models](https://github.com/plankanban/planka/tree/master/server/api/models)
### [Helpers](https://github.com/plankanban/planka/tree/master/server/api/helpers)

## Alternative SDK

Python:
- [plankapy](https://github.com/hwelch-fle/plankapy/tree/master)
