<?php

class Testimonial_Statement extends Db_ActiveRecord 
{

    public $table_name = 'testimonial_statements';

    public $has_many = array(
        'images' => array('class_name' => 'Db_File', 'foreign_key' => 'master_object_id', 'conditions' => "master_object_class='Testimonial_Statement' and field='images'", 'order' => 'sort_order, id', 'delete' => true)
    );
    
    public $is_enabled = true;

    public static function create() 
    {
        return new self();
    }

    public function define_columns($context = null) 
    {
        $this->define_column('title', 'Title')->order('asc')->validation()->fn('trim')->required("Please specify the title.");
        $this->define_column('url', 'URL Name')->validation()->fn('trim');
        $this->define_column('author_name', 'Author Name')->defaultInvisible()->validation()->fn('trim');
        $this->define_column('author_company', 'Author Company')->defaultInvisible()->validation()->fn('trim');
        $this->define_column('author_link', 'Author Link')->defaultInvisible()->validation()->fn('trim');
        $this->define_column('submitted_at', 'Date Published');
        $this->define_column('excerpt', 'Excerpt')->defaultInvisible()->validation()->fn('trim');
        $this->define_column('content', 'Content')->defaultInvisible()->validation()->fn('trim');
        $this->define_column('sort_order', 'Sort Order')->defaultInvisible()->validation()->fn('trim')->unique("This sort order is already in use.");
        $this->define_column('is_enabled', 'Enabled');
        $this->define_multi_relation_column('images', 'images', 'Images', '@name')->invisible();
    }

    public function define_form_fields($context = null) 
    {
        $this->add_form_field('title', 'left')->tab('Statement');
        $this->add_form_field('url', 'right')->tab('Statement');
        $this->add_form_field('author_name', 'full')->tab('Author');
        $this->add_form_field('author_company', 'left')->tab('Author');
        $this->add_form_field('author_link', 'right')->tab('Author');


        $content_field = $this->add_form_field('content')->renderAs(frm_html)->size('large')->tab('Statement');
        $content_field->htmlPlugins .= ',save,fullscreen,inlinepopups';
        $content_field->htmlButtons1 = 'save,separator,'.$content_field->htmlButtons1.',separator,fullscreen';
        $content_field->saveCallback('save_code');
        $content_field->htmlFullWidth = true;

        $content_field = $this->add_form_field('excerpt')->renderAs(frm_textarea)->size('small')->tab('Statement');
        
        $this->add_form_field('is_enabled', 'left')->tab('Statement')->renderAs(frm_checkbox)->comment('Check this to display on the front end');
        $this->add_form_field('submitted_at', 'right')->tab('Statement');
        
        $this->add_form_field('images')->renderAs(frm_file_attachments)
            ->noLabel()->tab('Images')
            ->renderFilesAs('image_list')
            ->addDocumentLabel('Add image(s)')
            ->noAttachmentsLabel('There are no images uploaded')
            ->imageThumbSize(170)
            ->fileDownloadBaseUrl(url('admin/files/get/'));
    }

    // Events
    //
    
    public function after_create() 
    {
        Db_DbHelper::query("update testimonial_statements set sort_order=:sort_order where id=:id", array(
            'sort_order' => $this->id,
            'id' => $this->id
        ));

        $this->sort_order = $this->id;
    }
    
    // Service methods
    //

    public static function set_orders($ids, $orders) 
    {
        if (is_string($ids))
            $ids = explode(',', $ids);
            
        if (is_string($orders))
            $orders = explode(',', $orders);

        foreach($ids as $index => $id) 
        {
            $order = $orders[$index];
            
            Db_DbHelper::query("update testimonial_statements set sort_order=:sort_order where id=:id", array(
                'sort_order' => $order,
                'id' => $id
            ));
        }
    }
    
    public static function sort_items($first, $second) 
    {
        if ($first->sort_order == $second->sort_order)
            return 0;
            
        if ($first->sort_order > $second->sort_order)
            return 1;
            
        return -1;
    }    

}