<?php

namespace App\DataTables\Dashboard;

use App\Models\Contact;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContactDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.contacts.actions')
            ->editColumn('message', function ($item){
                $id = $item->id;
                $desc = $item->message;
                return view('dashboard.components.text', compact('id', 'desc'))->render();
            })
            ->rawColumns(['action', 'message']);
    }

    public function query(Contact $model)
    {
        return $model->newQuery()->with('user');
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
            'user' => new Column(['title' => trans('dashboard.user'), 'data' => 'user.name']),
            'phone' => new Column(['title' => trans('dashboard.phone'), 'data' => 'phone']),
            'message' => new Column(['title' => trans('dashboard.message'), 'data' => 'message']),
        ];
    }

    protected function filename()
    {
        return 'Contact_' . date('YmdHis');
    }
}
