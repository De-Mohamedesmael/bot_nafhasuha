<?php

namespace App\DataTables\Store;

use App\Models\Area;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AreaDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'store.view.areas.actions')
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', '%' . $keyword . '%');
            });
    }

    public function query(Area $model)
    {
        return $model->newQuery()
                ->wherehas('city', function($q){
                         $q->where('store_id', auth('store')->id());
                })->with('city');
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
            'name' => new Column(['title' => trans('store.name'), 'data' => 'name']),
            'city' => new Column(['title' => trans('store.city'), 'data' => 'city.name']),
        ];
    }

    protected function filename()
    {
        return 'Area_' . date('YmdHis');
    }
}
