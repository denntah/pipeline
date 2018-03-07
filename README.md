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
    $this->status(200, $berries);
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

# Pipeline

## Pipeline\App

Register the pipeline. ```$this``` on the three below will be an instance of ```Pipeline\Context```

### use
```function use($func)```
```$func``` that should be execued on the request. First (and only parameter) ```$next``` is a function to execute the next function in the pipline and continue when that's done.

### map
```function map($path, $func)```
```$path``` that is mathed against the request.
```$func``` that should be execued on the request. First (and only parameter) ```$app``` is an instance of Pipline\App to append sub-endpoint/middlewares to be executed on the current path.

### get/post/put/delete
```function {method}($path, $func)```
```$path``` that is mathed against the request.
```$func``` that should be execued on the request. No parameters is forwarded.


## Pipeline\Context

Will keep information about the current request and can send responses back to the client.

### status
```function status($statusCode, $data = null)```
```$statusCode``` that is sent back to the client.
```$data``` if any, will be parsed as JSON as body.

### view
```function view($view, $data = null)```
```$view``` path to the view to be pressented.
```$data``` if any, will be extracted and available in the view-document.

### redirect
```function view($url, $permanent = true)```
```$url``` where it should be  redirected to.
```$permanent``` if status 301 should be sent, otherwise 302.