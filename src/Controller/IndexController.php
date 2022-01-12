<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Picture;
use App\Entity\Category;
use App\Form\ContactType;
use App\Entity\Prestation;
use App\Form\InscriptionType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function mentionslegales(): Response
    {
        return $this->render('index/mentionslegales.html.twig');
    }

    /**
     * @Route("/galerie", name="galerie")
     */
    public function galerie(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $pictureRepository = $em->getRepository(Picture::class);
        $categoryRepository = $em->getRepository(Category::class);

        $pictureCategory = $categoryRepository->findEachCategory();
        $picture = $pictureRepository->findAll();

        return $this->render('index/galerie.html.twig', [
            'listeCategory' => $pictureCategory,
            'listePicture' => $picture
        ]);
    }

    /**
    * @route ("/galerie/{nameCategory?}", name="galerie_custom")
    */
    public function customIndex(Request $request, $nameCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $pictureRepository = $em->getRepository(Picture::class);
        $categoryRepository = $em->getRepository(Category::class);

        $listePicture = $pictureRepository->findByCategory($nameCategory);

        $listeCategory = $categoryRepository->findEachCategory();

        return $this->render('index/galerie.html.twig', [
            'listePicture' => $listePicture,
            'listeCategory' => $listeCategory,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {

        $formContact = $this->createForm(ContactType::class);

        $formContact->handleRequest($request);



        if($request->isMethod('post') && $formContact->isValid()) {

            $contactFormData = $formContact->getData();
            
            $message = (new Email())
                ->from($contactFormData['email'])
                ->to('emythekiwii@gmail.com')
                ->subject($contactFormData['objet'].\PHP_EOL)
                ->text('Expéditeur : '.$contactFormData['email'].\PHP_EOL.\PHP_EOL.
                    $contactFormData['message'],
                    'text/plain');
            $mailer->send($message);


            $this->addFlash('success', 'Votre email a bien été envoyé.');

            return $this->redirectToRoute('contact');
        }
        return $this->render('index/contact.html.twig', [
            'form_contact' => $formContact->createView(),
        ]);
    }

    /**
     * @Route("/formules-tarifs", name="formules_tarifs")
     */
    public function formulesTarifs(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $prestationRepository = $em->getRepository(Prestation::class);

        $prestation = $prestationRepository->findAll();

        return $this->render('index/formulestarifs.html.twig', [
            'listePrestation' => $prestation,
        ]);
    }
}
