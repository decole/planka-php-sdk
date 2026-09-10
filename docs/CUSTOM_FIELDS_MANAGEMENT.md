# Custom Fields Management (Planka v2)

Planka v2 allows creating Base Custom Field Groups in Projects, Custom Field Groups on Boards or Cards, and Custom Fields within groups.

---

## 1. Prerequisites: Client Setup & Authentication

Before managing custom fields, initialize and authenticate your client using **API Key** or **Username/Password**:

```php
<?php

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

// Option A: API Key (Recommended)
$config = new Config(
    baseUri: 'http://192.168.1.100',
    port: 3000,
    apiKey: 'your_api_key'
);
$client = new PlankaClient($config);

// OR Option B: Username & Password (JWT)
// $config = new Config(user: 'admin@example.com', password: 'password', baseUri: 'http://192.168.1.100', port: 3000);
// $client = new PlankaClient($config);
// $client->authenticate();
```

---

## 2. Base Custom Field Groups (in Project)

```php
// Create base custom field group in project
$baseGroup = $client->baseCustomFieldGroup()->create(
    projectId: '1357158568008091264',
    name: 'Standard Specifications'
);

// Add custom field to base group
$customField = $client->customField()->createInBaseGroup(
    baseGroupId: $baseGroup->id,
    name: 'Priority',
    showOnFrontOfCard: true
);
```

---

## 3. Custom Field Groups (on Board or Card)

```php
// Create custom field group on a board
$boardGroup = $client->customFieldGroup()->createInBoard(
    boardId: '1357158568008091265',
    name: 'Board Properties'
);

// Add custom field into board group
$field = $client->customField()->createInGroup(
    groupId: $boardGroup->id,
    name: 'Estimated Hours',
    showOnFrontOfCard: false
);
```

---

## 4. Update & Delete Custom Fields

```php
// Update field
$updated = $client->customField()->update(
    id: $field->id,
    name: 'Estimated Time (Hours)',
    position: 65536,
    showOnFrontOfCard: true
);

// Partially update field via CustomFieldPatchInput
use Planka\Bridge\Inputs\CustomFieldPatchInput;

$patchedField = $client->customField()->patching(
    id: $field->id,
    map: new CustomFieldPatchInput(
        name: 'Story Points (SP)',
        showOnFrontOfCard: true
    )
);

// Delete field
$client->customField()->delete(id: $field->id);
```
