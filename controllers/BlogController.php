<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class BlogController extends AbstractController
{
    public function __construct()
    {
        $lang = $_SESSION["lang"];

        parent::__construct("auth", $lang);
    }
    
    public function home() : void
    {
        $data = [];
        $postManager = new PostManager();
        $data['posts'] = $postManager->findLatest();
        $categoryManager = new CategoryManager();
        $data['categories'] = $categoryManager->findAll();
        $this->render("home", $data);
    }

    public function category(int $categoryId) : void
    {
        $data = [];
        $categoryManager = new CategoryManager();
        $data['category'] = $categoryManager->findOne($categoryId);
        $postManager = new PostManager();
        $data['posts'] = $postManager->findByCategory($categoryId);
        $categoryManager = new CategoryManager();
        $data['categories'] = $categoryManager->findAll();
        if($data) {
            $this->render("category", $data);
        } else {
            $this->redirect("index.php");
        }
    }

    public function post(int $postId) : void
    {
        $data = [];
        $postManager = new PostManager();
        $data['post'] = $postManager->findOne($postId);
        $categoryManager = new CategoryManager();
        $data['categories'] = $categoryManager->findAll();
        $commentManager = new CommentManager();
        $data['comments'] = $commentManager->findByPost($postId);
        if($data) {
            $this->render("post", $data);
        } else {
            $this->redirect("index.php");
        }
    }

    public function checkComment() : void
    {
        //pour commencer on test le tokenCSRF
        $tokenCSRF = new CSRFTokenManager();
        $verifToken = $tokenCSRF->validateCSRFToken($_POST['csrf-token']);
        if($verifToken) {
            if(isset($_POST['comment'])) {
                $userManager = new UserManager();
                $user = $userManager->findById($_SESSION['id']);
                $postManager = new PostManager();
                $post = $postManager->findOne($_GET['post']);     
                $comment = new Comment(htmlspecialchars($_POST['comment']), $user, $post);
                $commentManager = new CommentManager();
                $commentManager->create($comment);
                $this->redirect("index.php?route=post&post_id={$_GET['post']}");
            } else {
                $this->redirect("index.php?route=comment");
            }
        } else {
            $this->redirect("index.php?route=comment");
        }
        
    }

    public function comment() : void
    {
        $this->render("add-comment", []);
    }
}