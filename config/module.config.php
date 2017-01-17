<?php
return array(
    //Пути до сущностей
    'content_entity' => array(
        'content'       => '\Application\Entity\Content',
        'contentType'   => '\Application\Entity\ContentType',
        'lang'          => '\Application\Entity\Lang',
    ),
    
    //Маршрут для врзврата на предыдущую страницу (по умолчанию), если зашли с другого сайта
    'content_default_url' => 'home',
    
    //Справочник типов отображения
    'content_display_type' => array(
        1 => 'Short',
        2 => 'Partial',
        3 => 'Partial without header',
        4 => 'Full',
        5 => 'Full without header',
    ),
    //Справочник типов отображения
    'content_link_type' => array(
        1 => 'Link',
        2 => 'Button',
        3 => 'Header link',
    ),
    //Справочник сортировки по полям (название)
    'content_order_field' => array(
        1 => 'Date',
        2 => 'Name',
        3 => 'Id',
    ),
    //Справочник сортировки по полям (поле)
    'content_order_field_db' => array(
        1 => 'c.dt',
        2 => 'c.name',
        3 => 'c.contentId',
    ),
    //Настройки фильтр-форм
    'filter_form' => array(
        'content' => array(
            //Искать в
            'value0' => array(
                0   => 'Name',
                1   => 'Id',
                2   => 'Date',
                3   => 'Lang.',
                4   => 'Content type',
            ),
            //Искать в
            'field0' => array(
                0   => 'c.name',
                1   => 'c.contentId',
                2   => 'c.dt',
                3   => 'l.name',
                4   => 't.name'
            ),
            //Сортировать
            'value1' => array(
                0   => 'Name',
                1   => 'Id',
                2   => 'Date',
                3   => 'Lang.',
                4   => 'Content type',
            ),
            //Сортировать
            'field1' => array(
                0   => 'c.name',
                1   => 'c.contentId',
                2   => 'c.dt',
                3   => 'l.name',
                4   => 't.name'
            ), 
            //Значения по умолчанию
            'default' => array(
                'f_search'  => 0,
                'f_value'   => '',
                'f_exact'   => 0,
                'f_sort'    => 0,
                'f_desc'    => 1,
                'f_limit'   => 1,
            ),
        ),
        'contentType' => array(
            //Искать в
            'value0' => array(
                0   => 'Name',
                1   => 'Id'
            ),
            //Искать в
            'field0' => array(
                0   => 't.name',
                1   => 't.contentTypeId',
            ),
            //Сортировать
            'value1' => array(
                0   => 'Name',
                1   => 'Id',                
            ),
            //Сортировать
            'field1' => array(
                0   => 't.name',
                1   => 't.contentTypeId',
            ), 
            //Значения по умолчанию
            'default' => array(
                'f_search'  => 0,
                'f_value'   => '',
                'f_exact'   => 0,
                'f_sort'    => 0,
                'f_desc'    => 1,
                'f_limit'   => 1,
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'RtContentManager' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(),
        'factories' => array(
            'RtContentManager\Controller\Get'           => 'RtContentManager\Factory\Controller\GetControllerFactory',
            'RtContentManager\Controller\lang'          => 'RtContentManager\Factory\Controller\LangControllerFactory',
            'RtContentManager\Controller\Content'       => 'RtContentManager\Factory\Controller\ContentControllerFactory',
            'RtContentManager\Controller\ContentType'   => 'RtContentManager\Factory\Controller\ContentTypeControllerFactory',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'class_loader' => 'RtContentManager\Factory\ClassLoader',
            'link_previous_page' => 'RtContentManager\Factory\LinkPreviousPage',
        ),
    ),
    'router' => array(
        'routes' => array(
            'contentmanager' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/:lang/contentmanager',
                    'constraints' => array(
                        'lang' => '[a-z]{2}?',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'lang' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/lang',
                            'defaults' => array(
                               'controller' => 'RtContentManager\Controller\Lang',
                               'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'add' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/add',
                                    'defaults' => array(
                                       'controller' => 'RtContentManager\Controller\Lang',
                                       'action'     => 'add',
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/edit[/:id]',
                                    'defaults' => array(
                                       'controller' => 'RtContentManager\Controller\Lang',
                                       'action'     => 'edit',
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/delete[/:id]',
                                    'defaults' => array(
                                       'controller' => 'RtContentManager\Controller\Lang',
                                       'action'     => 'delete',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'contenttype' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/contenttype[/:page]',
                            'defaults' => array(
                                'controller' => 'RtContentManager\Controller\ContentType',
                                'action'     => 'index',
                                'page'       => 1,
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'add' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/add',
                                    'defaults' => array(
                                        'controller' => 'RtContentManager\Controller\ContentType',
                                        'action'     => 'add',
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/edit[/:id]',
                                    'defaults' => array(
                                        'controller' => 'RtContentManager\Controller\ContentType',
                                        'action'     => 'edit',
                                    ),
                                ),
                            ),
                            'copy' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/copy[/:id]',
                                    'defaults' => array(
                                        'controller' => 'RtContentManager\Controller\ContentType',
                                        'action'     => 'copy',
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/delete[/:id]',
                                    'defaults' => array(
                                        'controller' => 'RtContentManager\Controller\ContentType',
                                        'action'     => 'delete',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'content' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/content[/:page]',
                            'defaults' => array(
                                'controller' => 'RtContentManager\Controller\Content',
                                'action'     => 'index',
                                'page'       => 1,
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'add' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/add',
                                    'defaults' => array(
                                       'controller' => 'RtContentManager\Controller\Content',
                                       'action'     => 'add',
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/edit[/:id]',
                                    'defaults' => array(
                                       'controller' => 'RtContentManager\Controller\Content',
                                       'action'     => 'edit',
                                    ),
                                ),
                            ),
                            'copy' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/copy[/:id]',
                                    'defaults' => array(
                                       'controller' => 'RtContentManager\Controller\Content',
                                       'action'     => 'copy',
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/delete[/:id]',
                                    'defaults' => array(
                                       'controller' => 'RtContentManager\Controller\Content',
                                       'action'     => 'delete',
                                    ),
                                ),
                            ),
                            'geta' => array(
                                'type'    => 'literal',
                                'options' => array(
                                    'route'    => '/geta',
                                    'defaults' => array(
                                        'controller'=> 'RtContentManager\Controller\Get',
                                        'action'    => 'geta',
                                    ),
                                ),
                            ),
                            'getb' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/getb[/:id[/:layout[/:noback]]]',
                                    'defaults' => array(
                                        'controller'=> 'RtContentManager\Controller\Get',
                                        'action'    => 'getb',
                                        'layout'    => '',
                                        'noback'    => 0,
                                    ),
                                ),
                            ),
                            'getc' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/getc[/:id[/:layout[/:noback]]]',
                                    'defaults' => array(
                                        'controller'=> 'RtContentManager\Controller\Get',
                                        'action'    => 'getc',
                                        'layout'    => '',
                                        'noback'    => 0,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'rtcontent_script' => __DIR__ . '/../view/rtcontent_script.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);