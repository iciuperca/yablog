<?php

require_once SYSTEMFOLDER . '/controller.php';
require_once SYSTEMFOLDER . '/form_validation.php';
require_once MODELS . '/post_model.php';

class Post extends Controler {

    private $post;

    public function __construct() {
        parent::__construct();
        $this->post = new Post_Model();
    }

    function listAction() {
        $posts = $this->post->getAllPosts();
        $data['posts'] = $posts;
        $data['_title'] = 'List of Posts';
        $this->render('app/templates/posts/list.php', $data);
    }

    public function showAction($slug) {
        $post = $this->post->getBySlug($slug);
        if (empty($post)) {
            $this->show_404();
        }
        $data['post'] = $post;
        $data['post']['content'] = nl2br($data['post']['content']);
        $data['_title'] = $post['title'];
        $this->render('app/templates/posts/show.php', $data);
    }

    public function newAction() {
        $current_user = $this->session->getItem('user');
        if (is_null($current_user) || !$current_user['is_admin']) {
            $this->show_404();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            extract(array_map('trim', $_POST));
            if (!isset($is_published)) {
                $is_published = false;
            }else {
                $is_published = true;
            }
            $fields = array(
                'title' => array(
                    'label' => 'Title',
                    'value' => $title,
                    'rules' => 'trim|required|isAlphaNum|between[5,255]',
                ),
                'content' => array(
                    'label' => 'Content',
                    'value' => $content,
                    'rules' => 'required|minLength[5]|maxLength[5000]',
                ),
                'is_published' => array(
                    'label' => 'Published',
                    'value' => $is_published,
                    'rules' => 'trim',
                ),
            );


            $validation = new FormValidator($fields);
            if (!$validation->run()) {
                $data = compact('title', 'content', 'is_published');
                $data['_title'] = 'Create new post';
                $data['errors'] = $validation->getErrors();
                $this->render('app/templates/posts/create.php', $data);
            } else {

                $slug = $this->post->insert($title, $content, $current_user['user_id'], $is_published);

                $this->session->setFlash('success', 'Post Created');
                Url::redirect('/post/edit/'.$slug);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data['_title'] = 'Create new post';
            $hasError = $this->session->hasFlash('error');
            if ($hasError) {
                $data['errors'] = $this->session->getFlash('error');
            }
            $this->render('app/templates/posts/create.php', $data);
        }
    }
    
        public function editAction($slug) {
        $current_user = $this->session->getItem('user');
        if (is_null($current_user) || !$current_user['is_admin']) {
            $this->show_404();
        }
        
        $ed_post = $this->post->getBySlug($slug);
        
        if(empty($ed_post)) {
            $this->show_404();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            extract(array_map('trim', $_POST));
            if (!isset($is_published)) {
                $is_published = false;
            }else {
                $is_published = true;
            }
            $fields = array(
                'title' => array(
                    'label' => 'Title',
                    'value' => $title,
                    'rules' => 'required|isAlphaNum|between[5,255]',
                ),
                'content' => array(
                    'label' => 'Content',
                    'value' => $content,
                    'rules' => 'required|minLength[5]|maxLength[5000]',
                ),
            );


            $validation = new FormValidator($fields);
            if (!$validation->run()) {
                $data = compact('title', 'content', 'is_published');
                $data['_title'] = 'Edit post';
                $data['errors'] = $validation->getErrors();
                $this->render('app/templates/posts/create.php', $data);
            } else {

                $this->post->update($title, $content, $slug, $is_published);

                $this->session->setFlash('success', 'Post updated');
                Url::redirect('/post/edit/'.$slug);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data['_title'] = 'Edit post';
            $data['title'] = $ed_post['title'];
            $data['content'] = $ed_post['content'];
            $data['is_published'] = $ed_post['is_published'];
            $this->render('app/templates/posts/create.php', $data);
        }
    }


}