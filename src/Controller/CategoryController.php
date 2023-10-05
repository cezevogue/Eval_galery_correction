<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/category')]
#[IsGranted('ROLE_ADMIN')]
class CategoryController extends AbstractController
{
    // Méthode de gestion des catégories
    // ajout, lecture et modification
    // nous avons un formulaire à afficher il nous faut donc la classe Request de HttpFoundation afin de récupérer les données provenant de nos superglobale (ici $_POST), L' EntityManagerInterface afin de communiquer avec la bdd pour l'insert ou la modification et enfin le repository de category pour aller effectuer les requetes de select en BDD (récupération de données)
    #[Route('/', name: 'app_category')]
    #[Route('/update/{id}', name: 'updateCategory')]
    public function index(Request $request, EntityManagerInterface $manager, CategoryRepository $repository, $id=null): Response
    {
        // si $id==null alors nous sur la route de création
        if (!$id){

            // pour la création: nouvel objet Category
            $category=new Category();

        }else{

            $category=$repository->find($id);
        }


        // on va récupérer toutes les catégorie en bdd (equivalence de select * from category)
        $categories=$repository->findAll();

        dump($categories);
        dump($category);

        // création du formulaire: 1er argument le type(le formulaire), 2nd l'objet en liens avec ce formulaire
        // ceci afin d'effectuer les contrôles de concordance entre les champs demandés dans le type et les propriétés existantes dans l'entité
        $form=$this->createForm(CategoryType::class, $category);


        // on charge l'objet grâce aux données de formulaire avec la méthode handleRequest

        $form->handleRequest($request);

        // condition de soumission de formulaire
        if ($form->isSubmitted() && $form->isValid())
        {
            // on appelle le manager pour préparer la requête puis pour l'executer
            $manager->persist($category);
            $manager->flush();

            // message en session
            $this->addFlash('success', 'Opération réalisée avec succès');

            // redirection sur la même route de gestion
           return  $this->redirectToRoute('app_category');


        }


        // on renvoie les categories déjà créées
        // renvoie la vue du formulaire dans une variable 'form' utilisable dans le twig
        return $this->render('category/index.html.twig', [
            'title' => 'Gestion catégories',
            'form'=>$form->createView(),
            'categories'=>$categories
        ]);
    }


    // méthode pour la suppression en BDD.
    // on doit récupérer la catégorie en question dont l'id transite en paramètre de l'url ainsi grace à l'entité Catégory
    // symfony voyant un id en parametre et une entité en dépendance va remplir automatiquement l'objet de l'entité sans passer par la méthode find($id) du repository
    // l'objectif de cette méthode étant d'altérer les enregistrements en BDD on injecte l' EntityManagerInterface
       #[Route('/delete/{id}', name: 'categoryDelete')]
          public function delete(Category $category, EntityManagerInterface $manager): Response
          {
              // methode de  suppression du manager
              $manager->remove($category);
              $manager->flush();

              $this->addFlash('success', 'opération réalisée avec succès');

              return $this->redirectToRoute('app_category');
          }



}
