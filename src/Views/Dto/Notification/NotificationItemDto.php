<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Notification;

/**
 * Represents a single notification item in response lists.
 *
 * This DTO extends NotificationDto and is structurally identical to it because
 * OpenAPI (swagger.json) defines a single 'Notification' schema. It exists to
 * maintain SDK-wide type consistency and Output Factory conventions for list items.
 */
final class NotificationItemDto extends NotificationDto {}
