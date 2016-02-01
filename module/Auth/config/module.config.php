<?php

namespace Auth;

return array(
    'controller' => array(
        'classes' => array(
            'album/album' => 'Album\Controller\AlbumController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);