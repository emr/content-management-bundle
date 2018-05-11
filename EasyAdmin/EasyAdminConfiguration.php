<?php

namespace Emr\CMBundle\EasyAdmin;

use Emr\CMBundle\Configuration\EntityConfig;
use Emr\CMBundle\Exception\SameEntityNameException;
use Illuminate\Support\Arr;

/**
 * @todo 1 kere kullanım varsa cache'ler kaldırılacak.
 */
class EasyAdminConfiguration
{
    /**
     * @var array
     */
    private $settings;

    /**
     * @var EntityConfig
     */
    private $entityConfig;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var EasyAdminEntityNaming
     */
    private $naming;

    public function __construct(array $settings, EntityConfig $entityConfig, EasyAdminEntityNaming $naming)
    {
        $this->naming       = $naming;
        $this->settings     = $settings;
        $this->entityConfig = $entityConfig;
    }

    private function setAndGetEntityName(string $class): string
    {
        return $this->naming->set(
            $this->settings['entities'][$class] ?? $class,
            null,
            true
        );
    }

    public function getAutoConfigEntities(): array
    {
        if (!$this->settings['auto_config_sections']) return [];
        if (isset($this->config['auto_config_entities'])) return $this->config['auto_config_entities'];

        return $this->config['auto_config_entities'] = array_merge(
            $this->getSectionEntities(),
            $this->getAdminEntities()
        );
    }

    public function getAdminEntities(): array
    {
        if (isset($this->config['admin_entities'])) return $this->config['admin_entities'];
        $this->config['admin_entities'] = [];

        foreach ($this->entityConfig->getAdmins() as $adminClass => $settings)
        {
            try {
                $name = $this->setAndGetEntityName($adminClass);
            } catch (SameEntityNameException $e) {
                continue;
            }

            $this->config['admin_entities'][$name] = [
                'show' => [
                    'fields' => $this->entityConfig->getFields($adminClass, function($field) {
                        return in_array('show', $field['actions']) && !in_array('show', $field['disabled_actions']);
                    })
                ],
                'form' => [
                    'fields' => $this->entityConfig->getFields($adminClass, function($field) {
                        return in_array('form', $field['actions']) && !in_array('form', $field['disabled_actions']);
                    })
                ],
                'list' => [
                    'fields' => $this->entityConfig->getFields($adminClass, function($field) {
                        return in_array('list', $field['actions']) && !in_array('list', $field['disabled_actions']);
                    })
                ],
                'new' => [
                    'fields' => $this->entityConfig->getFields($adminClass, function($field) {
                        return in_array('new', $field['actions']) && !in_array('new', $field['disabled_actions']);
                    })
                ],
                'edit' => [
                    'fields' => $this->entityConfig->getFields($adminClass, function($field) {
                        return in_array('edit', $field['actions']) && !in_array('edit', $field['disabled_actions']);
                    })
                ]
            ];

            foreach ($settings as $key => $value)
                Arr::set($this->config['admin_entities'][$name], $key, $value);
        }

        return $this->config['admin_entities'];
    }

    public function getSectionEntities(): array
    {
        if (isset($this->config['section_entities'])) return $this->config['section_entities'];
        $this->config['section_entities'] = [];

        foreach ($this->entityConfig->getSections() as $section)
        {
            $name = $this->setAndGetEntityName($section['class']);

            $this->config['section_entities'][$name] = [
                'action_roles' => [
                    'new' => 'ROLE_SUPER_ADMIN',
                    'list' => 'ROLE_SUPER_ADMIN',
                    'delete' => 'ROLE_SUPER_ADMIN',
                ],
                'class' => $section['class'],
                'label' => $section['label'],
                'form' => [
                    'fields' => [
                        [
                            'role_require' => 'ROLE_SUPER_ADMIN',
                            'type' => 'section',
                            'label' => 'Advanced',
                            'css_class' => 'col-md-offset-2',
                        ],
                        [
                            'role_require' => 'ROLE_SUPER_ADMIN',
                            'property' => 'identifier',
                        ],
                        [
                            'role_require' => 'ROLE_SUPER_ADMIN',
                            'property' => 'pages',
                            'type_options' => [
                                'by_reference' => false
                            ]
                        ],
                        [
                            'role_require' => 'ROLE_SUPER_ADMIN',
                            'type' => 'section',
                            'label' => 'User values',
                            'css_class' => 'col-md-offset-2',
                        ],
                    ]
                ],
                'new' => [
                    'fields' => $this->entityConfig->getFields($section['class'], function($field) {
                        return (in_array('new', $field['actions']) && !in_array('new', $field['disabled_actions'])) || in_array('form', $field['actions']);
                    })
                ],
                'edit' => [
                    'fields' => array_merge(
                        $this->entityConfig->getFields($section['class'], function($field) {
                            return (in_array('edit', $field['actions']) && !in_array('edit', $field['disabled_actions'])) || in_array('form', $field['actions']);
                        })
                    )
                ]
            ];

            foreach ($this->entityConfig->getAdmin($section['class']) as $key => $value)
                Arr::set($this->config['section_entities'][$name], $key, $value);
        }

        return $this->config['section_entities'];
    }

