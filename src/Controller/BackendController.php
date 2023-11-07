<?php

namespace App\Controller;

use App\Model\CompanyDTO;
use App\Service\CompanyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api-ouverte-ent-liste')]
class BackendController extends AbstractController
{
    
    public function __construct(private CompanyService $companyService, private SerializerInterface $serializer) { 
        $encoder = [new JsonEncoder()];
        $normalier = [new ObjectNormalizer()];
    }

    #[Route('/', name: 'get_all', methods:['GET'] )]
    public function index():Response
    {   
        $result = $this->companyService->getAllCompany();
        $jsonContent = $this->serializer->serialize($result, 'json');
        return new Response($jsonContent, 200);
    }

    #[Route('/search', name: 'search', methods:['GET'])]
    public function search(Request $request):Response
    {   
        $siren = $request->get('siren');
        $result = $this->companyService->getAllCompany($siren);

        if (empty($result)) {
            $errorMessage = $this->serializer->serialize("Error no companies found!", 'json');
            return new Response($errorMessage,404);
        }

        $jsonContent = $this->serializer->serialize($result, 'json');
        return new Response($jsonContent, 200);
    }

    #[Route('/', name: 'create', methods:['POST'] )]
    public function create(Request $request):JsonResponse
    {
        $jsonInput = $this->serializer->deserialize($request->getContent(), CompanyDTO::class, 'json');
        dd($jsonInput);
        
        return $this->json("test");
    }
}
