<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

class DashboardController extends CI_Controller {
	
	
	public function response($data = NULL) {
		$this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ))->_display();exit();
	}
	
    function loadViews($folder = 'dashboard', $page, $data = array()){

        $this->load->view($folder.'/estructura/header', $data);
        $this->load->view($folder.'/estructura/inter_header', $data);
        $this->load->view($folder.'/pagina/'.$page, $data);
        $this->load->view($folder.'/estructura/inter_footer', $data);
        $this->load->view($folder.'/estructura/footer', $data);
    }

    function paginationCompress($link, $count , $uri_segment = 4 ,$num_links = 10,$per_page = 20, $segment = SEGMENT) {
    	$cantidadResultado = $count;
    	$pagina = $segment;
		$config['base_url'] = $link;
		$config['total_rows'] = $cantidadResultado;
        $config["uri_segment"] = $uri_segment; 
        $config['num_links'] = $num_links; 
        $config['per_page'] = $per_page;
		$config['full_tag_open'] = '<ul class="pagination mt-4">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
	
		$obtieneLimite = ($pagina != 0) ? (int) $pagina : 0 ;
        $limite = $config['per_page'];
        $offset = $obtieneLimite;
        $this->pagination->initialize($config);
	
		return array (
			"limite" => $limite,
			"offset" => $offset
		);
	}

}