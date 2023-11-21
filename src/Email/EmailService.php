<?php

namespace Drenso\Shared\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class EmailService.
 *
 * Works as an abstraction layer to create e-mails with the correct parameters
 */
class EmailService
{
  /** EmailService constructor. */
  public function __construct(
    private readonly MailerInterface $mailer,
    private readonly string $senderEmail,
    private readonly ?string $senderName,
    private readonly ?TranslatorInterface $translator,
    private readonly TransportInterface $transport)
  {
  }

  /**
   * Queues the e-mail by placing it on the message bus.
   *
   * @throws TransportExceptionInterface
   *
   * @suppress PhanTypeInvalidThrowsIsInterface
   */
  public function send(RawMessage $message): void
  {
    $this->mailer->send($message);
  }

  /**
   * Send the e-mail directly, skipping the message bus.
   *
   * @throws TransportExceptionInterface
   *
   * @suppress PhanTypeInvalidThrowsIsInterface
   */
  public function sendDirectly(RawMessage $message): void
  {
    $this->transport->send($message);
  }

  /**
   * Create an e-mail object for a specific user.
   *
   * @param string|null $emailAddress Overwrites the e-mail address
   */
  public function emailForUser(EmailableUserInterface $user, ?string $emailAddress = null): TemplatedEmail
  {
    return $this->emailDefaults()
      ->to($this->getAddress($user, $emailAddress));
  }

  /**
   * Create an e-mail object for the given e- address.
   * Note that the `emailForUser` is preferred.
   */
  public function emailForEmailAddress(string $emailAddress): TemplatedEmail
  {
    return $this->emailDefaults()
      ->to(new Address($emailAddress));
  }

  /** Sets sane defaults for out e-mails. */
  private function emailDefaults(): TemplatedEmail
  {
    $senderName = $this->senderName
        ? ($this->translator ? $this->translator->trans($this->senderName) : $this->senderName)
        : '';

    return (new TemplatedEmail())
      ->from(new Address($this->senderEmail, $senderName));
  }

  /** Retrieve address object for an user. */
  private function getAddress(EmailableUserInterface $user, ?string $emailAddress = null): Address
  {
    return new Address(($emailAddress ?: $user->getEmailAddress()) ?? '', $user->getEmailName());
  }
}
