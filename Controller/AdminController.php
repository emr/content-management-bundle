<?php

namespace Emr\CMBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use Emr\CMBundle\Configuration\EntityConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class AdminController extends BaseAdminController
{
    use SecurityMiddleware;
    use UserMiddleware;

    /**
     * @var array
     */
    protected $cmsConfig;

    /**
     * @var EntityConfig
     */
    protected $cmsEntityConfig;

    /**
     * @Route("/", name="easyadmin")
     */
    public function indexAction(Request $request)
    {
        try {
            return parent::indexAction($request);
        } catch (ForbiddenActionException $e) {
            return $this->redirectToBackendHomepage();
        }
    }

    public function initialize(Request $request)
    {
        $this->cmsConfig = $this->getParameter('emr_cm.config');
        $this->cmsEntityConfig = $this->get('emr_cm.entity_config');

        parent::initialize($request);

        $this->checkPermissions($this->entity, true);
        if ($request->query->get('entity'))
            $this->checkEntityActionPermissions();
    }

    public function dashboardAction()
    {
        $pages = $this->em->getRepository($this->cmsEntityConfig->getClass(EntityConfig::PAGE))->findAll();

//        $entities = [
//            [
//                'name' => 'Model',
//                'title' => 'Models',
//                'identifier' => 'code',
//                'rows' => $this->em->getRepository(Model::class)->findBy([], ['id' => 'DESC'], 10)
//            ],
//            [
//                'name' => 'News',
//                'identifier' => 'title',
//                'rows' => $this->em->getRepository(News::class)->findBy([], ['id' => 'DESC'], 10)
//            ],
//        ];

        return $this->renderTemplate('dashboard', '@EmrCM/admin/dashboard.html.twig', [
            'edit_pages' => $pages,
//            'edit_entities' => $entities,
        ]);
    }

    public function sectionAction()
    {
        $pages = $this->em->getRepository($this->cmsEntityConfig->getClass(EntityConfig::PAGE))->findBy([
            'key' => $this->request->query->get('_key')
        ]);

        return $this->renderTemplate('section', '@EmrCM/admin/section_list.html.twig', [
            'pages' => $pages,
            'sections' => $this->cmsEntityConfig->getSections(),
        ]);
    }

    protected function renderTemplate($actionName, $templatePath, array $parameters = array())
    {
        $constant = $this->em->getRepository($this->cmsEntityConfig->getClass(EntityConfig::LOCALIZED_CONSTANT))->find($this->request->getLocale());
        $parameters['menu_pages'] = $this->em->getRepository(
            $this->cmsEntityConfig->getClass(EntityConfig::PAGE)
        )
            ->findBy(
                [
                    'constant' => $constant,
                    'menuView' => true,
                ],
                [
                    'menuPosition' => 'ASC',
                ]
            )
        ;
        $parameters['cms_config'] = $this->cmsConfig;
        return $this->render($templatePath, $parameters);
    }
}