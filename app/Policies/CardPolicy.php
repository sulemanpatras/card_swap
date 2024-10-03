<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admin;

class CardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function updateStatus(Admin $admin)
{
    return $admin->hasPermissionTo('update-card-status'); // Adjust this according to your setup
}

}
