<?php
/**
 * Created by PhpStorm.
 * User: dingzong
 * Date: 2017/11/11
 * Time: 19:03
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use AppBundle\Utils\Slugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogController
 * @package AppBundle\Controller\Admin
 * @Route("/admin/blog")
 * @Security("has_role('ROLE_ADMIN')")

 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     * @Route("/", name="admin_post_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findBy(['author' => $this->getUser()], ['publishedAt' => 'DESC']);

        return $this->render('admin/blog/index.html.twig',['posts'=> $posts]);

    }

    /**
     * @Route("/new", name="admin_post_new")
     * @Method({"GET","POST"})
     */
    public function newAction(Request $request, Slugger $slugger)
    {
        $post = new Post();
        $post->setAuthor($this->getUser());

        $form = $this->createForm(PostType::class, $post)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post->setSlug($slugger->slugify($post->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();


            $this->addFlash('success','post.created_successfully');

            if($form->get('saveAndCreateNew')->isClicked()){
                return $this->redirectToRoute('admin_post_new');
            }
            return $this->redirectToRoute("admin_post_index");
        }
        return $this->render('admin/blog/new.html.twig', [
            'post'  => $post,
            'form'  => $form->createView(),
        ]);


    }
}