<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Board;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class BoardDto implements OutputDtoInterface
{
    // {
    //    "item": {
    //        "id": "745435921242915851",
    //        "createdAt": "2022-06-24T07:08:06.000Z",
    //        "updatedAt": null,
    //        "position": 131070,
    //        "name": "host.com",
    //        "projectId": "744759349448016899"
    //    },
    //    "included": {
    //        "users": [
    //            {
    //                "id": "744724852362970113",
    //                "createdAt": "2022-06-23T07:35:20.000Z",
    //                "updatedAt": "2022-10-14T07:42:25.000Z",
    //                "email": "decole@rambler.ru",
    //                "isAdmin": true,
    //                "name": "decole",
    //                "username": "demo",
    //                "phone": "+7(961)693-77-98",
    //                "organization": "ООО Teamleads",
    //                "language": "ru",
    //                "subscribeToOwnCards": false,
    //                "deletedAt": null,
    //                "avatarUrl": "https://***/user-avatars/c88b0866-0e0f-483e-8736-55950dc5693f/square-100.jpg"
    //            }
    //        ],
    //        "boardMemberships": [
    //            {
    //                "id": "745435921712677900",
    //                "createdAt": "2022-06-24T07:08:06.000Z",
    //                "updatedAt": null,
    //                "role": "editor",
    //                "canComment": null,
    //                "boardId": "745435921242915851",
    //                "userId": "744724852362970113"
    //            }
    //        ],
    //        "labels": [
    //            {
    //                "id": "770114546592384237",
    //                "createdAt": "2022-07-28T08:20:07.000Z",
    //                "updatedAt": "2022-08-13T19:54:31.000Z",
    //                "position": 65535,
    //                "name": "ASBD-IIS",
    //                "color": "berry-red",
    //                "boardId": "745435921242915851"
    //            },
    //        ],
    //        "lists": [
    //            {
    //                "id": "745596217727124495",
    //                "createdAt": "2022-06-24T12:26:35.000Z",
    //                "updatedAt": null,
    //                "position": 65535,
    //                "name": "Backlog",
    //                "boardId": "745435921242915851"
    //            },
    //        ],
    //        "cards": [
    //            {
    //                "id": "887800796094137856",
    //                "createdAt": "2023-01-06T17:21:42.000Z",
    //                "updatedAt": "2023-03-28T12:15:14.000Z",
    //                "position": 16383.75,
    //                "name": "[OWASP security] check active ports",
    //                "description": "`sudo nmap -sT -p- 83.239.141.68`",
    //                "dueDate": null,
    //                "timer": null,
    //                "boardId": "745435921242915851",
    //                "listId": "745596475987198997",
    //                "creatorUserId": "744724852362970113",
    //                "coverAttachmentId": null,
    //                "isSubscribed": false
    //            },
    //        ],
    //        "cardMemberships": [],
    //        "cardLabels": [
    //            {
    //                "id": "784991220060390780",
    //                "createdAt": "2022-08-17T20:57:25.000Z",
    //                "updatedAt": null,
    //                "cardId": "746557357336560756",
    //                "labelId": "770114546592384237"
    //            },
    //        ],
    //        "tasks": [
    //            {
    //                "id": "897662777894635101",
    //                "createdAt": "2023-01-20T07:55:41.000Z",
    //                "updatedAt": null,
    //                "position": 65535,
    //                "name": "Please install the \"intl\" PHP extension for best performance.",
    //                "isCompleted": false,
    //                "cardId": "891364383349802559"
    //            },
    //        ],
    //        "attachments": [],
    //        "projects": [
    //            {
    //                "id": "744759349448016899",
    //                "createdAt": "2022-06-23T08:43:52.000Z",
    //                "updatedAt": "2022-10-14T07:42:49.000Z",
    //                "name": "host.com",
    //                "background": {
    //                    "type": "image"
    //                },
    //                "backgroundImage": {
    //                    "url": "https://***/project-background-images/e20b4ec5-4659-468b-bc36-4aab7dca8716/original.jpg",
    //                    "coverUrl": "https://***/project-background-images/e20b4ec5-4659-468b-bc36-4aab7dca8716/cover-336.jpg"
    //                }
    //            }
    //        ]
    //    }
    //}
    public function __construct(
        public readonly ?BoardItemDto $item,
        public readonly ?BoardIncludedDto $included
    ) {
    }
}