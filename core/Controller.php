<?php
namespace Application\Core;

abstract class Controller
{
    /**
     * ارسال پاسخ JSON به کلاینت
     *
     * @param mixed $data داده‌ای که می‌خواهیم به JSON تبدیل شود
     * @param int $statusCode کد وضعیت HTTP (مثلا 200، 404، 500)
     * @return void
     */
    protected function jsonResponse($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /**
     * ریدایرکت به یک آدرس (برای API معمولا کمتر استفاده می‌شود)
     *
     * @param string $url
     * @return void
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    // اگر بخوای ویو بارگذاری کنی می‌تونی متد loadView اضافه کنی
    // این پروژه API هست، پس معمولا نیازی نیست
}
