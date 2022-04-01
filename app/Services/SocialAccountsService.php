<?php
namespace App\Services;

use App\Models\User;
use App\Models\SocialAccount;
use Laravel\Socialite\Two\User as ProviderUser;
class SocialAccountsService
{
    /**
     * Find or create user instance by provider user instance and provider name.
     * 
     * @param ProviderUser $providerUser
     * @param string $provider
     * 
     * @return User
     */
    public function findOrCreate(ProviderUser $providerUser, string $provider): User
    {
        $SocialAccount = SocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();
        if ($SocialAccount) {
            return $SocialAccount->user;
        } else {
            $user = null;
            if ($email = $providerUser->getEmail()) {
                $user = User::where('email', $email)->first();
            }
            if (! $user) {
                $user = User::create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                ]);
            }
            
            SocialAccount::create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
                'user_id' => $user->id
            ]);
        
            return $user;
        }
    }
}