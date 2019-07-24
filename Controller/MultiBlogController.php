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
	public $components = ['BcContents'];

/**
 * モデル
 *
 * @var array
 */
	public $uses = ['MultiBlog.MultiBlogPost'];

/**
 * フロントの記事一覧を表示
 *
 * @return void
 */
	public function index () {
		$records = $this->MultiBlogPost->find('all', [
			'conditions' => [
				'MultiBlogPost.blog_content_id' => $this->request->params['entityId']],
			'recursive' => -1
		]);
		$blogContent = $this->MultiBlogPost->MultiBlogContent->find('first', [
			'conditions' => [
				'MultiBlogContent.id' => $this->request->params['entityId']],
			'recursive' => 0
		]);
		if($this->BcContents->preview == 'default' && $this->request->data) {
			$blogContent = $this->request->data;
		} elseif($this->BcContents->preview == 'alias') {
			$blogContent['Content'] = $this->request->data['Content'];
		}
		if(!$blogContent) {
			$this->notFound();
		}
		$this->set('blogContent', $blogContent);
		$this->set('records', $records);
		$this->set('editLink', [
			'admin' => true,
			'plugin' => 'multi_blog',
			'controller' => 'multi_blog_contents',
			'action' => 'edit',
			$this->request->params['entityId']
		]);
		$this->pageTitle = $blogContent['Content']['title'];
	}

/**
 * フロントの詳細を表示
 *
 * @param $id
 * @return void
 */
	public function view($no) {
		$this->crumbs[] = [
			'name' => $this->request->params['Content']['title'],
			'url' => $this->request->params['Content']['url']
		];
		$blogContentId = $this->request->params['entityId'];
		$data = $this->MultiBlogPost->find('first', [
			'conditions' => [
				'MultiBlogPost.blog_content_id' => $blogContentId,
				'MultiBlogPost.no' => $no
		]]);
		$blogContent = $this->MultiBlogPost->MultiBlogContent->find('first', [
			'conditions' => [
				'MultiBlogContent.id' => $blogContentId
		]]);
		$this->pageTitle = $data['MultiBlogPost']['title'];
		$this->set('blogContent', $blogContent);
		$this->set('data', $data);
		$this->set('editLink', [
			'admin' => true,
			'plugin' => 'multi_blog',
			'controller' => 'multi_blog_posts',
			'action' => 'edit',
			$blogContentId, $data['MultiBlogPost']['id']
		]);
	}

}