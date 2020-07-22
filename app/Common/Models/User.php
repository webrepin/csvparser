<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Base user class
 *
 * Class User
 * @package App\Common\Models
 *
 * @property int $id
 * @property int $deleted_at
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $card
 */
class User extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'email',
        'first_name',
        'last_name',
        'card'
    ];

}
