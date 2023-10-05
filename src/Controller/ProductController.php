<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/product')]
#[IsGranted('ROLE_ADMIN')]
class ProductController extends AbstractController
{


    #[Route('/create', name: 'create_product')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $product=new Product();

        $form=$this->createForm(ProductType::class, $product, ['create'=>true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {


            // on récupère les données de l'input type file
            $picture=$form->get('picture_src')->getData();
            //dd($product);

            // on renomme le fichier photo en le concaténant avec la date complète d'ajout
              $picture_src=date('YmdHis').'-'.$picture->getClientOriginalName();

              // on réaffecte à l'objet product le nouveau nom du src

            $product->setDisponibility(false);
            $product->setPictureSrc($picture_src);

            // on upload dans l'application le fichier grace à la méthode move() qui attend en paramètre:
            //1=> l'emplacement d'upload definit dans le services.yaml .
            //2=> le nom du fichier à y créer
            $picture->move($this->getParameter('upload'), $picture_src);


            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'opération réalisée avec succès');

            return $this->redirectToRoute('read_product');

        }



        return $this->render('product/create.html.twig', [
            'title' => 'Ajout d\'une oeuvre',
            'form'=>$form->createView()
        ]);
    }



    #[Route('/update/{id}', name: 'update_product')]
    public function update_product(Product $product,Request $request,EntityManagerInterface $manager): Response
    {


        $form=$this->createForm(ProductType::class, $product, ['update'=>true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            // on récupère les données de l'input type file
            $picture=$form->get('update_picture_src')->getData();
            //dd($product);

            if ($picture){

                // on renomme le fichier photo en le concaténant avec la date complète d'ajout
                $picture_src=date('YmdHis').'-'.$picture->getClientOriginalName();

                // on supprime le précédent fichier photo avec la méthode unlink qui attend en paramètre le chemin complet
                // vers le fichier avec nom du fichier et extensions inclus

                unlink($this->getParameter('upload').'/'.$product->getPictureSrc());


                // on réaffecte à l'objet product le nouveau nom du src

                $product->setPictureSrc($picture_src);

                // on upload dans l'application le fichier grace à la méthode move() qui attend en paramètre:
                //1=> l'emplacement d'upload definit dans le services.yaml .
                //2=> le nom du fichier à y créer
                $picture->move($this->getParameter('upload'), $picture_src);



            }



            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'opération réalisée avec succès');

            return $this->redirectToRoute('read_product');

        }



        return $this->render('product/update.html.twig', [
            'title' => 'Modification de la fiche oeuvre',
            'form'=>$form->createView()
        ]);
    }


    #[Route('/delete/{id}', name: 'delete_product')]
    public function delete_product(Product $product,EntityManagerInterface $manager): Response
    {

        unlink($this->getParameter('upload').'/'.$product->getPictureSrc());
        $manager->remove($product);
        $manager->flush();

        $this->addFlash('success', 'opération réalisée avec succès');

        return $this->redirectToRoute('read_product');


    }


    #[Route('/read', name: 'read_product')]
    public function read_product(ProductRepository $repository): Response
    {
        $products=$repository->findAll();


        return $this->render('product/index.html.twig', [
            'title' => 'Gestion des oeuvres',
            'products'=>$products
        ]);
    }



}
