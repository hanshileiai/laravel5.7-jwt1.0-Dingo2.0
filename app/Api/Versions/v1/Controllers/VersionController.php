<?php

namespace App\Api\Versions\v1\Controllers;

use Dingo\Api\Http\Request;
use App\Api\Versions\BaseController;
use App\Api\Models\Version\Version;

class VersionController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['actionVersion'] ]);
    }

    public function actionVersion(Request $request)
    {
        if ($request->has('os_type') xor $request->has('version_code')) {
            $this->responseNotEnoughParam();
        }
        $osType = $request->query('os_type', '');
        $clientVersionCode = $request->query('version_code', 0);

        $appVersionForceUpdate = Version::where('os_type', $osType)
            ->where('version_code', $clientVersionCode)
            ->value('force_update') ?? 0;
        $serverVersion = Version::find(Version::where('os_type', $osType)->max('id'));

        if ($serverVersion) {
            $newVersionCode = $serverVersion->version_code;
            $serverVersion->is_update = 0;
            if ($clientVersionCode < $newVersionCode) {
                // 客户端版本小于数据库版本
                $serverVersion->is_update = 1;
            }
            if($serverVersion->force_update==1){
                // 如果是强制更新，设置弹窗状态默认为 1
                $serverVersion->pop_status = 1;
            }
            // app 当前版本是否强制更新
            $serverVersion->force_update = $appVersionForceUpdate;

            return $this->responseSuccess($serverVersion);
        }

        return $this->responseNotObject();
    }

}
