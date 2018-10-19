<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OneWord
 * @package App\Models
 * @property int $id
 * @property int $hitokoto_id
 * @property string $hitokoto
 * @property string $type
 * @property string $from
 * @property string $creator
 * @property int $create_time
 */
class OneWord extends Model
{
    protected $table   = 'one_words';
    protected $guarded = ['s'];
}
