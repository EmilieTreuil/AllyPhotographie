<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Category;
use App\Entity\Prestation;
use App\Form\PrestationType;
use App\Form\AddCategoryType;
use App\Form\AjoutPictureType;
use App\Form\UpdatePictureType;
use App\Service\FormatImageService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


/**
* @Route("/admin", name="admin_")
*/
class AdminController extends AbstractController
{
    /**
    * @Route("/", name="index_admin")
    */
    public function indexAdmin(): Response
    {

        $em = $this->getDoctrine()->getManager();
        $pictureRepository = $em->getRepository(Picture::class);
        $categoryRepository = $em->getRepository(Category::class);

        $picture = $pictureRepository->findAll();
        $category = $categoryRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'listePicture' => $picture,
            'listeCategory' => $category,
        ]);
    }

     /**
     * @Route("/form-picture", name="form_picture")
     */
    public function formPicture(Request $request, SluggerInterface $slugger)
    {
        $picture = new Picture();
        $em = $this->getDoctrine()->getManager();
        $formPicture = $this->createForm(AjoutPictureType::class, $picture);
        $formPicture->handleRequest($request);

        $pictureRepository = $em->getRepository(Picture::class);
        $categoryRepository = $em->getRepository(Category::class);
        $pictureListeCategory = $categoryRepository->findEachCategory();


        if($request->isMethod('post') && $formPicture->isValid())
        {
            $pictureRepository = $em->getRepository(Picture::class);
            $picturePresentName = $pictureRepository->findFirstByName($picture->getName());

            if($picturePresentName)
            {
                $this->addFlash('danger', 'Ce nom est déjà enregistré.');
                return $this->redirect($this->generateUrl('admin_form_picture'));
            } 
            else
            {
                $image = $formPicture->get('picture')->getData();

                if($image)
                {
                    $imgTempFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $imgFileName = $slugger->slug($imgTempFileName);
                    $imgFileName = $imgFileName . '-' . uniqid() . '.' . $image->guessExtension();

                    try {
                        $image->move(
                            $this->getParameter('picture_dir'),
                            $imgFileName
                        );
                    } catch (FileException $e) {
                        
                    }
                    $picture->setPicture(pathinfo($imgFileName, PATHINFO_FILENAME) . '.jpg');
                    $id = $_POST["ajout_picture"]["category"];
                    $cat = $categoryRepository->findOneById($id);
                    $picture->setCategoryId($cat);

                }
                $em->persist($picture);
                $em->flush();
    
                return $this->redirect($this->generateUrl('admin_index_admin'));
            }
        }


            return $this->render('form/pictureform.html.twig', [
                'form_picture' => $formPicture->createView(),
                'listeCategory' => $pictureListeCategory,
            ]);
    }

    /**
     * @Route ("/form-update/{idPicture}", name="form_update")
     *
     */
    public function updatePicture(Request $request, $idPicture) 
    {
        $em = $this->getDoctrine()->getManager();
        $pictureRepository = $em->getRepository(Picture::class);
        $categoryRepository = $em->getRepository(Category::class);
        $picture = $pictureRepository->findOneById($idPicture);

        $formPicture = $this->createForm(UpdatePictureType::class, $picture);
        $formPicture->handleRequest($request);

        if($request->isMethod('post') && $formPicture->isValid())
        {
            $id = $_POST["update_picture"]["category"];
            $cat = $categoryRepository->findOneById($id);
            $picture->setCategoryId($cat);
            $em->persist($picture);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_index_admin'));
        }

        return $this->render('form/pictureform.html.twig', [
                'form_picture' => $formPicture->createView()
        ]);


    }

    /**
     * @Route("/form-delete/{idPicture}", name="form_delete")
     */
    public function deletePicture(Request $request, $idPicture){
        
        $em = $this->getDoctrine()->getManager();
        $pictureRepository = $em->getRepository(Picture::class);
        $picture = $pictureRepository->findOneById($idPicture);
        $em->remove($picture);
        $em->flush();
        return $this->redirect($this->generateUrl("admin_index_admin"));
        
    }


    /**
    * @Route("/category", name="index_category")
    */
    public function categoryAdmin(): Response
    {

        $em = $this->getDoctrine()->getManager();
        $pictureRepository = $em->getRepository(Picture::class);
        $categoryRepository = $em->getRepository(Category::class);

        $picture = $pictureRepository->findAll();
        $category = $categoryRepository->findAll();

        return $this->render('admin/category.html.twig', [
            'listePicture' => $picture,
            'listeCategory' => $category,
        ]);
    }

    /**
     * @Route("/form-category", name="form_category")
     */
    public function createCategory(Request $request){
        $category = new Category;
        $formCategory = $this->createForm(AddCategoryType::class, $category);
        $formCategory->handleRequest($request);

        if($request->isMethod('post') && $formCategory->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_index_category'));
        }

        return $this->render('form/categoryform.html.twig', [
            'form_category' => $formCategory->createView(),
        ]);
    }


        /**
     * @Route("/form-delete-category/{idCategory}", name="form_delete_category")
     */
    public function deleteCategory(Request $request, $idCategory){
        
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->findOneById($idCategory);
        $em->remove($category);
        $em->flush();
        return $this->redirect($this->generateUrl("admin_index_category"));
        
    }

    /**
    * @Route("/prestation", name="index_prestation")
    */
    public function prestationAdmin(): Response
    {

        $em = $this->getDoctrine()->getManager();
        $prestationRepository = $em->getRepository(Prestation::class);

        $prestation = $prestationRepository->findAll();

        return $this->render('admin/prestation.html.twig', [
            'listePrestation' => $prestation,
        ]);
    }

     /**
     * @Route("/form-prestation", name="form_prestation")
     */
    public function createPrestation(Request $request){
        $prestation = new Prestation;
        $formPrestation = $this->createForm(PrestationType::class, $prestation);
        $formPrestation->handleRequest($request);

        if($request->isMethod('post') && $formPrestation->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($prestation);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_index_prestation'));
        }

        return $this->render('form/prestationform.html.twig', [
            'form_prestation' => $formPrestation->createView(),
        ]);
    }

    /**
     * @Route ("/form-update-prestation/{idPrestation}", name="form_update_prestation")
     *
     */
    public function updatePrestation(Request $request, $idPrestation) 
    {
        $em = $this->getDoctrine()->getManager();
        $prestationRepository = $em->getRepository(Prestation::class);
        $prestation = $prestationRepository->findOneById($idPrestation);

        $formPrestation = $this->createForm(PrestationType::class, $prestation);
        $formPrestation->handleRequest($request);

        if($request->isMethod('post') && $formPrestation->isValid())
        {
            $em->persist($prestation);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_index_prestation'));
        }

        return $this->render('form/prestationform.html.twig', [
                'form_prestation' => $formPrestation->createView()
        ]);


    }

    /**
     * @Route("/form-delete-prestation/{idPrestation}", name="form_delete_prestation")
     */
    public function deletePrestation(Request $request, $idPrestation){
        
        $em = $this->getDoctrine()->getManager();
        $prestationRepository = $em->getRepository(Prestation::class);
        $prestation = $prestationRepository->findOneById($idPrestation);
        $em->remove($prestation);
        $em->flush();
        return $this->redirect($this->generateUrl("admin_index_prestation"));
        
    }
}