<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Views\Dto\Action\ActionDto;

/**
 * Represents a single action item on a card or board.
 *
 * This DTO extends ActionDto and is structurally identical to it because
 * OpenAPI (swagger.json) defines a single 'Action' schema. It exists to
 * maintain SDK-wide type consistency and Output Factory conventions for list items.
 */
class CardActionItemDto extends ActionDto {}
