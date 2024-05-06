<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategory extends Model
{
    protected $table            = 'products_category';
    protected $primaryKey       = 'id';

    protected $allowedFields = ['nama_category'];
}
