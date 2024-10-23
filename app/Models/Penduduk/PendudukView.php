<?php

namespace App\Models\Penduduk;

use App\Services\EnumQueryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PendudukView extends Model
{
    use HasFactory;

    protected $table = 'penduduk_view';

    public static function getView($key, $wilayahId = null)
    {

        $enumClass = EnumQueryService::getEnumClassByKeyName(key: $key);

        $subQuery = EnumQueryService::getSubQuery($enumClass, $key);

        DB::statement('CALL sp_create_penduduk_view(?,?,?)', [$subQuery, $key, $wilayahId]);

        return static::query()
            ->from('penduduk_view');
    }
}
