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
 * Class EmailService
 *
 * Works as an abstraction layer to create e-mails with the correct parameters
 */
class EmailService
{
  /**
   * @var MailerInterface
   */
  private $mailer;
  /**
   * @var string
   */
  private $senderEmail;
  /**
   * @var string|null
   */
  private $senderName;
  /**
   * @var TranslatorInterface|null
   */
  private $translator;
  /**
   * @var TransportInterface
   */
  private $transport;

  /**
   * EmailService constructor.
   *
   * @param MailerInterface          $mailer
   * @param string                   $senderEmail
   * @param string|null              $senderName
   * @param TranslatorInterface|null $translator
   * @param TransportInterface       $transport
   */
  public function __construct(
      MailerInterface $mailer, string $senderEmail, ?string $senderName,
      ?TranslatorInterface $translator, TransportInterface $transport)
  {
    $this->mailer      = $mailer;
    $this->senderEmail = $senderEmail;
    $this->senderName  = $senderName;
    $this->translator  = $translator;
    $this->transport   = $transport;
  }

  /**
   * Queues the e-mail by placing it on the message bus
   *
   * @param RawMessage $message
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
   * Send the e-mail directly, skipping the message bus
   *
   * @param RawMessage $message
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
   * Create an e-mail object for a specific user
   *
   * @param EmailableUserInterface $user
   * @param string|null            $emailAddress Overwrites the e-mail address
   *
   * @return TemplatedEmail
   */
  public function emailForUser(EmailableUserInterface $user, ?string $emailAddress = NULL): TemplatedEmail
  {
    return $this->emailDefaults()
        ->to($this->getAddress($user, $emailAddress));
  }

  /**
   * Create an e-mail object for the given e- address.
   * Note that the `emailForUser` is preferred.
   *
   * @param string $emailAddress
   *
   * @return TemplatedEmail
   */
  public function emailForEmailAddress(string $emailAddress): TemplatedEmail
  {
    return $this->emailDefaults()
        ->to(new Address($emailAddress));
  }

  /**
   * Sets sane defaults for out e-mails
   *
   * @return TemplatedEmail
   */
  private function emailDefaults(): TemplatedEmail
  {
    $senderName = $this->senderName
        ? ($this->translator ? $this->translator->trans($this->senderName) : $this->senderName)
        : '';

    return (new TemplatedEmail())
        ->from(new Address($this->senderEmail, $senderName));
  }

  /**
   * Retrieve address object for an user
   *
   * @param EmailableUserInterface $user
   * @param string|null            $emailAddress
   *
   * @return Address
   */
  private function getAddress(EmailableUserInterface $user, ?string $emailAddress = NULL): Address
  {
    return new Address($emailAddress ? $emailAddress : $user->getEmailAddress(), $user->getEmailName());
  }
}
