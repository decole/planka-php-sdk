<?php

declare(strict_types=1);

namespace Planka\Bridge;

use Planka\Bridge\Contracts\Resources\AccessTokenResourceInterface;
use Planka\Bridge\Contracts\Resources\AttachmentResourceInterface;
use Planka\Bridge\Contracts\Resources\BaseCustomFieldGroupResourceInterface;
use Planka\Bridge\Contracts\Resources\BoardListResourceInterface;
use Planka\Bridge\Contracts\Resources\BoardMembershipResourceInterface;
use Planka\Bridge\Contracts\Resources\BoardResourceInterface;
use Planka\Bridge\Contracts\Resources\CardActionResourceInterface;
use Planka\Bridge\Contracts\Resources\CardLabelResourceInterface;
use Planka\Bridge\Contracts\Resources\CardMembershipResourceInterface;
use Planka\Bridge\Contracts\Resources\CardResourceInterface;
use Planka\Bridge\Contracts\Resources\CardTaskResourceInterface;
use Planka\Bridge\Contracts\Resources\CommentResourceInterface;
use Planka\Bridge\Contracts\Resources\CustomFieldGroupResourceInterface;
use Planka\Bridge\Contracts\Resources\CustomFieldResourceInterface;
use Planka\Bridge\Contracts\Resources\LabelResourceInterface;
use Planka\Bridge\Contracts\Resources\NotificationResourceInterface;
use Planka\Bridge\Contracts\Resources\NotificationServiceResourceInterface;
use Planka\Bridge\Contracts\Resources\ProjectManagerResourceInterface;
use Planka\Bridge\Contracts\Resources\ProjectResourceInterface;
use Planka\Bridge\Contracts\Resources\SystemConfigResourceInterface;
use Planka\Bridge\Contracts\Resources\TermsResourceInterface;
use Planka\Bridge\Contracts\Resources\UserResourceInterface;
use Planka\Bridge\Contracts\Resources\WebhookResourceInterface;
use Planka\Bridge\Enum\LanguageEnum;
use Planka\Bridge\Exceptions\AuthenticateException;
use Planka\Bridge\Exceptions\LogoutException;
use Planka\Bridge\Views\Dto\Auth\AuthenticateResultDto;
use Planka\Bridge\Views\Dto\Common\BootstrapDto;
use Planka\Bridge\Views\Dto\Common\ServerInfoDto;

interface PlankaClientInterface
{
    public function accessToken(): AccessTokenResourceInterface;

    public function attachment(): AttachmentResourceInterface;

    public function baseCustomFieldGroup(): BaseCustomFieldGroupResourceInterface;

    public function board(): BoardResourceInterface;

    public function boardList(): BoardListResourceInterface;

    public function boardMembership(): BoardMembershipResourceInterface;

    public function card(): CardResourceInterface;

    public function cardAction(): CardActionResourceInterface;

    public function cardLabel(): CardLabelResourceInterface;

    public function cardTask(): CardTaskResourceInterface;

    public function cardMembership(): CardMembershipResourceInterface;

    public function comment(): CommentResourceInterface;

    public function customField(): CustomFieldResourceInterface;

    public function customFieldGroup(): CustomFieldGroupResourceInterface;

    public function label(): LabelResourceInterface;

    public function notification(): NotificationResourceInterface;

    public function notificationService(): NotificationServiceResourceInterface;

    public function project(): ProjectResourceInterface;

    public function projectManager(): ProjectManagerResourceInterface;

    public function systemConfig(): SystemConfigResourceInterface;

    public function terms(): TermsResourceInterface;

    public function user(): UserResourceInterface;

    public function webhook(): WebhookResourceInterface;

    /**
     * @throws AuthenticateException
     */
    public function authenticate(bool $withHttpOnlyToken = false): AuthenticateResultDto;

    /**
     * @throws AuthenticateException
     */
    public function verifyTotp(string $pendingToken, string $code, bool $trustDevice = false): AuthenticateResultDto;

    /**
     * @throws AuthenticateException
     */
    public function acceptTerms(
        string $pendingToken,
        string $signature,
        ?LanguageEnum $initialLanguage = null,
    ): AuthenticateResultDto;

    /**
     * @throws AuthenticateException|LogoutException
     */
    public function logout(): void;

    public function getInfo(): ServerInfoDto;

    public function getBootstrap(): BootstrapDto;

    /**
     * Executes multiple actions in batch.
     *
     * @param array<string|int, Contracts\Actions\ActionInterface> $actions
     * @param string                                               $method  Default HTTP method (GET, POST, etc.)
     *
     * @return array<string|int, mixed>
     */
    public function batch(array $actions, string $method = 'GET'): array;
}
