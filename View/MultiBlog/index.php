<?php
/**
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright (c) baserCMS Users Community <http://basercms.net/community/>
 *
 * @copyright		Copyright (c) baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			MultiBlog.View
 * @since			baserCMS v 4.0.0
 * @license			http://basercms.net/license/index.html
 */

/**
 * @var array $records
 */
?>


<h1><?php echo h($blogContent['Content']['title']) ?></h1>
<p><?php echo h($blogContent['MultiBlogContent']['content']) ?></p>

<?php if($records): ?>
    <ul>
        <?php foreach($records as $record): ?>
            <li>
                <?php echo $this->BcBaser->link($record['MultiBlogPost']['title'], [
                    'plugin' => '',
                    'controller' => $this->request->params['Content']['url'],
                    'action' => 'view',
                    $record['MultiBlogPost']['no']
                ]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php endif ?>

