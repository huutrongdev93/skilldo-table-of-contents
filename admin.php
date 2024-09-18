<?php
Class Table_Of_Contents_Admin {
    static function registerSystem($tab) {
        $tab['toc'] = [
			'group'     => 'marketing',
            'label'     => 'TOC',
            'description'=> 'Quản lý hiển thị chỉ mục cho bài viết',
            'callback'  => 'Table_Of_Contents_Admin::form',
            'icon'      => '<i class="fad fa-list-ol"></i>'
        ];
        return $tab;
    }
    static function form(\SkillDo\Http\Request $request): void
    {
        $form = form();

        $form->switch('toc[enable]', ['label' => 'Bật / Tắt TOC'], table_of_contents::config('enable'));

        $form->checkbox('toc[headings]', [
            'h1' => 'Tiêu đề H1',
            'h2' => 'Tiêu đề H2',
            'h3' => 'Tiêu đề H3',
            'h4' => 'Tiêu đề H4',
            'h5' => 'Tiêu đề H5',
            'h6' => 'Tiêu đề H6',
        ], ['label' => 'Heading'], table_of_contents::config('headings'));

		Admin::view('components/system-default', [
			'title' => 'Cấu hình',
			'description' => 'Quản lý hiển thị chỉ mục cho bài viết',
			'form' => $form
		]);

        Admin::view('components/system-default', [
            'title' => 'Short Code',
            'description' => 'Short code dùng để nhúng vào các vị trí chưa có mục lục bài viết',
            'form' => function () {
                echo '<div class="box-content">';
                echo Admin::alert('info', 'class_or_id_content là id hoặc class bao bọc nội dung cần tạo mục lục', [
                    'heading' => '[toc content_id=class_or_id_content][/toc]'
                ]);
                echo '</div>';
            }
        ]);
    }
    static function save(\SkillDo\Http\Request $request): void
    {
        $toc = [];

        $data = $request->input('toc');

        $toc['enable'] = (!empty($data['enable'])) ? 1 : 0;

        $toc['headings'] = (!empty($data['headings']) && have_posts($data['headings'])) ? $data['headings'] : [];

        Option::update('table_of_contents_config', $toc);
    }
}

add_filter('skd_system_tab', 'Table_Of_Contents_Admin::registerSystem');
add_action('admin_system_toc_save','Table_Of_Contents_Admin::save');