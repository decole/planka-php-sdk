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
 * @property AccessToken          $accessToken
 * @property Attachment           $attachment
 * @property BaseCustomFieldGroup $baseCustomFieldGroup
 * @property Board                $board
 * @property BoardList            $boardList
 * @property BoardMembership      $boardMembership
 * @property Card                 $card
 * @property CardAction           $cardAction
 * @property CardLabel            $cardLabel
 * @property CardTask             $cardTask
 * @property CardMembership       $cardMembership
 * @property Comment              $comment
 * @property CustomField          $customField
 * @property CustomFieldGroup     $customFieldGroup
 * @property Label                $label
 * @property Notification         $notification
 * @property NotificationService  $notificationService
 * @property Project              $project
 * @property ProjectManager       $projectManager
 * @property SystemConfig         $systemConfig
 * @property Terms                $terms
 * @property User                 $user
 * @property Webhook              $webhook
 *
 * @see https://plankanban.github.io/planka/swagger-ui/
 */
final class PlankaClient
{
    private array $controllers = [];

    private const CONTROLLER_MAP = [
        'accessToken' => AccessToken::class,
        'attachment' => Attachment::class,
        'baseCustomFieldGroup' => BaseCustomFieldGroup::class,
        'board' => Board::class,
        'boardList' => BoardList::class,
        'boardMembership' => BoardMembership::class,
        'card' => Card::class,
        'cardAction' => CardAction::class,
        'cardLabel' => CardLabel::class,
        'cardTask' => CardTask::class,
        'cardMembership' => CardMembership::class,
        'comment' => Comment::class,
        'customField' => CustomField::class,
        'customFieldGroup' => CustomFieldGroup::class,
        'label' => Label::class,
        'notification' => Notification::class,
        'notificationService' => NotificationService::class,
        'project' => Project::class,
        'projectManager' => ProjectManager::class,
        'systemConfig' => SystemConfig::class,
        'terms' => Terms::class,
        'user' => User::class,
        'webhook' => Webhook::class,
    ];

    private readonly Client $client;

    public function __construct(
        private readonly Config $config,
        ?Client $client = null,
    ) {
        $this->client = $client ?? new Client($this->config);
    }

    public function __get(string $name): object
    {
        if (!isset(self::CONTROLLER_MAP[$name])) {
            throw new \InvalidArgumentException(sprintf("Controller '%s' does not exist on PlankaClient.", $name));
        }

        return $this->controllers[$name] ??= $this->createController(self::CONTROLLER_MAP[$name]);
    }

    private function createController(string $className): object
    {
        if (is_a($className, AccessToken::class, true)
            || is_a($className, Terms::class, true)
            || is_a($className, Webhook::class, true)
        ) {
            return new $className($this->client);
        }

        return new $className($this->config, $this->client);
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
