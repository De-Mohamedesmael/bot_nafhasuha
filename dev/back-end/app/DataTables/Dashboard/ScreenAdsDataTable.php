<?php

namespace App\DataTables\Dashboard;

use App\Models\Ads;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ScreenAdsDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.screenAds.actions')
            ->editColumn('is_active', function ($item) {
                return $item->is_active ?
                    '<span class="alert alert-success">' . trans('dashboard.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('dashboard.no') . '</span>';
            })
            ->rawColumns(['action', 'is_active']);
    }

    public function query(Ads $model)
    {
        return $model->newQuery()->where('name', 'screen')->withCount('slider');
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
            'id' => new Column(['title' => trans('dashboard.id'), 'data' => 'id', 'visible' => false]),
            'floor' => new Column(['title' => trans('dashboard.floor'), 'data' => 'floor']),
            'hall' => new Column(['title' => trans('dashboard.hall'), 'data' => 'hall']),
            'sort' => new Column(['title' => trans('dashboard.sort'), 'data' => 'sort']),
            'is_active' => new Column(['title' => trans('dashboard.is_active'), 'data' => 'is_active']),
            'slider' => new Column(['title' => trans('dashboard.slider'), 'data' => 'slider_count', 'orderable' => false, 'searchable' => false]),
        ];
    }

    protected function filename()
    {
        return 'Ads_' . date('YmdHis');
    }
}
