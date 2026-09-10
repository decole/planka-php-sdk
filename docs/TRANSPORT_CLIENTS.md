# Transport Layer Architecture & Multiple HTTP Client Implementations

Planka PHP SDK v2 is built with a modular, transport-agnostic architecture based on `TransportClientInterface`. This design decouples the SDK's business logic, controllers, and actions from the underlying HTTP networking layer, providing **multiple ready-to-use transport implementations** as well as full support for custom clients and middleware pipelines.

---

## 1. Multiple Transport Client Implementations

The SDK provides three built-in implementations of `Planka\Bridge\TransportClients\TransportClientInterface`:

| Transport Client | Description | Typical Use Case |
| :--- | :--- | :--- |
| **`Client`** | Native adapter for `Symfony\Contracts\HttpClient\HttpClientInterface`. | High-performance production environments, HTTP/2 multiplexing, async streaming, default out-of-the-box transport. |
| **`PsrTransportClient`** | Universal adapter for any **PSR-18 HTTP Client** (`psr/http-client`) and **PSR-17 Factory** (`psr/http-factory`). | Applications standardizing on PSR standards, Guzzle 7, Buzz, Nyholm, or custom PSR-18 HTTP clients. |
| **`MiddlewareStackTransportClient`** | Decorator pipeline wrapping any `TransportClientInterface` with a chain of `TransportMiddlewareInterface`. | Logging (PSR-3 Monolog), automated retries with exponential backoff, request metrics, distributed tracing. |
| **Custom Implementation** | Any class implementing `TransportClientInterface`. | In-memory testing, mock transports, custom caching layers, specialized corporate proxies. |

```
┌──────────────────────────────────────────────────────────────────────────────┐
│                            PlankaClient (Facade)                             │
└──────────────────────────────────────┬───────────────────────────────────────┘
                                       │
                                       ▼
┌──────────────────────────────────────────────────────────────────────────────┐
│               MiddlewareStackTransportClient (Decorator Pipeline)             │
│   ┌───────────────────────────┐         ┌───────────────────────────┐        │
│   │    PsrLoggerMiddleware    │   ───>  │      RetryMiddleware      │  ───>  │
│   └───────────────────────────┘         └───────────────────────────┘        │
└──────────────────────────────────────┬───────────────────────────────────────┘
                                       │
              ┌────────────────────────┴────────────────────────┐
              ▼                                                 ▼
┌───────────────────────────┐                     ┌───────────────────────────┐
│     Client (Symfony)      │                     │    PsrTransportClient     │
│  - Symfony HttpClient     │                     │  - PSR-18 (Guzzle, Buzz)  │
│  - HTTP/2, Async, Chunks  │                     │  - PSR-17 Request Factory │
└───────────────────────────┘                     └───────────────────────────┘
```

---

## 2. Authentication Methods with Transports

Regardless of which transport client implementation you choose, the SDK supports **both authentication methods**:

### Method 1: User API Key (`apiKey` parameter — Recommended)
All requests automatically include the `X-Api-Key` header. No manual call to `->authenticate()` is required:

```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'your_user_api_key_here'
);

$planka = new PlankaClient($config);
$projects = $planka->project()->list();
```

### Method 2: Username & Password (JWT Bearer Token)
Requires calling `$planka->authenticate()` before issuing API requests. The returned JWT Bearer token is automatically stored in `TokenStorage` and added to `Authorization: Bearer <token>` headers:

```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

$config = new Config(
    user: 'admin@example.com',
    password: 'secure_password',
    baseUri: 'http://192.168.1.100',
    port: 3000
);

$planka = new PlankaClient($config);
$authResult = $planka->authenticate();

if (!$authResult->success) {
    if ($authResult->requiresTotp()) {
        $planka->verifyTotp($authResult->pendingToken, '123456');
    }
}

$projects = $planka->project()->list();
```

---

## 3. Implementation 1: Native Symfony HttpClient (`Client`)

`Planka\Bridge\TransportClients\Client` is the default transport. If no transport client is passed to `PlankaClient`, this client is instantiated automatically.

### A. Default Instantiation:
```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

$config = new Config(baseUri: 'http://192.168.1.100', port: 3000, apiKey: 'your_api_key');
$planka = new PlankaClient($config);
```

### B. Customizing Symfony HttpClient (Timeouts, Proxies, HTTP/2):
```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Client;
use Symfony\Component\HttpClient\HttpClient;

$symfonyClient = HttpClient::create([
    'timeout' => 15.0,
    'max_duration' => 30.0,
    'proxy' => 'http://proxy.internal:8080',
    'http_version' => '2.0',
]);

$config = new Config(baseUri: 'http://192.168.1.100', port: 3000, apiKey: 'your_api_key');
$transport = new Client(config: $config, client: $symfonyClient);
$planka = new PlankaClient(config: $config, client: $transport);
```

---

## 4. Implementation 2: Universal PSR-18 / PSR-17 Transport (`PsrTransportClient`)

`Planka\Bridge\TransportClients\PsrTransportClient` allows using any PSR-18 compliant client and PSR-17 factory.

### A. Using with Guzzle 7
```bash
composer require guzzlehttp/guzzle guzzlehttp/psr7
```

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\PsrTransportClient;

// 1. Configure authentication (API Key or Login/Password)
$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'your_user_api_key'
);

