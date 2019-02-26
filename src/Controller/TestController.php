<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use App\Entity\Evaluation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MovieRepository;


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

    // /**
    //  * @Route("/test", name="test")
    //  */
    // // fonction fète pr tester ds trucs

    // public function test()
    // {
    //     $ms = $this->getDoctrine()->getRepository(Movie::class)->findAll();
    //     // fonction qui essé de calc moyen note flm mais prblm
    //     for ($i=0; $i < count($ms) ; $i) {
    //       $notes = $ms[$i]->getEvaluations()->getGrade();
    //     }
    //     return $this->render('test/index.html.twig', [
    //       "ms" => $ms
    //     ]);
    // }

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
     * @Route("/evaluation/{id}", name="evaluation")
     * @Isgranted("ROLE")
     */
    public function rate(Movie $b, Request $c)
    {
        $d = new Evaluation();

        $form = $this->createFormBuilder($d)
            ->add('comment')
            ->add('grade')
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $d.setMovie($b);
          $d.setUser($u);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($d);
          $entityManager->flush();
        }

        return $this->render('test/evaluation.html.twig', [
          "b" => $b,
          "form" => $form->createView()
        ]);
    }
}
