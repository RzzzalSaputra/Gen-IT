<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'img',
        'gmap',
        'province',
        'city',
        'address',
        'website',
        'instagram',
        'facebook',
        'x',
        'read_counter',
    ];

    protected $casts = [
        'read_counter' => 'integer',
    ];

    /**
     * Relasi ke tabel jobs.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Hapus gambar jika ada.
     */
    public function deleteImage()
    {
        if (!empty($this->img) && is_string($this->img)) {
            $filePath = str_replace('/storage/', '', $this->img);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        // Hapus gambar lama sebelum update jika ada perubahan di 'img'
        static::updating(function ($company) {
            $oldImage = $company->getOriginal('img');
            if ($company->isDirty('img') && !empty($oldImage) && is_string($oldImage)) {
                $filePath = str_replace('/storage/', '', $oldImage);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        });

        // Hapus gambar saat company dihapus
        static::deleting(function ($company) {
            $company->deleteImage();
        });
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            if ($model->img && Storage::disk('public')->exists($model->img)) {
                Storage::disk('public')->delete($model->img);
            }
        });
    }
}
