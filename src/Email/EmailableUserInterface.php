<?php

namespace Drenso\Shared\Email;

/**
 * Interface EmailableUserInterface
 * Marks the class as a mailable user
 */
interface EmailableUserInterface
{
  /**
   * Should return the e-mail address to be used for the e-mail recipient
   * Null will throw an error when trying to send an email
   *
   * @return string|null
   */
  public function getEmailAddress(): ?string;

  /**
   * Should return the full name to be used for the e-mail recipient
   *
   * @return string
   */
  public function getEmailName(): string;
}
