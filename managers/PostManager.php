<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class PostManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findLatest() : array {
        $userManager = new UserManager();
        $categoryManager = new CategoryManager();

        // $query = $this->db->prepare('SELECT posts.id, posts.title_en, posts.excerpt_en, posts.content_en, posts.title_fr, posts.excerpt_fr, posts.content_fr, posts.created_at, posts.author, posts_categories.category_id FROM posts JOIN posts_categories ON posts.id = posts_categories.post_id GROUP BY posts.id ORDER BY created_at LIMIT 4');
        $query = $this->db->prepare('SELECT * FROM posts ORDER BY created_at LIMIT 4');
        $query->execute();
        $postDB = $query->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        
        foreach($postDB as $postFor) {
            $user = $userManager->findById($postFor["author"]); 
            $categories = $categoryManager->findByPost($postFor["id"]);
            // $category = $categoryManager->findOne($postFor["category_id"]);
            // if ($_SESSION['lang'] === 'fr') {
            //     $title = $postFor["title_fr"];
            //     $excerpt = $postFor["excerpt_fr"];
            //     $content = $postFor["content_fr"];
            // } else {
            //     $title = $postFor["title_en"];
            //     $excerpt = $postFor["excerpt_en"];
            //     $content = $postFor["content_en"];
            // }
            // $post = new Post($title, $excerpt, $content, $user, DateTime::createFromFormat('Y-m-d H:i:s', $postFor["created_at"]), $category);
            $post = new Post($postFor["title"], $postFor["excerpt"], $postFor["content"], $user, DateTime::createFromFormat('Y-m-d H:i:s', $postFor["created_at"]));
            $post->setId($postFor["id"]);
            $post->setCategories($categories);
            $posts[] = $post;
        }
        
        return $posts;
    }

    public function findOne(int $id) : ?Post {
        $query = $this->db->prepare('SELECT posts.id, posts.title_en, posts.excerpt_en, posts.content_en, posts.title_fr, posts.excerpt_fr, posts.content_fr, posts.created_at, posts.author, posts_categories.category_id FROM posts JOIN posts_categories ON posts.id = posts_categories.post_id WHERE id = :id');
        $parameters = [
            "id" => $id,
        ];
        $query->execute($parameters);
        $postDB = $query->fetch(PDO::FETCH_ASSOC);
        $userManager = new UserManager();
        $categoryManager = new CategoryManager();

        if($postDB) {
            if ($_SESSION['lang'] === 'fr') {
                $title = $postDB["title_fr"];
                $excerpt = $postDB["excerpt_fr"];
                $content = $postDB["content_fr"];
            } else {
                $title = $postDB["title_en"];
                $excerpt = $postDB["excerpt_en"];
                $content = $postDB["content_en"];
            }
            $user = $userManager->findById($postDB["author"]);   
            $category = $categoryManager->findOne($postDB["category_id"]);
            $post = new Post($postDB['title'], $postDB['excerpt'], $postDB['content'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $postDB["created_at"]), $category);
            $post->setId($postDB["id"]);

            return $post;
        }

        return null;
    }

    public function findByCategory(int $categoryId) : array {
        $query = $this->db->prepare('SELECT posts.id, posts.title, posts.excerpt, posts.content, posts.created_at, posts.author, posts_categories.category_id FROM posts JOIN posts_categories ON posts.id = posts_categories.post_id WHERE category_id = :id');
        $parameters = [
            "id" => $categoryId,
        ];
        $query->execute($parameters);
        $postDB = $query->fetchAll(PDO::FETCH_ASSOC);
        $userManager = new UserManager();
        $categoryManager = new CategoryManager();

        foreach($postDB as $postFor) {
            $user = $userManager->findById($postFor["author"]);
            $category = $categoryManager->findOne($categoryId);
            $post = new Post($postFor["title"], $postFor["excerpt"], $postFor["content"], $user, DateTime::createFromFormat('Y-m-d H:i:s', $postFor["created_at"]), $category);
            $post->setId($postFor["id"]);
            $posts[] = $post;
        }
        
        return $posts;
    }
}