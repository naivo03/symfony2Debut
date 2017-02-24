<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction()
    {
        

        for ($i=0; $i < 10; $i++) { 
            $article = new Article();
            $article->setTitle("article ".rand(1, 1000))->setContent("bonjour 1er article")->setDate(new \Datetime())->setUserId(42);
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();    
        }

        return new Response("creation d'un article"); 
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function articlesAction()
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Article");

        $articles = $repo->findAll();

        return $this->render("article/articles.html.twig", array('articles' => $articles));
    }
}
