<?php

namespace App\DataTables\Dashboard;

use App\Models\Category;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'dashboard.view.categories.actions')
            ->editColumn('parent', function ($item) {
                return optional($item->parent)->name;
            })
            ->editColumn('icon', function ($item) {
                $id = $item->id;
                $img = $item->icon;
                return view('dashboard.components.image', compact('img', 'id'))->render();
            })
            ->rawColumns(['icon', 'action', 'parent'])
            ->addIndexColumn();
    }

    public function query(Category $model)
    {
        return $model->newQuery()->with('parent');
    }

    public function html()
    {
        $lang = session()->get('locale') == 'ar' ? 'Arabic' : 'English';
        $builder = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'order' => [0, 'desc'],
                'language' => [
                    'url' => url("//cdn.datatables.net/plug-ins/1.10.12/i18n/$lang.json"),
                ],
            ]);
        if (majorAdmin()) $builder = $builder->addAction(['width' => '80px', 'printable' => false, 'title' => trans('dashboard.action')]);
        return $builder;
    }

    protected function getColumns()
    {
        return [
            'id' => new Column(['title' => 'id', 'data' => 'id', 'visible' => false]),
            'name' => new Column(['title' => trans('dashboard.name'), 'data' => 'name', 'orderable' => false]),
            'sort' => new Column(['title' => trans('dashboard.sort'), 'data' => 'sort']),
            'icon' => new Column(['title' => trans('dashboard.icon'), 'data' => 'icon']),
            'related' => new Column(['title' => trans('dashboard.related'), 'data' => 'parent', 'orderable' => false, 'searchable' => false]),
        ];
    }

    protected function filename()
    {
        return 'Category_' . date('YmdHis');
    }
}
