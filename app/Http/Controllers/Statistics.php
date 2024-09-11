<?php
// app/Http/Controllers/Statistics.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Statistics extends Controller
{
    public function index()
    {
        $data = DB::table('orders')
            ->select([
                'article as Article',
                DB::raw("
                    COUNT(DISTINCT CASE WHEN status IN ('Новый', 'Принят', 'Недозвон', 'Отмена', 'В пути', 'Доставлен', 'Выполнен', 'Возврат', 'Подмены') THEN ID_number ELSE NULL END) AS Lead,
                    COUNT(DISTINCT CASE WHEN status IN ('Принят') THEN ID_number ELSE NULL END) AS Qabul,
                    COUNT(DISTINCT CASE WHEN status IN ('Отмена') THEN ID_number ELSE NULL END) AS Otkaz,
                    COUNT(DISTINCT CASE WHEN status IN ('В пути', 'EMU') THEN ID_number ELSE NULL END) AS Yolda,
                    COUNT(DISTINCT CASE WHEN status IN ('Доставлен') THEN ID_number ELSE NULL END) AS Yetkazildi,
                    COUNT(DISTINCT CASE WHEN status IN ('Выполнен') THEN ID_number ELSE NULL END) AS Sotildi,
                    COUNT(DISTINCT CASE WHEN status IN ('Возврат') THEN ID_number ELSE NULL END) AS QaytibKeldi
                ")
            ])
            ->where('source', 'btd')
            ->groupBy('article')
            ->get();

        return view('statistics', ['data' => $data]);
    }
}
