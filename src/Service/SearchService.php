<?php

namespace App\Service;

use App\Entity\ProductCategory;

class SearchService
{

    /*** @var int */
    public $page = 1;

    /*** @var string */
    public $q = '';

    /*** @var ProductCategory */
    public $categories;

}