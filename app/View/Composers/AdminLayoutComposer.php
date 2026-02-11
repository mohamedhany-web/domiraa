<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminLayoutComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = Auth::user();
        
        if ($user) {
            // Clear any cached relationships to ensure fresh data
            $user->unsetRelation('roleModel');
            $user->unsetRelation('directPermissions');
            
            // Force reload all permission relationships from database
            $user->load(['roleModel.permissions', 'directPermissions']);
            
            // Cache user permissions in view for performance
            $view->with('userPermissions', $user->getAllPermissions());
            $view->with('currentUser', $user);
        }
    }
}

