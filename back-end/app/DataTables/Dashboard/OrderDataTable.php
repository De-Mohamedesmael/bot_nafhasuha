<?php

namespace App\DataTables\Dashboard;

use App\Models\Order;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.orders.actions')
            ->editColumn('payment_method', function ($item) {
                return '<span class="alert alert-info">' . trans('dashboard.' . $item->payment_method) . '</span>';
            })
            ->editColumn('store', function ($item) {
                return optional($item->store->translate(app()->getLocale()))->name;
            })
            ->editColumn('status', function ($item) {
                return $item->status == 'open' ?
                    '<span class="alert alert-success">' . trans('dashboard.' . $item->status) . '</span>'
                    : '<span class="alert alert-danger">' . trans('dashboard.' . $item->status) . '</span>';
            })
            ->editColumn('delivery_time', function ($item) {
                return date('Y-m-d h:i a', $item->delivery_time);
            })
            ->editColumn('created_at', function ($item) {
                return date('Y-m-d h:i a', $item->created_at);
            })
            ->editColumn('final_price', function ($item) {
                return '<span class="alert alert-primary">' . $item->final_price . '</span>';
            })
            ->filterColumn('store', function ($query, $keyword) {
                $query->whereHas('store', function ($que) use ($keyword){
                    $que->whereTranslationLike('name', '%' . $keyword . '%');
                });
            })
            ->rawColumns(['action', 'store', 'payment_method', 'status', 'delivery_time', 'created_at', 'final_price'])
            ->addIndexColumn();
    }

    public function query(Order $model)
    {
        return $model->newQuery()->with(['user', 'store'])
            ->when(request('store_id'), function ($query){
                $query->where('store_id', request('store_id'));
            })
            ->when(request('from'), function ($query){
                $query->whereDate('created_at', '>=', request('from'));
            })
            ->when(request('to'), function ($query){
                $query->whereDate('created_at', '<=', request('to'));
            })
            ->when(request('payment_method'), function ($query){
                $query->where('payment_method', request('payment_method'));
            })
            ->when(request('date'), function ($query){
                $query->whereDate('created_at', date('Y-m-d'));
            })
            ->when(request('type'), function ($query){
                $query->where('type', request('type'));
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
            'store' => new Column(['title' => trans('dashboard.store'), 'data' => 'store', 'orderable' => false]),
            'user' => new Column(['title' => trans('dashboard.user'), 'data' => 'user.name']),
            'phone' => new Column(['title' => trans('dashboard.phone'), 'data' => 'user.phone']),
            'products_count' => new Column(['title' => trans('dashboard.products_count'), 'data' => 'products_count']),
            'final_price' => new Column(['title' => trans('dashboard.final_price'), 'data' => 'final_price']),
            'payment_method' => new Column(['title' => trans('dashboard.payment_method'), 'data' => 'payment_method']),
            'status' => new Column(['title' => trans('dashboard.status'), 'data' => 'status']),
            'delivery_time' => new Column(['title' => trans('dashboard.delivery_time'), 'data' => 'delivery_time']),
            'created_at' => new Column(['title' => trans('dashboard.created_at'), 'data' => 'created_at']),
        ];
    }

    protected function filename()
    {
        return 'Order_' . date('YmdHis');
    }
}
