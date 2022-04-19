<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Form\ValidationReservationType;
use App\Repository\ProduitRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client; 


/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="app_reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_reservation_ajout", methods={"GET", "POST"})
     */
    public function new(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sid    = "AC20e305fbc4dc302ea1ff7cf6b70f58e7"; 
            $token  = "a34e111effe5e7b881c4396a7fa009c8"; 
            $twilio = new Client($sid, $token); 
            
            $message = $twilio->messages 
                            ->create("+21625026491", // to 
                                    array(  
                                        "messagingServiceSid" => "MG7a040c40201f5442796ba4c553fd05ea",      
                                        "body" => "test2" 
                                    ) 
                            ); 
            
            print($message->sid);


            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/new/{id}", name="app_reservation_new", methods={"GET", "POST"})
     */
    public function newReservation(Request $request,$id, ReservationRepository $reservationRepository,ProduitRepository $produitRepository): Response
    {
        $produit =$produitRepository->findOneById($id);
        $reservation = new Reservation();
        $form = $this->createForm(ValidationReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->addProduit($produit);

            $sid    = "AC20e305fbc4dc302ea1ff7cf6b70f58e7"; 
            $token  = "a34e111effe5e7b881c4396a7fa009c8"; 
            $twilio = new Client($sid, $token); 
            
            $message = $twilio->messages 
                            ->create("+21625026491", // to 
                                    array(  
                                        "messagingServiceSid" => "MG7a040c40201f5442796ba4c553fd05ea",      
                                        "body" => "test2" 
                                    ) 
                            ); 
            
            print($message->sid);

            $reservation->setDateDeReservation(new \DateTime());

            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/newFront.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

 
    
    /**
     * @Route("/{id}", name="app_reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
