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
    private const STEPS = [
        1 => ['title' => 'Type de produit', 'form' => ProductTypeStepType::class],
        2 => ['title' => 'Détails', 'form' => ProductDetailsStepType::class],
        3 => ['title' => 'Stock', 'form' => ProductStockStepType::class],
        4 => ['title' => 'Prix', 'form' => ProductPriceStepType::class],
        5 => ['title' => 'Récapitulatif', 'form' => null], 
    ];

    #[Route('/{step}', name: 'product_flow', requirements: ['step' => '\d+'], defaults: ['step' => 1])]
    public function handleFlow(int $step, Request $request, EntityManagerInterface $em): Response
    {
        if (!isset(self::STEPS[$step])) {
            return $this->redirectToRoute('product_flow', ['step' => 1]);
        }

        $session = $request->getSession();
        $product = $session->get('product_flow_data', new Product());

        if ($step === 5) {
            if ($request->isMethod('POST')) {
                $em->persist($product);
                $em->flush();
                
                $session->remove('product_flow_data');
                
                $this->addFlash('success', 'Produit créé avec succès !');
                return $this->redirectToRoute('app_product_index'); 
            }

            return $this->render('product/flow/recap.html.twig', [
                'product' => $product,
                'steps' => self::STEPS,
                'current_step' => 5
            ]);
        }

        $formClass = self::STEPS[$step]['form'];
        $form = $this->createForm($formClass, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('product_flow_data', $product);
            return $this->redirectToRoute('product_flow', ['step' => $step + 1]);
        }

        return $this->render('product/flow/step.html.twig', [
            'form' => $form->createView(),
            'steps' => self::STEPS,
            'current_step' => $step,
            'step_title' => self::STEPS[$step]['title'],
            'product' => $product
        ]);
    }
    
    #[Route('/edit/{id}', name: 'product_flow_edit')]
    public function initEdit(Product $product, Request $request): Response
    {
        $request->getSession()->set('product_flow_data', $product);
        return $this->redirectToRoute('product_flow', ['step' => 1]);
    }

    #[Route('/start', name: 'product_flow_start')]
    public function start(Request $request): Response
    {
        $request->getSession()->remove('product_flow_data');
        
        return $this->redirectToRoute('product_flow', ['step' => 1]);
    }
}