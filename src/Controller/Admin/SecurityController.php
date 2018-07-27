<?php

namespace App\Controller\Admin;

use App\Facade\UserFacade;
use App\Form\RegistrationType;
use App\Model\User as UserModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @param Request $request
     * @param UserFacade $userFacade
     * @return Response
     */
    public function register(Request $request, UserFacade $userFacade): Response
    {
        $userModel = new UserModel();

        $form = $this->createForm(RegistrationType::class, $userModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userFacade->register(
                $userModel->username,
                $userModel->password,
                $userModel->email
            );

            $this->addFlash(
                'success',
                sprintf(
                    'Hi %s you have successfully registered an account, now you can log in!',
                    $userModel->username
                )
            );

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
