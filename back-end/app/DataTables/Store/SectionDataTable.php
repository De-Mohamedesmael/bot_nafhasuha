<?php

namespace App\DataTables\Store;

use App\Models\Section;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SectionDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'store.view.sections.actions');
    }

    public function query(Section $model)
    {
        return $model->newQuery()->where('store_id', auth('store')->id());
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'title' => trans('store.action')]);
    }

    protected function getColumns()
    {
        return [
            'name' => new Column(['title' => trans('store.name'), 'data' => 'name']),
            'wall' => new Column(['title' => trans('store.wall'), 'data' => 'wall']),
            'position' => new Column(['title' => trans('store.position'), 'data' => 'position']),
        ];
    }

    protected function filename()
    {
        return 'Section_' . date('YmdHis');
    }
}
