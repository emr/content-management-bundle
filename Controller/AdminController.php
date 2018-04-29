<?php

namespace Emr\CMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Emr\CMBundle\Entity\Page;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class AdminController extends BaseAdminController
{
    use SecurityMiddleware;
    use PageMiddleware;
    use UserMiddleware;

    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function dashboardIndexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository(Page::class)->findBy(['constant' => $request->getLocale()]);

//        $entities = [
//            [
//                'name' => 'Model',
//                'title' => 'Models',
//                'identifier' => 'code',
//                'rows' => $em->getRepository(Model::class)->findBy([], ['id' => 'DESC'], 10)
//            ],
//            [
//                'name' => 'News',
//                'identifier' => 'title',
//                'rows' => $em->getRepository(News::class)->findBy([], ['id' => 'DESC'], 10)
//            ],
//        ];

        return $this->render('admin/dashboard.html.twig', [
            'edit_pages' => $pages,
//            'edit_entities' => $entities,
        ]);
    }

    public function sectionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository(Page::class)->findBy([
            'page' => $this->request->query->get('_page')
        ]);

        return $this->render('admin/section_list.html.twig', [
            'pages' => $pages,
        ]);
    }
}