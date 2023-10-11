<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TableController extends Controller
{
    // Bootstrap Table
    public function table()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['name' => "Table Bootstrap"]];
        return view('/content/table/table-bootstrap/table-bootstrap', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    // Datatable Basic
    public function datatable_basic()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Datatable"], ['name' => "Basic"]];
        return view('/content/table/table-datatable/table-datatable-basic', ['breadcrumbs' => $breadcrumbs]);
    }

    // Datatable Basic
    public function datatable_advance()
    {
        // $breadcrumbs = [
        //     ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Datatable"], ['name' => "Advanced"]
        // ];
        // return view('/content/table/table-datatable/table-datatable-advance', [
        //     'breadcrumbs' => $breadcrumbs
        // ]);

        return view('/content/table/table-datatable/table-datatable-advance');
    }

    public function user_data(Request $request)
    {
        //print_r($request->all());

        $query = User::query();

        $columnNames = $request->input('columns');
        $orderColumnIndex = $request->input('order.0.column');
        $sortType = $request->input('order.0.dir');
        $columnName = $columnNames[$orderColumnIndex]['data'];

        $query->orderBy($columnName, $sortType);

        if (!empty($request->input('search.value'))) 
        {
            $searchValue = $request->input('search.value');
            $query->where('name', 'like', '%' . $searchValue . '%')
                ->orWhere('email', 'like', '%' . $searchValue . '%')
                ->orWhere('post', 'like', '%' . $searchValue . '%')
                ->orWhere('city', 'like', '%' . $searchValue . '%')
                ->orWhere('created_at', 'like', '%' . $searchValue . '%')
                ->orWhere('salary', 'like', '%' . $searchValue . '%');
        }

        
        if( !empty($request->input('columns.0.search.value')) )
        {
            $name = $request->input('columns.0.search.value');
            $query->where('name', 'like', '%' . $name . '%');
        }
        if(!empty($request->input('columns.1.search.value')))
        {
            $email = $request->input('columns.1.search.value');
            $query->Where('email', 'like', '%' . $email . '%');
        }
        if(!empty($request->input('columns.2.search.value')))
        {
            $post = $request->input('columns.2.search.value');
            $query->Where('post', 'like', '%' . $post . '%');
        }
        if(!empty($request->input('columns.3.search.value')))
        {
            $city = $request->input('columns.3.search.value');
            $query->Where('city', 'like', '%' . $city . '%');
        }
        if(!empty($request->input('columns.4.search.value')))
        {
            $createdAt = $request->input('columns.4.search.value');
            $query->Where('created_at', 'like', '%' . $createdAt . '%');
        }
        if(!empty($request->input('columns.5.search.value')))
        {
            $salary = $request->input('columns.5.search.value');
            $query->Where('salary', 'like', '%' . $salary . '%');
        }


        $totalRecords = $query->count();

        $start = $request->input('start');
        $length = $request->input('length');
        $query->offset($start)->paginate($length);
        
        $data = $query->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ]);

    }
}
