<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name - Имя пользователя
 * @property int $from_person_id - Идентификатор отправителя подарка
 * @property int $to_person_id - Идентификатор получателя подарка
 */
class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'from_person_id',
        'to_person_id',
    ];

    public $timestamps = false;

    protected static function newFactory(): PersonFactory
    {
        return new PersonFactory();
    }
}
