<?php

namespace App\Http\Controllers;

use App\Models\CisaProduct;
use App\Models\CisaProductGlCode;
use App\Models\TitleConfiguration;
use App\Models\GenderConfiguration;
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
			'gl_code' => 'required|string|max:50|unique:cisa_product_gl_codes,gl_code',
		], [
			'gl_code.unique' => 'GL code already exists.',
		]);

		$product->glCodes()->create($validated);
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

	public function title()
	{
		$query = TitleConfiguration::orderBy('title_code');

		if (request('q')) {
			$q = trim(request('q'));
			$query->where(function($w) use ($q) {
				$w->where('title_code', 'like', "%$q%")
				  ->orWhere('title', 'like', "%$q%");
			});
		}

		$titles = $query->paginate(10)->withQueryString();
		return view('admin.configurations.title', compact('titles'));
	}

	public function storeTitle(Request $request)
	{
		$validated = $request->validate([
			'title_code' => 'required|string|max:50|unique:title_configurations,title_code',
			'title' => 'required|string|max:255',
		], [
			'title_code.unique' => 'Title code already exists.',
		]);

		TitleConfiguration::create($validated);
		return back()->with('success', 'Title configuration created');
	}

	public function updateTitle(Request $request, TitleConfiguration $titleConfiguration)
	{
		$validated = $request->validate([
			'title_code' => 'required|string|max:50|unique:title_configurations,title_code,' . $titleConfiguration->id,
			'title' => 'required|string|max:255',
		], [
			'title_code.unique' => 'Title code already exists.',
		]);

		$titleConfiguration->update($validated);
		return back()->with('success', 'Title configuration updated');
	}

	public function destroyTitle(TitleConfiguration $titleConfiguration)
	{
		$titleConfiguration->delete();
		return back()->with('success', 'Title configuration deleted');
	}

	public function gender()
	{
		$query = GenderConfiguration::orderBy('gender_code');

		if (request('q')) {
			$q = trim(request('q'));
			$query->where(function($w) use ($q) {
				$w->where('gender_code', 'like', "%$q%")
				  ->orWhere('gender', 'like', "%$q%");
			});
		}

		$genders = $query->paginate(10)->withQueryString();
		return view('admin.configurations.gender', compact('genders'));
	}

	public function storeGender(Request $request)
	{
		$validated = $request->validate([
			'gender_code' => 'required|string|max:50|unique:gender_configurations,gender_code',
			'gender' => 'required|string|max:255',
		], [
			'gender_code.unique' => 'Gender code already exists.',
		]);

		GenderConfiguration::create($validated);
		return back()->with('success', 'Gender configuration created');
	}

	public function updateGender(Request $request, GenderConfiguration $genderConfiguration)
	{
		$validated = $request->validate([
			'gender_code' => 'required|string|max:50|unique:gender_configurations,gender_code,' . $genderConfiguration->id,
			'gender' => 'required|string|max:255',
		], [
			'gender_code.unique' => 'Gender code already exists.',
		]);

		$genderConfiguration->update($validated);
		return back()->with('success', 'Gender configuration updated');
	}

	public function destroyGender(GenderConfiguration $genderConfiguration)
	{
		$genderConfiguration->delete();
		return back()->with('success', 'Gender configuration deleted');
	}
}


