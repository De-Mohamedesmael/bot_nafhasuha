<?php

namespace App\DataTables\Dashboard;

use App\Models\CoinPackage;
use App\Models\CoinPackageOrder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CoinPackageOrderDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.coinPackageOrders.actions')
            ->editColumn('coin_package', function ($item) {
                return optional($item->coin_package)['name_'.app()->getLocale()];
            })
            ->rawColumns(['action', 'coin_package']);
    }

    public function query(CoinPackageOrder $model)
    {
        return $model->newQuery()->with(['user', 'coin_package'])
            ->when(request('date'), function ($query){
                $query->whereDate('created_at', date('Y-m-d'));
            });
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
            'id' => new Column(['title' => 'id', 'data' => 'id', 'visible' => false]),
            'coin_package' => new Column(['title' => trans('dashboard.coin_package'), 'data' => 'coin_package', 'orderable' => false, 'searchable'=> false]),
            'price' => new Column(['title' => trans('dashboard.price'), 'data' => 'price']),
            'coins' => new Column(['title' => trans('dashboard.coins'), 'data' => 'coins']),
            'user' => new Column(['title' => trans('dashboard.user'), 'data' => 'user.name']),
        ];
    }

    protected function filename()
    {
        return 'CoinPackage_' . date('YmdHis');
    }
}
