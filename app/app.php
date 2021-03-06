<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Race.php";
    require_once __DIR__."/../src/CharClass.php";
    require_once __DIR__."/../src/Background.php";
    require_once __DIR__."/../src/Stat.php";
    require_once __DIR__."/../src/Skill.php";
    require_once __DIR__."/../src/Description.php";
    require_once __DIR__."/../src/Character.php";


    session_start();
    if (empty($_SESSION['temporary_character'])) {
        $_SESSION['temporary_character'] = array(

        $_SESSION['race'] => "",

        $_SESSION['class'] => "",

        $_SESSION['background'] => "",

        $_SESSION['str'] => "",
        $_SESSION['dex'] => "",
        $_SESSION['con'] => "",
        $_SESSION['wis'] => "",
        $_SESSION['int'] => "",
        $_SESSION['cha'] => "",

        $_SESSION['name'] => "",
        $_SESSION['age'] => "",
        $_SESSION['gender'] => "",
        $_SESSION['height'] => "",
        $_SESSION['weight'] => "",
        $_SESSION['eye_color'] => "",
        $_SESSION['hair_color'] => "",
        $_SESSION['skin_tone'] => "",
        $_SESSION['alignment'] => "",
        $_SESSION['other_information'] => "",
    );};


    $app = new Silex\Application();
    $app['debug'] = true;


    $server = 'mysql:host=localhost:8889;dbname=dnd';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));


    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();


//landing page
    //renders homepage
    $app->get('/', function() use ($app)
    {
        return $app['twig']->render('home.html.twig', array('characters' => Character::getAll()));
    });



//race page
    //renders race page
    $app->get('/race', function() use ($app)
    {
        return $app['twig']->render('race.html.twig', array('races' => Race::getAll()));
    });

    //carry race id to class page
    $app->post('/class', function() use ($app)
    {
        $_SESSION['race'] = $_POST['race_id'];

        return $app['twig']->render('class.html.twig', array('classes' => CharClass::getAll()));
    });



//class page
    //carry race id and class id to background page
    $app->post('/background', function() use ($app)
    {
        $_SESSION['class'] = $_POST['class_id'];

        return $app['twig']->render('background.html.twig', array('backgrounds' => Background::getAll()));
    });



//background page
    //carry race id, class id, background id to stats page
    $app->post('/stats', function() use ($app)
    {
        $_SESSION['background'] = $_POST['background_id'];


        $race_id = $_SESSION['race'];
        $race_find = Race::find($race_id);
        $race = getName($race_find);

        $class_id = $_SESSION['class'];
        $class_find = CharClass::find($class_id);
        $classname = getName($class_find);


        $stats = statRoll();
        $assigned_stats = assignRolls($six_rolls, $classname, $race);


        return $app['twig']->render('stats.html.twig', array('stat' => Stat::getAll()));
    });




//stats page
    //carry race id, class id, background id, stats id to skills page
    $app->post('/bio', function() use ($app)
    {
        $_SESSION['str'] = $_POST['str_id'];
        $_SESSION['dex'] = $_POST['dex_id'];
        $_SESSION['con'] = $_POST['con_id'];
        $_SESSION['int'] = $_POST['int_id'];
        $_SESSION['wis'] = $_POST['wis_id'];
        $_SESSION['cha'] = $_POST['cha_id'];


        return $app['twig']->render('bio.html.twig');
    });



//bio page
    //render bio page
    $app->get('/bio', function() use ($app)
    {
        return $app['twig']->render('bio.html.twig');
    });

//summary page
    //render summary page
    $app->get('/summary', function() use ($app)
    {
        $_SESSION['name'] = $_POST['name_id'];
        $_SESSION['age'] = $_POST['age_id'];
        $_SESSION['gender'] = $_POST['gender_id'];
        $_SESSION['height'] = $_POST['height_id'];
        $_SESSION['weight'] = $_POST['weight_id'];
        $_SESSION['eye_color'] = $_POST['eye_color_id'];
        $_SESSION['hair_color'] = $_POST['hair_color_id'];
        $_SESSION['skin_tone'] = $_POST['skin_tone_id'];
        $_SESSION['alignment'] = $_POST['alignment_id'];
        $_SESSION['other'] = $_POST['other_id'];

        return $app['twig']->render('summary.html.twig');
    });


return $app;

?>
