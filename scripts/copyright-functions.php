<?php
function create_copyright(): string
{
    $year = date('Y');
    $message = 'Copyright &copy; ' . $year. ' Hotel Avalon, Inc.';
    return $message;
};
