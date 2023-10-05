<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CommandeController extends AbstractController
{

    #[Route('/order', name: 'order')]
    public function order(Request $request, CartService $cartService, EntityManagerInterface $manager): Response
    {
        $fullCart = $cartService->getCartWithData();


        foreach ($fullCart as $item) {
            $commande = new Commande();
            $commande->setDate(new \DateTime());
            $commande->setUser($this->getUser());
            $commande->setProduct($item['product']);

            $manager->persist($commande);


        }

        $manager->flush();
        $this->addFlash('success', 'Votre commande a bien été prise en compte. Merci de votre confiance');
        return $this->redirectToRoute("orderList");
    }


    #[Route('/orderList', name: 'orderList')]
    public function orderList(CommandeRepository $repository): Response
    {
        $commandes = $repository->findBy(['user' => $this->getUser()]);
     dump($commandes);

        return $this->render('front/orderList.html.twig', [
          'commandes'=>$commandes,
           'title'=>'Vos commandes passées'
        ]);
    }


}