    public function getConstantEntities(): array
    {
        return $this->config['constant_entities'] ?? $this->config['constant_entities'] = [
            $this->naming->get(EasyAdminEntityNaming::CONSTANT) => [
                'class' => $this->entityConfig->getClass(EntityConfig::CONSTANT),
                'action_roles' => [
                    'new' => 'ROLE_ADMIN',
                    'list' => 'ROLE_ADMIN',
                    'delete' => 'ROLE_ADMIN',
                ],
                'form' => [
                    'title' => 'Settings',
                    'fields' => $this->entityConfig->getFields(EntityConfig::CONSTANT, function($field) {
                        return in_array('edit', $field['actions']) && !in_array('edit', $field['disabled_actions']);
                    })
                ]
            ],
            $this->naming->get(EasyAdminEntityNaming::LOCALIZED_CONSTANT) => [
                'class' => $this->entityConfig->getClass(EntityConfig::LOCALIZED_CONSTANT),
                'action_roles' => [
                    'new' => 'ROLE_ADMIN',
                    'delete' => 'ROLE_ADMIN',
                ],
                'templates' => [
                    'list' => '@EmrCM/admin/list_locale.html.twig'
                ],
                'list' => [
                    'title' => 'Localized settings',
                ],
                'new' => [
                    'fields' => [
                        [
                            'property' => 'locale',
                            'type' => 'locale',
                            'role_require' => 'ROLE_SUPER_ADMIN',
                        ],
                        [
                            'property' => 'constant',
                            'type_options' => ['required' => true],
                            'role_require' => 'ROLE_SUPER_ADMIN',
                        ]
                    ]
                ],
                'edit' => [
                    'fields' => array_merge(
//                        [
//                            [
//                                'type' => 'section',
//                                'label' => 'User values',
//                                'css_class' => 'col-md-offset-2',
//                            ],
//                        ],
                        $this->entityConfig->getFields(EntityConfig::LOCALIZED_CONSTANT, function($field) {
                            return in_array('edit', $field['actions']) && !in_array('edit', $field['disabled_actions']) && $field['property'] != 'locale';
                        }),
                        [
                            [
                                'type' => 'section',
                                'label' => 'Advanced',
                                'css_class' => 'col-md-offset-2',
                                'role_require' => 'ROLE_SUPER_ADMIN',
                            ],
                            [
                                'property' => 'locale',
                                'type' => 'locale',
                                'role_require' => 'ROLE_SUPER_ADMIN',
                            ],
                        ]
                    )
                ]
            ],
        ];
    }

