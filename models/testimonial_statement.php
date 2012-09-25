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
        $this->define_column('slug', 'Slug')->validation()->fn('trim');
        $this->define_column('author_name', 'Author Name')->invisible()->validation()->fn('trim');
        $this->define_column('author_company', 'Author Company')->invisible()->validation()->fn('trim');
        $this->define_column('author_link', 'Author Link')->invisible()->validation()->fn('trim');
        $this->define_column('excerpt', 'Excerpt')->invisible()->validation()->fn('trim');
        $this->define_column('content', 'Content')->invisible()->validation()->fn('trim');
        $this->define_column('sort_order', 'Sort Order')->validation()->fn('trim')->unique("This sort order is already in use.");
        $this->define_column('is_enabled', 'Enabled');
        $this->define_multi_relation_column('images', 'images', 'Images', '@name')->invisible();
    }

    public function define_form_fields($context = null) 
    {
        $this->add_form_field('is_enabled')->tab('Statement')->renderAs(frm_checkbox);
        $this->add_form_field('title', 'left')->tab('Statement')->renderAs(frm_text);
        $this->add_form_field('slug', 'right')->tab('Statement')->renderAs(frm_text);
        $this->add_form_field('author_name', 'full')->tab('Statement')->renderAs(frm_text);
        $this->add_form_field('author_company', 'left')->tab('Statement')->renderAs(frm_text);
        $this->add_form_field('author_link', 'right')->tab('Statement')->renderAs(frm_text);

        $content_field = $this->add_form_field('excerpt')->renderAs(frm_html)->size('small')->tab('Statement');
        $content_field->htmlPlugins .= ',save,fullscreen,inlinepopups';
        $content_field->htmlButtons1 = 'save,separator,'.$content_field->htmlButtons1.',separator,fullscreen';
        $content_field->saveCallback('save_code');
        $content_field->htmlFullWidth = true;

        $content_field = $this->add_form_field('content')->renderAs(frm_html)->size('small')->tab('Statement');
        $content_field->htmlPlugins .= ',save,fullscreen,inlinepopups';
        $content_field->htmlButtons1 = 'save,separator,'.$content_field->htmlButtons1.',separator,fullscreen';
        $content_field->saveCallback('save_code');
        $content_field->htmlFullWidth = true;
        
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