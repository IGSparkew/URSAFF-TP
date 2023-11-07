<?php

namespace App\Controller;

use App\Model\CompanyDTO;
use App\Service\CompanyService;
use DefaultMessage;
use Doctrine\ORM\Cache\DefaultCache;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api-ouverte-ent-liste')]
class BackendController extends AbstractController
{
    
    public function __construct(private CompanyService $companyService, private SerializerInterface $serializer) { }

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
            $errorMessage = new DefaultMessage("Error no companies found!");
            $errorJson = $this->serializer->serialize($errorMessage, 'json');
            return new Response($errorJson, 404);
        }

        $jsonContent = $this->serializer->serialize($result, 'json');
        return new Response($jsonContent, 200);
    }

    #[Route('/', name: 'create', methods:['POST'] )]
    public function create(Request $request):Response
    {   
        try {
        $jsonInput = $this->serializer->deserialize($request->getContent(), CompanyDTO::class, 'json');
        $statusResponse = $this->companyService->upsertCompany($jsonInput, true);

        if ($statusResponse == 409) {
            $errorMessage = new DefaultMessage("Enterprise already exist");
            $errorJson = $this->serializer->serialize($errorMessage, 'json');
            return new Response($errorJson, 409);
        }
        
        if ($statusResponse == 200) {
            $getterUrl = $_SERVER['SERVER_NAME']."/api-ouverte-ent-liste/search?siren=".$jsonInput->getSiren();
            $message = new DefaultMessage("Company created with siren".$jsonInput->getSiren(), $getterUrl);
            $jsonResponse = $this->serializer->serialize($message, 'json');
            return new Response($jsonResponse, $statusResponse);
        }

        } catch (Exception $e) {
            $errorMessage = new DefaultMessage("Error json format");
            $errorJson = $this->serializer->serialize($errorMessage, 'json');
            return new Response($errorJson, 400);
        }        
    }
}
