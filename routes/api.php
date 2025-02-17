<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Option;
use App\Models\Post;
use App\Models\Gallery;
use App\Models\Vicon;
use App\Models\Material;
use App\Models\Company;
use App\Models\Submission;
use App\Models\School;
use App\Models\Study;
use App\Models\Contact;
use App\Models\Job;

// Options Routes
Route::prefix('options')->group(function () {
    Route::get('/', function() {
        return Option::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Option::all();
    });
    Route::post('/', function(Request $request) {
        return Option::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Option::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $option = Option::findOrFail($id);
        $option->update($request->all());
        return $option;
    });
    Route::delete('/{id}', function($id) {
        $option = Option::findOrFail($id);
        return $option->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $option = Option::withTrashed()->findOrFail($id);
        $option->restore();
        return $option;
    });
});

// Posts Routes
Route::prefix('posts')->group(function () {
    Route::get('/', function() {
        return Post::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Post::all();
    });
    Route::post('/', function(Request $request) {
        return Post::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Post::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return $post;
    });
    Route::delete('/{id}', function($id) {
        $post = Post::findOrFail($id);
        return $post->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();
        return $post;
    });
});

// Galleries Routes
Route::prefix('galleries')->group(function () {
    Route::get('/', function() {
        return Gallery::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Gallery::all();
    });
    Route::post('/', function(Request $request) {
        return Gallery::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Gallery::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $gallery = Gallery::findOrFail($id);
        $gallery->update($request->all());
        return $gallery;
    });
    Route::delete('/{id}', function($id) {
        $gallery = Gallery::findOrFail($id);
        return $gallery->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $gallery = Gallery::withTrashed()->findOrFail($id);
        $gallery->restore();
        return $gallery;
    });
});

// Vicons Routes
Route::prefix('vicons')->group(function () {
    Route::get('/', function() {
        return Vicon::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Vicon::all();
    });
    Route::post('/', function(Request $request) {
        return Vicon::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Vicon::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $vicon = Vicon::findOrFail($id);
        $vicon->update($request->all());
        return $vicon;
    });
    Route::delete('/{id}', function($id) {
        $vicon = Vicon::findOrFail($id);
        return $vicon->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $vicon = Vicon::withTrashed()->findOrFail($id);
        $vicon->restore();
        return $vicon;
    });
});

// Materials Routes
Route::prefix('materials')->group(function () {
    Route::get('/', function() {
        return Material::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Material::all();
    });
    Route::post('/', function(Request $request) {
        return Material::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Material::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $material = Material::findOrFail($id);
        $material->update($request->all());
        return $material;
    });
    Route::delete('/{id}', function($id) {
        $material = Material::findOrFail($id);
        return $material->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $material = Material::withTrashed()->findOrFail($id);
        $material->restore();
        return $material;
    });
    Route::patch('/{id}/count-read', function($id) {
        $material = Material::findOrFail($id);
        $material->increment('read_counter');
        return $material;
    });
    Route::patch('/{id}/count-download', function($id) {
        $material = Material::findOrFail($id);
        $material->increment('download_counter');
        return $material;
    });
});

// Companies Routes
Route::prefix('companies')->group(function () {
    Route::get('/', function() {
        return Company::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Company::all();
    });
    Route::post('/', function(Request $request) {
        return Company::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Company::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $company = Company::findOrFail($id);
        $company->update($request->all());
        return $company;
    });
    Route::delete('/{id}', function($id) {
        $company = Company::findOrFail($id);
        return $company->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $company = Company::withTrashed()->findOrFail($id);
        $company->restore();
        return $company;
    });
    Route::patch('/{id}/count-read', function($id) {
        $company = Company::findOrFail($id);
        $company->increment('read_counter');
        return $company;
    });
    Route::patch('/{id}/count-download', function($id) {
        $company = Company::findOrFail($id);
        $company->increment('download_counter');
        return $company;
    });
});

