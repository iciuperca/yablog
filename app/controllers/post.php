<?php

require_once SYSTEMFOLDER . '/controller.php';
require_once MODELS . '/post_model.php';

class Post extends Controler {

    private $post;

    public function __construct() {
        parent::__construct();
        $this->post = new Post_Model();
    }

    function listAction() {
        $posts = $this->post->get_all_posts();
        $data['posts'] = $posts;
        $data['title'] = 'List of Posts';
        $this->render('app/templates/posts/list.php', $data);
    }

    public function showAction($slug) {
        $post = $this->post->get_by_slug($slug);
        if(empty($post)) {
            $this->show_404();
        }
        $data['post'] = $post;
        $data['title'] = $post['title'];
        $this->render('app/templates/posts/show.php', $data);

    }

}