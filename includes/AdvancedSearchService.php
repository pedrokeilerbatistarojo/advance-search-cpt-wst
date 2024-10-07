<?php

namespace includes;

use WP_Query;

class AdvancedSearchService
{
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public static function init(): void
    {
        add_shortcode('search', array(__CLASS__, 'show_filtered_posts'));
    }

    public static function show_filtered_posts($attrs): string
    {
        $attrs = shortcode_atts([
            'post_types' => 'post',   // Default to 'post' if not defined
            'fields'     => '',       // Custom field to filter
            'values'     => '',       // Value to compare the custom field to
        ], $attrs, 'search');

        // Si no se define un campo o un valor, devolver un mensaje
        if (empty($attrs['fields']) || empty($attrs['values'])) {
            return '<p>Please provide a field and value to search on.</p>';
        }

        $query_args = array(
            'post_type' => explode(',', $attrs['post_types']),
            'meta_query' => array(
                array(
                    'key'     => $attrs['fields'],
                    'value'   => $attrs['values'],
                    'compare' => '='
                )
            )
        );

        $posts_query = new WP_Query($query_args);
//        echo '<pre>';
//        var_dump($posts_query->have_posts());
//        echo '</pre>';
//        exit();
        $output = '<div class="list-filtered-posts">';

        if ($posts_query->have_posts()) {
            while ($posts_query->have_posts()) {
                $posts_query->the_post();
                $output .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            }
        } else {
            $output .= '<p>No se encontraron resultados.</p>';
        }

        wp_reset_postdata();

        $output .= '</div>';

        return $output;
    }
}