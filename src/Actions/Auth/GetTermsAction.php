<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\LanguageEnum;
use Planka\Bridge\Views\Factory\Terms\TermsDtoFactory;

final class GetTermsAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(private readonly ?LanguageEnum $language = null) {}

    public function url(): string
    {
        return 'api/terms';
    }

    public function getOptions(): array
    {
        return [
            'query' => [
                'language' => $this->language?->value ?? LanguageEnum::EN_US->value,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new TermsDtoFactory();
    }
}
