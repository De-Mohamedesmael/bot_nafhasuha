<?php

namespace App\DataTables\Booth;

use App\Models\FAQ;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FaqDataTable extends DataTable
{

    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        return $dataTable
            ->addColumn('action', 'booth.view.faq.actions')
            ->editColumn('title_ar', function ($item) {
                return optional($item->translate('ar'))->title;
            })
            ->editColumn('title_en', function ($item) {
                return optional($item->translate('en'))->title;
            })
            ->editColumn('text_ar', function ($item) {
                $id = $item->id . '-ar';
                $desc = optional($item->translate('ar'))->text;
                return view('booth.components.text', compact('id', 'desc'))->render();
            })
            ->editColumn('text_en', function ($item) {
                $id = $item->id . '-en';
                $desc = optional($item->translate('en'))->text;
                return view('booth.components.text', compact('id', 'desc'))->render();
            })
            ->filterColumn('title_ar', function ($query, $keyword) {
                $query->whereTranslationLike('title', '%' . $keyword . '%');
            })
            ->filterColumn('title_en', function ($query, $keyword) {
                $query->whereTranslationLike('title', '%' . $keyword . '%');
            })
            ->rawColumns(['action', 'title_ar', 'title_en', 'text_en', 'text_ar'])
            ->addIndexColumn();
    }

    public function query(FAQ $model)
    {
        return $model->newQuery()
            ->where('store_id', auth('booth')->id());
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
            'title_ar' => new Column(['title' => trans('booth.title_ar'), 'data' => 'title_ar', 'orderable' => false]),
            'title_en' => new Column(['title' => trans('booth.title_en'), 'data' => 'title_en', 'orderable' => false]),
            'text_ar' => new Column(['title' => trans('booth.text_ar'), 'data' => 'text_ar', 'orderable' => false]),
            'text_en' => new Column(['title' => trans('booth.text_en'), 'data' => 'text_en', 'orderable' => false]),
        ];
    }

    protected function filename()
    {
        return 'FAQ_' . date('YmdHis');
    }
}
