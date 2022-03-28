<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="app_room_index", methods={"GET"})
     */
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_room_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RoomRepository $roomRepository): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['pict']->getData();
            if($uploadedFile == null) {
                return $this->redirectToRoute('app_room_new');
            }
            else{
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = urlencode($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $room->setPict($newFilename);
                $roomRepository->add($room);
                return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->renderForm('room/new.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_room_show", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_room_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Room $room, RoomRepository $roomRepository): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form['pict']->getData() != null){
                $uploadedFile = $form['pict']->getData();
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = urlencode($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );

                $room->setPict($newFilename);
            }
            $roomRepository->add($room);
            return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('room/edit.html.twig', [
            'room' => $room,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_room_delete", methods={"POST"})
     */
    public function delete(Request $request, Room $room, RoomRepository $roomRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $roomRepository->remove($room);
        }

        return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
    }
}
