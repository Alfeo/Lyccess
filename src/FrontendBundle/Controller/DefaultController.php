<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FrontendBundle\Entity\Succes;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $formFactory = $this->get('fos_user.registration.form.factory');
        $form = $formFactory->createForm();

        if ($this->has('security.csrf.token_manager')) {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } else {
            // BC for SF < 2.4
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        }

        return $this->render('FrontendBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'csrf_token' => $csrfToken,
        ));
    }

    public function challengeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();

        $challengeTab = [];

        $challenges = $em->getRepository('FrontendBundle:Succes')->findAll();

        foreach ($challenges as $challenge) 
        {
            $owner = $em->getRepository('AppBundle:User')->findOneById($challenge->getOwner());
            $challengeTab[] = array(
                'owner' => $owner->getUsername(),
                'category' => $challenge->getCategory(),
                'name' => $challenge->getName(),
                'description' => $challenge->getDescription(),
                'reward' => $challenge->getReward()
            );
        }

        return $this->render('FrontendBundle:Default:challenge.html.twig', array(
            'user' => $user,
            'challenges' => $challengeTab
        ));
    }

    public function addChallengeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->container->get('security.context')->getToken()->getUser();

        $field1 = $request->request->get('category');
        $field2 = $request->request->get('name');
        $field3 = $request->request->get('description');
        $field4 = $request->request->get('reward');
        $field5 = $request->request->get('iduser');

        $challenge = new Succes();
        $challenge->setCategory($field1);
        $challenge->setName($field2);
        $challenge->setDescription($field3);
        $challenge->setReward($field4);
        $challenge->setOwner($field5);

        $em->persist($challenge);
        $em->flush();

        $url = $this->generateUrl('index_challenge');
        $response = new RedirectResponse($url);

        return $response;
    }
}
