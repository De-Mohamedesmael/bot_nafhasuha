<?php

namespace App\DataTables\Dashboard;

use App\Models\Ads;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StandAdsDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.standAds.actions')
            ->editColumn('is_active', function ($item) {
                return $item->is_active ?
                    '<span class="alert alert-success">' . trans('dashboard.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('dashboard.no') . '</span>';
            })
            ->editColumn('front_slider', function ($item) {
                $id = $item->id.'-front';
                $img = optional($item->front_slider()->where('type', 'img')->first())->file;
                return view('dashboard.components.image', compact('id', 'img'));
            })
            ->editColumn('back_slider', function ($item) {
                $id = $item->id.'-back';
                $img = optional($item->back_slider()->where('type', 'img')->first())->file;
                return view('dashboard.components.image', compact('id', 'img'));
            })
            ->rawColumns(['action', 'is_active', 'front_slider', 'back_slider']);
    }

    public function query(Ads $model)
    {
        return $model->newQuery()->where('name', 'stand');
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
            'front_files' => new Column(['title' => trans('dashboard.front_files'), 'data' => 'front_slider', 'orderable' => false, 'searchable' => false]),
            'back_files' => new Column(['title' => trans('dashboard.back_files'), 'data' => 'back_slider', 'orderable' => false, 'searchable' => false]),
        ];
    }

    protected function filename()
    {
        return 'Ads_' . date('YmdHis');
    }
}
