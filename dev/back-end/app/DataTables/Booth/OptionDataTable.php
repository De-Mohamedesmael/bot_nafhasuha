<?php

namespace App\DataTables\Booth;

use App\Models\Option;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OptionDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'booth.view.options.actions');
    }

    public function query(Option $model)
    {
        return $model->newQuery()->where('store_id', auth('booth')->id())
            ->with('attribute');
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'title' => trans('booth.action')])
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
            'name_ar' => new Column(['title' => trans('booth.name_ar'), 'data' => 'name_ar']),
            'name_en' => new Column(['title' => trans('booth.name_en'), 'data' => 'name_en']),
            'price' => new Column(['title' => trans('booth.price'), 'data' => 'price']),
            'quantity' => new Column(['title' => trans('booth.quantity'), 'data' => 'quantity']),
            'attribute' => new Column(['title' => trans('booth.attribute_id'), 'data' => 'attribute.name_' . app()->getLocale()]),
//            'section' => new Column(['title' => trans('booth.section_id'), 'data' => 'section.name']),
        ];
    }

    protected function filename()
    {
        return 'Option_' . date('YmdHis');
    }
}
