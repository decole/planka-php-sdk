<?php

declare(strict_types=1);

namespace Planka\Bridge;

use Planka\Bridge\Actions\Auth\AcceptTermsAction;
use Planka\Bridge\Actions\Auth\AuthenticateAction;
use Planka\Bridge\Actions\Auth\LogoutAction;
use Planka\Bridge\Actions\Auth\VerifyTotpAction;
use Planka\Bridge\Actions\Common\GetBootstrapAction;
use Planka\Bridge\Actions\Common\GetInfoAction;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Controllers\AccessToken;
use Planka\Bridge\Controllers\Attachment;
use Planka\Bridge\Controllers\BaseCustomFieldGroup;
use Planka\Bridge\Controllers\Board;
use Planka\Bridge\Controllers\BoardList;
use Planka\Bridge\Controllers\BoardMembership;
use Planka\Bridge\Controllers\Card;
use Planka\Bridge\Controllers\CardAction;
use Planka\Bridge\Controllers\CardLabel;
use Planka\Bridge\Controllers\CardMembership;
use Planka\Bridge\Controllers\CardTask;
use Planka\Bridge\Controllers\Comment;
use Planka\Bridge\Controllers\CustomField;
use Planka\Bridge\Controllers\CustomFieldGroup;
use Planka\Bridge\Controllers\Label;
use Planka\Bridge\Controllers\Notification;
use Planka\Bridge\Controllers\NotificationService;
use Planka\Bridge\Controllers\Project;
use Planka\Bridge\Controllers\ProjectManager;
use Planka\Bridge\Controllers\SystemConfig;
use Planka\Bridge\Controllers\Terms;
use Planka\Bridge\Controllers\User;
use Planka\Bridge\Controllers\Webhook;
use Planka\Bridge\Enum\LanguageEnum;
use Planka\Bridge\Exceptions\AuthenticateException;
use Planka\Bridge\Exceptions\LogoutException;
use Planka\Bridge\Exceptions\PlankaAccessDeniedException;
use Planka\Bridge\TransportClients\BatchExecutor;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\TransportClients\Middleware\TransportMiddlewareInterface;
use Planka\Bridge\TransportClients\MiddlewareStackTransportClient;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Auth\AuthenticateResultDto;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @see https://plankanban.github.io/planka/swagger-ui/
 */
final class PlankaClient implements PlankaClientInterface
{
    private array $controllers = [];

    private readonly TransportClientInterface $client;

