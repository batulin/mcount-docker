<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\Place;
use App\Form\RentType;
use App\Service\RentService;
use App\Service\PlaceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

#[Route('/rent')]
class RentController extends AbstractController
{
    public function __construct(private RentService $rentService, public PlaceService $placeService)
    {
    }
    #[Route('/', name: 'app_rent_index', methods: ['GET'])]
    public function index(): Response
    {
        $rents = $this->rentService->allRents();

        return $this->render('rent/index.html.twig', [
            'rents' => $rents,
        ]);
    }

    #[Route('/new/{place_id}/{date}', name: 'app_rent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $place_id, string $date): Response
    {
        $rent = (new Rent())
        ->setBeginDate(new DateTimeImmutable($date))
        ->addPlace($this->placeService->placeById($place_id));
        $form = $this->createForm(RentType::class, $rent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->rentService->createRent($rent);

            return $this->redirectToRoute('app_rent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rent/new.html.twig', [
            'rent' => $rent,
            'form' => $form,
        ]);
    }
//
//    #[Route('/new', name: 'app_rent_new', methods: ['GET', 'POST'])]
//    public function new(Request $request): Response
//    {
//        $rent = new Rent();
//        $form = $this->createForm(RentType::class, $rent);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $rentRepository->save($rent, true);
//
//            return $this->redirectToRoute('app_rent_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('rent/new.html.twig', [
//            'rent' => $rent,
//            'form' => $form,
//        ]);
//    }
    #[Route('/calendar/{month}', name: 'app_calendar', methods: ['GET'])]
    public function calendar($month): Response
    {
        $dateInfo = $this->rentService->getDateInfo($month);
        $calendar = $this->rentService->placesWithRents($dateInfo);


        return $this->render('rent/calendar.html.twig', [
            'calendar' => $calendar,
            'period' => $dateInfo['period'],
            'prevMonth' => $dateInfo['prevMonth'],
            'nextMonth' => $dateInfo['nextMonth'],
        ]);
    }


    #[Route('/{id}', name: 'app_rent_show', methods: ['GET'])]
    public function show(Rent $rent): Response
    {
        return $this->render('rent/show.html.twig', [
            'rent' => $rent,
        ]);
    }


//    #[Route('/new/{place_id}/{current_date}', name: 'app_rent_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, string $place_id, string $current_date): Response
//    {
//        $place = $this->rentService->placeById($place_id);
//        $rent = (new Rent())
//        ->addPlace($place)
//        ->setBeginDate(new \DateTimeImmutable($current_date . '00:00:00'));
//        $form = $this->createForm(RentType::class, $rent);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $rents = $this->rentService->createRent($rent);
//            return $this->render('rent/twst.html.twig', [
//                'rents' => $rents
//            ]);
//
////            return $this->redirectToRoute('app_rent_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('rent/new.html.twig', [
//            'rent' => $rent,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}/edit', name: 'app_rent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rent $rent, RentRepository $rentRepository): Response
    {
        $form = $this->createForm(RentType::class, $rent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rentRepository->save($rent, true);

            return $this->redirectToRoute('app_rent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rent/edit.html.twig', [
            'rent' => $rent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rent_delete', methods: ['POST'])]
    public function delete(Request $request, Rent $rent, RentRepository $rentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rent->getId(), $request->request->get('_token'))) {
            $rentRepository->remove($rent, true);
        }

        return $this->redirectToRoute('app_rent_index', [], Response::HTTP_SEE_OTHER);
    }

}
