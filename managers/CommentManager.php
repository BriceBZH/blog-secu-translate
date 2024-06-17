<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class CommentManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findByPost(int $postId) : array {
        $query = $this->db->prepare('SELECT * FROM comments WHERE post_id = :postId');
        $parameters = [
            "postId" => $postId
        ];
        $query->execute($parameters);
        $commentDB = $query->fetchAll(PDO::FETCH_ASSOC);
        $comments = [];
        $userManager = new UserManager();
        $postManager = new PostManager();
        
        foreach($commentDB as $com) {
            $user = $userManager->findById($com["user_id"]); 
            $post = $postManager->findOne($com["post_id"]);
            $comment = new Comment($com['content'], $user, $post);
            $comment->setId($com["id"]);
            $comments[] = $comment;
        }

        return $comments;
    }

    public function create(Comment $comment) : void {
        $query = $this->db->prepare('INSERT INTO comments (content, user_id, post_id) VALUES (:content, :userId, :postId)');
        $parameters = [
            "content" => $comment->getContent(),
            "userId" => $comment->getUser()->getId(),
            "postId" => $comment->getPost()->getId(),
        ];

        $query->execute($parameters);

        $comment->setId($this->db->lastInsertId());
    }
}