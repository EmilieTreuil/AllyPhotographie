<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



/**
* @Route("/auth", name="auth_")
*/
class RegisterController extends AbstractController
{
    /*
     * @Route("/register-admin", name="register_admin")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passEncoder): Response
    {

        $formInscription = $this->createFormBuilder()
        ->add('email', TextType::class, ['label' => 'Email'])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmation du mot de passe']
        ])
        ->add('Inscription', SubmitType::class, [
            'attr' => ['class' => 'btn btn-dark']
        ])
        ->getForm()
        ;

        $formInscription->handleRequest($request);

        if($request->isMethod('post') && $formInscription->isSubmitted() && $formInscription->isValid())
        {
            $data = $formInscription->getData();
            $user = new User;
            $em = $this->getDoctrine()->getManager();
            $user->setPassword(
                $passEncoder->encodePassword($user, $data['password'])
            );
            $user->setEmail($data['email']);
            $user->setRoles(['ROLE_ADMIN']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('auth_app_login'));
        }

        return $this->render('register/index.html.twig', [
            'formRegister' => $formInscription->createView(),
        ]);

    }

}
