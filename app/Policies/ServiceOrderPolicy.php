<?php

namespace App\Policies;

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ServiceOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return ($user->id === 1) ? Response::allow() : Response::deny('Você não tem permissão para acessar esse item!');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceOrder  $serviceOrder
     * @return mixed
     */
    public function view(User $user, ServiceOrder $serviceOrder)
    {
        return (($user->id === 1) || ($serviceOrder->user_id === $user->id)) ? Response::allow() : Response::deny('Você não tem permissão para acessar esse item!');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceOrder  $serviceOrder
     * @return mixed
     */
    public function update(User $user, ServiceOrder $serviceOrder)
    {
        return (($user->id === 1) || ($serviceOrder->user_id === $user->id)) ? Response::allow() : Response::deny('Você não tem permissão para acessar esse item!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceOrder  $serviceOrder
     * @return mixed
     */
    public function delete(User $user, ServiceOrder $serviceOrder)
    {
        return (($user->id === 1) || ($serviceOrder->user_id === $user->id)) ? Response::allow() : Response::deny('Você não tem permissão para acessar esse item!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceOrder  $serviceOrder
     * @return mixed
     */
    public function restore(User $user, ServiceOrder $serviceOrder)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ServiceOrder  $serviceOrder
     * @return mixed
     */
    public function forceDelete(User $user, ServiceOrder $serviceOrder)
    {
        //
    }
}
