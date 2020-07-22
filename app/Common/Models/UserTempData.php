<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Temporary user data holder
 *
 * Class UserTempData
 *
 * @package App\Common\Models
 *
 */
class UserTempData extends User
{
    /**
     * @var string
     */
    protected $table = 'user_temp_data';

}
