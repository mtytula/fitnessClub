<?php

namespace App\Controller\Admin;

use App\Facade\ParticipantFacade;
use App\Form\ParticipantType;
use App\Model\Participant as ParticipantModel;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParticipantController extends AbstractController
{
    /**
     * @var ParticipantFacade $participantFacade
     */
    private $participantFacade;

    /**
     * ParticipantController constructor.
     * @param ParticipantFacade $participantFacade
     */
    public function __construct(ParticipantFacade $participantFacade)
    {
        $this->participantFacade = $participantFacade;
    }

    /**
     * @param ParticipantRepository $participantRepository
     * @return Response
     */
    public function index(ParticipantRepository $participantRepository): Response
    {
        $participants = $participantRepository->findAll();

        return $this->render('participant/index.html.twig', [
           'participants' =>  $participants
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $participantModel = new ParticipantModel();

        $form = $this->createForm(ParticipantType::class, $participantModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->participantFacade->createParticipant(
                $participantModel->firstName,
                $participantModel->lastName,
                $participantModel->email
            );

            $this->addFlash(
                'success',
                $participantModel->firstName . ' ' . $participantModel->lastName . ' has been added'
            );

            return $this->redirectToRoute('participants');
        }

        return $this->render('participant/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param ParticipantRepository $participantRepository
     * @param int $id
     * @return Response
     */
    public function show(ParticipantRepository $participantRepository, int $id): Response
    {
        $participant = $participantRepository->find($id);
        if (!$participant) {
            $this->addFlash('danger', 'Participant was not found');

            return $this->redirectToRoute('participants');
        }

        return $this->render('participant/show.html.twig', [
            'participant' => $participant
        ]);
    }

    /**
     * @param ParticipantRepository $participantRepository
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ParticipantRepository $participantRepository, Request $request, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $participant = $participantRepository->find($id);
        if (!$participant) {
            $this->addFlash('danger', 'Participant was not found');

            return $this->redirectToRoute('participants');
        }
        $participantModel = new ParticipantModel();
        $updateParticipant = $participantModel->fromParticipant($participant);

        $form = $this->createForm(ParticipantType::class, $updateParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->participantFacade->updateParticipant(
                $participant,
                $updateParticipant->firstName,
                $updateParticipant->lastName,
                $updateParticipant->email
            );

            $this->addFlash(
                'success',
                $updateParticipant->firstName . ' ' . $participantModel->lastName . ' has been edited'
            );

            return $this->redirectToRoute('participants');
        }

        return $this->render('participant/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param ParticipantRepository $participantRepository
     * @param int $id
     * @return Response
     */
    public function delete(ParticipantRepository $participantRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $participant = $participantRepository->find($id);
        if (!$participant) {
            $this->addFlash('danger', 'Participant was not found');

            return $this->redirectToRoute('participants');
        }

        $this->participantFacade->delete($participant);
        $this->addFlash(
            'success',
            $participant->getFirstName() . ' '. $participant->getLastName() . " has been deleted"
        );

        return $this->redirectToRoute('participants');
    }
}
