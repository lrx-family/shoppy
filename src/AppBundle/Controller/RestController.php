<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestController extends Controller
{
    public function listCategoriesAction(Request $request)
    {
        $keyword = $request->get('name_startsWith');
        $em = $this->getDoctrine()->getEntityManager();
        $categories = $em->getRepository('AppBundle:Category')
            ->createQueryBuilder('c')
            ->where('c.label LIKE :keyword')
            ->setParameter(':keyword', '%' . $keyword . '%')
            ->getQuery()
            ->getResult();

        $result = [];
        foreach ($categories as $category) {
            $result[] = $category->getLabel();
        }
        return new JsonResponse(
            $result
        );
    }

    public function deleteCategoriesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);

        // puis la supprimer
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('app_list_categories');
    }

}
