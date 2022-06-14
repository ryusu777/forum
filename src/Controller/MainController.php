<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/create', name: 'app_create')]
    public function createQuestion(): Response
    {
        return $this->render('main/createQuestion.html.twig');
    }

    #[Route('/search-result', name: 'app_search_result')]
    public function searchResult(): Response
    {
        return $this->render('main/searchResult.html.twig');
    }

    #[Route('/details', name: 'app_details')]
    public function details(): Response
    {
        return $this->render('main/questionDetail.html.twig');
    }
}
