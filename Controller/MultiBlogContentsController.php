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
 * MultiBlogContentsController
 *
 * @package MultiBlog.Controller
 * @property MultiBlogContent $MultiBlogContent
 * @property Content $Content
 * @property CookieComponent $Cookie
 * @property BcAuthComponent $BcAuth
 * @property BcAuthConfigureComponent $BcAuthConfigure
 * @property BcContentsComponent $BcContents
 * @property BcMessageComponent $BcMessage
 */
class MultiBlogContentsController extends AppController {

/**
 * コンポーネント
 *
 * @var array
 */
	public $components = ['Cookie', 'BcAuth', 'BcAuthConfigure', 'BcContents' => ['useForm' => true]];

/**
 * サブメニュー
 *
 * @var array
 */
	public $subMenuElements = ['multi_blog_contents'];

/**
 * ブログを追加する
 *
 * @return string|false
 */
	public function admin_add() {
		$this->autoRender = false;
		if(!$this->request->data) {
			$this->ajaxError(500, '無効な処理です。');
		}
		$this->request->data['MultiBlogContent'] = [
			'content' => 'ブログの説明文が入ります。'
		];
		if ($data = $this->MultiBlogContent->save($this->request->data)) {
			$this->BcMessage->setSuccess(sprintf(
				"マルチブログ「%s」を追加しました。",
				$data['Content']['title']
			));
			return json_encode($data['Content']);
		} else {
			$this->ajaxError(500, '保存中にエラーが発生しました。');
		}
		return false;
	}

/**
 * ブログを更新する
 *
 * @param int $id
 * @return void
 */
	public function admin_edit($id) {
		if(!$this->request->data) {
			$this->request->data = $this->MultiBlogContent->find('first', [
				'conditions' => ['MultiBlogContent.id' => $id]
			]);
		} else {
			if ($data = $this->MultiBlogContent->save($this->request->data)) {
				$this->BcMessage->setSuccess(sprintf(
					"マルチブログ「%s」を更新しました。",
					$data['Content']['title']
				));
				$this->redirect([
					'plugin' => 'multi_blog',
					'controller' => 'multi_blog_contents',
					'action' => 'edit',
					$id
				]);
			} else {
				$this->BcMessage->setError("保存中にエラーが発生しました。入力内容を確認してください。");
			}
		}
		$this->pageTitle = 'マルチブログ編集';
		$this->set('publishLink', $this->request->data['Content']['url']);
	}

/**
 * ブログを削除する
 *
 * @param int $id
 * @return bool
 */
	public function admin_delete() {
		if(empty($this->request->data['entityId'])) {
			return false;
		}
		if($this->MultiBlogContent->delete($this->request->data['entityId'])) {
			return true;
		}
		return false;
	}

/**
 * コピー
 *
 * @return bool
 */
	public function admin_copy() {
		$this->autoRender = false;
		if(!$this->request->data) {
			$this->ajaxError(500, '無効な処理です。');
		}
		$user = $this->BcAuth->user();
		if ($data = $this->MultiBlogContent->copy($this->request->data['entityId'], $this->request->data['title'], $user['id'], $this->request->data['siteId'])) {
			$this->BcMessage->setSuccess(sprintf(
				"マルチブログのコピー「%s」を追加しました。",
				$data['Content']['title']
			));
			return json_encode($data['Content']);
		} else {
			$this->ajaxError(500, '保存中にエラーが発生しました。');
		}
		return false;
	}

}