<?php
/**
 * Routes
 * Contains main routes for api access
 *
 *
 */

use App\Core\Rest\Router as RestRouter;

$router = new RestRouter($container['router'], $container['config']['rest']);

/**
 * Bootstrap route
 */
$app->get('/', 'core.controller:root')->setName('root');

/**
 * CORS Pre-flight request
 */
$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

/**
 * Student route
 * fired on submit convention (ending step)
 */
$app->post('/student/send/convention', 'ims.convention.controller:submit')->setName('student.convention.send');

/**
 * Admin routes
 */
$app->group('/admin', function () use ($container) {
    //$this->post('/register', 'security.auth.controller:register')->setName('register');
    $this->post('/login', 'security.auth.controller:login')->setName('login');
    $this->post('/auth/refresh', 'security.auth.controller:refresh')->setName('jwt.refresh');
    $this->get('/users/me', 'security.auth.controller:me')
        ->add($container['auth.middleware']()) // Adding auth middleware to create a new "protected" endpoint
        ->setName('users.me');
});


/**
 * Router Documentation
 */

/**
 *         URL          |           CONTROLLER            |     ROUTE
 * ---------------------|---------------------------------|----------------
 * GET /articles        | article.controller:getArticle    | get_articles
 * GET /articles/{id}   | article.controller:getArticles   | get_article
 * POST /articles       | article.controller:postArticle   | post_article
 * PUT /articles/{id}   | article.controller:putArticle    | put_article
 * DELETE /article/{id} | article.controller:deleteArticle | delete_article
 */
//$router->crud('articles', 'article.controller');

// OR

/**
 * $router->cget('articles', 'article.controller', [$options]);
 * $router->get('articles', 'article.controller');
 * $router->post('articles', 'article.controller');
 * $router->put('articles', 'article.controller');
 * $router->delete('articles', 'article.controller');
 */

// With options
/**
 * $options = [
 *      'key' => 'id',
 *      'requirement' => '[0-9]+',
 *      'singular' => 'article'
 * ];
 *
 * $router->crud('articles', 'article.controller', [], $options);
 *
 * OR
 *
 * $router->get('articles', 'article.controller', $options);
 * ...
 */

/***********************************************************/
/* -------------------- SUB RESOURCES -------------------- */
/***********************************************************/

/**
 *                        URL                         |                   CONTROLLER                  |        ROUTE
 * ---------------------------------------------------|-----------------------------------------------|------------------------
 * GET /articles/{article_id}/comments                | ArticleCommentController:getArticleComments   | get_article_comments
 * GET /articles/{article_id}/comments/{comment_id}   | ArticleCommentController:getArticleComment    | get_article_comment
 * POST /articles/{article_id}/comments               | ArticleCommentController:postArticleComment   | post_article_comment
 * PUT /articles/{article_id}/comments/{comment_id}   | ArticleCommentController:putArticleComment    | put_article_comment
 * DELETE /article/{article_id}/comments/{comment_id} | ArticleCommentController:deleteArticleComment | delete_article_comment
 */
//$router->subCrud('articles', 'comments', 'ArticleCommentController');

// OR

/**
 * $router->cgetSub('articles', 'comments', 'article.controller');
 * $router->getSub('articles', 'comments', 'article.controller');
 * $router->postSub('articles', 'comments', 'article.controller');
 * $router->putSub('articles', 'comments', 'article.controller');
 * $router->deleteSub('articles', 'comments', 'article.controller');
 */

// With options
/**
 * $options = [
 *      'parent_key' => 'article_id',
 *      'parent_requirement' => '[0-9]+',
 *      'sub_key' => 'comment_id',
 *      'sub_requirement' => '[0-9]+',
 *      'parent_singular' => 'article',
 *      'sub_singular' => 'comment'
 * ];
 *
 * $router->subCrud('articles', 'comments', 'ArticleCommentController', [], $options);
 *
 * OR
 *
 * $router->getSub('articles', 'comments', 'article.controller', $options);
 * ...
 */
