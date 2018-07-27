<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\Coach;
use App\Entity\Room;
use App\Entity\Schedule;
use App\Facade\ScheduleFacade;
use App\Form\ScheduleType;
use App\Model\Schedule as ScheduleModel;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends Controller
{
    /**
     * @var ScheduleFacade
     */
    private $scheduleFacade;

    /**
     * ScheduleController constructor.
     * @param ScheduleFacade $scheduleFacade
     */
    public function __construct(ScheduleFacade $scheduleFacade)
    {
        $this->scheduleFacade = $scheduleFacade;
    }

    /**
     * @param ScheduleRepository $scheduleRepository
     * @return Response
     */
    public function index(ScheduleRepository $scheduleRepository): Response
    {
        $schedules = $scheduleRepository->findAll();

        return $this->render('schedule/index.html.twig', ['schedules' => $schedules]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $scheduleModel = new ScheduleModel();
        $entityManager = $this->getDoctrine()->getManager();

        $coachRepository = $entityManager->getRepository(Coach::class);
        $activityRepository = $entityManager->getRepository(Activity::class);
        $roomRepository = $entityManager->getRepository(Room::class);

        $coches = $coachRepository->findAll();
        $activities = $activityRepository->findAll();
        $rooms = $roomRepository->findAll();

        $form = $this->createForm(ScheduleType::class, $scheduleModel, [
            'coaches' => $coches,
            'activities' => $activities,
            'rooms' => $rooms
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->scheduleFacade->createSchedule(
                $data->coach,
                $data->room,
                $data->activity,
                $data->startDate,
                $data->endDate
            );

            $this->addFlash(
                'success',
                'Schedule for ' .
                $data->activity->getName() . ' on ' .
                $data->startDate->format('d.m.Y H:i') . ' has been added'
            );

            return $this->redirectToRoute('schedules');
        }

        return $this->render('schedule/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param ScheduleRepository $scheduleRepository
     * @param int $id
     * @return Response
     */
    public function show(ScheduleRepository $scheduleRepository, int $id): Response
    {
        $schedule = $scheduleRepository->find($id);
        if (!$schedule) {
            $this->addFlash('danger', 'Schedule was not found');

            return $this->redirectToRoute('schedules');
        }

        return $this->render('schedule/show.html.twig', ['schedule' => $schedule]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $scheduleRepository = $this->getDoctrine()->getRepository(Schedule::class);
        $schedule = $scheduleRepository->find($id);

        if (!$schedule) {
            $this->addFlash('danger', 'Schedule was not found');

            return $this->redirectToRoute('schedules');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $coachRepository = $entityManager->getRepository(Coach::class);
        $activityRepository = $entityManager->getRepository(Activity::class);
        $roomRepository = $entityManager->getRepository(Room::class);

        $coches = $coachRepository->findAll();
        $activities = $activityRepository->findAll();
        $rooms = $roomRepository->findAll();

        $scheduleModel = new ScheduleModel();
        $scheduleToUpdate = $scheduleModel->fromSchedule($schedule);
        $form = $this->createForm(ScheduleType::class, $scheduleToUpdate, [
            'coaches' => $coches,
            'activities' => $activities,
            'rooms' => $rooms
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->scheduleFacade->updateSchedule(
                $schedule,
                $data->coach,
                $data->room,
                $data->activity,
                $data->startDate,
                $data->endDate
            );

            $this->addFlash(
                'success',
                'Schedule for ' .
                $data->activity->getName() . ' on ' .
                $data->startDate->format('d.m.Y H:i') . ' has been edited'
            );

            return $this->redirectToRoute('schedules');
        }

        return $this->render('schedule/edit.html.twig', [
            'schedule' => $schedule,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $scheduleRepository = $this->getDoctrine()->getRepository(Schedule::class);
        $schedule = $scheduleRepository->find($id);

        if (!$schedule) {
            $this->addFlash('danger', 'Schedule was not found');

            return $this->redirectToRoute('schedules');
        }

        $this->scheduleFacade->deleteSchedule($schedule);
        $this->addFlash(
            'success',
            'Schedule for ' .
            $schedule->getActivity()->getName() . ' on ' .
            $schedule->getStartDate()->format('d.m.Y H:i') . ' has been deleted'
        );

        return $this->redirectToRoute('schedules');
    }
}
