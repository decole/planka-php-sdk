# Type-Safe Partial Updates with Patch Input DTOs

Planka SDK v2 provides type-safe partial updates via the `patching()` method across all API entities using strongly-typed Input DTOs implementing `PatchInputInterface`.

---

## 1. Prerequisites: Client Setup & Authentication

Before executing any `patching()` or modification requests, the `PlankaClient` instance must be configured and authenticated. You can authenticate using either a **User API Key** (recommended for automations and scripts) or **Username & Password (JWT)**:

### Option A: Authentication via API Key (Recommended)
```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'your_user_api_key_here'
);

$planka = new PlankaClient($config);
// API Key is automatically included in request headers. No explicit ->authenticate() call needed.
```

### Option B: Authentication via Username & Password (JWT)
```php
use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

$config = new Config(
    user: 'admin@example.com',
    password: 'secure_password',
    baseUri: 'http://192.168.1.100',
    port: 3000
);

$planka = new PlankaClient($config);
$authResult = $planka->authenticate();

if (!$authResult->success) {
    throw new \RuntimeException('Authentication failed');
}
```

---

## 2. Card Partial Updates (`CardPatchInput`)

`CardPatchInput` allows modifying one or more card properties without touching unspecified fields. `null` values are automatically omitted from the payload, and `\DateTimeInterface` and `BackedEnum` types are automatically serialized:

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Inputs\CardPatchInput;

// 1. Configure and initialize the client
$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'your_api_key'
);

$planka = new PlankaClient($config);

// 2. Partially update card properties using CardPatchInput DTO
$card = $planka->card()->patching(
    cardId: '1357158568008091264',
    map: new CardPatchInput(
        name: 'Refactor Auth Module',
        description: 'Updated card description with detailed acceptance criteria.',
        dueDate: new \DateTimeImmutable('2026-10-15 18:00:00'),
        isDueCompleted: false,
        position: 65536,
        type: BoardDefaultCardTypeEnum::PROJECT,
        isClosed: false
    )
);

echo "Updated Card Name: {$card->name}\n";
echo "Due Date: {$card->dueDate?->format('Y-m-d H:i:s')}\n";
```

---

## 3. Board Partial Updates (`BoardPatchInput`)

```php
use Planka\Bridge\Inputs\BoardPatchInput;
use Planka\Bridge\Enum\BoardDefaultViewEnum;

$board = $planka->board()->patching(
    boardId: '1357158568008091265',
    map: new BoardPatchInput(
        name: 'Sprint 2026 Board',
        defaultView: BoardDefaultViewEnum::KANBAN->value,
        expandTaskListsByDefault: true,
        limitCardTypesToDefaultOne: false
    )
);

echo "Updated Board: {$board->item?->name}\n";
```

---

## 4. Project Partial Updates (`ProjectPatchInput`)

```php
use Planka\Bridge\Inputs\ProjectPatchInput;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Enum\BackgroundGradientEnum;

$project = $planka->project()->patching(
    projectId: '1357158568008091266',
    map: new ProjectPatchInput(
        name: 'Core SDK Development',
        backgroundType: BackgroundTypeEnum::GRADIENT,
        backgroundGradient: BackgroundGradientEnum::BLURPLE
    )
);

echo "Updated Project: {$project->name}\n";
```

---

## 5. User Partial Updates (`UserPatchInput`)

```php
use Planka\Bridge\Inputs\UserPatchInput;
use Planka\Bridge\Enum\UserRoleEnum;

$user = $planka->user()->patching(
    userId: '1853641278592386049',
    map: new UserPatchInput(
        name: 'Jane Doe',
        email: 'jane.doe@example.com',
        role: UserRoleEnum::PROJECT_OWNER,
        isDeactivated: false
    )
);

echo "Updated User: {$user->name} ({$user->role?->value})\n";
```

---

## 6. Board List Partial Updates (`BoardListPatchInput`)

```php
use Planka\Bridge\Inputs\BoardListPatchInput;
use Planka\Bridge\Enum\ListColorEnum;
use Planka\Bridge\Enum\ListTypeEnum;

$list = $planka->boardList()->patching(
    listId: '1853641278592386050',
    map: new BoardListPatchInput(
        name: 'In Review',
        position: 131072,
        color: ListColorEnum::LAGOON_BLUE,
        type: ListTypeEnum::ACTIVE
    )
);

echo "Updated List: {$list->name} (Color: {$list->color?->value})\n";
```

---

## 7. Webhook Partial Updates (`WebhookPatchInput`)

```php
use Planka\Bridge\Inputs\WebhookPatchInput;

$webhook = $planka->webhook()->patching(
    webhookId: '1853641278592386051',
    map: new WebhookPatchInput(
        name: 'CI/CD Pipeline Webhook',
        url: 'https://ci.company.com/planka-webhook',
        events: ['cardCreate', 'cardUpdate', 'cardDelete']
    )
);

echo "Updated Webhook: {$webhook->name}\n";
```

---

## 8. Task List & Card Task Partial Updates (`TaskListPatchInput`, `CardTaskPatchInput`)

```php
use Planka\Bridge\Inputs\TaskListPatchInput;
use Planka\Bridge\Inputs\CardTaskPatchInput;

// Update Task List container
$taskList = $planka->cardTask()->patchingTaskList(
    taskListId: '1853641278592386052',
    map: new TaskListPatchInput(
        name: 'Backend Deliverables',
        showOnFrontOfCard: true,
        hideCompletedTasks: false
    )
);

// Update individual checklist Task item
$task = $planka->cardTask()->patching(
    taskId: '1853641278592386053',
    map: new CardTaskPatchInput(
        name: 'Implement OAuth2 token storage',
        isCompleted: true,
        assigneeUserId: '1853641278592386049'
    )
);
```

---

## 9. Custom Fields Partial Updates (`CustomFieldPatchInput`, `CustomFieldGroupPatchInput`, `BaseCustomFieldGroupPatchInput`)

```php
use Planka\Bridge\Inputs\CustomFieldPatchInput;
use Planka\Bridge\Inputs\CustomFieldGroupPatchInput;

// Update Custom Field
$field = $planka->customField()->patching(
    id: '1853641278592386054',
    map: new CustomFieldPatchInput(
        name: 'Story Points',
        position: 1,
        showOnFrontOfCard: true
    )
);

// Update Custom Field Group
$group = $planka->customFieldGroup()->patching(
    id: '1853641278592386055',
    map: new CustomFieldGroupPatchInput(
        name: 'Scrum Metrics',
        position: 2
    )
);
```

---

## 10. Attachment Partial Updates (`AttachmentPatchInput`)

```php
use Planka\Bridge\Inputs\AttachmentPatchInput;

$attachment = $planka->attachment()->patching(
    attachmentId: '1853641278592386056',
    map: new AttachmentPatchInput(
        name: 'Architecture_Diagram_v2.pdf'
    )
);
```
