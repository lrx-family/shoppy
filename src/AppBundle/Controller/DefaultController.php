<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $items = $em->getRepository('AppBundle:Item')->findBy(array(), array('category' => 'asc','label'=>'asc'));

        return $this->render(':default:item.html.twig', array('items'=>$items));


    }

    public function listCategoriesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render(':default:categorie.html.twig', array('categories'=>$categories));
    }


}
