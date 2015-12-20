<?php
/**
 * Класс должен находиться ЗДЕСЬ, И ТОЛЬКО ЗДЕСЬ!
 * Потому что это СИСТЕМНЫЙ, МАТЬ ЕГО, КЛАСС)))
 **/
namespace App;

use App\Helpers\Steam\APIBridge;
use App\Models\Game;
use App\Models\Place;
use App\Models\SteamItem;
use App\Helpers\IComparable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Kodeine\Acl\Traits\HasRole;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract,
                                    IComparable
{
    use Authenticatable, CanResetPassword;
    use HasRole;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'steam_id', 'avatar'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];

    /**
     * Get all of the tasks for the user.
     */
    public function items()
    {
        return $this->hasMany(SteamItem::class);
    }

    public function places() {
        return $this->hasMany(Place::class)->with('game.places');
    }

    public function wins() {
        return $this->hasMany(Game::class, 'winner_id');
    }

    public function getSteamLink() {
        $api = new APIBridge(\Config::get('steam-api.api_key'));
        return $api->parseSteamID($this->steam_id)['profileurl'];
    }

    public function games() {
        return Game::whereHas('places', function($query) {
            $query->where('user_id', $this->id);
        })->get();
    }

    /**
     * @param IComparable $other
     * @param String $comparison any of ==, <, >, =<, >=, etc
     * @return Bool true | false depending on result of comparison
     */
    public function compareTo(IComparable $other, $comparison = '==')
    {
        if(!$other instanceof User)
            throw new \InvalidArgumentException('Cant compare User to' . gettype($other));

        if ($comparison != '==')
            throw new \InvalidArgumentException('Cant compare users with ' . $comparison);

        /** @var $other User */
        return $other->id == $this->id && $other->name == $this->name;
    }
}