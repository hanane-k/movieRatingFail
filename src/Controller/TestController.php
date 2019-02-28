<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use App\Entity\User;
use App\Entity\Evaluation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MovieRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TestController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
      // return new Response("<html><body>hi</html></body>");
        $ms = $this->getDoctrine()->getRepository(Movie::class)->findAll();
        return $this->render('test/index.html.twig', [
          "ms" => $ms
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    // fonction fète pr tester ds trucs

    public function test()
    {
    //     $ms = $this->getDoctrine()->getRepository(Movie::class)->findAll();
    //     // fonction qui essé de calc moyen note flm mais prblm
    //     for ($i=0; $i < count($ms) ; $i) {
    //       $notes = $ms[$i]->getEvaluations()->getGrade();
    //     }
    //     return $this->render('test/index.html.twig', [
    //       "ms" => $ms
    //     ]);    //     return $this->render('test/index.html.twig', [
    //       "ms" => $ms
    //     ]);
      return new Response("<h1>c'est la page test</h1>");
    }

    /**
     * @Route("/single/{id}", name="show")
     */
    public function show(Movie $a)
    {
        return $this->render('test/single.html.twig', [
          "a" => $a
        ]);
    }

    /**
     * @Route("/evaluation/{id}", name="evaluation", methods={"GET","POST"})
     * @Isgranted("ROLE_USER")
     */
    public function rate(Movie $movie, Request $request)
    {
        $evaluation = new Evaluation();
        $user = $this->getUser();
        dump($user);
        $form = $this->createFormBuilder($evaluation)
            ->add('comment')
            ->add('grade')
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $evaluation->setMovie($movie);
          $evaluation->setUser($user);
          $entityManager->persist($evaluation);
          $entityManager->flush();
          return $this->redirectToRoute('index');
        }

        return $this->render('test/evaluation.html.twig', [
          "movie" => $movie,
          "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/moyenne/{id}", name="moyenne")
     */
    public function getAverage($evaluations) {
      $evaluations = $this->getEvaluations();
      $moyenne = array_sum($this->getEvaluations())/count($evaluations);
      return $moyenne;
  }

}
