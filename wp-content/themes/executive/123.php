<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/25/15
 * Time: 10:23 AM
 */

// Add a custom project task type called Design task
function custom_projekt_tasks_task_type($task_types)
{
    array_push($task_types, array(
        'id' => 'my_design_tasks',
        'name' => 'Design task',
        'plural' => 'Design tasks'
    ));
    return $task_types;
}

add_filter('projekt_tasks_task_types', 'custom_projekt_tasks_task_type');


// Create a task target called Stylesheet Correction as a "global" projekt task target
// and put it under Design Tasks
function custom_project_task_target($task_targets)
{
    array_push($task_targets, array(
        'target_type' => 'my_design_tasks',
        'target_id' => 'my_target_id',
        'target_category' => 'Design task',
        'name' => 'Stylesheet Correction',
        'item_name' => 'On all pages',
        'shortening' => 'C',
        'order' => 1,
        'description' => 'Connect this task to the page stylesheet design.'
    ));
    return $task_targets;
}

add_filter('projekt_tasks_targets', 'custom_project_task_target');


// Make the custom project task target appear on every page that uses the page template
// called "page-template-2.php"
function custom_project_task_target_on_page($active_targets, $post)
{
    $page_template_name = get_post_meta($post->ID, '_wp_klasehnemark_page_template', true);
    if ($page_template_name == 'page-template-2.php') {
        array_push($active_targets, array(
            'target_type' => 'my_design_tasks',
            'target_id' => 'my_target_id',
            'item_name' => 'The Stylesheet for the Page Template 2',
            'description' => 'Connect this task to the page stylesheet design
						on Page Template 2.'
        ));
    }
    return $active_targets;
}

add_filter('projekt_tasks_active_targets_on_post', 'custom_project_task_target_on_page', 10, 2);