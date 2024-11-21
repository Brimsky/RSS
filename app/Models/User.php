<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia, HasApiTokens;

    protected $fillable = ["name", "email", "password", "role", "avatar"];
    protected $hidden = ["password", "remember_token"];
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function isAdmin()
    {
        return $this->role === "admin";
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("avatar")
            ->singleFile()
            ->useDisk("public");
    }

    protected $appends = ['avatar_url'];

    public function addAvatar(UploadedFile $file): void
    {
        $this->addMedia($file)
            ->usingName($this->name . "_avatar")
            ->usingFileName(time() . "_" . $file->getClientOriginalName())
            ->toMediaCollection("avatar");
    }

    public function getAvatarAttribute()
    {
        if ($this->attributes["avatar"]) {
            return asset("storage/" . $this->attributes["avatar"]);
        }
        return null;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('avatar') ?: null;
    }

    // In your existing User model
    public function saves()
    {
        return $this->belongsToMany(
            Product::class,
            "saved_products"
        )->withTimestamps();
    }
}
