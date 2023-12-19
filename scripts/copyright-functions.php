<?php
declare(strict_types=1);


function create_copyright(): string
{
    $year = date('Y');
    $message = 'Copyright &copy; ' . $year. ' Hotel Avalon, Inc.';
    return $message;
};
/*SIMPLE FUNCTION TO CREATE A COPYRIGHT MARK*/