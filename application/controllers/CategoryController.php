<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryController extends CI_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('CategoryModel');
		$this->load->library('form_validation');
		$this->load->helper('url');
	}

	public function index() {
		$data['title'] = 'Kategori';
		$data['categories'] = $this->CategoryModel->getCategoryWithItemCount();
		$this->load->view('category/index', $data);
	}

	public function add() {
		$data['title'] = 'Tambah Kategori';
		$this->load->view('category/add', $data);
	}

	public function store() {
		$this->form_validation->set_rules('name', 'Nama Kategori', 'required|is_unique[categories.name]');
		$this->form_validation->set_rules('description', 'Deskripsi', 'required');

		if ($this->form_validation->run() == false) {
			$this->add();
		} else {
			$data = [
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			];

			if ($this->CategoryModel->insert($data)) {
				$this->session->set_flashdata('success', 'Kategori berhasil ditambahkan');
				redirect('category');
			} else {
				$this->session->set_flashdata('error', 'Terjadi kesalahan saat menambahkan kategori');
				redirect('category/add');
			}
		}
	}

	public function edit($id) {
		$data['title'] = 'Edit Kategori';
		$data['category'] = $this->CategoryModel->getById($id);

		if (!$data['category']) {
			$this->session->set_flashdata('error', 'Kategori tidak ditemukan');
			redirect('category');
		}

		$this->load->view('category/edit', $data);
	}

	public function update($id) {
		$category = $this->CategoryModel->getById($id);
		if (!$category) {
			$this->session->set_flashdata('error', 'Kategori tidak ditemukan');
			redirect('category');
		}

		// Check if name is changed
		if ($this->input->post('name') != $category->name) {
			$this->form_validation->set_rules('name', 'Nama Kategori', 'required|is_unique[categories.name]');
		} else {
			$this->form_validation->set_rules('name', 'Nama Kategori', 'required');
		}
		$this->form_validation->set_rules('description', 'Deskripsi', 'required');

		if ($this->form_validation->run() == false) {
			$this->edit($id);
		} else {
			$data = [
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'updated_at' => date('Y-m-d H:i:s')
			];

			if ($this->CategoryModel->update($id, $data)) {
				$this->session->set_flashdata('success', 'Kategori berhasil diubah');
				redirect('category');
			} else {
				$this->session->set_flashdata('error', 'Terjadi kesalahan saat mengubah kategori');
				redirect('category/edit/' . $id);
			}
		}
	}

	public function delete($id) {
		$category = $this->CategoryModel->getById($id);
		if (!$category) {
			$this->session->set_flashdata('error', 'Kategori tidak ditemukan');
			redirect('category');
		}

		if ($this->CategoryModel->delete($id)) {
			$this->session->set_flashdata('success', 'Kategori berhasil dihapus');
		} else {
			$this->session->set_flashdata('error', 'Terjadi kesalahan saat menghapus kategori');
		}
		redirect('category');
	}
}


