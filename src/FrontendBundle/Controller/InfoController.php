<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FrontendBundle\Entity\Actu;

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

        $tabActu = [];

        $allActu = $em->getRepository('FrontendBundle:Actu')->findByAuteur($user->getId());
        foreach ($allActu as $actu)
        {
            $thisUserFull = $em->getRepository('AppBundle:User')->findOneById($actu->getAuteur());
            $thisDatauser = $em->getRepository('FrontendBundle:Datauser')->findOneByUser($actu->getAuteur());

            $tabActu[] = array(
                'username' => $thisUserFull->getUsername(),
                'message' => $actu->getMessage(),  
                'avatar' => $thisDatauser->getAvatar(),  
            );
        }

        return $this->render('FrontendBundle:Default:dashboard.html.twig', array(
            'user' => $user,
            'infouser' => $thisUser,
            'dataform' => $dataform->createView(),
            'allActu' => array_reverse($tabActu),
        ));
    }

    public function newActuAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();

        $message = $request->request->get('message');

        $actu = new Actu();
        $actu->setAuteur($user->getId());
        $actu->setMessage($message);
        $actu->setType(1);

        $em->persist($actu);
        $em->flush();

        $url = $this->generateUrl('user_dashboard');
        $response = new RedirectResponse($url);

        return $response;
    }

    public function showUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($id == $user->getId())
        {
            $url = $this->generateUrl('user_dashboard');
            $response = new RedirectResponse($url);

            return $response;
        }

        $thisUser = $em->getRepository('FrontendBundle:Datauser')->findOneByUser($id);

        $tabActu = [];

        $allActu = $em->getRepository('FrontendBundle:Actu')->findByAuteur($id);
        foreach ($allActu as $actu)
        {
            $thisUserFull = $em->getRepository('AppBundle:User')->findOneById($actu->getAuteur());
            $thisDatauser = $em->getRepository('FrontendBundle:Datauser')->findOneByUser($actu->getAuteur());

            $tabActu[] = array(
                'username' => $thisUserFull->getUsername(),
                'message' => $actu->getMessage(),  
                'avatar' => $thisDatauser->getAvatar(),  
            );
        }

        return $this->render('FrontendBundle:Default:showuser.html.twig', array(
            'user' => $user,
            'infouser' => $thisUser,
            'allActu' => array_reverse($tabActu),
        ));
    }
}
