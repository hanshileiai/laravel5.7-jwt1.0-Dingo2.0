<?php

namespace App\Api\Traits;

use App\Api\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Facades\JWTAuth;
use Storage;

trait UserManager
{
    protected $uuidCacheKey = 'user_uuid_cache';
    protected $cackeTime = 60;

    protected function UserModel(): Model
    {
        return new User();
    }

    /**
     * 生成一个新的uuid.
     *
     * @return string
     */
    protected function createUuid(): string
    {
        $uuidObj = Uuid::uuid1();
        $uuid = $uuidObj->toString();
        if ($this->checkExists($uuid)) {
            return '';
        }

        return $uuid;
    }

    /**
     * 获取缓存.
     *
     * @return Collection
     */
    public function getCache(): Collection
    {
        return Cache::tags(['app', 'user'])->remember($this->uuidCacheKey, $this->cackeTime, function () {
            $uuids = $this->UserModel()->pluck('id', 'uuid');

            return $uuids;
        });
    }

    /**
     * 将新增的uuid放入缓存.
     *
     * @param string $uuid
     */
    public function setCache(string $uuid)
    {
        $uuids = $this->getCache();
        $uuids->put($uuid, 1);
        Cache::tags(['app', 'user'])->put($this->uuidCacheKey, $uuids, $this->cackeTime);
    }

    /**
     * 清理缓存.
     */
    public function clearCache()
    {
        Cache::tags(['user'])->flush();
    }

    /**
     * 检测是否已经存在.
     *
     * @param string $uuid
     *
     * @return bool
     */
    protected function checkExists(string $uuid): bool
    {
        $uuids = $this->UserModel()->where('uuid',$uuid)->value('id');

        return $uuids ? true : false;
    }

    /**
     * 生成userid.
     *
     * @return string
     */
    public function makeUserId(): string
    {
        while ('' == ($userId = $this->createUuid()));

        return $userId;
    }

    /**
     * @param Request $request
     * @param string $userId
     * @return User
     */
    public function createUser(Request $request, string $userId): User
    {
        $fillUser = [
            'uuid' => $userId,
            'user_pseudo_id' => '',
        ];
        $user = $this->UserModel();
        $user->fill($fillUser);
        $user->save();

        return $user;
    }

    /**
     * 生成token.
     *
     * @param User $user
     *
     * @return string
     */
    public function createTokenFromUser(User $user): string
    {
        return JWTAuth::FromUser($user);
    }

    public function guard()
    {
        return Auth::guard('api');
    }

    public function getUser()
    {
        $user = $this->guard()->user();

        return $user ?? null;
    }

    public function getUserUUID()
    {
        return $this->getUser()['uuid'] ?? null;
    }

}
