<?php

BModuleRegistry::i()->addModule('DotMesh', array(
    'version' => '0.1.0',
    'bootstrap' => array('callback'=>'DotMesh::bootstrap'),
));

BConfig::i()->add(array(
    'request' => array(
        'module_run_level' => array('DotMesh' => 'REQUIRED'),
    ),
));

class DotMesh extends BClass
{
    public static function bootstrap()
    {
        BFrontController::i()
            ->route('GET /', 'DotMesh_Controller.index')
            ->route('GET /:term', 'DotMesh_Controller.catch_all')


            ->route('GET|POST /a/.action', 'DotMesh_Controller_Account')
            ->route('GET|POST /n/.action', 'DotMesh_Controller_Node')
            
            ->route('GET|POST|PUT|HEAD|OPTIONS /u/:username', 'DotMesh_Controller_User.index')
            ->route('GET|POST|PUT|HEAD|OPTIONS /p/:postname', 'DotMesh_Controller_Post.index')
            ->route('GET|POST|PUT|HEAD|OPTIONS /t/:tagname', 'DotMesh_Controller_Tag.index')

            ->route('^(GET|POST|PUT|HEAD|OPTIONS) /u/([a-zA-Z0-9_]+)\.json$', 'DotMesh_Controller_User.json')
            ->route('^(GET|POST|PUT|HEAD|OPTIONS) /p/([a-zA-Z0-9_]+)\.json$', 'DotMesh_Controller_Post.json')
            ->route('^(GET|POST|PUT|HEAD|OPTIONS) /t/([a-zA-Z0-9_]+)\.json$', 'DotMesh_Controller_Tag.json')

            ->route('^GET /u/([a-zA-Z0-9_]+)\.rss$', 'DotMesh_Controller_User.rss')
            ->route('^GET /p/([a-zA-Z0-9_]+)\.rss$', 'DotMesh_Controller_Post.rss')
            ->route('^GET /t/([a-zA-Z0-9_]+)\.rss$', 'DotMesh_Controller_Tag.rss')

            ->route('^GET /u/([a-zA-Z0-9_]+)\.(png|jpg|gif)$', 'DotMesh_Controller_User.thumb')
        ;

        BLayout::i()
            ->addView('head', array('view_class'=>'BViewHead'))
            ->addAllViews('views')

            ->addLayout(array(
                'base' => array(
                    array('hook', 'head', 'views'=>array('head')),
                    array('hook', 'header', 'views'=>array('header')),
                    array('hook', 'footer', 'views'=>array('footer')),
                    array('view', 'head', 'do'=>array(
                        array('js', '{DotMesh}/css/dotmesh.css'),
                        array('js', '//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js'),
                        //array('js', '{DotMesh}/js/jquery.min.js'),
                        array('js', '{DotMesh}/js/dotmesh.js'),
                    )),
                ),
                '/' => array(
                    array('layout', 'base'),
                    array('hook', 'main', 'views'=>array('home')),
                ),
                '/setup' => array(
                    array('layout', 'base'),
                    array('hook', 'main', 'views'=>array('setup')),
                ),
                '/signup' => array(
                    array('layout', 'base'),
                    array('hook', 'main', 'views'=>array('signup')),
                ),
                '/search' => array(
                    array('layout', 'base'),
                    array('hook', 'main', 'views'=>array('search')),
                ),
                '/post' => array(
                    array('layout', 'base'),
                    array('hook', 'main', 'views'=>array('post')),
                ),
                '/user' => array(
                    array('layout', 'base'),
                    array('hook', 'main', 'views'=>array('user')),
                ),
                '/tag' => array(
                    array('layout', 'base'),
                    array('hook', 'main', 'views'=>array('tag')),
                ),
            ));
        ;
    }
}

/************************************************************************/

class DotMesh_Controler_Abstract extends BActionController
{
    public function beforeDispatch()
    {
        if (!parent::beforeDispatch()) {
            return false;
        }
        $r = BRequest::i();
        if (!DotMesh_Model_Node::i()->localNode() && $r->rawPath()!=='/a/setup') {
            BResponse::i()->redirect(BApp::href('a/setup'));
        }
        if (($guest = $r->get('guest_uri'))) {
            DotMesh_Model_User::i()->acceptGuest($guest, $r->get('guest_signature'));
        }
        return true;
    }

    public function afterDispatch()
    {
        parent::afterDispatch();
        BResponse::i()->output();
    }
}

/************************************************************************/

class DotMesh_Model_UserSub extends BModel
{
    protected static $_origClass = __CLASS__;
    protected static $_table = 'dm_user_sub';
}

class DotMesh_Model_TagSub extends BModel
{
    protected static $_origClass = __CLASS__;
    protected static $_table = 'dm_tag_sub';
}

class DotMesh_Model_PostFeedback extends BModel
{
    protected static $_origClass = __CLASS__;
    protected static $_table = 'dm_post_feedback';
}

class DotMesh_Model_PostTag extends BModel
{
    protected static $_origClass = __CLASS__;
    protected static $_table = 'dm_post_tag';
}

class DotMesh_Model_PostUser extends BModel
{
    protected static $_origClass = __CLASS__;
    protected static $_table = 'dm_post_user';
}

class DotMesh_Model_NodeBlock extends BModel
{
    protected static $_origClass = __CLASS__;
    protected static $_table = 'dm_node_block';
}

class DotMesh_Model_UserBlock extends BModel
{
    protected static $_origClass = __CLASS__;
    protected static $_table = 'dm_user_block';
}

class DotMesh_Migrate extends BClass
{

}