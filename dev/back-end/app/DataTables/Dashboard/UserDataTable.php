<?php

namespace App\DataTables\Dashboard;

use App\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.users.actions')
            ->editColumn('gander', function ($item) {
                return $item->gander == 0 ? trans('dashboard.male') : trans('dashboard.female');
            })
            ->rawColumns(['action', 'gander']);
    }

    public function query(User $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'title' => trans('dashboard.action')])
            ->parameters([
                'order' => [7, 'desc'],
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
            'email' => new Column(['title' => trans('dashboard.email'), 'data' => 'email']),
            'phone' => new Column(['title' => trans('dashboard.phone'), 'data' => 'phone']),
            'address' => new Column(['title' => trans('dashboard.address'), 'data' => 'address']),
            'gender' => new Column(['title' => trans('dashboard.gender'), 'data' => 'gander']),
            'coins' => new Column(['title' => trans('dashboard.coins'), 'data' => 'coins']),
            'total_orders_cost' => new Column(['title' => trans('dashboard.total_orders_cost'), 'data' => 'total_orders_cost']),
        ];
    }

    protected function filename()
    {
        return 'User_' . date('YmdHis');
    }
}
