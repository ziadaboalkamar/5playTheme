<?php
/**
 * @package  DTAppsScrapper
 */
namespace Inc\Base;

class DeActivate {
    function __construct() {
        flush_rewrite_rules();
        // remove all schedule event
    }
}