<?php

class Testimonial_Module extends Core_Module_Base 
{

    protected function set_module_info() 
    {
        return new Core_Module_Detail(
            "Testimonial",
            "Allows you to manage user testimonials",
            "Scripts Ahoy!",
            "http://scriptsahoy.com/"
        );
    }
    
    public function build_admin_menu($menu)
    {
        $top = $menu->add('testimonials', 'Testimonials', '/testimonial/statements')->permission('manage_testimonials');
    }

    public function build_admin_permissions($host)
    {
        $host->add_permission_field($this, 'manage_testimonials', 'Manage Testimonials')->renderAs(frm_checkbox)->comment('View and manage site testimonials');
    }

}
