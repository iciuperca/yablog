<?php

require_once SYSTEMFOLDER . '/model.php';

/**
 * Model class for the posts table
 */

class Post_Model extends Model {

    /**
     * Return an array of all the posts matching the query
     * @return array The result of teh query
     */
    function get_all_posts() {
        $posts = $this->getAssocArray('SELECT post_id, title FROM posts');
        return $posts;
    }

    /**
     * Search for a post based on the slug
     * @param string $slug The slug of the post
     * @return array
     */
    public function get_by_slug($slug) {
        $sql = "SELECT * FROM `posts`
            WHERE `slug`=?
            LIMIT 1
            ";
        $post = $this->getAssocRow($sql, $slug);

        return $post;
    }

}