    /**
     * @param TransportMiddlewareInterface[] $middlewares
     */
    public function __construct(
        private readonly Config $config,
        ?TransportClientInterface $client = null,
        array $middlewares = [],
    ) {
        $baseClient = $client ?? new Client($this->config);

        $this->client = !empty($middlewares)
            ? new MiddlewareStackTransportClient($baseClient, $middlewares)
            : $baseClient;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    private function getController(string $class): object
    {
        /* @var T */
        return $this->controllers[$class] ??= new $class($this->client);
    }

    public function accessToken(): AccessToken
    {
        return $this->getController(AccessToken::class);
    }

    public function attachment(): Attachment
    {
        return $this->getController(Attachment::class);
    }

    public function baseCustomFieldGroup(): BaseCustomFieldGroup
    {
        return $this->getController(BaseCustomFieldGroup::class);
    }

    public function board(): Board
    {
        return $this->getController(Board::class);
    }

    public function boardList(): BoardList
    {
        return $this->getController(BoardList::class);
    }

    public function boardMembership(): BoardMembership
    {
        return $this->getController(BoardMembership::class);
    }

    public function card(): Card
    {
        return $this->getController(Card::class);
    }

    public function cardAction(): CardAction
    {
        return $this->getController(CardAction::class);
    }

    public function cardLabel(): CardLabel
    {
        return $this->getController(CardLabel::class);
    }

    public function cardTask(): CardTask
    {
        return $this->getController(CardTask::class);
    }

    public function cardMembership(): CardMembership
    {
        return $this->getController(CardMembership::class);
    }

    public function comment(): Comment
    {
        return $this->getController(Comment::class);
    }

    public function customField(): CustomField
    {
        return $this->getController(CustomField::class);
    }

    public function customFieldGroup(): CustomFieldGroup
    {
        return $this->getController(CustomFieldGroup::class);
    }

    public function label(): Label
    {
        return $this->getController(Label::class);
    }

    public function notification(): Notification
    {
        return $this->getController(Notification::class);
    }

    public function notificationService(): NotificationService
    {
        return $this->getController(NotificationService::class);
    }

    public function project(): Project
    {
        return $this->getController(Project::class);
    }

    public function projectManager(): ProjectManager
    {
        return $this->getController(ProjectManager::class);
    }

    public function systemConfig(): SystemConfig
    {
        return $this->getController(SystemConfig::class);
    }

    public function terms(): Terms
    {
        return $this->getController(Terms::class);
    }

    public function user(): User
    {
        return $this->getController(User::class);
    }

    public function webhook(): Webhook
    {
        return $this->getController(Webhook::class);
    }

    /**
     * 'POST /api/access-tokens'.
     *
     * @throws AuthenticateException
     */
    public function authenticate(bool $withHttpOnlyToken = false): AuthenticateResultDto
    {
        try {
            $response = $this->client->post(new AuthenticateAction(
                $this->config->getUser() ?? '',
                $this->config->getPassword() ?? '',
                $withHttpOnlyToken,
            ));
        } catch (PlankaAccessDeniedException $e) {
            $data = json_decode($e->getMessage(), true);

            if (is_array($data)) {
                $message = $data['message'] ?? '';
                $pendingToken = is_string($data['item'] ?? null) ? $data['item'] : ($data['pendingToken'] ?? null);
                $challenge = match ($message) {
                    'TOTP verification required' => AuthenticateResultDto::CHALLENGE_TOTP,
                    'Terms acceptance required' => AuthenticateResultDto::CHALLENGE_TERMS,
                    default => null,
                };

                if (null !== $challenge) {
                    return new AuthenticateResultDto(
                        success: false,
                        pendingToken: is_string($pendingToken) ? $pendingToken : null,
                        challenge: $challenge,
                        _rawResponse: $data,
                    );
                }
            }

            throw new AuthenticateException($e->getMessage(), $e->getCode(), $e);
        }

        $data = $response instanceof ResponseInterface
            ? $response->toArray(false)
            : (is_array($response) ? $response : []);

        $token = $data['item'] ?? null;

        if (!is_string($token) || '' === $token) {
            throw new AuthenticateException('Authentication failed: empty token returned');
        }

        $this->config->setAuthToken($token);

        return new AuthenticateResultDto(
            success: true,
            token: $token,
            _rawResponse: $data,
        );
    }

    /**
     * 'POST /api/access-tokens/verify-totp'.
     *
     * @throws AuthenticateException
     */
    public function verifyTotp(string $pendingToken, string $code, bool $trustDevice = false): AuthenticateResultDto
    {
        return $this->completePendingAuth(new VerifyTotpAction($pendingToken, $code, $trustDevice));
    }

    /**
     * 'POST /api/access-tokens/accept-terms'.
     *
     * @throws AuthenticateException
     */
    public function acceptTerms(
        string $pendingToken,
        string $signature,
        ?LanguageEnum $initialLanguage = null,
    ): AuthenticateResultDto {
        return $this->completePendingAuth(new AcceptTermsAction($pendingToken, $signature, $initialLanguage));
    }

    private function completePendingAuth(ActionInterface $action): AuthenticateResultDto
    {
        $response = $this->client->post($action);

        $data = $response instanceof ResponseInterface
            ? $response->toArray(false)
            : (is_array($response) ? $response : []);

        $token = $data['item'] ?? null;

        if (!is_string($token) || '' === $token) {
            throw new AuthenticateException((string) ($data['message'] ?? 'Authentication failed'));
        }

        $this->config->setAuthToken($token);

        return new AuthenticateResultDto(
            success: true,
            token: $token,
            _rawResponse: $data,
        );
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

    /** 'GET /api/bootstrap' */
    public function getBootstrap(): Views\Dto\Common\BootstrapDto
    {
        return $this->client->get(new GetBootstrapAction());
    }

    /**
     * Executes multiple actions in batch.
     *
     * @param array<string|int, ActionInterface> $actions
     *
     * @return array<string|int, mixed>
     */
    public function batch(array $actions, string $method = 'GET'): array
    {
        return (new BatchExecutor($this->client))->execute($actions, $method);
    }
}
