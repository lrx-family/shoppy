<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Item;
use AppBundle\Form\CategoryType;
use AppBundle\Form\ItemType;
use AppBundle\Form\CategorySearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

        $item = new Item();
        $form = $this->createForm(ItemType::class, $item, [
            'attr' => [
                'class' => 'form-inline'
            ]]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('app_home');
        } else {
            dump($form->getErrors());
            dump($form->getData());
        }


        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('AppBundle:Item')->findBy([], ['label' => 'asc']);

        return $this->render(':default:item.html.twig', ['items' => $items, 'category' => $item,
            'form' => $form->createView(),]);
    }

    public function listCategoriesAction(Request $request)
    {
        // ici je récupère l'entity manager
        $em = $this->getDoctrine()->getManager();

        // création du formulaire
        $cat = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $cat);
        $form->add('save', SubmitType::class, ['label' => 'Enregistrer']);

        $form->handleRequest($request);

        // si le forumlaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //j'ajoute la ctégorie
            $em->persist($cat);
            $em->flush();
        }

        // maintenant je récupère toutes les catégories
        $categories = $em->getRepository('AppBundle:Category')->findBy([], ['label' => 'asc']);

        // et j'affiche ma page
        return $this->render(':default:categorie.html.twig', [
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }


    public function deleteCategoryAction($id)
    {
        // trouver la categorie correspondante
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);

        // puis la supprimer
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('app_list_categories');
    }

    public function deleteItemAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:Item')->find($id);
        $em->remove($item);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    public function plusItemAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:Item')->find($id);
        $item->setQuantity($item->getQuantity() + 1);
        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    public function decreaseItemAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:Item')->find($id);
        if ($item->getQuantity() > 1) {
            $item->setQuantity($item->getQuantity() - 1);
            $em->persist($item);
            $em->flush();
        } elseif ($item->getQuantity() == 1) {
            $em->remove($item);
            $em->flush();
        }

        return $this->redirectToRoute('app_home');
    }

    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }


}
