<?php

namespace Emr\CMBundle\Controller;

use Func\EasyAdminGalleryBundle\Controller\Traits\DragDrop;
use Func\EasyAdminGalleryBundle\Controller\Traits\ListMosaic;

class GalleryAdminController extends AdminController
{
    use ListMosaic;
    use DragDrop;
}
