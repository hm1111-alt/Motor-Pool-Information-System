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
     * Store a newly created class in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'class_name' => 'required|string|max:255|unique:lib_class,class_name',
        ]);

        $class = ClassModel::create([
            'class_name' => $request->class_name,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
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
     * Update the specified class in storage.
     */
    public function update(Request $request, ClassModel $class): RedirectResponse|JsonResponse
    {
        $request->validate([
            'class_name' => 'required|string|max:255|unique:lib_class,class_name,' . $class->id_class . ',id_class',
        ]);

        $class->update([
            'class_name' => $request->class_name,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
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
            // Check for dependent records
            $employeePositionCount = \App\Models\EmpPosition::where('class_id', $class->id_class)->count();
            
            if ($employeePositionCount > 0) {
                $message = "Cannot delete class '{$class->class_name}' because it has dependent records:\n";
                $message .= "- {$employeePositionCount} employee position(s)\n";
                $message .= "\nPlease reassign or delete these dependent records before deleting the class.";
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'dependencies' => [
                            'employee_positions' => $employeePositionCount
                        ]
                    ], 422);
                }
                
                return redirect()->route('admin.classes.index')
                                 ->with('error', $message);
            }
            
            $className = $class->class_name;
            $class->delete();
            
            $successMessage = "Class '{$className}' deleted successfully.";
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }
            
            return redirect()->route('admin.classes.index')
                             ->with('success', $successMessage);
        } catch (\Exception $e) {
            \Log::error('Error deleting class: ' . $e->getMessage());
            \Log::error('Class ID: ' . $class->id_class . ', Name: ' . $class->class_name);
            
            $errorMessage = 'There was an error deleting the class: ' . $e->getMessage();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->route('admin.classes.index')
                             ->with('error', $errorMessage);
        }
    }
}