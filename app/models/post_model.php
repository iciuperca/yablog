<?php

require_once SYSTEMFOLDER . '/model.php';

/**
 * Model class for the posts table
 */

class Post_Model extends Model {

    function get_all_posts() {
        $posts = $this->getAssocArray('SELECT post_id, title FROM posts');
        return $posts;
    }

    public function get_by_slug($slug) {
        $sql = "SELECT * FROM `posts`
            WHERE `slug`=?
            LIMIT 1
            ";
        $post = $this->getAssocRow($sql, $slug);

        return $post;
    }

}