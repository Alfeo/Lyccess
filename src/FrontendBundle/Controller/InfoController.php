<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InfoController extends Controller
{
    public function DashboardAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();

        return $this->render('FrontendBundle:Default:dashboard.html.twig', array(
            'user' => $user
        ));
    }
}
