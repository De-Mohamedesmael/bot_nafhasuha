<?php

namespace App\DataTables\Store;

use App\Models\Coupon;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CouponDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'store.view.coupons.actions')
            ->editColumn('is_active', function ($item) {
                return $item->is_active ?
                    '<span class="alert alert-success">' . trans('store.yes') . '</span>'
                    : '<span class="alert alert-danger">' . trans('store.no') . '</span>';
            })
            ->rawColumns(['action', 'is_active']);
    }

    public function query(Coupon $model)
    {
        return $model->newQuery()->where('store_id', auth('store')->id());
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'title' => trans('store.action')])
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
            'name' => new Column(['title' => trans('store.name'), 'data' => 'name']),
            'code' => new Column(['title' => trans('store.code'), 'data' => 'code']),
            'discount' => new Column(['title' => trans('store.discount'), 'data' => 'discount']),
            'usage_limit' => new Column(['title' => trans('store.usage_limit'), 'data' => 'usage_limit']),
            'usage_finished' => new Column(['title' => trans('store.usage_finished'), 'data' => 'usage_finished']),
            'start_date' => new Column(['title' => trans('store.start_date'), 'data' => 'start_date']),
            'end_date' => new Column(['title' => trans('store.end_date'), 'data' => 'end_date']),
            'is_active' => new Column(['title' => trans('store.is_active'), 'data' => 'is_active']),
        ];
    }

    protected function filename()
    {
        return 'Coupon_' . date('YmdHis');
    }
}
