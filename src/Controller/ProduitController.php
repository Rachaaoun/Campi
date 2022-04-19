<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\SearchForm;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_produit_index_front", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository,Request $request): Response
    {
        $data = new SearchData();
            $form = $this->createForm(SearchForm::class, $data);
    
            $form->handleRequest($request);
    
            $produits=$produitRepository ->findSearch($data);
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form'=>$form->createView()
        ]);
    }

      /**
     * @Route("/list", name="app_produit_index", methods={"GET"})
     */
    public function indexBack(ProduitRepository $produitRepository,Request $request): Response
    {
        $data = new SearchData();
            $form = $this->createForm(SearchForm::class, $data);
    
            $form->handleRequest($request);
    
            $produits=$produitRepository ->findSearch($data);
        return $this->render('produit/indexBack.html.twig', [
            'produits' => $produits,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProduitRepository $produitRepository, \Swift_Mailer $mailer): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                    //send email
                    $message = (new \Swift_Message('Hello Email')) //subject
                    ->setFrom('racha.aoun@esprit.tn')
                    ->setTo('racha.aoun@hotmail.com')
                    ->setBody("The product has been added."
                    ) ;
    
            $mailer->send($message);
            $ImageFile = $form->get('image')->getData();
            // so the PDF file must be processed only when a file is uploaded
            if ($ImageFile) {
              
          $fileName=md5(uniqid()).'.'.$ImageFile->guessExtension();
          try{
              $ImageFile->move($this->getParameter('brochures_directory'),$fileName);
          }catch(FileException $e){

          }
              // Move the file to the directory where brochures are stored
              
              // updates the 'brochureFilename' property to store the PDF file name
              // instead of its contents
              $produit->setImage($fileName);
          }
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ImageFile = $form->get('image')->getData();
            // so the PDF file must be processed only when a file is uploaded
            if ($ImageFile) {
              
          $fileName=md5(uniqid()).'.'.$ImageFile->guessExtension();
          try{
              $ImageFile->move($this->getParameter('brochures_directory'),$fileName);
          }catch(FileException $e){

          }
              // Move the file to the directory where brochures are stored
              
              // updates the 'brochureFilename' property to store the PDF file name
              // instead of its contents
              $produit->setImage($fileName);
          }
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
