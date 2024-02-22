<?php

namespace App\DataTables\Dashboard;

use App\Models\BoothNumber;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BoothNumberDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.boothNumbers.actions');
    }

    public function query(BoothNumber $model)
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
            'floor' => new Column(['title' => trans('dashboard.floor'), 'data' => 'floor']),
            'hall' => new Column(['title' => trans('dashboard.hall'), 'data' => 'hall']),
            'number' => new Column(['title' => trans('dashboard.number'), 'data' => 'number']),
        ];
    }

    protected function filename()
    {
        return 'BoothNumber_' . date('YmdHis');
    }
}
