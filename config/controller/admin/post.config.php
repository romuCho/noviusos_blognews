<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */





$datas = array(
    'controller_url'  => 'admin/noviusos_blognews/post',
    'model' => 'NoviusDev\\BlogNews\\Model_Post',
    'messages' => array(
        'successfully added' => __('Post successfully added.'),
        'successfully saved' => __('Post successfully saved.'),
        'successfully deleted' => __('The post has successfully been deleted!'),
        'you are about to delete, confim' => __('You are about to delete the post <span style="font-weight: bold;">":title"</span>. Are you sure you want to continue?'),
        'you are about to delete' => __('You are about to delete the post <span style="font-weight: bold;">":title"</span>.'),
        'exists in multiple lang' => __('This post exists in <strong>{count} languages</strong>.'),
        'delete in the following languages' => __('Delete this post in the following languages:'),
        'item deleted' => __('This post has been deleted.'),
        'not found' => __('Post not found'),
        'blank_state_item_text' => __('post'),
    ),
    'tab' => array(
        'iconUrl' => 'static/apps/noviusos_blognews/img/16/post.png',
        'labels' => array(
            'insert' => __('Add a post'),
            'blankSlate' => __('Translate a post'),
        ),
    ),
    'layout' => array(
        'title' => 'title',
        //'id' => 'blog_id',
        'large' => true,
        'medias' => array('medias->thumbnail->medil_media_id'),//'medias->thumbnail->medil_media_id'),

        'save' => 'save',

        'subtitle' => array('summary'),

        'content' => array(
            'expander' => array(
                'view' => 'nos::form/expander',
                'params' => array(
                    'title'   => __('Content'),
                    'nomargin' => true,
                    'options' => array(
                        'allowExpand' => false,
                    ),
                    'content' => array(
                        'view' => 'nos::form/fields',
                        'params' => array(
                            'fields' => array(
                                'wysiwygs->content->wysiwyg_text',
                            ),
                        ),
                    ),
                ),
            ),
        ),

        'menu' => array(
            // user_fullname is not a real field in the database
            __('Meta') => array('field_template' => '{field}', 'fields' => array('author->user_fullname', 'author_alias', 'created_at_date', 'created_at_time', 'read')),
            __('URL (post address)') => array('virtual_name'),
            __('Tags') => array('tags'),
            __('Categories') => array('categories'),
        ),
    ),
    'fields' => function($namespace, $application_name) {
        return array(
            'id' => array (
                'label' => 'ID: ',
                'form' => array(
                    'type' => 'hidden',
                ),
                'dont_save' => true,
                // requis car la clé primaire ne correspond pas (le getter fait le taf mais
                // les mécanismes internes lèvent une exception)
            ),
            'title' => array(
                'label' => 'Titre',
                'form' => array(
                    'type' => 'text',
                ),
                'validation' => array(
                    'required',
                    'min_length' => array(2),
                ),
            ),
            'summary' => array (
                'label' => __('Summary'),
                'template' => '<td class="row-field">{field}</td>',
                'form' => array(
                    'type' => 'textarea',
                    'rows' => '6',
                ),
            ),
            'author_alias' => array(
                'label' => __('Alias: '),
                'form' => array(
                    'type' => 'text',
                ),
            ),
            'virtual_name' => array(
                'label' => __('URL: '),
                'widget' => 'Nos\Widget_Virtualname',
                'template' => '{label}{required} <div class="table-field">{field} <span>&nbsp;.html</span></div>',
                'validation' => array(
                    'required',
                    'min_length' => array(2),
                ),
            ),
            'author->user_fullname' => array(
                'label' => __('Author: '),
                'widget' => 'Nos\Widget_Text',
                'editable' => false,
                'template' => '<p>{label} {field}</p>'
            ),
            'wysiwygs->content->wysiwyg_text' => array(
                'label' => __('Content'),
                'widget' => 'Nos\Widget_Wysiwyg',
                'template' => '{field}',
                'form' => array(
                    'style' => 'width: 100%; height: 500px;',
                ),
            ),
            'medias->thumbnail->medil_media_id' => array(
                'label' => '',
                'widget' => 'Nos\Widget_Media',
                'form' => array(
                    'title' => 'Thumbnail',
                ),
            ),
            'created_at' => array(
                'form' => array(
                    'type' => 'text',
                ),
                'populate' => function($item) {
                    if (\Input::method() == 'POST') {
                        return \Input::post('created_at_date').' '.\Input::post('created_at_time').':00';
                    }
                    return $item->created_at;
                }
            ),
            'created_at_date' => array(
                'label' => __('Created on:'),
                'widget' => 'Nos\Widget_Date_Picker',
                'template' => '<p>{label}<br/>{field}',
                'dont_save' => true,
                'populate' => function($item) {

                    if ($item->created_at && $item->created_at!='0000-00-00 00:00:00')
                        return \Date::create_from_string($item->created_at, 'mysql')->format('%Y-%m-%d');
                    else
                        return \Date::forge()->format('%Y-%m-%d');
                }
            ),
            'created_at_time' => array(
                'label' => __('Created time:'),
                'widget' => 'Nos\Widget_Time_Picker',
                'dont_save' => true,
                'template' => ' {field}</p>',
                'populate' => function($item) {

                    if ($item->created_at && $item->created_at!='0000-00-00 00:00:00')
                        return \Date::create_from_string($item->created_at, 'mysql')->format('%H:%M');
                    else
                        return \Date::forge()->format('%H:%M');
                }
            ),
            'read' => array(
                'label' => __('Read'),
                'template' => '<p>{label} {field} times</p>',
                'form' => array(
                    'type' => 'text',
                    'size' => '4',
                ),
            ),
            'tags' => array(
                'label' => __('Tags'),
                'widget' => 'Nos\Widget_Tag',
                'widget_options' => array(
                    'model'         => $namespace.'Model_Tag',
                    'label_column'  => 'tag_label',
                    'relation_name' => 'tags'
                ),
            ),
            'categories' => array(
                'widget' => 'NoviusDev\BlogNews\Widget_Category_Selector',
                'widget_options' => array(
                    'width' => '250px',
                    'height' => '250px',
                    'namespace' => $namespace,
                    'application_name' => $application_name,
                    'multiple' => '1',
                ),
                'label' => __(''),
                'form' => array(
                ),
                //'dont_populate' => true,
                'before_save' => function($object, $data) use ($namespace) {
                    $object->categories;//fetch et 'cree' la relation
                    unset($object->categories);

                    $category_class = $namespace.'Model_Category';
                    if(!empty($data['categories']))
                    {
                        foreach($data['categories'] as $cat_id)
                        {
                            if (ctype_digit($cat_id) ) {
                                $object->categories[$cat_id] = $category_class::find($cat_id); // @todo: come back after...
                            }
                        }
                    }
                },
            ),
            'save' => array(
                'label' => '',
                'form' => array(
                    'type' => 'submit',
                    'tag' => 'button',
                    'value' => __('Save'),
                    'class' => 'primary',
                    'data-icon' => 'check',
                ),
            ),
        );
    }
);


return $datas;