<?php

class Testimonial_Actions extends Cms_Action_Base 
{
    public function statements() 
    {
    	$statements = Testimonial_Statement::create()
    		->where('is_enabled=1')
    		->order('sort_order');

    	$statements = $statements->find_all();
    	
        $this->data['statements'] = $statements;
    }

	public function on_submit_statement()
	{
		$idea = Testimonial_Statement::create();
        $idea->init_columns_info();
        $idea->is_enabled = false;
		$idea->save($_POST);

        if (!post('no_flash'))
        {
            $message = post('message', 'Testimonial submitted successfully');
            Phpr::$session->flash['success'] = $message;
        }

        if ($redirect = post('redirect'))
            Phpr::$response->redirect($redirect);
	}    
}
