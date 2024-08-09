<?php


namespace App\Supports;


use App\Models\Config;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CMS
{
    public static final function urlBase($url = null)
    {
        $base = env("APP_URL");
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $base = $_SERVER['HTTP_REFERER'];
        } elseif (!empty($_SERVER['HTTP_ORIGIN'])) {
            $base = $_SERVER['HTTP_ORIGIN'];
        }
        $base = $base . $url;
        $base = str_replace(" ", "", $base);
        $base = str_replace("\\", "/", $base);
        $base = str_replace("//", "/", $base);
        $base = str_replace(":/", "://", $base);

        return $base;
    }

    public static final function genCode($table, $column, $length = 10, $isNumber = false)
    {
        $key = Str::random($length);
        if ($isNumber) {
            $key = sprintf("%" . $length . "d", mt_rand(1, 999999));
        }
        $item = DB::table("$table")->where("$column", $key)->first();
        if ($item) {
            self::genCode($table, $column, $length);
        }
        return $key;
    }

    public static final function getFile($path = null)
    {
        return !empty($path)
            ? Storage::disk(self::disk())->url($path)
            : self::defaultImage();
    }

    public static final function disk()
    {
        return config('filesystems.default', 'public');
    }

    public static final function defaultImage()
    {
        return 'https://ui-avatars.com/api/?name=im&color=7F9CF5&background=EBF4FF';
    }


    public static final function uploadFiles($path, $files, $result = [])
    {
        $dt = Carbon::now();
        $dt->format('m-y');
        $path = $path . '/' . $dt->format('m-Y');

        foreach ($files as $file) {
            $result[] = $file->storePublicly(
                "$path", ['disk' => self::disk()]
            );
        }
        return $result;
    }

    public static final function uploadFile($path, UploadedFile $file)
    {
        $dt = Carbon::now();
        $dt->format('m-y');
        $path = $path . '/' . $dt->format('m-Y');

        return $file->storePublicly(
            "$path", ['disk' => self::disk()]
        );
    }

    public static final function deleteFile($file)
    {
        Storage::disk(self::disk())->delete($file);

        return null;
    }

    public static final function updateFile($path, UploadedFile $file, $fileCurrent = null)
    {
        $dt = Carbon::now();
        $dt->format('m-y');
        $path = $path . '/' . $dt->format('m-Y');

        if ($fileCurrent) {
            Storage::disk(self::disk())->delete($fileCurrent);
        }

        return $file->storePublicly(
            "$path", ['disk' => self::disk()]
        );
    }

    public static final function deleteFiles(array $files = [], array $result = [], $keySearch = null)
    {
        foreach ($files as $file) {
            Storage::disk(self::disk())->delete($file);
            if (count($result) == 0) {
                if ($keySearch) {
                    $key = array_search($file, array_column($result, "$keySearch"));
                } else {
                    $key = array_search($file, $result);
                }
                unset($result[$key]);
            }
        }
        return $result;
    }

    public static final function getMimeLink($file)
    {
        try {
            $mimes = ['video/mp4', 'application/x-mpegURL', 'vnd.apple.mpegURL', 'video/x-m4v'];
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $content_mime = $finfo->buffer(file_get_contents($file));
            if (!in_array($content_mime, $mimes)) {
                throw new \Exception('File không đúng định dạng hoặc không truy cập được', 422);
            }

        } catch (\Exception $exception) {
            throw new \Exception('File không đúng định dạng hoặc không truy cập được', 422);
        }

        return $content_mime;
    }

    /**
     * @param $time
     * @return string
     */
    public static final function timeAgo($time = null): string
    {
        \Carbon\Carbon::setLocale(config('app.locale'));
        if (empty($time)) {
            $dateTime = \Carbon\Carbon::now()->locale;
        } else {
            $dateTime = \Carbon\Carbon::parse($time);
        }
        return $dateTime->longAbsoluteDiffForHumans();
    }

    public static final function getAppConfig(): array
    {
       $configs = Config::with([])->whereIn('code', ['logo', 'ico', 'name'])->get()->toArray();
       return $configs;
    }


}
