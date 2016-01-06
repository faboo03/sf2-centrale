<?php

namespace Centrale\UserBundle\Controller;

use Centrale\UserBundle\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{firstname}/{lastname}")
     */
    public function indexAction($firstname, $lastname)
    {
//        dump($this->container, $this->getRequest());

        return $this->render('CentraleUserBundle:Default:index.html.twig', array(
            'firstname' => $firstname,
            "lastname" => $lastname
        ));
    }

    /**
     * @Route("/wall/{firstname}/{lastname}", name="centrale_user_bundle_wall")
     * @Template("")
     */
    public function wallAction($firstname, $lastname)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('CentraleUserBundle:Post')->findAll();

        return array(
            'posts' => $posts,
            'firstname' => $firstname,
            "lastname" => $lastname
        );
    }
}
