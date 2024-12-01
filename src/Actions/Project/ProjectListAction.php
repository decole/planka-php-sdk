<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Planka\Bridge\Views\Factory\Project\ProjectListDtoFactory;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Views\Dto\Project\ProjectListDto;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Traits\AuthenticateTrait;

final class ProjectListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return 'api/projects';
    }

    public function getOptions(): array
    {
        return [];
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ResponseException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function hydrate(ResponseInterface $response): ProjectListDto
    {
        /** @var array{items: array, included: array} $result */
        $result = $response->toArray();

        if (array_key_exists('items', $result)) {
            return (new ProjectListDtoFactory())->create($result);
        }

        throw new ResponseException($response->getContent());
    }
}
