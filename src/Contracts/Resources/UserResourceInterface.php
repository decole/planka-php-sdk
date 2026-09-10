<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Builders\UserBuilder;
use Planka\Bridge\Enum\UserRoleEnum;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Inputs\UserPatchInput;
use Planka\Bridge\Views\Dto\User\ApiKeyDto;
use Planka\Bridge\Views\Dto\User\TotpSetupDto;
use Planka\Bridge\Views\Dto\User\TrustedDeviceDto;
use Planka\Bridge\Views\Dto\User\UserDto;

interface UserResourceInterface
{
    public function builder(?string $name = null): UserBuilder;

    /**
     * @return UserDto[]
     */
    public function list(): array;

    public function create(string $email, string $name, string $password, string $username): UserDto;

    public function get(string $id): UserDto;

    public function update(UserDto $dto): UserDto;

    /**
     * @param array{
     *   name?: string,
     *   username?: string|null,
     *   email?: string,
     *   role?: 'admin'|'projectOwner'|'boardUser'|UserRoleEnum,
     *   isDeactivated?: bool
     * }|UserPatchInput|PatchInputInterface $map
     */
    public function patching(string $userId, array|PatchInputInterface $map): UserDto;

    public function updateEmail(UserDto $dto): UserDto;

    public function updatePassword(string $id, string $current, string $new): UserDto;

    public function updateUsername(UserDto $dto): UserDto;

    public function updateAvatar(UserDto $dto, string $file): UserDto;

    public function delete(UserDto $dto): UserDto;

    public function createApiKey(string $userId): ApiKeyDto;

    public function setupTotp(string $userId, string $currentPassword): TotpSetupDto;

    public function enableTotp(string $userId, string $currentPassword, string $code): UserDto;

    public function disableTotp(string $userId, ?string $currentPassword = null, ?string $code = null): UserDto;

    public function regenerateTotpRecoveryCodes(string $userId, string $currentPassword, string $code): mixed;

    /**
     * @return TrustedDeviceDto[]
     */
    public function listTrustedDevices(string $userId): array;

    public function deleteTrustedDevice(string $userId, string $deviceId): mixed;
}
