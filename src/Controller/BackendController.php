<?php

namespace App\Controller;

use App\Model\CompanyDTO;
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
        $sirenExist = $this->companyService->insertCompany($jsonInput);
        
        if (!empty($sirenExist)) {
            $getterUrl = $_SERVER['SERVER_NAME']."/api-ouverte-ent-liste/search?siren=".$sirenExist;
            $message = new DefaultMessage("Company created with siren".$sirenExist, $getterUrl);
            $jsonResponse = $this->serializer->serialize($message, 'json');
            return new Response($jsonResponse, 201);
        }

        } catch (CompanyExistingException $cee) {
            $errorMessage = new DefaultMessage("Enterprise already exist");
            $errorJson = $this->serializer->serialize($errorMessage, 'json');
            return new Response($errorJson, 409);
        } catch (Exception $e) {
            $errorMessage = new DefaultMessage("Error json format");
            $errorJson = $this->serializer->serialize($errorMessage, 'json');
            return new Response($errorJson, 400);
        }        
    }

    #[Route('/{siren}', name: 'update_all_fields', methods:['PATCH'] )]
    public function update_all_fields(string $siren, Request $request) : Response {
        try {
            $jsonInput = $this->serializer->deserialize($request->getContent(), CompanyDTO::class, 'json');
            $sirenExist = $this->companyService->updateCompany($jsonInput, $siren);
            // add auth 
            
            if (!empty($sirenExist)) {
                $getterUrl = $_SERVER['SERVER_NAME']."/api-ouverte-ent-liste/search?siren=".$sirenExist;
                $message = new DefaultMessage("Company created with siren".$sirenExist, $getterUrl);
                $jsonResponse = $this->serializer->serialize($message, 'json');
                return new Response($jsonResponse, 201);
            }
        }catch(CompanyNotExistException $cee) {
            $errorMessage = new DefaultMessage("Error company not found! ");
            $errorJson = $this->serializer->serialize($errorMessage, 'json');
            return new Response($errorJson, 409);
        } catch (Exception $e) {
            $errorMessage = new DefaultMessage("Error json format");
            $errorJson = $this->serializer->serialize($errorMessage, 'json');
            return new Response($errorJson, 400);
        }
    }

    #[Route('/{siren}', name: 'delete', methods:['DELETE'] )]
    public function delete_company(string $siren) {
            // add auth 
        try {
            $isDelete = $this->companyService->remove($siren);

            if ($isDelete) {
                $message = new DefaultMessage("Company was deleted with id: ".$siren);
                $jsonResponse = $this->serializer->serialize($message, 'json');
                return new Response($jsonResponse, 200);
            }

            $message = new DefaultMessage("Error to delete Company with id: ".$siren);
            $jsonResponse = $this->serializer->serialize($message, 'json');
            return new Response($jsonResponse, 400);

        } catch (CompanyNotExistException $cee) {
            $errorMessage = new DefaultMessage("Error company not found with this siren: ".$siren);
            $errorJson = $this->serializer->serialize($errorMessage, 'json');
            return new Response($errorJson, 404);
        } catch (Exception $e) {
            $errorMessage = new DefaultMessage("Error json format");
            $errorJson = $this->serializer->serialize($errorMessage, 'json');
            return new Response($errorJson, 400);
        }
    }
}
