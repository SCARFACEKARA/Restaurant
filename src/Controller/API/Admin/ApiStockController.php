<?php

namespace App\Controller\API\Admin;


use App\Entity\Stock;
use App\Entity\Ingredient;
use App\Enum\StockStatus;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin/stocks')]
class ApiStockController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/all', name: 'api_stock_list', methods: ['GET'])]
    public function findAll(StockRepository $stockRepository): JsonResponse
    {
        return $this->json($stockRepository->findAll(), Response::HTTP_OK);
    }

    #[Route('/create', name: 'api_stock_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $ingredient = $this->entityManager->getRepository(Ingredient::class)->find($data['idIngredient']);
        if (!$ingredient) {
            return $this->json(['error' => 'Ingredient not found'], Response::HTTP_NOT_FOUND);
        }

        $status = StockStatus::tryFrom($data['status']);
        if (!$status) {
            return $this->json(['error' => 'Invalid status'], Response::HTTP_BAD_REQUEST);
        }

        $stock = new Stock();
        $stock->setIngredient($ingredient);
        $stock->setQuantite($data['quantite']);
        $stock->setDateStock(new \DateTime($data['dateStock']));
        $stock->setStatus($status);

        $this->entityManager->persist($stock);
        $this->entityManager->flush();

        return $this->json($stock, Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'api_stock_delete', methods: ['DELETE'])]
    public function delete(Stock $stock): JsonResponse
    {
        $this->entityManager->remove($stock);
        $this->entityManager->flush();

        return $this->json(['message' => 'Stock deleted successfully'], Response::HTTP_OK);
    }
}
