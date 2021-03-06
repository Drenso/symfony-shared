<?php

namespace Drenso\Shared\Helper;

/**
 * Class GravatarHelper
 *
 * This helper generates a gravatar url for any email address
 */
class GravatarHelper
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
   * @param string      $emailAddress
   * @param int|null    $size
   *
   * @param string|null $fallbackStyle
   *
   * @return string
   */
  public function gravatarImage(string $emailAddress, ?int $size = NULL, ?string $fallbackStyle = NULL): string
  {
    return sprintf("https://www.gravatar.com/avatar/%s?d=%s%s",
        md5(strtolower(trim($emailAddress))),
        $fallbackStyle ?? $this->fallbackStyle,
        $size ? sprintf("&s=%d", $size) : "");
  }
}
