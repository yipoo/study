<?php
/**
 * Created by PhpStorm.
 * User: dingzong
 * Date: 2017/11/11
 * Time: 16:59
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 * @Route("/blog")
*/
class BlogController extends Controller
{

    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, name="blog_index")
     * @Route("/rss.xml", defaults={"page": "1", "_format"="xml"}, name="blog_rss")
     * @Route("/page/{page}", defaults={"_format"="html"}, requirements={"page": "[1-9]\d*"}, name="blog_index_paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
     *
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     * See https://symfony.com/doc/current/quick_tour/the_controller.html#using-formats
     */
    public function indexAction($page, $_format)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findLatest($page);

        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
        return $this->render('blog/index.'.$_format.'.twig', ['posts' => $posts]);
    }
    /**
     * @Route("/search", name="blog_search")
     * @Method("GET")
     *
     * @return Response|JsonResponse
     */
    public function searchAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->render('blog/search.html.twig');
        }

        $query = $request->query->get('q', '');
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBySearchQuery($query);

        $results = [];
        foreach ($posts as $post) {
            $results[] = [
                'title' => htmlspecialchars($post->getTitle()),
                'summary' => htmlspecialchars($post->getSummary()),
                'url' => $this->generateUrl('blog_post', ['slug' => $post->getSlug()]),
            ];
        }

        return $this->json($results);
    }
}