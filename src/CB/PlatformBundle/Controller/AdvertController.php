<?php

namespace CB\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use CB\PlatformBundle\Entity\Advert;

class AdvertController extends Controller
{
    public function indexAction($page)
    {  
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
    if ($page < 1) {
        // On déclenche une exception NotFoundHttpException, cela va afficher
        // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
        throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    // Ici, on récupérera la liste des annonces, puis on la passera au template

    // Notre liste d'annonce en dur
    $listAdverts = array(
    array(
    'title'   => 'Un bon site pour apprendre les ouvertures',
    'id'      => 1,
    'author'  => 'Aghiles',
    'content' => 'Maitriser quelques ouverutres est indispensables',
    'date'    => new \Datetime()),
    array(
    'title'   => 'Un lien pour maitriser les tactiques',
    'id'      => 2,
    'author'  => 'Aghiles',
    'content' => 'Avoir une bonne notion de tactiques est necéssaire',
    'date'    => new \Datetime()),
    array(
    'title'   => 'La stratégie est l\'ame du jeu',
    'id'      => 3,
    'author'  => 'Aghiles',
    'content' => 'C\'est grace à la stratégie qu\'on peut gagner un avantage',
    'date'    => new \Datetime())
      );
  
      // Et modifiez le 2nd argument pour injecter notre liste
      return $this->render('CBPlatformBundle:Advert:index.html.twig', array(
        'listAdverts' => $listAdverts
      ));
    
    }

    public function addAction(Request $request)
    {
      //Création de l'entité
      $advert = new Advert();
      $advert->setTitle('Rechérche un développeur Symfony');
      $advert->setAuthor('Aghiles');
      $advert->setContent('Nous recherchons un développeur Symfony afin de consolider notre code');

      //On récupère l'EntityManager
      $em = $this->getDoctrine()->getManager();

      //Etape1:  on persiste l'entité
      $em->persist($advert);

      //Etape2: on flush tout ce qui a été persisté avant
      $em->flush();
      
      // La gestion d'un formulaire est particulière, mais l'idée est la suivante :

      // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
      if ($request->isMethod('POST')) {
          // Ici, on s'occupera de la création et de la gestion du formulaire
          $request->getSession()->getFlashBag()->add('notice', 'Lien bien enregistrée.');

          // Puis on redirige vers la page de visualisation de cettte annonce
          return $this->redirectToRoute('cb_platform_view', array('id' => $advert->getId()));
      }
      //Si on est pas en POST, alors on affiche le formulaire
      return $this->render('CBPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
    }

    public function menuAction($limit)
    {
      // On fixe en dur une liste ici, bien entendu par la suite
      // on la récupérera depuis la BDD !
      $listAdverts = array(
        array('id' => 2, 'title' => 'Apprendre les tactics de base'),
        array('id' => 5, 'title' => 'Stratégie de base'),
        array('id' => 9, 'title' => 'Quelques ouvertures pour bien débuter')
      );
  
      return $this->render('CBPlatformBundle:Advert:menu.html.twig', array(
        // Tout l'intérêt est ici : le contrôleur passe
        // les variables nécessaires au template !
        'listAdverts' => $listAdverts
      ));
    }

    public function viewAction($id)
    {
      //On récupére le repository
      $repository = $this->getDoctrine()
      ->getManager()
      ->getRepository('CBPlatformBundle:Advert');

      //On récupére l'entité correspondant à id
      $advert = $repository->find($id);

      // $advert est un instance de CB\PlatformBundle\Advert
      // Ou null si l'id n'existe pas
      if (null === $advert){
        throw new Exception("L'annonce d'id ".$id."n'existe pas !");
      }

      //Le render ne change pas, avant on passait un tableau, maintenant un objet
      return $this->render('CBPlatformBundle:Advert:view.html.twig',array(
        'advert' => $advert
      ));
    }
}
