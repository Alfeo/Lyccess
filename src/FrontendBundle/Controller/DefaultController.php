<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
