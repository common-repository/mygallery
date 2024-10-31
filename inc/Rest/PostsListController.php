<?php
namespace MyGallery\Rest;

use MyGallery\Message\Errors;

/**
 * Rest controller
 * GET /my-gallery/v1/post-list/{order_by}/{order} return list of posts (titles & post_id)
 *
 * PHP version 7.0
 *
 * @package Models
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT
 */
class PostsListController
{
    /**
     * Namespace.
     *
     * @var string
     */
    protected $namespace = "my-gallery/v1";
    /**
     * Name of resource.
     *
     * @var string
     */
    protected $resource_name = "posts-list";
    /**
     * Init function.
     */
    public function __construct()
    {
        $this->init();
    }
    /**
     * Initialization.Add action for rest function registration.
     *
     * @return void
     */
    protected function init()
    {
        add_action('rest_api_init', array($this, 'registerRouts'));
    }
    /**
     * Register routes.
     *
     * @return void
     */
    public function registerRouts()
    {
        register_rest_route(
            $this->namespace,
            $this->resource_name . '/(?P<order_by>[a-z]+)' . '/(?P<order>desc|asc)',
            array(
                'method' => 'GET',
                'callback' => array($this, 'getPostsList'),
                'permission_callback' => array($this, 'checkPermission'),
                'schema' => array($this, 'getSchema'),
                'args' => array(
                    'id' => array(
                        'description' => 'post id',
                        'type' => 'integer',
                        'validate_callback' => function ($param) {
                            return is_numeric($param);
                        },
                    ),
                    'title' => array(
                        'description' => 'post title',
                        'type' => 'string',
                    ),
                ),
            )
        );
    }
    /**
     * Check if user have rights to read posts.
     *
     *
     * @return void
     */
    public function checkPermission()
    {

        if (!current_user_can('read')) {
            return new \WP_Error('rest_forbidden', Errors::text('NO_RIGHTS_TO_READ'));
        }

        return true;
    }
    /**
     * Get sample schema for posts list.
     *
     *
     * @return void
     */
    public function getSchema()
    {
        $schema = array(
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'Posts',
            'description' => 'List of posts with ids',
            'type' => 'object',
            'items' => array(
                'type' => 'object',
                'properties' => array(
                    'title' => array(
                        'description' => 'post title',
                        'type' => 'string',
                    ),
                    'id' => array(
                        'description' => 'post id',
                        'type' => 'integer',
                    ),
                ),
            ),
        );
        return $schema;
    }
    /**
     * Get List of posts.
     *
     * @param \WP_REST_Request $request Instance contains info about request.
     * @return void
     */
    public function getPostsList(\WP_REST_Request $request)
    {
        $order = $request['order'];
        $order_by = $request['order_by'];

        $args = array(
            'author' => get_current_user_id(),
            'orderby' => $order_by,
            'order' => $order,
            'posts_per_page' => -1,
            'post_status' => array('publish', 'pending', 'draft', 'future'),

        );
        $posts = get_posts($args);
        $response = $this->prepareResponse($posts);
        return $response;
    }
    /**
     * Prepare object of post titles.
     *
     * @param array $posts Array of WP_Post objects.
     * @return string json
     */
    protected function prepareResponse(array $posts)
    {
        $response = array();
        foreach ($posts as $post) {
            $response[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
            );
        }
        return \json_encode($response);
    }
}
