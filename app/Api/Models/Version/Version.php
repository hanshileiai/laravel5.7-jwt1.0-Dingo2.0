<?php

namespace App\Api\Models\Version;

use App\Api\Models\BaseModel;

class Version extends BaseModel
{
    protected $table = 'versions';
    protected $fillable = ['os_type','version_name','version_code','app_url','description','pop_status','force_update'];

}
