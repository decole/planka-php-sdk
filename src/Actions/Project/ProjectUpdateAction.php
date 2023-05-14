<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Exceptions\ValidateException;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Traits\ProjectHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Enum\BackgroundTypeEnum;

final class ProjectUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, ProjectHydrateTrait;

    public function __construct(
        string $token,
        private readonly ProjectDto $project,
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/projects/{$this->project->id}";
    }

    /**
     * @throws ValidateException
     */
    public function getOptions(): array
    {
        $body = [
            'body' => [
                'name' => $this->project->name,
            ],
        ];

        if ($this->project->background->type === BackgroundTypeEnum::IMAGE) {
            $this->validateBackgroundImage();

            $body['body']['background'] = [
                'type' => BackgroundTypeEnum::IMAGE->value,
            ];
        }

        if ($this->project->background->type === BackgroundTypeEnum::GRADIENT) {
            $this->validateBackgroundGradient();

            $body['body']['background'] = [
                'type' => BackgroundTypeEnum::GRADIENT->value,
                'name' => $this->project->background->name->value,
            ];
        }

        if ($this->project->background === null) {
            $body['body']['background'] = null;
        }

        if ($this->project->backgroundImage === null) {
            $body['body']['backgroundImage'] = null;
        }

        return $body;
    }

    /**
     * @throws ValidateException
     */
    private function validateBackgroundImage(): void
    {
        if ($this->project->backgroundImage === null) {
            throw new ValidateException('Empty image data for backgroundImage parameter');
        }
    }

    /**
     * @throws ValidateException
     */
    private function validateBackgroundGradient(): void
    {
        if ($this->project->background->name === null) {
            throw new ValidateException('Select gradient name by gradient background');
        }
    }
}