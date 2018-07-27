<?php

namespace App\SendEmail\Provider;

use App\SendEmail\Exception\ProviderException;
use App\SendEmail\ProviderInterface;

class SwiftMailer implements ProviderInterface
{
    const NAME = 'swiftmailer';

    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var \Swift_Mailer $mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment $templating
     */
    private $templating;

    /**
     * SwiftMailer constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->emailAddress = getenv('EMAIL_ADDRESS');
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param string $subject
     * @param string $deliveryAddress
     * @param string $template
     * @param array $data
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function sendEmail(string $subject, string $deliveryAddress, string $template, array $data): bool
    {
        try {
            $message = (new \Swift_Message($subject))
                ->setFrom($this->emailAddress)
                ->setTo($deliveryAddress)
                ->setBody(
                    $this->templating->render(
                        $template,
                        $data
                    ),
                    'text/html'
                );

            if ($this->mailer->send($message)) {
                return true;
            }
        } catch (\Twig_Error_Loader | \Twig_Error_Runtime | \Twig_Error_Syntax | \Swift_TransportException $exception) {
            return false;
        }

        return false;
    }

    /**
     * @param string $deliveryAddress
     * @param array $data
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendAssignEmail(string $deliveryAddress, array $data): bool
    {
        return $this->sendEmail(
            sprintf('Confirmation signing to %s', $data['activity']),
            $deliveryAddress,
            'emails/activity_registration.html.twig',
            $data
        );
    }
}
