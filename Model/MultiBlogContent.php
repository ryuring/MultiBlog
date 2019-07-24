<?php
/**
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright (c) baserCMS Users Community <http://basercms.net/community/>
 *
 * @copyright		Copyright (c) baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			MultiBlog.Model
 * @since			baserCMS v 4.0.0
 * @license			http://basercms.net/license/index.html
 */

/**
 * MultiBlogContent
 *
 * @package MultiBlog.Model
 * @property MultiBlogPost $MultiBlogPost
 * @property Content $Content
 */
class MultiBlogContent extends AppModel {

/**
 * Behavior Setting
 *
 * @var array
 */
	public $actsAs = ['BcContents'];

/**
 * belongsTo
 *
 * @var array
 */
    public $hasMany = array(
        'MultiBlogPost' => array(
            'className'	=> 'MultiBlog.MultiBlogPost',
            'foreignKey'=> 'blog_content_id',
            'dependent'	=> true
        )
    );

/**
 * バリデーション
 *
 * @var array
 */
	public $validate = array(
		'content' => array(
			array(
				'rule'		=> array('notBlank'),
				'message'	=> 'ブログの内容を入力してください。',
				'required'	=> true
			)
		)
	);

/**
 * ブログをコピーする
 *
 * @param $id
 * @param $title
 * @param $authorId
 * @return bool|mixed
 */
	public function copy($id, $newTitle, $newAuthorId, $newSiteId = null) {
		$blogContent = $this->find('first', [
			'conditions' => ['MultiBlogContent.id' => $id]
		]);
		if(!$blogContent) {
			return false;
		}
		unset($blogContent['MultiBlogContent']['id'], $blogContent['MultiBlogContent']['modified'], $blogContent['MultiBlogContent']['created']);
		$this->getDataSource()->begin();
		$result = $this->save($blogContent['MultiBlogContent']);
		if($result) {
			if(!empty($blogContent['MultiBlogPost'])) {
				$no = 1;
				foreach($blogContent['MultiBlogPost'] as $post) {
					unset($post['id'], $post['modified'], $post['created']);
					$post['blog_content_id'] = $this->id;
					$post['no'] = $no;
					$this->MultiBlogPost->create($post);
					if(!$this->MultiBlogPost->save()) {
						$result = false;
					}
				}
			}
		}
		if ($result) {
			$content = $this->Content->copy($blogContent['Content']['id'], $this->id, $newTitle, $newAuthorId, $newSiteId);
			if($content) {
				$this->getDataSource()->commit();
				return $content;
			}
		}
		$this->getDataSource()->rollback();
		return false;
	}

}