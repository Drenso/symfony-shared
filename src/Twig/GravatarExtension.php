<?php

namespace Drenso\Shared\Twig;

use Drenso\Shared\Helper\GravatarHelper;
use Override;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/** This extension generates a gravatar url for any email address. */
class GravatarExtension extends AbstractExtension
{
  public function __construct(private readonly GravatarHelper $gravatarHelper)
  {
  }

  #[Override]
  public function getFilters(): array
  {
    return [
      new TwigFilter('gravatarImage', $this->gravatarHelper->gravatarImage(...)),
    ];
  }
}
