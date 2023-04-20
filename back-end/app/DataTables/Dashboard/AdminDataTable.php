<?php

namespace App\DataTables\Dashboard;

use App\Models\Admin;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AdminDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.admins.actions')
            ->editColumn('image', function ($item) {
                $id = $item->id;
                $img = $item->image;
                return view('dashboard.components.image', compact('img', 'id'))->render();
            })
            ->editColumn('type', function ($item) {
                return trans('dashboard.'.$item->type);
            })
            ->rawColumns(['action', 'image', 'type']);
    }

    public function query(Admin $model)
    {
        return $model->newQuery()
            ->when(!majorAdmin(), function ($query){
                $query->where('type', 'normal');
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
            'name' => new Column(['title' => trans('dashboard.name'), 'data' => 'name']),
            'email' => new Column(['title' => trans('dashboard.email'), 'data' => 'email']),
            'type' =>  new Column(['title' => trans('dashboard.type'), 'data' => 'type',
                'visible' => majorAdmin()]),
            'image' => new Column(['title' => trans('dashboard.image'), 'data' => 'image']),
        ];
    }

    protected function filename()
    {
        return 'Admin_' . date('YmdHis');
    }
}
