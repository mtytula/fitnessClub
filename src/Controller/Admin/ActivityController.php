<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use App\Model\Activity as ActivityModel;
use App\Facade\ActivityFacade;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends AbstractController
{
    /**
     * @var ActivityFacade $activityFacade
     */
    private $activityFacade;

    /**
     * ActivityController constructor.
     * @param ActivityFacade $activityFacade
     */
    public function __construct(ActivityFacade $activityFacade)
    {
        $this->activityFacade = $activityFacade;
    }

    /**
     * @param ActivityRepository $activityRepository
     * @return Response
     */
    public function index(ActivityRepository $activityRepository): Response
    {
        return $this->render("activity/index.html.twig", [
            'activities' => $activityRepository->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $activityModel = new ActivityModel();

        $form = $this->createForm(ActivityType::Class, $activityModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->activityFacade->createActivity(
                $activityModel->name,
                $activityModel->description,
                $activityModel->slots
            );

            $this->addFlash('success', $activityModel->name . ' has been added');

            return $this->redirectToRoute('activities');
        }

        return $this->render('activity/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param ActivityRepository $activityRepository
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(int $id, ActivityRepository $activityRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $activity = $activityRepository->find($id);
        if (!$activity) {
            $this->addFlash('danger', 'Activity was not found');

            return $this->redirectToRoute('activities');
        }
        $activityModel = new ActivityModel();
        $updateActivity = $activityModel->fromActivity($activity);

        $form = $this->createForm(ActivityType::class, $updateActivity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->activityFacade->updateActivity(
                $activity,
                $updateActivity->name,
                $updateActivity->description,
                $updateActivity->slots
            );

            $this->addFlash('success', $updateActivity->name . ' has been edited');

            return $this->redirectToRoute('activities');
        }

        return $this->render('activity/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $activityRepository = $entityManager->getRepository(Activity::class);
        $activity = $activityRepository->find($id);
        if (!$activity) {
            $this->addFlash('danger', 'Activity was not found');

            return $this->redirectToRoute('activities');
        }

        return $this->render('activity/show.html.twig', [
            'activity' => $activity
        ]);
    }

    /**
     * @param int $id
     * @param ActivityRepository $activityRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(
        int $id,
        ActivityRepository $activityRepository,
        EntityManagerInterface $entityManager,
        ScheduleRepository $scheduleRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $activity = $activityRepository->find($id);
        if (!$activity) {
            $this->addFlash('danger', 'Activity was not found');

            return $this->redirectToRoute('activities');
        }

        $assigned = $scheduleRepository->findByActivity($id);
        if ($assigned) {
            $this->addFlash(
                'danger',
                'You cannot remove this activity. Already assigned to schedule!'
            );

            return $this->redirectToRoute('activities');
        }

        $entityManager->remove($activity);
        $entityManager->flush();
        $this->addFlash('success', $activity->getName() . ' has been deleted');

        return $this->redirectToRoute('activities');
    }
}
