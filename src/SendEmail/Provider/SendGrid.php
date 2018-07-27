<?php

namespace App\SendEmail\Provider;

use App\SendEmail\Exception\ProviderException;
use App\SendEmail\ProviderInterface;
use SendGrid\Mail\Mail;

class SendGrid implements ProviderInterface
{
    const NAME = 'sendgrid';

    /**
     * @var \Twig_Environment $templating
     */
    private $templating;

    /**
     * @var string $emailAddress
     */
    private $emailAddress;

    /**
     * @var string $sendGridApiKey
     */
    private $sendGridApiKey;

    /**
     * SendGrid constructor.
     * @param string $sendGridApiKey
     * @param \Twig_Environment $templating
     * @param string $emailAddress
     */
    public function __construct(string $sendGridApiKey, \Twig_Environment $templating, string $emailAddress)
    {
        $this->templating = $templating;
        $this->emailAddress = $emailAddress;
        $this->sendGridApiKey = $sendGridApiKey;
    }

    /**
     * @param string $subject
     * @param string $deliveryAddress
     * @param string $template
     * @param array $data
     * @return bool
     * @throws ProviderException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function sendMail(string $subject, string $deliveryAddress, string $template, array $data): bool
    {
        try {
            $email = new Mail();
            $email->setFrom($this->emailAddress);
            $email->setSubject($subject);
            $email->addTo($deliveryAddress);
            $email->addContent(
                'text/html',
                $this->templating->render(
                    $template,
                    $data
                )
            );
            $sendGrid = new \SendGrid($this->sendGridApiKey);
            $response = $sendGrid->send($email);

            if ($response->statusCode() !== 202) {
                return false;
            }
        } catch (\Twig_Error_Loader | \Twig_Error_Runtime | \Twig_Error_Syntax | \Exception $exception) {
            return false;
        }
        
        return true;
    }

    /**
     * @param string $deliveryAddress
     * @param array $data
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws ProviderException
     */
    public function sendAssignEmail(string $deliveryAddress, array $data): bool
    {
        return $this->sendMail(
            'Confirmation signing to ' . $data['activity'],
            $deliveryAddress,
            'emails/activity_registration.html.twig',
            $data
        );
    }
}
