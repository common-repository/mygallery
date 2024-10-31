<?php
namespace MyGallery\Rest;

use MyGallery\Factories\PostFactory;
use MyGallery\Message\Errors;

/**
 * Rest controller
 * GET /my-gallery/v1/post/{post_id}/ return object with shortcodes
 * PATCH /my-gallery/v1/post/{post_id}/ replace or add shortcodes to post
 *
 * PHP version 7.0
 *
 * @package Models
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT
 */
class ShortcodeController
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
    protected $resource_name = "post";
    /**
     * Post factory
     *
     * @var PostFactory
     */
    protected $factory;
    /**
     * Init function.
     */
    public function __construct(PostFactory $factory)
    {
        $this->factory=$factory;
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
            $this->resource_name . '/(?P<id>[\d]+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'getShortcodes'),
                'permission_callback' => array($this, 'checkPermission'),
                'schema' => array($this, 'getSchema'),
            )
        );
        register_rest_route(
            $this->namespace,
            $this->resource_name . '/(?P<id>[\d]+)',
            array(
                'methods' => 'PATCH',
                'callback' => array($this, 'saveShortcodes'),
                'permission_callback' => array($this, 'checkPermissionPostUpdate'),
                'schema' => array($this, 'getSchema'),
                "args" => array(
                    'id' => array(
                        'validate_callback' => function ($param) {
                            return is_numeric($param);
                        },
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
     * Check if user have rights to update posts.
     *
     * @param WP_REST_Request $request Instance contains info about request.
     * @return void
     */
    public function checkPermissionPostUpdate(\WP_REST_Request $request)
    {
        $post_id = (int) $request['id'];

        if (!current_user_can('edit_post', $post_id)) {
            return new \WP_Error('rest_forbidden', Errors::text('NO_RIGHTS_TO_WRITE'));
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
                    'postId' => array(
                        'description' => \esc_html__('post id', MYGALLERY_PLUGIN_SHORTCODE),
                        'type' => 'integer',
                    ),
                    'status' => array(
                        'description' => \esc_html__('status of shortcode (saved|draft|deleted)', MYGALLERY_PLUGIN_SHORTCODE),
                        'type' => 'integer',
                    ),
                    'shortcodes' => array(
                        'description' => \esc_html__('Array of shortcodes object', MYGALLERY_PLUGIN_SHORTCODE),
                        'type' => 'array',
                        'properties' => array(
                            'code' => array(
                                'type' => 'object',
                            ),
                            'images' => array(
                                'type' => 'array',
                            ),
                            'settings' => array(
                                'type' => 'object',
                            ),
                            '_originalCode' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
            ),
        );
        return $schema;
    }
    /**
     *Function gets array of shotcode objects.
     *
     * @param WP_REST_Request $request Instance contains info about request.
     * @return array
     */
    public function getShortcodes(\WP_REST_Request $request)
    {
        $id = $request['id'];
        $post_data = $this->extractShortcodeData($id);

        $response = $this->prepareResponse($post_data);
        return $response;
    }
    /**
     * Saves shortcode to the post body.
     *
     * @param WP_REST_Request $request
     * @return void
     */
    public function saveShortcodes(\WP_REST_Request $request)
    {
        $body = $request->get_body_params();
      
        $post_id = (int) $request['id'];
        $post = $this->getPost($post_id);
        $escaped_data = $this->escapeShortcodesArray(json_decode($body['shortcodes']));
        $response = $post->updatePostShortcodes($escaped_data);
        return $response;
    }
    /**
     * Escaping received data
     *
     * @param array $shortcodes array of shortcode string and status.
     * @return void
     */
    protected function escapeShortcodesArray(array $shortcodes)
    {
        $escaped_data = [];
        foreach ($shortcodes as $shortcode) {
            $escaped_data[] = (object) array(
                "status" => esc_html($shortcode->status),
                "code" => esc_html($shortcode->code),
            );
        }
        return $escaped_data;
    }
    /**
     * Get shortcode data from post body.
     *
     * @param integer $id Post id.
     * @return object
     */
    protected function extractShortcodeData(int $id)
    {
        $post = $this->getPost($id);
        $response = $post->getShortcode();
        return $response;
    }
    /**
     * Post Factory facade.
     *
     * @param integer $post_id Post id.
     * @return void
     */
    protected function getPost(int $post_id)
    {
        return $this->factory->get($post_id);
    }
    /**
     * Decode response to json.
     *
     * @param object|array|boolean $postData Data that should be send to user.
     * @return string json
     */
    protected function prepareResponse($postData)
    {

        return \json_encode($postData);
    }
}