    public function getPageEntities(): array
    {
        return $this->config['page_entities'] ?? $this->config['page_entities'] = [
            $this->naming->get(EasyAdminEntityNaming::PAGE_ADMIN) => [
                'role_require' => 'ROLE_SUPER_ADMIN',
                'class' => $this->entityConfig->getClass(EntityConfig::PAGE),
                'list' => [
                    'title' => 'Pages',
                    'fields' => [
                        [
                            'property'  => 'key',
                            'type'      => 'text',
                        ],
                        'locale',
                    ]
                ],
                'form' => [
                    'fields' => [
                        [
                            'property' => 'key',
                        ],
                        [
                            'property'      => 'constant',
                            'label'         => 'Locale',
                            'type_options'  => [
                                'required' => true
                            ]
                        ],
                        [
                            'property'      => 'name',
                            'type'          => 'text',
                            'type_options'  => [
                                'required' => true
                            ]
                        ],
                        'menuView',
                        'menuPosition',
                    ]
                ],
                'new' => [
                    'fields' => [
//                        [
//                            'type' => 'section',
//                            'label' => 'Sections',
//                            'css_class' => 'col-md-offset-2',
//                        ],
                        [
                            'property' => 'sections',
                            'type' => 'Emr\CMBundle\Form\Admin\SectionType',
                        ]
                    ]
                ],
                'edit' => [
                    'fields' => $this->entityConfig->getSections()
                ]
            ],
            $this->naming->get(EasyAdminEntityNaming::PAGE_LAYOUT) => [
                'class' => $this->entityConfig->getClass(EntityConfig::PAGE),
                'disabled_actions' => ['list', 'delete', 'search', 'new'],
                'list' => [
                    'title' => 'Page layout',
                    'fields' => [
                        [
                            'property'  => 'key',
                            'type'      => 'text'
                        ],
                        'locale'
                    ]
                ]
            ],
        ];
    }

    public function getEntities(): array
    {
        return $this->config['entities'] ?? $this->config['entities'] = array_merge(
            $this->getConstantEntities(),
            $this->getPageEntities(),
            $this->getAutoConfigEntities()
        );
    }

    public function getEntity(string $name): array
    {
        return $this->getEntities()[$name];
    }

    public function getMenu(): array
    {
        return $this->config['menu'] ?? $this->config['menu'] = array_merge(
            [
                [
                    'label' => 'Dashboard',
                    'icon' => 'dashboard',
                    'entity' => $this->naming->get(EasyAdminEntityNaming::CONSTANT), // herhangi bir entity
                    'params' => [
                        'action' => 'dashboard',
                    ],
                    'default' => true,
                ],
                [
                    'label' => 'General settings',
                    'icon' => 'cog',
                    'entity' => $this->naming->get(EasyAdminEntityNaming::CONSTANT),
                    'params' => [
                        'action' => 'edit',
                    ]
                ],
                [
                    'label' => 'Localized settings',
                    'icon' => 'cog',
                    'entity' => $this->naming->get(EasyAdminEntityNaming::LOCALIZED_CONSTANT),
                ],
                [
                    'label' => 'Add localized constant',
                    'icon' => 'cog',
                    'entity' => $this->naming->get(EasyAdminEntityNaming::LOCALIZED_CONSTANT),
                    'role_require' => 'ROLE_SUPER_ADMIN',
                    'params' => [
                        'action' => 'new'
                    ]
                ],
                [
                    'label' => 'Pages',
                ],
                [
                    'embed_pages' => true,
//                'label' => '%%label%%',
                    'entity' => $this->naming->get(EasyAdminEntityNaming::PAGE_LAYOUT),
                    'params' => [
                        'action' => 'section',
//                    '_page' => '%%key%%',
                    ]
                ],
                [
                    'label' => 'Advanced',
                    'icon' => 'eye-close',
                    'role_require' => 'ROLE_ADMIN',
                ],
                [
                    'label' => 'Pages',
                    'entity' => $this->naming->get(EasyAdminEntityNaming::PAGE_ADMIN),
                    'params' => [
                        'action' => 'list',
                    ],
                    'role_require' => 'ROLE_SUPER_ADMIN',
                ],
                [
                    'label' => 'Sections',
                    'children' => array_keys($this->getSectionEntities()),
                    'role_require' => 'ROLE_SUPER_ADMIN',
                ],
            ]
//            [
//                'label' => 'Users',
//                'entity' => 'User',
////                'role_require' => 'ROLE_ADMIN',
//            ]
        );
    }

    public function getDesign(): array
    {
        return $this->config['design'] ?? $this->config['design'] = [
            'assets' => [
                'css' => [
                    'bundles/emrcm/easyadmin-improvements.css'
                ]
            ],
            'templates' => [
                'menu' => '@EmrCM/admin/menu.html.twig',
            ],
            'menu' => $this->getMenu()
        ];
    }

    public function getFullConfiguration(): array
    {
        return $this->config['full'] ?? $this->config['full'] = [
            'design'    => $this->getDesign(),
            'entities'  => $this->getEntities(),
        ];
    }
}