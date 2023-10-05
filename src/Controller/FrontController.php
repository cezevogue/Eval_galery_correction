<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FrontController extends AbstractController
{
    // 1er controller pour l'accueil et les pages utilisateur
    // avant cela pour initialisé mon projet j'ai réalisé la commande suivante:
    // composer create-project symfony/skeleton:"6.3.*" art_galery
    // puis:
    // cd art_galery
    //puis:
    // composer require webapp (pour installer les dépendances web. ex le moteur de template twig etc...)
    // ensuite  j'ai créé mon controller avec la commande:
    // php bin/console make:controller front
    // j'ai ensuite configuré mon .env pour la connexion à la bdd
    // et exécuté la commande:
    //  php bin/console doctrine:database:create
    // ensuite j'ai configuré mon base.html.twig de templates pour avoir bootstrap ainsi qu'une barre de navigation.
    // J'ai de même configuré la constante pour le dossier d'upload des images dans le services.yaml dans config avec:
    //  upload: '%kernel.project_dir%/public/upload' , sous parameters
    // a présent il mez faut configuré le twig.yaml dans config/packages pour que les formulaires de symfony utilise bootstrap 5.3
    // form_themes: ['bootstrap_5_layout.html.twig']


    #[Route('/', name: 'home')]
    public function index(ProductRepository $repository, CartService $cartService): Response
    {
      $products=$repository->findBy(['disponibility'=>false]);

//      $cartService->destroy();
//      dd();
        $cart = $cartService->getCartWithData();
        return $this->render('front/index.html.twig', [
            'title' => 'Accueil',
            'products'=>$products,
            'cart' => $cart
        ]);
    }


    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function cart_add(CartService $cartService, $id): Response
    {
        $cartService->add($id);
        $this->addFlash('info', 'Ajouté au panier');
        return $this->redirectToRoute('home');
    }

    #[Route('/cart/remove/{id}/{target}', name: 'cart_remove')]
    public function cart_remove(CartService $cartService, $id,$target): Response
    {
        $cartService->remove($id);
        $this->addFlash('info', 'Retiré du panier');
        // $target sert à gérer la redirection

        if ($target=='home')
        {
            return $this->redirectToRoute('home');

        }else
        {

            return $this->redirectToRoute('fullCart');
        }


    }

    #[Route('/fullCart', name: 'fullCart')]
    public function fullCart(CartService $cartService): Response
    {
        $cart = $cartService->getCartWithData();


        return $this->render('front/cart.html.twig', [
            'cart' => $cart,
            'title'=>'Votre panier'
        ]);
    }




}
