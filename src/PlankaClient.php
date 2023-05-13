<?php

declare(strict_types = 1);

namespace Planka\Bridge;

use Planka\Bridge\Controllers\BoardMembership;
use Planka\Bridge\Controllers\CardAction;
use Planka\Bridge\Controllers\CardLabel;
use Planka\Bridge\Controllers\CardMembership;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Planka\Bridge\Exceptions\AuthenticateException;
use Planka\Bridge\Actions\Auth\AuthenticateAction;
use Planka\Bridge\Actions\Common\GetInfoAction;
use Planka\Bridge\Exceptions\LogoutException;
use Planka\Bridge\Actions\Auth\LogoutAction;
use Planka\Bridge\Controllers\Notification;
use Planka\Bridge\Controllers\BoardList;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Controllers\Attachment;
use Planka\Bridge\Controllers\Comment;
use Planka\Bridge\Controllers\Project;
use Planka\Bridge\Controllers\Board;
use Planka\Bridge\Controllers\Card;
use Planka\Bridge\Controllers\Label;
use Planka\Bridge\Controllers\Task;
use Planka\Bridge\Controllers\User;

/**
 * https://github.com/plankanban/planka/blob/master/server/config/routes.js
 */
final class PlankaClient
{
    public readonly Attachment $attachment;
    public readonly Board $board;
    public readonly BoardList $boardList;
    public readonly BoardMembership $boardMembership;
    public readonly Card $card;
    public readonly CardAction $cardAction;
    public readonly CardLabel $cardLabel;
    public readonly CardMembership $cardMembership;
    public readonly Comment $comment;
    public readonly Label $label;
    public readonly Notification $notification;
    public readonly Project $project;
    public readonly Task $task;
    public readonly User $user;

    private readonly Client $client;

    public function __construct(
        private readonly Config $config,
        ?Client $client = null
    ) {
        if ($client === null) {
            $this->client = new Client($this->config->getBaseUri(), $this->config->getPort());
        }

        $this->attachment = new Attachment($config, $this->client); // todo воспроизвести прикрепления
        $this->board = new Board($config, $this->client);
        $this->boardList = new BoardList($config, $this->client);
        $this->boardMembership = new BoardMembership($config, $this->client);
        $this->card = new Card($config, $this->client);
        $this->cardAction = new CardAction($config, $this->client);
        $this->cardLabel = new CardLabel($config, $this->client);
        $this->cardMembership = new CardMembership($config, $this->client);
        $this->comment = new Comment($config, $this->client);
        $this->label = new Label($config, $this->client);
        $this->notification = new Notification($config, $this->client);
        $this->project = new Project($config, $this->client);
        $this->task = new Task($config, $this->client);
        $this->user = new User($config, $this->client);
    }

    /**
     * 'POST /api/access-tokens'
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
     * 'DELETE /api/access-tokens/me'
     * @throws AuthenticateException|LogoutException
     */
    public function logout(): void
    {
        $response = $this->client->delete(new LogoutAction(token: $this->config->getAuthToken()));

        $this->config->setAuthToken(null);

        if ($response->getStatusCode() !== 200) {
            throw new LogoutException($response->getContent());
        }
    }

    /** 'GET /' - for ping Planka */
    public function getInfo(): ResponseInterface
    {
        return $this->client->get(new GetInfoAction());
    }
}