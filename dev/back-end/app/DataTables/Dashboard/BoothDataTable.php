<?php

namespace App\DataTables\Dashboard;

use App\Models\Store;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BoothDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.booths.actions')
            ->editColumn('logo', function ($item) {
                $id = $item->id;
                $img = $item->logo;
                return view('dashboard.components.image', compact('img', 'id'))->render();
            })
            ->editColumn('name', function ($item) {
                return optional($item->translate(app()->getLocale()))->name;
            })
            ->editColumn('is_active', function ($item) {
                return $item->is_active ?
                    '<span class="alert alert-success">' . trans('dashboard.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('dashboard.no') . '</span>';
            })
            ->editColumn('can_login', function ($item) {
                return $item->can_login ?
                    '<span class="alert alert-success">' . trans('dashboard.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('dashboard.no') . '</span>';
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', '%' . $keyword . '%');
            })
            ->rawColumns(['action', 'name', 'logo', 'is_active', 'can_login'])
            ->addIndexColumn();
    }

    public function query(Store $model)
    {
        return $model->newQuery()->booth()->with('category')->withCount(['products', 'orders']);
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'title' => trans('dashboard.action')])
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
//            'id' => new Column(['title' => trans('dashboard.store_number'), 'data' => 'id']),
            'name' => new Column(['title' => trans('dashboard.name'), 'data' => 'name', 'orderable' => false]),
            'is_active' => new Column(['title' => trans('dashboard.is_active'), 'data' => 'is_active']),
            'email' => new Column(['title' => trans('dashboard.email'), 'data' => 'email']),
            'password' => new Column(['title' => trans('dashboard.password'), 'data' => 'password_text']),
            'floor' => new Column(['title' => trans('dashboard.floor'), 'data' => 'floor']),
            'hall' => new Column(['title' => trans('dashboard.hall'), 'data' => 'hall']),
            'can_login' => new Column(['title' => trans('dashboard.can_login'), 'data' => 'can_login']),
            'logo' => new Column(['title' => trans('dashboard.logo'), 'data' => 'logo']),
            'category' => new Column(['title' => trans('dashboard.category'), 'data' => 'category.name', 'orderable' => false]),
            'products' => new Column(['title' => trans('store.products'), 'data' => 'products_count', 'orderable' => false, 'searchable' => false]),
            'orders' => new Column(['title' => trans('store.orders'), 'data' => 'orders_count', 'orderable' => false, 'searchable' => false]),
        ];
    }

    protected function filename()
    {
        return 'Store_' . date('YmdHis');
    }
}
