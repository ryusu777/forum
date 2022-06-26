<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Jawaban;
use App\Entity\Pertanyaan;
use App\Form\PertanyaanType;
use App\Form\JawabanType;
use App\Repository\PertanyaanRepository;
use App\Repository\JawabanRepository;
use App\Repository\TagPertanyaanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, PertanyaanRepository $pertanyaanRepository): Response
    {
        $results = array();
        $jawaban = $doctrine->getRepository(Jawaban::class)->findAll();
        $pertanyaan = $pertanyaanRepository->findAll();
        
        for ($i = 0; $i < count($pertanyaan); $i++){
            $answerCounts = 0;
            for($j = 0; $j < count($jawaban); $j++){
                if($pertanyaan[$i]->getIdPertanyaan() == $jawaban[$j]->getPertanyaan()->getIdPertanyaan()){
                    $answerCounts += 1;
                }
            }
            array_push($results, $answerCounts);
        }
        $results = array_map(null, $pertanyaan, $results);
        $results = array_reverse($results);
        return $this->render('main/home.html.twig', [
            'items' => $results
        ]);
    }

    #[Route('/create', name: 'app_create')]
    public function createQuestion(Request $request, PertanyaanRepository $pertanyaanRepository): Response
    {
        date_default_timezone_set("Asia/Jakarta");
        $pertanyaan = new Pertanyaan();        
        $pertanyaan->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(PertanyaanType::class, $pertanyaan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pertanyaanRepository->add($pertanyaan, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/createQuestion.html.twig', [
            'pertanyaan' => $pertanyaan,
            'form' => $form,
        ]);
    }

    #[Route('/create/edit/{idPertanyaan}/', name: 'app_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pertanyaan $pertanyaan, PertanyaanRepository $pertanyaanRepository): Response
    {
        date_default_timezone_set("Asia/Jakarta");  
        $pertanyaan->setUpdatedAt(new \DateTime('now'));

        $form = $this->createForm(PertanyaanType::class, $pertanyaan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pertanyaanRepository->add($pertanyaan, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/createQuestion.html.twig', [
            'pertanyaan' => $pertanyaan,
            'form' => $form,
        ]);
    }

    #[Route('/details/{idPertanyaan}', name: 'app_delete', methods: ['POST'])]
    public function delete(Request $request, Pertanyaan $pertanyaan, PertanyaanRepository $pertanyaanRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pertanyaan->getIdPertanyaan(), $request->request->get('_token'))) {
            $pertanyaanRepository->remove($pertanyaan, true);
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search/{search}', name: 'app_search', methods: ['GET'])]
    public function display($search, ManagerRegistry $doctrine, PertanyaanRepository $pertanyaanRepository): Response
    {
        $results = array();
        $jawaban = $doctrine->getRepository(Jawaban::class)->findAll();
        $pertanyaan = $pertanyaanRepository->findAll();
        
        for ($i = 0; $i < count($pertanyaan); $i++){
            $answerCounts = 0;
            for($j = 0; $j < count($jawaban); $j++){
                if($pertanyaan[$i]->getIdPertanyaan() == $jawaban[$j]->getPertanyaan()->getIdPertanyaan()){
                    $answerCounts += 1;
                }
            }
            array_push($results, $answerCounts);
        }
        $results = array_map(null, $pertanyaan, $results);

        if ($search == 'new'){
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        } else if ($search == 'top'){
            array_multisort(array_map(function($element) {
                return $element[1];
            }, $results), SORT_DESC, $results);
            $display = $results;
        }

        dump($display);
        return $this->render('main/home.html.twig', [
            'items' => $display,
        ]);
    }

    #[Route('/search-result', name: 'app_search_result')]
    public function searchResult(): Response
    {
        return $this->render('main/searchResult.html.twig');
    }

    #[Route('/details/{idPertanyaan}', name: 'app_details')]
    public function details(Pertanyaan $pertanyaan, JawabanRepository $jawabanRepository, TagPertanyaanRepository $tagPertanyaanRepository): Response
    {
        $tagPertanyaans = $tagPertanyaanRepository->findBy(array('pertanyaan' => $pertanyaan->getIdPertanyaan()));
        $namaTags = array();
        for ($i = 0; $i < count($tagPertanyaans); $i++){
            array_push($namaTags, $tagPertanyaans[$i]->getTag()->getNamaTag());
        }

        return $this->render('main/questionDetail.html.twig', [
            'pertanyaan' => $pertanyaan, 
            'jawabans' => $jawabanRepository->findBy(array('pertanyaan' => $pertanyaan->getIdPertanyaan())),
            'namaTags' => $namaTags
        ]);
    }

    #[Route('/details/{idPertanyaan}/{idJawaban}', name: 'app_jawaban_vote', methods: ['GET', 'POST'])]
    public function jawabanVote(Jawaban $jawaban, JawabanRepository $jawabanRepository, Pertanyaan $pertanyaan): Response
    {
        $jawaban->setVote($jawaban->getVote() + 1);

        $jawabanRepository->add($jawaban, true);

        return $this->redirectToRoute('app_details', ['idPertanyaan'=> $pertanyaan->getIdPertanyaan()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/details/{idPertanyaan}/{idJawaban}/approve', name: 'app_jawaban_approve', methods: ['GET', 'POST'])]
    public function jawabanApprove(Jawaban $jawaban, JawabanRepository $jawabanRepository, Pertanyaan $pertanyaan): Response
    {
        if ($jawaban->getApproveStatus() == 1){
            $jawaban->setApproveStatus(0);
        } else {
            $jawaban->setApproveStatus(1);
        }

        $jawabanRepository->add($jawaban, true);

        return $this->redirectToRoute('app_details', ['idPertanyaan'=> $pertanyaan->getIdPertanyaan()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/details/{idPertanyaan}/{idJawaban}', name: 'app_jawaban_delete', methods: ['GET', 'POST'])]
    public function jawabanDelete(Jawaban $jawaban, JawabanRepository $jawabanRepository, Pertanyaan $pertanyaan): Response
    {
        $jawabanRepository->remove($jawaban, true);

        return $this->redirectToRoute('app_details', ['idPertanyaan'=> $pertanyaan->getIdPertanyaan()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/details/{idPertanyaan}/jawaban/new', name: 'app_jawaban_new', methods: ['GET', 'POST'])]
    public function jawabanNew(Pertanyaan $pertanyaan, Request $request, JawabanRepository $jawabanRepository): Response
    {
        date_default_timezone_set("Asia/Jakarta");
        $jawaban = new Jawaban();
        $jawaban->setCreatedAt(new \DateTime('now'))
                ->setApproveStatus(0)
                ->setPertanyaan($pertanyaan);
        $form = $this->createForm(JawabanType::class, $jawaban);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jawabanRepository->add($jawaban, true);

            return $this->redirectToRoute('app_details', ['idPertanyaan'=> $pertanyaan->getIdPertanyaan()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/answer/new.html.twig', [
            'pertanyaan' => $pertanyaan, 
            'jawaban' => $jawaban,
            'form' => $form,
        ]);
    }

    #[Route('/details/{idPertanyaan}/{idJawaban}/edit', name: 'app_jawaban_edit', methods: ['GET', 'POST'])]
    public function jawabanEdit(Request $request, Jawaban $jawaban, JawabanRepository $jawabanRepository, Pertanyaan $pertanyaan): Response
    {
        date_default_timezone_set("Asia/Jakarta");
        
        $jawaban->setUpdatedAt(new \DateTime('now'));

        $form = $this->createForm(JawabanType::class, $jawaban);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jawabanRepository->add($jawaban, true);

            return $this->redirectToRoute('app_details', ['idPertanyaan'=> $pertanyaan->getIdPertanyaan()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/answer/new.html.twig', [
            'pertanyaan' => $pertanyaan, 
            'jawaban' => $jawaban,
            'form' => $form,
        ]);
    }
}
