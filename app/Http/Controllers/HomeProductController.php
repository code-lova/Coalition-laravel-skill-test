<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeProductController extends Controller
{
    public function index()
    {
       
        return view('index');  // Display the form and list of products
    }


    // Store product data in JSON  file
    public function store(Request $request)
    {
        $data = $request->only(['name', 'quantity', 'price']);
        $data['datetime_submitted'] = now()->toDateTimeString();
        $data['total_value'] = $data['quantity'] * $data['price'];

        $filePath = storage_path('app/data.json');
        $existingData = [];

        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            $existingData = json_decode($json, true);
        }

        $existingData[] = $data;
        file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));

        return response()->json($existingData);
    }


    public function getProducts()
    {
        // Retrieve product data from JSON file
        $filePath = storage_path('app/data.json');
        $existingData = [];

        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            $existingData = json_decode($json, true);
        }

        return response()->json($existingData);
    }


    public function update(Request $request, $id)
    {
        // Load existing data from the JSON filee
        $filePath = storage_path('app/data.json');
        $products = [];
        if (file_exists($filePath)) {
            $products = json_decode(file_get_contents($filePath), true);
        }

        // Finding and updating the product.... needed more time on this
        foreach ($products as &$product) {
            if ($product['id'] == $id) {
                $product['name'] = $request->name;
                $product['quantity'] = $request->quantity;
                $product['price'] = $request->price;
                $product['total_value'] = $request->quantity * $request->price;
                break;
            }
        }

        // Save updated data back to the file
        file_put_contents($filePath, json_encode($products, JSON_PRETTY_PRINT));

        return response()->json($products);
    }

}
