<?php

namespace App\DataTables;

use Illuminate\Support\Facades\View;

use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ServicesRequestDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('action',function ($service){
            return View::make('components.services.table-action',[
                'id' => $service->id 
            ]);
        })
        ->rawColumns(['action'])
        ->setRowId('id')
       ; 
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ServiceRequest $model): QueryBuilder
    {
        $service = request()->service;
        return $model->where('serviceable_id',$service->id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('servicesrequest-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                       
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
          
            Column::make('id'),
            Column::make('status'),
            Column::make('start_time'),
            Column::make('end_time'),
            Column::make('customer_id'),
            Column::make('created_at')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ServicesRequest_' . date('YmdHis');
    }
}
