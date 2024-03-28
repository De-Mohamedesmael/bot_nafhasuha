<?php

namespace App\DataTables\Booth;

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
            ->addColumn('action', 'booth.view.orders.actions')
            ->editColumn('payment_method', function ($item) {
                return '<span class="alert alert-info">' . trans('booth.' . $item->payment_method) . '</span>';
            })
            ->editColumn('status', function ($item) {
                return $item->status == 'open' ?
                    '<span class="alert alert-success">' . trans('booth.' . $item->status) . '</span>'
                    : '<span class="alert alert-danger">' . trans('booth.' . $item->status) . '</span>';
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
            ->rawColumns(['action', 'payment_method', 'status', 'delivery_time', 'created_at', 'final_price']);
    }

    public function query(Order $model)
    {
        return $model->newQuery()
            ->where('store_id', auth('booth')->id())->with(['user'])
            ->when(request('date'), function ($query) {
                $query->whereDate('created_at', date('Y-m-d'));
            });
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'title' => trans('booth.action')])
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
            'user' => new Column(['title' => trans('booth.user'), 'data' => 'user.name']),
            'phone' => new Column(['title' => trans('booth.phone'), 'data' => 'user.phone']),
            'products_count' => new Column(['title' => trans('booth.products_count'), 'data' => 'products_count']),
            'final_price' => new Column(['title' => trans('booth.final_price'), 'data' => 'final_price']),
            'payment_method' => new Column(['title' => trans('booth.payment_method'), 'data' => 'payment_method']),
            'status' => new Column(['title' => trans('booth.status'), 'data' => 'status']),
            'delivery_time' => new Column(['title' => trans('booth.delivery_time'), 'data' => 'delivery_time']),
            'created_at' => new Column(['title' => trans('booth.created_at'), 'data' => 'created_at']),
        ];
    }

    protected function filename()
    {
        return 'Order_' . date('YmdHis');
    }
}
