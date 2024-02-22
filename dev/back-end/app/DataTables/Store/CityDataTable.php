<?php

namespace App\DataTables\Store;

use App\Models\City2;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CityDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'store.view.cities.actions')
            ->editColumn('name', function ($item) {
                return optional($item->translate(app()->getLocale()))->name;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', '%' . $keyword . '%');
            })
            ->rawColumns(['action','name']);
    }

    public function query(City2 $model)
    {
        return $model->newQuery()->where('store_id', auth('store')->id());
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'title' => trans('store.action')])
            ->parameters([
                'order' => [0, 'desc'],
                'language' => [
                    'url' => url("//cdn.datatables.net/plug-ins/1.10.12/i18n/$lang.json"),
                ],
            ]);
    }

    protected function getColumns()
    {
        return [
            'id' => new Column(['title' => 'id', 'data' => 'id', 'visible' => false]),
            'name' => new Column(['title' => trans('store.name'), 'data' => 'name', 'orderable' => false]),
            'shipping_rate' => new Column(['title' => trans('store.shipping_rate'), 'data' => 'shipping_rate'])
        ];
    }

    protected function filename()
    {
        return 'city_' . date('YmdHis');
    }
}
