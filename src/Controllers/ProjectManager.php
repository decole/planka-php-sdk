<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\ProjectManager\ProjectManagerCreateAction;
use Planka\Bridge\Actions\ProjectManager\ProjectManagerDeleteAction;
use Planka\Bridge\Contracts\Resources\ProjectManagerResourceInterface;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Exceptions\ValidateException;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Project\ProjectManagerDto;

final class ProjectManager implements ProjectManagerResourceInterface
{
    public function __construct(private readonly TransportClientInterface $client) {}

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
        } catch (ResponseException $exception) {
            if (409 === $exception->getStatusCode()) {
                throw new ValidateException('User already joined to project managers', 409, $exception);
            }

            throw $exception;
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
