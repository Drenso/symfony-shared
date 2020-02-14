<?php

namespace Drenso\Shared\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class GravatarExtension
 *
 * This extension generates a gravatar url for any emailaddress
 */
class GravatarExtension extends AbstractExtension
{

  /**
   * @return array
   */
  public function getFilters(): array
  {
    return array(
        new TwigFilter('gravatarImage', array($this, 'gravatarImage')),
    );
  }

  /**
   * @param string      $emailAddress
   * @param string|null $size
   *
   * @return string
   */
  public function gravatarImage(string $emailAddress, ?string $size = null): string
  {
    return sprintf("https://www.gravatar.com/avatar/%s?d=mm%s", md5(strtolower(trim($emailAddress))), $size ? sprintf("&s=%d", $size) : "");
  }
}
