<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/save', name: 'save')]
    public function save(Request $request): JsonResponse
    {
        $data = $request->get('jsonData');
        $file = [];
        if (is_file('savedCompany.json')) {
            $file = json_decode(file_get_contents('savedCompany.json'), true);
        }
        $file[$data['siren']] = $data;
        file_put_contents('savedCompany.json', json_encode($file));
        $_SESSION['savedData'] = $data;
        return $this->json(['message' => 'OK', 'status' => 1]);
    }
}
