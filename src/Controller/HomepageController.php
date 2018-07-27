<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Coach;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{
    /**
     * @return Response
     */
    public function homepage(): Response
    {
        $activityRepository = $this
            ->getDoctrine()
            ->getRepository(Activity::class);
        $coachRepository = $this
            ->getDoctrine()
            ->getRepository(Coach::class);

        $activities = $activityRepository->findAll();
        $coaches = $coachRepository->findBy([], [], 3);

        return $this->render('homepage/homepage.html.twig', [
            'activities' => $activities,
            'coaches' => $coaches
        ]);
    }
}
