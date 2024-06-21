<?php
function tocShortCode($atts, $content)
{
    if(table_of_contents::config('enable') == 0) return $content;

    $contentId = empty($atts['content_id']) ? '.object-detail-content' : $atts['content_id'];

    $heading = table_of_contents::config('headings');

    $heading = (have_posts($heading)) ? implode(',', $heading) : '';

    $productHtml = Plugin::partial(TOC_NAME, 'views/toc', [
        'heading' => $heading,
        'contentId' => $contentId
    ]);

    return $productHtml.$content;
}

add_shortcode('toc', 'tocShortCode');