<?php

namespace App\Controller;

use App\Facade\ParticipantFacade;
use App\Form\ParticipantType;
use App\Model\Setting;
use App\Repository\ScheduleRepository;
use App\Repository\SettingRepository;
use App\Property\SettingProperty as Property;
use App\SendEmail\Exception\ProviderException;
use App\SendEmail\SendEmailContext;
use App\SendEmail\StrategyFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
use App\Entity\Schedule;
use App\Model\Participant as ParticipantModel;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ScheduleController extends AbstractController
{
    /**
     * @var ParticipantFacade $participantFacade
     */
    private $participantFacade;

    /**
     * @var CsrfTokenManagerInterface $csrfTokenManager
     */
    private $csrfTokenManager;

    /**
     * ScheduleController constructor.
     * @param ParticipantFacade $participantFacade
     * @param StrategyFactory $strategyFactory
     * @param CsrfTokenManagerInterface $csrfToken
     */
    public function __construct(ParticipantFacade $participantFacade, StrategyFactory $strategyFactory, CsrfTokenManagerInterface $csrfToken)
    {
        $this->participantFacade = $participantFacade;
        $this->strategyFactory = $strategyFactory;
        $this->csrfTokenManager = $csrfToken;
    }

    /**
     * @var StrategyFactory
     */
    private $strategyFactory;

    /**
     * @param int $id
     * @return Response
     */
    public function showSchedulerActivity(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $scheduleRepository = $entityManager->getRepository(Schedule::class);

        $schedule = $scheduleRepository->find($id);
        if (!$schedule) {
            $this->addFlash('danger', 'Schedule was not found');

            return $this->redirectToRoute('schedules');
        }

        $participantModel = new ParticipantModel();
        $form = $this->createForm(ParticipantType::class, $participantModel);

        $estimatingTime = $schedule->getEndDate()->diff($schedule->getStartDate());

        return $this->render('fitness/activity.html.twig', [
            'schedule' => $schedule,
            'estimating_time' => $estimatingTime,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param SettingRepository $settingRepository
     * @return Response
     * @throws ProviderException
     */
    public function addParticipantIntoSchedulerActivity(int $id, Request $request, SettingRepository $settingRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $scheduleRepository = $entityManager->getRepository(Schedule::class);
        $schedule = $scheduleRepository->find($id);
        $setting = $settingRepository->findOneByName(Setting::SETTING_EMAIL_NOTIFICATION_PROVIDER);
        if (!$setting) {
            $this->addFlash('danger', 'Email notification provider setting was not found');

            return $this->redirectToRoute('settings');
        }
        $participantModel = new ParticipantModel();
        $form = $this->createForm(ParticipantType::class, $participantModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participant = $this->participantFacade->createParticipant(
                $participantModel->firstName,
                $participantModel->lastName,
                $participantModel->email
            );

            $schedule->addParticipant($participant);
            $entityManager->persist($schedule);
            $entityManager->flush();

            $data = [
                'firstName' => $participant->getFirstName(),
                'startDate' => $schedule->getStartDate(),
                'room' => $schedule->getRoom(),
                'coach' => $schedule->getCoach(),
                'activity' => $schedule->getActivity(),
            ];
            Property::set($setting->getName(), $setting->getValue());
            $emailNotificationProvider = Property::get(Setting::SETTING_EMAIL_NOTIFICATION_PROVIDER);

            $strategy = $this->strategyFactory->getStrategy($emailNotificationProvider);
            $emailContext = new SendEmailContext($strategy);

            $message = $emailContext->sendAssignEmail($participant->getEmail(), $data);

            if ($message) {
                $this->addFlash(
                    'success',
                    $participantModel->firstName . ' ' . $participantModel->lastName . ' joined ' .
                    $schedule->getActivity()->getName()
                );
            } else {
                $this->addFlash(
                    'danger',
                    sprintf(
                        'Something went wrong with sending your email, don\'t worry, you are still signed to %s',
                        $schedule->getActivity()
                    )
                );
            }

            return $this->redirectToRoute('schedules');
        }

        $estimatingTime = $schedule->getEndDate()->diff($schedule->getStartDate());

        return $this->render('fitness/activity.html.twig', [
            'schedule' => $schedule,
            'estimating_time' => $estimatingTime,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param SettingRepository $settingRepository
     * @return Response
     * @throws ProviderException
     */
    public function addUserAsParticipantIntoSchedulerActivity(
        int $id,
        Request $request,
        SettingRepository $settingRepository
    ): Response {
        $csrfToken = $request->request->get('_csrf_token');

        if (false === $this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }

        $setting = $settingRepository->findOneByName(Setting::SETTING_EMAIL_NOTIFICATION_PROVIDER);
        if (!$setting) {
            $this->addFlash('danger', 'Email notification provider setting was not found');

            return $this->redirectToRoute('settings');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $scheduleRepository = $entityManager->getRepository(Schedule::class);
        $schedule = $scheduleRepository->find($id);

        $user = $this->getUser();
        $participant = $this->participantFacade->createParticipant(
            $user->getUserName(),
            $user->getUserName(),
            $user->getEmail()
        );

        $schedule->addParticipant($participant);
        $entityManager->persist($schedule);
        $entityManager->flush();

        $data = [
            'firstName' => $participant->getFirstName(),
            'startDate' => $schedule->getStartDate(),
            'room' => $schedule->getRoom(),
            'coach' => $schedule->getCoach(),
            'activity' => $schedule->getActivity(),
        ];
        Property::set($setting->getName(), $setting->getValue());
        $emailNotificationProvider = Property::get(Setting::SETTING_EMAIL_NOTIFICATION_PROVIDER);

        $strategy = $this->strategyFactory->getStrategy($emailNotificationProvider);
        $emailContext = new SendEmailContext($strategy);

        $message = $emailContext->sendAssignEmail($user->getEmail(), $data);

        if ($message) {
            $this->addFlash(
                'success',
                $user->getUserName() . ' joined ' .
                $schedule->getActivity()->getName()
            );
        } else {
            $this->addFlash(
                'danger',
                sprintf(
                    'Something went wrong with sending your email, don\'t worry, you are still signed to %s',
                    $schedule->getActivity()->getName()
                )
            );
        }

        return $this->redirectToRoute('schedules');
    }

    /**
     * @param ScheduleRepository $scheduleRepository
     * @return Response
     */
    public function scheduler(ScheduleRepository $scheduleRepository): Response
    {
        $firstDay = new DateTime();
        $firstDay->modify("first day of this month");

        $lastDay = new DateTime();
        $lastDay->modify('last day of this month');

        $schedules = $scheduleRepository->findByDate($firstDay, $lastDay);

        $now = new DateTime();
        $numberOfDays = $now->format('t');
        $numericFirstDay = $firstDay->format('w');

        return $this->render('fitness/scheduler.html.twig', [
            'schedules' => $schedules,
            'numericFirstDate' => $numericFirstDay,
            'numberOfDays' => $numberOfDays + $numericFirstDay
        ]);
    }

    /**
     * Received date string format d-m-Y according to php date manual
     *
     * @param string $date
     * @param ScheduleRepository $scheduleRepository
     * @return Response
     */
    public function showSchedulesByDate(string $date, ScheduleRepository $scheduleRepository): Response
    {
        $day = DateTime::createFromFormat('d-m-Y', $date);
        $schedules = $scheduleRepository->findAllByDay($day);
        if (!$schedules) {
            $this->addFlash('danger', 'Schedules was not found');

            return $this->redirectToRoute('scheduler');
        }

        return $this->render('fitness/schedulesByDate.html.twig', [
            'schedules' => $schedules
        ]);
    }
}
