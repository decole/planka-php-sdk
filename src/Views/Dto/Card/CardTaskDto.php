<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Views\Dto\Task\TaskDto;

/**
 * Represents a single task item inside a task list on a card.
 *
 * This DTO extends TaskDto and is structurally identical to it because
 * OpenAPI (swagger.json) defines a single 'Task' schema. It exists to
 * maintain SDK-wide type consistency and Output Factory conventions for list items.
 */
class CardTaskDto extends TaskDto {}
