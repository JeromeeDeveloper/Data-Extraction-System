<?php

namespace App\Http\Controllers;

use App\Models\CisaProduct;
use App\Models\CisaProductGlCode;
use App\Models\TitleConfiguration;
use App\Models\TitleMbwinCode;
use App\Models\GenderConfiguration;
use App\Models\GenderMbwinCode;
use App\Models\CivilConfiguration;
use App\Models\CivilMbwinCode;
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
		$query = TitleConfiguration::with('mbwinCodes')->orderBy('cisa_code');

		if (request('q')) {
			$q = trim(request('q'));
			$query->where(function($w) use ($q) {
				$w->where('cisa_code', 'like', "%$q%")
				  ->orWhere('description', 'like', "%$q%");
			});
		}

		$titles = $query->paginate(10)->withQueryString();
		return view('admin.configurations.title', compact('titles'));
	}

	public function storeTitle(Request $request)
	{
		$validated = $request->validate([
			'cisa_code' => 'required|string|max:50|unique:title_configurations,cisa_code',
			'description' => 'required|string|max:255',
		], [
			'cisa_code.unique' => 'CISA code already exists.',
		]);

		TitleConfiguration::create($validated);
		return back()->with('success', 'Title configuration created');
	}

	public function updateTitle(Request $request, TitleConfiguration $titleConfiguration)
	{
		$validated = $request->validate([
			'cisa_code' => 'required|string|max:50|unique:title_configurations,cisa_code,' . $titleConfiguration->id,
			'description' => 'required|string|max:255',
		], [
			'cisa_code.unique' => 'CISA code already exists.',
		]);

		$titleConfiguration->update($validated);
		return back()->with('success', 'Title configuration updated');
	}

	public function addTitleMbwinCode(Request $request, TitleConfiguration $titleConfiguration)
	{
		$validated = $request->validate([
			'mbwin_code' => 'required|string|max:50|unique:title_mbwin_codes,mbwin_code',
		], [
			'mbwin_code.unique' => 'MBWIN code already exists.',
		]);

		$titleConfiguration->mbwinCodes()->create($validated);
		return back()->with('success', 'MBWIN code added');
	}

	public function deleteTitleMbwinCode(TitleConfiguration $titleConfiguration, TitleMbwinCode $mbwinCode)
	{
		abort_if($mbwinCode->title_configuration_id !== $titleConfiguration->id, 404);
		$mbwinCode->delete();
		return back()->with('success', 'MBWIN code removed');
	}

	public function destroyTitle(TitleConfiguration $titleConfiguration)
	{
		$titleConfiguration->delete();
		return back()->with('success', 'Title configuration deleted');
	}

	public function gender()
	{
		$query = GenderConfiguration::with('mbwinCodes')->orderBy('cisa_code');

		if (request('q')) {
			$q = trim(request('q'));
			$query->where(function($w) use ($q) {
				$w->where('cisa_code', 'like', "%$q%")
				  ->orWhere('description', 'like', "%$q%");
			});
		}

		$genders = $query->paginate(10)->withQueryString();
		return view('admin.configurations.gender', compact('genders'));
	}

	public function storeGender(Request $request)
	{
		$validated = $request->validate([
			'cisa_code' => 'required|string|max:50|unique:gender_configurations,cisa_code',
			'description' => 'required|string|max:255',
		], [
			'cisa_code.unique' => 'CISA code already exists.',
		]);

		GenderConfiguration::create($validated);
		return back()->with('success', 'Gender configuration created');
	}

	public function updateGender(Request $request, GenderConfiguration $genderConfiguration)
	{
		$validated = $request->validate([
			'cisa_code' => 'required|string|max:50|unique:gender_configurations,cisa_code,' . $genderConfiguration->id,
			'description' => 'required|string|max:255',
		], [
			'cisa_code.unique' => 'CISA code already exists.',
		]);

		$genderConfiguration->update($validated);
		return back()->with('success', 'Gender configuration updated');
	}

	public function addGenderMbwinCode(Request $request, GenderConfiguration $genderConfiguration)
	{
		$validated = $request->validate([
			'mbwin_code' => 'required|string|max:50|unique:gender_mbwin_codes,mbwin_code',
		], [
			'mbwin_code.unique' => 'MBWIN code already exists.',
		]);

		$genderConfiguration->mbwinCodes()->create($validated);
		return back()->with('success', 'MBWIN code added');
	}

	public function deleteGenderMbwinCode(GenderConfiguration $genderConfiguration, GenderMbwinCode $mbwinCode)
	{
		abort_if($mbwinCode->gender_configuration_id !== $genderConfiguration->id, 404);
		$mbwinCode->delete();
		return back()->with('success', 'MBWIN code removed');
	}

	public function destroyGender(GenderConfiguration $genderConfiguration)
	{
		$genderConfiguration->delete();
		return back()->with('success', 'Gender configuration deleted');
	}

	public function civil()
	{
		$query = CivilConfiguration::with('mbwinCodes')->orderBy('cisa_code');

		if (request('q')) {
			$q = trim(request('q'));
			$query->where(function($w) use ($q) {
				$w->where('cisa_code', 'like', "%$q%")
				  ->orWhere('description', 'like', "%$q%");
			});
		}

		$civils = $query->paginate(10)->withQueryString();
		return view('admin.configurations.civil', compact('civils'));
	}

	public function storeCivil(Request $request)
	{
		$validated = $request->validate([
			'cisa_code' => 'required|string|max:50|unique:civil_configurations,cisa_code',
			'description' => 'required|string|max:255',
		], [
			'cisa_code.unique' => 'CISA code already exists.',
		]);

		CivilConfiguration::create($validated);
		return back()->with('success', 'Civil configuration created');
	}

	public function updateCivil(Request $request, CivilConfiguration $civilConfiguration)
	{
		$validated = $request->validate([
			'cisa_code' => 'required|string|max:50|unique:civil_configurations,cisa_code,' . $civilConfiguration->id,
			'description' => 'required|string|max:255',
		], [
			'cisa_code.unique' => 'CISA code already exists.',
		]);

		$civilConfiguration->update($validated);
		return back()->with('success', 'Civil configuration updated');
	}

	public function addCivilMbwinCode(Request $request, CivilConfiguration $civilConfiguration)
	{
		$validated = $request->validate([
			'mbwin_code' => 'required|string|max:50|unique:civil_mbwin_codes,mbwin_code',
		], [
			'mbwin_code.unique' => 'MBWIN code already exists.',
		]);

		$civilConfiguration->mbwinCodes()->create($validated);
		return back()->with('success', 'MBWIN code added');
	}

	public function deleteCivilMbwinCode(CivilConfiguration $civilConfiguration, CivilMbwinCode $mbwinCode)
	{
		abort_if($mbwinCode->civil_configuration_id !== $civilConfiguration->id, 404);
		$mbwinCode->delete();
		return back()->with('success', 'MBWIN code removed');
	}

	public function destroyCivil(CivilConfiguration $civilConfiguration)
	{
		$civilConfiguration->delete();
		return back()->with('success', 'Civil configuration deleted');
	}
}


