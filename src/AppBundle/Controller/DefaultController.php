<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('AppBundle:Item')->findBy(array(), array('category' => 'asc', 'label' => 'asc'));

        return $this->render(':default:item.html.twig', array('items' => $items));


    }

    public function listCategoriesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render(':default:categorie.html.twig', array('categories' => $categories));
    }

    public function addCategoryAction(Request $request)
    {
        $cat = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();

            return $this->redirectToRoute('app_categories');
        }

        return $this->render(':default:newCategory.html.twig', array(
            'category' => $cat,
            'form' => $form->createView(),
        ));

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

        return $this->render(':default:newItem.html.twig', array(
            'category' => $item,
            'form' => $form->createView(),
        ));

    }


    public function resumeAction()
    {
        return $this->render(':default:resume.html.twig');


    }
}
