<?php

namespace App\DataTables\Store;

use App\Models\Attribute;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AttributeDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'store.view.attributes.actions')
            ->editColumn('has_price', function ($item) {
                return $item->has_price ?
                    '<span class="alert alert-success">' . trans('store.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('store.no') . '</span>';
            })
            ->editColumn('has_quantity', function ($item) {
                return $item->has_quantity ?
                    '<span class="alert alert-success">' . trans('store.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('store.no') . '</span>';
            })
            ->editColumn('selection_type', function ($item) {
                return '<span class="alert alert-info">' . trans('store.' . $item->selection_type) . '</span>';
            })
            ->rawColumns(['action', 'has_price', 'has_quantity', 'selection_type']);
    }

    public function query(Attribute $model)
    {
        return $model->newQuery()->where('store_id', auth('store')->id())->with('section');
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
            'name_ar' => new Column(['title' => trans('store.name_ar'), 'data' => 'name_ar']),
            'name_en' => new Column(['title' => trans('store.name_en'), 'data' => 'name_en']),
            'has_price' => new Column(['title' => trans('store.has_price'), 'data' => 'has_price']),
            'has_quantity' => new Column(['title' => trans('store.has_quantity'), 'data' => 'has_quantity']),
            'selection_type' => new Column(['title' => trans('store.selection_type'), 'data' => 'selection_type']),
            'section' => new Column(['title' => trans('store.section_id'), 'data' => 'section.name']),
        ];
    }

    protected function filename()
    {
        return 'Attribute_' . date('YmdHis');
    }
}
