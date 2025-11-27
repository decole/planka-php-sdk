# PHP PLANKA REST API

Wrapper over the rest api of the Planka (https://github.com/plankanban/planka)

Tested on Planka version: 
 - 1.10.3
 - 1.11
 - 1.24.3

Implemented all entrypoints for the bar version **1.10.3** and later.

On 1.24.3 add new entrypoints for OIDC and update result DTO on 1.24.3. - on version this project **1.3.0**


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

## Dto has raw array result

- ProjectListDto `$client->project->list();`
- 


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


----

[Routes:](https://github.com/plankanban/planka/blob/master/server/config/routes.js)

----

**(create for next iteration)**


- 'GET /api/config'

- 'POST /api/access-tokens'
- 'POST /api/access-tokens/exchange-using-oidc' 
- 'DELETE /api/access-tokens/me' 

----

## Checking routes

~~- 'GET /api/users'~~
~~- 'POST /api/users'~~
~~- 'GET /api/users/:id'~~
~~- 'PATCH /api/users/:id'~~
~~- 'PATCH /api/users/:id/email'~~
~~- 'PATCH /api/users/:id/password'~~
~~- 'PATCH /api/users/:id/username'~~
~~- 'POST /api/users/:id/avatar'~~
~~- 'DELETE /api/users/:id'~~


~~- 'GET /api/projects'~~
~~- 'POST /api/projects'~~
~~- 'GET /api/projects/:id'~~
~~- 'PATCH /api/projects/:id'~~
~~- 'POST /api/projects/:id/background-image'~~
~~- 'DELETE /api/projects/:id'~~
 

~~- 'POST /api/projects/:projectId/managers'~~
~~- 'DELETE /api/project-managers/:id'~~


- 'POST /api/projects/:projectId/boards'
- 'GET /api/boards/:id'
- 'PATCH /api/boards/:id'
- 'DELETE /api/boards/:id'


- 'POST /api/boards/:boardId/memberships'
- 'PATCH /api/board-memberships/:id'
- 'DELETE /api/board-memberships/:id'


- 'POST /api/boards/:boardId/labels'
- 'PATCH /api/labels/:id'
- 'DELETE /api/labels/:id'


- 'POST /api/boards/:boardId/lists'
- 'PATCH /api/lists/:id'
- 'POST /api/lists/:id/sort'
- 'DELETE /api/lists/:id'


- 'POST /api/lists/:listId/cards'
- 'GET /api/cards/:id'
- 'PATCH /api/cards/:id'
- 'POST /api/cards/:id/duplicate'
- 'DELETE /api/cards/:id'
- 'POST /api/cards/:cardId/memberships'
- 'DELETE /api/cards/:cardId/memberships'
- 'POST /api/cards/:cardId/labels'
- 'DELETE /api/cards/:cardId/labels/:labelId'


- 'POST /api/cards/:cardId/tasks'
- 'PATCH /api/tasks/:id'
- 'DELETE /api/tasks/:id'


- 'POST /api/cards/:cardId/attachments'
- 'PATCH /api/attachments/:id'
- 'DELETE /api/attachments/:id'


- 'GET /api/cards/:cardId/actions'


- 'POST /api/cards/:cardId/comment-actions'
- 'PATCH /api/comment-actions/:id'
- 'DELETE /api/comment-actions/:id'


- 'GET /api/notifications'
- 'GET /api/notifications/:id'
- 'PATCH /api/notifications/:ids'


- 'GET /user-avatars/*'
- 'GET /project-background-images/*'
- 'GET /attachments/:id/download/:filename'
- 'GET /attachments/:id/download/thumbnails/cover-256.:extension'
- 'GET /*'
