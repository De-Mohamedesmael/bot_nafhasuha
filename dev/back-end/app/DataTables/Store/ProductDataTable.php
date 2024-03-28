<?php

namespace App\DataTables\Store;

use App\Models\Product;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'store.view.products.actions')
            ->editColumn('img', 'store.components.image')
            ->editColumn('name', function ($item) {
                return optional($item->translate(app()->getLocale()))->name;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', '%' . $keyword . '%');
            })
            ->rawColumns(['img', 'action', 'name'])
            ->addIndexColumn();
    }

    public function query(Product $model)
    {
        return $model->newQuery()
            ->where('store_id', auth('store')->id())
            ->with(['section', 'attributes']);
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
            'img' => new Column(['title' => trans('store.img'), 'data' => 'img']),
            'sale_price' => new Column(['title' => trans('store.sale_price'), 'data' => 'sale_price']),
            'regular_price' => new Column(['title' => trans('store.regular_price'), 'data' => 'regular_price']),
            'quantity' => new Column(['title' => trans('store.quantity'), 'data' => 'quantity']),
            'section' => new Column(['title' => trans('store.section_id'), 'data' => 'section.name']),
        ];
    }

    protected function filename()
    {
        return 'Product_' . date('YmdHis');
    }
}
