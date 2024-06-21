<?php
/**
Plugin name     : Tạo mục lục cho bài viết
Plugin class    : table_of_contents
Plugin uri      : https://sikido.vn
Description     : Tạo mục lục tự động cho bài viết
Author          : SKDSoftware Dev Team
Version         : 1.1.0
 */
const TOC_NAME = 'table-of-contents';

define('TOC_PATH', Path::plugin(TOC_NAME) );

class table_of_contents {

    private string $name = 'table_of_contents';

    function __construct() {
    }

    //active plugin
    public function active(): void
    {
        Option::update('table_of_contents_config', [
            'enable' => 1,
            'headings' => ['h2', 'h3', 'h4', 'h5']
        ]);
    }

    //Gở bỏ plugin
    public function uninstall(): void
    {
        Option::delete( 'table_of_contents_config');
    }

    static function config($key = '') {
        $config = ['enable' => 1, 'headings' => ['h2', 'h3', 'h4', 'h5']];
        $table_of_contents_config = Option::get( 'table_of_contents_config' , $config);
        if(have_posts($table_of_contents_config)) $config = array_merge($config, $table_of_contents_config);
        if(!empty($key)) return Arr::get($config, $key);
        return $config;
    }

    static function assets(AssetPosition $header, AssetPosition $footer): void
    {
        $header->add('toc', TOC_PATH.'/assets/toc-style.css', ['minify' => true]);
        $footer->add('toc', TOC_PATH.'/jquery.toc/jquery.toc.min.js');
    }

    static function render($content) {

        if(table_of_contents::config('enable') == 0) return $content;

        return tocShortCode([
            'content_id' => '.object-detail-content'
        ], $content);
    }
}

include_once 'short-code.php';
include_once 'admin.php';

if(!Admin::is() && Template::isPage('post_detail')) {
    add_filter('the_content', 'table_of_contents::render', 1);
    add_action('theme_custom_assets', 'table_of_contents::assets', 10, 2);
}
