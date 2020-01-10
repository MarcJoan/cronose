<?php
session_start();
require_once '../controllers/Language.controller.php';
require_once '../controllers/Offer.controller.php';
require_once '../controllers/User.controller.php';
require_once '../models/Model.php';
new Model();

// header('Access-Control-Allow-Origin: *');
// header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// header("Access-Control-Allow-Headers: Origin");

$uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));
$langController = LanguageController::getLang();
$displayLang = $langController['data']['language'];

if (!LanguageController::langExist($uri[0])) {
  array_unshift($uri, $displayLang);
  $uriString = implode("/", $uri);
  header('Location: ' . $uriString);
} else {
  $displayLang = $uri[0];
}

$auxUri = $uri;
array_splice($auxUri, 0, 1);
$auxUriString = implode("/", $auxUri);

if (!isset($_SESSION['user'])) $_SESSION['user'] = null;

$method = strtolower($_SERVER['REQUEST_METHOD']);
switch ($uri[1]){
  case '':
    header('Location: ' . $displayLang . '/home');
    break;
  case 'home':
    include '../views/home.php';
    break;

  case 'market':
    $offers = OfferController::getOffersByLang($displayLang);
    include '../views/market.php';
    break;

  case 'login':
    if ($method == 'post') {
      $_SESSION['user'] = UserController::userLogin($_POST['username'], $_POST['password']);
      echo $_SESSION['user'];
    } else {
      include '../views/login.php';
    }
    break;
  case 'register':
    include '../views/register.php';
    break;

  case 'about-us':
    include '../views/about-us.php';
    break;

  case 'my-works':
    // $dataController = WorkController::getMyOffers();
    include '../views/myWorks.php';
    break;

  case 'logout':
    include '../views/logout.php';
    break;

  case 'chat':
    include '../views/chat.php';
    break;

  case 'wallet':
    include '../views/wallet.php';
    break;

  case 'profile':
    if ( count($uri) == 2 )
      $user = UserController::getProfileInfo($_SESSION['user']->getUsername());
    else if ( count($uri) == 3 )
      $user = UserController::getProfileInfo($uri[2]);
    include '../views/profile.php';
    break;

  case 'card':
    include '../views/card.php';
    break;

  case 'edit-profile':
    include '../views/editProfile.php';
    break;

  case 'new-work':
    include '../views/newWork.php';
    break;

  case 'work':
    include '../views/work.php';
    break;

  case 'preview-work':
    include '../views/previewWork.php';
    break;

  case 'published':
    include '../views/published.php';
    break;

  case 'datatable':
    if ($uri[2] == 'province') {
      include '../views/datatables/province.php';
    };
    if ($uri[2] == 'category') {
      include '../views/datatables/category.php';
    };
    if ($uri[2] == 'company') {
      include '../views/datatables/company.php';
    };
    break;

  default:
    header('Location: /home');
    include '../views/home.php';
    break;
}

?>
