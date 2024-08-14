<?php

namespace OWC\PDC\Leges\RestAPI\Controllers;

use OWC\PDC\Leges\RestAPI\Repositories\LegesRepository;
use WP_Error;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

class LegesController
{
    protected LegesRepository $repository;

    public function __construct()
    {
        $this->repository = new LegesRepository;
    }

    public function getLeges(WP_REST_Request $request): WP_REST_Response
    {
        $this->repository->addQueryArguments(array_merge(array_filter([
            'posts_per_page' => $request->get_param('limit'),
            'paged' => $request->get_param('page'),
            'post__in' => ! empty($request->get_param('ids')) ? explode(',', $request->get_param('ids')): [],
        ]), $this->addMetaQueryArguments($request)));

        $data = $this->repository->all();
        $query = $this->repository->getQuery();

        return new WP_REST_Response($this->addPaginator($data, $query), 200);
    }

    protected function addMetaQueryArguments(WP_REST_Request $request): array
    {
        $metaKey = $request->get_param('meta_key');
        $metaValue = $request->get_param('meta_value');

        if (empty($metaKey) || empty($metaValue)) {
            return [];
        }

        if (! $this->checkAllowedMetaKeys($metaKey)) {
            return [];
        }

        $metaValue = explode(',', $metaValue);

        return [
            'meta_query' => [
                [
                    'key' => $metaKey,
                    'value' => $metaValue,
                    'compare' => 'IN',
                ],
            ],
        ];
    }

    protected function checkAllowedMetaKeys(string $metaKey): bool
    {
        $allowed = apply_filters('owc/pdc/leges/rest-api/args/allowed-meta-keys', [
            '_pdc-lege-price',
            '_pdc-lege-new-price',
            '_pdc-lege-active-date',
            '_pdc-lege-start-time',
            '_pdc-lege-end-time',
            '_pdc-lege-person-count-threshold',
            '_pdc-lege-exception-price',
        ]);

        return in_array($metaKey, $allowed);
    }

    /**
     * @return WP_REST_Response|WP_Error
     */
    public function getLege(WP_REST_Request $request)
    {
        $id = $request->get_param('id');
        $data = $this->repository->find($id);

        if (! $data) {
            return new WP_Error('no_item_found', sprintf('Item with ID [%d] not found', $id), [
                'status' => 404,
            ]);
        }

        return new WP_REST_Response($data, 200);
    }

    /**
     * @return WP_REST_Response|WP_Error
     */
    public function getLegeBySlug(WP_REST_Request $request)
    {
        $slug = $request->get_param('slug');
        $data = $this->repository->findBySlug($slug);

        if (! $data) {
            return new WP_Error('no_item_found', sprintf('Item with slug [%s] not found', $slug), [
                'status' => 404,
            ]);
        }

        return new WP_REST_Response($data, 200);
    }

    /**
     * Merges a paginator, based on a WP_Query, inside a data array.
     */
    protected function addPaginator(array $data, WP_Query $query): array
    {
        $perPage = $query->get('posts_per_page');
        $page = $query->get('paged');
        $page = $page = (0 == $page || -1 == $perPage) ? 1 : $page; // If $perPage = -1, $page should be 1.

        return array_merge([
            'data' => $data,
        ], [
            'pagination' => [
                'total_count' => (int) $query->found_posts,
                'current_page' => $page,
                'limit' => $perPage,
            ],
        ]);
    }
}
