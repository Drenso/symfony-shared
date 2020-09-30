<?php

namespace Drenso\Shared\Twig;

use Drenso\Shared\Helper\GravatarHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class GravatarExtension
 *
 * This extension generates a gravatar url for any email address
 */
class GravatarExtension extends AbstractExtension
{
  /**
   * @var GravatarHelper
   */
  private $gravatarHelper;

  public function __construct(GravatarHelper $gravatarHelper)
  {
    $this->gravatarHelper = $gravatarHelper;
  }

  /**
   * @return array
   */
  public function getFilters(): array
  {
    return array(
        new TwigFilter('gravatarImage', [$this->gravatarHelper, 'gravatarImage']),
    );
  }
}
