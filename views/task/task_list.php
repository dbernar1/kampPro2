<?php
  add_stylesheet_to_page('project/task_list.css');
  $task_list_options = array();
  if ($cc = $task_list->countComments()) {
    $task_list_options[] = '<span><a href="'. $task_list->getViewUrl() .'#objectComments">'. lang('comments') .'('. $cc .')</a></span>';
  }
?>
<div class="taskList">
<div class="block" id="taskList<?php echo $task_list->getId() ?>">
  <div class="header"><a class="task-list-title" href="<?php echo $task_list->getViewUrl() ?>"><?php echo clean($task_list->getName()) ?></a>
    <?php if ($task_list->isPrivate()) { ?>
    <div class="private" title="<?php echo lang('private task list') ?>"><span><?php echo lang('private task list') ?></span></div>
    <?php } // if ?>
    <div class="task-list-util">
      <?php
      if ($task_list->canEdit(logged_user())) {
        echo '<a href="' . $task_list->getEditUrl() . '"><img title="'.lang( 'edit' ).'" src="'.get_image_url('/icons/edit.png').'" height="12" alt="' . lang('edit') . '" /></a>';
      } // if
      if (ProjectTaskList::canAdd(logged_user(), active_project())) {
        echo '<a href="' . $task_list->getCopyUrl() . '"><img title="'.lang( 'copy' ).'" src="'.get_image_url('/icons/copy.png').'" height="12" alt="' . lang('copy') . '" /></a>';
        echo '<a href="' . $task_list->getMoveUrl() . '"><img title="'.lang( 'move' ).'" src="'.get_image_url( '/icons/move.png' ).'" height="12" alt="' . lang( 'move' ) . '" /></a>';
      } // if
      if ( $task_list->canDelete( logged_user() ) ) {
        echo '<a href="' . $task_list->getDeleteUrl() . '"><img title="'.lang( 'delete' ).'" src="'.get_image_url( '/icons/delete.png' ).'" height="12" alt="' . lang( 'delete' ) . '" /></a>';
      } // if
      ?>
    </div>
  </div>
  <div class="content">
      <?php if (!is_null($task_list->getDueDate())) { ?>
      <div class="dueDate">
        <span><?php echo lang('due date') ?>:</span>
        <?php
        if ($task_list->getDueDate()->getYear() > DateTimeValueLib::now()->getYear()) {
          echo format_date($task_list->getDueDate(), null, 0);
        } else {
          echo format_descriptive_date($task_list->getDueDate(), 0);
        }
        ?>
      </div>
      <?php } // if ?>
<?php if ($task_list->getScore()>0) { ?>
      <div class="score"><span><?php echo lang('score') ?>:</span> <?php echo $task_list->getScore() ?></div>
<?php } // if ?>
<?php if ($task_list->getDescription()) { ?>
  <div class="desc"><?php echo (do_textile($task_list->getDescription())) ?></div>
<?php } // if ?>
<?php if (plugin_active('tags')) { ?>
  <div class="taskListTags"><span><?php echo lang('tags') ?>:</span> <?php echo project_object_tags($task_list, $task_list->getProject()) ?></div>
<?php } ?>
<?php if (is_array($task_list->getOpenTasks())) { ?>
  <div class="openTasks">
    <table class="blank">
      <?php foreach ($task_list->getOpenTasks() as $task) { ?>
      <tr class="<?php odd_even_class($task_list_ln); ?>">
        <!-- Task text and options -->
        <td class="taskText">
          <div class="wrapper">
          <?php echo $task->getText() ?>
          <?php
            $task_options = array();
            if ($task->canEdit(logged_user())) {
              $task_options[] = '<a href="' . $task->getEditUrl() . '"><img title="'.lang( 'edit' ).'" src="'.get_image_url('icons/edit.png').'" height="12" alt="' . lang('edit') . '" /></a>';
            } // if
            if ($task->canDelete(logged_user())) {
              $task_options[] = '<a href="' . $task->getDeleteUrl() . '"><img title="'.lang( 'delete' ).'" src="'.get_image_url('icons/delete.png').'" height="12" alt="' . lang('delete') . '" /></a>';
            } // if
            if ($task->canView(logged_user())) {
              $task_options[] = '<a href="' . $task->getViewUrl($on_list_page) . '"><img title="'.lang( 'view' ).'" src="'.get_image_url('icons/view.png').'" height="12" alt="' . lang('view') . '" /></a>';
            } // if
            if ($cc = $task->countComments()) {
              $task_options[] = '<a href="' . $task->getViewUrl() .'#objectComments">'. lang('comments') .'('. $cc .')</a>';
            }
            if ($task->canChangeStatus(logged_user())) {
              if ($task->isOpen()) {
                $task_options[] = '<a href="' . $task->getCompleteUrl() . '"><img title="'.lang( 'mark task as completed' ).'" src="'.get_image_url('icons/check.png').'" height="12" alt="' . lang('mark task as completed') . '" /></a>';
              } else {
                $task_options[] = '<span>' . lang('open task') . '</span>';
              } // if
            } // if
          ?>
          <?php if (count($task_options)) { ?>
            <div class="options"><?php echo implode(' ', $task_options) ?></div>
          <?php } // if ?>
          
          <?php if ( !is_null( $task->getStartDate() ) ): ?>
          <div class="startDate"><span><?php echo lang('start date') ?>:</span>
            <?php
            if ($task->getStartDate()->getYear() > DateTimeValueLib::now()->getYear()) {
              echo format_date($task->getStartDate(), null, 0);
            } else {
              echo format_descriptive_date($task->getStartDate(), 0);
            }
            ?>
          </div>
          <?php endif ?>

          <?php if ( !is_null( $task->getDueDate() ) ): ?>
          <div class="dueDate">
            <span><?php echo lang('due date') ?>:</span>
            <?php
            if ($task->getDueDate()->getYear() > DateTimeValueLib::now()->getYear()) {
              echo format_date($task->getDueDate(), null, 0);
            } else {
              echo format_descriptive_date($task->getDueDate(), 0);
            }
            ?>
          </div>
          <?php endif ?>

          <?php if ( $task->getAssignedTo() ): ?>
          <div class="task-assigned-to">
            <span class="assignedTo"><?php echo clean( $task->getAssignedTo()->getContact()->getDisplayName() ) ?></span>
          </div>
          <?php endif ?>

          </div>
        </td>
      </tr>
      <?php } // foreach ?>
    </table>
  </div>
  <?php } // if ?>
