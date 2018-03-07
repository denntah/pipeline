# PHP Pipeline
A micro framework for PHP to simplify requests and reponses.

### Simple usage
```
use Pipeline\App;
$app = App:init();

$app->get('/', function() {
    $this->status(418);
});

$app->run();
```

## Setup
```
use Pipeline\App;
$app = App:init([
    'templateRoot' => dirname(__DIR__) . '/templates'
]);
```

### Define your endponts
```
$app->get('/api/animals', function() {
    $berries = ['cat','dog'];
    $this->status(200, $berries)
});

$app->get('/user/{id}', function() {
    $userService = new UserService();
    $user = $userService->get($this->args['id']);
    $this->view('/user.php', $user);
});

$app->map('/admin', function($app) {
    $app->use(function($next) {
        if (CurrentUser::get()->isAuthorized())
            $next();
        else
            $this->status(401);
    });

    // path is appended from map (/admin/home)
    $app->get('/home', function() {
        $this->view('/admin/home.php', CurrentUser::get());
    });
});
```