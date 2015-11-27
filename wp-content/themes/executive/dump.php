<style>
    .custom-widget {
        background-color: grey;
        margin: 5px 0 10px;
        padding: 10px;
        overflow: hidden;
    }
</style>
<?php
add_action('genesis_after_header', 'add_genesis_widget_area');
function add_genesis_widget_area()
{
    genesis_widget_area('custom-widget', array(
        'before' => '<div class="custom-widget widget-area">',
        'after' => '</div>',
    ));
}