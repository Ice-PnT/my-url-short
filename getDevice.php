<?php

function getDevice(){
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    if (strpos($user_agent, 'iPhone') || strpos($user_agent, 'Android')) return 'Mobile';
    elseif (strpos($user_agent, 'x64')) return 'Desktop';
    return '';
}