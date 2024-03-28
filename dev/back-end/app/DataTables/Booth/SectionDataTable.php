<?php

namespace App\DataTables\Booth;

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
            ->addColumn('action', 'booth.view.sections.actions');
    }

    public function query(Section $model)
    {
        return $model->newQuery()->where('store_id', auth('booth')->id());
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'title' => trans('booth.action')]);
    }

    protected function getColumns()
    {
        return [
            'name' => new Column(['title' => trans('booth.name'), 'data' => 'name']),
            'wall' => new Column(['title' => trans('booth.wall'), 'data' => 'wall']),
            'position' => new Column(['title' => trans('booth.position'), 'data' => 'position']),
        ];
    }

    protected function filename()
    {
        return 'Section_' . date('YmdHis');
    }
}
