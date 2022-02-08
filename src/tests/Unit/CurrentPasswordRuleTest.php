<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Rules\CurrentPassword;
use App\User;
use App\Http\Requests\TestRequestCurrentPassword;
use App\Students;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class CurrentPasswordRuleTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @dataProvider dataprovider
     *
     * @return void
     */
    public function test_currentPasswordRule(string $item, string $data, bool $expect)
    {
        $this->markTestSkipped('検証済みスキップ');
        $user = User::first();
        //currentPasswordのバリデーションは、ログインユーザを処理に使用しているので、この操作は必須
        $request = $this->actingAs($user);
        //リクエスト
        $request = new TestRequestCurrentPassword();
        //フォームリクエストのルールをセット
        $rules = $request->rules();
        //itemがチェックする項目名（password_current）
        //dataが比較する値　（password, failure_password）
        $dataList = [$item => $data];

        //比較する処理
        $validator = Validator::make($dataList, $rules);
        $result = $validator->passes();

        //expectは,true,false
        $this->assertEquals($expect, $result);
    }

    public function dataprovider(): array
    {
        return [
            'this password is True' => ['password_current', 'password', true],
            'this password is False' => ['password_current', 'failure_password', false],
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
}
