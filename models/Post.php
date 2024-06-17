<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class Post
{
    private ? int $id = null;
    private array $categories = [];
    public function __construct(private string $title, private string $excerpt, private string $content, private User $author, private datetime $createdAt) //, private Category $category
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): void
    {
        $this->excerpt = $excerpt;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getCreatedAt(): datetime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(datetime $createdAt): void
    {
        $this->createdAT = $createdAt;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    // public function getCategory(): Category
    // {
    //     return $this->category;
    // }

    // public function setCategory(Category $category): void
    // {
    //     $this->category = $category;
    // }
}