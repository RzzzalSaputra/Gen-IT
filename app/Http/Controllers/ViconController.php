<?php

namespace App\Http\Controllers;

use App\Models\Vicon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViconController extends Controller
{
    protected $default_folder = 'vicon';
    protected $file_indexes = ['img'];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $vicons = Vicon::all();
        return view('vicons.index', compact('vicons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'time' => 'required|date',
            'link' => 'required|url',
            'download' => 'nullable|url'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            // Create Vicon first to get ID
            $vicon = Vicon::create([
                'title' => $data['title'],
                'desc' => $data['desc'],
                'time' => $data['time'],
                'link' => $data['link'],
                'download' => $data['download'] ?? null
            ]);

            // Handle image upload
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $extension = $file->getClientOriginalExtension();
                $filename = $this->default_folder . '/' . $vicon->id . '.' . $extension;
                
                $path = $file->storeAs('public/' . $this->default_folder, $vicon->id . '.' . $extension);
                $vicon->img = '/storage/' . $filename;
                $vicon->save();
            }

            DB::commit();
            return redirect()->route('vicons.index')->with('success', 'Vicon created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error creating vicon: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Vicon $vicon)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'time' => 'required|date',
            'link' => 'required|url',
            'download' => 'nullable|url'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('img')) {
                // Delete old image
                if ($vicon->img) {
                    $oldPath = str_replace('/storage/', 'public/', $vicon->img);
                    Storage::delete($oldPath);
                }

                $file = $request->file('img');
                $extension = $file->getClientOriginalExtension();
                $filename = $this->default_folder . '/' . $vicon->id . '.' . $extension;
                
                $path = $file->storeAs('public/' . $this->default_folder, $vicon->id . '.' . $extension);
                $data['img'] = '/storage/' . $filename;
            }

            $vicon->update($data);
            DB::commit();
            return redirect()->route('vicons.index')->with('success', 'Vicon updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating vicon: ' . $e->getMessage());
        }
    }
}