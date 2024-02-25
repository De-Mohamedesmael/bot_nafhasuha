<?php

namespace App\DataTables\Dashboard;

use App\Models\GiftBox;
use App\Models\Store;
use App\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GiftBoxDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.giftBoxes.actions')
            ->editColumn('floor', function ($item) {
                return $item->floor == 0 ? trans('dashboard.all') : $item->floor;
            })
            ->editColumn('stores', function ($item) {
                $id = $item->id . '-store';
                $names = Store::store()->whereIn('id', $item->stores)->get()->pluck('name');
                return view('dashboard.components.names', compact('names', 'id'))->render();
            })
            ->editColumn('users', function ($item) {
                $id = $item->id . '-user';
                $names = $item->users ? User::whereIn('id', $item->users)->pluck('name') : [];
                return view('dashboard.components.names', compact('names', 'id'))->render();
            })
            ->editColumn('is_active', function ($item) {
                return $item->is_active ?
                    '<span class="alert alert-success">' . trans('dashboard.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('dashboard.no') . '</span>';
            })
            ->rawColumns(['stores', 'action', 'users', 'floor', 'is_active']);
    }

    public function query(GiftBox $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        $builder = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax();
        if (majorAdmin()) $builder = $builder->addAction(['width' => '80px', 'printable' => false, 'title' => trans('dashboard.action')]);
        return $builder->parameters([
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
            'name' => new Column(['title' => trans('dashboard.name'), 'data' => 'name']),
            'floor' => new Column(['title' => trans('dashboard.floor'), 'data' => 'floor']),
            'hall' => new Column(['title' => trans('dashboard.hall'), 'data' => 'hall']),
            'stores' => new Column(['title' => trans('dashboard.stores'), 'data' => 'stores']),
            'code' => new Column(['title' => trans('dashboard.code'), 'data' => 'code']),
            'discount' => new Column(['title' => trans('dashboard.discount'), 'data' => 'discount']),
            'coins' => new Column(['title' => trans('dashboard.coins'), 'data' => 'coins']),
            'start_date' => new Column(['title' => trans('dashboard.start_date'), 'data' => 'start_date']),
            'end_date' => new Column(['title' => trans('dashboard.end_date'), 'data' => 'end_date']),
            'is_active' => new Column(['title' => trans('dashboard.is_active'), 'data' => 'is_active']),
            'users' => new Column(['title' => trans('dashboard.users'), 'data' => 'users']),
        ];
    }

    protected function filename()
    {
        return 'GiftBox_' . date('YmdHis');
    }
}
