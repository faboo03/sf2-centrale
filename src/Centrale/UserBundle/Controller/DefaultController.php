<?php

namespace Centrale\UserBundle\Controller;

use Centrale\UserBundle\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{firstname}/{lastname}")
     */
    public function indexAction($firstname, $lastname)
    {

        return $this->render('CentraleUserBundle:Default:index.html.twig', array(
            'firstname' => $firstname,
            "lastname" => $lastname
        ));
    }

    /**
     * @Route("/wall/{firstname}/{lastname}", name="centrale_user_bundle_wall")
     * @Template("")
     */
    public function wallAction(Request $request, $firstname, $lastname)
    {

        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('CentraleUserBundle:Post')->findAll();

        $newPost = new Post();
        $newPost->setCreatedAt(new \DateTime());
        $newPost->setAuthor($firstname.' '.$lastname);

        $form = $this->createFormBuilder($newPost)
                ->setAction($this->generateUrl("centrale_user_bundle_wall", array(
                            'firstname' => $firstname,
                            "lastname" => $lastname)
                ))
                ->setMethod('POST')
                ->add('created_at', 'datetime', array("label" => "Créé le", "widget" => 'single_text'))
                ->add('message', 'textarea')
                ->add('author')
                ->add('save', 'submit')
                ->getForm();

        if($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if($form->isValid()){
                $post = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
            }
        }

        return array(
            'posts' => $posts,
            'firstname' => $firstname,
            "lastname" => $lastname,
            "form" => $form->createView()
        );
    }
}
