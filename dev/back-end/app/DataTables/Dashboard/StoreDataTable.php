<?php

namespace App\DataTables\Dashboard;

use App\Models\Store;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StoreDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.stores.actions')
            ->editColumn('logo', function ($item) {
                $id = $item->id;
                $img = $item->logo;
                return view('dashboard.components.image', compact('img', 'id'))->render();
            })
            ->editColumn('banner', function ($item) {
                $id = $item->id.'-banner';
                $img = $item->banner;
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
            ->rawColumns(['action', 'name', 'logo', 'banner', 'is_active', 'can_login'])
            ->addIndexColumn();
    }

    public function query(Store $model)
    {
        return $model->newQuery()->store()->where('id', '<=', 330)
            ->with('category')->withCount(['products', 'orders']);
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'title' => trans('dashboard.action')])
            ->parameters([
                'order' => [0, 'asc'],
                'language' => [
                    'url' => url("//cdn.datatables.net/plug-ins/1.10.12/i18n/$lang.json"),
                ],
            ]);
    }

    protected function getColumns()
    {
        return [
            'id' => new Column(['title' => trans('dashboard.store_number'), 'data' => 'id']),
            'name' => new Column(['title' => trans('dashboard.name'), 'data' => 'name', 'orderable' => false]),
            'is_active' => new Column(['title' => trans('dashboard.is_active'), 'data' => 'is_active']),
            'email' => new Column(['title' => trans('dashboard.email'), 'data' => 'email']),
            'password' => new Column(['title' => trans('dashboard.password'), 'data' => 'password_text']),
            'can_login' => new Column(['title' => trans('dashboard.can_login'), 'data' => 'can_login']),
            'logo' => new Column(['title' => trans('dashboard.logo'), 'data' => 'logo']),
            'banner' => new Column(['title' => trans('dashboard.banner'), 'data' => 'banner']),
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
