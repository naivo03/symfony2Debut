<?php 

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;
use AppBundle\Form\Type\CreateUpdateFormType    ;

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
        $repo = $this->getDoctrine()->getRepository("AppBundle:Article");
        $articles = $repo->findAll();

        return $this->render("article/backoffice.html.twig", ['articles' => $articles]);
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

                $repo = $this->getDoctrine()->getRepository("AppBundle:Article");
                $articles = $repo->findAll();

                return $this->render("article/backoffice.html.twig", ['articles' => $articles]);

            }

            return $this->render('article/update.html.twig', ['formulaire' => $formulaire->createView()]);
        }

        return $this->articlesAction(); //tetourne a la page d'article si on a un mauvais ID

        /*$em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Article')->find(1);

        dump($product);
        $product->setTitle('titre updater');
        $em->persist($product);
        $em->flush();

        return new Response("update d'un article");*/ 
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

            $repo = $this->getDoctrine()->getRepository("AppBundle:Article");
            $articles = $repo->findAll();

            return $this->render("article/backoffice.html.twig", ['articles' => $articles]);

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
     * @Route("/article", name="article")
     */
    public function articleAction(/*$idArticle*/) //vue d'un article en particulier
    {
        $repo = $this->getDoctrine()->getRepository("AppBundle:Article");
        $article = $repo->find(1);

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

}
 