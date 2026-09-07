<?php

declare(strict_types=1);

namespace Planka\Bridge;

use Planka\Bridge\Exceptions\AuthenticateException;
use Planka\Bridge\Actions\Auth\AuthenticateAction;
use Planka\Bridge\Actions\Common\GetInfoAction;
use Planka\Bridge\Controllers\AccessToken;
use Planka\Bridge\Controllers\BoardMembership;
use Planka\Bridge\Controllers\CardMembership;
use Planka\Bridge\Controllers\ProjectManager;
use Planka\Bridge\Controllers\Terms;
use Planka\Bridge\Exceptions\LogoutException;
use Planka\Bridge\Actions\Auth\LogoutAction;
use Planka\Bridge\Controllers\Notification;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Controllers\Attachment;
use Planka\Bridge\Controllers\CardAction;
use Planka\Bridge\Controllers\BoardList;
use Planka\Bridge\Controllers\CardLabel;
use Planka\Bridge\Controllers\CardTask;
use Planka\Bridge\Controllers\Comment;
use Planka\Bridge\Controllers\Project;
use Planka\Bridge\Controllers\Board;
use Planka\Bridge\Controllers\Label;
use Planka\Bridge\Controllers\Card;
use Planka\Bridge\Controllers\BaseCustomFieldGroup;
use Planka\Bridge\Controllers\CustomField;
use Planka\Bridge\Controllers\CustomFieldGroup;
use Planka\Bridge\Controllers\NotificationService;
use Planka\Bridge\Controllers\SystemConfig;
use Planka\Bridge\Controllers\User;
use Planka\Bridge\Controllers\Webhook;

/**
 * @see https://plankanban.github.io/planka/swagger-ui/
 */
final class PlankaClient
{
    private array $controllers = [];

    private readonly TransportClientInterface $client;

    public function __construct(
        private readonly Config $config,
        ?TransportClientInterface $client = null,
    ) {
        $this->client = $client ?? new Client($this->config);
    }

    public function accessToken(): AccessToken
    {
        return $this->controllers['accessToken'] ??= new AccessToken($this->client);
    }

    public function attachment(): Attachment
    {
        return $this->controllers['attachment'] ??= new Attachment($this->client);
    }

    public function baseCustomFieldGroup(): BaseCustomFieldGroup
    {
        return $this->controllers['baseCustomFieldGroup'] ??= new BaseCustomFieldGroup($this->client);
    }

    public function board(): Board
    {
        return $this->controllers['board'] ??= new Board($this->client);
    }

    public function boardList(): BoardList
    {
        return $this->controllers['boardList'] ??= new BoardList($this->client);
    }

    public function boardMembership(): BoardMembership
    {
        return $this->controllers['boardMembership'] ??= new BoardMembership($this->client);
    }

    public function card(): Card
    {
        return $this->controllers['card'] ??= new Card($this->client);
    }

    public function cardAction(): CardAction
    {
        return $this->controllers['cardAction'] ??= new CardAction($this->client);
    }

    public function cardLabel(): CardLabel
    {
        return $this->controllers['cardLabel'] ??= new CardLabel($this->client);
    }

    public function cardTask(): CardTask
    {
        return $this->controllers['cardTask'] ??= new CardTask($this->client);
    }

    public function cardMembership(): CardMembership
    {
        return $this->controllers['cardMembership'] ??= new CardMembership($this->client);
    }

    public function comment(): Comment
    {
        return $this->controllers['comment'] ??= new Comment($this->client);
    }

    public function customField(): CustomField
    {
        return $this->controllers['customField'] ??= new CustomField($this->client);
    }

    public function customFieldGroup(): CustomFieldGroup
    {
        return $this->controllers['customFieldGroup'] ??= new CustomFieldGroup($this->client);
    }

    public function label(): Label
    {
        return $this->controllers['label'] ??= new Label($this->client);
    }

    public function notification(): Notification
    {
        return $this->controllers['notification'] ??= new Notification($this->client);
    }

    public function notificationService(): NotificationService
    {
        return $this->controllers['notificationService'] ??= new NotificationService($this->client);
    }

    public function project(): Project
    {
        return $this->controllers['project'] ??= new Project($this->client);
    }

    public function projectManager(): ProjectManager
    {
        return $this->controllers['projectManager'] ??= new ProjectManager($this->client);
    }

    public function systemConfig(): SystemConfig
    {
        return $this->controllers['systemConfig'] ??= new SystemConfig($this->client);
    }

    public function terms(): Terms
    {
        return $this->controllers['terms'] ??= new Terms($this->client);
    }

    public function user(): User
    {
        return $this->controllers['user'] ??= new User($this->client);
    }

    public function webhook(): Webhook
    {
        return $this->controllers['webhook'] ??= new Webhook($this->client);
    }

    /**
     * 'POST /api/access-tokens'.
     *
     * @throws AuthenticateException
     */
    public function authenticate(): bool
    {
        $response = $this->client->post(new AuthenticateAction($this->config->getUser(), $this->config->getPassword()));

        $token = $response->toArray()['item'] ?? null;

        if (empty($token)) {
            throw new AuthenticateException('not authenticate');
        }

        $this->config->setAuthToken($token);

        return true;
    }

    /**
     * 'DELETE /api/access-tokens/me'.
     *
     * @throws AuthenticateException|LogoutException
     */
    public function logout(): void
    {
        $response = $this->client->delete(new LogoutAction());

        $this->config->setAuthToken(null);

        if (200 !== $response->getStatusCode()) {
            throw new LogoutException($response->getContent());
        }
    }

    /** 'GET /' - for ping Planka */
    public function getInfo(): Views\Dto\Common\ServerInfoDto
    {
        return $this->client->get(new GetInfoAction());
    }
}
