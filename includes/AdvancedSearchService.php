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
        add_shortcode('search', [__CLASS__, 'show_filtered_posts']);
    }

    public static function show_filtered_posts($attrs): string
    {
        $attrs = shortcode_atts([
            'post_types' => 'post',   // Default to 'post' if not defined
            'fields'     => '',       // Custom field to filter
            'values'     => '',       // Value to compare the custom field to
        ], $attrs, 'search');

        $query_args = [
            'post_type' => explode(',', $attrs['post_types']),
        ];

        if (!empty($attrs['fields']) && !empty($attrs['values'])) {
            $query_args['meta_query'] = [
                [
                    'key'     => $attrs['fields'],
                    'value'   => $attrs['values'],
                    'compare' => '='
                ]
            ];
        }

        $posts_query = new WP_Query($query_args);

        $output = '<div class="list-filtered-posts">';

        if ($posts_query->have_posts()) {
            while ($posts_query->have_posts()) {
                $posts_query->the_post();
                $output .= '<div class="column">';
                $output .=      '<a href="' . get_permalink() . '">';
                $output .=          '<i class="fa fa-graduation-cap" aria-hidden="true"></i>';
                $output .=      '</a>';
                $output .=  '<h3 style="padding: 10px"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                $output .= '</div>';
            }
        } else {
            $output .= '<p>No se encontraron resultados.</p>';
        }

        wp_reset_postdata();

        $output .= '</div>';

        return $output;
    }
}