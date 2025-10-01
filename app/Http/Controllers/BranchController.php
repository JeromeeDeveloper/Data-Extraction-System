<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
	public function index()
	{
		$query = Branch::orderBy('branch_code');
		if (request('q')) {
			$q = trim(request('q'));
			$query->where(function($w) use ($q) {
				$w->where('branch_code', 'like', "%$q%")
				  ->orWhere('branch_name', 'like', "%$q%");
			});
		}
		$branches = $query->paginate(10)->withQueryString();
		return view('admin.branch.branch', compact('branches'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'branch_code' => 'required|string|max:50|unique:branches,branch_code',
			'branch_name' => 'required|string|max:255',
		]);

		Branch::create($validated);
		return back()->with('success', 'Branch created');
	}

	public function update(Request $request, Branch $branch)
	{
		$validated = $request->validate([
			'branch_code' => 'required|string|max:50|unique:branches,branch_code,' . $branch->id,
			'branch_name' => 'required|string|max:255',
		]);

		$branch->update($validated);
		return back()->with('success', 'Branch updated');
	}

	public function destroy(Branch $branch)
	{
		$branch->delete();
		return back()->with('success', 'Branch deleted');
	}
}


