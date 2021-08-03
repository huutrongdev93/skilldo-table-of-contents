<?php
/**
Plugin name     : Tạo mục lục cho bài viết
Plugin class    : table_of_contents
Plugin uri      : https://sikido.vn
Description     : Tạo mục lục tự động cho bài viết
Author          : SKDSoftware Dev Team
Version         : 1.0.1
 */
define( 'TOC_NAME', 'table-of-contents' );

define( 'TOC_PATH', Path::plugin(TOC_NAME) );

class table_of_contents {

    private $name = 'table_of_contents';

    function __construct() {
    }

    //active plugin
    public function active() {
    }

    //Gở bỏ plugin
    public function uninstall() {}

    static public function config($key = '') {
        $config = ['enable' => 1, 'headings' => ['h2', 'h3', 'h4', 'h5']];
        $table_of_contents_config = Option::get( 'table_of_contents_config' , $config);
        if(have_posts($table_of_contents_config)) $config = array_merge($config, $table_of_contents_config);
        if(!empty($key)) return Arr::get($config, $key);
        return $config;
    }

    static public function assets() {
        Template::asset()->location('footer')->add('toc', TOC_PATH.'/jquery.toc/jquery.toc.min.js');
    }

    static public function render($content) {
        if(table_of_contents::config('enable') == 0) return $content;
        $heading = table_of_contents::config('headings');
        if(have_posts($heading)) {
            $heading = implode(',', $heading);
        }
        else {
            $heading = '';
        }
        ob_start();
        ?>
        <div class="toc-container" id="toc-container">
            <div class="toc-header">
                <p id="toc-header-title">NỘI DUNG BÀI VIẾT</p>
                <div class="toc-show"><span class="fas fa-angle-up"></span></div>
            </div>
            <ol id="toc"></ol>
        </div>
        <style>
            .toc-container {
                border: 2px solid var(--theme-color);
                overflow:hidden;
                padding:10px;
                background: rgba(243,243,243,.95);
            }
            .toc-container .toc-header { position: relative;}
            .toc-container .toc-header p {
                color:#000; font-weight: bold;
            }
            .toc-container .toc-header .toc-show {
                cursor: pointer;
                position: absolute; right: 15px; top:0px; font-style: normal;
            }
            .toc-container ol {
                margin-left: 20px;
                color: #333;
                background: rgba(243,243,243,.95);
                list-style: decimal;
            }
            .toc-container ol li {
                counter-increment: List!important;
            }
            .toc-container ol li a {
                display: block;
                padding: 5px 10px;
                z-index: 10;
                overflow: hidden;
                position: relative;
                -webkit-transition: color .3s;
                transition: color .3s;
                color:#000;
                font-weight: bold;
                font-size: 12px;
            }
        </style>
        <script defer>
            $(function () {
                $("#toc").toc({content:"div.object-detail-content", headings:"<?php echo $heading;?>"});
                $(document).on('click', '#toc li a', function () {
                    let id = $(this).attr('href');
                    $('html, body').animate({
                        scrollTop: $(id).offset().top - 100
                    }, 1000);
                    return false;
                });
                $(document).on('click', '#toc-container .toc-show', function () {
                    $('#toc-container #toc').toggle();
                    return false;
                });
            })
        </script>
        <?php
        $productHtml = ob_get_contents();
        ob_clean();
        ob_end_clean();
        return $productHtml.$content;
    }
}

include_once 'toc-admin.php';

if(!Admin::is() && Template::isPage('post_detail')) {
    add_filter('the_content', 'table_of_contents::render', 1);
    add_action('init', 'table_of_contents::assets');
}
