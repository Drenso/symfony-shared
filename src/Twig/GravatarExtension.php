<?php

namespace Drenso\Shared\Twig;

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
   * The fallback style of gravatar to use in case there is not gravatar available
   *
   * @var string
   */
  private $fallbackStyle;

  public function __construct(string $fallbackStyle)
  {
    $this->fallbackStyle = $fallbackStyle;
  }

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
   * @param string|null $fallbackStyle
   *
   * @return string
   */
  public function gravatarImage(string $emailAddress, ?string $size = NULL, ?string $fallbackStyle = NULL): string
  {
    return sprintf("https://www.gravatar.com/avatar/%s?d=%s%s",
        md5(strtolower(trim($emailAddress))),
        $fallbackStyle ?? $this->fallbackStyle,
        $size ? sprintf("&s=%d", $size) : "");
  }
}
