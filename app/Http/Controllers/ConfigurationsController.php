<?php

namespace App\Http\Controllers;

use App\Models\CisaProduct;
use App\Models\CisaProductGlCode;
use Illuminate\Http\Request;

class ConfigurationsController extends Controller
{
	public function index()
	{
        $query = CisaProduct::with('glCodes')->orderBy('cisa_code');

        if (request('q')) {
            $q = trim(request('q'));
            $query->where(function($w) use ($q) {
                $w->where('cisa_code', 'like', "%$q%")
                  ->orWhere('description', 'like', "%$q%")
                  ->orWhere('type', 'like', "%$q%");
            });
        }

        $products = $query->paginate(10)->withQueryString();
        return view('admin.configurations.configurations', compact('products'));
	}

	public function storeProduct(Request $request)
	{
		$validated = $request->validate([
			'cisa_code' => 'required|string|max:50|unique:cisa_products,cisa_code',
			'description' => 'required|string|max:255',
			'type' => 'required|in:installment,non-installment',
		],[
			'cisa_code.unique' => 'CISA product already exists.',
		]);

		CisaProduct::create($validated);
		return back()->with('success', 'CISA product created');
	}

	public function updateProduct(Request $request, CisaProduct $product)
	{
		$validated = $request->validate([
			'cisa_code' => 'required|string|max:50|unique:cisa_products,cisa_code,' . $product->id,
			'description' => 'required|string|max:255',
			'type' => 'required|in:installment,non-installment',
		],[
			'cisa_code.unique' => 'CISA product already exists.',
		]);

		$product->update($validated);
		return back()->with('success', 'CISA product updated');
	}

	public function addGlCode(Request $request, CisaProduct $product)
	{
		$validated = $request->validate([
			'gl_code' => 'required|string|max:50',
		]);

		$product->glCodes()->firstOrCreate(['gl_code' => $validated['gl_code']]);
		return back()->with('success', 'GL code added');
	}

	public function deleteGlCode(CisaProduct $product, CisaProductGlCode $glCode)
	{
		abort_if($glCode->cisa_product_id !== $product->id, 404);
		$glCode->delete();
		return back()->with('success', 'GL code removed');
	}

	public function destroy(CisaProduct $product)
	{
		$product->delete();
		return back()->with('success', 'CISA product deleted');
	}
}


