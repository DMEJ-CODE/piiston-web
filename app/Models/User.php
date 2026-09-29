<?php

namespace App\Models;

use App\Models\Administration\Administrator;
use App\Models\AI\AiConversation;
use App\Models\AI\AiDiagnosis;
use App\Models\AI\AiMemory;
use App\Models\AI\AiUsageLog;
use App\Models\BI\BusinessInsight;
use App\Models\BI\Dashboard;
use App\Models\BI\Report;
use App\Models\Documents\Document;
use App\Models\Documents\DocumentFolder;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\Invoice;
use App\Models\Finance\PaymentTransaction;
use App\Models\Finance\UserSubscription;
use App\Models\Finance\Wallet;
use App\Models\Fleets\Company;
use App\Models\Fleets\Driver;
use App\Models\Fleets\FleetMember;
use App\Models\Garages\GarageCompany;
use App\Models\Globalization\Address;
use App\Models\Globalization\Country;
use App\Models\Globalization\Language;
use App\Models\Globalization\Timezone;
use App\Models\Identity\Role;
use App\Models\Identity\UserPreference;
use App\Models\Identity\UserVerification;
use App\Models\Integrations\ApiClient;
use App\Models\Integrations\DeveloperApplication;
use App\Models\Integrations\Webhook;
use App\Models\Maps\Location;
use App\Models\Maps\NavigationSession;
use App\Models\Maps\TrackingSession;
use App\Models\Marketplace\SellerProfile;
use App\Models\Marketplace\ShoppingCart;
use App\Models\Mechanics\MechanicProfile;
use App\Models\Messaging\Conversation;
use App\Models\Messaging\UserPresence;
use App\Models\Notifications\Notification;
use App\Models\Notifications\NotificationPreference;
use App\Models\Notifications\UserDevice;
use App\Models\Search\Favorite;
use App\Models\Search\Recommendation;
use App\Models\Search\SavedSearch;
use App\Models\Search\SearchHistory;
use App\Models\Search\SearchSession;
use App\Models\Vehicles\Vehicle;
use App\Models\Workflows\EmergencyRequest;
use App\Models\Workflows\ServiceRequest;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $phone
 * @property int|null $country_id
 * @property int|null $language_id
 * @property int|null $timezone_id
 * @property string $status
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $phone_verified_at
 * @property string $password
 * @property string|null $profile_photo
 * @property Carbon|null $date_of_birth
 * @property string|null $gender
 * @property Carbon|null $last_login_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name', 'first_name', 'last_name', 'email', 'phone', 'password',
    'country_id', 'language_id', 'timezone_id', 'status',
    'profile_photo', 'date_of_birth', 'gender', 'last_login_at',
    'phone_verified_at', 'email_verified_at',
])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'date_of_birth' => 'date',
            'password' => 'hashed',
        ];
    }

    // --- Relationships ---

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function preferredLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot('assigned_at', 'status')
            ->withTimestamps();
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'user_addresses')
            ->withPivot('type', 'is_default')
            ->withTimestamps();
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(UserVerification::class);
    }

    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }

    public function garageCompanies(): HasMany
    {
        return $this->hasMany(GarageCompany::class, 'owner_id');
    }

    public function mechanicProfile(): HasOne
    {
        return $this->hasOne(MechanicProfile::class);
    }

    public function sellerProfile(): HasOne
    {
        return $this->hasOne(SellerProfile::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(ShoppingCart::class);
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function emergencyRequests(): HasMany
    {
        return $this->hasMany(EmergencyRequest::class);
    }

    public function managedCompanies(): HasMany
    {
        return $this->hasMany(Company::class, 'owner_id');
    }

    public function driverProfile(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    public function fleetMemberships(): HasMany
    {
        return $this->hasMany(FleetMember::class);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_members')
            ->withPivot('role', 'joined_at', 'last_seen_message_id', 'is_admin')
            ->withTimestamps();
    }

    public function presence(): HasOne
    {
        return $this->hasOne(UserPresence::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function notificationPreferences(): HasMany
    {
        return $this->hasMany(NotificationPreference::class);
    }

    public function notificationDevices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'payer_id');
    }

    public function financialAccounts(): HasMany
    {
        return $this->hasMany(FinancialAccount::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class);
    }

    public function administrator(): HasOne
    {
        return $this->hasOne(Administrator::class);
    }

    public function aiConversations(): HasMany
    {
        return $this->hasMany(AiConversation::class);
    }

    public function aiDiagnoses(): HasMany
    {
        return $this->hasMany(AiDiagnosis::class);
    }

    public function aiMemories(): HasMany
    {
        return $this->hasMany(AiMemory::class);
    }

    public function aiUsageLogs(): HasMany
    {
        return $this->hasMany(AiUsageLog::class);
    }

    public function searchHistory(): HasMany
    {
        return $this->hasMany(SearchHistory::class);
    }

    public function savedSearches(): HasMany
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function searchSessions(): HasMany
    {
        return $this->hasMany(SearchSession::class);
    }

    public function managedApiClients(): HasMany
    {
        return $this->hasMany(ApiClient::class, 'owner_id')->where('owner_type', 'User');
    }

    public function developerApplications(): HasMany
    {
        return $this->hasMany(DeveloperApplication::class, 'developer_id');
    }

    public function webhooks(): MorphMany
    {
        return $this->morphMany(Webhook::class, 'owner');
    }

    public function dashboards(): HasMany
    {
        return $this->hasMany(Dashboard::class, 'owner_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'owner_id');
    }

    public function businessInsights(): HasMany
    {
        return $this->hasMany(BusinessInsight::class, 'owner_id');
    }

    public function location(): MorphOne
    {
        return $this->morphOne(Location::class, 'entity');
    }

    public function trackingSessions(): HasMany
    {
        return $this->hasMany(TrackingSession::class, 'started_by');
    }

    public function navigationSessions(): HasMany
    {
        return $this->hasMany(NavigationSession::class);
    }

    public function promotions(): MorphMany
    {
        return $this->morphMany(Promotion::class, 'owner');
    }

    public function campaigns(): MorphMany
    {
        return $this->morphMany(Campaign::class, 'owner');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'created_by');
    }

    public function ownedDocuments()
    {
        return $this->morphMany(Document::class, 'owner');
    }

    public function documentFolders()
    {
        return $this->morphMany(DocumentFolder::class, 'owner');
    }

    // --- Helpers ---

    public function hasRole($roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasPermission($permissionName): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionName) {
            $query->where('name', $permissionName);
        })->exists();
    }

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Keep "name" in sync with the canonical first_name/last_name columns.
     *
     * The first token becomes the first name and the remainder becomes the last
     * name, so assigning and reading "name" always round-trips.
     */
    public function setNameAttribute(?string $value): void
    {
        $value = trim((string) $value);

        if ($value === '') {
            $this->attributes['first_name'] = null;
            $this->attributes['last_name'] = null;

            return;
        }

        $parts = preg_split('/\s+/', $value, 2);

        $this->attributes['first_name'] = $parts[0];
        $this->attributes['last_name'] = $parts[1] ?? null;
    }

    public function initials(): string
    {
        $name = $this->name ?: $this->email;
        $initials = Str::initials($name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
