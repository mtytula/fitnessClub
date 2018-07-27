<?php

namespace App\Controller\Admin;

use App\FileUpload\FileUploadContext;
use App\FileUpload\StrategyFactory;
use App\Entity\Coach;
use App\Form\CoachType;
use App\Model\Setting;
use App\Property\SettingProperty as Property;
use App\Repository\CoachRepository;
use App\Model\Coach as CoachModel;
use App\Facade\CoachFacade;
use App\Repository\ScheduleRepository;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FileUpload\Exception\ProviderException;

class CoachController extends AbstractController
{
    /**
     * @var CoachFacade $coachFacade
     */
    private $coachFacade;

    /**
     * @var StrategyFactory $strategyFactory
     */
    private $strategyFactory;

    /**
     * CoachController constructor.
     * @param CoachFacade $coachFacade
     * @param StrategyFactory $strategyFactory
     */
    public function __construct(CoachFacade $coachFacade, StrategyFactory $strategyFactory)
    {
        $this->strategyFactory = $strategyFactory;
        $this->coachFacade = $coachFacade;
    }

    /**
     * @param CoachRepository $coachRepository
     * @return Response
     */
    public function index(CoachRepository $coachRepository): Response
    {
        return $this->render("coach/index.html.twig", [
            'coaches' => $coachRepository->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @param SettingRepository $settingRepository
     * @return Response
     * @throws ProviderException
     */
    public function new(Request $request, SettingRepository $settingRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $coachModel = new CoachModel();
        $setting = $settingRepository->findOneByName(Setting::SETTING_UPLOAD_PROVIDER);
        if (!$setting) {
            $this->addFlash('danger', 'Upload setting was not found');

            return $this->redirectToRoute('settings');
        }

        $form = $this->createForm(CoachType::Class, $coachModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            Property::set($setting->getName(), $setting->getValue());
            $uploadProvider = Property::get(Setting::SETTING_UPLOAD_PROVIDER);

            /** @var UploadedFile $file */
            $file = $coachModel->picture;
            $strategy = $this->strategyFactory->getStrategy($uploadProvider);
            $uploadFile = new FileUploadContext($strategy);
            $fileName = $uploadFile->upload($file);
            $this->coachFacade->createCoach(
                $coachModel->firstName,
                $coachModel->lastName,
                $fileName,
                $coachModel->description
            );

            $this->addFlash('success', $coachModel->firstName . ' ' . $coachModel->lastName . ' has been added');

            return $this->redirectToRoute('coaches');
        }

        return $this->render('coach/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $id
     * @param CoachRepository $coachRepository
     * @param SettingRepository $settingRepository
     * @param Request $request
     * @return Response
     * @throws ProviderException
     */
    public function update(
        int $id,
        CoachRepository $coachRepository,
        SettingRepository $settingRepository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $coach = $coachRepository->find($id);
        if (!$coach) {
            $this->addFlash('danger', 'Coach was not found');

            return $this->redirectToRoute('coaches');
        }
        $coachModel = new CoachModel();
        $oldFile = $coachModel->fromCoach($coach);
        $updateCoach = $coachModel->fromCoach($coach);
        $setting = $settingRepository->findOneByName(Setting::SETTING_UPLOAD_PROVIDER);
        if (!$setting) {
            $this->addFlash('danger', 'Upload provider setting was not found');

            return $this->redirectToRoute('settings');
        }
        $form = $this->createForm(CoachType::class, $updateCoach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            Property::set($setting->getName(), $setting->getValue());
            $uploadProvider = Property::get(Setting::SETTING_UPLOAD_PROVIDER);

            /** @var UploadedFile $file */
            $file = $updateCoach->picture;
            $strategy = $this->strategyFactory->getStrategy($uploadProvider);
            $updateFile = new FileUploadContext($strategy);
            $fileName = $updateFile->upload($file);

            $this->coachFacade->updateCoach(
                $coach,
                $updateCoach->firstName,
                $updateCoach->lastName,
                $fileName,
                $updateCoach->description
            );
            $updateFile->delete($oldFile->picture);
            $this->addFlash('success', $updateCoach->firstName . ' ' . $updateCoach->lastName . ' has been edited');

            return $this->redirectToRoute('coaches');
        }

        return $this->render('coach/edit.html.twig', [
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

        $coachRepository = $entityManager->getRepository(Coach::class);
        $coach = $coachRepository->find($id);
        if (!$coach) {
            $this->addFlash('danger', 'Coach was not found');

            return $this->redirectToRoute('coaches');
        }

        return $this->render('coach/show.html.twig', [
            'coach' => $coach
        ]);
    }

    /**
     * @param int $id
     * @param CoachRepository $coachRepository
     * @param SettingRepository $settingRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ProviderException
     */
    public function delete(
        int $id,
        CoachRepository $coachRepository,
        SettingRepository $settingRepository,
        EntityManagerInterface $entityManager,
        ScheduleRepository $scheduleRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $coach = $coachRepository->find($id);
        if (!$coach) {
            $this->addFlash('danger', 'Coach was not found');

            return $this->redirectToRoute('coaches');
        }

        $assigned = $scheduleRepository->findByCoach($id);
        if ($assigned) {
            $this->addFlash(
                'danger',
                'You cannot remove this coach. Already assigned to schedule!'
            );

            return $this->redirectToRoute('coaches');
        }

        $coachModel = new CoachModel();
        $oldFile = $coachModel->fromCoach($coach);
        $setting = $settingRepository->findOneByName(Setting::SETTING_UPLOAD_PROVIDER);
        if (!$setting) {
            $this->addFlash('danger', 'Upload setting was not found');

            return $this->redirectToRoute('settings');
        }
        Property::set($setting->getName(), $setting->getValue());
        $uploadProvider = Property::get(Setting::SETTING_UPLOAD_PROVIDER);
        $strategy = $this->strategyFactory->getStrategy($uploadProvider);
        $deleteFile = new FileUploadContext($strategy);
        $deleteFile->delete($oldFile->picture);

        $entityManager->remove($coach);
        $entityManager->flush();
        $this->addFlash('success', $coach->getFirstName() . ' ' . $coach->getLastName() . " has been deleted");

        return $this->redirectToRoute('coaches');
    }
}
