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
    use AuthenticateTrait;
    use ProjectHydrateTrait;

    public function __construct(private readonly ProjectDto $project, string $token)
    {
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
            'json' => [
                'name' => $this->project->name,
            ],
        ];

        if (BackgroundTypeEnum::IMAGE === $this->project?->background?->type) {
            $this->validateBackgroundImage();

            $body['json']['background'] = [
                'type' => BackgroundTypeEnum::IMAGE->value,
            ];
        }

        if (BackgroundTypeEnum::GRADIENT === $this->project?->background?->type) {
            $this->validateBackgroundGradient();

            $body['json']['background'] = [
                'type' => BackgroundTypeEnum::GRADIENT->value,
                'name' => $this->project->background->name->value,
            ];
        }

        if (null === $this->project->background) {
            $body['json']['background'] = null;
        }

        if (null === $this->project?->backgroundImage) {
            $body['json']['backgroundImage'] = null;
        }

        return $body;
    }

    /**
     * @throws ValidateException
     */
    private function validateBackgroundImage(): void
    {
        if (null === $this->project->backgroundImage) {
            throw new ValidateException('Empty image data for backgroundImage parameter');
        }
    }

    /**
     * @throws ValidateException
     */
    private function validateBackgroundGradient(): void
    {
        if (null === $this->project->background->name) {
            throw new ValidateException('Select gradient name by gradient background');
        }
    }
}
