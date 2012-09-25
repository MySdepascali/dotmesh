<?php

class DotMesh_Controller_Account extends DotMesh_Controler_Abstract
{
    public function action_setup()
    {
        if (DotMesh_Model_Node::i()->localNode()) {
            BResponse::i()->redirect(BApp::href());
        }
        $r = BRequest::i();
        BLayout::i()->view('setup')->set(array(
            'node_uri' => trim($r->httpHost().'/'.$r->webRoot(), '/'),
            'is_https' => $r->https(),
            'is_modrewrite' => $r->modRewriteEnabled(),
        ));
        BLayout::i()->applyLayout('/setup');
    }

    public function action_setup__POST()
    {
        $redirectUrl = BApp::href();
        try {
            if (DotMesh_Model_Node::i()->localNode()) {
                BResponse::i()->redirect(BApp::href());
            }
            $form = BRequest::i()->post('setup');
            $node = DotMesh_Model_Node::i()->setup($form);
        } catch (BException $e) {
            $result = array('status'=>'error', 'message'=>$e->getMessage());
        } catch (Exception $e) {
            $result = array('status'=>'error', 'message'=>$e->getMessage());
        }
        BResponse::i()->redirect(BUtil::setUrlQuery($redirectUrl, $result));
    }

    public function action_login()
    {
        BLayout::i()->applyLayout('/login');
    }

    public function action_login__POST()
    {
        $r = BRequest::i();
        $redirectUrl = $r->referrer() ? $r->referrer() : BApp::href();
        try {
            if ($r->xhr()) {
                $form = $r->json();
            } else {
                $form = $r->post('login');
            }
            if (empty($form['username']) || empty($form['password'])) {
                throw new BException('Missing username or password');
            }
            $user = DotMesh_Model_User::i()->authenticate($form['username'], $form['password']);
            if (!$user) {
                throw new BException('Invalid username or password');
            }
            $user->login();
            $result = array('status'=>'success', 'message'=>'Login successful');
        } catch (BException $e) {
            $result = array('status'=>'error', 'message'=>$e->getMessage());
        } catch (Exception $e) {
            $result = array('status'=>'error', 'message'=>$e->getMessage());
        }
        if ($r->xhr()) {
            BResponse::i()->json($result);
        } else {
            BResponse::i()->redirect(BUtil::setUrlQuery($redirectUrl, $result));
        }
    }

    public function action_signup()
    {
        BLayout::i()->applyLayout('/signup');
        BResponse::i()->output();
    }

    public function action_signup__POST()
    {
        $r = BRequest::i();
        $redirectUrl = BApp::href();
        try {
            if ($r->xhr()) {
                $form = $r->json();
            } else {
                $form = $r->post('signup');
            }
            $user = Denteva_Model_User::i()->signup($form);
            $result = array('status'=>'success', 'message'=>'Sign up successful');
        } catch (BException $e) {
            $result = array('status'=>'error', 'message'=>$e->getMessage());
        } catch (Exception $e) {
            $result = array('status'=>'error', 'message'=>$e->getMessage());
        }
        if ($r->xhr()) {
            BResponse::i()->json($result);
        } else {
            BResponse::i()->redirect(BUtil::setUrlQuery($redirectUrl, $result));
        }
    }

    public function action_password_recover()
    {
        BLayout::i()->applyLayout('/password_recover');
    }

    public function action_password_recover__POST()
    {

        BResponse::i()->redirect(BApp::href());
    }

    public function action_password_reset()
    {
        BLayout::i()->applyLayout('/password_reset');
    }

    public function action_password_reset__POST()
    {

        BResponse::i()->redirect(BApp::href());
    }

    public function action_logout()
    {
        if (($user = DotMesh_Model_User::i()->sessionUser())) {
            $user->logout();
        }
        BResponse::i()->redirect(BApp::href());
    }
}
