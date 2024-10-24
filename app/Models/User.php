<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Http\UploadedFile;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia;

    protected $fillable = ["name", "email", "password", "role"];
    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("avatar")->singleFile()->useDisk("public");
    }

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
    // In your existing User model
    public function saves()
    {
        return $this->belongsToMany(
            Product::class,
            "saved_products"
        )->withTimestamps();
    }
}
