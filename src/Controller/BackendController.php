<?php

namespace App\Controller;

use App\Service\CompanyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    private const BASE_URL_API= '/api-ouverte-ent-liste';
    
    public function __construct(private CompanyService $companyService) { }

    #[Route(self::BASE_URL_API .'/', name: 'app_backend')]
    public function index():JsonResponse
    {   
        $result = $this->companyService->getAllCompany();
        return $this->json($result, 200);
    }
}
