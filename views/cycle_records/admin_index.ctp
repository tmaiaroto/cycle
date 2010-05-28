<div class="types index">
    <h2><?php echo $title_for_layout; ?></h2>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Cycle Record', true), array('action'=>'add')); ?></li>
            <li><?php echo $html->link(__('Clear All Cycle Thumbnail Cache', true), array('controller' => 'cycles', 'plugin' => 'cycle', 'admin' => true, 'action'=>'clear_thumbnail_cache')); ?></li>
        </ul>
    </div>

    <table cellpadding="0" cellspacing="0">
    <?php
        $tableHeaders =  $html->tableHeaders(array(
            $paginator->sort('id'),
            $paginator->sort('title'),
            __('Actions', true),
        ));
        echo $tableHeaders;

        $rows = array();
        foreach ($records AS $record) {
            $actions  = $html->link(__('Edit', true), array('plugin' => 'cycle', 'controller' => 'cycle_records', 'action' => 'edit', $record['CycleRecord']['id']));            
            $actions .= ' ' . $layout->adminRowActions($record['CycleRecord']['id']);
            $actions .= ' ' . $html->link(__('Delete', true), array(
                'controller' => 'cycle_records',
                'action' => 'delete',
                $record['CycleRecord']['id'],
                'token' => $this->params['_Token']['key'],
            ), null, __('Are you sure?', true));

            $rows[] = array(
                $record['CycleRecord']['id'],
                $record['CycleRecord']['title'],
                $actions,
            );
        }

        echo $html->tableCells($rows);
        echo $tableHeaders;
    ?>
    </table>
</div>

<div class="paging"><?php echo $paginator->numbers(); ?></div>
<div class="counter"><?php echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true))); ?></div>
