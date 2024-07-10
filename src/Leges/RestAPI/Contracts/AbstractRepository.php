<?php

namespace OWC\PDC\Leges\RestAPI\Contracts;

use WP_Post;
use WP_Query;

abstract class AbstractRepository
{
    /**
     * Posttype definition
     */
    protected string $posttype = '';

    /**
     * Instance of the WP_Query object.
     *
     * @var null|WP_Query
     */
    protected $query = null;

    /**
     * Arguments for the WP_Query.
     */
    protected array $queryArgs = [];

    public function all(): array
    {
        $args = array_merge($this->queryArgs, [
            'post_type' => [$this->posttype],
            'post_status' => 'publish',
        ]);

        $this->query = new WP_Query($args);

        return array_map([$this, 'transform'], $this->getQuery()->posts);
    }

    public function find(int $id): ?array
    {
        $args = array_merge($this->queryArgs, [
            'p' => $id,
            'post_type' => [$this->posttype],
        ]);

        $this->query = new WP_Query($args);
        $post = $this->getQuery()->post;

        if (! $post instanceof WP_Post) {
            return null;
        }

        return $this->transform($post);
    }

    public function findBySlug(string $slug)
    {
        $args = array_merge($this->queryArgs, [
            'name' => $slug,
            'post_type' => [$this->posttype],
        ]);

        $this->query = new WP_Query($args);
        $post = $this->getQuery()->post;

        if (! $post instanceof WP_Post) {
            return null;
        }

        return $this->transform($post);
    }

    public function getQuery(): ?WP_Query
    {
        return $this->query;
    }

    public function addQueryArguments(array $args): self
    {
        $this->queryArgs = array_merge_recursive($this->queryArgs, $args);

        return $this;
    }

    abstract public function transform(WP_Post $post): array;
}
