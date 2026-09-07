# Type-Safe Partial Updates with Patch Input DTOs

Planka SDK v2 supports type-safe partial updates via `patching()` method using Input DTOs: `BoardPatchInput`, `CardPatchInput`, and `ProjectPatchInput`.

---

## 1. Card Partial Updates (`CardPatchInput`)

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;
use Planka\Bridge\Inputs\CardPatchInput;

$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'your_api_key'
);

$planka = new PlankaClient($config);

// Partially update card properties using CardPatchInput DTO
$card = $planka->card()->patching(
    cardId: '1357158568008091264',
    map: new CardPatchInput(
        name: 'Refactor Auth Module',
        isClosed: false,
        isDueCompleted: true
    )
);

echo "Updated Card: {$card->name}\n";
```

---

## 2. Board Partial Updates (`BoardPatchInput`)

```php
use Planka\Bridge\Inputs\BoardPatchInput;

$board = $planka->board()->patching(
    boardId: '1357158568008091265',
    map: new BoardPatchInput(
        name: 'Sprint 2026 Board',
        defaultView: 'kanban',
        expandTaskListsByDefault: true
    )
);
```

---

## 3. Project Partial Updates (`ProjectPatchInput`)

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
```
