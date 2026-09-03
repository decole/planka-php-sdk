<?php

declare(strict_types=1);

namespace Planka\Bridge\Enum;

enum CommentTypeEnum: string
{
    case COMMENT_CARD = 'commentCard';
    case CREATE_CARD = 'createCard';
    case MOVE_CARD = 'moveCard';
    case ADD_MEMBER_TO_CARD = 'addMemberToCard';
    case REMOVE_MEMBER_FROM_CARD = 'removeMemberFromCard';
    case COMPLETE_TASK = 'completeTask';
    case UNCOMPLETE_TASK = 'uncompleteTask';
}
