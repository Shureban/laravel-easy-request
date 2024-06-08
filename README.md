# Laravel easy request

## Installation

Require this package with composer using the following command:

```bash
composer require shureban/laravel-easy-request
```

Add the following class to the `providers` array in `config/app.php`:

```php
Shureban\LaravelEasyRequest\EasyRequestServiceProvider::class,
```

You can also publish the config file to change implementations (ie. interface to specific class).

```shell
php artisan vendor:publish --provider="Shureban\LaravelEasyRequest\EasyRequestServiceProvider"
```

## How to use

All what you need, that is type PhpDoc for your request class.

For example:

```php
/**
 * @method string name()
 * @method boolean isConfirmed()
 * @method bool isAdult()
 * @method integer age()
 * @method int size()
 * @method float salary()
 * @method array workingDays()
 * @method mixed description()
 * @method additionalInformation()
 * @method string|null managerName()
 * @method DateTime birthday()
 * @method Carbon firstWorkingDay()
 */
class CustomRequest extends \Illuminate\Foundation\Http\FormRequest
{
}

class RegistrationController extends Controller
{
    public function __invoke(CustomRequest $request): JsonResponse
    {
        dd(
            $request->name(), // return value with string type
            $request->isConfirmed(), // return value with bool type
            $request->isAdult(), // return value with bool type
            $request->age(), // return value with integer type
            $request->size(), // return value with integer type
            $request->salary(), // return value with float type
            $request->workingDays(), // return array value
            $request->description(), // return original value
            $request->additionalInformation(), // return original value
            $request->managerName(), // return string value or NULL
            $request->birthday(), // return date with type DateTime
            $request->firstWorkingDay(), // return date as Carbon type
        );    
    }
}
```

That is not a problem if you methods written in camelCase format and your request data in snake_case. You may use any of
cases to get your value.

For example:

```php
/**
 * @method int userId()
 * @method int client_id()
 */
class CustomRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'int'],
            'clientId' => ['required', 'int'],
        ];
    }
}

class RegistrationController extends Controller
{
    public function __invoke(CustomRequest $request): JsonResponse
    {
        dd(
            $request->userId(), // return value for field user_id
            $request->client_id(), // return value for field clientId
        );    
    }
}
```

If you need to work with models, and get model by field_id, that is easy.

For example:

```php
/**
 * @method User user()
 */
class CustomRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'int'],
        ];
    }
}

class RegistrationController extends Controller
{
    public function __invoke(CustomRequest $request): JsonResponse
    {
        dd(
            $request->user(), // return instance of models User
        );    
    }
}
```

You have to name your property with _id ending or Id(in camel case), and set method type which is extends Model.

