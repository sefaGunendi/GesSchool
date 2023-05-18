<?php

namespace App\Controller;

use App\Entity\Ecole;
use App\Entity\Classe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClasseController extends AbstractController
{
    // pour récuprer les classes d'une école
    public function index(Ecole $ecole): Response
    {
        $classes = $ecole->getClasses();

        return $this->render('ecole/index.html.twig', [
            'ecole' => $ecole,
            'classes' => $classes,
        ]);
    }

    //pour récuprer les info d'une classe secltionné    
    #[Route("/classe/{id}", name:"app_classe_show")]
    public function show(Classe $classe): Response
    {
        return $this->render('classe/show_classe.html.twig', [
            'classe' => $classe,
        ]);
    }


        // fonction pour modifier les information d'une classe
        #[Route('/classe/{id}/edit', name: 'app_classe_edit')]
        public function edit(Request $request, Classe $classe, ManagerRegistry $doctrine): Response
        {
            $form = $this->createFormBuilder($classe)
                ->add('nom_classe', TextType::class, [
                    'label' => 'Nom de la classe'
                ])
                ->add('section_classe', TextType::class, [
                    'label' => 'Section de la classe'
                ])
                
                ->getForm();
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $doctrine->getManager()->flush();
    
                $this->addFlash('success', 'La classe a été modifiée avec succès');
    
                return $this->redirectToRoute('app_classe_show', ['id' => $classe->getId()]);
            }
    
            return $this->render('classe/edit_show_classe.html.twig', [
                'form' => $form->createView(),
                'classe' => $classe,
            ]);
        }
}