<?php 

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CreateUpdateFormType;

class ArticleController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->articlesAction();
    }

    /**
     * @Route("/remove/{idArticle}", defaults={"idArticle" = 0}, name="remove")
     * @Method("GET")
     * @Template()
     */
    public function removeAction($idArticle)
    { 
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Article')->find($idArticle);

        $em->remove($product);
        $em->flush(); //execute la requete demande ici remove(product)

        return $this->articlesAction();
    }

    /**
     * @Route("/update/{idArticle}", defaults={"idArticle" = 0}, name="update")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, $idArticle)
    {
        if ($idArticle > 0)
        {
            $repo = $this->getDoctrine()->getRepository("AppBundle:Article");
            $article = $repo->find($idArticle);

            $formulaire = $this->createForm(CreateUpdateFormType::class, [
                'title' => $article->getTitle(), 
                'content' => $article->getContent()
                ]);
            $formulaire->handleRequest($request);

            if($formulaire->isValid())
            {
                $data = $formulaire->getData();
                /****ici on va travailler avec $article pr modifier l'article****/
                
                $em = $this->getDoctrine()->getManager();
                $article->setTitle($data['title'])->setContent($data['content'])->setDate(new \Datetime());
                $em->persist($article);
                $em->flush();

                /********/

                return $this->backofficeAction();

            }

            return $this->render('article/update.html.twig', ['formulaire' => $formulaire->createView()]);
        }

        return $this->articlesAction(); //retourne a la page d'article si on a un mauvais ID
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction(Request $request)
    {
        $formulaire = $this->createForm(CreateUpdateFormType::class, [
            'title' => 'titre ici', 
            'content' => 'contenu ici'
            ]);
        $formulaire->handleRequest($request);

        if($formulaire->isValid())
        {
            $data = $formulaire->getData();

            $article = new Article();
            $article->setTitle($data['title'])->setContent($data['content'])->setDate(new \Datetime())->setUserId(42);
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();    

            return $this->articlesAction();

        }

        return $this->render('article/update.html.twig', ['formulaire' => $formulaire->createView()]);
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function articlesAction()
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Article");
        $articles = $repo->findAll();

        return $this->render("article/articles.html.twig", ['articles' => $articles]);
    }


    /**
     * @Route("/article/{idArticle}", defaults={"idArticle" = 0}, name="article")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function articleAction($idArticle) //vue d'un article en particulier
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Article");
        $article = $repo->find($idArticle);

        return $this->render("article/article.html.twig", ['article' => $article]);
    }


    /**
     * @Route("/backoffice", name="backoffice")
     */
    public function backofficeAction()
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Article");
        $articles = $repo->findAll();

        return $this->render("article/backoffice.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction()
    {
        $em = $this->getDoctrine()->getManager();


        $articles = $em->getRepository('AppBundle:Article')->findAll();

        dump($articles); die;
        $testComment = new Comment();

        $testComment->setTitle('title')->setComment('content')->setArticle($article)->setUserId(42);

        $em = $this->getDoctrine()->getManager();
        $em->persist($testComment);
        $em->flush();

        return new Response("article + commentaire brut force");
    }

}
 