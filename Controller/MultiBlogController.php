<?php
/**
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright (c) baserCMS Users Community <http://basercms.net/community/>
 *
 * @copyright		Copyright (c) baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			MultiBlog.Controller
 * @since			baserCMS v 4.0.0
 * @license			http://basercms.net/license/index.html
 */

/**
 * MultiBlogController
 *
 * @package MultiBlog.Controller
 * @property MultiBlogPost $MultiBlogPost
 */
class MultiBlogController extends AppController {

/**
 * コンポーネント
 *
 * @var array
 */
	public $components = array('BcContents');

/**
 * モデル
 *
 * @var array
 */
	public $uses = array('MultiBlog.MultiBlogPost');

/**
 * フロントの記事一覧を表示
 *
 * @return void
 */
	public function index () {
		$datas = $this->MultiBlogPost->find('all', array('conditions' => array(
			'MultiBlogPost.blog_content_id' => $this->request->params['entityId']
		)));
		$blogContent = $this->MultiBlogPost->MultiBlogContent->find('first', array('conditions' => array(
			'MultiBlogContent.id' => $this->request->params['entityId']
		)));
		$this->pageTitle = $blogContent['Content']['title'];
		if($this->BcContents->preview == 'default' && $this->request->data) {
			$blogContent = $this->request->data;
		} elseif($this->BcContents->preview == 'alias') {
			$blogContent['Content'] = $this->request->data['Content'];
		}
		if(!$blogContent) {
			$this->notFound();
		}
		$this->set('blogContent', $blogContent);
		$this->set('datas', $datas);
		$this->set('editLink', array('plugin' => 'multi_blog', 'admin' => true, 'controller' => 'multi_blog_contents', 'action' => 'edit', $this->request->params['entityId']));
	}

/**
 * フロントの詳細を表示
 *
 * @param $id
 * @return void
 */
	public function view($no) {
		$this->crumbs[] = array('name' => $this->request->params['Content']['title'], 'url' => $this->request->params['Content']['url']);
		$blogContentId = $this->request->params['entityId'];
		$data = $this->MultiBlogPost->find('first', array('conditions' => array(
			'MultiBlogPost.blog_content_id' => $blogContentId,
			'MultiBlogPost.no' => $no
		)));
		$blogContent = $this->MultiBlogPost->MultiBlogContent->find('first', array('conditions' => array(
			'MultiBlogContent.id' => $blogContentId
		)));
		$this->pageTitle = $data['MultiBlogPost']['title'];
		$this->set('blogContent', $blogContent);
		$this->set('data', $data);
		$this->set('editLink', array('plugin' => 'multi_blog', 'admin' => true, 'controller' => 'multi_blog_posts', 'action' => 'edit', $blogContentId, $data['MultiBlogPost']['id']));
	}

}