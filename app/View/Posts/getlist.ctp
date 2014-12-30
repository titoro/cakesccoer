<!-- app/View/Posts/getlist.ctp -->
<table>
<tr>
	<th><?php echo $this->Paginator->sort('id','記事ID'); ?></th>
	<th><?php echo $this->Paginator->sort('name','タイトル'); ?></th>
	<th><?php echo $this->Paginator->sort('body','本文'); ?></th>
</tr>
<?php foreach($data as $row): ?>
<tr>
	<td><?php echo h($row['Post']['id']); ?></td>
	<td><?php echo h($row['Post']['name']); ?></td>
	<td><?php echo h($row['Post']['body']); ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php echo $this->Paginator->counter(); ?><br />
<?php echo $this->Paginator->prev('前へ'); ?><br />
<?php echo $this->Paginator->numbers(); ?><br />
<?php echo $this->Paginator->next('次へ'); ?><br />
