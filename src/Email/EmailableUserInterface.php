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
   *
   * @return string
   */
  function getEmailAddress(): string;

  /**
   * Should return the full name to be used for the e-mail recipient
   *
   * @return string
   */
  function getEmailName(): string;
}
