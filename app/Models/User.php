<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'id_role',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function dataTableUser()
    {
        $query = DB::table('users as u')
                    ->join('role as r', 'r.id', '=', 'u.id_role')
                    ->where([['r.status', 1], ['u.status', 1]])
                    ->select('u.id', 'u.name', 'u.username', 'u.email', 'u.id_role', 'u.status', 'r.nama as role');

        return $query;
    }

    public function editUser($id)
    {
        $query = DB::table('users as u')
                    ->join('role as r', 'r.id', '=', 'u.id_role')
                    ->where([['u.id', $id], ['r.status', 1]])
                    ->select('u.id', 'u.name', 'u.username', 'u.email', 'u.id_role', 'u.status', 'r.nama as nama_role')
                    ->first();

        return $query;
    }
}
