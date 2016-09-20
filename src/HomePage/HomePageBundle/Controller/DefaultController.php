<?php

namespace HomePage\HomePageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//dependance du projet
use HomePage\HomePageBundle\Entity\articles;
use HomePage\HomePageBundle\Form\articlesType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('HomePageHomePageBundle:Default:index1.html.twig');
    }

    // CREATION
    //-------------
    public function createAction(Request $request)
    {
        $article = new Articles();

        // creation formulaire
        $form = $this->createForm(articlesType::class,$article);

        // soumission du formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis enregistrement des donnees
        if ($form->isSubmitted() && $form->isValid())
        {
          $em = $this->getDoctrine()->getManager(); //connexion bdd
          $name     = $form["nom"]->getData();
          $username = $form["prenom"]->getData();
          $type     = $form["type"]->getData();
          $article->setNom($name);
          $article->setPrenom($username);
          $article->setType($type);
          $em->persist($article);
          $em->flush();

          $request->getSession()->getFlashBag()->add('success', "Page enregistree");
        }

        //creation de la vue du formulaire
        $form = $form->createView();

        return $this->render('HomePageHomePageBundle:Default:index.html.twig',array(
          "form" => $form));
    }

    // LECTURE
    //-------------
    public function readAction(Request $request)
    {
        // recuperer les article de la table
        $listearticle = $this->getDoctrine()->getManager()->getRepository('HomePageHomePageBundle:articles')->findAll();
        if ($listearticle)
        {
          return $this->render('HomePageHomePageBundle:Default:liste.html.twig',array(
            "liste" => $listearticle));
        }
    }


    // MODIFICATION
    //-------------
    public function updateAction($id,Request $request)
    {
      $em = $this->getDoctrine()->getManager(); //connexion bdd

      // On récupère l'article $id
      $article = $em->getRepository('HomePageHomePageBundle:articles')->find($id);

      // creation formulaire
      $form = $this->createForm(articlesType::class, $article);

      // soumission du formulaire
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid())
      {
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', "article modifiée");
      }

      //creation de la vue du formulaire
      $form = $form->createView();

      return $this->render('HomePageHomePageBundle:Default:index.html.twig',array(
        "form" => $form));

    }

    // SUPPRESSION
    //-------------
    public function deleteAction($id,Request $request)
    {
      $em = $this->getDoctrine()->getManager(); //connexion bdd

      // On récupère l'article $id
      $article = $em->getRepository('HomePageHomePageBundle:articles')->find($id);

      return $this->render('HomePageHomePageBundle:Default:delete.html.twig',array(
      'article' => $article));

    }


    // SUPPRESSION DE CONFIRMATION
    //----------------------------
    public function confirmAction($id,Request $request)
    {
      $em = $this->getDoctrine()->getManager(); //connexion bdd

      // On récupère l'article $id
      $article = $em->getRepository('HomePageHomePageBundle:articles')->find($id);

      $em->remove($article);
      $em->flush();
      $request->getSession()->getFlashBag()->add('success', "article supprimé");

      return $this->redirect($this->generateUrl('article_read'));
    }




}