// 2. Initialize Guzzle and PSR-17 Factory
$guzzleClient = new GuzzleClient([
    'timeout' => 10.0,
    'connect_timeout' => 5.0,
]);
$httpFactory = new HttpFactory();

// 3. Create PSR Transport Client
$psrTransport = new PsrTransportClient(
    config: $config,
    httpClient: $guzzleClient,          // Implements Psr\Http\Client\ClientInterface
    requestFactory: $httpFactory,       // Implements Psr\Http\Message\RequestFactoryInterface
    streamFactory: $httpFactory         // Implements Psr\Http\Message\StreamFactoryInterface
);

// 4. Instantiate SDK with PSR transport
$planka = new PlankaClient(config: $config, client: $psrTransport);
$projects = $planka->project()->list();
```

### B. Using with Nyholm PSR-7 & Buzz / cURL Client
```bash
composer require nyholm/psr7 kriswallsmith/buzz
```

```php
use Buzz\Client\Curl;
use Nyholm\Psr7\Factory\Psr17Factory;
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\PsrTransportClient;

$config = new Config(baseUri: 'http://192.168.1.100', port: 3000, apiKey: 'your_api_key');

$psr17Factory = new Psr17Factory();
$buzzClient = new Curl($psr17Factory);

$psrTransport = new PsrTransportClient(
    config: $config,
    httpClient: $buzzClient,
    requestFactory: $psr17Factory,
    streamFactory: $psr17Factory
);

$planka = new PlankaClient(config: $config, client: $psrTransport);
```

### C. Using with Symfony Psr18Client Adapter
```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\PsrTransportClient;
use Symfony\Component\HttpClient\Psr18Client;

$config = new Config(baseUri: 'http://192.168.1.100', port: 3000, apiKey: 'your_api_key');
$psr18Client = new Psr18Client(); // Acts as PSR-18 client, RequestFactory and StreamFactory

$psrTransport = new PsrTransportClient(
    config: $config,
    httpClient: $psr18Client,
    requestFactory: $psr18Client,
    streamFactory: $psr18Client
);

$planka = new PlankaClient(config: $config, client: $psrTransport);
```

---

## 5. Implementation 3: Middleware Pipeline (`MiddlewareStackTransportClient`)

`MiddlewareStackTransportClient` implements the Decorator pattern over any `TransportClientInterface`.

### Built-in Middlewares:
1. **`TokenRefreshMiddleware`** (Automatic JWT Re-Authentication): Catches 401 Unauthorized errors on long-running processes, re-authenticates automatically with user/password credentials, updates token storage, and replays the original request.
2. **`PsrLoggerMiddleware`** (PSR-3 Logging): Logs requests, execution durations, and error details.
3. **`RetryMiddleware`** (Exponential Backoff): Retries requests that failed due to transient server errors (429, 502, 503, 504) or network timeouts.

```php
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\Middleware\PsrLoggerMiddleware;
use Planka\Bridge\TransportClients\Middleware\RetryMiddleware;
use Planka\Bridge\TransportClients\Middleware\TokenRefreshMiddleware;

$logger = new Logger('planka');
$logger->pushHandler(new StreamHandler(__DIR__ . '/var/planka.log'));

$config = new Config(
    user: 'admin@example.com',
    password: 'secure_password',
    baseUri: 'http://192.168.1.100',
    port: 3000
);

$planka = new PlankaClient(
    config: $config,
    middlewares: [
        new TokenRefreshMiddleware($config),
        new PsrLoggerMiddleware($logger),
        new RetryMiddleware(maxRetries: 3, baseDelayMs: 200),
    ]
);
```

### Writing a Custom Transport Middleware:
```php
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\TransportClients\Middleware\TransportMiddlewareInterface;

final class CustomHeaderMiddleware implements TransportMiddlewareInterface
{
    public function handle(ActionInterface $action, string $method, callable $next): mixed
    {
        // Custom pre-processing (metrics, headers, tracing)
        $result = $next($action, $method);
        // Custom post-processing
        return $result;
    }
}
```

---

## 6. Unit Testing with Mock Transports

Using `PsrTransportClient` or a custom `TransportClientInterface` allows complete offline testing without making network calls:

```php
use PHPUnit\Framework\TestCase;
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\TransportClients\PsrTransportClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class PlankaServiceTest extends TestCase
{
    public function testGetCardOffline(): void
    {
        $config = new Config(baseUri: 'http://test', port: 80, apiKey: 'test-key');

        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('__toString')->willReturn(json_encode([
            'item' => [
                'id' => 'card_123',
                'name' => 'Mock Card',
                'createdAt' => '2026-09-01T00:00:00.000Z',
            ],
        ]));

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $mockHttpClient = $this->createMock(ClientInterface::class);
        $mockHttpClient->expects($this->once())
            ->method('sendRequest')
            ->willReturn($mockResponse);

        $mockRequestFactory = $this->createMock(RequestFactoryInterface::class);

        $transport = new PsrTransportClient(
            config: $config,
            httpClient: $mockHttpClient,
            requestFactory: $mockRequestFactory,
        );

        $client = new PlankaClient($config, $transport);
        $card = $client->card()->get('card_123');

        $this->assertEquals('card_123', $card->id);
        $this->assertEquals('Mock Card', $card->name);
    }
}
```
