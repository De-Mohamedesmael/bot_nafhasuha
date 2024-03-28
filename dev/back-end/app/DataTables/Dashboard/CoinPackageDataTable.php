<?php

namespace App\DataTables\Dashboard;

use App\Models\CoinPackage;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CoinPackageDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.coinPackages.actions')
            ->editColumn('is_active', function ($item) {
                return $item->is_active ?
                    '<span class="alert alert-success">' . trans('dashboard.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('dashboard.no') . '</span>';
            })
            ->rawColumns(['action', 'is_active']);
    }

    public function query(CoinPackage $model)
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
            'name_ar' => new Column(['title' => trans('dashboard.name_ar'), 'data' => 'name_ar']),
            'name_en' => new Column(['title' => trans('dashboard.name_en'), 'data' => 'name_en']),
            'price' => new Column(['title' => trans('dashboard.price'), 'data' => 'price']),
            'coins' => new Column(['title' => trans('dashboard.coins'), 'data' => 'coins']),
            'is_active' => new Column(['title' => trans('dashboard.is_active'), 'data' => 'is_active']),
        ];
    }

    protected function filename()
    {
        return 'CoinPackage_' . date('YmdHis');
    }
}
