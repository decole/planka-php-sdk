<?php

declare(strict_types=1);

namespace Planka\Bridge\TransportClients\Middleware;

use Planka\Bridge\Actions\Auth\AuthenticateAction;
use Planka\Bridge\Config;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Exceptions\AuthenticateException;
use Planka\Bridge\Exceptions\PlankaAccessDeniedException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class TokenRefreshMiddleware implements TransportMiddlewareInterface
{
    private bool $isRefreshing = false;

    public function __construct(private readonly Config $config) {}

    public function handle(ActionInterface $action, string $method, callable $next): mixed
    {
        try {
            return $next($action, $method);
        } catch (PlankaAccessDeniedException $e) {
            if (
                401 !== $e->getCode()
                || $this->isRefreshing
                || !$action instanceof AuthenticateInterface
                || null === $this->config->getUser()
                || null === $this->config->getPassword()
            ) {
                throw $e;
            }

            $this->refreshToken($next);

            return $next($action, $method);
        }
    }

    /**
     * @throws AuthenticateException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function refreshToken(callable $next): void
    {
        $this->isRefreshing = true;

        try {
            $authAction = new AuthenticateAction(
                username: (string) $this->config->getUser(),
                password: (string) $this->config->getPassword(),
            );

            $response = $next($authAction, 'POST');

            if ($response instanceof ResponseInterface) {
                $data = $response->toArray(false);
            } elseif (is_array($response)) {
                $data = $response;
            } else {
                $data = [];
            }

            $token = $data['item'] ?? null;

            if (!is_string($token) || '' === $token) {
                throw new AuthenticateException('Token refresh failed: empty token returned.');
            }

            $this->config->setAuthToken($token);
        } finally {
            $this->isRefreshing = false;
        }
    }
}
