<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $products = Product::with('category')->get();
            return DataTables::of($products)
                ->addIndexColumn()
                
                ->addColumn('action', function ($data) {
                        return
                        '<a class="btn btn-success text-white" data-target="update" href="'.route('product.show', $data->id).'" data-value="'.$data->id.'"><i class="zmdi zmdi-edit"></i></a>
                        <button class="btn btn-danger  text-white " onclick="confirmDelete(this)" data-target="delete" data-value="'.$data->id.'"><i class="fa fa-trash"></i></button>';
                })
                ->addColumn('createdAt', function($data){
                    return Carbon::parse($data->created_at)->format('d-m-Y H:i:s');
                })
                ->addColumn('updatedAt', function($data){
                    return Carbon::parse($data->updated_at)->format('d-m-Y H:i:s');
                })
                ->rawColumns([
                    'action',
                    'createAt'
                ])
                ->make(true);
        }
        if(auth()->user()->role->name != 'admin') return abort(403);
        return view('pages.products.index');
    }

    public function create()
    {
        $category = ProductCategory::get();
        if(auth()->user()->role->name != 'admin') return abort(403);
        return view('pages.products.create', compact('category'));
    }

    public function store(ProductRequest $request)
    {
        if(auth()->user()->role->name != 'admin') return abort(403);
        $validate = $request->validated();
        if($request->hasFile('image')){
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $destinationPath = public_path('/upload/products');
            $imageName = time().'.'.$extension;
            $file->move($destinationPath, $imageName);
        }

        Product::create([
            'name'          => $validate['name'],
            'description'   => $validate['description'],
            'price'         => $validate['price'],
            'image'         => $imageName,
            'category_id'   => $validate['category_id'],
            'unit'          => $validate['unit'],
            'code'          => $validate['code']
        ]);

        return response()->json([
            'status'    => true
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $product = Product::find($id);
        if($request->ajax()){
            return response()->json($product);
        }
        if(auth()->user()->role->name != 'admin') return abort(403);
        return view('pages.products.show', compact('product'));
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        if(auth()->user()->role->name != 'admin') return abort(403);
        $validate = $request->validated();
        if($request->hasFile('image')){
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $destinationPath = public_path('/upload/products');
            $imageName = time().'.'.$extension;
            $file->move($destinationPath, $imageName);
        }

        $product = Product::find($id);
        $product->update([
                'name'          => $validate['name'] ?? $product['name'],
                'description'   => $validate['description'] ?? $product['description'],
                'price'         => $validate['price'] ?? $product['price'],
                'image'         => $request->hasFile('image') ? $imageName : $product['image'],
                'category_id'   => $validate['category_id'] ?? $product['category_id'],
                'unit'          => $validate['unit'] ?? $product['unit'],
                'code'          => $validate['code'] ?? $product['code']
            ]);
        return response()->json([
            'status'    => true
        ], 200);
    }

    public function destroy($id)
    {
        try {
            Product::destroy($id);
            return response()->json([
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false
            ], 200);
        }
        
    }
}
