<?php
Class Table_Of_Contents_Admin {
    static public function registerSystem($tab) {
        $tab['toc'] = [
            'label'     => 'TOC',
            'callback'  => 'Table_Of_Contents_Admin::form',
            'icon' => '<i class="fad fa-list-ol"></i>'
        ];
        return $tab;
    }
    static public function form($ci, $tab) {
        $form = new FormBuilder();
        $form->add('toc[enable]', 'checkbox', ['label' => 'Bật / Tắt TOC', 'single' => true], (table_of_contents::config('enable') == 1) ? 'toc_enable' : '');
        $form->add('toc[headings]', 'checkbox', ['label' => 'Heading', 'options' => [
            'h1' => 'Tiêu đề H1',
            'h2' => 'Tiêu đề H2',
            'h3' => 'Tiêu đề H3',
            'h4' => 'Tiêu đề H4',
            'h5' => 'Tiêu đề H5',
            'h6' => 'Tiêu đề H6',
        ]], table_of_contents::config('headings'));
        ?>
        <div class="col-xs-12 col-md-12">
            <div class="box">
                <div class="box-content" style="padding:10px;"><?php echo $form->html();?></div>
            </div>
        </div>
        <?php
    }
    static public function save($result, $data) {
        $toc = [];
        $toc['enable'] = (!empty($data['toc']['enable'])) ? 1 : 0;
        $toc['headings'] = [];
        if(!empty($data['toc']['headings']) && have_posts($data['toc']['headings'])) {
            $toc['headings'] = $data['toc']['headings'];
        }
        Option::update( 'table_of_contents_config' , $toc);
        return $result;
    }
}

add_filter('skd_system_tab', 'Table_Of_Contents_Admin::registerSystem');
add_filter('system_toc_save','Table_Of_Contents_Admin::save',10,2);