<?php if ( count( $task_list_options ) || $task_list->canAddTask( logged_user() ) ): ?>
  <div class="options">
    <?php echo implode( ' | ', $task_list_options ) ?>
    <?php
    if ( $task_list->canAddTask( logged_user() ) ) {
      echo '<a href="#" class="add-to-task-list">' . lang( 'add task' ) . '</a>';
      // Data for adding a task through the task list page
      $task = new ProjectTask();
      $task_data = array_var( $_POST, 'task' );
      if ( !is_array( $task_data ) ) {
        $task_data = array();
      } // if
      tpl_assign( 'task', $task );
      tpl_assign( 'task_data', $task_data );
      tpl_assign( 'task_list', $task_list );
      tpl_assign( 'back_to_list', 1 );
      tpl_assign( 'inline_task_form', true );
      // End of data for adding a task through the task list page
      echo '<div class="add-to-task-list" id="add-task-to-list-'.$task_list->getID().'">';
      $this->includeTemplate( get_template_path( 'add_task', 'task' ) );
      echo '</div>';
    } // if
    ?>
  </div>
<?php endif ?>
  <?php if (is_array($task_list->getCompletedTasks())) { ?>
  <div class="completedTasks expand-container-completed">
    <?php echo lang( $on_list_page ? 'completed tasks' : 'recently completed tasks' ), ':'; ?>
    <table class="blank expand-block-completed">
      <?php $counter = 0; foreach ($task_list->getCompletedTasks() as $task) { ?>
      <?php if ($on_list_page || (++$counter <= 5)) { ?>
      <tr>
        <td class="taskText"><?php echo (do_textile('[' .$task->getId() . '] ' . $task->getText())) ?>
        <?php
          $task_options = array();
          if ($task->getCompletedBy()) {
            $task_options[] = '<span class="taskCompletedOnBy">' . lang('completed on by', format_date($task->getCompletedOn()), $task->getCompletedBy()->getCardUrl(), clean($task->getCompletedBy()->getDisplayName())) . '</span>';
          } else {
            $task_options[] = '<span class="taskCompletedOnBy">' . lang('completed on', format_date($task->getCompletedOn())) . '</span>';
          } //if 
          if ($task->canEdit(logged_user())) {
            $task_options[] = '<a href="' . $task->getEditUrl() . '">' . lang('edit') . '</a>';
          } // if
          if ($task->canDelete(logged_user())) {
            $task_options[] = '<a href="' . $task->getDeleteUrl() . '">' . lang('delete') . '</a>';
          } // if
          if ($task->canView(logged_user())) {
            $task_options[] = '<a href="' . $task->getViewUrl($on_list_page) . '">' . lang('view') . '</a>';
          } // if
          if ($cc = $task->countComments()) {
            $task_options[] = '<a href="' . $task->getViewUrl() .'#objectComments">'. lang('comments') .'('. $cc .')</a>';
          }
          if ($task->canChangeStatus(logged_user())) {
              $task_options[] = '<a href="' . $task->getOpenUrl() . '">' . lang('mark task as open') . '</a>';
          } else {
              $task_options[] = '<span>' . lang('completed task') . '</span>';
          } // if
        ?>
        <?php if (count($task_list_options)) { ?>
          <div class="options"><?php echo implode(' | ', $task_options) ?></div>
        <?php } // if ?>
        </td>
      </tr>
      <?php } // if ?>
<?php } // foreach ?>
      <?php if (!$on_list_page && $counter > 5) { ?>
      <tr>
        <td colspan="2"><a href="<?php echo $task_list->getViewUrl() ?>"><?php echo lang('view all completed tasks', $counter) ?></a></td>
      </tr>
      <?php } // if ?>
    </table>
  </div>
<?php } // if (is_array($task_list->getCompletedTasks())) ?>
</div><?php // div class="taskListExpanded" ?>
</div>
</div>
