<?php

namespace App\Controller;

use App\Service\CompanyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api-ouverte-ent-liste')]
class BackendController extends AbstractController
{
    private const BASE_URL_API= '/api-ouverte-ent-liste';
    
    public function __construct(private CompanyService $companyService) { }

    #[Route('/', name: 'get_all', methods:['GET'] )]
    public function index():JsonResponse
    {   
        $result = $this->companyService->getAllCompany();
        return $this->json($result, 200);
    }

    #[Route('/search', name: 'search', methods:['GET'])]
    public function search(Request $request):JsonResponse
    {   
        $siren = $request->get('siren');
        $result = $this->companyService->getAllCompany($siren);
        if (empty($result)) {
            return new JsonResponse("Error no companies found!",404);
        }


        return $this->json($result, 200);
    }

    #[Route('/', name: 'create', methods:['POST'] )]
    public function create():JsonResponse
    {   
        return $this->json("test");
    }
}
