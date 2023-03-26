<?php

namespace App\Controller;

use App\Service\PaginationService;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductController extends AbstractController
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    // Fetching total product data from external API

    private function fetchData($page, $skip)
    {
        $response = $this->client->request('GET', 'https://dummyjson.com/products', [
            'query' => [
                'page' => $page,
                'skip' => $skip,
                'limit' => 10
            ]
        ]);

        return $data = json_decode($response->getContent(), true);
    }

    // Fetching product data from external API by specific product id

    private function fetchDataById($id)
    {
        $response = $this->client->request('GET', "https://dummyjson.com/products/{$id}");

        return json_decode($response->getContent(), true);
    }


    #[Route('/products', name: 'app_product')]
    public function showProducts(Request $request): Response
    {
        $page =  $request->query->getInt('page', 1); // Getting page number from GET query
        $skip = ($page - 1) * 10; // Page 1: skip 0 products, Page 2: skip 10 products, ...

        $productData = $this->fetchData($page, $skip);

        $pagination = new PaginationService(
            $request->query->getInt('page', 1),
            $productData['total'],
            10
        );

        return $this->render('products/index.html.twig', [
            'data' => $productData['products'],
            'pagination' => $pagination,
        ]);
    }

    #[Route('/products/{id}', name: 'app_product_id')]
    public function showProductById($id): Response
    {
        $productData = $this->fetchDataById($id);

        return $this->render('products/show.html.twig', [
            'product' => $productData,
            'id' => $id
        ]);
    }
}
