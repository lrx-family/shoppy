<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('AppBundle:Item')->findBy([], ['label' => 'asc']);

        return $this->render(':default:item.html.twig', ['items' => $items]);
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
            //j'ajoute la ctégorie'
            $em->persist($cat);
            $em->flush();
        }

        // maintenant je récupères toutes les catégories
        $categories = $em->getRepository('AppBundle:Category')->findBy([], ['label' => 'asc']);

        // et j'affiche ma page
        return $this->render(':default:categorie.html.twig', [
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    public function addItemAction(Request $request)
    {
        $item = new Item();
        $form = $this->createForm('AppBundle\Form\ItemType', $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render(':default:newItem.html.twig', [
            'category' => $item,
            'form' => $form->createView(),
        ]);
    }

    public function resumeAction()
    {
        return $this->render(':default:resume.html.twig');
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
        if ($item->getQuantity() > 0) {
            $item->setQuantity($item->getQuantity() - 1);
            $em->persist($item);
            $em->flush();
        }        return $this->redirectToRoute('app_home');
    }


}
