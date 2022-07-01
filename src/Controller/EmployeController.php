<?php

namespace App\Controller;



use App\Entity\Employe;
use App\Form\EmployeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/*
*VOUS DEVEZ IMPORTER TOUTES LES CLASS QUE VOUS UTILISEZ
*/ 

class EmployeController extends AbstractController
{

  /**
   * Une fonction d'un controller s'appelera une action
   * Le nom de cette action (cette fonction) commencera TOUJOURS par un verbe
   * on privilegie langlais à defaut, on nomme correctement ses variables en francais
   * 
   * 
   * La route = 1param: l'uri, 2param: le nom de la route, 3param : la methode HTTP
   * 
    * @Route("/ajouter-un-employe.html", name="employe_create", methods={"GET|POST"})
    */   
  public function create(Request $request, EntityManagerInterface $entityManager): Response
{

//////////////////////---------------1ERE Partie : GET--------------------------------////////////////////////////////////

      # Variabilisation d'un nouvel objet de type Employe
      $employe = new Employe();

      #on cree dans une variable un formulaire à partir de notre prototypaEmployeFormType
      #Pour faire fonctionner le mécanisme d'auto hydratation d'objet de symfony, vous devrez passer en 2 eme argument votre objet $employe
      # Mais egalement que tous les noms de vos champs dans le prototype de form (EmployFormType) aient EXACTEMENT les mêmes noms que les propriétés de la Class à laquelle il est rattaché. 
      $form = $this->createForm(EmployeFormType::class, $employe);

      #
      $form->handleRequest($request);

//////////////////////---------------2EME Partie : POST--------------------------------////////////////////////////////////      

      if($form->isSubmitted() && $form->isValid()){

          # Cette méthode pour récuperer les données des inputs est la premiere methode.
          # Nous utiliserons la seconde grace au mécanisme d'auto hydratation de Symfony
          //$form->get('salary')->getData();
          $entityManager->persist($employe);
          $entityManager->flush();

          return $this->redirectToRoute('default_home');
      }
//////////////////////---------------3EME Partie : GET--------------------------------////////////////////////////////////


  # On passe en parametre le formulaire à notre vue Twig
      return $this->render("form/employe.html.twig", [
          "form_employe" =>$form->createView() #On doit createView() sur $form
          ]);

  } # end function  create()


  /** 
  * @Route("/modifier-un-mploye-{id}", name="employe_update", methods={"GET|POST"})
  */ 

  public function update(Employe $employe, Request $request, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(EmployeFormType::class, $employe)
        ->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($employe);
        $entityManager->flush();

        return $this->redirectToRoute('default_home');
    } //end if

    return $this->render("form/employe.html.twig", [
      'employe' => $employe,
      'form_employe' => $form->createView() 
      ]); 
} //end function update


 /** 
  * @Route("/supprimer-un-employe-{id}", name="employe_delete", methods={"GET"})
  */ 

public function delete(Employe $employe, EntityManagerInterface $entityManager): RedirectResponse

{
  $entityManager->remove($employe);
  $entityManager->flush();

  return $this->redirectToRoute('default_home');

}# end funcion delete()

}# end class
