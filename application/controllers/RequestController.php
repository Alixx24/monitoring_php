<?php

namespace App\Controllers;


use Application\Core\Controller;
use Application\Core\Database;
use Application\Model\RequestModel;
use Application\Model\User;
use Exception;

class RequestController extends Controller
{

    private $requestModel;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $database = Database::getInstance($config['db']);
        $this->requestModel = new RequestModel($database);
    }

    public function index()
    {
        $requests = $this->requestModel->getAllRequests();

        require __DIR__ . '/../views/panel/requests/index.php';
    }

    public function create()
    {


        require __DIR__ . '/../views/panel/requests/create.php';
    }
public function store()
{
    session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method Not Allowed');
    }

    if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }

    
    $url = filter_var(trim($_POST['url'] ?? ''), FILTER_VALIDATE_URL);
    $time = trim($_POST['time'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $name = trim($_POST['name'] ?? '');

    if (!$url || !$email || empty($name)) {
        $_SESSION['error'] = 'لطفا تمام فیلدها را به درستی پر کنید.';
        header("Location: /request/create");
        exit();
    }

    $data = [
        'url' => $url,
        'time' => $time,
        'email' => $email,
        'name' => $name,
        'time' => $time, 
    ];

    try {
        $requests = $this->requestModel->createRequest($data);

        if ($requests) {
            try {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlErr = curl_error($ch);
                curl_close($ch);

                if ($response === false) {
                    $_SESSION['warning'] = "ارسال درخواست به url با خطا مواجه شد: $curlErr";
                } elseif ($httpCode < 200 || $httpCode >= 300) {
                    $_SESSION['warning'] = "ثبت با موفقیت انجام شد، اما پاسخ HTTP از URL موفق نبود. کد وضعیت: $httpCode";
                } else {
                    $_SESSION['success'] = "ثبت با موفقیت انجام شد و ارسال درخواست به URL موفق بود.";
                }
            } catch (Exception $e) {
                $_SESSION['warning'] = "ارسال درخواست به URL با خطا مواجه شد.";
            }
        } else {
            $_SESSION['error'] = 'خطا در ایجاد درخواست.';
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "خطا در پردازش درخواست: " . $e->getMessage();
    }

    header("Location: /request/requests");
    exit();
}


  public function sendRequestToUrl()
    {


        require __DIR__ . '/../views/panel/requests/create.php';
    }

}
