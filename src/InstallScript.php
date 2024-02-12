<?php

namespace Dyalnu\Curdmix;

class InstallScript
{
    public static function run()
    {
        // المسار إلى الملف المصدر
        $sourceFile = base_path('vendor/dyalnu/curdmix/src/Console/Commands/MakeAllCommand.php');

        // المسار إلى الملف الهدف
        $destinationFile = base_path('app/Console/Commands/MakeAllCommand.php');

        // يتم نقل الملف
        if (!copy($sourceFile, $destinationFile)) {
            echo "فشل في نقل الملف.";
        } else {
            echo "تم نقل الملف بنجاح.";
        }
    }
}
