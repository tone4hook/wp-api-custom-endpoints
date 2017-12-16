<?php

// query the posts by param args
function wace_query_posts($param, $flag) {

    $args = $param;

    $query = new WP_Query( $args );

    // this gives the number of pages for the specific query
    // which is useful if you need to create a custom pagination
    // number_of_pages is the JSON response param
    $pages_num = $query->max_num_pages;

    // specify the navbar menu categories
    // example: $cat_include = 'include=4,5,6,10,15';
    $cat_include = '';
    $cat_list = get_categories( $cat_include );
    // specify the sidebar tags
    // example: $tags_include = 'include=4,5,6,10,15';
    $tags_include = '';
    $tags_list = get_tags( $tags_include );

    $cat_all = get_categories();
    $tags_all = get_tags();

    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            foreach ($categories as $cat):
                $cat_names[] = (object) array(
                        'cat_id'=> get_cat_ID($cat->name),
                        'cat_name'=> $cat->name,
                        'cat_slug'=> $cat->slug,
                        'cat_bool'=> $bool
                    );
            endforeach;
        }

        $tags = get_the_tags();
        if ( ! empty( $tags ) ) {
            foreach ($tags as $tag):
                $tag_names[] = (object) array(
                        'tag_id'=> $tag->term_id,
                        'tag_name'=> $tag->name,
                        'tag_slug'=> $tag->slug
                    );
            endforeach;
        }

        switch ($flag) {
            case 'rand':

                $customize_excerpt = get_the_excerpt();

                $data[] = (object) array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'slug' => basename(get_permalink()),
                    'thumbnail' => get_the_post_thumbnail_url( '', 'thumbnail' ),
                    'featured_image' => get_the_post_thumbnail_url( '',  'full' ),
                    'author' => get_author_name(),
                    'date_published' => get_the_date( 'l F j, Y' ),
                    'excerpt' => wace_custom_excerpt($customize_excerpt),
                    'categories' => $cat_names,
                    'tags' => $tag_names,
                    'number_of_pages' => $pages_num
                );
            break;

            case 'home':

                $customize_excerpt = get_the_excerpt();

                $data[] = (object) array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'slug' =>  basename(get_permalink()),
                    'thumbnail' => get_the_post_thumbnail_url( '', 'thumbnail' ),
                    'featured_image' => get_the_post_thumbnail_url( '',  'full' ),
                    'author' => get_author_name(),
                    'date_published' => get_the_date( 'l F j, Y' ),
                    'excerpt' => wace_custom_excerpt($customize_excerpt),
                    'categories' => $cat_names,
                    'tags' => $tag_names,
                    'menu_items' => $cat_list,
                    'sidebar_items' => $tags_list,
                    'categories_all' => $cat_all,
                    'tags_all' => $tags_all
                );
            break;

            case 'cat':

                $customize_excerpt = get_the_excerpt();

                $data[] = (object) array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'slug' =>  basename(get_permalink()),
                    'thumbnail' => get_the_post_thumbnail_url( '', 'thumbnail' ),
                    'featured_image' => get_the_post_thumbnail_url( '',  'full' ),
                    'author' => get_author_name(),
                    'date_published' => get_the_date( 'l F j, Y' ),
                    'excerpt' => wace_custom_excerpt($customize_excerpt),
                    'categories' => $cat_names,
                    'tags' => $tag_names,
                    'number_of_pages' => $pages_num
                );
            break;

            case 'tag':

                $customize_excerpt = get_the_excerpt();

                $data[] = (object) array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'slug' =>  basename(get_permalink()),
                    'thumbnail' => get_the_post_thumbnail_url( '', 'thumbnail' ),
                    'featured_image' => get_the_post_thumbnail_url( '',  'full' ),
                    'author' => get_author_name(),
                    'date_published' => get_the_date( 'l F j, Y' ),
                    'excerpt' => wace_custom_excerpt($customize_excerpt),
                    'categories' => $cat_names,
                    'tags' => $tag_names,
                    'number_of_pages' => $pages_num
                );
            break;

            case 'post':

                $customize_content = strip_shortcodes( get_the_content() );

                $data[] = (object) array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'slug' =>  basename(get_permalink()),
                    'thumbnail' => get_the_post_thumbnail_url( '', 'thumbnail' ),
                    'featured_image' => get_the_post_thumbnail_url( '',  'full' ),
                    'author' => get_author_name(),
                    'date_published' => get_the_date( 'l F j, Y' ),
                    'content' => wace_add_responsive_class($customize_content),
                    'categories' => $cat_names,
                    'tags' => $tag_names
                );
            break;

            default:

                $customize_content = strip_shortcodes( get_the_content() );
                $customize_excerpt = get_the_excerpt();

                $data[] = (object) array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'slug' =>  basename(get_permalink()),
                    'thumbnail' => get_the_post_thumbnail_url( '', 'thumbnail' ),
                    'featured_image' => get_the_post_thumbnail_url( '',  'full' ),
                    'author' => get_author_name(),
                    'date_published' => get_the_date( 'l F j, Y' ),
                    'content' => wace_add_responsive_class($customize_content),
                    'excerpt' => wace_custom_excerpt($customize_excerpt),
                    'categories' => $cat_names,
                    'tags' => $tag_names,
                    'number_of_pages' => $pages_num
                );
        }

        $cat_names = null;
        $tag_names = null;

    endwhile;
    wp_reset_postdata();
    else :

    endif;

    if (empty($data)) {
        $data[] = (object) array(
            'title' => 'Nothing to see here... :('
        );
    }

    return $data;
} // /wace_query_posts