// Submissions Routes
Route::prefix('submissions')->group(function () {
    Route::get('/', function() {
        return Submission::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Submission::all();
    });
    Route::post('/', function(Request $request) {
        return Submission::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Submission::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $submission = Submission::findOrFail($id);
        $submission->update($request->all());
        return $submission;
    });
    Route::delete('/{id}', function($id) {
        $submission = Submission::findOrFail($id);
        return $submission->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $submission = Submission::withTrashed()->findOrFail($id);
        $submission->restore();
        return $submission;
    });
    Route::patch('/{id}/approve', function(Request $request, $id) {
        $submission = Submission::findOrFail($id);
        $submission->update([
            'status' => $request->status
        ]);
        return $submission;
    });
    Route::patch('/{id}/count-read', function($id) {
        $submission = Submission::findOrFail($id);
        $submission->increment('read_counter');
        return $submission;
    });
});

// Schools Routes
Route::prefix('schools')->group(function () {
    Route::get('/', function() {
        return School::withTrashed()->get();
    });
    Route::get('/active', function() {
        return School::all();
    });
    Route::post('/', function(Request $request) {
        return School::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return School::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $school = School::findOrFail($id);
        $school->update($request->all());
        return $school;
    });
    Route::delete('/{id}', function($id) {
        $school = School::findOrFail($id);
        return $school->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $school = School::withTrashed()->findOrFail($id);
        $school->restore();
        return $school;
    });
    Route::patch('/{id}/count-read', function($id) {
        $school = School::findOrFail($id);
        $school->increment('read_counter');
        return $school;
    });
});

// Studies Routes
Route::prefix('studies')->group(function () {
    Route::get('/', function() {
        return Study::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Study::all();
    });
    Route::post('/', function(Request $request) {
        return Study::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Study::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $study = Study::findOrFail($id);
        $study->update($request->all());
        return $study;
    });
    Route::delete('/{id}', function($id) {
        $study = Study::findOrFail($id);
        return $study->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $study = Study::withTrashed()->findOrFail($id);
        $study->restore();
        return $study;
    });
    Route::patch('/{id}/count-read', function($id) {
        $study = Study::findOrFail($id);
        $study->increment('read_counter');
        return $study;
    });
});

// Contacts Routes
Route::prefix('contacts')->group(function () {
    Route::get('/', function() {
        return Contact::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Contact::all();
    });
    Route::post('/', function(Request $request) {
        return Contact::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Contact::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $contact = Contact::findOrFail($id);
        $contact->update($request->all());
        return $contact;
    });
    Route::delete('/{id}', function($id) {
        $contact = Contact::findOrFail($id);
        return $contact->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $contact = Contact::withTrashed()->findOrFail($id);
        $contact->restore();
        return $contact;
    });
    Route::patch('/{id}/respond', function(Request $request, $id) {
        $contact = Contact::findOrFail($id);
        $contact->update([
            'respond_message' => $request->respond_message,
            'respond_by' => $request->user()->id,
            'status' => $request->status
        ]);
        return $contact;
    });
});

// Jobs Routes
Route::prefix('jobs')->group(function () {
    Route::get('/', function() {
        return Job::withTrashed()->get();
    });
    Route::get('/active', function() {
        return Job::all();
    });
    Route::post('/', function(Request $request) {
        return Job::create($request->all());
    });
    Route::get('/{id}', function($id) {
        return Job::withTrashed()->findOrFail($id);
    });
    Route::put('/{id}', function(Request $request, $id) {
        $job = Job::findOrFail($id);
        $job->update($request->all());
        return $job;
    });
    Route::delete('/{id}', function($id) {
        $job = Job::findOrFail($id);
        return $job->delete();
    });
    Route::patch('/{id}/restore', function($id) {
        $job = Job::withTrashed()->findOrFail($id);
        $job->restore();
        return $job;
    });
    Route::patch('/{id}/count-read', function($id) {
        $job = Job::findOrFail($id);
        $job->increment('read_counter');
        return $job;
    });
});