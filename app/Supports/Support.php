<?php

namespace App\Supports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Support
{
    public static final function genCode($table, $column, $length = 10)
    {
        do {
            $code = Str::random($length);
        } while (DB::table("$table")->where("$column", $code)->first());

        return $code;
    }

    public static final function generateIntUnique($table, $column, $min = 100000, $max = 99999999)
    {
        do {
            $code = random_int($min, $max);
        } while (DB::table("$table")->where("$column", $code)->first());

        return $code;
    }

    public static final function writeJsonFile($filePath, $newData)
    {

        // Chuyển đổi dữ liệu thành định dạng JSON
        $jsonContent = json_encode($newData, JSON_PRETTY_PRINT);

        try {
            // Kiểm tra xem file có tồn tại không
            if (file_exists($filePath)) {
                // Nếu file đã tồn tại, ghi đè dữ liệu trong file đó
                file_put_contents($filePath, $jsonContent);
            } else {
                // Nếu file chưa tồn tại, tạo file mới và ghi dữ liệu vào đó
                file_put_contents($filePath, $jsonContent);
            }

//            return response()->json(['message' => 'File has been written successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error writing file', 'error' => $e->getMessage()], 500);
        }
    }



    public static final function allImageMimeTypes(): array
    {
        return [
            'image/apng',
            'image/avif',
            'image/bmp',
            'image/cgm',
            'image/dicom-rle',
            'image/emf',
            'image/fits',
            'image/g3fax',
            'image/gif',
            'image/heic',
            'image/heic-sequence',
            'image/heif',
            'image/heif-sequence',
            'image/hej2k',
            'image/hsj2k',
            'image/ief',
            'image/jls',
            'image/jp2',
            'image/jpeg',
            'image/jph',
            'image/jphc',
            'image/jpm',
            'image/jpx',
            'image/jxr',
            'image/jxra',
            'image/jxrs',
            'image/jxs',
            'image/jxsc',
            'image/jxsi',
            'image/jxss',
            'image/ktx',
            'image/png',
            'image/sgi',
            'image/svg+xml',
            'image/t38',
            'image/tiff',
            'image/tiff-fx',
            'image/vnd.adobe.photoshop',
            'image/vnd.airzip.accelerator.azv',
            'image/vnd.dece.graphic',
            'image/vnd.djvu',
            'image/vnd.dvb.subtitle',
            'image/vnd.dwg',
            'image/vnd.dxf',
            'image/vnd.fastbidsheet',
            'image/vnd.fpx',
            'image/vnd.fst',
            'image/vnd.fujixerox.edmics-mmr',
            'image/vnd.fujixerox.edmics-rlc',
            'image/vnd.globalgraphics.pgb',
            'image/vnd.microsoft.icon',
            'image/vnd.mix',
            'image/vnd.ms-modi',
            'image/vnd.mozilla.apng',
            'image/vnd.net-fpx',
            'image/vnd.radiance',
            'image/vnd.sealed.png',
            'image/vnd.sealedmedia.softseal.gif',
            'image/vnd.sealedmedia.softseal.jpg',
            'image/vnd.svf',
            'image/vnd.tencent.tap',
            'image/vnd.valve.source.texture',
            'image/vnd.wap.wbmp',
            'image/vnd.xiff',
            'image/vnd.zbrush.pcx',
            'image/webp',
            'image/wmf',
            'image/x-3ds',
            'image/x-cmu-raster',
            'image/x-cmx',
            'image/x-freehand',
            'image/x-icon',
            'image/x-jng',
            'image/x-mrsid-image',
            'image/x-nikon.nef',
            'image/x-pcx',
            'image/x-pict',
            'image/x-portable-anymap',
            'image/x-portable-bitmap',
            'image/x-portable-graymap',
            'image/x-portable-pixmap',
            'image/x-rgb',
            'image/x-tga',
            'image/x-xbitmap',
            'image/x-xpixmap',
            'image/x-xwindowdump'
        ];
    }

    public static final function allImageMimeTypeString(): string
    {
        return implode(',', self::allImageMimeTypes());
    }

    /**
     * @return string
     */
    public static final function now(): string
    {
        Carbon::setLocale(config('app.locale'));
        return Carbon::now('Asia/Ho_Chi_Minh');
    }

    /**
     * @param null $time
     * @return string|null
     */
    public static final function formatTime($time = null): ?string
    {
        if (empty($time)) {
            return null;
        }
        Carbon::setLocale(config('app.locale'));
        return Carbon::parse($time,'Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
    }
}
