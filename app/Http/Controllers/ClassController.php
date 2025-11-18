<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassModel;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ClassController extends Controller
{
    /**
     * Display a listing of the classes.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = ClassModel::query();
        
        // Handle search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('class_name', 'like', '%' . $search . '%');
        }
        
        $classes = $query->paginate(10)->appends($request->except('page'));
        
        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'table_body' => view('admin.classes.partials.table-body', compact('classes'))->render(),
                'pagination' => view('admin.classes.partials.pagination', compact('classes'))->render()
            ]);
        }
        
        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create(): View
    {
        return view('admin.classes.create');
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'class_name' => 'required|string|max:255|unique:class,class_name',
        ]);

        $class = ClassModel::create([
            'class_name' => $request->class_name,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class created successfully.',
                'data' => $class
            ]);
        }

        return redirect()->route('admin.classes.index')
                         ->with('success', 'Class created successfully.');
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(ClassModel $class): View
    {
        return view('admin.classes.edit', compact('class'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, ClassModel $class): RedirectResponse|JsonResponse
    {
        $request->validate([
            'class_name' => 'required|string|max:255|unique:class,class_name,' . $class->id,
        ]);

        $class->update([
            'class_name' => $request->class_name,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully.',
                'data' => $class
            ]);
        }

        return redirect()->route('admin.classes.index')
                         ->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(ClassModel $class): RedirectResponse|JsonResponse
    {
        try {
            // Check if class has related records
            if ($class->employees()->count() > 0) {
                $message = 'Cannot delete class because it has related records (employees). Please remove or reassign these records first.';
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->route('admin.classes.index')
                                 ->with('error', $message);
            }
            
            $class->delete();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Class deleted successfully.'
                ]);
            }
            
            return redirect()->route('admin.classes.index')
                             ->with('success', 'Class deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting class: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'There was an error deleting the class.'
                ], 500);
            }
            
            return redirect()->route('admin.classes.index')
                             ->with('error', 'There was an error deleting the class.');
        }
    }
}