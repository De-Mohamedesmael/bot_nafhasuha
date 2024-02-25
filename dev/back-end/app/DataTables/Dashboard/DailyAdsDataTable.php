<?php

namespace App\DataTables\Dashboard;

use App\Models\Ads;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DailyAdsDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.dailyAds.actions')
            ->editColumn('is_active', function ($item) {
                return $item->is_active ?
                    '<span class="alert alert-success">' . trans('dashboard.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('dashboard.no') . '</span>';
            })
            ->editColumn('image', function ($item) {
                $id = $item->id.'-image';
                $img = $item->image->file;
                return view('dashboard.components.image', compact('img', 'id'))->render();
            })
            ->editColumn('store', function ($item) {
                return $item->store ? $item->store->name : '-';
            })
            ->rawColumns(['action', 'is_active', 'image', 'store']);
    }

    public function query(Ads $model)
    {
        return $model->newQuery()->where('name', 'daily')->with(['image', 'store']);
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
            'is_active' => new Column(['title' => trans('dashboard.is_active'), 'data' => 'is_active']),
            'store' => new Column(['title' => trans('dashboard.store'), 'data' => 'store', 'searchable' => false, 'orderable' => false]),
            'created_at' => new Column(['title' => trans('dashboard.created_at'), 'data' => 'created_at']),
            'image' => new Column(['title' => trans('dashboard.image'), 'data' => 'image', 'orderable' => false, 'searchable' => false]),
        ];
    }

    protected function filename()
    {
        return 'Ads_' . date('YmdHis');
    }
}
