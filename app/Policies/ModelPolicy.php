<?php
namespace App\Policies;

use App\Models\Model;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        // Define your logic
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Model $model)
    {
        // Define your logic
    }

    // Add other methods as needed
}
