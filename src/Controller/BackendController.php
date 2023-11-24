<?php

namespace App\Controller;

use App\Model\CompanyDTO;
use App\Service\AuthService;
use App\Service\CompanyService;
use CompanyExistingException;
use CompanyNotExistException;
use DefaultMessage;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api-ouverte-ent-liste')]
class BackendController extends AbstractController
{
    
    public function __construct(private CompanyService $companyService, private SerializerInterface $serializer, private AuthService $authService) { }

    #[Route('/', name: 'get_all', methods:['GET'] )]
    public function index(Request $request):Response
    {
        $contentType = $request->headers->get("Content-Type");
        $type = 'json';
        if ($contentType == "text/csv") $type = 'csv';

        $result = $this->companyService->getAllCompany();
        $jsonContent = $this->serializer->serialize($result, $type);
        return new Response($jsonContent, 200);
    }

    #[Route('/search', name: 'search', methods:['GET'])]
    public function search(Request $request):Response
    {   
        $siren = $request->get('siren');
        $result = $this->companyService->getAllCompany($siren);

        if (empty($result)) {
            return $this->setupResponse("Error no companies found!", 404);
        }

        $jsonContent = $this->serializer->serialize($result, 'json');
        return new Response($jsonContent, 200);
    }

    #[Route('/', name: 'create', methods:['POST'] )]
    public function create(Request $request):Response
    {   
        try {
        $jsonInput = $this->serializer->deserialize($request->getContent(), CompanyDTO::class, 'json');
        $sirenExist = $this->companyService->insertCompany($jsonInput);
        
        if (!empty($sirenExist)) {
            $getterUrl = $_SERVER['SERVER_NAME']."/api-ouverte-ent-liste/search?siren=".$sirenExist;
            return $this->setupResponse("Company created with siren".$sirenExist, 201, $getterUrl);
        }

        } catch (CompanyExistingException $cee) {
            return $this->setupResponse("Enterprise already exist", 409);
        } catch (Exception $e) {
            return $this->setupResponse("Error json format", 400);
        }        
    }

    #[Route('/{siren}', name: 'update_fields', methods:['PATCH'] )]
    public function update_fields(string $siren, Request $request) : Response {
        try {
            $jsonInput = $this->serializer->deserialize($request->getContent(), CompanyDTO::class, 'json');
            $sirenExist = $this->companyService->updateCompany($jsonInput, $siren);

            $response = $this->authSecurity($request);
            if (!empty($response)) return $response;
            
            if (!empty($sirenExist)) {
                $getterUrl = $_SERVER['SERVER_NAME']."/api-ouverte-ent-liste/search?siren=".$sirenExist;
                return $this->setupResponse("Company updated with siren".$sirenExist, 200, $getterUrl);
                
            }
        }catch(CompanyNotExistException $cee) {
            return $this->setupResponse("Error company not found! ", 404);

        } catch (Exception $e) {
            return $this->setupResponse("Error json format", 400);
        }
    }

    #[Route('/{siren}', name: 'delete', methods:['DELETE'] )]
    public function delete_company(Request $request, string $siren) {
        try {
            $response = $this->authSecurity($request);
            
            if (!empty($response)) return $response;
            $this->companyService->remove($siren);

            return $this->setupResponse("Company was deleted with id: ".$siren, 200);
        } catch (CompanyNotExistException $cee) {
            return $this->setupResponse("Error company not found with this siren: ".$siren, 404);
        } catch (Exception $e) {
            return $this->setupResponse("Error json format", 400);
        }
    }

    private function authSecurity(Request $request): Response | null {
        $app_user = $this->getParameter('app.username');
        $app_password = $this->getParameter('app.password');
        $authIsLogin =  $this->authService->login($request->headers, $app_user, $app_password);

        if (!$authIsLogin) {
            return $this->setupResponse("Error unauthorize to access to this ressource", 402);
        }

        return null;
    }

    private function setupResponse(string $message, int $status, string $uri=null): Response {
            $content = new DefaultMessage($message, $uri);
            $jsonContent = $this->serializer->serialize($content, 'json');
            return new Response($jsonContent, $status);
    }
}
