<?php

namespace Drenso\Shared\Twig;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

class PhoneNumberExtension extends AbstractExtension
{
  public function __construct(
    private readonly PhoneNumberUtil $phoneNumberUtil,
    public readonly int $nationalCountryCode,
  ) {
  }

  /** @return TwigFilter[] */
  public function getFilters(): array
  {
    return [
      new TwigFilter('phone_number_format_national', $this->phoneNumberFormatNational(...)),
      new TwigFilter('phone_number_href', $this->phoneNumberHref(...), ['is_safe' => ['html']]),
    ];
  }

  /** @return TwigTest[] */
  public function getTests(): array
  {
    return [
      new TwigTest('phone_number', $this->isPhoneNumber(...)),
    ];
  }

  public function phoneNumberFormatNational(PhoneNumber $phoneNumber, ?int $nationalCountryCode = null): string
  {
    $nationalCountryCode ??= $this->nationalCountryCode;

    return $this->phoneNumberUtil->format(
      $phoneNumber,
      $phoneNumber->getCountryCode() === $nationalCountryCode
        ? PhoneNumberFormat::NATIONAL
        : PhoneNumberFormat::INTERNATIONAL,
    );
  }

  public function phoneNumberHref(PhoneNumber $phoneNumber, ?int $nationalCountryCode = null): string
  {
    return "<a href=\"{$this->phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::RFC3966)}\">{$this->phoneNumberFormatNational($phoneNumber, $nationalCountryCode)}</a>";
  }

  public function isPhoneNumber(mixed $value): bool
  {
    return $value instanceof PhoneNumber;
  }
}
