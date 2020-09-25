<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AccountInfo extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'account_info';

    protected $fillable = [
        'username', 'name','gender', 'birthday','email'
    ];

    public $sortable = [
        'id',
        'username',
        'name',
        'gender',
        'birthday',
        'email',
        ];
}
