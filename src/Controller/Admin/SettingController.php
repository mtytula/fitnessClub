<?php

namespace App\Controller\Admin;

use App\Form\SettingType;
use App\Model\Setting as SettingModel;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    /**
     * @param SettingRepository $settingRepository
     * @return Response
     */
    public function index(SettingRepository $settingRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        return $this->render('setting/index.html.twig', [
            'settings' => $settingRepository->findAll()
        ]);
    }

    /**
     * @param int $id
     * @param SettingRepository $settingRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function update(
        int $id,
        SettingRepository $settingRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $setting = $settingRepository->find($id);
        if (!$setting) {
            $this->addFlash('danger', 'Setting not found');

            return $this->redirectToRoute('settings');
        }

        $settingModel = new SettingModel();
        $updateSetting = $settingModel->fromSetting($setting);
        $form = $this->createForm(SettingType::class, $updateSetting, [
            'options' => $setting->getOptions()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $setting->setValue($updateSetting->value);
            $entityManager->persist($setting);
            $entityManager->flush();

            $this->addFlash('success', $setting->getName(). ' updated!');

            return $this->redirectToRoute('settings');
        }

        return $this->render('setting/edit.html.twig', [
            'form' => $form->createView(),
            'setting' => $setting
        ]);
    }
}
