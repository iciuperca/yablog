<?php

require_once SYSTEMFOLDER . '/model.php';

/**
 * Model class for the posts table
 */
class Post_Model extends Model {

    protected $_table;
    protected $_pk;

    public function __construct() {
        parent::__construct();

        $this->_table = 'posts';
        $this->_pk = 'post_id';
    }

    /**
     * Return an array of all the posts matching the query
     * @return array The result of teh query
     */
    function getAllPosts() {
        $posts = $this->getAssocArray('SELECT * FROM posts');
        return $posts;
    }

    /**
     * Search for a post based on the slug
     * @param string $slug The slug of the post
     * @return array
     */
    public function getBySlug($slug) {
        $sql = "SELECT * FROM `posts`
            WHERE `slug`=?
            LIMIT 1
            ";
        $post = $this->getAssocRow($sql, $slug);

        return $post;
    }

    public function insert($title, $content, $user_id, $is_published = false) {
        $last_id = $this->getMaxId($this->_table, $this->_pk);
        $next_id = ++$last_id;
        $sql = "
            INSERT INTO {$this->_table}
            (
                `user_id` ,
                `title` ,
                `slug` ,
                `preview` ,
                `content` ,
                `is_published`
            )
            VALUES
            (
                :user_id ,
                :title ,
                :slug ,
                :preview ,
                :content ,
                :is_published
            )
            ";
        $post = array(
            ':user_id' => $user_id,
            ':title' => $title,
            ':slug' => $this->slugify($next_id.'-'.$title),
            ':preview' => $this->truncate($content, 300),
            ':content' => $content,
            ':is_published' => $is_published,
            
        );
        $pdo = $this->prepare($sql);
        $pdo->execute($post);
        
        return $post[':slug'];
    }
    
        public function update($title, $content, $slug, $is_published = false) {
        $sql = "
            UPDATE {$this->_table} SET
            
                `title`=:title ,
                `preview`=:preview ,
                `content`=:content ,
                `is_published`=:is_published
            WHERE `slug`=:slug
            ";
        $post = array(
            ':title' => $title,
            ':preview' => $this->truncate($content, 300),
            ':content' => $content,
            ':is_published' => $is_published,
            ':slug' => $slug,
            
        );
        $pdo = $this->prepare($sql);
        $pdo->execute($post);

    }

    private function truncate($string, $width) {
        $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
        $parts_count = count($parts);

        $length = 0;
        $last_part = 0;
        for (; $last_part < $parts_count; ++$last_part) {
            $length += strlen($parts[$last_part]);
            if ($length > $width) {
                break;
            }
        }

        return implode(array_slice($parts, 0, $last_part));
    }

}