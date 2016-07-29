<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InfoController extends Controller
{
    public function DashboardAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();

        $thisUser = $em->getRepository('FrontendBundle:Datauser')->findOneByUser($user->getId());

        $avatarSave = $thisUser->getAvatar();
        $coverSave = $thisUser->getCover();

        $dataform = $this->createForm('FrontendBundle\Form\DatauserType', $thisUser);

        $dataform->handleRequest($request);
        
        if ($dataform->isValid()) 
        {
            $photoAvatar = $thisUser->getAvatar();

            if ($photoAvatar != NULL)
            {
            	$photoNameAvatar = md5(uniqid()).'.'.$photoAvatar->guessExtension();
	            $photoDirAvatar = $this->container->getParameter('kernel.root_dir').'/../web/uploads/avatar';
	            $photoAvatar->move($photoDirAvatar, $photoNameAvatar);
            	$thisUser->setAvatar($photoNameAvatar);
            }

            $photoCover = $thisUser->getCover();

            if ($photoCover != NULL)
            {
            	$photoNameCover = md5(uniqid()).'.'.$photoCover->guessExtension();
	            $photoDirCover = $this->container->getParameter('kernel.root_dir').'/../web/uploads/cover';
	            $photoCover->move($photoDirCover, $photoNameCover);
            	$thisUser->setCover($photoNameCover);
            }

            if ($photoAvatar != NULL)
            	$thisUser->setCover($coverSave);
            if ($photoCover != NULL)
         		$thisUser->setAvatar($avatarSave);

            $em->persist($thisUser);
            $em->flush();
        }

        return $this->render('FrontendBundle:Default:dashboard.html.twig', array(
            'user' => $user,
            'infouser' => $thisUser,
            'dataform' => $dataform->createView(),
        ));
    }
}
