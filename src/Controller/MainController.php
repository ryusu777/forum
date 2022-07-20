<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Jawaban;
use App\Entity\TagPertanyaan;
use App\Entity\Pertanyaan;
use App\Form\PertanyaanType;
use App\Form\JawabanType;
use App\Repository\PertanyaanRepository;
use App\Repository\JawabanRepository;
use App\Repository\TagPertanyaanRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('main/home.html.twig', ['isAuth' => false]);
    }

    #[Route('/question/create', name: 'app_create', methods: ['GET', 'POST'])]
    public function createQuestion(Request $request, PertanyaanRepository $pertanyaanRepository): Response
    {
        date_default_timezone_set("Asia/Jakarta");
        $pertanyaan = new Pertanyaan();        
        $pertanyaan->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(PertanyaanType::class, $pertanyaan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pertanyaanRepository->add($pertanyaan, true);
            return $this->redirectToRoute('app_questions', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/createQuestion.html.twig', [
            'pertanyaan' => $pertanyaan,
            'form' => $form,
            'isAuth' => false
        ]);
    }

    #[Route('/questions', name: 'app_questions', methods: ['GET', 'POST'])]
    public function search(ManagerRegistry $doctrine, PertanyaanRepository $pertanyaanRepository): Response
    {
        $searchResults = array();
        $jawaban = $doctrine->getRepository(Jawaban::class)->findAll();
        $pertanyaan = $pertanyaanRepository->findAll();
        
        for ($i = 0; $i < count($pertanyaan); $i++){
            $answerCounts = 0;
            for($j = 0; $j < count($jawaban); $j++){
                if($pertanyaan[$i]->getIdPertanyaan() == $jawaban[$j]->getPertanyaan()->getIdPertanyaan()){
                    $answerCounts += 1;
                }
            }
            array_push($searchResults, $answerCounts);
        }

        $searchResults = array_map(null, $pertanyaan, $searchResults);
        $searchResults = array_reverse($searchResults);
        
        return $this->render('main/searchResult.html.twig', ['results' => $searchResults]);
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

            return $this->redirectToRoute('app_questions', [], Response::HTTP_SEE_OTHER);
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

        return $this->redirectToRoute('app_questions', [], Response::HTTP_SEE_OTHER);
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

    #[Route('/details/{idPertanyaan}/{idJawaban}/delete', name: 'app_jawaban_delete', methods: ['GET', 'POST'])]
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

    
    #[Route('/search/{search}', name: 'app_display', methods: ['GET'])]
    public function display($search, ManagerRegistry $doctrine, PertanyaanRepository $pertanyaanRepository): Response
    {
        $searchResults = array();
        $jawaban = $doctrine->getRepository(Jawaban::class)->findAll();
        $pertanyaan = $pertanyaanRepository->findAll();
        
        for ($i = 0; $i < count($pertanyaan); $i++){
            $answerCounts = 0;
            for($j = 0; $j < count($jawaban); $j++){
                if($pertanyaan[$i]->getIdPertanyaan() == $jawaban[$j]->getPertanyaan()->getIdPertanyaan()){
                    $answerCounts += 1;
                }
            }
            array_push($searchResults, $answerCounts);
        }
        $searchResults = array_map(null, $pertanyaan, $searchResults);

        if ($search == 'new'){
            return $this->redirectToRoute('app_questions', [], Response::HTTP_SEE_OTHER);
        } else if ($search == 'top'){
            array_multisort(array_map(function($element) {
                return $element[1];
            }, $searchResults), SORT_DESC, $searchResults);
            $display = $searchResults;
        }

        return $this->render('main/searchResult.html.twig', [
            'results' => $display,
        ]);
    }
    
    #[Route('/search_result', name: 'app_questions_result', methods: ['GET', 'POST'])]
    public function searchBar(Request $request, ManagerRegistry $doctrine,
                            PertanyaanRepository $pertanyaanRepository, TagRepository $tagRepository): Response
    {
        $form = $this->createFormBuilder(null)
            ->add('cari', TextType::class, [ 
            'attr' => [
                'placeholder' => 'Apa pertanyaanmu?'
            ],
            'label' => false
        ])->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $value = $form->getData();
            $searchResults = array();
            $jawaban = $doctrine->getRepository(Jawaban::class)->findAll();
            $tagPertanyaan = $doctrine->getRepository(TagPertanyaan::class)->findAll();

            if(substr($value['cari'], 0, 1) == '#'){
                $searchs = array();
                $searchTag = $tagRepository->findBySearch($value);
                for ($i = 0; $i < count($searchTag); $i++){
                    for($j = 0; $j < count($tagPertanyaan); $j++){
                        if($searchTag[$i]->getIdTag() == $tagPertanyaan[$j]->getTag()->getIdTag()){
                            $search = $pertanyaanRepository->findBy(array('idPertanyaan' => $tagPertanyaan[$j]->getPertanyaan()->getIdPertanyaan()));
                            array_push($searchs, $search[0]);
                            dump($searchs);
                        }
                    }
                }
            } else {
                $searchs = $pertanyaanRepository->findBySearch($value);
            }

            for ($i = 0; $i < count($searchs); $i++){
                $answerCounts = 0;
                for($j = 0; $j < count($jawaban); $j++){
                    if($searchs[$i]->getIdPertanyaan() == $jawaban[$j]->getPertanyaan()->getIdPertanyaan()){
                        $answerCounts += 1;
                    }
                }
                array_push($searchResults, $answerCounts);
            }

            $searchResults = array_map(null, $searchs, $searchResults);
            $searchResults = array_reverse($searchResults);
            return $this->render('main/searchResult.html.twig', [
                'results' => $searchResults
            ]);
        }

        return $this->renderForm('main/question/searchBar.html.twig', [
            'form' =>$form,
        ]);
    }

}
