## 安裝方式

---

`composer require jerry58321/api-transform`

## 概念

---

在 Transforms 路徑下，應該會有2大主要類別，分別為Models、Features。

`Models (Transform)`：定義已存在的Table Schema，可以被Features引用或者作為其它Models類的關聯引用。

`Features (Transform)`：為每一個API功能定義回傳的內容，在職責分明、低耦合度的情況下，Features類可以作為其它Features類的引用。

## 使用方法及範例

```markdown
// IndexController.php

...

public function index()
{
    /** @var Models/LoginLog $loginLog */
    $loginLog = LoginLog::with('user')->get();

    return LoginLogTransform::response(compact('loginLog'));
}
```

```markdown
// Transforms/Models/UserTransform.php

...

class UserTransform extends Transform
{
    public function methodOutputKey(): array
    {
        return [
            'user' => false
        ];
    }

    public function __user(Resources $resource)
    {
        return [
            'account' => $resource->account,
            'name'    => $resource->name,
        ];
    }
}
```

```markdown
// Transforms/Models/LoginLogTransform.php

...

class LoginLogTransform extends Transform
{
    public function methodOutputKey(): array
    {
        return [
            'loginLog' => 'login_log'
        ];
    }

    public function __loginLog(Resources $resources)
    {
        $user = UserTransform::quote(['user' => $resources->user]);

        return array_merge($user, [
            'ip'       => $resources->ip,
            'login_at' => $resources->login_at
        ]);
    }
}
```