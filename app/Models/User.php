<?php
/**
 * Web Application User Model
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App\Models;
use Components\Helpers\Hash;

class User extends \Core\Models\DbModel
{
	use \Core\Models\Traits\Belong, 
		\Core\Models\Traits\Has;
	
	protected 
		$hidden = ['password'],
		$writable = ['company_id', 'login', 'name', 'phone', 'email', 'active'];
	
    /**
     * Set password
	 * @param string $val - new password
	 * @return User
     */
	public function setPassword($val)
	{
		$this->attributes['password'] = Hash::make($val);
		return $this;
	}
	
    /**
     * Has user password
	 * @param string $from - password
	 * @return bool
     */
	public function hasPassword($from)
	{
		return Hash::check($from, $this->attributes['password']);
	}
	
    /**
     * Get User Company
	 * @return Company
     */
	public function company()
	{
		return $this->belongsTo(Company::class);
	}
	
    /**
     * Get User Roles
	 * @return Collection
     */
	public function roles()
	{
		return $this->hasMany(Role::class);
	}
	
    /**
     * Has User Role
	 * @return bool
     */
	public function hasRole($role)
	{
		if ($role instanceof \App\Models\Role) {
			$role = $role->code;
		}
		return $this->roles->contains('code', $role);
	}
}
