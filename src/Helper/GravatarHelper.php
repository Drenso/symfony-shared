<?php

namespace Drenso\Shared\Helper;

/** This helper generates a gravatar url for any email address. */
final readonly class GravatarHelper
{
  public function __construct(private string $fallbackStyle)
  {
  }

  public function gravatarImage(string $emailAddress, ?int $size = null, ?string $fallbackStyle = null): string
  {
    return sprintf('https://www.gravatar.com/avatar/%s?d=%s%s',
      md5(strtolower(trim($emailAddress))),
      $fallbackStyle ?? $this->fallbackStyle,
      $size ? sprintf('&s=%d', $size) : '');
  }
}
