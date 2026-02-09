<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Product\Step\ProductDetailsStepType;
use App\Form\Product\Step\ProductPriceStepType;
use App\Form\Product\Step\ProductStockStepType;
use App\Form\Product\Step\ProductTypeStepType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product/flow')]
class ProductFlowController extends AbstractController
{
    // Configuration des étapes : Titre + Classe de formulaire
    private const STEPS = [
        1 => ['title' => 'Type de produit', 'form' => ProductTypeStepType::class],
        2 => ['title' => 'Détails', 'form' => ProductDetailsStepType::class],
        3 => ['title' => 'Stock', 'form' => ProductStockStepType::class],
        4 => ['title' => 'Prix', 'form' => ProductPriceStepType::class],
        5 => ['title' => 'Récapitulatif', 'form' => null], // Pas de formulaire pour le récap
    ];

    #[Route('/{step}', name: 'product_flow', requirements: ['step' => '\d+'], defaults: ['step' => 1])]
    public function handleFlow(int $step, Request $request, EntityManagerInterface $em): Response
    {
        // 1. Sécurité : Vérifier que l'étape existe
        if (!isset(self::STEPS[$step])) {
            return $this->redirectToRoute('product_flow', ['step' => 1]);
        }

        // 2. Récupérer le produit en session (ou en créer un nouveau)
        $session = $request->getSession();
        $product = $session->get('product_flow_data', new Product());

        // Si on est à l'étape finale (Récapitulatif)
        if ($step === 5) {
            // Gestion de la validation finale
            if ($request->isMethod('POST')) {
                // On réintègre l'objet dans Doctrine pour le sauvegarder
                $em->persist($product);
                $em->flush();
                
                // On nettoie la session
                $session->remove('product_flow_data');
                
                $this->addFlash('success', 'Produit créé avec succès !');
                return $this->redirectToRoute('app_product_index'); // Ta liste de produits
            }

            return $this->render('product/flow/recap.html.twig', [
                'product' => $product,
                'steps' => self::STEPS,
                'current_step' => 5
            ]);
        }

        // 3. Créer le formulaire correspondant à l'étape
        $formClass = self::STEPS[$step]['form'];
        $form = $this->createForm($formClass, $product);
        $form->handleRequest($request);

        // 4. Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les données mises à jour en session
            $session->set('product_flow_data', $product);

            // Rediriger vers l'étape suivante
            return $this->redirectToRoute('product_flow', ['step' => $step + 1]);
        }

        return $this->render('product/flow/step.html.twig', [
            'form' => $form->createView(),
            'steps' => self::STEPS,
            'current_step' => $step,
            'step_title' => self::STEPS[$step]['title'],
            'product' => $product // Pour afficher des infos si besoin
        ]);
    }
    
    // Pour l'édition d'un produit existant
    #[Route('/edit/{id}', name: 'product_flow_edit')]
    public function initEdit(Product $product, Request $request): Response
    {
        // On met le produit existant en session et on démarre le flux
        $request->getSession()->set('product_flow_data', $product);
        return $this->redirectToRoute('product_flow', ['step' => 1]);
    }

    #[Route('/start', name: 'product_flow_start')]
    public function start(Request $request): Response
    {
        // On nettoie la session pour repartir de zéro
        $request->getSession()->remove('product_flow_data');
        
        // On redirige vers l'étape 1
        return $this->redirectToRoute('product_flow', ['step' => 1]);
    }
}