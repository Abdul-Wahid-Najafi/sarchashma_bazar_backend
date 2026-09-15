<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $phone_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Province> $provinces
 * @property-read int|null $provinces_count
 * @method static \Database\Factories\CountryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country wherePhoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $identifier
 * @property string $code
 * @property bool $is_used
 * @property string $type
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp isValid($identifier, $code)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Otp whereUpdatedAt($value)
 */
	class Otp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @method static \Database\Factories\ProvinceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereUpdatedAt($value)
 */
	class Province extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $province_id
 * @property string $shop_name
 * @property string $slug
 * @property string|null $description
 * @property string|null $contact_number
 * @property string|null $whatsapp
 * @property string|null $website
 * @property string|null $logo
 * @property string|null $cover_image
 * @property string $status
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $followers
 * @property-read int|null $followers_count
 * @property-read \App\Models\Province $province
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShopService> $services
 * @property-read int|null $services_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShopSocialAccount> $socialAccounts
 * @property-read int|null $social_accounts_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\ShopFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop whereWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shop withoutTrashed()
 */
	class Shop extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $shop_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Shop|null $shop
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopFollower newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopFollower newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopFollower query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopFollower whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopFollower whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopFollower whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopFollower whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopFollower whereUserId($value)
 */
	class ShopFollower extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $shop_id
 * @property string $service
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopService whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopService whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopService whereUpdatedAt($value)
 */
	class ShopService extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $shop_id
 * @property int $social_icon_id
 * @property string $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Shop|null $shop
 * @property-read \App\Models\SocialIcon $socialIcon
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopSocialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopSocialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopSocialAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopSocialAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopSocialAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopSocialAccount whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopSocialAccount whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopSocialAccount whereSocialIconId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShopSocialAccount whereUpdatedAt($value)
 */
	class ShopSocialAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereUserId($value)
 */
	class SocialAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $icon_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialIcon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialIcon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialIcon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialIcon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialIcon whereIconUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialIcon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialIcon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialIcon whereUpdatedAt($value)
 */
	class SocialIcon extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $whatsapp
 * @property string|null $password
 * @property string|null $profile_picture
 * @property bool $is_seller
 * @property bool $is_shop_profile_complete
 * @property bool $is_personal_profile_complete
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Shop|null $shop
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialAccount> $socialAccounts
 * @property-read int|null $social_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsPersonalProfileComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsSeller($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsShopProfileComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

