<?php

declare(strict_types=1);

namespace Planka\Bridge;

use Symfony\Contracts\HttpClient\ResponseInterface;
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
    public readonly AccessToken $accessToken;

    public readonly Attachment $attachment;

    public readonly BaseCustomFieldGroup $baseCustomFieldGroup;

    public readonly Board $board;

    public readonly BoardList $boardList;

    public readonly BoardMembership $boardMembership;

    public readonly Card $card;

    public readonly CardAction $cardAction;

    public readonly CardLabel $cardLabel;

    public readonly CardTask $cardTask;

    public readonly CardMembership $cardMembership;

    public readonly Comment $comment;

    public readonly CustomField $customField;

    public readonly CustomFieldGroup $customFieldGroup;

    public readonly Label $label;

    public readonly Notification $notification;

    public readonly NotificationService $notificationService;

    public readonly Project $project;

    public readonly ProjectManager $projectManager;

    public readonly SystemConfig $systemConfig;

    public readonly Terms $terms;

    public readonly User $user;

    public readonly Webhook $webhook;

    private readonly Client $client;

    public function __construct(
        private readonly Config $config,
        ?Client $client = null,
    ) {
        if (null === $client) {
            $client = new Client($this->config);
        }

        $this->client = $client;

        $this->accessToken = new AccessToken($this->client);
        $this->attachment = new Attachment($config, $this->client);
        $this->baseCustomFieldGroup = new BaseCustomFieldGroup($config, $this->client);
        $this->board = new Board($config, $this->client);
        $this->boardList = new BoardList($config, $this->client);
        $this->boardMembership = new BoardMembership($config, $this->client);
        $this->card = new Card($config, $this->client);
        $this->cardAction = new CardAction($config, $this->client);
        $this->cardLabel = new CardLabel($config, $this->client);
        $this->cardMembership = new CardMembership($config, $this->client);
        $this->cardTask = new CardTask($config, $this->client);
        $this->comment = new Comment($config, $this->client);
        $this->customField = new CustomField($config, $this->client);
        $this->customFieldGroup = new CustomFieldGroup($config, $this->client);
        $this->label = new Label($config, $this->client);
        $this->notification = new Notification($config, $this->client);
        $this->notificationService = new NotificationService($config, $this->client);
        $this->project = new Project($config, $this->client);
        $this->projectManager = new ProjectManager($config, $this->client);
        $this->systemConfig = new SystemConfig($config, $this->client);
        $this->terms = new Terms($this->client);
        $this->user = new User($config, $this->client);
        $this->webhook = new Webhook($config, $this->client);
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
    public function getInfo(): ResponseInterface
    {
        return $this->client->get(new GetInfoAction());
    }
}
