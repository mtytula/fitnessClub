<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Facade\RoomFacade;
use App\Form\RoomType;
use App\Model\Room as RoomModel;
use App\Repository\RoomRepository;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{
    /**
     * @var RoomFacade
     */
    private $roomFacade;

    /**
     * RoomController constructor.
     * @param RoomFacade $roomFacade
     */
    public function __construct(RoomFacade $roomFacade)
    {
        $this->roomFacade = $roomFacade;
    }

    /**
     *
     * @param RoomRepository $roomRepository
     * @return Response
     */
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();

        return $this->render('room/index.html.twig', ['rooms' => $rooms]);
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $roomModel = new RoomModel();

        $form = $this->createForm(RoomType::class, $roomModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->roomFacade->createRoom(
                $data->name,
                $data->capacity
            );

            $this->addFlash('success', $data->name . ' has been added');

            return $this->redirectToRoute('rooms');
        }

        return $this->render('room/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @param RoomRepository $roomRepository
     * @param int $id
     * @return Response
     */
    public function show(RoomRepository $roomRepository, int $id): Response
    {
        $room = $roomRepository->find($id);
        if (!$room) {
            $this->addFlash('danger', 'Room was not found');

            return $this->redirectToRoute('rooms');
        }

        return $this->render('room/show.html.twig', ['room' => $room]);
    }

    /**
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $roomRepository = $this->getDoctrine()->getRepository(Room::class);
        $room = $roomRepository->find($id);
        if (!$room) {
            $this->addFlash('danger', 'Room was not found');

            return $this->redirectToRoute('rooms');
        }
        $roomModel = new RoomModel();
        $roomToUpdate = $roomModel->fromRoom($room);
        $form = $this->createForm(RoomType::class, $roomToUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->roomFacade->updateRoom(
                $room,
                $data->name,
                $data->capacity
            );

            $this->addFlash('success', $data->name . ' has been edited');

            return $this->redirectToRoute('rooms');
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @param int $id
     * @param ScheduleRepository $scheduleRepository
     * @return Response
     */
    public function delete(int $id, ScheduleRepository $scheduleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $roomRepository = $this->getDoctrine()->getRepository(Room::class);
        $room = $roomRepository->find($id);
        if (!$room) {
            $this->addFlash('danger', 'Room was not found');

            return $this->redirectToRoute('rooms');
        }

        $assigned = $scheduleRepository->findByRoom($id);
        if ($assigned) {
            $this->addFlash(
                'danger',
                'You cannot remove this room. Already assigned to schedule!'
            );

            return $this->redirectToRoute('rooms');
        }

        $this->roomFacade->deleteRoom($room);
        $this->addFlash('success', $room->getName() . " has been deleted");

        return $this->redirectToRoute('rooms');
    }
}
