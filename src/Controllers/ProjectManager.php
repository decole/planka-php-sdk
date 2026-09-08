<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\ProjectManager\ProjectManagerCreateAction;
use Planka\Bridge\Actions\ProjectManager\ProjectManagerDeleteAction;
use Symfony\Component\HttpClient\Exception\ClientException;
use Planka\Bridge\Views\Dto\Project\ProjectManagerDto;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Exceptions\ValidateException;
use Planka\Bridge\TransportClients\TransportClientInterface;

final class ProjectManager
{
    public function __construct(
        private readonly TransportClientInterface $client,
    ) {}

    /**
     * 'POST /api/projects/:projectId/managers'.
     *
     * @throws ResponseException|ValidateException
     */
    public function add(string $projectId, string $userId): ProjectManagerDto
    {
        try {
            return $this->client->post(new ProjectManagerCreateAction(
                projectId: $projectId,
                userId: $userId,
            ));
        } catch (ClientException $exception) {
            if (409 === $exception->getCode()) {
                throw new ValidateException('User already joined to project managers');
            }

            throw new ResponseException($exception->getMessage());
        }
    }

    /** 'DELETE /api/project-managers/:id' */
    public function remove(string $managerId): ProjectManagerDto
    {
        return $this->client->delete(new ProjectManagerDeleteAction(
            projectManagerId: $managerId,
        ));
    }
}
